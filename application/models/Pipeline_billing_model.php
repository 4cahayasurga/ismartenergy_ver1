<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_billing_model extends CI_Model
{
    private $user;
    private $role;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Spbb_model');
        $this->load->model('Pipeline_model');
        $this->load->library('Pipeline_finance');
        $this->user=$this->session->userdata('MIS_LOGGED_ID');
        $this->role=$this->Spbb_model->get_role($this->user);
    }
    public function ready() { return $this->db->table_exists('PipelinePayment') && $this->db->table_exists('PipelineDocument') && $this->db->table_exists('PipelineRate'); }
    public function branches()
    {
        return $this->admin() ? $this->Spbb_model->spbb_list() : $this->Spbb_model->spbb_list_by_ids($this->role['spbb_ids']);
    }
    private function admin() { return $this->role['role']==='SUPERADMIN' || strtolower($this->user)==='admin'; }
    public function branch_allowed($id)
    {
        foreach ($this->branches() as $branch) if ((int)$branch['SpbbID']===(int)$id) return true;
        return false;
    }
    public function customers()
    {
        $this->Pipeline_model->delivery_query();
        $deliveries=$this->db->order_by('D.DeliveryID','DESC')->get()->result_array();
        $rows=array();
        foreach($deliveries as $row) {
            $key=$row['SpbbID'].'/'.$row['CustomerID'];
            if (!isset($rows[$key])) $rows[$key]=array('CustomerID'=>$row['CustomerID'],'SpbbID'=>$row['SpbbID'],
                'CompanyName'=>$row['BillingCompany'],'UserName'=>$row['BillingCompany']);
        }
        foreach($this->invoices() as $row) {
            $key=$row['SpbbID'].'/'.$row['CustomerID'];
            if (!isset($rows[$key])) $rows[$key]=array('CustomerID'=>$row['CustomerID'],'SpbbID'=>$row['SpbbID'],
                'CompanyName'=>$row['CustomerName'],'UserName'=>$row['CustomerName']);
        }
        return array_values($rows);
    }
    public function invoice_companies()
    {
        $this->Pipeline_model->delivery_query();
        $deliveries=$this->db->where('X.DeliveryID IS NULL',null,false)->group_start()->where('D.OrderID IS NULL',null,false)
            ->or_where('O.OrderStatus !=','DITOLAK')->group_end()->order_by('D.DeliveryID','DESC')->get()->result_array();
        $outstanding=array();
        foreach($this->invoices() as $invoice) {
            $key=$invoice['SpbbID'].'/'.$invoice['CustomerID'];
            if (!isset($outstanding[$key])) $outstanding[$key]=0;
            $outstanding[$key]+=max(0,$invoice['GrandTotal']-$invoice['Paid']);
        }
        $rows=array();
        foreach($deliveries as $row) {
            $key=$row['SpbbID'].'/'.$row['CustomerID'].'/'.$row['BillingCompany'];
            if (!isset($rows[$key])) $rows[$key]=array('DeliveryID'=>$row['DeliveryID'],'SpbbID'=>$row['SpbbID'],
                'CustomerID'=>$row['CustomerID'],'CompanyName'=>$row['BillingCompany'],'DOCount'=>0,
                'Outstanding'=>isset($outstanding[$row['SpbbID'].'/'.$row['CustomerID']])?$outstanding[$row['SpbbID'].'/'.$row['CustomerID']]:0);
            $rows[$key]['DOCount']++;
        }
        if ($this->db->table_exists('PipelineSppInvoice')) {
            $this->load_spp_billing();
            foreach ($this->spp_invoice->candidates() as $capture) {
                $company=trim((string)$capture['Buyer']);
                if ($company==='' || (float)$capture['OrderedLiters']<=0 || !$this->pipeline_finance->date_valid(substr((string)$capture['DeliveryDate'],0,10))) continue;
                $key='SPP/'.$capture['SpbbID'].'/'.$company;
                if (!isset($rows[$key])) $rows[$key]=array('DeliveryID'=>0,'SelectionID'=>'SPP:'.$capture['CaptureID'],
                    'Source'=>'SPP','SpbbID'=>$capture['SpbbID'],'CustomerID'=>$this->pipeline_finance->company_key($company),
                    'CompanyName'=>$company,'DOCount'=>0,'Outstanding'=>$outstanding[$capture['SpbbID'].'/'.$this->pipeline_finance->company_key($company)]??0);
                $rows[$key]['DOCount']++;
            }
        }
        return array_values($rows);
    }
    /** Memakai validasi SPP yang sama dengan penagihan langsung pada alur dokumen. */
    private function load_spp_billing()
    {
        $ci=get_instance();
        if (!isset($ci->spp)) $this->load->model('Pipeline_spp_model','spp');
        if (!isset($ci->spp_invoice)) $this->load->model('Pipeline_spp_invoice_model','spp_invoice');
    }
    public function invoice_spp_company($id,$branch)
    {
        $this->load_spp_billing();
        $r=$this->spp->capture($id);
        if (!$r || (int)$r['SpbbID']!==(int)$branch || $r['Status']!=='CONFIRMED' || trim((string)$r['Buyer'])==='') {
            throw new RuntimeException('Pilih perusahaan dari SPP cabang ini yang sudah diperiksa.');
        }
        $this->spp_invoice->available($r);
        return array('CustomerID'=>$this->pipeline_finance->company_key(trim($r['Buyer'])), 'BillingCompany'=>trim($r['Buyer']));
    }
    public function candidate_spps($company,$branch,$start,$end)
    {
        $this->load_spp_billing();
        return array_values(array_filter($this->spp_invoice->candidates(),function($r)use($company,$branch,$start,$end){
            $date=substr((string)$r['DeliveryDate'],0,10);
            return (int)$r['SpbbID']===(int)$branch && trim((string)$r['Buyer'])===$company && $date>=$start && $date<=$end;
        }));
    }
    public function invoice_company($id,$branch)
    {
        $delivery=$this->delivery($id);
        if (!$delivery || (int)$delivery['SpbbID']!==(int)$branch || $this->billed($id) || $delivery['OrderStatus']==='DITOLAK') throw new RuntimeException('Perusahaan harus dipilih dari DO cabang ini yang belum ditagihkan.');
        return $delivery;
    }
    public function customer($customer,$branch)
    {
        foreach ($this->customers() as $row) if ($row['CustomerID']===$customer && (int)$row['SpbbID']===(int)$branch) return $row;
        throw new RuntimeException('Pelanggan atau cabang di luar akses Anda.');
    }
    public function delivery($id)
    {
        $this->Pipeline_model->delivery_query();
        return $this->db->where('D.DeliveryID',(int)$id)->get()->row_array();
    }
    public function documents($delivery_id)
    {
        return $this->db->where('DeliveryID',(int)$delivery_id)->order_by('DocumentID','DESC')->get('PipelineDocument')->result_array();
    }
    public function current_documents($delivery_id)
    {
        $current=array();
        foreach ($this->documents($delivery_id) as $row) if (!isset($current[$row['DocumentType']])) $current[$row['DocumentType']]=$row;
        return $current;
    }
    public function document($id)
    {
        $row=$this->db->where('DocumentID',(int)$id)->get('PipelineDocument')->row_array();
        return $row && $this->delivery($row['DeliveryID']) ? $row : null;
    }
    public function billed($id) { return (bool)$this->db->where('DeliveryID',(int)$id)->get('PipelineInvoiceDelivery')->row_array(); }

    /** Dipanggil dalam transaksi verifikasi pesanan. */
    public function remove_spp_attachment_for_order($id,$request)
    {
        if (!$this->admin()) throw new RuntimeException('Pelepasan lampiran hanya untuk Administrator Sistem.');
        $document=$this->document($id);
        $delivery=$document?$this->delivery($document['DeliveryID']):null;
        if (!$delivery || $document['DocumentType']!=='SPP' || (int)$delivery['SpbbID']!==(int)$request['SpbbID']) {
            throw new RuntimeException('Lampiran DO berubah. Muat ulang pesanan.');
        }
        $this->lock($delivery['SpbbID'].'/'.$delivery['CustomerID']);
        if ($this->billed($delivery['DeliveryID'])) throw new RuntimeException('DO '.$delivery['DONumber'].' sudah masuk invoice.');
        $captures=$this->db->where('DocumentID',(int)$id)->get('PipelineSppCapture')->result_array();
        foreach ($captures as $capture) {
            $captureId=(int)$capture['CaptureID'];
            if ((int)$capture['SpbbID']!==(int)$request['SpbbID']
                || ($capture['DeliveryID'] && (int)$capture['DeliveryID']!==(int)$delivery['DeliveryID'])
                || $this->db->where('CaptureID',$captureId)->count_all_results('PipelineSppInvoice')
                || $this->db->where('CaptureID',$captureId)->count_all_results('PipelineOperation')
                || $this->operations->spp->flow_request($capture)) {
                throw new RuntimeException('Lampiran DO masih digunakan pada dokumen lain.');
            }
        }
        $this->operations->audit('spp',(int)$id,'DETACH',array('RequestID'=>$request['RequestID'],'Document'=>$document,'Captures'=>$captures));
        $this->db->where('DocumentID',(int)$id)->update('PipelineSppCapture',array('DocumentID'=>null,'DeliveryID'=>null));
        $this->db->where('DocumentID',(int)$id)->delete('PipelineDocument');
    }

    public function detach_spp($capture)
    {
        $actor=$this->session->userdata('MIS_LOGGED_ID');
        if (!$this->admin()) throw new RuntimeException('Pelepasan tautan hanya untuk Administrator Sistem.');
        $this->load->model('Pipeline_operation_model','operations');
        return $this->operations->transaction(function() use ($capture,$actor) {
            $id=(int)$capture['CaptureID'];
            $lock=$this->db->query("DECLARE @r int; EXEC @r=sp_getapplock @Resource=?, @LockMode='Exclusive', @LockOwner='Transaction', @LockTimeout=10000; SELECT @r AS LockResult",array('pipeline-spp-'.$id));
            if (!$lock || (int)$lock->row()->LockResult<0) throw new RuntimeException('SPP sedang diproses. Coba kembali.');
            $fresh=$this->operations->spp->capture($id);
            if (!$fresh || (int)$fresh['DeliveryID']!==(int)$capture['DeliveryID'] || (int)$fresh['DocumentID']!==(int)$capture['DocumentID']) {
                throw new RuntimeException('Tautan SPP berubah. Muat ulang halaman.');
            }
            $document=$fresh['DocumentID']?$this->document($fresh['DocumentID']):null;
            $delivery=$this->delivery($fresh['DeliveryID']?:($document['DeliveryID']??0));
            if (!$delivery || (int)$delivery['SpbbID']!==(int)$fresh['SpbbID']
                || ($fresh['DocumentID'] && (!$document || $document['DocumentType']!=='SPP'
                    || (int)$document['DeliveryID']!==(int)$delivery['DeliveryID']
                    || $document['FileHash']!==$fresh['FileHash']))) {
                throw new RuntimeException('Tautan DO atau lampiran SPP tidak konsisten.');
            }
            $this->lock($delivery['SpbbID'].'/'.$delivery['CustomerID']);
            if ($this->billed($delivery['DeliveryID']) || $this->db->where('CaptureID',$id)->count_all_results('PipelineSppInvoice')
                || $this->db->where('CaptureID',$id)->count_all_results('PipelineOperation')
                || $this->operations->spp->flow_request($fresh)) {
                throw new RuntimeException('SPP atau DO sudah dipakai pada invoice, pesanan, atau berita acara.');
            }
            if ($document && $this->db->where('DocumentID',$document['DocumentID'])->where('CaptureID !=',$id)->count_all_results('PipelineSppCapture')) {
                throw new RuntimeException('Lampiran juga dipakai oleh arsip SPP lain.');
            }
            $this->operations->audit('spp',$id,'DETACH',array('Capture'=>$fresh,'Document'=>$document,'DONumber'=>$delivery['DONumber']));
            $this->db->where('CaptureID',$id)->update('PipelineSppCapture',array(
                'DeliveryID'=>null,'DocumentID'=>null,'UpdatedAt'=>date('Y-m-d H:i:s')));
            if ($document) $this->db->where('DocumentID',$document['DocumentID'])->delete('PipelineDocument');
        });
    }

    private function lock($name)
    {
        $result=$this->db->query("DECLARE @r int; EXEC @r=sp_getapplock @Resource=?, @LockMode='Exclusive', @LockOwner='Transaction', @LockTimeout=10000; SELECT @r AS LockResult",array('pipeline-'.hash('sha256',$name)));
        if (!$result || (int)$result->row()->LockResult<0) throw new RuntimeException('Data sedang diproses. Silakan coba lagi.');
    }
    public function save_totalizers($delivery,$start,$end)
    {
        list($start,$end)=$this->pipeline_finance->totalizers($start,$end);
        if(round($end-$start,3)>(float)$delivery['Quantity'])throw new RuntimeException('Selisih totalizer melebihi volume DO. Periksa angka awal dan akhir.');
        $this->load->model('Pipeline_flow_model','flow');
        $request=$this->flow->delivery_request($delivery['DeliveryID']);
        if($request){
            if($request['Status']==='FILLED'&&(float)$request['TotalizerStart']===$start&&(float)$request['TotalizerEnd']===$end)return true;
            if($request['Status']!=='FILLING')throw new RuntimeException('Pengisian sudah selesai atau sudah masuk proses BA. Totalizer terkunci.');
            $this->flow->change($request['RequestID'],'finish_filling',array('TotalizerStart'=>(string)$start,'TotalizerEnd'=>(string)$end));
            return true;
        }
        return $this->transaction(function() use($delivery,$start,$end) {
            $this->lock($delivery['SpbbID'].'/'.$delivery['CustomerID']);
            if ($this->billed($delivery['DeliveryID'])) throw new RuntimeException('DO sudah ditagihkan; totalizer terkunci.');
            if (!$this->db->where('DeliveryID',$delivery['DeliveryID'])->update('PipelineDelivery',array(
                'TotalizerStart'=>$start,'TotalizerEnd'=>$end,'TotalizerUpdatedBy'=>$this->user,'TotalizerUpdatedAt'=>date('Y-m-d H:i:s')))) throw new RuntimeException('Totalizer gagal disimpan.');
            return true;
        });
    }
    public function add_document($delivery,$data)
    {
        return $this->transaction(function() use($delivery,$data) {
            if ($data['DocumentType']==='SPP' && $this->db->table_exists('PipelineFlowRequest')) {
                $lock=$this->db->query("DECLARE @r int; EXEC @r=sp_getapplock @Resource='pipeline-operation-workflow',
                    @LockMode='Exclusive',@LockOwner='Transaction',@LockTimeout=10000; SELECT @r Result");
                if (!$lock || (int)$lock->row()->Result<0) throw new RuntimeException('Dokumen sedang diproses.');
                $this->load->model('Pipeline_spp_model','spp');
                if ($this->spp->flow_request(array('SpbbID'=>$delivery['SpbbID'],'LONumber'=>$data['LoadingReference']??'','ShipmentNo'=>$data['ReferenceNo']))) {
                    throw new RuntimeException('SPP sudah digunakan pada BA Pengisian.');
                }
            }
            $this->lock($delivery['SpbbID'].'/'.$delivery['CustomerID']);
            $this->lock('document/'.$data['DocumentType'].'/'.$data['ReferenceNo']);
            if ($this->billed($delivery['DeliveryID'])) throw new RuntimeException('DO sudah ditagihkan; lampiran terkunci untuk menjaga jejak tagihan.');
            if ($this->db->where('DocumentType',$data['DocumentType'])->where('ReferenceNo',$data['ReferenceNo'])->where('DeliveryID !=',$delivery['DeliveryID'])->get('PipelineDocument')->row_array()) throw new RuntimeException('Referensi dokumen ini sudah dipakai oleh DO lain.');
            if ($data['DocumentType']==='SPP') {
                $duplicate=$this->db->query("SELECT TOP 1 P.DocumentID,P.DeliveryID,P.FileHash,P.LoadingReference,P.DocumentDate,P.VolumeLiters
                    FROM PipelineDocument P JOIN PipelineDelivery D ON D.DeliveryID=P.DeliveryID
                    WHERE D.SpbbID=? AND P.DocumentType='SPP' AND (P.FileHash=? OR UPPER(LTRIM(RTRIM(P.LoadingReference)))=?)
                    ORDER BY CASE WHEN P.DeliveryID=? THEN 1 ELSE 0 END,P.DocumentID DESC",
                    array($delivery['SpbbID'],$data['FileHash'],mb_strtoupper(trim($data['LoadingReference'])),$delivery['DeliveryID']))->row_array();
                if ($duplicate) {
                    if ((int)$duplicate['DeliveryID']===(int)$delivery['DeliveryID'] && $duplicate['FileHash']===$data['FileHash']
                        && strcasecmp(trim($duplicate['LoadingReference']),trim($data['LoadingReference']))===0
                        && substr($duplicate['DocumentDate'],0,10)===substr($data['DocumentDate'],0,10)
                        && bccomp((string)$duplicate['VolumeLiters'],(string)$data['VolumeLiters'],3)===0) return (int)$duplicate['DocumentID'];
                    throw new RuntimeException('File atau nomor LO sudah terlampir. Periksa lampiran SPP yang ada sebelum melanjutkan.');
                }
            }
            $existing=$this->db->where('DeliveryID',$delivery['DeliveryID'])->where('DocumentType',$data['DocumentType'])->where('FileHash',$data['FileHash'])->where('ReferenceNo',$data['ReferenceNo'])->order_by('DocumentID','DESC')->get('PipelineDocument')->row_array();
            if($existing)return (int)$existing['DocumentID'];
            if (!$this->db->insert('PipelineDocument',$data)) throw new RuntimeException('Dokumen gagal disimpan. Nomor referensi jenis dokumen harus unik.');
            return true;
        });
    }
    public function review_document($document,$delivery)
    {
        return $this->transaction(function() use($document,$delivery) {
            $this->lock($delivery['SpbbID'].'/'.$delivery['CustomerID']);
            if ($this->billed($delivery['DeliveryID'])) throw new RuntimeException('Dokumen invoice sudah terkunci.');
            return $this->db->where('DocumentID',$document['DocumentID'])->update('PipelineDocument',array('Reviewed'=>1,'ReviewedBy'=>$this->user,'ReviewedAt'=>date('Y-m-d H:i:s')));
        });
    }
    public function rate_history($branch)
    {
        return $this->db->where('SpbbID',(int)$branch)->order_by('EffectiveDate','DESC')->order_by('RateID','DESC')->get('PipelineRate')->result_array();
    }
    public function rate($code,$date,$branch)
    {
        $row=$this->db->where('SpbbID',(int)$branch)->where('ServiceCode',$code)->where('EffectiveDate <=',$date)
            ->order_by('EffectiveDate','DESC')->limit(1)->get('PipelineRate')->row_array();
        if (!$row) throw new RuntimeException('Tarif '.$code.' belum tersedia pada '.$date.'. Isi Basic Harga terlebih dahulu.');
        return $row;
    }
    public function add_rates($branch,$date,$reason,$amounts,$fuelUnit=null)
    {
        if($fuelUnit!==null&&!in_array($fuelUnit,array('LITER','TON'),true))throw new InvalidArgumentException('Satuan BBM tidak valid.');
        return $this->transaction(function() use($branch,$date,$reason,$amounts,$fuelUnit) {
            $this->lock('rates/'.$branch);
            foreach ($amounts as $code=>$amount) {
                $service=$this->pipeline_finance->services()[$code];
                if (!$this->db->insert('PipelineRate',array('SpbbID'=>$branch,'ServiceCode'=>$code,'ServiceName'=>$service[0],
                    'Unit'=>($code==='FUEL_ADMIN'&&$fuelUnit!==null?$fuelUnit:$service[1]),'Amount'=>$amount,'EffectiveDate'=>$date,'Reason'=>$reason,'CreatedBy'=>$this->user))) {
                    throw new RuntimeException('Harga pada tanggal berlaku yang sama sudah ada atau gagal disimpan. Riwayat tidak ditimpa.');
                }
            }
            return true;
        });
    }
    public function candidate_deliveries($customer,$branch,$start,$end,$company=null)
    {
        $this->Pipeline_model->delivery_query();
        $this->db->where('D.CustomerID',$customer)->where('D.SpbbID',(int)$branch)
            ->group_start()->where('D.OrderID IS NULL',null,false)->or_where('O.OrderStatus !=','DITOLAK')->group_end()
            ->where('X.DeliveryID IS NULL',null,false)->where('D.DeliveryDate >=',$start)->where('D.DeliveryDate <=',$end);
        if ($company!==null) $this->db->where('D.BillingCompany',$company);
        return $this->db->order_by('D.DeliveryDate')->order_by('D.DeliveryID')->get()->result_array();
    }
    public function quote($input)
    {
        $fromSpp=isset($input['BillingSource']) && $input['BillingSource']==='SPP';
        $direct=null;
        if ($fromSpp) {
            if (!$this->branch_allowed($input['SpbbID']) || $input['CustomerID']!==$this->pipeline_finance->company_key($input['CustomerName'])) throw new RuntimeException('Perusahaan / area SPP tidak valid.');
            $captures=$this->candidate_spps($input['CustomerName'],$input['SpbbID'],$input['PeriodStart'],$input['PeriodEnd']);
            $valid=array_map('intval',array_column($captures,'CaptureID'));
            $selected=isset($input['SelectedCaptureIDs'])?$input['SelectedCaptureIDs']:$valid;
            if (!$selected || array_diff($selected,$valid)) throw new RuntimeException('SPP terpilih di luar perusahaan / area / periode, belum diperiksa, atau sudah ditagihkan.');
            $direct=$this->spp_invoice->quote($selected);
            $deliveries=array();
        } else {
        $this->customer($input['CustomerID'],$input['SpbbID']);
        $deliveries=$this->candidate_deliveries($input['CustomerID'],$input['SpbbID'],$input['PeriodStart'],$input['PeriodEnd'],isset($input['CustomerName'])?$input['CustomerName']:null);
        if(isset($input['SelectedDeliveryIDs'])){
            $valid=array_map('intval',array_column($deliveries,'DeliveryID'));
            if(array_diff($input['SelectedDeliveryIDs'],$valid))throw new RuntimeException('DO terpilih di luar perusahaan / area / periode, atau sudah ditagihkan.');
            $deliveries=array_values(array_filter($deliveries,function($r)use($input){return in_array((int)$r['DeliveryID'],$input['SelectedDeliveryIDs'],true);}));
        }
        }
        $lines=array(); $linked=array(); $liters=$direct?$direct['liters']:0; $tons=$liters/1000;
        foreach ($deliveries as $delivery) {
            $documents=$this->current_documents($delivery['DeliveryID']);
            $errors=$this->pipeline_finance->reconcile($delivery,$documents);
            if ($errors) throw new RuntimeException($delivery['DONumber'].': '.implode('; ',$errors));
            if(method_exists($this->db,'table_exists')&&$this->db->table_exists('PipelineOperation')){$sourceRef=trim((string)$documents['SPP']['LoadingReference']);if($sourceRef!==''&&$this->db->where('SpbbID',$input['SpbbID'])->where('Kind','BASTB')->where('SourceKey','LO:'.mb_strtoupper($sourceRef))->get('PipelineOperation')->row_array())throw new RuntimeException('SPP dengan LO ini sudah dikelola pada alur BASTB/RDT.');}
            if(method_exists($this->db,'table_exists')&&$this->db->table_exists('PipelineSppInvoice')){$ref=mb_strtoupper(trim((string)$documents['SPP']['LoadingReference']));$billed=$this->db->query("SELECT TOP 1 S.InvoiceID FROM PipelineSppInvoice S JOIN PipelineSppCapture C ON C.CaptureID=S.CaptureID WHERE S.SpbbID=? AND (C.DeliveryID=? OR S.SourceKey=? OR (UPPER(LTRIM(RTRIM(C.ShipmentNo)))<>'' AND UPPER(LTRIM(RTRIM(C.ShipmentNo)))=?) OR EXISTS(SELECT 1 FROM PipelineSppCapture C2 WHERE C2.DeliveryID=? AND ((C2.LONumber<>'' AND UPPER(LTRIM(RTRIM(C2.LONumber)))=UPPER(LTRIM(RTRIM(C.LONumber)))) OR (C2.ShipmentNo<>'' AND UPPER(LTRIM(RTRIM(C2.ShipmentNo)))=UPPER(LTRIM(RTRIM(C.ShipmentNo)))))))",array($input['SpbbID'],$delivery['DeliveryID'],'LO:'.$ref,mb_strtoupper(trim((string)$documents['SPP']['ReferenceNo'])),$delivery['DeliveryID']))->row_array();if($billed)throw new RuntimeException('SPP sudah ditagihkan langsung.');}
            $density=$documents['SPP']['Density'];
            $ton=$this->pipeline_finance->tons((float)$delivery['Quantity'],$input['ConversionMode'],$density);
            $date=substr($delivery['DeliveryDate'],0,10);
            $rate=$this->rate('FUEL_ADMIN',$date,$input['SpbbID']);$rate['Unit']=$rate['Unit']??'TON'; $vat=$this->rate('VAT',$date,$input['SpbbID']);
            $key=$rate['RateID'].'/'.$vat['RateID'];
            if (!isset($lines[$key])) $lines[$key]=array('ServiceCode'=>'FUEL_ADMIN','Description'=>$rate['ServiceName'],'Quantity'=>0,'Unit'=>$rate['Unit'],'Rate'=>$rate['Amount'],'RateID'=>$rate['RateID'],'vat'=>$vat);
            $lines[$key]['Quantity']+=strtoupper($rate['Unit'])==='LITER'?(float)$delivery['Quantity']:$ton;
            $linked[]=array('DeliveryID'=>$delivery['DeliveryID'],'Liters'=>$delivery['Quantity'],'Tons'=>$ton,'Density'=>$density,
                'DOFileID'=>$documents['DO']['DocumentID'],'SPPFileID'=>$documents['SPP']['DocumentID'],'LOFileID'=>$documents['LO']['DocumentID']);
            $liters+=(float)$delivery['Quantity']; $tons+=$ton;
        }
        $output=array(); $subtotal=0; $tax=0;
        foreach ($this->pipeline_finance->services() as $code=>$service) {
            if (in_array($code,array('FUEL_ADMIN','VAT'))) continue;
            $quantity=isset($input['quantities'][$code])?$input['quantities'][$code]:0;
            $rate=$this->rate($code,$input['PeriodEnd'],$input['SpbbID']);
            $amount=$this->pipeline_finance->amount($quantity,$rate['Amount']);
            $output[]=array('ServiceCode'=>$code,'Description'=>$service[0],'Quantity'=>$quantity,'Unit'=>$service[1],'Rate'=>$rate['Amount'],'RateID'=>$rate['RateID'],'Amount'=>$amount);
            $subtotal+=$amount;
        }
        foreach ($lines as $line) {
            $vat=$line['vat']; unset($line['vat']);
            $line['Quantity']=round($line['Quantity'],6);
            $line['Amount']=$this->pipeline_finance->amount($line['Quantity'],$line['Rate']);
            $output[]=$line; $subtotal+=$line['Amount'];
            $vatAmount=$this->pipeline_finance->amount($line['Amount']/100,$vat['Amount']);
            $output[]=array('ServiceCode'=>'VAT','Description'=>'PPN (Jasa Administrasi BBM)','Quantity'=>$line['Amount'],'Unit'=>'Dasar Rp','Rate'=>$vat['Amount'],'RateID'=>$vat['RateID'],'Amount'=>$vatAmount);
            $tax+=$vatAmount;
        }
        if ($direct) {
            $output=array_merge($output,$direct['lines']); $subtotal+=$direct['subtotal']; $tax+=$direct['tax'];
        }
        return array('spp_links'=>$direct?$direct['links']:array(),'lines'=>$output,'deliveries'=>$linked,'TotalLiters'=>round($liters,3),'TotalTons'=>round($tons,6),
            'Subtotal'=>round($subtotal,2),'Tax'=>round($tax,2),'GrandTotal'=>round($subtotal+$tax,2));
    }
    public function create_invoice($input,$expected)
    {
        return $this->transaction(function() use($input,$expected) {
            $workflow=$this->db->query("DECLARE @r int;EXEC @r=sp_getapplock @Resource='pipeline-operation-workflow',@LockMode='Exclusive',@LockOwner='Transaction',@LockTimeout=10000;SELECT @r r");if(!$workflow||(int)$workflow->row()->r<0)throw new RuntimeException('Dokumen sedang diproses.');$this->lock($input['SpbbID'].'/'.$input['CustomerID']);
            $quote=$this->quote($input);
            if (!hash_equals($expected,hash('sha256',json_encode(array($input,$quote))))) throw new RuntimeException('Data berubah saat penerbitan. Hitung / Preview kembali.');
            if ($quote['GrandTotal']<=0) throw new RuntimeException('Tidak ada nilai tagihan. DO / SPP harus belum ditagihkan atau isi jasa lainnya.');
            $header=$input; unset($header['quantities'],$header['SelectedDeliveryIDs'],$header['SelectedCaptureIDs'],$header['BillingSource']);
            foreach (array('TotalLiters','TotalTons','Subtotal','Tax','GrandTotal') as $key) $header[$key]=$quote[$key];
            $header['CreatedBy']=$this->user; $header['InvoiceType']='INVOICE';
            $id=$this->insert_invoice($header);
            foreach ($quote['lines'] as $line) { $line['InvoiceID']=$id; if (!$this->db->insert('PipelineInvoiceLine',$line)) throw new RuntimeException('Rincian invoice gagal disimpan.'); }
            foreach ($quote['deliveries'] as $line) { $line['InvoiceID']=$id; if (!$this->db->insert('PipelineInvoiceDelivery',$line)) throw new RuntimeException('DO sudah ditagihkan oleh proses lain.'); }
            foreach ($quote['spp_links'] as $link) {
                $link['InvoiceID']=$id;
                if (!$this->db->insert('PipelineSppInvoice',$link)) throw new RuntimeException('SPP sudah ditagihkan oleh proses lain.');
            }
            return $id;
        });
    }
    private function insert_invoice($data)
    {
        $columns=array_map(function($key){ return '['.str_replace(']',']]',$key).']'; },array_keys($data));
        $query=$this->db->query('INSERT INTO PipelineInvoice ('.implode(',',$columns).') OUTPUT INSERTED.InvoiceID VALUES ('.implode(',',array_fill(0,count($data),'?')).')',array_values($data),true);
        if (!$query) throw new RuntimeException('Gagal menyimpan tagihan. Nomor tagihan harus unik.');
        return (int)$query->row()->InvoiceID;
    }
    private function invoice_scope()
    {
        if ($this->admin()) return;
        if ($this->role['role']==='CUSTOMER') $this->db->where('I.CustomerID',$this->user);
        elseif ($this->role['spbb_ids']) $this->db->where_in('I.SpbbID',$this->role['spbb_ids']);
        else $this->db->where('1=0',null,false);
    }
    public function invoices($start=null,$end=null,$customer=null,$branch=null)
    {
        $this->db->select('I.*, ISNULL(P.Paid,0) AS Paid',false)->from('PipelineInvoice I')
            ->join('(SELECT InvoiceID,SUM(Amount) Paid FROM PipelinePayment GROUP BY InvoiceID) P','P.InvoiceID=I.InvoiceID','left',false);
        $this->invoice_scope();
        if ($start) $this->db->where('I.InvoiceDate >=',$start);
        if ($end) $this->db->where('I.InvoiceDate <=',$end);
        if ($customer) $this->db->where('I.CustomerID',$customer);
        if ($branch) $this->db->where('I.SpbbID',(int)$branch);
        return $this->db->order_by('I.InvoiceDate')->order_by('I.InvoiceID')->get()->result_array();
    }
    public function invoice($id)
    {
        $this->db->select('I.*')->from('PipelineInvoice I')->where('I.InvoiceID',(int)$id); $this->invoice_scope();
        return $this->db->get()->row_array();
    }
    public function lines($id) { return $this->db->where('InvoiceID',(int)$id)->order_by('LineID')->get('PipelineInvoiceLine')->result_array(); }
    public function invoice_deliveries($id)
    {
        return $this->db->select('X.*,D.DONumber,D.DeliveryDate,D.VesselName')->from('PipelineInvoiceDelivery X')
            ->join('PipelineDelivery D','D.DeliveryID=X.DeliveryID')->where('X.InvoiceID',(int)$id)->get()->result_array();
    }
    public function invoice_spps($id)
    {
        if (!$this->invoice($id) || !$this->db->table_exists('PipelineSppInvoice')) return array();
        return $this->db->where('InvoiceID',(int)$id)->get('PipelineSppInvoice')->result_array();
    }
    public function payments($customer,$branch,$end)
    {
        $this->db->select('P.*,I.InvoiceNumber')->from('PipelinePayment P')->join('PipelineInvoice I','I.InvoiceID=P.InvoiceID'); $this->invoice_scope();
        return $this->db->where('I.CustomerID',$customer)->where('I.SpbbID',(int)$branch)->where('P.PaymentDate <=',$end)->get()->result_array();
    }
    public function add_payment($invoice,$data)
    {
        return $this->transaction(function() use($invoice,$data) {
            $this->lock('payment/'.$invoice['InvoiceID']);$invoice=$this->invoice($invoice['InvoiceID']);if(!$invoice)throw new RuntimeException('Invoice telah dihapus atau tidak dapat diakses.');
            $paid=$this->db->select_sum('Amount')->where('InvoiceID',$invoice['InvoiceID'])->get('PipelinePayment')->row_array();
            if (round((float)$paid['Amount']+(float)$data['Amount'],2)>(float)$invoice['GrandTotal']) throw new RuntimeException('Pembayaran melebihi sisa piutang.');
            if ($data['PaymentDate']<substr($invoice['InvoiceDate'],0,10)) throw new RuntimeException('Tanggal pembayaran tidak boleh sebelum tanggal tagihan.');
            $data['InvoiceID']=$invoice['InvoiceID']; $data['CreatedBy']=$this->user;
            if (!$this->db->insert('PipelinePayment',$data)) throw new RuntimeException('Pembayaran gagal disimpan atau referensi sudah digunakan.');
            return true;
        });
    }
    public function opening($data)
    {
        $this->customer($data['CustomerID'],$data['SpbbID']);
        return $this->transaction(function() use($data) {
            $data['CreatedBy']=$this->user; $data['InvoiceType']='OPENING';
            return $this->insert_invoice($data);
        });
    }
    private function transaction($callback)
    {
        $debug=$this->db->db_debug; $this->db->db_debug=false; $this->db->trans_begin();
        try {
            $result=$callback();
            if (!$this->db->trans_status()) throw new RuntimeException('Transaksi database gagal. Tidak ada perubahan disimpan.');
            $this->db->trans_commit(); return $result;
        } catch (Exception $e) { $this->db->trans_rollback(); throw $e; }
        finally { $this->db->db_debug=$debug; }
    }
}
