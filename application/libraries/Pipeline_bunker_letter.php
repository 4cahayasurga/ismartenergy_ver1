<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_bunker_letter {
 public function fields(){return array(
  'VesselName'=>array('Nama Kapal','text',200),'Flag'=>array('Bendera','text',100),'GrossTonnage'=>array('DWT/GT','text',100),'VesselType'=>array('Jenis Kapal','text',100),'CompanyName'=>array('Nama Perusahaan','text',200),'MainTank'=>array('Kapasitas Tangki Utama','text',100),'DailyTank'=>array('Kapasitas Tangki Harian','text',100),'RemainingFuel'=>array('Sisa BBM saat ini','text',200),'PlannedLiters'=>array('Rencana Bunker Subsidi','number',30),'Supplier'=>array('Suplayer','text',200),'OriginPort'=>array('Datang dari','text',200),'ArrivalPort'=>array('Kapal Tiba','text',200),'FillingDate'=>array('Rencana Pengisian Tangki','date',10),'Position'=>array('Posisi Kapal','text',200),'Captain'=>array('Nahkoda','text',200));}
 public function defaults($place='Padangbai'){
  if(str_replace(' ','',strtolower((string)$place))==='padangbai')$place='Padangbai';
  return array('Place'=>$place,'PortName'=>$place,'AttachmentText'=>'1 (satu) berkas','Recipient'=>strtolower($place)==='padangbai'?"Kesyahbandaran Dan Otoritas\nPelabuhan Kelas IV Padangbai":'','RecipientPlace'=>mb_strtoupper($place),'IssuerName'=>'PT ASDP Indonesia Ferry (Persero)','FooterBranch'=>'Lembar','IssuerAddress'=>"Pelabuhan Penyeberangan Lembar, Mataram - Nusa\nTenggara Barat 83111",'IssuerPhone'=>'(+62-370) 681313','IssuerWebsite'=>'www.asdp.id','SignatoryTitle'=>'Plh. Port Operation Superintendent','Signatory'=>'');
 }
 public function valid_date($value){if(!is_string($value))return false;$d=DateTimeImmutable::createFromFormat('!Y-m-d',$value);return $d&&$d->format('Y-m-d')===$value&&(int)$d->format('Y')>0;}
 public function prepare($master,$input){
  $snapshot=$master;
  foreach($this->fields() as $key=>$field){
   $raw=in_array($key,array('VesselName','CompanyName'),true)?(isset($master[$key])?$master[$key]:''):(isset($input[$key])?$input[$key]:(isset($master[$key])?$master[$key]:''));
   if(!is_string($raw)&&!is_numeric($raw))throw new InvalidArgumentException($field[0].' tidak valid.');$value=trim((string)$raw);
   if($value===''||mb_strlen($value)>$field[2])throw new InvalidArgumentException($field[0].' wajib diisi, maksimal '.$field[2].' karakter.');
   if($field[1]==='number'){if(!preg_match('/^\d+(?:\.\d{1,3})?$/D',$value)||(float)$value<=0||(float)$value>1000000000)throw new InvalidArgumentException('Rencana Bunker Subsidi wajib lebih dari nol, maksimal tiga desimal.');$value=(float)$value;}
   if($field[1]==='date'&&!$this->valid_date($value))throw new InvalidArgumentException('Rencana tanggal pengisian tidak valid.');$snapshot[$key]=$value;
  }
  $defaults=$this->defaults(isset($input['Place'])&&is_string($input['Place'])?$input['Place']:'Padangbai');
  foreach(array('Place'=>200,'PortName'=>200,'AttachmentText'=>100,'Recipient'=>500,'RecipientPlace'=>200,'IssuerName'=>200,'FooterBranch'=>200,'IssuerAddress'=>500,'IssuerPhone'=>100,'IssuerWebsite'=>150,'SignatoryTitle'=>200,'Signatory'=>200) as $key=>$max){
   $raw=isset($input[$key])?$input[$key]:(isset($defaults[$key])?$defaults[$key]:'');if(!is_string($raw)||mb_strlen($raw)>$max)throw new InvalidArgumentException($key.' tidak valid.');$value=trim($raw);
   if($value===''&&!in_array($key,array('IssuerAddress','IssuerPhone','IssuerWebsite','FooterBranch'),true))throw new InvalidArgumentException($key.' wajib diisi.');$snapshot[$key]=$value;
  }
  $snapshot['LetterTemplateVersion']=2;return $snapshot;
 }
 public function printable($snapshot){$s=$snapshot;foreach($this->defaults(isset($s['Place'])?$s['Place']:'Padangbai') as $k=>$v)if(!array_key_exists($k,$s))$s[$k]=$v;if(!isset($snapshot['PortName'])&&isset($snapshot['ArrivalPort']))$s['PortName']=$snapshot['ArrivalPort'];foreach($this->fields() as $k=>$f)if(!isset($s[$k]))$s[$k]='';return $s;}
 public function date_label($value){$value=substr((string)$value,0,10);if(!$this->valid_date($value))return $value;$m=array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');return (int)substr($value,8,2).' '.$m[(int)substr($value,5,2)].' '.substr($value,0,4);}
 public function liters_label($value){return rtrim(rtrim(number_format((float)$value,3,',','.'),'0'),',').' Liter';}
}
