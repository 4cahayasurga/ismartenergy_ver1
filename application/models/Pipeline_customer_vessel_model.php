<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_customer_vessel_model extends CI_Model
{
    private function scope()
    {
        $actor=$this->session->userdata('MIS_LOGGED_ID');
        $user=$this->db->select('UserID,UserRole,BusinessEntityID')->where('UserID',$actor)->where('Active','1')->get('Login')->row_array();
        if(!$user)throw new RuntimeException('Akun tidak aktif.');
        if($user['UserRole']==='SUPERADMIN'||strtolower($actor)==='admin'){
            $this->pipeline_access->require_menu('customer_accounts');
            return null;
        }
        $this->pipeline_access->require_menu('customer_vessels');
        if($user['UserRole']!=='CUSTOMER')show_error('Akses Master Kapal Customer ditolak.',403);
        return (int)$user['BusinessEntityID'];
    }

    public function listing()
    {
        $company=$this->scope();
        $this->db->select('V.*,B.CompanyName')->from('JbtVessel V')->join('JbtBusinessEntity B','B.BusinessEntityID=V.BusinessEntityID');
        if($company!==null)$this->db->where('V.BusinessEntityID',$company);
        return $this->db->order_by('B.CompanyName')->order_by('V.VesselName')->get()->result_array();
    }

    public function find($id)
    {
        $company=$this->scope();
        $this->db->select('V.*,B.CompanyName')->from('JbtVessel V')->join('JbtBusinessEntity B','B.BusinessEntityID=V.BusinessEntityID');
        if($company!==null)$this->db->where('V.BusinessEntityID',$company);
        return $this->db->where('V.VesselID',(int)$id)->get()->row_array();
    }

    public function history($id)
    {
        if(!$this->find($id))return array();
        return $this->db->select('A.*,L.UserName')->from('PipelineDocumentAudit A')->join('Login L','L.UserID=A.UserID','left')
            ->where('A.Kind','customer_vessel')->where('A.RecordID',(int)$id)->order_by('A.AuditID','DESC')->get()->result_array();
    }

    public function fields()
    {
        $fields=array('VesselName'=>array('Nama Kapal',150),'RegistrationNo'=>array('Nomor Registrasi',60),
            'VesselType'=>array('Jenis Kapal',80),'GrossTonnage'=>array('GT (Gross Tonnage)',14,true));
        if ($this->db->field_exists('DeadweightTonnage','JbtVessel')) {
            $fields['DeadweightTonnage']=array('DWT (ton)',14,true);
        }
        if ($this->db->field_exists('IMONumber','JbtVessel')) {
            $fields+=array('IMONumber'=>array('Nomor IMO',7),'RouteName'=>array('Lintasan',200),
                'MainTankLiters'=>array('Kapasitas Tangki Utama (L)',15,true),'DailyTankLiters'=>array('Kapasitas Tangki Harian / Samping (L)',15,true));
        }
        return $fields;
    }

    public function route_options($company=null)
    {
        $company=$this->scope()??$company;
        if (!$company || !$this->db->field_exists('RouteName','JbtVessel')) return array();
        $route='LTRIM(RTRIM(RouteName))';
        $rows=$this->db->distinct()->select($route.' AS RouteName',false)
            ->where('BusinessEntityID',(int)$company)->where($route.' <>','')
            ->order_by($route,'ASC',false)->get('JbtVessel')->result_array();
        return array_column($rows,'RouteName');
    }

    public function settings_ready()
    {
        return $this->db->field_exists('ShowCustomerQuota','PipelineFlowSettings');
    }

    public function settings()
    {
        $row=$this->settings_ready()?$this->db->where('ID',1)->get('PipelineFlowSettings')->row_array():null;
        return array('show_quota'=>(bool)($row['ShowCustomerQuota']??1),
            'required'=>array_merge(array('VesselName','RegistrationNo'),explode(',',(string)($row['RequiredVesselFields']??''))));
    }

    public function settings_update($input)
    {
        if (!$this->settings_ready() || !isset($input['VesselSettings'])) return array();
        $this->pipeline_access->require_menu('flow_settings');
        $keys=array_intersect(array_keys($this->fields()),(array)($input['RequiredVesselFields']??array()));
        return array('ShowCustomerQuota'=>($input['ShowCustomerQuota']??'')==='1'?1:0,'RequiredVesselFields'=>implode(',',$keys));
    }

    public function revision($row)
    {
        return hash('sha256',json_encode(array_intersect_key($row,array_flip(array_merge(
            array('VesselID','BusinessEntityID','IsActive'),array_keys($this->fields()))))));
    }

    public function save($id,$input)
    {
        $data=array();
        $required=$this->settings()['required'];
        foreach ($this->fields() as $key=>$field) {
            $value=$input[$key]??'';
            if (!is_string($value) || mb_strlen(trim($value))>$field[1]) throw new InvalidArgumentException($field[0].' tidak valid.');
            $value=trim($value);
            if ($value==='' && in_array($key,$required,true)) throw new InvalidArgumentException($field[0].' wajib diisi.');
            if (!empty($field[2]) && $value!=='' && !preg_match('/^\d{1,10}(?:\.\d{1,2})?$/D',$value)) throw new InvalidArgumentException($field[0].' harus berupa angka positif atau nol.');
            if ($key==='IMONumber' && $value!=='' && !preg_match('/^[0-9]{7}$/D',$value)) throw new InvalidArgumentException('Nomor IMO harus tujuh digit.');
            $data[$key]=$value===''&&$key!=='VesselType' ? ($key==='GrossTonnage'?'0':null) : $value;
        }
        $debug=$this->db->db_debug;
        $this->db->db_debug=false;
        $this->db->trans_begin();
        try{
            $lock=$this->db->query("DECLARE @r int;EXEC @r=sp_getapplock @Resource='customer-vessel-edit',@LockMode='Exclusive',@LockOwner='Transaction',@LockTimeout=10000;SELECT @r Result");
            if(!$lock||(int)$lock->row()->Result<0)throw new RuntimeException('Data kapal sedang diproses.');
            $before=$id?$this->find($id):null;
            $company=$before ? (int)$before['BusinessEntityID'] : $this->scope();
            if (($id&&!$before) || !$company) throw new RuntimeException('Kapal atau perusahaan tidak tersedia.');
            $version=$input['RevisionHash']??null;
            if($id && (!is_string($version)||!hash_equals($this->revision($before),$version)))throw new RuntimeException('Data kapal telah berubah. Muat ulang form.');
            $registration=preg_replace('/[^A-Z0-9]/','',mb_strtoupper($data['RegistrationNo']));
            if($registration==='')throw new RuntimeException('Nomor registrasi / IMO tidak valid.');
            $others=$this->db->select('RegistrationNo')->where('BusinessEntityID',$company)->where('VesselID !=',(int)$id)->get('JbtVessel')->result_array();
            foreach($others as $other){
                if($registration===preg_replace('/[^A-Z0-9]/','',mb_strtoupper($other['RegistrationNo'])))throw new RuntimeException('Nomor registrasi / IMO sudah dipakai kapal lain dalam perusahaan.');
            }
            $action=$id?'EDIT':'CREATE';
            if (!empty($data['IMONumber']) && $this->db->where('BusinessEntityID',$company)->where('VesselID !=',(int)$id)
                ->where('IMONumber',$data['IMONumber'])->count_all_results('JbtVessel')) throw new RuntimeException('Nomor IMO sudah dipakai kapal lain.');
            if ($id) {
                if (!$this->db->where('VesselID',(int)$id)->update('JbtVessel',$data)) throw new RuntimeException('Data kapal gagal disimpan.');
            } else {
                $this->load->model('Pipeline_operation_model','vessel_operations');
                $id=$this->vessel_operations->insert('JbtVessel','VesselID',$data+array('BusinessEntityID'=>$company,'IsActive'=>1,
                    'CreatedBy'=>$this->session->userdata('MIS_LOGGED_ID'),'CreatedAt'=>date('Y-m-d H:i:s')));
            }
            $after=$this->find($id);
            if(!$this->db->insert('PipelineDocumentAudit',array('Kind'=>'customer_vessel','RecordID'=>(int)$id,'Action'=>$action,
                'Snapshot'=>json_encode(array('Before'=>$before,'After'=>$after),JSON_UNESCAPED_UNICODE),
                'UserID'=>$this->session->userdata('MIS_LOGGED_ID'))))throw new RuntimeException('Log perubahan gagal disimpan.');
            if(!$this->db->trans_status())throw new RuntimeException('Penyimpanan dibatalkan karena kesalahan database.');
            $this->db->trans_commit();
        }catch(Throwable $e){$this->db->trans_rollback();throw $e;}
        finally{$this->db->db_debug=$debug;}
    }
}
