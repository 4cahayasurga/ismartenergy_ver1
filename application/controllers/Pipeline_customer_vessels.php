<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_customer_vessels extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->library('Pipeline_access');
        if(!$this->pipeline_access->allowed('customer_accounts')&&!$this->pipeline_access->allowed('customer_vessels'))show_error('Akses Master Kapal Customer ditolak.',403);
        $this->load->model('Pipeline_customer_vessel_model','vessels');
        if(!$this->session->userdata('pipeline_form_token'))$this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));
    }

    private function render($view,$data)
    {
        $data['pipeline_token']=$this->session->userdata('pipeline_form_token');
        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('pipeline/'.$view,$data);
        $this->load->view('templates/footer');
    }

    public function index()
    {
        $this->render('customer_vessel_list',array('judul'=>'Master Kapal Customer','vessels'=>$this->vessels->listing()));
    }

    public function edit($id)
    {
        $vessel=$this->vessels->find($id);
        if(!$vessel)show_404();
        $error='';
        $form=$vessel;
        $revision=$this->vessels->revision($vessel);
        if($this->input->method()==='post'){
            $token=$this->input->post('pipeline_token');
            if(!is_string($token)||!hash_equals($this->session->userdata('pipeline_form_token'),$token))show_error('Muat ulang form sebelum menyimpan.',403);
            try{
                $this->vessels->save($id,$this->input->post());
                $this->session->set_flashdata('Berhasil','Data kapal diperbarui dan log perubahan tersimpan.');
                redirect('Pipeline_customer_vessels/edit/'.(int)$id);
                return;
            }catch(RuntimeException $e){$error=$e->getMessage();}
            catch(InvalidArgumentException $e){$error=$e->getMessage();}
            $form=array_merge($form,array_intersect_key(array_filter((array)$this->input->post(),'is_scalar'),$this->vessels->fields()));
            $postedRevision=$this->input->post('RevisionHash');
            $revision=is_string($postedRevision)?$postedRevision:'';
        }
        $this->render('customer_vessel_edit',array('judul'=>'Edit Kapal Customer','vessel'=>$vessel,'form'=>$form,'fields'=>$this->vessels->fields(),'routes'=>$this->vessels->route_options($vessel['BusinessEntityID']),'vesselSettings'=>$this->vessels->settings(),'revision'=>$revision,'error'=>$error,'history'=>$this->vessels->history($id)));
    }
}
