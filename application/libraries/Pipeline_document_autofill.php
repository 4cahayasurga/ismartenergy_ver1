<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_document_autofill {
 public function from_source($type,$delivery,$spp=null){$spp=$spp?:array();return array('ReferenceNo'=>$type==='DO'?$delivery['DONumber']:($type==='LO'?($spp['LoadingReference']??''):($spp['ReferenceNo']??'')),'LoadingReference'=>$spp['LoadingReference']??'','VesselName'=>$delivery['VesselName'],'Volume'=>isset($spp['VolumeLiters'])?(string)(float)$spp['VolumeLiters']:(string)(float)$delivery['Quantity'],'VolumeUnit'=>'L','Density'=>isset($spp['Density'])?(string)(float)$spp['Density']:'','DocumentDate'=>substr($spp['DocumentDate']??$delivery['DeliveryDate'],0,10));}
 public function recognize($result,$requested,$delivery){$f=$result['fields'];$text=$result['text'];$type=$requested;
  if(preg_match('/SURAT\s*PENGANTAR\s*PENGIRIMAN/iu',$text))$type='SPP';elseif(preg_match('/DELIVERY\s*ORDER|SURAT\s*JALAN/iu',$text))$type='DO';elseif(preg_match('/LOADING\s*(?:ORDER|LETTER)/iu',$text))$type='LO';
  $reference=$type==='SPP'?($f['ShipmentNo']!==''?$f['ShipmentNo']:($f['LONumber']!==''?'SPP-LO-'.$f['LONumber']:'')):($type==='LO'?$f['LONumber']:'');
  if($type==='DO'&&preg_match('/(?:^|\n)\s*(?:NOMOR|NO\.?)\s*(?:D\.?\s*O\.?|DELIVERY\s*ORDER)\s*[:#\-]?\s*([A-Z0-9][A-Z0-9\/._\-]*)/iu',$text,$m))$reference=$m[1];
  $volume=$f['OrderedLiters'];$unit='L';if($volume===''&&preg_match('/(?:^|\n)\s*(?:QTY|QUANTITY|VOLUME|JUMLAH\s*(?:PENGIRIMAN|PENGISIAN|BBM)?)\s*[:=]?\s*([\d.,]+)\s*(KL|LIT[ER]+|LTR|L)\b/iu',$text,$m)){$v=$m[1];if(preg_match('/^\d{1,3}(?:\.\d{3})+(?:,\d+)?$/D',$v))$v=str_replace('.','',$v);$v=str_replace(',','.',$v);if(is_numeric($v)&&$v>0&&$v<=1000000000){$volume=(string)(float)$v;$unit=strtoupper($m[2])==='KL'?'KL':'L';}}
  $date=$f['DeliveryDate'];if($date===''&&preg_match('/(?:^|\n)\s*(?:TANGGAL|DATE|TGL(?:\s*DO)?)\s*[:=]?\s*(\d{1,4}[.\/-]\d{1,2}[.\/-]\d{2,4})/iu',$text,$m)){foreach(array('!d.m.Y','!d/m/Y','!d-m-Y','!Y-m-d') as $format){$d=DateTime::createFromFormat($format,$m[1]);$e=DateTime::getLastErrors();if($d&&(!$e||(!$e['warning_count']&&!$e['error_count']))){$date=$d->format('Y-m-d');break;}}}
  $density='';if(preg_match('/0[.,]\d{3,6}/',$f['DensityTemperature'],$m))$density=str_replace(',','.',$m[0]);
  $fields=array('ReferenceNo'=>$reference,'LoadingReference'=>$f['LONumber'],'VesselName'=>$delivery['VesselName'],'Volume'=>$volume,'VolumeUnit'=>$unit,'Density'=>$density,'DocumentDate'=>$date);
  $warnings=array();foreach(array('ReferenceNo'=>'Nomor dokumen','Volume'=>'Volume','DocumentDate'=>'Tanggal dokumen') as $key=>$label)if($fields[$key]==='')$warnings[]=$label.' belum terbaca; lengkapi dari dokumen.';
  if($type!==$requested)$warnings[]='Jenis surat dikenali sebagai '.$type.' dan pilihan disesuaikan.';$warnings[]='Kapal penerima mengikuti DO, bukan pelat kendaraan pada SPP. Periksa hasil sebelum upload dan View / Periksa.';return array('fields'=>$fields,'type'=>$type,'warnings'=>$warnings);
 }
}
