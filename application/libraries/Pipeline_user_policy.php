<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_user_policy {
 public function roles(){return array('SUPERADMIN'=>'Administrator','PEMILIK_SPBB'=>'Pengelola Area','OPERATOR'=>'Operator','CUSTOMER'=>'Customer','TRANSPORTIR'=>'Transportir','ASDP'=>'ASDP','IFPRO'=>'IFPRO');}
 public function admin($id,$role){return strtolower((string)$id)==='admin'||$role==='SUPERADMIN';}
 public function reserved(){return array('areas','users','access_management','customer_accounts','registration_approval','flow_settings','transport_assign');}
 public function profile($input,$creating=false){
  $out=array();foreach(array('UserID'=>30,'UserName'=>30,'Email'=>100,'UserRole'=>30,'Active'=>1) as $key=>$limit){$v=isset($input[$key])?$input[$key]:'';if(!is_string($v)||mb_strlen($v)>$limit)throw new InvalidArgumentException($key.' tidak valid.');$out[$key]=trim($v);}
  if(!preg_match('/^[A-Za-z0-9_.-]{1,30}$/D',$out['UserID']))throw new InvalidArgumentException('User ID wajib 1-30 huruf, angka, titik, strip atau underscore.');
  if($out['UserName']==='')throw new InvalidArgumentException('Nama user wajib diisi, maksimal 30 karakter.');
  if($out['Email']!==''&&!filter_var($out['Email'],FILTER_VALIDATE_EMAIL))throw new InvalidArgumentException('Format email tidak valid.');
  if(!isset($this->roles()[$out['UserRole']])||!in_array($out['Active'],array('0','1'),true))throw new InvalidArgumentException('Role atau status tidak valid.');
  $password=isset($input['Password'])?$input['Password']:'';$confirm=isset($input['ConfirmPassword'])?$input['ConfirmPassword']:'';
  if(!is_string($password)||!is_string($confirm))throw new InvalidArgumentException('Password tidak valid.');
  if($creating||$password!==''){
   if(strlen($password)<8||strlen($password)>72)throw new InvalidArgumentException('Password wajib 8-72 byte.');
   if($password!==$confirm)throw new InvalidArgumentException('Konfirmasi password tidak sama.');
   $out['PasswordHash']=password_hash($password,PASSWORD_DEFAULT);
  }elseif($confirm!=='')throw new InvalidArgumentException('Isi password baru sebelum konfirmasi.');
  return $out;
 }
 public function ids($values){if(!is_array($values)||count($values)>1000)throw new InvalidArgumentException('Daftar area tidak valid.');$ids=array();foreach($values as $v){if((!is_string($v)&&!is_int($v))||!ctype_digit((string)$v)||(int)$v<1)throw new InvalidArgumentException('ID area tidak valid.');$ids[]=(int)$v;}return array_values(array_unique($ids));}
 public function menu_keys($values,$known){if(!is_array($values)||count($values)>100)throw new InvalidArgumentException('Daftar menu tidak valid.');foreach($values as $v)if(!is_string($v)||!isset($known[$v])||in_array($v,$this->reserved(),true))throw new InvalidArgumentException('Menu tidak valid atau khusus administrator.');return array_values(array_unique($values));}
 public function defaults($role){if($role==='TRANSPORTIR')return array('transport_portal');if(in_array($role,array('ASDP','IFPRO'),true))return array('partner_revenue');if($role==='PEMILIK_SPBB')return array('master','rates','dashboard','orders','delivery','spp_ocr','bunker','ksop','invoices','receivables','export','pipeline_documents');if($role==='OPERATOR')return array('operator_dashboard','operator_queue','operator_shift','operator_company','dashboard','delivery','spp_ocr','bunker','ksop','pipeline_documents');if($role==='CUSTOMER')return array('customer_dashboard','customer_order','customer_logo','customer_vessels','customer_documents','customer_users','customer_history','dashboard','orders','export');return array();}
}
