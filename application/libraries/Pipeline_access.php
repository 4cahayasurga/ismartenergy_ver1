<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pipeline_access {
 public function menus(){return array('transport_assign'=>'Penugasan Transportir','transport_portal'=>'Portal Transportir','partner_revenue'=>'Dashboard Bagi Hasil','customer_team'=>'Manajemen User','customer_team_toggle'=>'Akses Manajemen User Customer','customer_kkm'=>'Konfirmasi Pengisian','customer_supervision'=>'Approval Permohonan Bunkering','flow_approval'=>'Approval Pengisian BBM','flow_settings'=>'Persyaratan Upload Pipanisasi','customer_accounts'=>'Admin Kapal / Customers','customer_dashboard'=>'Dashboard Customer','customer_order'=>'Pesan BBM Pipanisasi','customer_logo'=>'Profil & Logo Perusahaan','customer_vessels'=>'Master Kapal & Kuota','customer_documents'=>'Dokumen Legalitas','customer_users'=>'Pengajuan User','customer_history'=>'Riwayat & Invoice','operator_dashboard'=>'Dashboard Operator','operator_queue'=>'Antrean Pengisian','operator_shift'=>'Rekap per Shift','operator_company'=>'Profil Perusahaan Operator','registration_approval'=>'Approval Registrasi / User Customer','dashboard'=>'Stock Movement','areas'=>'Basic Data Area','master'=>'Basic Data Kapal / Perusahaan','rates'=>'Basic Harga Pipanisasi','pass_rates'=>'Tarif Pass Pelabuhan','bastb'=>'BASTB BBM','rdt'=>'BA / RDT BBM','port_pass'=>'Pass Pelabuhan','filling'=>'Proses Pengisian','filling_actual'=>'Catat Pengisian Aktual dan Input Totalizer Akhir','orders'=>'Pesanan Pipanisasi','delivery'=>'Input Surat Pengantar Pengiriman','spp_ocr'=>'Upload / Foto Surat Pengantar Pengiriman','bunker'=>'Permohonan Bunker','ksop'=>'Upload Surat Pengawasan KSOP','invoices'=>'Create Invoice Pipanisasi','pipeline_documents'=>'Dokumen Surat Pengantar Pengiriman','export'=>'Ekspor Excel Pipanisasi','receivables'=>'Kartu Piutang Pipanisasi','users'=>'Pengaturan User','access_management'=>'Akses Management');}
 public function groups(){return array(
  'partners'=>array('label'=>'Mitra Pipanisasi','icon'=>'fa-handshake-o','keys'=>array('transport_portal','partner_revenue')),
  'customer'=>array('label'=>'Admin Kapal / Customers','icon'=>'fa-ship','keys'=>array('customer_team','customer_kkm','customer_supervision','customer_accounts','customer_dashboard','customer_order','customer_logo','customer_vessels','customer_documents','customer_users','customer_history')),
  'approval'=>array('label'=>'Approval','icon'=>'fa-check-square-o','keys'=>array('flow_approval')),
  'basic'=>array('label'=>'Data Utama','icon'=>'fa-database','keys'=>array('areas','master','rates','pass_rates')),
  'operation'=>array('label'=>'Operasional','icon'=>'fa-exchange','keys'=>array('orders','spp_ocr','ksop','filling','filling_actual','bunker','delivery','rdt','bastb','port_pass','invoices','pipeline_documents')),
  'report'=>array('label'=>'Laporan','icon'=>'fa-bar-chart','keys'=>array('export','receivables')),
  'settings'=>array('label'=>'Pengaturan','icon'=>'fa-cog','keys'=>array('customer_team_toggle','users','access_management','registration_approval','flow_settings'))
 );}
 public function links(){return array('transport_portal'=>'Pipeline_partner/transport','partner_revenue'=>'Pipeline_partner/revenue','customer_team'=>'Pipeline_customer_team','customer_team_toggle'=>'Pipeline_customer_team/settings','customer_kkm'=>'Pipeline_flow/approval','customer_supervision'=>'Pipeline_flow/supervision','flow_approval'=>'Pipeline_flow/approval','flow_settings'=>'Pipeline_flow/settings','customer_accounts'=>'Pipeline_customers','customer_dashboard'=>'Spbb/customer_dashboard','customer_order'=>'Pipeline_flow','customer_logo'=>'Pipeline_flow/profile','customer_vessels'=>'Spbb/customer_vessels','customer_documents'=>'Spbb/customer_documents','customer_users'=>'Spbb/customer_users','customer_history'=>'Spbb/customer_history','operator_dashboard'=>'Spbb_operator','operator_queue'=>'Spbb_operator/queue','operator_shift'=>'Spbb_operator/shift','operator_company'=>'Pipeline_operator_company','registration_approval'=>'Superadmin_registration','dashboard'=>'Pipanisasi','areas'=>'Pipeline_setup/areas','master'=>'Pipeline_setup/master','rates'=>'Pipeline_billing/rates','pass_rates'=>'Pipeline_documents/tariffs','bastb'=>'Pipeline_documents/index/BASTB','rdt'=>'Pipeline_documents/index/RDT','port_pass'=>'Pipeline_documents/index/PASS','filling'=>'Pipanisasi/filling','filling_actual'=>'Pipanisasi/filling_actual','orders'=>'Pipanisasi/orders','delivery'=>'Pipanisasi/delivery','spp_ocr'=>'Pipeline_spp','bunker'=>'Pipeline_setup/bunker','ksop'=>'Pipeline_setup/ksop','invoices'=>'Pipeline_billing/invoices','export'=>'Pipanisasi/export','receivables'=>'Pipeline_billing/receivables','users'=>'Pipeline_users','access_management'=>'Pipeline_users/access');}
 public function current($controller,$method){$c=strtolower((string)$controller);$m=strtolower((string)$method);if($c==='pipeline_partner')return $m==='revenue'?'partner_revenue':'transport_portal';if($c==='pipanisasi')return array('index'=>'dashboard',''=>'dashboard','orders'=>'orders','filling'=>'filling','filling_actual'=>'filling_actual','delivery'=>'delivery','export'=>'export')[$m]??'';
  if($c==='pipeline_documents'){if($m==='tariffs')return 'pass_rates';$kind=strtoupper((string)get_instance()->uri->segment(3));return array('BASTB'=>'bastb','RDT'=>'rdt','PASS'=>'port_pass')[$kind]??($m==='invoice'?'invoices':'');}if($c==='pipeline_spp')return 'spp_ocr';if($c==='pipeline_setup'){if(in_array($m,array('bunker','print_bunker'),true))return 'bunker';if(in_array($m,array('ksop','ksop_file'),true))return 'ksop';return in_array($m,array('master','areas'),true)?$m:'';}
  if($c==='pipeline_billing'){if($m==='rates')return 'rates';if($m==='receivables')return 'receivables';if(in_array($m,array('documents','document','file','print_do'),true))return 'delivery';return 'invoices';}
  if($c==='spbb'){return array('customer_dashboard'=>'customer_dashboard','customer_order'=>'customer_order','customer_pipeline'=>'customer_order','customer_vessels'=>'customer_vessels','customer_documents'=>'customer_documents','customer_users'=>'customer_users','customer_history'=>'customer_history')[$m]??'';}if($c==='spbb_operator')return $m==='queue'?'operator_queue':($m==='shift'?'operator_shift':'operator_dashboard');if($c==='pipeline_operator_company')return 'operator_company';if($c==='pipeline_customer_vessels')return get_instance()->session->userdata('USER_ROLE')==='CUSTOMER'?'customer_vessels':'customer_accounts';if($c==='pipeline_customers')return 'customer_accounts';if($c==='superadmin_registration')return 'registration_approval';if($c==='pipeline_customer_team')return $m==='settings'?'customer_team_toggle':'customer_team';if($c==='pipeline_flow' && $m==='supervision')return 'customer_supervision';if($c==='pipeline_flow')return $m==='profile'?'customer_logo':($m==='settings'?'flow_settings':($m==='approval'?'flow_approval':($m==='detail'?'orders':'customer_order')));if($c==='pipeline_users')return $m==='access'?'access_management':'users';return '';
 }
 public function allowed($menu,$user_id=null,$role=null){
  $ci=get_instance();$user_id=$user_id===null?$ci->session->userdata('MIS_LOGGED_ID'):$user_id;if(!is_string($user_id)||$user_id===''||!isset($this->menus()[$menu]))return false;
  if(strpos($menu,'operator_')===0)return false; // Antrean Operator SPBB dinonaktifkan sementara.
  if($role===null){$user=$ci->db->select('UserRole,Active')->where('UserID',$user_id)->get('Login')->row_array();if(!$user||(isset($user['Active'])&&$user['Active']!=='1'))return false;$role=$user['UserRole'];}
  if ($menu==='customer_supervision' || $role==='CUSTOMER') {
   $supervisor=$role==='CUSTOMER' && $ci->db->where('UserID',$user_id)->where('MenuKey','customer_supervision')->where('Allowed',1)->count_all_results('PipelineMenuAccess')>0;
   if ($menu==='customer_supervision' || $supervisor) return $supervisor && $menu==='customer_supervision';
  }
  if ($menu==='customer_team_toggle') {
   if (!$ci->db->table_exists('PipelineCustomerManagement')) return false;
   return $this->team_developer($user_id,$role) || ($role==='PEMILIK_SPBB' && $ci->db->where('UserID',$user_id)->where('MenuKey',$menu)->where('Allowed',1)->count_all_results('PipelineMenuAccess')>0);
  }
  if ($role==='CUSTOMER' && $ci->db->where('UserID',$user_id)->where('MenuKey','customer_kkm')->where('Allowed',1)->count_all_results('PipelineMenuAccess')>0) {
   return in_array($menu,array('customer_kkm','flow_approval'),true);
  }
  if ($menu==='customer_kkm') return false;
  if ($menu==='customer_team') {
   if ($role!=='CUSTOMER' || !$ci->db->table_exists('PipelineCustomerManagement')) return false;
   return $ci->db->from('PipelineMenuAccess A')->join('Login L','L.UserID=A.UserID')->join('PipelineCustomerManagement M','M.BusinessEntityID=L.BusinessEntityID')
    ->where('A.UserID',$user_id)->where('A.MenuKey','customer_team')->where('A.Allowed',1)->where('M.Enabled',1)->count_all_results()>0;
  }
  if($role==='OPERATOR'&&in_array($menu,array('orders','spp_ocr','filling','filling_actual'),true))return false;
  if(strpos($menu,'customer_')===0&&$menu!=='customer_accounts'&&$role!=='CUSTOMER')return false;if(strpos($menu,'operator_')===0&&$role==='CUSTOMER')return false;if($role==='SUPERADMIN'||strtolower($user_id)==='admin')return true;
  if(in_array($menu,array('filling','filling_actual'),true))return in_array($role,array('OPERATOR','PEMILIK_SPBB'),true)&&$this->allowed('orders',$user_id,$role);
  if($menu==='transport_assign')return false;
  $partnerMenu=array('TRANSPORTIR'=>'transport_portal','ASDP'=>'partner_revenue','IFPRO'=>'partner_revenue')[$role]??null;
  if($partnerMenu!==null){
   if($menu!==$partnerMenu)return false;
   $row=$ci->db->where('UserID',$user_id)->where('MenuKey',$menu)->get('PipelineMenuAccess')->row_array();
   return !$row || (bool)$row['Allowed'];
  }
  if(in_array($menu,array('transport_portal','partner_revenue'),true))return false;
  if(in_array($menu,array('areas','users','access_management','customer_accounts','registration_approval','flow_settings'),true))return false;
  if($role==='CUSTOMER'&&strpos($menu,'operator_')===0)return false;if($ci->db->table_exists('PipelineMenuAccess')){$row=$ci->db->where('UserID',$user_id)->where('MenuKey',$menu)->get('PipelineMenuAccess')->row_array();if($row)return (bool)$row['Allowed'];}
  if($menu==='customer_logo')return $this->allowed('customer_order',$user_id,$role);
  if(strpos($menu,'customer_')===0||strpos($menu,'operator_')===0||$menu==='flow_approval')return false;return $role==='PEMILIK_SPBB'||($role==='CUSTOMER'&&in_array($menu,array('dashboard','orders','export'),true));
 }
 public function team_developer($userId=null,$role=null)
 {
  $ci=get_instance();
  $ci->load->library('Pipeline_user_policy');
  return $ci->pipeline_user_policy->admin($userId??$ci->session->userdata('MIS_LOGGED_ID'),$role??$ci->session->userdata('USER_ROLE'));
 }
 public function landing(){$role=get_instance()->session->userdata('USER_ROLE');$preferred=$role==='CUSTOMER'?'customer_order':($role==='OPERATOR'?'operator_dashboard':'dashboard');if($this->allowed($preferred))return $this->links()[$preferred];if($this->allowed('dashboard'))return 'Pipanisasi';foreach($this->links() as $key=>$path)if($this->allowed($key))return $path;return 'Pipanisasi/no_access';}
 public function customer_vessel_allowed($user,$vessel){
  $ci=get_instance();
  if (!$ci->db->where('UserID',$user)->where('MenuKey','customer_kkm')->where('Allowed',1)->count_all_results('PipelineMenuAccess')) return true;
  return $ci->db->table_exists('PipelineCustomerVesselAccess') && $ci->db->from('PipelineCustomerVesselAccess A')
   ->join('JbtVessel V','V.VesselID=A.VesselID')->join('Login L','L.UserID=A.UserID AND L.BusinessEntityID=V.BusinessEntityID')
   ->where('A.UserID',$user)->where('A.VesselID',(int)$vessel)->where('V.IsActive',1)->where('L.Active','1')->count_all_results()>0;
 }
 public function require_menu($menu){if(!$this->allowed($menu))show_error('Anda tidak memiliki akses ke menu ini.',403);}
}
