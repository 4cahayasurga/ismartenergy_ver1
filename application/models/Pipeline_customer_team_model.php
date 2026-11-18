<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pipeline_customer_team_model extends CI_Model
{
    public function ready()
    {
        return $this->db->table_exists('PipelineCustomerManagement') && $this->db->table_exists('PipelineCustomerVesselAccess')
            && $this->db->table_exists('PipelineCustomerPasswordInvite');
    }

    public function scopes()
    {
        $this->load->model('Spbb_model');
        return $this->developer()?array_column($this->Spbb_model->spbb_list(),'Area','SpbbID')
            :array_column($this->Spbb_model->spbb_list_by_ids($this->Spbb_model->get_role($this->actor())['spbb_ids']),'Area','SpbbID');
    }

    private function actor() { return $this->session->userdata('MIS_LOGGED_ID'); }
    private function developer() { return $this->pipeline_access->team_developer(); }

    public function companies()
    {
        if (!$this->pipeline_access->allowed('customer_team_toggle')) show_error('Akses pengaturan ditolak.',403);
        $ids=$this->developer()?null:array_keys($this->scopes());
        if ($ids===array()) return array();
        $this->db->select('B.BusinessEntityID,B.CompanyName,M.SpbbID,M.Enabled')->from('JbtBusinessEntity B')
            ->join('PipelineCustomerManagement M','M.BusinessEntityID=B.BusinessEntityID','left')->where('B.IsActive',1);
        if ($ids!==null) $this->db->where_in('M.SpbbID',$ids);
        return $this->db->order_by('B.CompanyName')->get()->result_array();
    }

    public function toggle($entity,$branch,$enabled)
    {
        if (!$this->pipeline_access->allowed('customer_team_toggle')) show_error('Akses pengaturan ditolak.',403);
        $this->transaction(function()use($entity,$branch,$enabled){
            $old=$this->db->where('BusinessEntityID',$entity)->get('PipelineCustomerManagement')->row_array();
            if (!$this->developer()) $branch=$old?(int)$old['SpbbID']:0;
            if (!isset($this->scopes()[$branch]) || !$this->db->where('BusinessEntityID',$entity)->where('IsActive',1)->count_all_results('JbtBusinessEntity')) {
                show_error('Perusahaan di luar cakupan cabang.',403);
            }
            $data=array('SpbbID'=>$branch,'Enabled'=>$enabled?1:0);
            if ($old) $this->db->where('BusinessEntityID',$entity)->update('PipelineCustomerManagement',$data);
            else $this->db->insert('PipelineCustomerManagement',$data+array('BusinessEntityID'=>$entity));
            $this->audit('company-'.$entity,'TEAM_TOGGLE',$data);
        });
    }

    public function company()
    {
        if (!$this->pipeline_access->allowed('customer_team')) show_error('Manajemen User belum diaktifkan atau akses ditolak.',403);
        $company=$this->db->select('B.BusinessEntityID,B.CompanyName,M.SpbbID,S.Area')->from('Login L')
            ->join('JbtBusinessEntity B','B.BusinessEntityID=L.BusinessEntityID')->join('PipelineCustomerManagement M','M.BusinessEntityID=B.BusinessEntityID')
            ->join('SPBB S','S.LedgerNo=M.SpbbID','left')->where('L.UserID',$this->actor())->where('M.Enabled',1)->get()->row_array();
        if (!$company) show_error('Manajemen User belum aktif.',403);
        return $company;
    }

    public function phone_column()
    {
        $fields=$this->db->list_fields('Login');
        foreach (array('Phone','PhoneNo','MobilePhone','NoHP','Handphone','Telp') as $column) {
            if (in_array($column,$fields,true)) return $column;
        }
        return null;
    }

    public function members($company)
    {
        $phone=$this->phone_column();
        if ($phone) $this->db->select('L.'.$phone.' AS TeamPhone');
        return $this->db->select("L.UserID,L.UserName,L.Email,L.Active,L.last_login,
            CASE WHEN EXISTS(SELECT 1 FROM PipelineMenuAccess A WHERE A.UserID=L.UserID AND A.MenuKey='customer_supervision' AND A.Allowed=1) THEN 'SUPERVISI'
            WHEN EXISTS(SELECT 1 FROM PipelineMenuAccess A WHERE A.UserID=L.UserID AND A.MenuKey='customer_kkm' AND A.Allowed=1) THEN 'KKM'
            WHEN EXISTS(SELECT 1 FROM PipelineMenuAccess A WHERE A.UserID=L.UserID AND A.MenuKey IN ('customer_team','customer_order') AND A.Allowed=1) THEN 'ADMIN' ELSE 'LEGACY' END TeamRole",false)
            ->from('Login L')->where('L.BusinessEntityID',$company)->where('L.UserRole','CUSTOMER')
            ->where('NOT EXISTS(SELECT 1 FROM PipelineUserSecurity S WHERE S.UserID=L.UserID AND S.DeletedAt IS NOT NULL)',null,false)
            ->order_by('L.UserName')->get()->result_array();
    }

    public function vessels($company)
    {
        return $this->db->select('VesselID,VesselName')->where('BusinessEntityID',$company)->where('IsActive',1)->order_by('VesselName')->get('JbtVessel')->result_array();
    }

    public function assignments($company)
    {
        $rows=$this->db->select('A.UserID,A.VesselID')->from('PipelineCustomerVesselAccess A')->join('JbtVessel V','V.VesselID=A.VesselID')
            ->where('V.BusinessEntityID',$company)->get()->result_array();
        $byUser=array();
        foreach ($rows as $row) $byUser[$row['UserID']][]=(int)$row['VesselID'];
        return $byUser;
    }

    public function save($operation,$input)
    {
        return $this->transaction(function()use($operation,$input){
            $company=$this->company();
            $id=trim((string)($input['UserID']??''));
            if (!preg_match('/^[A-Za-z0-9_.-]{4,30}$/D',$id) || strcasecmp($id,$this->actor())===0 || strtolower($id)==='admin') throw new RuntimeException('User ID tidak valid atau dilindungi.');
            $members=array_column($this->members($company['BusinessEntityID']),null,'UserID');
            $old=$members[$id]??null;
            if ($operation!=='create' && !$old) show_error('User tidak ditemukan.',404);
            if ($operation==='reset') {
                if ($old['Active']!=='1') throw new RuntimeException('Aktifkan akun sebelum reset.');
                $this->invalidate($id);
                return $this->invite($id);
            }
            $name=trim((string)($input['UserName']??''));$email=trim((string)($input['Email']??''));
            $role=$input['TeamRole']??'';$active=$input['Active']??'';
            if ($name==='' || mb_strlen($name)>30 || strlen($email)>100 || !filter_var($email,FILTER_VALIDATE_EMAIL)
                || !in_array($role,array('ADMIN','SUPERVISI','KKM'),true) || !in_array($active,array('0','1'),true)) throw new RuntimeException('Periksa nama, email, role dan status.');
            if ($operation==='create' && $this->db->query('SELECT TOP 1 UserID FROM Login WHERE LOWER(UserID)=LOWER(?) OR NIK=?',array($id,$id))->row_array()) throw new RuntimeException('User ID sudah digunakan.');
            if ($this->db->query('SELECT TOP 1 UserID FROM Login WHERE LOWER(Email)=LOWER(?) AND UserID<>?',array($email,$id))->row_array()) throw new RuntimeException('Email sudah digunakan.');
            if ($old && $old['TeamRole']==='ADMIN' && $old['Active']==='1' && ($role!=='ADMIN'||$active!=='1')
                && count(array_filter($members,function($user){return $user['TeamRole']==='ADMIN'&&$user['Active']==='1';}))<=1) throw new RuntimeException('Admin aktif terakhir harus dipertahankan.');
            $vessels=$input['VesselIDs']??array();
            if (!is_array($vessels) || count($vessels)>1000) throw new RuntimeException('Penugasan kapal tidak valid.');
            $vessels=$role==='KKM'?array_unique(array_map('intval',$vessels)):array();
            if ($role==='KKM' && (!$vessels || array_diff($vessels,array_column($this->vessels($company['BusinessEntityID']),'VesselID')))) throw new RuntimeException('Pilih kapal aktif dari perusahaan Anda.');
            $data=array('UserName'=>$name,'Email'=>$email,'Active'=>$active);
            $phoneColumn=$this->phone_column();
            if ($phoneColumn && array_key_exists('TeamPhone',$input)) {
                $phone=$input['TeamPhone'];
                if (!is_string($phone) || (trim($phone)!=='' && !preg_match('/^\+?[0-9() -]{6,20}$/D',trim($phone)))) {
                    throw new RuntimeException('Format nomor HP tidak valid.');
                }
                $data[$phoneColumn]=trim($phone);
            }
            if ($operation==='create') {
                $password=password_hash(bin2hex(random_bytes(32)),PASSWORD_DEFAULT);
                $this->db->insert('Login',$data+array('UserID'=>$id,'UserRole'=>'CUSTOMER','AksesCompany'=>$this->config->item('Company'),
                    'BusinessEntityID'=>$company['BusinessEntityID'],'SpbbID'=>$company['SpbbID'],'ChangePasswd'=>'1',
                    'PasswordCI'=>$password,'Password_dari_CI'=>$password,'Password'=>$password,'UserEntry'=>$this->actor(),'TimeEntry'=>date('Y-m-d H:i:s')));
            } else $this->db->where('UserID',$id)->where('BusinessEntityID',$company['BusinessEntityID'])->update('Login',$data);
            $keys=array('ADMIN'=>array('customer_team','customer_order','customer_dashboard','customer_history','customer_vessels','customer_documents','customer_logo','customer_users'),
                'SUPERVISI'=>array('customer_supervision'),'KKM'=>array('customer_kkm','flow_approval'))[$role];
            $this->db->where('UserID',$id)->delete('PipelineMenuAccess');
            foreach ($keys as $key) $this->db->insert('PipelineMenuAccess',array('UserID'=>$id,'MenuKey'=>$key,'Allowed'=>1));
            $this->db->where('UserID',$id)->delete('PipelineCustomerVesselAccess');
            foreach ($vessels as $vessel) $this->db->insert('PipelineCustomerVesselAccess',array('UserID'=>$id,'VesselID'=>$vessel));
            $this->touch($id);
            $this->audit($id,$operation==='create'?'TEAM_NEW':'TEAM_EDIT',array('Role'=>$role,'Active'=>$active,'Vessels'=>$vessels));
            if ($active==='0') $this->db->where('UserID',$id)->delete('PipelineCustomerPasswordInvite');
            return $active==='1' && ($operation==='create' || ($old && $old['Active']!=='1'))?$this->invite($id):null;
        });
    }

    private function touch($id)
    {
        $this->db->query('IF EXISTS(SELECT 1 FROM PipelineUserSecurity WHERE UserID=?) UPDATE PipelineUserSecurity SET AccessVersion=AccessVersion+1 WHERE UserID=? ELSE INSERT PipelineUserSecurity(UserID,AccessVersion) VALUES(?,2)',array($id,$id,$id));
    }

    private function invalidate($id)
    {
        $hash=password_hash(bin2hex(random_bytes(32)),PASSWORD_DEFAULT);
        $this->db->where('UserID',$id)->update('Login',array('PasswordCI'=>$hash,'Password_dari_CI'=>$hash,'Password'=>$hash));
        $this->touch($id);
    }

    private function invite($id)
    {
        $token=bin2hex(random_bytes(32));
        $this->db->where('UserID',$id)->delete('PipelineCustomerPasswordInvite');
        $this->db->query('INSERT PipelineCustomerPasswordInvite(UserID,TokenHash,ExpiresAt) VALUES(?,?,DATEADD(minute,60,SYSUTCDATETIME()))',array($id,hash('sha256',$token)));
        $this->audit($id,'TEAM_LINK',array());
        return $token;
    }

    public function accept($token,$password,$confirmation)
    {
        if (!is_string($token) || !preg_match('/^[a-f0-9]{64}$/D',$token) || !is_string($password) || strlen($password)<10
            || strlen($password)>72 || $password!==$confirmation) throw new RuntimeException('Tautan atau konfirmasi password tidak valid.');
        $this->transaction(function()use($token,$password){
            $row=$this->db->query("SELECT I.UserID FROM PipelineCustomerPasswordInvite I JOIN Login L ON L.UserID=I.UserID
                JOIN PipelineCustomerManagement M ON M.BusinessEntityID=L.BusinessEntityID
                WHERE I.TokenHash=? AND I.ExpiresAt>SYSUTCDATETIME() AND L.Active='1' AND L.UserRole='CUSTOMER' AND M.Enabled=1
                AND NOT EXISTS(SELECT 1 FROM PipelineUserSecurity S WHERE S.UserID=I.UserID AND S.DeletedAt IS NOT NULL)",array(hash('sha256',$token)))->row_array();
            if (!$row) throw new RuntimeException('Tautan sudah dipakai, kedaluwarsa, atau akun tidak tersedia.');
            $hash=password_hash($password,PASSWORD_DEFAULT);
            $this->db->where('UserID',$row['UserID'])->update('Login',array('PasswordCI'=>$hash,'Password_dari_CI'=>$hash,'Password'=>$hash));
            $this->db->where('UserID',$row['UserID'])->delete('PipelineCustomerPasswordInvite');
            $this->touch($row['UserID']);
            $this->audit($row['UserID'],'TEAM_SET',array(),$row['UserID']);
        });
    }

    private function transaction($callback)
    {
        $old=$this->db->db_debug;$this->db->db_debug=false;$this->db->trans_begin();
        try {
            $lock=$this->db->query("DECLARE @r int; EXEC @r=sp_getapplock @Resource='pipeline-user-management',@LockMode='Exclusive',@LockOwner='Transaction',@LockTimeout=10000; SELECT @r Result");
            if (!$lock || (int)$lock->row()->Result<0) throw new RuntimeException('Pengaturan user sedang diproses.');
            $result=$callback();
            if (!$this->db->trans_status() || !$this->db->trans_commit()) throw new RuntimeException('Pengaturan user gagal disimpan.');
            return $result;
        } catch(Throwable $e) {$this->db->trans_rollback();throw $e;}
        finally {$this->db->db_debug=$old;}
    }

    private function audit($target,$action,$data,$actor=null)
    {
        $this->db->insert('PipelineUserAudit',array('ActorID'=>$actor??$this->actor(),'TargetID'=>$target,'Action'=>$action,'Details'=>json_encode($data,JSON_UNESCAPED_UNICODE)));
    }
}
