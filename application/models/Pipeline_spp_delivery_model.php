<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_spp_delivery_model extends CI_Model {
 public function __construct(){parent::__construct();$this->load->library('Pipeline_spp_ocr');$this->load->model('Pipeline_operation_model','operations');$this->load->model('Pipeline_spp_model','spp');$this->load->model('Pipeline_billing_model','billing');}
 public function candidates(){return array_values(array_filter($this->spp->captures(),function($r){return $r['Status']==='CONFIRMED'&&!$r['DeliveryID']&&!$r['DocumentID']&&!$this->db->where('CaptureID',$r['CaptureID'])->where('Kind','BASTB')->get('PipelineOperation')->row_array();}));}
 public function conflicts($number,$captureId=null)
 {
  $areas=array_column($this->billing->branches(),'SpbbID');
  if(!$areas)return array();
  $capture=is_scalar($captureId)?$this->spp->capture((int)$captureId):null;
  $number=is_string($number)?trim($number):'';
  $lo=trim((string)($capture['LONumber']??''));
  $shipment=trim((string)($capture['ShipmentNo']??''));
  if($number===''&&$lo===''&&$shipment==='')return array();
  $this->db->select('D.DeliveryID,D.DONumber,D.DeliveryDate,D.VesselName,P.DocumentID,P.ReferenceNo,P.OriginalName')
   ->from('PipelineDelivery D')->join('PipelineDocument P',"P.DeliveryID=D.DeliveryID AND P.DocumentType='SPP'",'left')
   ->where_in('D.SpbbID',$areas)->group_start()->where('1=0',null,false);
  if($number!=='')$this->db->or_where('D.DONumber',$number);
  if($capture){
   $this->db->or_where('D.DeliveryID',(int)$capture['DeliveryID']);
   $this->db->or_group_start()->where('D.SpbbID',(int)$capture['SpbbID'])->group_start()->where('1=0',null,false);
   if($lo!=='')$this->db->or_where('UPPER(LTRIM(RTRIM(P.LoadingReference)))',mb_strtoupper($lo));
   if($shipment!=='')$this->db->or_where('UPPER(LTRIM(RTRIM(P.ReferenceNo)))',mb_strtoupper($shipment));
   $this->db->group_end()->group_end();
  }
  return $this->db->group_end()->order_by('D.DeliveryID','DESC')->get()->result_array();
 }

 public function create($data,$captureId,$file=null){$path=null;try{return $this->operations->transaction(function()use($data,$captureId,$file,&$path){
  if(!is_string($captureId)||!ctype_digit($captureId))throw new RuntimeException('Pilih SPP yang valid.');
  $capture=$this->spp->capture((int)$captureId);if(!$capture||$capture['Status']!=='CONFIRMED'||$capture['DeliveryID']||$capture['DocumentID']||(int)$capture['SpbbID']!==(int)$data['SpbbID'])throw new RuntimeException('SPP harus terkonfirmasi, belum terikat DO, dan satu area.');
  $conflicts=$this->conflicts($data['DONumber'],$captureId);
  if($conflicts)throw new RuntimeException('Nomor DO atau SPP terpilih sudah digunakan pada DO '.implode(', ',array_unique(array_column($conflicts,'DONumber'))).'. Buka dokumen terkait di bawah.');
  if($this->db->where('DONumber',$data['DONumber'])->count_all_results('PipelineDelivery'))throw new RuntimeException('Nomor DO sudah digunakan. Gunakan nomor DO yang berbeda.');
  $validVessel=false;foreach($this->operations->masterdata->vessels() as $v)if((int)$v['SpbbID']===(int)$data['SpbbID']&&mb_strtolower(trim($v['VesselName']))===mb_strtolower(trim($data['VesselName'])))$validVessel=true;if(!$validVessel)throw new RuntimeException('Pilih nama kapal aktif dari Basic Data pada area SPP yang sama.');
  if($this->db->where('CaptureID',$captureId)->get('PipelineOperation')->row_array())throw new RuntimeException('SPP sudah dipakai dalam BASTB/RDT. Tidak boleh membuat mutasi/tagihan kedua.');
  if(!$capture['DeliveryDate']||!$capture['OrderedLiters']||!$capture['LONumber'])throw new RuntimeException('Lengkapi tanggal, liter dan nomor LO pada SPP terlebih dahulu.');
  if(substr($data['DeliveryDate'],0,10)!==substr($capture['DeliveryDate'],0,10)||abs((float)$data['Quantity']-(float)$capture['OrderedLiters'])>0.001)throw new RuntimeException('Tanggal dan volume DO harus sesuai SPP terpilih.');
  $sourceKey='LO:'.mb_strtoupper(trim($capture['LONumber']));if($this->db->where('SpbbID',$data['SpbbID'])->where('SourceKey',$sourceKey)->get('PipelineOperation')->row_array())throw new RuntimeException('LO SPP sudah dipakai pada alur berita acara.');
  $dup=$this->db->where('SpbbID',$data['SpbbID'])->where('LONumber',$capture['LONumber'])->where('DeliveryID IS NOT NULL',null,false)->get('PipelineSppCapture')->row_array();if($dup)throw new RuntimeException('LO SPP sudah terikat DO lain.');
  if($this->spp->flow_request($capture))throw new RuntimeException('SPP sudah dipakai di Pesanan Pipanisasi.');
  $id=$this->operations->insert('PipelineDelivery','DeliveryID',$data);$this->spp->attach((int)$captureId,$id);
  if($file&&$file['error']!==UPLOAD_ERR_NO_FILE){if(!is_scalar($file['error'])||(int)$file['error']!==UPLOAD_ERR_OK||$file['size']>15*1024*1024||!is_uploaded_file($file['tmp_name']))throw new RuntimeException('File DO maksimal 15 MB.');$mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);$ext=array('application/pdf'=>'pdf','image/jpeg'=>'jpg','image/png'=>'png');if(!isset($ext[$mime]))throw new RuntimeException('File DO harus JPG/PNG/PDF.');if($mime==='application/pdf'&&file_get_contents($file['tmp_name'],false,null,0,5)!=='%PDF-')throw new RuntimeException('PDF tidak valid.');if($mime!=='application/pdf'){$size=@getimagesize($file['tmp_name']);if(!$size||$size[0]*$size[1]>24000000)throw new RuntimeException('Gambar DO maksimal 24 megapiksel.');}
   $name=bin2hex(random_bytes(24)).'.'.$ext[$mime];$path=APPPATH.'storage/pipeline/'.$name;if(!move_uploaded_file($file['tmp_name'],$path))throw new RuntimeException('Upload DO gagal.');
   $this->billing->add_document($this->billing->delivery($id),array('DeliveryID'=>$id,'DocumentType'=>'DO','ReferenceNo'=>$data['DONumber'],'LoadingReference'=>$capture['LONumber'],'DocumentDate'=>$data['DeliveryDate'],'VolumeLiters'=>$data['Quantity'],'VesselName'=>$data['VesselName'],'Density'=>null,'Reviewed'=>0,'ReviewNote'=>'DO untuk SPP #'.$captureId,'CreatedBy'=>$data['CreatedBy'],'StoredName'=>$name,'OriginalName'=>mb_substr(basename($file['name']),0,255),'MimeType'=>$mime,'FileHash'=>hash_file('sha256',$path),'FileSize'=>filesize($path)));
  }
  $this->operations->audit('spp_do',$id,'CREATE',array('CaptureID'=>(int)$captureId,'DeliveryID'=>$id));return $id;
 });}catch(Exception $e){if($path&&is_file($path))unlink($path);throw $e;}}
}
