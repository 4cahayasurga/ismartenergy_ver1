<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_flow_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pipeline_operation_model', 'operations');
        $this->load->library(array('Pipeline_access', 'Pipeline_bunker_letter', 'Pipeline_bunker_number'));
    }

    public function labels()
    {
        return array(
            'Supervision' => 'Surat Pengawasan Pengisian Bahan Bakar',
            'Notice' => 'Surat Pemberitahuan Melakukan Kegiatan Usaha ke Transportir',
            'Payment' => 'Bukti Bayar BBM ke Pertamina',
            'Signed' => 'Permohonan Bunker yang sudah ditandatangani',
        );
    }

    public function statuses()
    {
        return array('DRAFT'=>'Draf','SUBMITTED'=>'Menunggu Verfikasi Pihak Bunkering','REVISION'=>'Perlu Perbaikan',
            'WAIT_TRANSPORT'=>'Menunggu Verifikasi Surat Pengantar Pengiriman','TRANSPORT_CONFIRMED'=>'Siap Pengisian','FILLING'=>'Sedang Pengisian','FILLED'=>'Pengisian Selesai - Siap Buat BA',
            'BA_APPROVAL'=>'Menunggu Approval BA Pengisian','BASTB_APPROVAL'=>'Menunggu Approval BASTB','COMPLETED'=>'BASTB Terbit');
    }

    public function slots()
    {
        return array(
            'BUNKER_SUPERVISOR'=>array('Letter','Signatory','Supervisi Pelayaran — Permohonan Bunker'),
            'BA_CAPTAIN'=>array('BA','Captain','Nakhoda'),
            'BA_KKM'=>array('BA','ChiefEngineer','Kepala Kamar Mesin'),
            'BST_SUPERVISOR'=>array('BASTB','Supervisor','Supervisi Pelayaran'),
            'BST_KKM'=>array('BASTB','ChiefEngineer','Kepala Kamar Mesin'),
            'BST_OFFICER'=>array('BASTB','FuelOfficer','Petugas BBM'),
        );
    }

    public function actor()
    {
        return $this->session->userdata('MIS_LOGGED_ID');
    }

    public function company()
    {
        if ($this->session->userdata('USER_ROLE') !== 'CUSTOMER') return null;
        return $this->db->select('B.*')->from('JbtBusinessEntity B')->join('Login L','L.BusinessEntityID=B.BusinessEntityID')
            ->where('L.UserID',$this->actor())->where('B.IsActive',1)->get()->row_array();
    }

    public function staff()
    {
        return in_array($this->session->userdata('USER_ROLE'),array('PEMILIK_SPBB','SUPERADMIN'),true)
            && $this->pipeline_access->allowed('orders');
    }

    public function find($id)
    {
        $company = $this->company();
        $staff = $this->staff();
        $canApprove = $this->pipeline_access->allowed('flow_approval');
        $row = $this->db->where('RequestID',(int)$id)->get('PipelineFlowRequest')->row_array();
        if (!$row) return null;
        if ($company && (int)$company['BusinessEntityID']===(int)$row['BusinessEntityID']) {
            if ($this->pipeline_access->allowed('customer_order')) return $row;
            if ($this->pipeline_access->allowed('customer_supervision')
                && ($this->revision_assigned_to_supervisor($row) || ($this->supervisor_state($row)!==''
                    && (json_decode($row['Signers'],true)['BUNKER_SUPERVISOR']??null)===$this->actor()))) return $row;
            if ($canApprove && $this->pipeline_access->customer_vessel_allowed($this->actor(),$row['JbtVesselID']) && in_array($this->actor(),json_decode($row['Signers'],true),true)) return $row;
        }
        if ($staff && !in_array($row['Status'],array('DRAFT','REVISION'),true) && $this->approved_bunker($row) && $this->operations->billing->branch_allowed($row['SpbbID'])) return $row;
        return null;
    }

    public function listing($start=null,$end=null)
    {
        $company = $this->company();
        $areas = $this->staff() ? array_column($this->operations->billing->branches(),'SpbbID') : array();
        if ($company) $this->db->where('BusinessEntityID',$company['BusinessEntityID']);
        elseif ($areas) $this->db->where_in('SpbbID',$areas)->where('Status !=','DRAFT');
        else return array();
        if ($start) $this->db->where('RequestDate >=',$start);
        if ($end) $this->db->where('RequestDate <=',$end);
        $rows=$this->db->order_by('RequestID','DESC')->get('PipelineFlowRequest')->result_array();
        if ($company) return array_values(array_filter($rows,function($row){
            return $this->supervisor_state($row)!=='CANCELLED';
        }));
        return array_values(array_filter($rows,function($row){
            return $row['Status']!=='REVISION' && $this->approved_bunker($row);
        }));
    }

    public function verified_sources($row)
    {
        $ids=array();
        if ($this->db->table_exists('PipelineFlowSource')) {
            $ids=array_column($this->db->where('RequestID',$row['RequestID'])->get('PipelineFlowSource')->result_array(),'CaptureID');
        }
        if ($row['CaptureID']) $ids[]=$row['CaptureID'];
        if (!$ids) return array();
        return $this->db->where_in('CaptureID',array_unique($ids))->where('SpbbID',$row['SpbbID'])
            ->order_by('CaptureID')->get('PipelineSppCapture')->result_array();
    }

    public function source_total($row)
    {
        $total='0.000';
        foreach ($this->verified_sources($row) as $capture) $total=bcadd($total,(string)$capture['OrderedLiters'],3);
        return $total;
    }

    public function source_remaining($row)
    {
        return bcsub((string)json_decode($row['Letter'],true)['PlannedLiters'],$this->source_total($row),3);
    }

    public function aggregate_source($row)
    {
        $captures=$this->verified_sources($row);
        if (!$captures) return null;
        $first=$captures[0];
        foreach ($captures as $capture) if ($capture['Status']!=='CONFIRMED') return null;
        $first['OrderedLiters']=$this->source_total($row);
        return $first;
    }

    public function source_requests()
    {
        $areas=$this->staff()?array_column($this->operations->billing->branches(),'SpbbID'):array();
        if (!$areas) return array();
        $rows=$this->db->select('RequestID,RequestNo,SpbbID,Letter,Status,CaptureID')
            ->where_in('SpbbID',$areas)->where_in('Status',array('SUBMITTED','WAIT_TRANSPORT','TRANSPORT_CONFIRMED'))
            ->order_by('RequestID','DESC')->get('PipelineFlowRequest')->result_array();
        return array_column(array_filter($rows,function($row){
            return $this->approved_bunker($row) && bccomp($this->source_remaining($row),'0',3)>0;
        }),null,'RequestID');
    }

    public function settings()
    {
        $row = $this->db->where('ID',1)->get('PipelineFlowSettings')->row_array();
        $rules = array('Signed'=>true);
        foreach (array('Supervision','Notice','Payment') as $kind) $rules[$kind] = !$row || (bool)$row[$kind];
        return $rules;
    }

    public function show_transport_assignment()
    {
        $row=$this->db->select('ShowTransportAssignment')->where('ID',1)->get('PipelineFlowSettings')->row_array();
        return !empty($row['ShowTransportAssignment']);
    }

    public function save_settings($input)
    {
        return $this->operations->transaction(function () use ($input) {
            $data = array('UpdatedBy'=>$this->actor(),'UpdatedAt'=>date('Y-m-d H:i:s'));
            $data['ShowTransportAssignment']=($input['ShowTransportAssignment']??null)==='1'?1:0;
            $this->load->model('Pipeline_customer_vessel_model','customer_vessel_settings');
            $data+=$this->customer_vessel_settings->settings_update($input);
            foreach (array('Supervision','Notice','Payment') as $kind) $data[$kind] = ($input[$kind]??null)==='1' ? 1 : 0;
            if ($this->db->where('ID',1)->get('PipelineFlowSettings')->row_array()) {
                $this->db->where('ID',1)->update('PipelineFlowSettings',$data);
            } else {
                $this->db->insert('PipelineFlowSettings',$data+array('ID'=>1));
            }
            $this->operations->audit('customer_flow',0,'SETTINGS',$data);
        });
    }

    public function profile($company)
    {
        return $company ? $this->db->where('BusinessEntityID',$company['BusinessEntityID'])->get('PipelineShippingProfile')->row_array() : null;
    }

    public function members($company)
    {
        if (!$company) return array();
        $rows = $this->db->select('UserID,UserName')->where('BusinessEntityID',$company['BusinessEntityID'])
            ->where('UserRole','CUSTOMER')->where('Active','1')->order_by('UserName')->get('Login')->result_array();
        return array_values(array_filter($rows,function ($row) {
            return $this->pipeline_access->allowed('flow_approval',$row['UserID'],'CUSTOMER');
        }));
    }

    private function text($input,$name,$max,$required=true)
    {
        $raw = $input[$name]??'';
        if (!is_string($raw) || mb_strlen($raw)>$max || ($required && trim($raw)==='')) {
            throw new RuntimeException('Lengkapi kolom '.$name.' dengan nilai yang valid.');
        }
        return trim($raw);
    }

    private function json($data)
    {
        $encoded = json_encode($data,JSON_UNESCAPED_UNICODE);
        if ($encoded===false) throw new RuntimeException('Isi dokumen tidak valid.');
        return $encoded;
    }

    public function store($file,$logo=false)
    {
        if (!isset($file['tmp_name'],$file['error'],$file['size'],$file['name']) ||
            !is_string($file['tmp_name']) || !is_string($file['name']) ||
            $file['error']!==UPLOAD_ERR_OK || $file['size']>($logo?5:15)*1024*1024 || !is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException($logo?'Pilih logo JPG/PNG maksimal 5 MB.':'Pilih dokumen PDF/JPG/PNG maksimal 15 MB.');
        }
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $extensions = array('image/jpeg'=>'jpg','image/png'=>'png');
        if (!$logo) $extensions['application/pdf']='pdf';
        if (!isset($extensions[$mime])) throw new RuntimeException('Format file tidak didukung.');
        if ($mime==='application/pdf') {
            if (file_get_contents($file['tmp_name'],false,null,0,5)!=='%PDF-') throw new RuntimeException('PDF tidak valid.');
        } else {
            $size = @getimagesize($file['tmp_name']);
            if (!$size || $size[0]*$size[1]>16000000) throw new RuntimeException('Gambar maksimal 16 megapiksel.');
        }
        $dir = APPPATH.'storage/customer_flow/';
        if (!is_dir($dir) && !mkdir($dir,0700,true) && !is_dir($dir)) throw new RuntimeException('Penyimpanan belum tersedia.');
        $name = bin2hex(random_bytes(24)).'.'.$extensions[$mime];
        if ($logo) {
            $original = @imagecreatefromstring(file_get_contents($file['tmp_name']));
            if (!$original) throw new RuntimeException('Isi gambar logo tidak valid.');
            $ratio = min(360/imagesx($original),180/imagesy($original));
            $width = max(1,(int)round(imagesx($original)*$ratio));
            $height = max(1,(int)round(imagesy($original)*$ratio));
            $canvas = imagecreatetruecolor(360,180);
            imagefill($canvas,0,0,imagecolorallocate($canvas,255,255,255));
            imagecopyresampled($canvas,$original,(int)((360-$width)/2),(int)((180-$height)/2),0,0,$width,$height,imagesx($original),imagesy($original));
            $name = bin2hex(random_bytes(24)).'.png';
            $mime = 'image/png';
            $saved = imagepng($canvas,$dir.$name);
            imagedestroy($canvas);
            imagedestroy($original);
        } else {
            $saved = move_uploaded_file($file['tmp_name'],$dir.$name);
        }
        if (!$saved) throw new RuntimeException('Gagal menyimpan file.');
        return array('StoredName'=>$name,'OriginalName'=>mb_substr(basename(str_replace(chr(92),'/',$file['name'])),0,255),
            'Mime'=>$mime,'FileHash'=>hash_file('sha256',$dir.$name));
    }

    public function save_profile($company,$input,$file)
    {
        $new = null;
        try {
            return $this->operations->transaction(function () use ($company,$input,$file,&$new) {
                $old = $this->profile($company);
                $current=$this->company();
                if (!$current || (int)$current['BusinessEntityID']!==(int)$company['BusinessEntityID']) throw new RuntimeException('Perusahaan di luar akses Anda.');
                $companyName=$this->text($input,'CompanyName',200,false);
                if ($companyName==='') $companyName=$company['CompanyName'];

                $data = array('IssuerCode'=>$this->pipeline_bunker_number->code($this->text($input,'IssuerCode',30,false),30,true),
                    'Address'=>$this->text($input,'Address',500),'Phone'=>$this->text($input,'Phone',100,false),
                    'Website'=>$this->text($input,'Website',150,false),'UpdatedBy'=>$this->actor(),'UpdatedAt'=>date('Y-m-d H:i:s'));
                $this->load->library('Pipeline_bank_accounts');
                $accounts=$this->pipeline_bank_accounts->from_profile($old?:array());
                if(isset($input['BankAccountsPresent'])) {
                    $accounts=$this->pipeline_bank_accounts->validate($input['BankAccounts']??array());
                } elseif(array_key_exists('BankAccountNumber',$input)) {
                    $legacy=array();foreach(array('BankAccountName','BankName','BankAccountNumber') as $field)$legacy[$field]=$input[$field]??'';
                    if($accounts)$accounts[0]=$legacy;else $accounts=array($legacy);
                    $accounts=$this->pipeline_bank_accounts->validate($accounts);
                }
                $data['BankAccountsJson']=json_encode($accounts,JSON_UNESCAPED_UNICODE);
                foreach(array('BankAccountName','BankName','BankAccountNumber') as $field)$data[$field]=$accounts[0][$field]??'';
                if (!$this->db->where('BusinessEntityID',$company['BusinessEntityID'])->update('JbtBusinessEntity',array('CompanyName'=>$companyName))) throw new RuntimeException('Nama perusahaan gagal disimpan.');
                if ($file && $file['error']!==UPLOAD_ERR_NO_FILE) {
                    $new = $this->store($file,true);
                    $data += array('Logo'=>$new['StoredName'],'LogoMime'=>$new['Mime']);
                }
                if ($old) $this->db->where('BusinessEntityID',$company['BusinessEntityID'])->update('PipelineShippingProfile',$data);
                else {
                    if (!$new) throw new RuntimeException('Unggah logo perusahaan terlebih dahulu.');
                    $this->db->insert('PipelineShippingProfile',$data+array('BusinessEntityID'=>$company['BusinessEntityID']));
                }
                $this->operations->audit('customer_flow',(int)$company['BusinessEntityID'],'PROFILE',array('Before'=>$old,'After'=>$data,'CompanyNameBefore'=>$company['CompanyName'],'CompanyNameAfter'=>$companyName));
            });
        } catch (Throwable $error) {
            if ($new) @unlink(APPPATH.'storage/customer_flow/'.$new['StoredName']);
            throw $error;
        }
    }

    private function reserve_number($branch,$vessel,$issuer,$date)
    {
        $period = $this->pipeline_bunker_number->period($date);
        $lock = $this->db->query("DECLARE @r int; EXEC @r=sp_getapplock @Resource=?,@LockMode='Exclusive',@LockOwner='Transaction',@LockTimeout=10000; SELECT @r Result",
            array('pipeline-bunker/'.$branch.'/'.$period['year'].'-'.$period['month']));
        if (!$lock || (int)$lock->row()->Result<0) throw new RuntimeException('Nomor surat sedang diproses.');
        $key = array('SpbbID'=>$branch,'NumberingYear'=>$period['year'],'NumberingMonth'=>$period['month']);
        $old = $this->db->where($key)->get('PipelineBunkerSequence')->row_array();
        $next = (int)($old['LastNumber']??0);
        do {
            $number=$this->pipeline_bunker_number->format(++$next,$vessel,$issuer,$date);
        } while ($this->request_number_exists($branch,$number));
        if ($old) $this->db->where($key)->update('PipelineBunkerSequence',array('LastNumber'=>$next));
        else $this->db->insert('PipelineBunkerSequence',$key+array('LastNumber'=>$next));
        return $number;
    }

    private function request_number_exists($branch,$number)
    {
        return $this->db->where('SpbbID',$branch)->where('RequestNo',$number)->count_all_results('PipelineFlowRequest')>0;
    }

    /** Memeriksa kewajiban kapal sebelumnya dalam transaksi workflow yang sama. */
    private function assert_previous_requests_clear($companyId, $vesselId, $exceptId = 0)
    {
        $previous = $this->db->select('RequestID,RequestNo,Status,Requirements')
            ->where('BusinessEntityID', $companyId)->where('JbtVesselID', $vesselId)
            ->where('RequestID !=', $exceptId)->where_not_in('Status', array('DRAFT', 'CANCELLED', 'REJECTED'))
            ->order_by('RequestID', 'DESC')->get('PipelineFlowRequest')->result_array();
        foreach ($previous as $request) {
            $requirements = json_decode($request['Requirements'], true);
            if (in_array($requirements['SupervisorState'] ?? '',array('REJECTED','CANCELLED'),true)) continue;
            if (!empty($requirements['Supervision'])) {
                $files = $this->files($request['RequestID']);
                if (!isset($files['Supervision']) || !(int) $files['Supervision']['Reviewed']) {
                    throw new RuntimeException('Lengkapi verifikasi surat pengawasan pada pengajuan '.$request['RequestNo'].' terlebih dahulu.');
                }
            }
            if (in_array($request['Status'], array('FILLED','BA_APPROVAL','BASTB_APPROVAL'), true)) {
                $slot = $request['Status'] === 'BASTB_APPROVAL' ? 'BST_KKM' : 'BA_KKM';
                if (!isset($this->approvals($request['RequestID'])[$slot])) {
                    throw new RuntimeException('Pengajuan '.$request['RequestNo'].' masih memerlukan konfirmasi KKM.');
                }
            }
        }
    }

    public function create($input)
    {
        return $this->operations->transaction(function () use ($input) {
            $company = $this->company();
            $profile = $this->profile($company);
            if (!$profile) throw new RuntimeException('Lengkapi profil dan logo perusahaan dahulu.');
            $branches = array_column($this->operations->billing->branches(),null,'SpbbID');
            $area = $branches[(int)$this->text($input,'SpbbID',10)]??null;
            if (!$area) throw new RuntimeException('Pilih cabang yang diizinkan.');
            $vessel = $this->db->where('BusinessEntityID',$company['BusinessEntityID'])->where('IsActive',1)
                ->where('VesselID',(int)$this->text($input,'VesselID',10))->get('JbtVessel')->row_array();
            if (!$vessel) throw new RuntimeException('Pilih kapal aktif milik perusahaan.');
            $this->assert_previous_requests_clear($company['BusinessEntityID'], $vessel['VesselID']);
            $members = array_column($this->members($company),'UserName','UserID');
            $captain = $this->text($input,'CaptainUser',100);
            $kkm = $this->text($input,'KkmUser',100);
            $supervisor = $this->text($input,'SupervisorUser',100);
            if (!$this->pipeline_access->customer_vessel_allowed($kkm,$vessel['VesselID']) || !isset($members[$captain],$members[$kkm]) || !isset(($supervisors=$this->supervisors($company,$this->actor()))[$supervisor]) || count(array_unique(array($captain,$kkm,$supervisor)))!==3) {
                throw new RuntimeException('Pilih tiga akun perusahaan yang berbeda dengan akses Approval Pengisian.');
            }
            $date = date('Y-m-d');
            $input = array_replace($input,array('Captain'=>$members[$captain],'Signatory'=>$supervisors[$supervisor],
                'SignatoryTitle'=>'Supervisi Customer','Recipient'=>'Supervisi Customer — '.$company['CompanyName'],'Place'=>$area['Area'],'PortName'=>$area['Area'],
                'RecipientPlace'=>$area['Area'],'IssuerName'=>$company['CompanyName'],'FooterBranch'=>'',
                'IssuerAddress'=>$profile['Address'],'IssuerPhone'=>$profile['Phone'],'IssuerWebsite'=>$profile['Website']));
            $letter = $this->pipeline_bunker_letter->prepare(array('CompanyName'=>$company['CompanyName'],'VesselName'=>$vessel['VesselName']),$input);
            $issuer=trim((string)$profile['IssuerCode']);
            if ($issuer!=='') {
                $code=$this->pipeline_bunker_number->code($this->text($input,'VesselCode',20),20);
                $number=$this->reserve_number((int)$area['SpbbID'],$code,$issuer,$date);
            } else {
                $number=$this->text($input,'RequestNo',100);
                if (preg_match('/[[:cntrl:]]/u',$number)) throw new RuntimeException('Nomor surat tidak valid.');
                if ($this->request_number_exists((int)$area['SpbbID'],$number)) throw new RuntimeException('Nomor surat sudah digunakan di cabang ini.');
            }
            $letter += array('Logo'=>$profile['Logo'],'LogoMime'=>$profile['LogoMime'],'IssuerCode'=>$profile['IssuerCode']);
            $id = $this->operations->insert('PipelineFlowRequest','RequestID',array(
                'SpbbID'=>$area['SpbbID'],'BusinessEntityID'=>$company['BusinessEntityID'],'JbtVesselID'=>$vessel['VesselID'],
                'RequestNo'=>$number,'RequestDate'=>$date,'Status'=>'DRAFT','Letter'=>$this->json($letter),
                'Requirements'=>$this->json($this->settings()),'Signers'=>$this->json(array('BA_CAPTAIN'=>$captain,'BA_KKM'=>$kkm,
                    'BUNKER_SUPERVISOR'=>$supervisor,'BST_SUPERVISOR'=>$supervisor,'BST_KKM'=>$kkm)),'CreatedBy'=>$this->actor()));
            $this->operations->audit('customer_flow',$id,'DRAFT',array('RequestNo'=>$number));
            return $id;
        });
    }

    public function files($id)
    {
        return array_column($this->db->where('RequestID',(int)$id)->get('PipelineFlowFile')->result_array(),null,'Kind');
    }

    public function approvals($id)
    {
        return array_column($this->db->where('RequestID',(int)$id)->get('PipelineFlowApproval')->result_array(),null,'Slot');
    }

    public function is_customer_editor($row)
    {
        return $this->session->userdata('USER_ROLE')==='CUSTOMER' && $row['CreatedBy']===$this->actor()
            && $this->pipeline_access->allowed('customer_order') && !in_array($this->supervisor_state($row),array('PENDING','REJECTED','CANCELLED'),true);
    }

    public function may_manage_order($row)
    {
        return $this->is_customer_editor($row)
            && in_array($row['Status'],array('DRAFT','REVISION'),true)
            && empty($row['DeliveryID']) && empty($row['CaptureID'])
            && empty($row['BA']) && empty($row['BASTB']);
    }

    public function order_version($row)
    {
        return hash('sha256',$this->json(array($row['Status'],$row['Letter'],$row['Requirements'],$row['UpdatedAt'])));
    }

    private function update_plan($letter,$input)
    {
        $date=$this->text($input,'FillingDate',10);
        $parsed=DateTimeImmutable::createFromFormat('!Y-m-d',$date);
        if (!$parsed || $parsed->format('Y-m-d')!==$date) throw new RuntimeException('Tanggal rencana tidak valid.');
        $letter['FillingDate']=$date;
        $letter['PlannedLiters']=$this->branch_volume($input,'PlannedLiters');
        return $letter;
    }

    private function manage_order($row,$action,$input)
    {
        if (!$this->may_manage_order($row)) throw new RuntimeException('Pesanan tidak dapat diubah pada tahap ini.');
        if (!hash_equals($this->order_version($row),$this->text($input,'OrderVersion',64))) {
            throw new RuntimeException('Pesanan sudah berubah. Muat ulang halaman.');
        }
        $id=(int)$row['RequestID'];
        $captures=$this->db->where('ProposedRequestID',$id)->get('PipelineSppCapture')->result_array();
        foreach ($captures as $capture) {
            if (!empty($capture['DeliveryID']) || !empty($capture['DocumentID'])) {
                throw new RuntimeException('SPP terkait sudah digunakan oleh dokumen operasional.');
            }
        }
        if ($action==='cancel_order') {
            $this->db->where('ProposedRequestID',$id)->where('DeliveryID',null)->where('DocumentID',null)
                ->update('PipelineSppCapture',array('ProposedRequestID'=>null));
            $this->operations->audit('customer_flow',$id,'CANCEL_SPP',array('SPP'=>array_column($captures,'CaptureID')));
            $requirements=(array)json_decode($row['Requirements'],true);
            $requirements['SupervisorState']='CANCELLED';
            $this->update($row,array('Status'=>'REVISION','Requirements'=>$this->json($requirements)),'CANCELLED');
            return;
        }
        $letter=$this->update_plan(json_decode($row['Letter'],true),$input);
        $letter['ApprovalRevision']=bin2hex(random_bytes(16));
        $this->operations->audit('customer_flow',$id,'PLAN_PRE',array('Letter'=>$row['Letter']));
        $this->update($row,array('Letter'=>$this->json($letter)),'PLAN_EDIT');
    }

    public function put_file($id,$kind,$file)
    {
        $stored = null;
        try {
            $this->operations->transaction(function () use ($id,$kind,$file,&$stored) {
                $row = $this->find($id);
                if (!$row || !$this->is_customer_editor($row) || !in_array($row['Status'],array('DRAFT','REVISION'),true)
                    || !isset($this->labels()[$kind])) throw new RuntimeException('Upload tidak diizinkan pada tahap ini.');
                $stored = $this->store($file);
                $key = array('RequestID'=>$id,'Kind'=>$kind);
                $old = $this->db->where($key)->get('PipelineFlowFile')->row_array();
                $data = $stored+array('UploadedBy'=>$this->actor(),'Reviewed'=>0,'ReviewedBy'=>null,'ReviewedAt'=>null);
                $data['VerificationJson']=null;
                if ($kind==='Payment') {
                    $this->load->library('Pipeline_payment_ocr');
                    $profile=$this->db->where('BusinessEntityID',$row['BusinessEntityID'])->get('PipelineShippingProfile')->row_array();
                    $verification=$this->pipeline_payment_ocr->read(APPPATH.'storage/customer_flow/'.$stored['StoredName'],$profile?:array());
                    $data['VerificationJson']=json_encode($verification,JSON_UNESCAPED_UNICODE);
                }

                if ($kind==='Signed') {
                    $approved=$this->approved_bunker($row);
                    $issued=$this->db->where('RequestID',$id)->get('PipelineFlowSignedPdf')->row_array();
                    $ok=$approved && $issued && hash_equals($approved['Token'],$issued['ApprovalToken'])
                        && hash_equals($approved['DocumentHash'],$issued['DocumentHash']) && hash_equals($stored['FileHash'],$issued['FileHash']);
                    if(!$ok) throw new RuntimeException('Unggah PDF asli dari tombol Unduh Permohonan Disetujui (QR). Dokumen belum disetujui, berubah, atau bukan milik pengajuan ini. Foto/scan perlu pemeriksaan dan belum diterima otomatis.');
                    $data['VerificationJson']=$this->json(array('status'=>'MATCH','message'=>'PDF asli dan QR approval cocok dengan catatan server.','approval'=>$approved,'checked_at'=>date('Y-m-d H:i:s')));
                } elseif ($kind==='Supervision') {
                    $this->load->library('Pipeline_flow_document_check');
                    $data['VerificationJson']=$this->json($this->pipeline_flow_document_check->read(APPPATH.'storage/customer_flow/'.$stored['StoredName'],$row,$kind));
                }
                if ($old) $this->db->where($key)->update('PipelineFlowFile',$data);
                else $this->db->insert('PipelineFlowFile',$data+$key);
                $this->operations->audit('customer_flow',$id,'UPLOAD',array('Kind'=>$kind,'File'=>$stored,'Previous'=>$old));
            });
        } catch (Throwable $error) {
            if ($stored) @unlink(APPPATH.'storage/customer_flow/'.$stored['StoredName']);
            throw $error;
        }
    }

    public function can_review_file($row, $file)
    {
        return $file && !(int)$file['Reviewed'] && $this->staff()
            && $this->operations->billing->branch_allowed($row['SpbbID'])
            && in_array($row['Status'],array('SUBMITTED','WAIT_TRANSPORT','TRANSPORT_CONFIRMED'),true);
    }

    public function document_blockers($row, $reviewed, $files=null)
    {
        $files=$files ?? $this->files($row['RequestID']);
        $requirements=$this->document_requirements($row);
        $blockers=array();
        if (!$this->approved_bunker($row)) $blockers[]='Approval Supervisi Pelayaran belum sah.';
        foreach ($this->labels() as $kind=>$label) {
            $file=$files[$kind]??null;
            if (!$file) {
                if (!empty($requirements[$kind])) $blockers[]=$label.' belum diunggah.';
                continue;
            }
            $check=json_decode($file['VerificationJson']??'',true);
            if (($check['manual']['status']??'')==='REJECTED') {
                $blockers[]=$label.' ditolak: '.($check['manual']['note']??'');
            } elseif ($kind==='Signed' && ($check['status']??'')!=='MATCH') {
                $blockers[]=$label.' belum lulus verifikasi QR.';
            } elseif ($reviewed && !(int)$file['Reviewed']) {
                $blockers[]=$label.' menunggu verifikasi manual.';
            }
        }
        return $blockers;
    }

    public function document_requirements($row)
    {
        $rules=(array)json_decode($row['Requirements'],true);
        $rules['Supervision']=true;
        $rules['Payment']=true;
        return $rules+array_fill_keys(array_keys($this->labels()),false);
    }

    public function ready_for_transport($row,$reviewed)
    {
        $blockers=$this->document_blockers($row,$reviewed);
        if ($blockers) throw new RuntimeException(implode(' ',$blockers));
    }

    public function start_blockers($row,$files=null)
    {
        $blockers=$this->document_blockers($row,true,$files);
        $capture=$this->aggregate_source($row);
        $letter=json_decode($row['Letter'],true);
        if (!$capture || $capture['Status']!=='CONFIRMED' || (int)$capture['SpbbID']!==(int)$row['SpbbID']) {
            $blockers[]='SPP belum terverifikasi untuk cabang ini.';
        } elseif (bccomp((string)$capture['OrderedLiters'],'0',3)<=0
            || bccomp((string)$capture['OrderedLiters'],(string)$letter['PlannedLiters'],3)>0) {
            $blockers[]='Volume SPP harus positif dan tidak melebihi volume yang disetujui.';
        }
        return $blockers;
    }

    private function review_file($row,$action,$input)
    {
        $id=$row['RequestID'];
        $kind=$this->text($input,'Kind',20);
        $file=$this->files($id)[$kind]??null;
        if (!isset($this->labels()[$kind]) || !$this->can_review_file($row,$file)
            || !hash_equals($file['FileHash'],$this->text($input,'FileHash',64))) {
            throw new RuntimeException('Dokumen sudah diproses atau berubah. Muat ulang pesanan.');
        }
        if (!hash_equals(hash('sha256',$file['StoredName']), $this->text($input,'FileVersion',64))) {
            throw new RuntimeException('Dokumen sudah diganti. Muat ulang pesanan.');
        }
        $rejected=$action==='reject_file';
        $note=$this->text($input,'ReviewNote',1000,$rejected);
        $check=(array)json_decode($file['VerificationJson']??'',true);
        if (!$rejected && $kind==='Signed' && ($check['status']??'')!=='MATCH') throw new RuntimeException('QR persetujuan belum sah.');
        $at=date('Y-m-d H:i:s');
        $check['manual']=array('status'=>$rejected?'REJECTED':'APPROVED','note'=>$note,
            'name'=>$this->session->userdata('MIS_LOGGED_NAME'),'by'=>$this->actor(),'at'=>$at);
        $this->db->where('RequestID',$id)->where('Kind',$kind)->update('PipelineFlowFile',array(
            'Reviewed'=>$rejected?0:1,'ReviewedBy'=>$this->actor(),'ReviewedAt'=>$at,'VerificationJson'=>$this->json($check)));
        $this->operations->audit('customer_flow',$id,$rejected?'DOC_REJECT':'REVIEW',array(
            'Kind'=>$kind,'FileHash'=>$file['FileHash'],'Manual'=>$check['manual']));
        if ($rejected) {
            $this->return_for_revision($row,mb_substr($this->labels()[$kind].' ditolak: '.$note,0,1000),
                array('DeliveryID'=>null,'TotalizerStart'=>null));
        } elseif ($row['Status']==='SUBMITTED' && !$this->document_blockers($row,true)) {
            $this->update($row,array('Status'=>'WAIT_TRANSPORT','Note'=>''),'DOCS_READY');
        }
    }


    /** Membaca ulang dokumen tersimpan hanya atas permintaan pemilik pada tahap draf/revisi. */
    public function recheck_file($id,$kind)
    {
        return $this->operations->transaction(function()use($id,$kind){
            $row=$this->find($id);
            if(!$row || !$this->is_customer_editor($row) || !in_array($row['Status'],array('DRAFT','REVISION'),true) || !in_array($kind,array('Supervision','Payment'),true))throw new RuntimeException('Pemeriksaan ulang tidak diizinkan.');
            $file=$this->files($id)[$kind]??null;
            if(!$file || !preg_match('/^[a-f0-9]{48}\.(jpg|png|pdf)$/D',$file['StoredName']))throw new RuntimeException('Dokumen tidak tersedia.');
            $path=APPPATH.'storage/customer_flow/'.$file['StoredName'];
            if(!is_file($path) || !hash_equals($file['FileHash'],hash_file('sha256',$path)))throw new RuntimeException('File berubah. Unggah ulang dokumen.');
            if($kind==='Payment'){
                $this->load->library('Pipeline_payment_ocr');
                $profile=$this->db->where('BusinessEntityID',$row['BusinessEntityID'])->get('PipelineShippingProfile')->row_array();
                $check=$this->pipeline_payment_ocr->read($path,$profile?:array());
            }else{
                $this->load->library('Pipeline_flow_document_check');$check=$this->pipeline_flow_document_check->read($path,$row,$kind);
            }
            $this->db->where('RequestID',$id)->where('Kind',$kind)->update('PipelineFlowFile',array('VerificationJson'=>$this->json($check),'Reviewed'=>0,'ReviewedBy'=>null,'ReviewedAt'=>null));
            $this->operations->audit('customer_flow',$id,'RECHECK',array('Kind'=>$kind,'Previous'=>$file['VerificationJson'],'Result'=>$check));
        });
    }

    private function update($row,$data,$event)
    {
        $this->db->where('RequestID',$row['RequestID'])->update('PipelineFlowRequest',$data+array('UpdatedAt'=>date('Y-m-d H:i:s')));
        $this->operations->audit('customer_flow',(int)$row['RequestID'],$event,$data);
    }

    private function return_for_revision($row,$note,$extra=array())
    {
        $rules=(array)json_decode($row['Requirements'],true);
        $rules['SupervisorState']='REVISE';
        if ($this->db->table_exists('PipelineFlowSource')) {
            $this->operations->audit('customer_flow',$row['RequestID'],'SRC_RETURN',array('Sources'=>$this->verified_sources($row)));
            $this->db->where('RequestID',$row['RequestID'])->delete('PipelineFlowSource');
        }
        $this->update($row,array('Status'=>'REVISION','CaptureID'=>null,'SourceKey'=>null,'SourceKind'=>null,
            'Requirements'=>$this->json($rules),'Note'=>$note)+$extra,'RETURN');
    }

    public function change($id,$action,$input)
    {
        $this->operations->transaction(function () use ($id,$action,$input) {
            $row = $this->find($id);
            if (!$row) throw new RuntimeException('Pesanan tidak tersedia.');
            if (in_array($action,array('edit_order','cancel_order'),true)) {
                $this->manage_order($row,$action,$input);
                return;
            }
            $status = $row['Status'];
            if ($action==='submit' && $this->is_customer_editor($row) && in_array($status,array('DRAFT','REVISION'),true)) {
                $this->submit_supervision($row,$input);
                return;
            }
            if (in_array($action,array('revise_supervision','reject_supervision'),true)) {
                $this->decline_supervision($row,$action,$input);
                return;
            }
            if ($action==='confirm_filling') {
                $this->confirm_branch_filling($row,$input);
                return;
            }
            if (!$this->staff()) throw new RuntimeException('Aksi hanya tersedia untuk Admin Cabang.');
            $progress=$this->filling_progress($row);
            if ($action==='resolve_difference' && $status==='FILLED' && ($progress['BranchWorkflow']??'')==='REVIEW') {
                $progress['ReviewNote']=$this->text($input,'ReviewNote',1000);
                $progress['ResolvedLiters']=$progress['ReceivedLiters'];
                $progress['ReviewedBy']=$this->actor();
                $progress['ReviewedAt']=date(DATE_ATOM);
                $progress['BranchWorkflow']='KKM';
                $this->update($row,array('BA'=>$this->json($progress)),'KKM_REVIEW');
                return;
            }
            if (in_array($action,array('review','reject_file'),true)) {
                $this->review_file($row,$action,$input);
                return;
            }
            if ($action==='revise' && in_array($status,array('SUBMITTED','WAIT_TRANSPORT','TRANSPORT_CONFIRMED'),true)) {
                $this->db->where('RequestID',$id)->update('PipelineFlowFile',array('Reviewed'=>0,'ReviewedBy'=>null,'ReviewedAt'=>null));
                $this->return_for_revision($row,$this->text($input,'Note',1000));
                return;
            }
            if ($action==='check_documents' && $status==='SUBMITTED') {
                $this->ready_for_transport($row,true);
                $this->update($row,array('Status'=>'WAIT_TRANSPORT','Note'=>$this->text($input,'Note',1000,false)),'DOCS_READY');
                return;
            }
            if ($action==='verify_source' && $this->awaiting_source($row)) {
                $this->ready_for_transport($row,true);
                $kind=$input['SourceKind']??'';
                if (!in_array($kind,array('SPP','DO'),true) || ($input['ConfirmSource']??'')!=='1') {
                    throw new RuntimeException('Pilih jenis dokumen dan konfirmasi kecocokan dengan pesanan.');
                }
                $capture=$this->operations->spp->capture((int)$this->text($input,'CaptureID',10));
                if ($capture && in_array((int)$capture['CaptureID'],array_map('intval',array_column($this->verified_sources($row),'CaptureID')),true)) {
                    throw new RuntimeException('Dokumen sudah dipakai di permohonan ini.');
                }
                if (!$capture || bccomp((string)$capture['OrderedLiters'],$this->source_remaining($row),3)>0) {
                    throw new RuntimeException('Volume surat pengantar melebihi sisa permohonan '.$this->source_remaining($row).' L.');
                }
                $previous=$this->aggregate_source($row);
                if ($previous && mb_strtoupper(trim($previous['ProductName']))!==mb_strtoupper(trim($capture['ProductName']))) {
                    throw new RuntimeException('Produk surat pengantar harus sama dalam satu permohonan.');
                }
                $deliveryId=$input['LinkedDeliveryID']??'';
                if(!is_string($deliveryId)||($deliveryId!==''&&!ctype_digit($deliveryId)))throw new RuntimeException('Pilihan DO tidak valid.');
                $resolvedDelivery=(int)$deliveryId;
                $source=$this->verify_source($row,$capture,$input['ResolveLegacy']??null,$resolvedDelivery);
                $deliveryId=$resolvedDelivery?(string)$resolvedDelivery:'';
                $this->operations->audit('customer_flow',(int)$row['RequestID'],'SOURCE_ID',array(
                    'CaptureID'=>$capture['CaptureID'],'SPPIdentity'=>$capture['VehicleOrVessel'],
                    'VesselIdentity'=>$this->vessel_identity($row)));
                $this->db->insert('PipelineFlowSource',array('RequestID'=>$row['RequestID'],'CaptureID'=>$capture['CaptureID'],
                    'SourceKey'=>$source,'VerifiedBy'=>$this->actor()));
                $this->db->where('CaptureID',$capture['CaptureID'])->update('PipelineSppCapture',array('ProposedRequestID'=>$row['RequestID']));
                $linkedDelivery=$deliveryId!==''?$this->operations->billing->delivery((int)$deliveryId):null;
                $this->update($row,array('Status'=>'TRANSPORT_CONFIRMED','CaptureID'=>$row['CaptureID']?:$capture['CaptureID'],
                    'SourceKey'=>$row['SourceKey']?:$source,'SourceKind'=>$row['SourceKind']?:$kind,
                    'DeliveryID'=>$row['DeliveryID']?:($deliveryId!==''?(int)$deliveryId:null),
                    'TotalizerStart'=>$row['TotalizerStart']??($linkedDelivery['TotalizerStart']??null)),'SOURCE_OK');
                return;
            }
            if ($action==='start_filling' && $status==='TRANSPORT_CONFIRMED' && $row['CaptureID']) {
                $blockers=$this->start_blockers($row);
                if ($blockers) throw new RuntimeException(implode(' ',$blockers));
                $capture=$this->aggregate_source($row);
                $started=(new DateTimeImmutable('now',new DateTimeZone('Asia/Makassar')))->format('Y-m-d H:i:s');
                $progress=array('BranchWorkflow'=>'ACTIVE','StartedBy'=>$this->actor(),'StartedName'=>$this->session->userdata('MIS_LOGGED_NAME'),
                    'StartedAt'=>$started,'OrderedLiters'=>(string)$capture['OrderedLiters'],
                    'SourceDocuments'=>array_map(function($source){return array_intersect_key($source,array_flip(array('CaptureID','LONumber','ShipmentNo','OrderedLiters','FileHash')));},$this->verified_sources($row)));
                $this->update($row,array('Status'=>'FILLING','BA'=>$this->json($progress)),'FILL_START');
                return;
            }
            if($action==='finish_filling' && $status==='FILLING'){
                $this->finish_branch_filling($row,$input);
                return;
            }
            throw new RuntimeException('Tahap pesanan telah berubah atau aksi tidak tersedia.');
        });
    }

    public function filling_progress($row)
    {
        $data = json_decode($row['BA'] ?? '', true);
        return isset($data['BranchWorkflow']) ? $data : array();
    }

    public function filling_status($row)
    {
        $supervision=array('PENDING'=>'Menunggu Approval Supervisi','REVISE'=>'Perlu Revisi Customer','REJECTED'=>'Ditolak Supervisi','CANCELLED'=>'Dibatalkan');
        if (in_array($row['Status'],array('DRAFT','REVISION'),true) && isset($supervision[$this->supervisor_state($row)])) return $supervision[$this->supervisor_state($row)];
        if ($row['Status']==='SUBMITTED' && $this->supervisor_state($row)==='APPROVED') return 'Disetujui Supervisi · Menunggu Verifikasi Admin Cabang';
        $phase = $this->filling_progress($row)['BranchWorkflow'] ?? '';
        $labels = array('ACTIVE'=>'Proses Pengisian', 'KKM'=>'Menunggu Konfirmasi KKM',
            'REVIEW'=>'Review Selisih', 'FINAL'=>'Terkonfirmasi · BASTB Terbit');
        return $labels[$phase] ?? $this->statuses()[$row['Status']];
    }

    public function kkm_task($row)
    {
        $signers = json_decode($row['Signers'], true);
        return $row['Status']==='FILLED' && ($this->filling_progress($row)['BranchWorkflow'] ?? '')==='KKM'
            && $this->session->userdata('USER_ROLE')==='CUSTOMER' && ($signers['BA_KKM'] ?? null)===$this->actor()
            && $this->pipeline_access->allowed('flow_approval') && $this->pipeline_access->customer_vessel_allowed($this->actor(),$row['JbtVesselID']);
    }

    public function kkm_completed($row)
    {
        $signers=json_decode($row['Signers'],true);
        if ($row['Status']!=='COMPLETED' || $this->session->userdata('USER_ROLE')!=='CUSTOMER'
            || ($signers['BST_KKM']??$signers['BA_KKM']??null)!==$this->actor()
            || !$this->pipeline_access->customer_vessel_allowed($this->actor(),$row['JbtVesselID'])) return false;
        $proof=$this->approvals($row['RequestID'])['BST_KKM']??null;
        return $proof && $proof['UserID']===$this->actor();
    }

    private function branch_volume($input, $field)
    {
        $value = $this->text($input, $field, 20);
        if (!preg_match('/^\d{1,12}(?:\.\d{1,3})?$/D', $value) || bccomp($value, '0', 3)<=0) {
            throw new RuntimeException('Masukkan volume positif dengan maksimal tiga desimal.');
        }
        return bcadd($value, '0', 3);
    }

    private function finish_branch_filling($row, $input)
    {
        $actual = $this->branch_volume($input, 'VolumeLiters');
        $capture=$this->aggregate_source($row);
        if (!$capture || $capture['Status']!=='CONFIRMED' || bccomp($actual, (string)$capture['OrderedLiters'], 3)>0) {
            throw new RuntimeException('Volume aktual melebihi SPP atau SPP belum terkonfirmasi.');
        }
        $date = $this->text($input, 'FillingDate', 10);
        $time = $this->text($input, 'FillingTime', 5);
        $zone = new DateTimeZone('Asia/Makassar');
        $finished = DateTimeImmutable::createFromFormat('!Y-m-d H:i', "$date $time", $zone);
        $progress = $this->filling_progress($row);
        if (!$finished || $finished->format('Y-m-d H:i')!=="$date $time" || $finished>new DateTimeImmutable('now', $zone)
            || (isset($progress['StartedAt']) && $finished->format('Y-m-d H:i')<substr($progress['StartedAt'],0,16))) {
            throw new RuntimeException('Waktu selesai harus sesudah mulai dan tidak boleh di masa depan.');
        }
        $letter = json_decode($row['Letter'], true);
        $name = (string)$this->session->userdata('MIS_LOGGED_NAME');
        $data = array_replace($progress, array('BranchWorkflow'=>'KKM', 'VolumeLiters'=>$actual,
            'OrderedLiters'=>(string)$capture['OrderedLiters'], 'FillingDate'=>$date, 'FillingTime'=>$time,
            'FinishedBy'=>$this->actor(), 'FinishedName'=>$name, 'FinishedAt'=>(new DateTimeImmutable('now', $zone))->format('Y-m-d H:i:s'),
            'VesselName'=>$letter['VesselName'], 'CompanyName'=>$letter['CompanyName'], 'Location'=>$letter['PortName'],
            'Pier'=>$this->text($input,'Pier',100), 'FuelType'=>$capture['ProductName'], 'Place'=>$letter['Place'],
            'FillingNote'=>$this->text($input,'FillingNote',1000,false),
            'DocumentNo'=>'BASTB/'.$row['RequestID'].'/'.(!empty($letter['IssuerCode'])?$letter['IssuerCode'].'/':'').substr($date,0,4),
            'DocumentDate'=>date('Y-m-d')));
        $this->update($row, array('Status'=>'FILLED', 'BA'=>$this->json($data)), 'FILL_END');
    }

    private function confirm_branch_filling($row, $input)
    {
        if (!$this->kkm_task($row)) throw new RuntimeException('Konfirmasi hanya untuk KKM yang ditugaskan pada transaksi ini.');
        $data = $this->filling_progress($row);
        $received = $this->branch_volume($input,'ReceivedLiters');
        $note = $this->text($input,'KkmNote',1000,false);
        $difference = bcsub($received,$data['VolumeLiters'],3);
        $resolved = isset($data['ResolvedLiters']) && bccomp($received,$data['ResolvedLiters'],3)===0;
        $needsReview = bccomp($difference,'0',3)!==0 && !$resolved;
        if ($needsReview && $note==='') throw new RuntimeException('Catatan selisih wajib diisi.');
        $data = array_replace($data,array('ReceivedLiters'=>$received,'DifferenceLiters'=>$difference,'KkmNote'=>$note));
        if ($needsReview) {
            $data['BranchWorkflow']='REVIEW';
            unset($data['ResolvedLiters']);
            $this->update($row,array('BA'=>$this->json($data)),'KKM_DIFF');
            return;
        }
        $data['BranchWorkflow']='FINAL';
        $data['ChiefEngineer']=(string)$this->session->userdata('MIS_LOGGED_NAME');
        $data['FuelOfficer']=$data['FinishedName'];
        $data['Supervisor']=json_decode($row['Letter'],true)['Signatory'];
        $bastb=$data;
        $bastb['VolumeLiters']=$received;
        $snapshot=$this->json($bastb);
        $this->db->insert('PipelineFlowApproval',array('RequestID'=>$row['RequestID'],'Slot'=>'BST_KKM','UserID'=>$this->actor(),
            'Approver'=>$data['ChiefEngineer'],'DocumentNo'=>$data['DocumentNo'],'ApprovedAt'=>date(DATE_ATOM),
            'Token'=>bin2hex(random_bytes(24)),'DocumentHash'=>hash('sha256',$snapshot)));
        $this->update($row,array('Status'=>'COMPLETED','BA'=>$this->json($data),'BASTB'=>$snapshot),'KKM_OK');
    }


    public function captures($row)
    {
        $used=array_column($this->verified_sources($row),'CaptureID');
        if ($used) $this->db->where_not_in('S.CaptureID',$used);
        return $this->db->select('S.CaptureID,S.SpbbID,S.VehicleOrVessel,S.OriginalName,S.LONumber,S.ShipmentNo,S.DeliveryDate,S.OrderedLiters,S.Buyer,S.ProductName,S.FileHash')
            ->from('PipelineSppCapture S')->join('PipelineFlowRequest R','R.CaptureID=S.CaptureID','left')
            ->where('R.RequestID IS NULL',null,false)
            ->where('S.SpbbID',$row['SpbbID'])->group_start()->where('S.ProposedRequestID',null)->or_where('S.ProposedRequestID',$row['RequestID'])->group_end()->where('S.Status','CONFIRMED')->order_by('S.CaptureID','DESC')->get()->result_array();
    }

    public function awaiting_source($row)
    {
        return in_array($row['Status'],array('WAIT_TRANSPORT','TRANSPORT_CONFIRMED'),true)
            && bccomp($this->source_remaining($row),'0',3)>0;
    }

    public function source_conflicts($capture)
    {
        $lo=mb_strtoupper(trim((string)$capture['LONumber']));
        $shipment=mb_strtoupper(trim((string)$capture['ShipmentNo']));
        return $this->db->query("SELECT P.DocumentID,P.DeliveryID,P.ReferenceNo,P.Reviewed,P.FileHash,D.DONumber,
            CASE WHEN X.InvoiceID IS NULL THEN 0 ELSE 1 END AS Billed
            FROM PipelineDocument P JOIN PipelineDelivery D ON D.DeliveryID=P.DeliveryID
            LEFT JOIN PipelineInvoiceDelivery X ON X.DeliveryID=D.DeliveryID
            WHERE D.SpbbID=? AND P.DocumentType='SPP' AND
            (P.FileHash=? OR (?<>'' AND UPPER(LTRIM(RTRIM(P.LoadingReference)))=?) OR (?<>'' AND UPPER(LTRIM(RTRIM(P.ReferenceNo)))=?))
            ORDER BY P.DocumentID",
            array($capture['SpbbID'],$capture['FileHash'],$lo,$lo,$shipment,$shipment))->result_array();
    }

    private function verify_source($row,$capture,$resolveLegacy,&$deliveryId)
    {
        if (!$capture || (int)$capture['SpbbID']!==(int)$row['SpbbID'] || $capture['Status']!=='CONFIRMED')
            throw new RuntimeException('Pilih SPP terkonfirmasi pada cabang pesanan.');
        if (!empty($capture['ProposedRequestID']) && (int)$capture['ProposedRequestID']!==(int)$row['RequestID']) {
            throw new RuntimeException('SPP dipilih untuk permohonan lain.');
        }
        $source = $this->operations->spp_invoice->key($capture);
        if ($this->operations->spp->flow_request($capture)) {
            throw new RuntimeException('SPP atau referensinya sudah dipakai di pesanan lain.');
        }
        if ($this->db->where('CaptureID',$capture['CaptureID'])->or_group_start()->where('SpbbID',$row['SpbbID'])->where('SourceKey',$source)->group_end()->get('PipelineOperation')->row_array()) {
            throw new RuntimeException('SPP atau referensinya sudah digunakan pada alur BASTB/RDT.');
        }
        $differences=$this->source_mismatches($row,$capture,$deliveryId);
        if ($differences) throw new RuntimeException(implode(' ', $differences));
        $legacy=$this->source_conflicts($capture);
        if (!$deliveryId && !$resolveLegacy && count($legacy)===1) {
            $doc=$legacy[0];
            if (!$doc['Billed'] && $doc['Reviewed'] && hash_equals($doc['FileHash'],$capture['FileHash'])) {
                $deliveryId=(int)$doc['DeliveryID'];
            }
        }
        if ($capture['DeliveryID'] && (int)$capture['DeliveryID']!==$deliveryId) {
            throw new RuntimeException('Gunakan DO yang sudah terkait dengan arsip SPP ini.');
        }
        if($deliveryId){
            $differences=$this->source_mismatches($row,$capture,$deliveryId);
            if ($differences) throw new RuntimeException(implode(' ', $differences));
            if($resolveLegacy!==null)throw new RuntimeException('Pilih gunakan DO yang sudah ada atau lepas lampirannya. Gunakan satu pilihan saja.');
            $delivery=$this->operations->billing->delivery($deliveryId);
            $matching=array_filter($legacy,function($doc)use($deliveryId){return (int)$doc['DeliveryID']===$deliveryId;});
            if(!$delivery||!$matching||(int)$delivery['SpbbID']!==(int)$row['SpbbID']||$this->operations->billing->billed($deliveryId))throw new RuntimeException('Pilih DO belum ditagihkan yang memiliki SPP ini.');
            if($this->db->where('DeliveryID',$deliveryId)->count_all_results('PipelineFlowRequest'))throw new RuntimeException('DO sudah terkait permohonan lain.');
            $documents=$this->operations->billing->current_documents($deliveryId);
            $document=$documents['SPP']??null;
            if(!$document||!$document['Reviewed']||$document['FileHash']!==$capture['FileHash'])throw new RuntimeException('Periksa lampiran SPP pada DO sebelum menghubungkan pesanan.');
            if(substr($delivery['DeliveryDate'],0,10)!==substr($capture['DeliveryDate'],0,10)
                ||abs((float)$delivery['Quantity']-(float)$capture['OrderedLiters'])>0.0005)throw new RuntimeException('Tanggal dan volume DO harus sesuai SPP yang akan dihubungkan.');
            $legacy=array_values(array_filter($legacy,function($doc)use($deliveryId){return (int)$doc['DeliveryID']!==$deliveryId;}));
        }
        if ($legacy && !$resolveLegacy) throw new RuntimeException('SPP terkait dengan DO '.implode(', ',array_unique(array_column($legacy,'DONumber'))).'. Lihat pilihan di bagian Verifikasi Dokumen.');
        if ($resolveLegacy!==null && (!is_string($resolveLegacy) || !$legacy
            || !hash_equals($this->source_conflict_key($capture,$legacy),$resolveLegacy))) {
            throw new RuntimeException('Pilihan SPP atau lampiran DO berubah. Periksa kembali dokumen terkait.');
        }
        foreach ($legacy as $conflict) {
            $this->operations->billing->remove_spp_attachment_for_order($conflict['DocumentID'],$row);
        }
        return $source;
    }

    public function source_mismatches($row,$capture,$deliveryId=0)
    {
        $letter=json_decode($row['Letter'],true);
        $normalize=function($value){return preg_replace('/[^A-Z0-9]/','',mb_strtoupper((string)$value));};
        $vessel=$this->vessel_identity($row);
        $identity=$normalize($capture['VehicleOrVessel']);
        $identities=array($normalize($letter['VesselName']));
        if ($vessel && trim((string)$vessel['RegistrationNo'])!=='') {
            $identities[]=$normalize($vessel['RegistrationNo']);
        }
        if (!empty($vessel['IMONumber'])) {
            $identities[]=$vessel['IMONumber'];
            $identities[]='IMO'.$vessel['IMONumber'];
        }
        $differences=array();
        $registration=$vessel?$normalize($vessel['RegistrationNo']):'';
        $ocrRegistrationMatches=$registration!=='' && $identity!=='' && strlen($identity)===strlen($registration)
            && strtr($identity,array('8'=>'B','0'=>'O','1'=>'I','5'=>'S','2'=>'Z'))
                ===strtr($registration,array('8'=>'B','0'=>'O','1'=>'I','5'=>'S','2'=>'Z'));
        if ($deliveryId && ($identity==='' || (!in_array($identity,$identities,true) && !$ocrRegistrationMatches))) {
            $differences[]='Identitas SPP "'.$capture['VehicleOrVessel'].'" berbeda dari kapal "'.$letter['VesselName']
                .'" / registrasi "'.($vessel['RegistrationNo']??'belum diisi').'".';
        }
        if ($normalize($capture['Buyer'])==='' || $normalize($capture['Buyer'])!==$normalize($letter['CompanyName'])) {
            $differences[]='Pembeli SPP "'.$capture['Buyer'].'" berbeda dari perusahaan permohonan "'.$letter['CompanyName'].'".';
        }
        if (!$capture['DeliveryDate'] || substr((string)$capture['DeliveryDate'],0,10)!==$letter['FillingDate']) {
            $differences[]='Tanggal SPP '.substr((string)$capture['DeliveryDate'],0,10).' berbeda dari rencana pengisian '.$letter['FillingDate'].'.';
        }
        if (bccomp((string)$capture['OrderedLiters'],'0',3)<=0 || bccomp((string)$capture['OrderedLiters'],(string)$letter['PlannedLiters'],3)>0) {
            $differences[]='Volume SPP '.$capture['OrderedLiters'].' L harus positif dan maksimal '.$letter['PlannedLiters'].' L.';
        }
        if (trim((string)$capture['ProductName'])==='') $differences[]='Produk pada SPP belum diisi.';
        return $differences;
    }

    public function vessel_identity($row)
    {
        $fields='VesselID,VesselName,RegistrationNo';
        if ($this->db->field_exists('IMONumber','JbtVessel')) $fields.=',IMONumber';
        return $this->db->select($fields)
            ->where('VesselID',(int)$row['JbtVesselID'])
            ->where('BusinessEntityID',(int)$row['BusinessEntityID'])
            ->get('JbtVessel')->row_array();
    }

    public function delivery_request($id)
    {
        $row=$this->db->select('RequestID')->where('DeliveryID',(int)$id)->get('PipelineFlowRequest')->row_array();
        return $row?$this->find($row['RequestID']):null;
    }

    public function source_conflict_key($capture,$conflicts)
    {
        return hash('sha256',json_encode(array((int)$capture['CaptureID'],array_column($conflicts,'DocumentID'))));
    }

    private function finish($row,$input)
    {
        $this->load->library('Pipeline_finance');
        list($start,$end)=$this->pipeline_finance->totalizers($this->text($input,'TotalizerStart',30),$this->text($input,'TotalizerEnd',30));
        $capture=$this->aggregate_source($row);
        if(!$capture || round($end-$start,3)>(float)$capture['OrderedLiters'])throw new RuntimeException('Selisih totalizer melebihi volume SPP. Periksa angka awal dan akhir.');
        if($row['DeliveryID']){
            $delivery=$this->operations->billing->delivery($row['DeliveryID']);
            if(!$delivery||$this->operations->billing->billed($delivery['DeliveryID']))throw new RuntimeException('DO sudah ditagihkan atau tidak tersedia.');
            if(!$this->db->where('DeliveryID',$delivery['DeliveryID'])->update('PipelineDelivery',array('TotalizerStart'=>$start,'TotalizerEnd'=>$end,'TotalizerUpdatedBy'=>$this->actor(),'TotalizerUpdatedAt'=>date('Y-m-d H:i:s'))))throw new RuntimeException('Totalizer DO gagal disimpan.');
        }
        $this->update($row,array('Status'=>'FILLED','TotalizerStart'=>$start,'TotalizerEnd'=>$end),'FILL_END');
    }

    public function make_ba($id,$input)
    {
        $this->operations->transaction(function () use ($id,$input) {
            $row=$this->find($id);
            if (!$row || !$this->staff() || $row['Status']!=='FILLED' || $this->filling_progress($row)) throw new RuntimeException('Simpan totalizer akhir untuk menyelesaikan pengisian terlebih dahulu.');
            $this->ready_for_transport($row,true);
            $capture=$this->aggregate_source($row);
            if (!$capture || $capture['Status']!=='CONFIRMED') throw new RuntimeException('Dokumen sumber terverifikasi tidak tersedia.');
            $source=$row['SourceKey'];
            $letter = json_decode($row['Letter'],true);
            $signers = json_decode($row['Signers'],true);
            $signers['BST_OFFICER'] = $this->actor();
            if (!$this->pipeline_access->allowed('flow_approval')) throw new RuntimeException('Petugas BBM membutuhkan akses Approval Pengisian.');
            $users = array_column($this->db->select('UserID,UserName')->where_in('UserID',array_values(array_unique($signers)))
                ->where('Active','1')->get('Login')->result_array(),'UserName','UserID');
            foreach ($signers as $user) if (!isset($users[$user])) throw new RuntimeException('Akun penandatangan tidak aktif.');
            $actual = $this->text($input,'VolumeLiters',30);
            if(abs((float)$actual-((float)$row['TotalizerEnd']-(float)$row['TotalizerStart']))>0.0005)throw new RuntimeException('Volume BA harus sama dengan selisih totalizer yang sudah disimpan.');
            if ((string)$capture['ProductName']==='' || !$capture['DeliveryDate'] || (float)$capture['OrderedLiters']<=0) {
                throw new RuntimeException('Lengkapi produk, tanggal dan volume SPP terkonfirmasi.');
            }
            if ($this->text($input,'FillingDate',10)!==substr((string)$capture['DeliveryDate'],0,10)) throw new RuntimeException('Tanggal pengisian aktual harus sesuai dokumen terverifikasi.');
            if ((float)$actual>(float)$capture['OrderedLiters']) throw new RuntimeException('Volume aktual melebihi volume pada SPP.');
            $common = array('DocumentDate'=>date('Y-m-d'),'VesselName'=>$letter['VesselName'],'CompanyName'=>$letter['CompanyName'],
                'FillingDate'=>$this->text($input,'FillingDate',10),'FillingTime'=>$this->text($input,'FillingTime',5),
                'Pier'=>$this->text($input,'Pier',100),'Location'=>$this->text($input,'Location',200),'VolumeLiters'=>$actual,
                'ChiefEngineer'=>$users[$signers['BA_KKM']]);
            $suffix = '/'.$row['RequestID'].'/'.(!empty($letter['IssuerCode'])?$letter['IssuerCode'].'/':'').substr($common['FillingDate'],0,4);
            $ba = $this->operation_forms->validate('RDT',$common+array('DocumentNo'=>'BA'.$suffix,'Captain'=>$users[$signers['BA_CAPTAIN']])+$input);
            $totalizerEnd=(string)$row['TotalizerEnd'];
            if (!preg_match('/^\d{1,15}(?:\.\d{1,3})?$/D',$totalizerEnd)) {
                throw new InvalidArgumentException('Totalizer akhir harus berupa angka positif atau nol dengan maksimal tiga desimal.');
            }
            $ba['TotalizerEnd']=$totalizerEnd;
            $bastb = $this->operation_forms->validate('BASTB',$common+array('DocumentNo'=>'BASTB'.$suffix,'Supervisor'=>$users[$signers['BST_SUPERVISOR']],
                'FuelOfficer'=>$users[$signers['BST_OFFICER']],'FuelType'=>$capture['ProductName'],'Place'=>$letter['Place']));
            $this->update($row,array('Status'=>'BA_APPROVAL','CaptureID'=>$capture['CaptureID'],'SourceKey'=>$source,
                'BA'=>$this->json($ba),'BASTB'=>$this->json($bastb),'Signers'=>$this->json($signers)),'MAKE_BA');
        });
    }

    /** Memeriksa approval terhadap snapshot surat yang sekarang, bukan sekadar keberadaan QR. */
    public function approved_bunker($row)
    {
        if ($this->supervisor_state($row)==='CANCELLED') return null;
        if ($row['Status']==='REVISION') return null;
        $approval=$this->approvals($row['RequestID'])['BUNKER_SUPERVISOR']??null;
        return $approval && hash_equals($approval['DocumentHash'],hash('sha256',$row['Letter'])) && $approval['DocumentNo']===$row['RequestNo']?$approval:null;
    }
    public function bunker_pdf($row)
    {
        $approved=$this->approved_bunker($row);
        if(!$approved)return null;
        $issued=$this->db->where('RequestID',$row['RequestID'])->get('PipelineFlowSignedPdf')->row_array();
        if(!$issued || (int)($issued['QrFormatVersion']??1)!==2 || !hash_equals($issued['ApprovalToken'],$approved['Token']) || !hash_equals($issued['DocumentHash'],$approved['DocumentHash']))return null;
        if(!preg_match('/^[a-f0-9]{48}\.pdf$/D',$issued['StoredName']))return null;
        $path=APPPATH.'storage/customer_flow/'.$issued['StoredName'];
        return is_file($path) && hash_equals($issued['FileHash'],hash_file('sha256',$path))?$path:null;
    }
    public function save_bunker_pdf($id,$hash,$bytes)
    {
        $saved=null;
        try {return $this->operations->transaction(function()use($id,$hash,$bytes,&$saved){
            $row=$this->find($id);$approved=$row?$this->approved_bunker($row):null;
            if(!$approved || !hash_equals($approved['DocumentHash'],$hash))throw new RuntimeException('Approval surat berubah. Muat ulang dokumen.');
            $cached=$this->bunker_pdf($row);if($cached)return $cached;
            $name=bin2hex(random_bytes(24)).'.pdf';$saved=APPPATH.'storage/customer_flow/'.$name;
            if(file_put_contents($saved,$bytes,LOCK_EX)===false)throw new RuntimeException('PDF approval gagal disimpan.');
            $data=array('QrFormatVersion'=>2,'ApprovalToken'=>$approved['Token'],'DocumentHash'=>$hash,'FileHash'=>hash('sha256',$bytes),'StoredName'=>$name);
            if($this->db->where('RequestID',$id)->get('PipelineFlowSignedPdf')->row_array())$this->db->where('RequestID',$id)->update('PipelineFlowSignedPdf',$data);
            else $this->db->insert('PipelineFlowSignedPdf',$data+array('RequestID'=>$id));
            return $saved;
        });}catch(Throwable $e){if($saved && is_file($saved))unlink($saved);throw $e;}
    }
    /** Mendaftarkan PDF ber-QR milik pengajuan ini sebagai lampiran; approval tidak dibuat ulang. */
    public function attach_bunker_pdf($id)
    {
        return $this->operations->transaction(function()use($id){
            $row=$this->find($id);
            if(!$row || !in_array($row['Status'],array('DRAFT','REVISION','SUBMITTED'),true))throw new RuntimeException('Lampiran otomatis hanya untuk permohonan draf/revisi yang dapat diakses.');
            $approved=$this->approved_bunker($row);$path=$this->bunker_pdf($row);
            if(!$approved || !$path)throw new RuntimeException('PDF approval yang sah belum tersedia.');
            $old=$this->files($id)['Signed']??null;
            if($old)return false;
            $data=array('RequestID'=>$id,'Kind'=>'Signed','StoredName'=>basename($path),'OriginalName'=>'Permohonan-Disetujui-'.$id.'.pdf',
                'Mime'=>'application/pdf','FileHash'=>hash_file('sha256',$path),'UploadedBy'=>$this->actor(),'Reviewed'=>0,'ReviewedBy'=>null,'ReviewedAt'=>null,
                'VerificationJson'=>$this->json(array('status'=>'MATCH','message'=>'PDF asli ber-QR dilampirkan otomatis dari approval sah.','approval'=>$approved,'checked_at'=>date('Y-m-d H:i:s'))));
            if(!$this->db->insert('PipelineFlowFile',$data))throw new RuntimeException('PDF approval gagal dilampirkan.');
            $this->operations->audit('customer_flow',$id,'ATTACH_QR',array('FileHash'=>$data['FileHash'],'ApprovalToken'=>$approved['Token']));
            return true;
        });
    }
    public function supervisor_state($row)
    {
        return json_decode($row['Requirements']??'',true)['SupervisorState']??'';
    }

    public function supervisors($company,$exclude)
    {
        if (!$company) return array();
        $rows=$this->db->select('UserID,UserName')->where('BusinessEntityID',$company['BusinessEntityID'])
            ->where('UserRole','CUSTOMER')->where('Active','1')->where('UserID !=',$exclude)->order_by('UserName')->get('Login')->result_array();
        return array_filter(array_column($rows,'UserName','UserID'),function($id){
            return $this->pipeline_access->allowed('customer_supervision',$id,'CUSTOMER');
        },ARRAY_FILTER_USE_KEY);
    }

    public function revision_assigned_to_supervisor($row)
    {
        if ($row['Status']!=='REVISION' || !in_array($this->supervisor_state($row),array('','REVISE','APPROVED'),true)) return false;
        $signers=json_decode($row['Signers'],true);
        return ($signers['BUNKER_CHAIN'][0]??$signers['BUNKER_SUPERVISOR']??null)===$this->actor();
    }

    private function may_decide_supervision($row)
    {
        $company=$this->company();
        $signers=json_decode($row['Signers'],true);
        return $company && (int)$company['BusinessEntityID']===(int)$row['BusinessEntityID']
            && strcasecmp($row['CreatedBy'],$this->actor())!==0 && ($signers['BUNKER_SUPERVISOR']??null)===$this->actor()
            && $this->pipeline_access->allowed('customer_supervision') && $this->supervisor_state($row)==='PENDING'
            && in_array($row['Status'],array('DRAFT','REVISION'),true);
    }

    private function submit_supervision($row,$input)
    {
        $id=$row['RequestID'];
        $this->assert_previous_requests_clear($row['BusinessEntityID'],$row['JbtVesselID'],$id);
        $letter=json_decode($row['Letter'],true);
        $signers=json_decode($row['Signers'],true);
        $supervisor=$this->text($input,'SupervisorUser',100);
        $choices=$this->supervisors($this->company(),$row['CreatedBy']);
        if (!isset($choices[$supervisor]) || in_array($supervisor,array($signers['BA_CAPTAIN']??null,$signers['BA_KKM']??null),true)) {
            throw new RuntimeException('Pilih Supervisi Customer perusahaan yang berbeda dari pembuat, Nakhoda, dan KKM.');
        }
        $extra=$input['SupervisorExtra']??array();
        if (!is_array($extra) || count($extra)>4) throw new RuntimeException('Maksimal lima tingkat Supervisi.');
        $chain=array($supervisor);
        foreach ($extra as $uid) {
            if ($uid==='') continue;
            if (!is_string($uid) || !isset($choices[$uid]) || in_array($uid,$chain,true)
                || in_array($uid,array($signers['BA_CAPTAIN']??null,$signers['BA_KKM']??null),true)) {
                throw new RuntimeException('Setiap tingkat harus diisi akun Supervisi perusahaan yang berbeda.');
            }
            $chain[]=$uid;
        }
        $rules=$this->document_requirements($row);
        $files=$this->files($id);
        foreach ($this->labels() as $kind=>$label) {
            if ($kind==='Signed') continue;
            $check=json_decode($files[$kind]['VerificationJson']??'',true);
            if ((!empty($rules[$kind]) && !isset($files[$kind])) || ($check['manual']['status']??'')==='REJECTED') {
                throw new RuntimeException('Lengkapi atau ganti '.$label.' sebelum mengajukan ke Supervisi.');
            }
        }
        if ($row['Status']==='REVISION' && isset($input['FillingDate'],$input['PlannedLiters'])) {
            $letter=$this->update_plan($letter,$input);
        }
        $this->operations->audit('customer_flow',$id,'SUP_SUBMIT',array('PreviousLetter'=>$row['Letter'],
            'PreviousApprovals'=>array_intersect_key($this->approvals($id),array_flip($this->bunker_approval_slots())),
            'PreviousSigned'=>$files['Signed']??null,'SupervisorChain'=>$chain));
        $this->db->where('RequestID',$id)->where_in('Slot',$this->bunker_approval_slots())->delete('PipelineFlowApproval');
        $this->db->where('RequestID',$id)->where('Kind','Signed')->delete('PipelineFlowFile');
        $this->db->where('RequestID',$id)->where('Kind','Supervision')->update('PipelineFlowFile',array('Reviewed'=>0,'ReviewedBy'=>null,'ReviewedAt'=>null));
        $rules['SupervisorState']='PENDING';
        $signers['BUNKER_SUPERVISOR']=$supervisor;
        $signers['BUNKER_CHAIN']=$chain;
        $signers['BUNKER_STAGE']=0;
        $signers['BST_SUPERVISOR']=end($chain);
        $letter['Recipient']='Supervisi Customer — '.$letter['CompanyName'];
        $letter['Signatory']=$choices[$signers['BST_SUPERVISOR']];
        $letter['SignatoryTitle']='Supervisi Customer';
        $letter['ApprovalRevision']=bin2hex(random_bytes(16));
        $this->update($row,array('Letter'=>$this->json($letter),'Signers'=>$this->json($signers),
            'Requirements'=>$this->json($rules),'Note'=>''),'SUP_WAIT');
    }

    private function bunker_approval_slots()
    {
        return array('BUNKER_SUPERVISOR','BUNKER_STAGE_1','BUNKER_STAGE_2','BUNKER_STAGE_3','BUNKER_STAGE_4');
    }

    private function decline_supervision($row,$action,$input)
    {
        if (!$this->may_decide_supervision($row) || !hash_equals(hash('sha256',$row['Letter']),$this->text($input,'DocumentHash',64))) {
            throw new RuntimeException('Permohonan berubah atau tidak ditugaskan kepada Anda.');
        }
        $note=$this->text($input,'SupervisorNote',1000);
        $rules=$this->document_requirements($row);
        $rules['SupervisorState']=$action==='reject_supervision'?'REJECTED':'REVISE';
        $this->update($row,array('Status'=>'REVISION','Requirements'=>$this->json($rules),'Note'=>$note),'SUP_RETURN');
    }


    public function pending($row)
    {
        $done = $this->approvals($row['RequestID']);
        $signers = json_decode($row['Signers'],true);
        $doc = $this->may_decide_supervision($row)?'Letter':(array('BA_APPROVAL'=>'BA','BASTB_APPROVAL'=>'BASTB')[$row['Status']]??null);
        $signers['BUNKER_SUPERVISOR']=$signers['BUNKER_SUPERVISOR']??$signers['BST_SUPERVISOR']??null;
        return array_filter($this->slots(),function ($slot,$key) use ($doc,$done,$signers) {
            return $doc===$slot[0] && ($key==='BUNKER_SUPERVISOR' || $this->pipeline_access->allowed('flow_approval')) && !isset($done[$key]) && ($signers[$key]??null)===$this->actor();
        },ARRAY_FILTER_USE_BOTH);
    }

    public function approve($id,$slot,$hash,$input=array())
    {
        $this->operations->transaction(function () use ($id,$slot,$hash,$input) {
            $row = $this->find($id);
            if (!$row || !$this->pipeline_access->allowed($slot==='BUNKER_SUPERVISOR'?'customer_supervision':'flow_approval') || !isset($this->pending($row)[$slot])) {
                throw new RuntimeException('Approval ini tidak ditugaskan kepada Anda atau sudah diproses.');
            }
            list($kind,$field,$title) = $this->slots()[$slot];
            $snapshot = $row[$kind];
            if (!is_string($hash) || !hash_equals(hash('sha256',$snapshot),$hash)) throw new RuntimeException('Dokumen berubah. Tinjau ulang.');
            $doc = json_decode($snapshot,true);
            $name = $this->session->userdata('MIS_LOGGED_NAME');
            $signers=json_decode($row['Signers'],true);
            $stage=(int)($signers['BUNKER_STAGE']??0);
            $next=$kind==='Letter'?($signers['BUNKER_CHAIN'][$stage+1]??null):null;
            $recordSlot=$next?'BUNKER_STAGE_'.($stage+1):$slot;
            $this->db->insert('PipelineFlowApproval',array('RequestID'=>$id,'Slot'=>$recordSlot,'UserID'=>$this->actor(),'Approver'=>$name,
                'DocumentNo'=>$kind==='Letter'?$row['RequestNo']:$doc['DocumentNo'],'ApprovedAt'=>(new DateTimeImmutable('now',new DateTimeZone('Asia/Jakarta')))->format(DATE_ATOM),
                'Token'=>bin2hex(random_bytes(24)),'DocumentHash'=>$hash));
            if ($kind==='Letter') {
                $rules=$this->document_requirements($row);
                $note=$this->text($input,'SupervisorNote',1000,false);
                if ($next) {
                    $signers['BUNKER_SUPERVISOR']=$next;
                    $signers['BUNKER_STAGE']=$stage+1;
                    $this->operations->audit('customer_flow',$id,'SUP_STEP',array('Stage'=>$stage+1,'Note'=>$note,'Next'=>$next));
                    $this->update($row,array('Signers'=>$this->json($signers),'Note'=>''),'SUP_NEXT');
                    return;
                }
                $rules['SupervisorState']='APPROVED';
                $this->update($row,array('Status'=>'SUBMITTED','Requirements'=>$this->json($rules),'Note'=>$note),'SUP_OK');
                return;
            }
            $done = $this->approvals($id);
            $needed = array_filter($this->slots(),function ($s) use ($kind) { return $s[0]===$kind; });
            if ($kind!=='Letter' && !array_diff_key($needed,$done)) {
                $this->update($row,array('Status'=>$kind==='BA'?'BASTB_APPROVAL':'COMPLETED'),'APPROVE');
            } else {
                $this->operations->audit('customer_flow',$id,'APPROVE',array('Slot'=>$slot,'DocumentHash'=>$hash));
            }
        });
    }
}
