<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_spp_model extends CI_Model
{
    private function scope()
    {
        $ids=array(); foreach($this->billing->branches() as $branch) $ids[]=(int)$branch['SpbbID'];
        if ($ids) $this->db->where_in('SpbbID',$ids);
        else $this->db->where('1=0',null,false);
    }
    public function captures()
    {
        $this->scope();
        return $this->db->order_by('CaptureID','DESC')->limit(100)->get('PipelineSppCapture')->result_array();
    }
    public function capture($id)
    {
        $this->scope();
        return $this->db->where('CaptureID',(int)$id)->get('PipelineSppCapture')->row_array();
    }
    public function flow_request($capture)
    {
        if (!$this->db->table_exists('PipelineFlowRequest')) return null;
        $lo = mb_strtoupper(trim((string)($capture['LONumber']??'')));
        $shipment = mb_strtoupper(trim((string)($capture['ShipmentNo']??'')));
        return $this->db->query("SELECT TOP 1 R.RequestID FROM PipelineFlowRequest R
            JOIN PipelineSppCapture S ON S.CaptureID=R.CaptureID
            WHERE R.SpbbID=? AND (S.CaptureID=? OR
             (?<>'' AND UPPER(LTRIM(RTRIM(S.LONumber)))=?) OR
             (?<>'' AND UPPER(LTRIM(RTRIM(S.ShipmentNo)))=?))",
            array($capture['SpbbID'],(int)($capture['CaptureID']??0),$lo,$lo,$shipment,$shipment))->row_array();
    }

    public function create($data)
    {
        if (!$this->billing->branch_allowed($data['SpbbID'])) throw new RuntimeException('Cabang di luar akses Anda.');
        if (!empty($data['DeliveryID'])) {
            $delivery=$this->billing->delivery($data['DeliveryID']);
            if (!$delivery || (int)$delivery['SpbbID']!==(int)$data['SpbbID'] || $this->billing->billed($delivery['DeliveryID'])) throw new RuntimeException('Pilih DO cabang ini yang belum ditagihkan.');
        }
        $data['CreatedBy']=$this->session->userdata('MIS_LOGGED_ID');
        $columns=array_map(function($key){return '['.$key.']';},array_keys($data));
        $debug=$this->db->db_debug; $this->db->db_debug=false;
        $this->db->trans_begin();
        try {
            $lock=$this->db->query("DECLARE @r int;EXEC @r=sp_getapplock @Resource='pipeline-operation-workflow',@LockMode='Exclusive',@LockOwner='Transaction',@LockTimeout=10000;SELECT @r Result");
            if(!$lock||(int)$lock->row()->Result<0)throw new RuntimeException('Dokumen sedang diproses. Coba kembali.');
            $request=(int)($data['ProposedRequestID']??0);
            if ($request && !$this->db->where('RequestID',$request)->where('SpbbID',$data['SpbbID'])
                ->where('CaptureID',null)->where_in('Status',array('SUBMITTED','WAIT_TRANSPORT','TRANSPORT_CONFIRMED'))
                ->count_all_results('PipelineFlowRequest')) {
                throw new RuntimeException('Pesanan sudah berubah. Pilih kembali permohonan yang masih tersedia.');
            }
            $existing=$this->db->where('SpbbID',$data['SpbbID'])->where('FileHash',$data['FileHash'])->order_by('CaptureID','DESC')->get('PipelineSppCapture')->row_array();
            if($existing){
                if($request && (int)$existing['ProposedRequestID']!==$request){
                    if($existing['ProposedRequestID'] || $existing['DeliveryID'] || $existing['DocumentID']
                        || $this->flow_request($existing)
                        || $this->db->where('CaptureID',$existing['CaptureID'])->count_all_results('PipelineOperation')
                        || $this->db->where('CaptureID',$existing['CaptureID'])->count_all_results('PipelineSppInvoice')){
                        throw new RuntimeException('SPP ini sudah tersimpan sebagai arsip #'.$existing['CaptureID'].' dan sudah terkait dokumen lain.');
                    }
                    if(!$this->db->where('CaptureID',$existing['CaptureID'])->update('PipelineSppCapture',array('ProposedRequestID'=>$request)))throw new RuntimeException('Tautan permohonan gagal disimpan.');
                }
                if(!$this->db->trans_status())throw new RuntimeException('Arsip SPP gagal dibuka.');
                $this->db->trans_commit();
                return (int)$existing['CaptureID'];
            }
            $q=$this->db->query('INSERT INTO PipelineSppCapture ('.implode(',',$columns).') OUTPUT INSERTED.CaptureID VALUES ('.implode(',',array_fill(0,count($data),'?')).')',array_values($data),true);
            if (!$q) throw new RuntimeException('Foto SPP gagal dicatat.');
            $id=(int)$q->row()->CaptureID;
            if(!$this->db->trans_status())throw new RuntimeException('Foto SPP gagal disimpan.');
            $this->db->trans_commit();
            return $id;
        } catch(Throwable $e) {
            $this->db->trans_rollback();
            throw $e;
        } finally { $this->db->db_debug=$debug; }
    }
    public function ocr_result($id,$result)
    {
        $capture=$this->capture($id);
        if (!$capture || $capture['Status']==='CONFIRMED') throw new RuntimeException('SPP sudah dikonfirmasi atau tidak ditemukan.');
        $fields=$result['fields'];
        foreach(array('OrderedLiters','DeliveryDate') as $key) if ($fields[$key]==='') $fields[$key]=null;
        $fields+=array('OcrText'=>mb_substr($result['text'],0,40000),'OcrFieldsJson'=>json_encode($result['fields'],JSON_UNESCAPED_UNICODE),
            'OcrWarningsJson'=>json_encode($result['warnings'],JSON_UNESCAPED_UNICODE),'Status'=>'NEEDS_REVIEW','UpdatedAt'=>date('Y-m-d H:i:s'));
        if (!$this->db->where('CaptureID',(int)$id)->where('Status !=','CONFIRMED')->update('PipelineSppCapture',$fields)) throw new RuntimeException('Hasil OCR gagal disimpan.');
    }
    public function attach($id,$deliveryId)
    {
        if (!$deliveryId) throw new RuntimeException('Pilih DO yang akan dilampiri.');
        return $this->confirm($id,array(),$deliveryId,true);
    }
    public function confirm($id,$fields,$deliveryId,$attachOnly=false)
    {
        $capture=$this->capture($id); if (!$capture) throw new RuntimeException('SPP tidak ditemukan.');
        $debug=$this->db->db_debug; $this->db->db_debug=false; $this->db->trans_begin();
        try {
            $lock=$this->db->query("DECLARE @r int; EXEC @r=sp_getapplock @Resource=?, @LockMode='Exclusive', @LockOwner='Transaction', @LockTimeout=10000; SELECT @r AS LockResult",array('pipeline-spp-'.$id));
            if (!$lock || (int)$lock->row()->LockResult<0) throw new RuntimeException('SPP sedang diproses. Coba kembali.');
            $capture=$this->capture($id);
            if (!$capture) throw new RuntimeException('SPP tidak ditemukan.');
            if ($attachOnly) {
                if ($capture['Status']!=='CONFIRMED' || $capture['DocumentID']) throw new RuntimeException('SPP harus sudah dikonfirmasi dan belum dilampirkan.');
                $this->load->model('Pipeline_spp_invoice_model','spp_invoice');
                if ($capture['ProposedRequestID'] || $capture['DeliveryID'] || $this->flow_request($capture)
                    || $this->db->where('CaptureID',$id)->count_all_results('PipelineOperation')) {
                    throw new RuntimeException('Arsip SPP sudah terkait pesanan atau dokumen lain.');
                }
                $this->spp_invoice->available($capture);
                // Later attachment always uses the confirmed database values, never edited POST fields.
                $ocr=new Pipeline_spp_ocr(); $savedFields=array();
                foreach($ocr->fields() as $key=>$meta) $savedFields[$key]=isset($capture[$key])?(string)$capture[$key]:'';
                $fields=$ocr->validate($savedFields);
            } elseif ($capture['Status']==='CONFIRMED') throw new RuntimeException('SPP sudah dikonfirmasi. Unggah revisi sebagai dokumen baru.');
            $documentId=null;
            if ($deliveryId) {
                $delivery=$this->billing->delivery($deliveryId);
                if (!$delivery || (int)$delivery['SpbbID']!==(int)$capture['SpbbID']) throw new RuntimeException('DO harus berasal dari cabang SPP yang sama.');
                if (!$fields['DeliveryDate'] || !$fields['OrderedLiters'] || $fields['LONumber']==='') throw new RuntimeException('Untuk melampirkan ke DO, lengkapi Tgl Pengiriman, Jml Pesanan, dan Nomor LO.');
                // VehicleOrVessel may be a truck plate, so it must never overwrite the receiving vessel.
                $reference=$fields['ShipmentNo']!==''?$fields['ShipmentNo']:'SPP-LO-'.$fields['LONumber'];
                if (mb_strlen($reference)>100) throw new RuntimeException('Referensi SPP terlalu panjang.');
                $doc=array('DeliveryID'=>$deliveryId,'DocumentType'=>'SPP','ReferenceNo'=>$reference,'LoadingReference'=>$fields['LONumber'],
                    'DocumentDate'=>$fields['DeliveryDate'],'VolumeLiters'=>$fields['OrderedLiters'],'VesselName'=>$delivery['VesselName'],
                    'Density'=>$fields['DensityObs'],'Reviewed'=>0,'ReviewNote'=>'Hasil Upload / Foto SPP #'.$id.'. Nomor SO: '.$fields['SONumber'],
                    'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID'));
                foreach(array('StoredName','OriginalName','MimeType','FileHash','FileSize') as $key) $doc[$key]=$capture[$key];
                $savedDocument=$this->billing->add_document($delivery,$doc);
                if(is_int($savedDocument))$saved=$this->db->where('DocumentID',$savedDocument)->get('PipelineDocument')->row_array();
                else $saved=$this->db->where('DeliveryID',$deliveryId)->where('StoredName',$capture['StoredName'])->order_by('DocumentID','DESC')->get('PipelineDocument')->row_array();
                if (!$saved) throw new RuntimeException('Lampiran SPP tidak ditemukan setelah disimpan.');
                $documentId=$saved['DocumentID'];
            }
            $fields+=array('Status'=>'CONFIRMED','DeliveryID'=>$deliveryId?:null,'DocumentID'=>$documentId,
                'ConfirmedBy'=>$attachOnly?$capture['ConfirmedBy']:$this->session->userdata('MIS_LOGGED_ID'),'ConfirmedAt'=>$attachOnly?$capture['ConfirmedAt']:date('Y-m-d H:i:s'),'UpdatedAt'=>date('Y-m-d H:i:s'));
            if (!$this->db->where('CaptureID',(int)$id)->update('PipelineSppCapture',$fields) || !$this->db->trans_status()) throw new RuntimeException('Hasil SPP gagal disimpan.');
            $this->db->trans_commit();
        } catch(Exception $e) { $this->db->trans_rollback(); throw $e; }
        finally { $this->db->db_debug=$debug; }
    }
}
