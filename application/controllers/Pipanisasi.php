<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipanisasi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->library('Pipeline_access');
        $this->load->model('Pipeline_model');
        if(!$this->session->userdata('pipeline_form_token'))$this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));
    }

    private function period()
    {
        $start = $this->input->get('start', true) ?: date('Y-m-01');
        $end = $this->input->get('end', true) ?: date('Y-m-d');
        foreach (array($start, $end) as $value) {
            $date = is_string($value) ? DateTime::createFromFormat('!Y-m-d', $value) : false;
            if (!$date || $date->format('Y-m-d') !== $value) show_error('Periode tanggal tidak valid.', 422);
        }
        if ($start > $end) show_error('Tanggal awal harus sebelum atau sama dengan tanggal akhir.', 422);
        return array($start, $end);
    }

    private function ready()
    {
        if (!$this->db->table_exists('PipelineDelivery')) show_error('Modul Pipanisasi belum diaktifkan. Jalankan migrasi database/20260928_pipeline_delivery.sql.', 503);
    }

    public function no_access(){ $this->render('pipeline/no_access',array('judul'=>'Akses Belum Diatur')); }

    public function index()
    {
        $this->pipeline_access->require_menu('dashboard');
        $this->ready();
        list($start, $end) = $this->period();
        $this->load->model('Pipeline_billing_model');
        $this->load->model('Pipeline_stock_model','stock');
        $branch=(int)$this->input->get('area');
        if($branch && !$this->Pipeline_billing_model->branch_allowed($branch))show_error('Area di luar akses Anda.',403);
        $stock=$this->stock->summary($start,$end,$branch);
        $deliveries = $this->Pipeline_model->deliveries($start, $end);
        $deliveries=array_values(array_filter($deliveries,function($r)use($branch){return !$branch || (int)$r['SpbbID']===$branch;}));
        $billing_ready = $this->Pipeline_billing_model->ready();
        $cost = array('amount'=>0,'tax'=>0,'pending'=>0,'missing_rates'=>0);
        if ($billing_ready) foreach ($deliveries as $delivery) {
            if ($this->pipeline_finance->reconcile($delivery,$this->Pipeline_billing_model->current_documents($delivery['DeliveryID']))) $cost['pending']++;
            try {
                $rate=$this->Pipeline_billing_model->rate('FUEL_ADMIN',substr($delivery['DeliveryDate'],0,10),$delivery['SpbbID']);
                $vat=$this->Pipeline_billing_model->rate('VAT',substr($delivery['DeliveryDate'],0,10),$delivery['SpbbID']);
                $amount=$this->pipeline_finance->amount($delivery['Quantity']/1000,$rate['Amount']);
                $cost['amount']+=$amount; $cost['tax']+=$this->pipeline_finance->amount($amount/100,$vat['Amount']);
            } catch (Exception $e) { $cost['missing_rates']++; }
        }
        $this->load->model('Pipeline_master_model','dashboard_master');
        $dashboard_vessels=$this->dashboard_master->vessels(false);
        $this->render('pipeline/dashboard', array('judul' => 'Dashboard Pipanisasi', 'start' => $start, 'end' => $end,
            'dashboard_vessels'=>$dashboard_vessels,'stock'=>$stock,'selected_area'=>$branch,'branches'=>$this->Pipeline_billing_model->branches(),'pipeline_token'=>$this->session->userdata('pipeline_form_token'),'orders' => $this->Pipeline_model->orders($start, $end), 'deliveries' => $deliveries,
            'billing_ready'=>$billing_ready,'cost'=>$cost,'monitoring'=>$this->Pipeline_model->monitoring($start,$end,$branch),
            'movements'=>$this->Pipeline_model->movements($start,$end)));
    }

    public function delivery()
    {
        $this->load->model('Pipeline_master_model','masterdata');

        $this->pipeline_access->require_menu('delivery');$this->load->model('Pipeline_spp_delivery_model','spp_delivery');
        $this->load->model('Pipeline_flow_model','flow');
        $deliveryError='';
        $this->ready();
        if (!$this->db->field_exists('BillingCompany', 'PipelineDelivery')) show_error('Jalankan php application/scripts/pipeline_migrate.php --apply untuk mengaktifkan totalizer.', 503);
        $this->load->library('form_validation');
        $this->load->model('Pipeline_billing_model','billing');
        if (!$this->session->userdata('pipeline_form_token')) $this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));
        if ($this->input->method() === 'post') {
            $token=$this->input->post('pipeline_token');
            if (!is_string($token) || !hash_equals($this->session->userdata('pipeline_form_token'),$token)) show_error('Form kedaluwarsa. Muat ulang halaman.',403);
            $this->form_validation->set_rules('OrderID', 'Pesanan', 'integer');
            $this->form_validation->set_rules('SpbbID','Cabang','required|integer');
            $this->form_validation->set_rules('BillingCompany','Perusahaan yang ditagih','required|trim|max_length[200]');
            foreach (array('DONumber' => 100, 'CompanyName' => 200, 'DriverName' => 150, 'VehicleNumber' => 30, 'VesselName' => 200) as $field => $length) {
                $this->form_validation->set_rules($field, $field, 'required|trim|max_length[' . $length . ']');
            }
            $this->form_validation->set_rules('TotalizerStart', 'Totalizer awal', 'required|regex_match[/^\d+(\.\d{1,3})?$/]|less_than[1000000000000]');
            $this->form_validation->set_rules('Quantity', 'Volume (liter)', 'required|numeric|greater_than[0]|less_than[1000000000000]');
            $this->form_validation->set_rules('DeliveryDate', 'Tanggal DO', 'required|callback_valid_date');
            $this->form_validation->set_rules('FillingTime', 'Jam pengisian', array('regex_match[/^([01][0-9]|2[0-3]):[0-5][0-9]$/]'));
            $this->form_validation->set_rules('ReceiptNumber', 'Nomor resi', 'max_length[100]');
            $this->form_validation->set_rules('Notes', 'Catatan', 'max_length[1000]');
            if ($this->form_validation->run()) {
                $branch=(int)$this->input->post('SpbbID');
                if (!$this->billing->branch_allowed($branch)) show_error('Cabang di luar akses Anda.',403);
                $order=null;
                if ($this->input->post('OrderID')!=='') {
                    $this->db->from('SpbbOrder O')->where('O.OrderID',(int)$this->input->post('OrderID'));
                    $this->Pipeline_model->scope(); $order=$this->db->get()->row_array();
                    if (!$order || (int)$order['SpbbID']!==$branch) show_error('Pesanan tidak ditemukan pada cabang terpilih atau di luar akses Anda.',403);
                    if ($order['OrderStatus']==='DITOLAK') show_error('DO tidak dapat dibuat untuk pesanan ditolak.',422);
                }
                $data = array();
                foreach (array('OrderID', 'DONumber', 'DeliveryDate', 'Quantity', 'CompanyName', 'DriverName', 'VehicleNumber', 'VesselName', 'FillingTime', 'ReceiptNumber', 'Notes', 'TotalizerStart', 'BillingCompany') as $field) {
                    $data[$field] = trim((string) $this->input->post($field, true));
                }
                $data['OrderID']=$order?(int)$order['OrderID']:null;
                $data['SpbbID']=$branch;
                $data['CustomerID']=$order?$order['CustomerID']:$this->pipeline_finance->company_key($data['BillingCompany']);
                $data['CreatedBy'] = $this->session->userdata('MIS_LOGGED_ID');
                $debug = $this->db->db_debug;
                $this->db->db_debug = false;
                $columns = array_map(function($key){return '['.$key.']';},array_keys($data));
                $sppId=$this->input->post('SourceCaptureID');$boundId=null;
                if($sppId!==null&&$sppId!==''){
                    try{$boundId=$this->spp_delivery->create($data,$sppId,$_FILES['DOFile']??null);$saved=true;}catch(Exception $e){$saved=false;$deliveryError=$e->getMessage();}
                }else{if(isset($_FILES['DOFile'])&&$_FILES['DOFile']['error']!==UPLOAD_ERR_NO_FILE){$this->db->db_debug=$debug;$this->session->set_flashdata('Gagal','Pilih SPP sumber untuk upload DO terintegrasi.');redirect('Pipanisasi/delivery');return;}
                if($this->db->where('DONumber',$data['DONumber'])->count_all_results('PipelineDelivery')){
                    $saved=false;
                    $deliveryError='Nomor DO sudah digunakan. Periksa DO tersimpan pada daftar dokumen terkait.';
                }else{
                    $saved = $this->db->query('INSERT INTO PipelineDelivery ('.implode(',',$columns).') OUTPUT INSERTED.DeliveryID VALUES ('.implode(',',array_fill(0,count($data),'?')).')',array_values($data),true);
                    if(!$saved){$failure=$this->db->error();log_message('error','PipelineDelivery insert failed: '.json_encode($failure));}
                }
                }
                $this->db->db_debug = $debug;
                if ($saved) {
                    $this->session->set_flashdata('Berhasil', 'DO berhasil disimpan dan masuk ke dashboard sesuai tanggal DO.');
                    $new_id=$boundId?:((int)$saved->row()->DeliveryID);
                    redirect('Pipanisasi/delivery?start='.$data['DeliveryDate'].'&end='.$data['DeliveryDate'].'&do='.$new_id.'#do-'.$new_id);
                }
                if($deliveryError==='')$deliveryError='DO gagal disimpan. Periksa nomor DO, cabang, tanggal, dan angka volume atau totalizer. Kode pemeriksaan DO-SAVE.';
            }
        }
        list($start, $end) = $this->period();
        $this->render('pipeline/delivery', array('deliveryError'=>$deliveryError,'do_conflicts'=>$this->spp_delivery->conflicts($this->input->post('DONumber'),$this->input->post('SourceCaptureID')?:$this->input->get('spp')),'spp_sources'=>$this->spp_delivery->candidates(),'selected_spp'=>(int)($this->input->post('SourceCaptureID')?:$this->input->get('spp')),'master_vessels'=>$this->masterdata->vessels(),'master_companies'=>$this->masterdata->companies(),'judul' => 'Input Surat Pengantar Pengiriman', 'orders' => $this->Pipeline_model->orders(),
            'branches'=>$this->billing->branches(),'billing'=>$this->billing,'pipeline_token'=>$this->session->userdata('pipeline_form_token'),
            'start'=>$start, 'end'=>$end, 'deliveries'=>$this->Pipeline_model->deliveries($start,$end)));
    }

    public function orders()
    {
        $this->pipeline_access->require_menu('orders');
        if ($this->session->userdata('USER_ROLE')==='CUSTOMER') {
            redirect('Pipeline_flow');
            return;
        }
        list($start, $end) = $this->period();
        $this->load->model('Pipeline_flow_model','flow');
        $this->render('pipeline/orders', array('judul'=>'Pesanan Pipanisasi','start'=>$start,'end'=>$end,
            'rows'=>$this->flow->listing($start,$end),'statuses'=>$this->flow->statuses(),
            'orders'=>$this->Pipeline_model->orders($start,$end)));
    }

    public function filling()
    {
        $this->filling_queue('filling',array('TRANSPORT_CONFIRMED','FILLING'));
    }

    public function filling_actual()
    {
        $this->filling_queue('filling_actual',array('FILLING','FILLED'));
    }

    private function filling_queue($menu,$states)
    {
        $this->pipeline_access->require_menu($menu);
        $this->load->model('Pipeline_flow_model','flow');
        $rows=array_filter($this->flow->listing(),function($row) use ($states) {
            return in_array($row['Status'],$states,true) && ($row['Status']!=='TRANSPORT_CONFIRMED' || !$this->flow->start_blockers($row));
        });
        $this->render('pipeline/filling_queue',array('judul'=>$this->pipeline_access->menus()[$menu],
            'rows'=>$rows,'statuses'=>$this->flow->statuses()));
    }

    public function valid_date($value)
    {
        $date = DateTime::createFromFormat('!Y-m-d', $value);
        $valid = $date && $date->format('Y-m-d') === $value;
        if (!$valid) $this->form_validation->set_message('valid_date', '{field} tidak valid.');
        return $valid;
    }

    public function export()
    {
        $this->pipeline_access->require_menu('export');
        $this->ready();
        list($start, $end) = $this->period();
        $datasets = array(
            array('DO', array('DONumber' => 'Nomor DO', 'DeliveryDate' => 'Tanggal DO', 'InvoiceNo' => 'Pesanan', 'Quantity' => 'Volume (liter)', 'CompanyName' => 'Perusahaan', 'DriverName' => 'Sopir', 'VehicleNumber' => 'No. Polisi', 'VesselName' => 'Kapal', 'FillingTime' => 'Jam Pengisian', 'ReceiptNumber' => 'No. Resi', 'Notes' => 'Catatan'), $this->Pipeline_model->deliveries($start, $end)),
            array('Pesanan', array('InvoiceNo' => 'Pesanan', 'CreatedAt' => 'Tanggal Pesanan', 'OrderStatus' => 'Status', 'Quantity' => 'Volume (liter)'), $this->Pipeline_model->orders($start, $end, true))
        );
        $filters=$this->input->get('filters')?:array();if(!is_array($filters))show_error('Filter tidak valid.',422);$valid=array();$totals=array();
        foreach($datasets as &$dataset){$name=$dataset[0];$totals[$name]=count($dataset[2]);$valid[$name]=array_keys($dataset[1]);$terms=$filters[$name]??array();if(!is_array($terms)||array_diff(array_keys($terms),$valid[$name]))show_error('Kolom filter tidak valid.',422);foreach($terms as $key=>$term)if(!is_string($term)||mb_strlen($term)>200)show_error('Pencarian maksimal 200 karakter per kolom.',422);
            $dataset[2]=array_values(array_filter($dataset[2],function($row)use($terms){foreach($terms as $key=>$term){$term=trim($term);if($term==='')continue;$raw=(string)($row[$key]??'');$display=$key==='Quantity'?rtrim(rtrim(number_format((float)$raw,3,',','.'),'0'),','):$raw;if(mb_stripos($raw,$term)===false&&mb_stripos($display,$term)===false)return false;}return true;}));
        }unset($dataset);if(array_diff(array_keys($filters),array_keys($valid)))show_error('Kelompok filter tidak valid.',422);
        if ($this->input->get('download') !== '1') {
            $this->render('pipeline/export', array('judul'=>'Ekspor Excel Pipanisasi','start'=>$start,'end'=>$end,'datasets'=>$datasets,'filters'=>$filters,'totals'=>$totals));return;
        }
        require_once FCPATH . 'vendor/autoload.php';
        $book = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        foreach ($datasets as $index => $dataset) {
            $sheet = $index === 0 ? $book->getActiveSheet() : $book->createSheet();
            $sheet->setTitle($dataset[0]);
            $sheet->setCellValue('A1', 'Periode: ' . $start . ' s/d ' . $end);
            $sheet->fromArray(array_values($dataset[1]), null, 'A3');
            $rowNumber = 4;
            foreach ($dataset[2] as $row) {
                $column = 1;
                foreach ($dataset[1] as $key => $label) {
                    $value = isset($row[$key]) ? $row[$key] : '';
                    $sheet->setCellValueExplicitByColumnAndRow($column++, $rowNumber, $key === 'Quantity' ? (float) $value : (string) $value,
                        $key === 'Quantity' ? \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC : \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                }
                $rowNumber++;
            }
            $last = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($dataset[1]));
            $sheet->getStyle('A3:' . $last . '3')->getFont()->setBold(true);
            $sheet->setAutoFilter('A3:' . $last . max(3, $rowNumber - 1));
            $sheet->freezePane('A4');
            foreach (range('A', $last) as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        $book->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Pipanisasi_' . $start . '_' . $end . '.xlsx"');
        header('Cache-Control: private, no-store');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($book))->save('php://output');
    }

    private function render($view, $data)
    {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}
