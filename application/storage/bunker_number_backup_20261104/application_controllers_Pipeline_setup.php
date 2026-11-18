<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_setup extends CI_Controller {
 public function __construct(){parent::__construct();is_login();$this->load->library('Pipeline_access');$this->load->model('Pipeline_billing_model','billing');if(!$this->db->table_exists('PipelineCompany'))show_error('Jalankan migrasi Ver.1 terlebih dahulu.',503);if(!$this->session->userdata('pipeline_form_token'))$this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));}
 private function guard($menu){$this->pipeline_access->require_menu($menu);$t=$this->input->post('pipeline_token');if($this->input->method()!=='post'||!is_string($t)||!hash_equals($this->session->userdata('pipeline_form_token'),$t))show_error('Form kedaluwarsa.',403);}
 private function text($key,$length=200,$required=true){$v=$this->input->post($key,true);if(!is_string($v)||mb_strlen($v)>$length||($required&&trim($v)===''))throw new InvalidArgumentException($key.' tidak valid.');return trim($v);}
 private function dateval($key){$v=$this->text($key,10);if(!$this->pipeline_finance->date_valid($v))throw new InvalidArgumentException('Tanggal tidak valid.');return $v;}
 private function branch($id){$id=(int)$id;if(!$id||!$this->billing->branch_allowed($id))show_error('Area di luar akses Anda.',403);return $id;}
 private function scoped($table,$alias='T'){
  // branches() executes a query on the shared CI3 builder and resets its state.
  // Resolve access before constructing the query that must retain FROM and JOINs.
  $ids=array_column($this->billing->branches(),'SpbbID');
  $this->db->from($table.' '.$alias);
  if($ids)$this->db->where_in($alias.'.SpbbID',$ids);
  else $this->db->where('1=0',null,false);
 }
 private function render($view,$data){$data+=array('judul'=>'iSmartEnergy Ver.1','pipeline_token'=>$this->session->userdata('pipeline_form_token'),'branches'=>$this->billing->branches());$this->load->view('templates/header',$data);$this->load->view('templates/sidebar');$this->load->view('pipeline/'.$view,$data);$this->load->view('templates/footer');}
 private function insert($table,$data){
  $keys=array('PipelineCompany'=>'CompanyID','PipelineVessel'=>'VesselID','PipelineBunker'=>'BunkerID','PipelineKsop'=>'KsopID','PipelineReceipt'=>'ReceiptID');
  if(!isset($keys[$table]))throw new RuntimeException('Tabel tidak valid.');$cols=array_map(function($k){return '['.$k.']';},array_keys($data));$debug=$this->db->db_debug;$this->db->db_debug=false;
  try{$q=$this->db->query('INSERT INTO ['.$table.'] ('.implode(',',$cols).') OUTPUT INSERTED.['.$keys[$table].'] AS NewID VALUES ('.implode(',',array_fill(0,count($data),'?')).')',array_values($data),true);if(!$q)throw new RuntimeException('Gagal menyimpan. Periksa duplikasi data dan panjang kolom.');$r=$q->row_array();return (int)$r['NewID'];}finally{$this->db->db_debug=$debug;}
 }
 private function company($branch,$name,$address=''){$r=$this->db->where('SpbbID',$branch)->where('CompanyName',$name)->get('PipelineCompany')->row_array();if($r)return $r['CompanyID'];return $this->insert('PipelineCompany',array('SpbbID'=>$branch,'CompanyName'=>$name,'Address'=>$address,'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID')));}
 private function vessel($company,$name,$fields=array()){$r=$this->db->where('CompanyID',$company)->where('VesselName',$name)->get('PipelineVessel')->row_array();if($r)return $r['VesselID'];return $this->insert('PipelineVessel',array('CompanyID'=>$company,'VesselName'=>$name)+$fields);}
 public function master(){
  $this->pipeline_access->require_menu('master');$error='';$notice='';
  if($this->input->get('template')==='1'){header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename="master-kapal.csv"');echo "CompanyName,VesselName,Address,Flag,GrossTonnage,VesselType,MainTank,DailyTank\r\n";return;}
  if($this->input->method()==='post'){$this->guard('master');$debug=$this->db->db_debug;$this->db->db_debug=false;$this->db->trans_begin();try{
   $branch=$this->branch($this->input->post('SpbbID'));
   if($this->input->post('action')==='import'){
    $f=isset($_FILES['ImportFile'])?$_FILES['ImportFile']:null;if(!$f||$f['error']!==0||$f['size']>2*1024*1024||!is_uploaded_file($f['tmp_name']))throw new RuntimeException('Pilih CSV maksimal 2 MB.');
    $h=fopen($f['tmp_name'],'r');$columns=fgetcsv($h);if($columns)$columns[0]=ltrim($columns[0],"\xEF\xBB\xBF");$allowed=array('CompanyName','VesselName','Address','Flag','GrossTonnage','VesselType','MainTank','DailyTank');
    if(!$columns||count($columns)!==count(array_unique($columns))||array_diff($columns,$allowed)||!in_array('CompanyName',$columns)||!in_array('VesselName',$columns))throw new RuntimeException('Gunakan header CSV sesuai template.');$n=0;
    while(($values=fgetcsv($h))!==false){if($values===array(null))continue;if(++$n>5000||count($values)!==count($columns))throw new RuntimeException('Jumlah kolom tidak sesuai pada baris '.($n+1));$row=array_combine($columns,array_map('trim',$values));if(!$row['CompanyName']||mb_strlen($row['CompanyName'])>200||mb_strlen($row['VesselName'])>200)throw new RuntimeException('Nama pada baris '.($n+1).' tidak valid.');foreach($row as $k=>$v){$limit=$k==='Address'?1000:(in_array($k,array('CompanyName','VesselName'))?200:100);if(mb_strlen($v)>$limit)throw new RuntimeException('Kolom terlalu panjang pada baris '.($n+1));}
    $c=$this->company($branch,$row['CompanyName'],isset($row['Address'])?$row['Address']:'');if($row['VesselName']!==''){$extra=array_intersect_key($row,array_flip(array_slice($allowed,3)));$this->vessel($c,$row['VesselName'],$extra);}}
    fclose($h);$notice=$n.' baris diproses; data duplikat dilewati.';
   }else{$c=$this->company($branch,$this->text('CompanyName'),$this->text('Address',1000,false));$v=$this->text('VesselName',200,false);if($v!==''){$fields=array();foreach(array('Flag','GrossTonnage','VesselType','MainTank','DailyTank') as $key)$fields[$key]=$this->text($key,100,false);$this->vessel($c,$v,$fields);}$notice='Data tersimpan.';}
   if(!$this->db->trans_status())throw new RuntimeException('Impor dibatalkan; database menolak data.');$this->db->trans_commit();
  }catch(Exception $e){$this->db->trans_rollback();$error=$e->getMessage();}finally{$this->db->db_debug=$debug;}}
  $this->scoped('PipelineCompany','C');$companies=$this->db->select('C.*')->order_by('C.CompanyName')->get()->result_array();$this->scoped('PipelineCompany','C');$vessels=$this->db->select('V.*,C.CompanyName,C.SpbbID')->join('PipelineVessel V','V.CompanyID=C.CompanyID')->get()->result_array();$this->render('master',compact('companies','vessels','error','notice'));
 }
 public function bunker(){
  $this->pipeline_access->require_menu('bunker');$error='';
  if($this->input->method()==='post'){$this->guard('bunker');try{$branch=$this->branch($this->input->post('SpbbID'));$this->scoped('PipelineCompany','C');$v=$this->db->select('V.*,C.CompanyName,C.SpbbID')->join('PipelineVessel V','V.CompanyID=C.CompanyID')->where('V.VesselID',(int)$this->input->post('VesselID'))->where('C.SpbbID',$branch)->get()->row_array();if(!$v)throw new RuntimeException('Kapal dan area tidak sesuai.');
   $s=$v;foreach(array('Place','Recipient','RecipientPlace','RemainingFuel','PlannedLiters','Supplier','OriginPort','ArrivalPort','Position','Captain','Signatory','SignatoryTitle','IssuerName') as $k)$s[$k]=$this->text($k,$k==='Recipient'?500:200);$s['FillingDate']=$this->dateval('FillingDate');$s['PlannedLiters']=$this->pipeline_finance->number($s['PlannedLiters'],1000000000);if($s['PlannedLiters']<=0)throw new RuntimeException('Rencana bunker harus positif.');
   $id=$this->insert('PipelineBunker',array('SpbbID'=>$branch,'RequestNo'=>$this->text('RequestNo',100),'RequestDate'=>$this->dateval('RequestDate'),'VesselID'=>$v['VesselID'],'Snapshot'=>json_encode($s,JSON_UNESCAPED_UNICODE),'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID')));redirect('Pipeline_setup/print_bunker/'.$id);return;
  }catch(Exception $e){$error=$e->getMessage();}}
  $this->scoped('PipelineCompany','C');$vessels=$this->db->select('V.*,C.CompanyName,C.SpbbID')->join('PipelineVessel V','V.CompanyID=C.CompanyID')->get()->result_array();$this->scoped('PipelineBunker');$requests=$this->db->order_by('T.BunkerID','DESC')->get()->result_array();$this->render('bunker',compact('error','vessels','requests'));
 }
 public function print_bunker($id){$this->pipeline_access->require_menu('bunker');$this->scoped('PipelineBunker');$r=$this->db->where('T.BunkerID',(int)$id)->get()->row_array();if(!$r)show_404();$data=array('request'=>$r,'s'=>json_decode($r['Snapshot'],true),'pdf_mode'=>$this->input->get('pdf')==='1');if($data['pdf_mode']){require_once FCPATH.'vendor/autoload.php';$dir=sys_get_temp_dir().'/ismartenergy_ver1_pdf';if(!is_dir($dir))mkdir($dir,0700,true);$pdf=new \Mpdf\Mpdf(array('mode'=>'c','format'=>'A4','tempDir'=>$dir));$pdf->WriteHTML($this->load->view('pipeline/bunker_print',$data,true));$pdf->Output('Permohonan-Bunker-'.$id.'.pdf','D');}else $this->load->view('pipeline/bunker_print',$data);}
 public function ksop(){
  $this->pipeline_access->require_menu('ksop');$error='';
  if($this->input->method()==='post'){$this->guard('ksop');$path=null;try{$branch=$this->branch($this->input->post('SpbbID'));$id=$this->input->post('BunkerID');if($id!==''){$this->scoped('PipelineBunker');$r=$this->db->where('T.BunkerID',(int)$id)->where('T.SpbbID',$branch)->get()->row_array();if(!$r)throw new RuntimeException('Surat bunker tidak sesuai area.');}
   $f=isset($_FILES['DocumentFile'])?$_FILES['DocumentFile']:null;if(!$f||$f['error']!==0||$f['size']>15*1024*1024||!is_uploaded_file($f['tmp_name']))throw new RuntimeException('Pilih PDF/JPG/PNG maksimal 15 MB.');$mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);$ext=array('application/pdf'=>'pdf','image/jpeg'=>'jpg','image/png'=>'png');if(!isset($ext[$mime]))throw new RuntimeException('Format dokumen tidak didukung.');
   $row=array('SpbbID'=>$branch,'BunkerID'=>$id!==''?(int)$id:null,'ReferenceNo'=>$this->text('ReferenceNo',100),'DocumentDate'=>$this->dateval('DocumentDate'),'StoredName'=>bin2hex(random_bytes(24)).'.'.$ext[$mime],'OriginalName'=>mb_substr(basename($f['name']),0,255),'MimeType'=>$mime,'FileHash'=>hash_file('sha256',$f['tmp_name']),'FileSize'=>$f['size'],'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID'));
   $dir=APPPATH.'storage/ksop/';if(!is_dir($dir)&&!mkdir($dir,0700,true))throw new RuntimeException('Penyimpanan gagal.');$path=$dir.$row['StoredName'];if(!move_uploaded_file($f['tmp_name'],$path))throw new RuntimeException('Upload gagal.');$this->insert('PipelineKsop',$row);$path=null;redirect('Pipeline_setup/ksop');return;
  }catch(Exception $e){if($path&&is_file($path))unlink($path);$error=$e->getMessage();}}
  $this->scoped('PipelineBunker');$requests=$this->db->order_by('T.BunkerID','DESC')->get()->result_array();$this->scoped('PipelineKsop');$documents=$this->db->order_by('T.KsopID','DESC')->get()->result_array();$this->render('ksop',compact('documents','requests','error'));
 }
 public function ksop_file($id){$this->pipeline_access->require_menu('ksop');$this->scoped('PipelineKsop');$r=$this->db->where('T.KsopID',(int)$id)->get()->row_array();if(!$r||!preg_match('/^[a-f0-9]{48}\.(pdf|jpg|png)$/D',$r['StoredName']))show_404();$path=APPPATH.'storage/ksop/'.$r['StoredName'];if(!is_file($path))show_404();header('Content-Type: '.$r['MimeType']);header('X-Content-Type-Options: nosniff');header('Cache-Control: private, no-store');header('Content-Security-Policy: sandbox');header('Content-Disposition: '.($this->input->get('download')==='1'?'attachment':'inline').'; filename="KSOP-'.$id.'.'.pathinfo($path,PATHINFO_EXTENSION).'"');readfile($path);}
 public function receipt(){
 $this->guard('delivery');try{$branch=$this->branch($this->input->post('SpbbID'));$type=$this->text('MovementType',10);if(!in_array($type,array('IN','OPENING'),true))throw new RuntimeException('Jenis mutasi tidak valid.');$q=$this->pipeline_finance->number($this->text('Quantity',30),1000000000);if($q<=0)throw new RuntimeException('Volume wajib positif.');
 $this->insert('PipelineReceipt',array('SpbbID'=>$branch,'ReferenceNo'=>$this->text('ReferenceNo',100),'ReceiptDate'=>$this->dateval('ReceiptDate'),'Quantity'=>$q,'MovementType'=>$type,'Notes'=>$this->text('Notes',1000,false),'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID')));$this->session->set_flashdata('Berhasil','Penerimaan / saldo awal tersimpan.');}catch(Exception $e){$this->session->set_flashdata('Gagal',$e->getMessage());}redirect('Pipanisasi');
 }

 public function areas(){
 $this->pipeline_access->require_menu('areas');$error='';if($this->input->method()==='post'){$this->guard('areas');$debug=$this->db->db_debug;$this->db->db_debug=false;try{$no=$this->text('No_SPBB',30);if($this->db->where('No_SPBB',$no)->get('SPBB')->row_array())throw new RuntimeException('Kode area sudah ada.');if(!$this->db->insert('SPBB',array('No_SPBB'=>$no,'Area'=>$this->text('Area',100),'Pic'=>$this->text('Pic',100,false),'Status'=>'Aktif','UserEntry'=>$this->session->userdata('MIS_LOGGED_ID'),'TimeEntry'=>date('Y-m-d H:i:s'))))throw new RuntimeException('Area gagal disimpan.');redirect('Pipeline_setup/areas');return;}catch(Exception $e){$error=$e->getMessage();}finally{$this->db->db_debug=$debug;}}
 $this->render('areas',array('error'=>$error));
 }

}
