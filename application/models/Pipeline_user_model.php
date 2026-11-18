<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_user_model extends CI_Model {
 public function __construct(){parent::__construct();$this->load->library('Pipeline_user_policy',null,'user_policy');$this->load->library('Pipeline_access');}
 public function ready(){foreach(array('PipelineUserSecurity','PipelineUserAudit','PipelineMenuAccess','SpbbUserAccess') as $t)if(!$this->db->table_exists($t))return false;return true;}
 private function profile_query(){return $this->db->select('L.UserID,L.UserName,L.Email,L.UserRole,L.Active,L.SpbbID,B.CompanyName,ISNULL(S.AccessVersion,1) AccessVersion,S.DeletedAt',false)->from('Login L')->join('PipelineUserSecurity S','S.UserID=L.UserID','left')->join('JbtBusinessEntity B','B.BusinessEntityID=L.BusinessEntityID','left');}
 public function user($id){return $this->profile_query()->where('L.UserID',$id)->get()->row_array();}
 public function listing($search='',$archived=false,$page=1){
  $filter=function()use($search,$archived){$this->profile_query();if(!$archived)$this->db->where('S.DeletedAt IS NULL',null,false);if($search!=='')$this->db->group_start()->like('L.UserID',$search)->or_like('L.UserName',$search)->or_like('L.Email',$search)->or_like('B.CompanyName',$search)->group_end();};
  $filter();$total=$this->db->count_all_results();$filter();$rows=$this->db->order_by('L.UserName')->order_by('L.UserID')->limit(50,($page-1)*50)->get()->result_array();return array('total'=>$total,'rows'=>$rows);
 }
 public function areas(){return $this->db->select('LedgerNo AS SpbbID,No_SPBB,Area')->where('Status','Aktif')->order_by('Area')->get('SPBB')->result_array();}
 public function area_ids($id){$rows=$this->db->select('SpbbID')->where('UserID',$id)->get('SpbbUserAccess')->result_array();$ids=array_map('intval',array_column($rows,'SpbbID'));if(!$ids){$u=$this->user($id);if($u&&$u['SpbbID'])$ids[]=(int)$u['SpbbID'];}return $ids;}
 public function menu_keys($id,$role){$rows=$this->db->where('UserID',$id)->get('PipelineMenuAccess')->result_array();$map=array();foreach($rows as $r)$map[$r['MenuKey']]=(bool)$r['Allowed'];$keys=array();foreach($this->pipeline_access->menus() as $key=>$label){if(in_array($key,$this->user_policy->reserved(),true))continue;if($map[$key]??$this->pipeline_access->allowed($key,$id,$role))$keys[]=$key;}return $keys;}
 public function audit($id){return $this->db->select('ActorID,Action,Details,CreatedAt')->where('TargetID',$id)->order_by('AuditID','DESC')->limit(20)->get('PipelineUserAudit')->result_array();}
 private function transaction($actor,$callback){
  $debug=$this->db->db_debug;$this->db->db_debug=false;$this->db->trans_begin();
  try{
   $q=$this->db->query("DECLARE @r int; EXEC @r=sp_getapplock @Resource='pipeline-user-management',@LockMode='Exclusive',@LockOwner='Transaction',@LockTimeout=10000; SELECT @r Result");if(!$q||(int)$q->row()->Result<0)throw new RuntimeException('Pengaturan user sedang diproses. Coba kembali.');
   $a=$this->user($actor);if(!$a||$a['Active']!=='1'||$a['DeletedAt']!==null||!$this->user_policy->admin($a['UserID'],$a['UserRole']))throw new RuntimeException('Hanya administrator aktif yang boleh mengelola user.');
   $result=$callback();if(!$this->db->trans_status())throw new RuntimeException('Transaksi gagal; perubahan dibatalkan.');$this->db->trans_commit();return $result;
  }catch(Exception $e){$this->db->trans_rollback();throw $e;}finally{$this->db->db_debug=$debug;}
 }
 private function checked($ok){if(!$ok)throw new RuntimeException('Data gagal disimpan. Periksa duplikasi User ID dan kolom isian.');}
 private function touch($id,$actor){
  $q=$this->db->query('IF NOT EXISTS(SELECT 1 FROM PipelineUserSecurity WHERE UserID=?) INSERT INTO PipelineUserSecurity(UserID) VALUES (?)',array($id,$id));$this->checked($q);
  $this->checked($this->db->query('UPDATE PipelineUserSecurity SET AccessVersion=AccessVersion+1,UpdatedBy=?,UpdatedAt=SYSDATETIME() WHERE UserID=?',array($actor,$id)));
  if($this->db->table_exists('SpbbApiToken'))$this->checked($this->db->where('UserID',$id)->delete('SpbbApiToken'));
  return (int)$this->user($id)['AccessVersion'];
 }
 private function record($actor,$id,$action,$details){$this->checked($this->db->insert('PipelineUserAudit',array('ActorID'=>$actor,'TargetID'=>$id,'Action'=>$action,'Details'=>json_encode($details,JSON_UNESCAPED_UNICODE))));}
 private function expected($user,$version){if(!$user||$user['DeletedAt']!==null)throw new RuntimeException('User tidak ditemukan atau sudah diarsipkan.');if((int)$user['AccessVersion']!==(int)$version)throw new RuntimeException('Data user berubah. Muat ulang halaman sebelum menyimpan.');}
 private function protect($old,$role,$active,$actor,$deleting=false){
  if(strcasecmp($old['UserID'],$actor)===0&&($deleting||$active!=='1'||$role!==$old['UserRole']))throw new RuntimeException('Akun sendiri tidak dapat dihapus, dinonaktifkan, atau diubah rolenya.');
  if(strtolower($old['UserID'])==='admin'&&($role!=='SUPERADMIN'||$active!=='1'||$deleting))throw new RuntimeException('Administrator utama harus tetap aktif.');
  if($this->user_policy->admin($old['UserID'],$old['UserRole'])&&($role!=='SUPERADMIN'||$active!=='1'||$deleting)){
   $count=$this->db->from('Login')->where('Active','1')->group_start()->where('UserRole','SUPERADMIN')->or_where('LOWER(UserID)=','admin')->group_end()->count_all_results();if($count<=1)throw new RuntimeException('Administrator aktif terakhir tidak dapat dihapus atau dibatasi.');
  }
 }
 private function replace_menus($id,$keys){$this->checked($this->db->where('UserID',$id)->delete('PipelineMenuAccess'));foreach($this->pipeline_access->menus() as $key=>$label)$this->checked($this->db->insert('PipelineMenuAccess',array('UserID'=>$id,'MenuKey'=>$key,'Allowed'=>in_array($key,$keys,true)?1:0)));}
 public function save_profile($input,$actor,$version=null,$creating=false){
  $data=$this->user_policy->profile($input,$creating);return $this->transaction($actor,function()use($data,$actor,$version,$creating){
   $id=$data['UserID'];$old=$this->user($id);if($creating){if($old)throw new RuntimeException('User ID sudah digunakan, termasuk pada arsip.');}else{$this->expected($old,$version);$this->protect($old,$data['UserRole'],$data['Active'],$actor);}
   $row=array('UserName'=>$data['UserName'],'Email'=>$data['Email'],'UserRole'=>$data['UserRole'],'Active'=>$data['Active']);
   if(isset($data['PasswordHash']))foreach(array('PasswordCI','Password_dari_CI','Password') as $key)$row[$key]=$data['PasswordHash'];
   if($creating){$row+=array('UserID'=>$id,'AksesCompany'=>$this->config->item('Company'),'ChangePasswd'=>'1','UserEntry'=>$actor,'TimeEntry'=>date('Y-m-d H:i:s'));$this->checked($this->db->insert('Login',$row));}
   else $this->checked($this->db->where('UserID',$id)->update('Login',$row));
   if($creating||$old['UserRole']!==$row['UserRole'])$this->replace_menus($id,$this->user_policy->defaults($row['UserRole']));
   $newVersion=$this->touch($id,$actor);$this->record($actor,$id,$creating?'CREATE':'UPDATE',array('UserName'=>$row['UserName'],'UserRole'=>$row['UserRole'],'Active'=>$row['Active'],'PasswordChanged'=>isset($data['PasswordHash'])));return array('UserID'=>$id,'AccessVersion'=>$newVersion);
  });
 }
 public function save_access($id,$areas,$menus,$actor,$version){
  $areas=$this->user_policy->ids($areas);$menus=$this->user_policy->menu_keys($menus,$this->pipeline_access->menus());
  return $this->transaction($actor,function()use($id,$areas,$menus,$actor,$version){
   $user=$this->user($id);$this->expected($user,$version);if($this->user_policy->admin($user['UserID'],$user['UserRole']))throw new RuntimeException('Administrator memiliki seluruh akses. Ubah role melalui Pengaturan User untuk membatasi akses.');
   $valid=array_map('intval',array_column($this->areas(),'SpbbID'));if(array_diff($areas,$valid))throw new RuntimeException('Area tidak tersedia atau sudah tidak aktif.');
   $this->checked($this->db->where('UserID',$id)->delete('SpbbUserAccess'));foreach($areas as $area)$this->checked($this->db->insert('SpbbUserAccess',array('UserID'=>$id,'SpbbID'=>$area,'CreatedBy'=>$actor)));
   $this->checked($this->db->where('UserID',$id)->update('Login',array('SpbbID'=>$areas?$areas[0]:null)));$this->replace_menus($id,$menus);$v=$this->touch($id,$actor);$this->record($actor,$id,'ACCESS',array('Areas'=>$areas,'Menus'=>$menus));return $v;
  });
 }
 public function archive($id,$actor,$version){return $this->transaction($actor,function()use($id,$actor,$version){$u=$this->user($id);$this->expected($u,$version);$this->protect($u,$u['UserRole'],'0',$actor,true);$this->checked($this->db->where('UserID',$id)->update('Login',array('Active'=>'0','SpbbID'=>null)));$this->touch($id,$actor);$this->checked($this->db->query('UPDATE PipelineUserSecurity SET DeletedAt=SYSDATETIME() WHERE UserID=?',array($id)));$this->record($actor,$id,'ARCHIVE',array('Active'=>'0'));});}
 public function restore($id,$actor,$version){return $this->transaction($actor,function()use($id,$actor,$version){$u=$this->user($id);if(!$u||$u['DeletedAt']===null||(int)$u['AccessVersion']!==(int)$version)throw new RuntimeException('Arsip berubah atau tidak ditemukan.');$this->checked($this->db->where('UserID',$id)->update('Login',array('Active'=>'0')));$this->checked($this->db->where('UserID',$id)->update('PipelineUserSecurity',array('DeletedAt'=>null)));$this->touch($id,$actor);$this->record($actor,$id,'RESTORE',array('Active'=>'0'));});}

 /** Hubungkan akun Customer ke badan usaha tanpa mengubah password atau role. */
 public function bind_customer($id,$nib,$company,$actor,$version){
  if(!is_string($id)||!is_string($nib)||!is_string($company)||!preg_match('/^[0-9]{5,20}$/D',$nib)||trim($company)===''||mb_strlen($company)>150)throw new RuntimeException('Pilih Customer, isi NIB 5–20 digit dan nama perusahaan maksimal 150 karakter.');
  return $this->transaction($actor,function()use($id,$nib,$company,$actor,$version){$user=$this->user($id);$this->expected($user,$version);if($user['UserRole']!=='CUSTOMER'||$user['DeletedAt']!==null)throw new RuntimeException('Pilih akun Customer yang belum diarsipkan.');
   $entity=$this->db->where('NIB',$nib)->get('JbtBusinessEntity')->row_array();if($entity){if(!(int)$entity['IsActive'])throw new RuntimeException('Badan usaha tidak aktif.');if(trim($entity['CompanyName'])!==trim($company))throw new RuntimeException('NIB sudah terdaftar dengan nama '.$entity['CompanyName'].'. Gunakan nama yang terdaftar.');$entityId=(int)$entity['BusinessEntityID'];}else{$q=$this->db->query('INSERT INTO JbtBusinessEntity(NIB,CompanyName,CreatedBy) OUTPUT INSERTED.BusinessEntityID ID VALUES(?,?,?)',array($nib,trim($company),$actor),true);$this->checked((bool)$q);$entityId=(int)$q->row()->ID;}
   $old=$this->db->select('BusinessEntityID,NIB')->where('UserID',$id)->get('Login')->row_array();$this->checked($this->db->where('UserID',$id)->update('Login',array('BusinessEntityID'=>$entityId,'NIB'=>$nib)));$this->touch($id,$actor);$this->record($actor,$id,'CUSTOMER_COMPANY',array('before'=>$old,'BusinessEntityID'=>$entityId,'NIB'=>$nib));return $entityId;
  });
 }
}
