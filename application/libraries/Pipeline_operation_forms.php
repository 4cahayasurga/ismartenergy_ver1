<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_operation_forms {
 public function fields($kind){
  $common=array('DocumentNo'=>array('Nomor Dokumen','text',100),'DocumentDate'=>array('Tanggal Dokumen','date',10),'VesselName'=>array('Nama Kapal','text',200),'CompanyName'=>array('Perusahaan / Pelayaran','text',200),'Location'=>array('Lokasi Pengisian','text',200),'FillingDate'=>array('Tanggal Pengisian','date',10),'FillingTime'=>array('Jam Pengisian (WITA)','time',5),'Pier'=>array('Dermaga Penyeberangan','text',100),'VolumeLiters'=>array('Jumlah Pengisian (liter)','number',30));
  if($kind==='BASTB')return $common+array('FuelType'=>array('Jenis BBM','text',150),'Supervisor'=>array('Nama Supervisi Pelayaran','text',150),'ChiefEngineer'=>array('Nama Kepala Kamar Mesin','text',150),'FuelOfficer'=>array('Nama Petugas BBM','text',150),'Place'=>array('Tempat Penandatanganan','text',100));
  if($kind==='RDT'){foreach(array('Before'=>'Sebelum','After'=>'Sesudah') as $prefix=>$label)foreach(array('Main1'=>'Tangki Induk I','Main2'=>'Tangki Induk II','DailyRight'=>'Tangki Harian Kanan','DailyLeft'=>'Tangki Harian Kiri') as $key=>$tank)$common[$prefix.$key]=array($label.' — '.$tank.' (liter)','number',30);return $common+array('Captain'=>array('Nama Nakhoda','text',150),'ChiefEngineer'=>array('Nama KKM / Pembuat','text',150));}
  if($kind==='PASS')return array_intersect_key($common,array_flip(array('DocumentNo','DocumentDate','VesselName','CompanyName','FillingDate','FillingTime')))+array('TransportCompany'=>array('Nama Perusahaan Transportir','text',200),'DriverName'=>array('Nama Supir','text',150),'VehicleNumber'=>array('No. Polisi','text',100),'VehicleType'=>array('Jenis Kendaraan','text',100),'PeopleCount'=>array('Jumlah Orang','number',30),'VehicleCount'=>array('Jumlah Kendaraan','number',30),'Place'=>array('Tempat Penerbitan','text',100),'PortOfficer'=>array('Nama Petugas Pelabuhan','text',150),'Shift'=>array('Regu','text',50));
  throw new InvalidArgumentException('Jenis dokumen tidak valid.');
 }
 public function validate($kind,$post){$result=array();foreach($this->fields($kind) as $key=>$meta){$v=isset($post[$key])?$post[$key]:'';if(!is_scalar($v)||trim((string)$v)===''||mb_strlen((string)$v)>$meta[2])throw new InvalidArgumentException($meta[0].' wajib diisi dan harus valid.');$v=trim((string)$v);
  if($meta[1]==='date'){$d=DateTime::createFromFormat('!Y-m-d',$v);if(!$d||$d->format('Y-m-d')!==$v)throw new InvalidArgumentException($meta[0].' tidak valid.');}
  if($meta[1]==='time'&&!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/D',$v))throw new InvalidArgumentException('Jam tidak valid.');
  if($meta[1]==='number'){if(!preg_match('/^\d+(?:\.\d{1,3})?$/D',$v)||(float)$v>1000000000)throw new InvalidArgumentException($meta[0].' tidak valid.');$v=(float)$v;}
  $result[$key]=$v;
 }if(isset($result['VolumeLiters'])&&$result['VolumeLiters']<=0)throw new InvalidArgumentException('Jumlah pengisian harus lebih dari nol.');
 if($kind==='RDT'){$before=0;$after=0;foreach(array('Main1','Main2','DailyRight','DailyLeft') as $tank){$before+=$result['Before'.$tank];$after+=$result['After'.$tank];}$result['BeforeTotal']=round($before,3);$result['AfterTotal']=round($after,3);if(abs($after-$before-$result['VolumeLiters'])>0.001)throw new InvalidArgumentException('Selisih total sounding sesudah dan sebelum harus sama dengan jumlah pengisian.');}
 if($kind==='PASS')foreach(array('PeopleCount','VehicleCount') as $key)if($result[$key]!=floor($result[$key])||$result[$key]<($key==='VehicleCount'?1:0)||$result[$key]>1000)throw new InvalidArgumentException('Jumlah orang/kendaraan harus bilangan bulat yang wajar.');return $result;
 }
}
