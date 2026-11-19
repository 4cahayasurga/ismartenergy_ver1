<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_billing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); is_login();
        $this->load->library('Pipeline_access'); $this->load->library('Pipeline_finance');
        $this->load->model('Pipeline_billing_model','billing');
        if (!$this->billing->ready()) show_error('Fitur dokumen dan tagihan belum diaktifkan. Jalankan php application/scripts/pipeline_migrate.php --apply dari folder proyek.',503);
        if (!$this->session->userdata('pipeline_form_token')) $this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));
    }
    private function write_guard($menu)
    {
        $this->pipeline_access->require_menu($menu);
        if ($this->input->method()!=='post') show_error('Metode tidak diizinkan.',405);
        $token=$this->input->post('pipeline_token');
        if (!is_string($token) || !hash_equals($this->session->userdata('pipeline_form_token'),$token)) show_error('Form kedaluwarsa. Muat ulang halaman.',403);
    }
    private function document_access()
    {
        foreach (array('pipeline_documents','delivery','dashboard','invoices','receivables') as $menu) if ($this->pipeline_access->allowed($menu)) return;
        show_error('Akses dokumen ditolak.',403);
    }
    private function text($key,$max,$required=true)
    {
        $value=$this->input->post($key,true);
        if ($value===null && !$required) return '';
        if (!is_string($value) || ($required && trim($value)==='') || mb_strlen($value)>$max) throw new InvalidArgumentException($key.' wajib diisi, maksimal '.$max.' karakter.');
        return trim($value);
    }
    private function date_field($key)
    {
        $value=$this->text($key,10);
        if (!$this->pipeline_finance->date_valid($value)) throw new InvalidArgumentException($key.' tidak valid.');
        return $value;
    }
    private function period()
    {
        $start=$this->input->get('start',true)?:date('Y-m-01'); $end=$this->input->get('end',true)?:date('Y-m-d');
        if (!$this->pipeline_finance->date_valid($start) || !$this->pipeline_finance->date_valid($end) || $start>$end) show_error('Periode tidak valid.',422);
        return array($start,$end);
    }
    private function render($view,$data)
    {
        $data['pipeline_token']=$this->session->userdata('pipeline_form_token');
        $this->load->view('templates/header',$data); $this->load->view('templates/sidebar');
        $this->load->view('pipeline/'.$view,$data); $this->load->view('templates/footer');
    }

    public function document_ocr($id){
        $this->write_guard('delivery');$delivery=$this->billing->delivery((int)$id);if(!$delivery)show_404();$path=null;$this->output->set_content_type('application/json')->set_header('Cache-Control: no-store');
        try{
            if($this->billing->billed($id))throw new RuntimeException('Dokumen DO sudah ditagihkan dan terkunci.');$type=$this->text('DocumentType',10);if(!in_array($type,array('DO','SPP','LO'),true))throw new RuntimeException('Jenis dokumen tidak valid.');
            $f=$_FILES['DocumentFile']??null;if(!$f||!is_scalar($f['error'])||(int)$f['error']!==UPLOAD_ERR_OK||$f['size']>15*1024*1024||!is_uploaded_file($f['tmp_name']))throw new RuntimeException('Pilih JPG/PNG/PDF maksimal 15 MB.');$mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);if(!in_array($mime,array('image/jpeg','image/png','application/pdf'),true))throw new RuntimeException('Format file tidak didukung.');if($mime==='application/pdf'){if(file_get_contents($f['tmp_name'],false,null,0,5)!=='%PDF-')throw new RuntimeException('PDF tidak valid.');}else{$size=@getimagesize($f['tmp_name']);if(!$size||$size[0]*$size[1]>24000000)throw new RuntimeException('Gambar maksimal 24 megapiksel.');}
            $path=tempnam(sys_get_temp_dir(),'pipeline_doc_');if(!$path||!move_uploaded_file($f['tmp_name'],$path))throw new RuntimeException('Penyimpanan OCR sementara gagal.');@set_time_limit(90);$this->load->library('Pipeline_spp_ocr');$this->load->library('Pipeline_document_autofill');$result=$this->pipeline_document_autofill->recognize($this->pipeline_spp_ocr->read($path),$type,$delivery);$this->output->set_output(json_encode(array('ok'=>true)+$result));
        }catch(Exception $e){$this->output->set_status_header(422)->set_output(json_encode(array('ok'=>false,'message'=>$e->getMessage())));}finally{if($path&&is_file($path))unlink($path);}
    }
    public function documents($id=null)
    {
        $this->document_access();
        if (!$id) { redirect('Pipanisasi/delivery#saved-do'); return; }
        $delivery=$this->billing->delivery((int)$id); if (!$delivery) show_404();
        $error='';
        if ($this->input->method()==='post') {
            $this->write_guard('delivery');
            $path=null;
            try {
                if ($this->input->post('action')==='totalizer') {
                    if (!$this->db->field_exists('TotalizerEnd','PipelineDelivery')) throw new RuntimeException('Jalankan migrasi totalizer terlebih dahulu.');
                    $this->billing->save_totalizers($delivery,$this->text('TotalizerStart',30),$this->text('TotalizerEnd',30));
                    $this->load->model('Pipeline_flow_model','flow');
                    $request=$this->flow->delivery_request($id);
                    $this->session->set_flashdata('Berhasil','Totalizer tersimpan. Pengisian selesai.');
                    if($request){redirect('Pipeline_flow/detail/'.$request['RequestID'].'#catat-pengisian');return;}
                    redirect('Pipanisasi/delivery?start='.substr($delivery['DeliveryDate'],0,10).'&end='.substr($delivery['DeliveryDate'],0,10).'&do='.$id.'#do-'.$id);
                    return;
                }
                if ($this->input->post('action')==='attach_spp') {
                    $this->pipeline_access->require_menu('spp_ocr');
                    $this->load->library('Pipeline_spp_ocr');
                    $this->load->model('Pipeline_spp_model','spp');
                    $captureId=$this->text('CaptureID',10);
                    if (!ctype_digit($captureId)) throw new RuntimeException('Pilih SPP dari arsip.');
                    $this->spp->attach((int)$captureId,(int)$id);
                    $this->session->set_flashdata('Berhasil','SPP dari arsip terlampir. Buka View / Periksa untuk memverifikasi.');
                    redirect('Pipanisasi/delivery?'.http_build_query(array('start'=>substr($delivery['DeliveryDate'],0,10),'end'=>substr($delivery['DeliveryDate'],0,10),'do'=>$id)).'#do-'.$id);
                    return;
                }
                $type=$this->text('DocumentType',10);
                if ($type==='SPP') throw new RuntimeException('Gunakan arsip Upload / Foto Surat Pengantar Pengiriman untuk melampirkan SPP ke DO.');
                if (!in_array($type,array('DO','LO'),true)) throw new InvalidArgumentException('Jenis dokumen tidak valid.');
                $volume=$this->pipeline_finance->number($this->text('Volume',30),1000000000);
                $unit=$this->text('VolumeUnit',5); if (!in_array($unit,array('L','KL'),true)) throw new InvalidArgumentException('Satuan volume tidak valid.');
                $volume=$volume*($unit==='KL'?1000:1); if ($volume<=0) throw new InvalidArgumentException('Volume wajib lebih dari nol.');
                $density=$this->text('Density',20,false); $density=$density===''?null:$this->pipeline_finance->number($density,2);
                if ($density!==null && $density<=0) throw new InvalidArgumentException('Densitas harus positif.');
                $data=array('DeliveryID'=>$delivery['DeliveryID'],'DocumentType'=>$type,'ReferenceNo'=>$this->text('ReferenceNo',100),
                    'LoadingReference'=>$this->text('LoadingReference',100,$type==='SPP'),'DocumentDate'=>$this->date_field('DocumentDate'),
                    'VolumeLiters'=>$volume,'VesselName'=>$this->text('VesselName',200),'Density'=>$density,
                    'Reviewed'=>0,'ReviewNote'=>$this->text('ReviewNote',1000,false),'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID'));
                if ($this->billing->billed($id)) throw new RuntimeException('Lampiran DO yang sudah ditagihkan terkunci.');
                $file=isset($_FILES['DocumentFile'])?$_FILES['DocumentFile']:null;
                if (!$file || !is_scalar($file['error']) || $file['error']!==UPLOAD_ERR_OK || $file['size']>15*1024*1024 || !is_uploaded_file($file['tmp_name'])) throw new InvalidArgumentException('Pilih PDF, JPG, atau PNG maksimal 15 MB. Periksa juga batas upload server.');
                $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
                $extensions=array('application/pdf'=>'pdf','image/jpeg'=>'jpg','image/png'=>'png');
                if (!isset($extensions[$mime])) throw new InvalidArgumentException('Isi file bukan PDF/JPG/PNG yang didukung.');
                $directory=APPPATH.'storage/pipeline/';
                if (!is_dir($directory) && !mkdir($directory,0700,true)) throw new RuntimeException('Folder dokumen tidak dapat dibuat.');
                $name=bin2hex(random_bytes(24)).'.'.$extensions[$mime]; $path=$directory.$name;
                if (!move_uploaded_file($file['tmp_name'],$path)) throw new RuntimeException('Upload gagal.');
                $data+=array('StoredName'=>$name,'OriginalName'=>mb_substr(basename($file['name']),0,255),'MimeType'=>$mime,
                    'FileHash'=>hash_file('sha256',$path),'FileSize'=>filesize($path));
                $savedDocument=$this->billing->add_document($delivery,$data);
                if(is_int($savedDocument)){
                    unlink($path);$path=null;
                    $this->session->set_flashdata('Berhasil','File ini sudah terlampir. Gunakan dokumen yang tersedia di daftar.');
                    redirect('Pipanisasi/delivery?start='.substr($delivery['DeliveryDate'],0,10).'&end='.substr($delivery['DeliveryDate'],0,10).'&do='.$id.'#do-'.$id);
                    return;
                }
                $path=null;
                $this->session->set_flashdata('Berhasil','Dokumen disimpan. Buka View / Periksa untuk mencocokkan data dengan file sebelum ditagihkan.');
                redirect('Pipanisasi/delivery?start='.substr($delivery['DeliveryDate'],0,10).'&end='.substr($delivery['DeliveryDate'],0,10).'&do='.$id.'#do-'.$id);
            } catch (Exception $e) { if ($path && is_file($path)) unlink($path); $error=$e->getMessage(); }
        }
        if ($error!=='') $this->session->set_flashdata('Gagal',$error);
        redirect('Pipanisasi/delivery?'.http_build_query(array('start'=>substr($delivery['DeliveryDate'],0,10),'end'=>substr($delivery['DeliveryDate'],0,10),'do'=>$id)).'#do-'.$id);
    }
    public function print_do($id)
    {
        $this->document_access();
        $delivery=$this->billing->delivery((int)$id); if (!$delivery) show_404();
        if (!$this->pipeline_finance->printable_do($delivery)) show_error('Cetak DO tersedia setelah totalizer awal dan akhir diisi.',422);
        $this->load->view('pipeline/delivery_print',array('delivery'=>$delivery));
    }
    public function document($id)
    {
        $this->document_access(); $doc=$this->billing->document($id); if (!$doc) show_404();
        $error='';
        if ($this->input->method()==='post') {
            $this->write_guard('delivery');
            try {
                if ($this->input->post('Reviewed')!=='1') throw new RuntimeException('Konfirmasi pemeriksaan dokumen wajib dicentang.');
                $this->billing->review_document($doc,$this->billing->delivery($doc['DeliveryID']));
                redirect('Pipeline_billing/documents/'.$doc['DeliveryID']);
            } catch (Exception $e) { $error=$e->getMessage(); }
        }
        $this->render('document_view',array('judul'=>'View / Periksa Dokumen','document'=>$doc,'error'=>$error));
    }
    public function file($id)
    {
        $this->document_access(); $doc=$this->billing->document($id); if (!$doc) show_404();
        if (!preg_match('/^[a-f0-9]{48}\.(pdf|png|jpg)$/D',$doc['StoredName'])) show_404();
        $path=APPPATH.'storage/pipeline/'.$doc['StoredName']; if (!is_file($path)) show_404();
        header('Content-Type: '.$doc['MimeType']); header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store'); header('Content-Length: '.filesize($path));
        header('Content-Disposition: '.($this->input->get('download')==='1'?'attachment':'inline').'; filename="document-'.$id.'.'.pathinfo($path,PATHINFO_EXTENSION).'"');
        readfile($path);
    }
    public function rates()
    {
        $this->pipeline_access->require_menu('rates'); $error='';
        $branch=(int)($this->input->post('SpbbID')?:$this->input->get('branch'));
        $branches=$this->billing->branches(); if (!$branch && $branches) $branch=(int)$branches[0]['SpbbID'];
        if ($branch && !$this->billing->branch_allowed($branch)) show_error('Cabang di luar akses Anda.',403);
        if ($this->input->method()==='post') {
            $this->write_guard('rates');
            try {
                if (!$branch) throw new RuntimeException('Pilih cabang.');
                $date=$this->date_field('EffectiveDate'); $reason=$this->text('Reason',500);
                $code=$this->text('ServiceCode',30); $services=$this->pipeline_finance->services(); $amounts=array();
                if ($code==='INITIAL') { foreach ($services as $key=>$service) $amounts[$key]=$service[2]; }
                elseif (isset($services[$code])) $amounts[$code]=$this->pipeline_finance->number($this->text('Amount',30),$code==='VAT'?100:1000000000);
                else throw new RuntimeException('Jenis harga tidak valid.');
                $fuelUnit=null;if($code==='FUEL_ADMIN'){$fuelUnit=$this->input->post('FuelUnit')?:'LITER';if(!is_string($fuelUnit)||!in_array($fuelUnit,array('LITER','TON'),true))throw new RuntimeException('Satuan tarif BBM tidak valid.');}$this->billing->add_rates($branch,$date,$reason,$amounts,$fuelUnit);
                $this->session->set_flashdata('Berhasil','Harga tersimpan sebagai riwayat baru. Invoice lama tidak berubah.');
                redirect('Pipeline_billing/rates?branch='.$branch);
            } catch(Exception $e) { $error=$e->getMessage(); }
        }
        $this->render('rates',array('judul'=>'Basic Harga & Riwayat','branches'=>$branches,'branch'=>$branch,'error'=>$error,
            'services'=>$this->pipeline_finance->services(),'history'=>$branch?$this->billing->rate_history($branch):array()));
    }
    private function invoice_input()
    {
        $data=array();
        foreach (array('InvoiceNumber'=>100,'ConversionMode'=>20) as $key=>$max) $data[$key]=$this->text($key,$max);
        $branch=(int)$this->text('SpbbID',10);
        $choice=$this->text('CompanyDeliveryID',40);
        if (preg_match('/^SPP:([1-9][0-9]*)$/D',$choice,$match)) {
            $data['BillingSource']='SPP'; $selected=$this->billing->invoice_spp_company((int)$match[1],$branch);
        } elseif (ctype_digit($choice) && (int)$choice>0) {
            $data['BillingSource']='DO'; $selected=$this->billing->invoice_company((int)$choice,$branch);
        } else throw new RuntimeException('Pilih perusahaan dari Surat Pengantar Pengiriman yang tersedia.');
        $data['CustomerID']=$selected['CustomerID']; $data['CustomerName']=$selected['BillingCompany'];
        $branchRow=null;
        foreach($this->billing->branches() as $row) if ((int)$row['SpbbID']===$branch) $branchRow=$row;
        if (!$branchRow) throw new RuntimeException('Cabang di luar akses Anda.');
        $data['IssuerName']=$branchRow['SpbbName'];
        $data['Place']=$branchRow['Area'];
        foreach(array('Signatory'=>150,'SignatoryTitle'=>200) as $key=>$max) $data[$key]=$this->text($key,$max,false);
        $data['Subject']='Tagihan Penggunaan Fasilitas BBM';
        foreach (array('CustomerAddress'=>1000,'Notes'=>1000) as $key=>$max) $data[$key]=$this->text($key,$max,false);
        foreach (array('InvoiceDate','PeriodStart','PeriodEnd') as $key) $data[$key]=$this->date_field($key);
        $data['DueDate']=$this->input->post('DueDate')?$this->date_field('DueDate'):date('Y-m-d',strtotime($data['InvoiceDate'].' +7 days'));
        if ($data['PeriodStart']>$data['PeriodEnd'] || $data['InvoiceDate']<$data['PeriodEnd'] || $data['DueDate']<$data['InvoiceDate']) throw new RuntimeException('Periode harus berurutan, tanggal invoice tidak sebelum akhir periode, dan jatuh tempo tidak sebelum invoice.');
        if ($data['ConversionMode']!=='LITER_1000') throw new RuntimeException('Dasar tagihan adalah 1 TON = 1.000 liter.');
        $data['SpbbID']=(int)$this->text('SpbbID',10); $data['quantities']=array();
        if($data['BillingSource']==='DO' && $this->input->post('SelectDO')==='selected'){
            $ids=$this->input->post('DeliveryIDs');if(!is_array($ids)||!count($ids)||count($ids)>2000)throw new RuntimeException('Pilih minimal satu DO.');
            foreach($ids as $v)if(!is_string($v)||!ctype_digit($v)||(int)$v<1)throw new RuntimeException('Pilihan DO tidak valid.');
            $data['SelectedDeliveryIDs']=array_values(array_unique(array_map('intval',$ids)));sort($data['SelectedDeliveryIDs']);
        }
        if ($data['BillingSource']==='SPP' && $this->input->post('SelectDO')==='selected') {
            $ids=$this->input->post('CaptureIDs');
            if (!is_array($ids) || !count($ids) || count($ids)>500) throw new RuntimeException('Pilih minimal satu SPP, maksimal 500.');
            foreach ($ids as $v) if (!is_string($v) || !ctype_digit($v) || (int)$v<1) throw new RuntimeException('Pilihan SPP tidak valid.');
            $data['SelectedCaptureIDs']=array_values(array_unique(array_map('intval',$ids))); sort($data['SelectedCaptureIDs']);
        }
        $posted=$this->input->post('quantities'); $posted=is_array($posted)?$posted:array();
        foreach ($this->pipeline_finance->services() as $code=>$service) if (!in_array($code,array('FUEL_ADMIN','VAT'))) $data['quantities'][$code]=$this->pipeline_finance->number(isset($posted[$code])?$posted[$code]:'0',1000000);
        return $data;
    }
    public function invoices()
    {
        $this->pipeline_access->require_menu('invoices'); list($start,$end)=$this->period();
        $this->render('invoices',array('judul'=>'Create Invoice / Nota Tagihan','start'=>$start,'end'=>$end,
            'invoices'=>$this->billing->invoices($start,$end)));
    }
    public function create_invoice()
    {
        $this->pipeline_access->require_menu('invoices'); $error=''; $quote=null; $available_deliveries=array(); $available_spps=array();
        $form=$this->input->post()?:array('InvoiceDate'=>date('Y-m-d'),'DueDate'=>'',
            'SpbbID'=>(int)$this->input->get('branch'),'InvoiceNumber'=>'INV-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3))),
            'PeriodStart'=>date('Y-m-01'),'PeriodEnd'=>date('Y-m-d'),'Subject'=>'Tagihan Penggunaan Fasilitas BBM','ConversionMode'=>'LITER_1000');
        if ($this->input->method()==='post') {
            $this->write_guard('invoices');
            try {
                $input=$this->invoice_input();
                if ($input['BillingSource']==='SPP') $available_spps=$this->billing->candidate_spps($input['CustomerName'],$input['SpbbID'],$input['PeriodStart'],$input['PeriodEnd']);
                else $available_deliveries=$this->billing->candidate_deliveries($input['CustomerID'],$input['SpbbID'],$input['PeriodStart'],$input['PeriodEnd'],$input['CustomerName']);
                $quote=$this->billing->quote($input);
                if (!$quote['deliveries'] && !$quote['spp_links']) throw new RuntimeException('Tidak ada Surat Pengantar Pengiriman yang belum ditagihkan untuk perusahaan dan periode ini.');
                if ($this->input->post('action')==='issue') {
                    $preview=$this->session->userdata('pipeline_invoice_preview');
                    $hash=hash('sha256',json_encode(array($input,$quote)));
                    if (!is_string($preview) || !hash_equals($preview,$hash)) throw new RuntimeException('Data berubah atau belum dipratinjau. Klik Hitung / Preview kembali.');
                    $id=$this->billing->create_invoice($input,$hash); $this->session->unset_userdata('pipeline_invoice_preview');
                    redirect('Pipeline_billing/invoice/'.$id);
                }
                $this->session->set_userdata('pipeline_invoice_preview',hash('sha256',json_encode(array($input,$quote))));
            } catch(Exception $e) { $error=$e->getMessage(); }
        }
        $this->render('invoice_form',array('judul'=>'Create Invoice','form'=>$form,'error'=>$error,'quote'=>$quote,'available_deliveries'=>$available_deliveries,'available_spps'=>$available_spps,
            'branches'=>$this->billing->branches(),'companies'=>$this->billing->invoice_companies(),'services'=>$this->pipeline_finance->services()));
    }
    public function invoice($id)
    {
        $this->pipeline_access->require_menu('invoices'); $invoice=$this->billing->invoice($id); if (!$invoice) show_404();
        $data=array('invoice'=>$invoice,'lines'=>$this->billing->lines($id),'deliveries'=>$this->billing->invoice_deliveries($id),'spp_links'=>$this->billing->invoice_spps($id),
            'terbilang'=>$this->pipeline_finance->words($invoice['GrandTotal']),
            'ledger'=>$this->pipeline_finance->ledger($this->billing->invoices(null,null,$invoice['CustomerID'],$invoice['SpbbID']),
                $this->billing->payments($invoice['CustomerID'],$invoice['SpbbID'],substr($invoice['InvoiceDate'],0,10)),
                substr($invoice['InvoiceDate'],0,4).'-01-01',substr($invoice['InvoiceDate'],0,10)));
        if ($this->input->get('pdf')==='1') { $this->pdf('invoice_print',$data,'Nota-Tagihan-'.$id.'.pdf'); return; }
        if ($this->input->get('print')==='1') { $this->load->view('pipeline/invoice_print',$data); return; }
        $this->render('invoice_detail',$data+array('judul'=>'Nota Tagihan '.$invoice['InvoiceNumber']));
    }
    public function receivables()
    {
        $this->pipeline_access->require_menu('receivables'); list($start,$end)=$this->period();
        $customer=$this->input->get('customer',true); $branch=(int)$this->input->get('branch');
        $error=''; $ledger=null; $invoices=array();
        if ($this->input->method()==='post') {
            $this->write_guard('receivables');
            try {
                if ($this->input->post('action')==='payment') {
                    $invoice=$this->billing->invoice((int)$this->input->post('InvoiceID')); if (!$invoice) throw new RuntimeException('Tagihan tidak ditemukan.');
                    $amount=$this->pipeline_finance->number($this->text('Amount',30)); if ($amount<=0) throw new RuntimeException('Pembayaran wajib positif.');
                    $this->billing->add_payment($invoice,array('PaymentDate'=>$this->date_field('PaymentDate'),'Amount'=>$amount,
                        'ReferenceNo'=>$this->text('ReferenceNo',100),'Notes'=>$this->text('Notes',1000,false)));
                    $customer=$invoice['CustomerID']; $branch=$invoice['SpbbID'];
                } else {
                    $customer=$this->text('CustomerID',100); $branch=(int)$this->text('SpbbID',10);
                    $client=$this->billing->customer($customer,$branch); $date=$this->date_field('OpeningDate');
                    $amount=$this->pipeline_finance->number($this->text('Amount',30)); if ($amount<=0) throw new RuntimeException('Saldo awal wajib positif.');
                    $this->billing->opening(array('InvoiceNumber'=>$this->text('ReferenceNo',100),'SpbbID'=>$branch,'CustomerID'=>$customer,
                        'CustomerName'=>$client['CompanyName']?:$client['UserName'],'InvoiceDate'=>$date,'DueDate'=>$date,'PeriodStart'=>$date,'PeriodEnd'=>$date,
                        'IssuerName'=>'','Place'=>'','Signatory'=>'','SignatoryTitle'=>'','Subject'=>'Saldo awal piutang','Notes'=>$this->text('Notes',1000,false),
                        'ConversionMode'=>'OPENING','Subtotal'=>$amount,'Tax'=>0,'GrandTotal'=>$amount));
                }
                $this->session->set_flashdata('Berhasil','Transaksi piutang berhasil disimpan.');
                redirect('Pipeline_billing/receivables?'.http_build_query(array('start'=>$start,'end'=>$end,'customer'=>$customer,'branch'=>$branch)));
            } catch(Exception $e) { $error=$e->getMessage(); }
        }
        if ($customer && $branch) {
            try {
                $this->billing->customer($customer,$branch);
                $invoices=$this->billing->invoices(null,null,$customer,$branch);
                $ledger=$this->pipeline_finance->ledger($invoices,$this->billing->payments($customer,$branch,$end),$start,$end);
            } catch(Exception $e) { $error=$e->getMessage(); }
        }
        $data=array('judul'=>'Kartu Piutang','start'=>$start,'end'=>$end,'customer'=>$customer,'branch'=>$branch,'ledger'=>$ledger,'error'=>$error,
            'invoices'=>$invoices,'customers'=>$this->billing->customers(),'branches'=>$this->billing->branches());
        if ($this->input->get('pdf')==='1' && $ledger) { $this->pdf('receivable_print',$data,'Kartu-Piutang.pdf','A4'); return; }
        if ($this->input->get('print')==='1' && $ledger) { $this->load->view('pipeline/receivable_print',$data); return; }
        $this->render('receivables',$data);
    }
    private function pdf($view,$data,$filename,$format='A4')
    {
        require_once FCPATH.'vendor/autoload.php';
        $temp=sys_get_temp_dir().'/ismartenergy_ver1_pdf'; if (!is_dir($temp)) mkdir($temp,0700,true);
        $pdf=new \Mpdf\Mpdf(array('mode'=>'c','format'=>$format,'tempDir'=>$temp,'default_font'=>'helvetica'));
        $data['pdf_mode']=true;
        $pdf->WriteHTML($this->load->view('pipeline/'.$view,$data,true));
        $pdf->Output($filename,'D');
    }
}
