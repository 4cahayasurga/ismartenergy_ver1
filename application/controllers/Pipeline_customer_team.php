<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pipeline_customer_team extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Pipeline_access');
        $this->load->model('Pipeline_customer_team_model','team');
        if (!$this->team->ready()) show_404();
        $this->output->set_header('Cache-Control: no-store');
    }

    public function index()
    {
        is_login();
        $company=$this->team->company();
        if ($this->input->method()==='post') {
            $action=$this->input->post('action');
            try {
                if (!in_array($action,array('create','edit','reset'),true)) show_404();
                $invite=$this->team->save($action,$this->input->post());
                if ($invite) $this->session->set_flashdata('team_invite',site_url('Pipeline_customer_team/activate/'.$invite));
                $this->session->set_flashdata('Berhasil','Pengaturan akun disimpan.');
            } catch(RuntimeException $e){$this->session->set_flashdata('Gagal',$e->getMessage());}
            redirect('Pipeline_customer_team');return;
        }
        $q=mb_strtolower(trim((string)$this->input->get('q')));
        $role=$this->input->get('role');$status=$this->input->get('status');
        $page=max(1,(int)$this->input->get('page'));
        $rows=array_values(array_filter($this->team->members($company['BusinessEntityID']),function($user)use($q,$role,$status){
            return ($q==='' || mb_strpos(mb_strtolower($user['UserID'].' '.$user['UserName'].' '.$user['Email']),$q)!==false)
                && (!$role || $role===$user['TeamRole']) && ($status===null || $status==='' || $status===$user['Active']);
        }));
        $pages=max(1,(int)ceil(count($rows)/20));$page=min($page,$pages);$rows=array_slice($rows,($page-1)*20,20);
        $vessels=array_column($this->team->vessels($company['BusinessEntityID']),'VesselName','VesselID');
        $assignments=$this->team->assignments($company['BusinessEntityID']);
        $hasPhone=(bool)$this->team->phone_column();
        $this->render('customer_team',compact('hasPhone','company','rows','vessels','assignments','q','role','status','pages','page'),'Manajemen User Customer');
    }

    public function settings()
    {
        is_login();$this->pipeline_access->require_menu('customer_team_toggle');
        if ($this->input->method()==='post') {
            try {$this->team->toggle((int)$this->input->post('CompanyID'),(int)$this->input->post('SpbbID'),$this->input->post('Enabled')==='1');
                $this->session->set_flashdata('Berhasil','Pengaturan perusahaan disimpan.');}
            catch(RuntimeException $e){$this->session->set_flashdata('Gagal',$e->getMessage());}
            redirect('Pipeline_customer_team/settings');return;
        }
        $this->render('customer_team_settings',array('companies'=>$this->team->companies(),'branches'=>$this->team->scopes(),
            'developer'=>$this->pipeline_access->team_developer()),'Akses Manajemen User Customer');
    }

    public function activate($token='')
    {
        if (!preg_match('/^[a-f0-9]{64}$/D',$token)) show_404();
        $this->output->set_header('Referrer-Policy: no-referrer');
        $error='';
        if ($this->input->method()==='post') {
            try {$this->team->accept($token,$this->input->post('Password'),$this->input->post('Confirmation'));redirect('Auth');return;}
            catch(RuntimeException $e){$error=$e->getMessage();}
        }
        $this->load->view('pipeline/customer_team_activate',compact('error','token'));
    }

    private function render($view,$data,$judul)
    {
        $this->load->view('templates/header',$data+compact('judul'));
        $this->load->view('templates/sidebar');
        $this->load->view('pipeline/'.$view,$data);
        $this->load->view('templates/footer');
    }
}
