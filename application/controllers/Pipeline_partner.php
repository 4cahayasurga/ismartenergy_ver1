<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_partner extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->library('Pipeline_access');
        $this->load->model('Pipeline_partner_model','partners');
        $this->load->model('Pipeline_billing_model','billing');
        if (!$this->db->table_exists('PipelineTransportEvidence')) show_error('Jalankan migrasi Portal Mitra terlebih dahulu.',503);
        if (!$this->session->userdata('pipeline_form_token')) {
            $this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));
        }
    }

    private function render($view,$data)
    {
        $data['pipeline_token']=$this->session->userdata('pipeline_form_token');
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('pipeline/'.$view,$data);
        $this->load->view('templates/footer');
    }

    private function actor()
    {
        return $this->session->userdata('MIS_LOGGED_ID');
    }

    private function guard()
    {
        if($this->input->method()!=='post')show_error('Gunakan formulir yang tersedia.',405);
        $token=$this->input->post('pipeline_token');
        if(!is_string($token)||!hash_equals($this->session->userdata('pipeline_form_token'),$token))show_error('Muat ulang halaman sebelum menyimpan.',403);
    }

    private function year()
    {
        $year=$this->input->get('year')??date('Y');
        if(!is_string($year)||!preg_match('/^20[0-9]{2}$/D',$year))show_error('Tahun tidak valid.',422);
        return (int)$year;
    }

    public function transport()
    {
        $this->pipeline_access->require_menu('transport_portal');
        $year=$this->year();
        $orders=array();
        $liters=array_fill(1,12,0);
        foreach($this->partners->orders($this->actor()) as $row){
            $ba=json_decode($row['BA'],true);
            if(substr($ba['FillingDate']??'',0,4)!==(string)$year)continue;
            $orders[]=$ba+array('RequestNo'=>$row['RequestNo']);
            $liters[(int)substr($ba['FillingDate'],5,2)]+=(float)$ba['VolumeLiters'];
        }
        $invoices=$this->partners->invoices($this->actor());
        $billingMonths=array_fill(1,12,0);
        $outstandingMonths=array_fill(1,12,0);
        foreach($invoices as $row)if(substr($row['InvoiceDate'],0,4)===(string)$year){
            $month=(int)substr($row['InvoiceDate'],5,2);
            $billingMonths[$month]+=(float)$row['GrandTotal'];
            $outstandingMonths[$month]+=round((float)$row['GrandTotal']-(float)$row['Paid'],2);
        }
        $this->render('partner_transport',compact('orders','liters','invoices','billingMonths','outstandingMonths','year')+
            array('judul'=>'Portal Transportir','evidence'=>$this->partners->evidence($this->actor())));
    }

    public function revenue()
    {
        $this->pipeline_access->require_menu('partner_revenue');
        $role=$this->session->userdata('USER_ROLE');
        $roles=in_array($role,array('ASDP','IFPRO'),true)?array($role):array('ASDP','IFPRO');
        $year=$this->year();
        $months=$this->partners->revenue(array_column($this->billing->branches(),'SpbbID'),$year,$roles);
        $this->render('partner_revenue',compact('roles','year','months')+array('judul'=>'Dashboard Bagi Hasil Pipanisasi'));
    }

    public function assign($kind,$id)
    {
        $this->pipeline_access->require_menu('transport_assign');
        $this->guard();
        $id=(int)$id;
        $table=array('ORDER'=>array('PipelineFlowRequest','RequestID'),'INVOICE'=>array('PipelineInvoice','InvoiceID'))[$kind]??null;
        if($kind==='ORDER') {
            $this->load->model('Pipeline_flow_model','flow');
            if(!$this->flow->show_transport_assignment())show_error('Penugasan Transportir pada pesanan sedang disembunyikan.',403);
        }
        if(!$table || !$this->db->where($table[1],$id)->get($table[0])->row_array())show_404();
        $uid=$this->input->post('TransportUser');
        if(!is_string($uid) || !$this->db->where('UserID',$uid)->where('UserRole','TRANSPORTIR')->where('Active','1')->get('Login')->row_array()){
            show_error('Pilih akun Transportir aktif.',422);
        }
        $saved=$this->db->query("MERGE PipelineTransportAssignment WITH (HOLDLOCK) AS T
            USING (SELECT ? TargetKind,? TargetID) AS S ON S.TargetKind=T.TargetKind AND S.TargetID=T.TargetID
            WHEN MATCHED AND (T.UserID=? OR T.TargetKind<>'INVOICE' OR NOT EXISTS(
                SELECT 1 FROM PipelineTransportEvidence E WITH (UPDLOCK,HOLDLOCK) WHERE E.InvoiceID=T.TargetID))
            THEN UPDATE SET UserID=?,AssignedBy=?,AssignedAt=SYSDATETIME()
            WHEN NOT MATCHED THEN INSERT(TargetKind,TargetID,UserID,AssignedBy) VALUES(S.TargetKind,S.TargetID,?,?);",
            array($kind,$id,$uid,$uid,$this->actor(),$uid,$this->actor()));
        if(!$saved||$this->db->affected_rows()!==1)show_error('Penugasan tidak dapat diubah setelah bukti bayar tersimpan.',409);
        $this->session->set_flashdata('Berhasil','Transportir berhasil ditugaskan.');
        redirect($kind==='ORDER'?'Pipeline_flow/detail/'.$id:'Pipeline_billing/invoice/'.$id);
    }

    public function upload($id)
    {
        $this->pipeline_access->require_menu('transport_portal');
        $this->guard();
        $invoice=$this->partners->invoices($this->actor(),(int)$id)[0]??null;
        if(!$invoice)show_404();
        $stored=null;
        try{
            $reference=$this->input->post('ReferenceNo');
            $amount=$this->input->post('Amount');
            if(!is_string($reference)||trim($reference)===''||mb_strlen($reference)>100||!is_string($amount)
                || !preg_match('/^\d{1,15}(?:\.\d{1,2})?$/D',$amount) || (float)$amount<=0
                || (float)$amount>round((float)$invoice['GrandTotal']-(float)$invoice['Paid'],2)){
                throw new RuntimeException('Isi referensi dan jumlah pembayaran yang sesuai sisa utang.');
            }
            $this->load->model('Pipeline_flow_model','flow');
            $stored=$this->flow->store($_FILES['Evidence']??array());
            $debug=$this->db->db_debug;
            $this->db->db_debug=false;
            try {
                $saved=$this->db->query("INSERT INTO PipelineTransportEvidence (InvoiceID,UserID,ReferenceNo,Amount,StoredName,Mime,FileHash)
                    SELECT TargetID,UserID,?,?,?,?,? FROM PipelineTransportAssignment WITH (UPDLOCK,HOLDLOCK)
                    WHERE TargetKind='INVOICE' AND TargetID=? AND UserID=?",
                    array(trim($reference),$amount,$stored['StoredName'],$stored['Mime'],$stored['FileHash'],(int)$id,$this->actor()));
                if(!$saved || $this->db->affected_rows()!==1)throw new RuntimeException('Bukti gagal disimpan. Periksa penugasan dan referensi pembayaran.');
            } finally {
                $this->db->db_debug=$debug;
            }
            $this->session->set_flashdata('Berhasil','Bukti tersimpan untuk diperiksa petugas.');
        }catch(Throwable $e){
            if($stored)@unlink(APPPATH.'storage/customer_flow/'.$stored['StoredName']);
            $this->session->set_flashdata('Gagal',$e->getMessage());
        }
        redirect('Pipeline_partner/transport?year='.$this->year());
    }

    public function file($id)
    {
        $row=$this->db->where('EvidenceID',(int)$id)->get('PipelineTransportEvidence')->row_array();
        if(!$row)show_404();
        $own=$this->pipeline_access->allowed('transport_portal') && $row['UserID']===$this->actor()
            && $this->partners->invoices($this->actor(),$row['InvoiceID']);
        if(!$own && (!$this->pipeline_access->allowed('receivables')||!$this->billing->invoice($row['InvoiceID'])))show_404();
        if(!preg_match('/^[a-f0-9]{48}\.(pdf|png|jpg)$/D',$row['StoredName']))show_404();
        $path=APPPATH.'storage/customer_flow/'.$row['StoredName'];
        if(!is_file($path)||!hash_equals($row['FileHash'],hash_file('sha256',$path)))show_404();
        $this->output->set_header('Cache-Control: private, no-store')->set_header('X-Content-Type-Options: nosniff')
            ->set_content_type($row['Mime'])->set_output(file_get_contents($path));
    }
}
