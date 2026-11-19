<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_spp extends CI_Controller
{
    public function __construct()
    {
        parent::__construct(); is_login();
        $this->load->library('Pipeline_access'); $this->pipeline_access->require_menu('spp_ocr');
        $this->load->library('Pipeline_spp_ocr');
        $this->load->model('Pipeline_billing_model','billing');
        $this->load->model('Pipeline_spp_model','spp');
        if (!$this->db->table_exists('PipelineSppCapture')) show_error('Fitur SPP belum diaktifkan. Jalankan migrasi Pipanisasi.',503);
        if (!$this->session->userdata('pipeline_form_token')) $this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));
    }
    private function guard()
    {
        $token=$this->input->post('pipeline_token');
        if ($this->input->method()!=='post' || !is_string($token) || !hash_equals($this->session->userdata('pipeline_form_token'),$token)) show_error('Form kedaluwarsa. Muat ulang halaman.',403);
    }
    private function render($view,$data)
    {
        $data['pipeline_token']=$this->session->userdata('pipeline_form_token');
        $this->load->view('templates/header',$data); $this->load->view('templates/sidebar');
        $this->load->view('pipeline/'.$view,$data); $this->load->view('templates/footer');
    }
    private function deliveries()
    {
        $this->Pipeline_model->delivery_query();
        return $this->db->where('X.DeliveryID IS NULL',null,false)->order_by('D.DeliveryDate','DESC')->order_by('D.DeliveryID','DESC')->get()->result_array();
    }
    private function recognize($capture)
    {
        @set_time_limit(90);
        $result=$this->pipeline_spp_ocr->read(APPPATH.'storage/pipeline/'.$capture['StoredName']);
        $this->spp->ocr_result($capture['CaptureID'],$result);
    }
    private function flow_request($id,$requests)
    {
        if ($id===null || $id==='') return null;
        if (!is_string($id) || !ctype_digit($id)) show_404();
        if (!isset($requests[(int)$id])) show_404();
        return $requests[(int)$id];
    }

    public function index()
    {
        $this->load->model('Pipeline_flow_model','flow');
        $requests=$this->flow->source_requests();
        $requested=$this->input->method()==='post'
            ? ($this->input->post('RequestID')??$this->input->get('request')) : $this->input->get('request');
        $workflow=$this->flow_request($requested,$requests);
        $error='';
        if ($this->input->method()==='post') {
            $this->guard(); $path=null;
            try {
                $branch=$this->input->post('SpbbID'); $deliveryId=$this->input->post('DeliveryID');if($deliveryId!==null&&$deliveryId!=='')throw new InvalidArgumentException('Tautan SPP ke DO sementara dinonaktifkan. Gunakan alur BASTB / RDT.');$deliveryId='';
                if (!is_string($branch) || !ctype_digit($branch) || !is_string($deliveryId) || ($deliveryId!=='' && !ctype_digit($deliveryId))) throw new InvalidArgumentException('Cabang / DO tidak valid.');
                if ($workflow && (int)$workflow['SpbbID']!==(int)$branch) throw new RuntimeException('Cabang Surat Pengantar Pengiriman harus sama dengan pesanan.');
                if (!$this->billing->branch_allowed((int)$branch)) throw new RuntimeException('Cabang di luar akses Anda.');
                $file=null;
                foreach(array('SPPFile','PhotoFile') as $key) if (isset($_FILES[$key]) && $_FILES[$key]['error']!==UPLOAD_ERR_NO_FILE) {
                    if ($file!==null) throw new InvalidArgumentException('Pilih satu file atau satu foto saja.');
                    $file=$_FILES[$key];
                }
                if (!$file || !is_scalar($file['error']) || (int)$file['error']!==UPLOAD_ERR_OK || $file['size']>15*1024*1024 || !is_uploaded_file($file['tmp_name'])) throw new InvalidArgumentException('Pilih dokumen JPG / PNG / PDF maksimal 15 MB.');
                $mime=(new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']); $extensions=array('image/jpeg'=>'jpg','image/png'=>'png','application/pdf'=>'pdf');
                if(!isset($extensions[$mime]))throw new InvalidArgumentException('File harus JPG, PNG atau PDF.');
                if($mime==='application/pdf'){if(strncmp(file_get_contents($file['tmp_name'],false,null,0,5),'%PDF-',5)!==0)throw new InvalidArgumentException('Isi PDF tidak valid.');}
                else{$image=@getimagesize($file['tmp_name']);if(!$image||$image[0]*$image[1]>24000000)throw new InvalidArgumentException('Gambar maksimal 24 megapiksel.');}
                $directory=APPPATH.'storage/pipeline/';
                if (!is_dir($directory) && !mkdir($directory,0700,true)) throw new RuntimeException('Penyimpanan foto belum tersedia.');
                $name=bin2hex(random_bytes(24)).'.'.$extensions[$mime]; $path=$directory.$name;
                if (!move_uploaded_file($file['tmp_name'],$path)) throw new RuntimeException('Foto gagal diunggah.');
                $id=$this->spp->create(array('SpbbID'=>(int)$branch,'DeliveryID'=>$deliveryId!==''?(int)$deliveryId:null,
                    'ProposedRequestID'=>$workflow['RequestID']??null,'StoredName'=>$name,'OriginalName'=>mb_substr(basename($file['name']),0,255),'MimeType'=>$mime,
                    'FileHash'=>hash_file('sha256',$path),'FileSize'=>filesize($path)));
                $savedCapture=$this->spp->capture($id);
                if($savedCapture['StoredName']!==$name){
                    unlink($path);
                    $path=null;
                    $this->session->set_flashdata('Berhasil','Surat Pengantar Pengiriman ini sudah tersimpan. Arsip dan hasil OCR yang ada dibuka kembali.');
                    redirect($workflow && $savedCapture['Status']==='CONFIRMED'
                        ?'Pipeline_flow/detail/'.$workflow['RequestID'].'?spp='.$id
                        :'Pipeline_spp/review/'.$id.($workflow?'?request='.$workflow['RequestID']:''));
                    return;
                }
                $path=null;
                try {
                    $this->recognize($this->spp->capture($id));
                    $this->session->set_flashdata('Berhasil','Dokumen dibaca dan form terisi otomatis. Cocokkan hasil OCR dengan dokumen sebelum konfirmasi.');
                } catch(Exception $e) { $this->session->set_flashdata('Gagal',$e->getMessage()); }
                redirect('Pipeline_spp/review/'.$id.($workflow?'?request='.$workflow['RequestID']:'')); return;
            } catch(Exception $e) { if ($path && is_file($path)) unlink($path); $error=$e->getMessage(); }
        }
        $this->render('spp_upload',array('judul'=>'Upload / Foto Surat Pengantar Pengiriman','error'=>$error,'branches'=>$this->billing->branches(),
            'deliveries'=>array(),'captures'=>$this->spp->captures(),'workflow'=>$workflow,'requests'=>$requests));
    }
    public function review($id)
    {
        $capture=$this->spp->capture($id);
        if (!$capture) show_404();
        $workflow=null;
        $requested=$capture['ProposedRequestID']??null;
        if ($requested!==null) {
            $this->load->model('Pipeline_flow_model','flow');
            $workflow=$this->flow->find($requested);
            if (!$workflow || !$this->flow->staff() || (int)$workflow['SpbbID']!==(int)$capture['SpbbID']) show_404();
        }
        $error=''; $form=$capture;
        if ($this->input->method()==='post') {
            $this->guard();
            try {
                if ($this->input->post('action')==='attach') {
                    throw new RuntimeException('Tautan SPP ke DO sementara dinonaktifkan.');
                    $deliveryId=$this->input->post('DeliveryID');
                    if (!is_string($deliveryId) || !ctype_digit($deliveryId)) throw new InvalidArgumentException('Pilih DO yang valid.');
                    $this->spp->attach((int)$id,(int)$deliveryId);
                    $this->session->set_flashdata('Berhasil','Foto SPP dilampirkan ke DO. Lanjutkan View / Periksa dokumen sebelum penagihan.');
                    redirect('Pipeline_spp/review/'.$id); return;
                }
                if ($capture['Status']==='CONFIRMED') throw new RuntimeException('Surat Pengantar Pengiriman sudah dikonfirmasi. Unggah foto baru untuk revisi.');
                if ($this->input->post('action')==='retry') {
                    $this->recognize($capture); redirect('Pipeline_spp/review/'.$id.($workflow?'?request='.$workflow['RequestID']:'')); return;
                }
                if ($this->input->post('Confirmed')!=='1') throw new InvalidArgumentException('Centang konfirmasi setelah memeriksa foto dan seluruh hasil bacaan.');
                if ($workflow && (!in_array($workflow['Status'],array('SUBMITTED','WAIT_TRANSPORT','TRANSPORT_CONFIRMED'),true) || bccomp($this->flow->source_remaining($workflow),'0',3)<=0)) {
                    throw new RuntimeException('Pesanan sudah melanjutkan proses pengisian.');
                }
                $posted=$this->input->post();
                $form=array_merge($capture,is_array($posted)?$posted:array());
                $fields=$this->pipeline_spp_ocr->validate($posted);
                $deliveryId=$this->input->post('DeliveryID');if($deliveryId!==null&&$deliveryId!=='')throw new RuntimeException('Tautan SPP ke DO sementara dinonaktifkan.');$deliveryId='';
                if (!is_string($deliveryId) || ($deliveryId!=='' && !ctype_digit($deliveryId))) throw new InvalidArgumentException('DO tidak valid.');
                $this->spp->confirm((int)$id,$fields,$deliveryId!==''?(int)$deliveryId:null);
                if ($workflow) { redirect('Pipeline_flow/detail/'.$workflow['RequestID'].'?spp='.(int)$id); return; }
                $this->session->set_flashdata('Berhasil','Hasil Surat Pengantar Pengiriman tersimpan.'.($deliveryId!==''?' File juga dilampirkan ke DO; lanjutkan View / Periksa dokumen sebelum penagihan.':''));
                redirect('Pipeline_spp/review/'.$id); return;
            } catch(Exception $e) { $error=$e->getMessage(); }
        }
        $this->render('spp_review',array('judul'=>'Hasil Pembacaan Surat Pengantar Pengiriman','capture'=>$capture,'form'=>$form,'error'=>$error,
            'fields'=>$this->pipeline_spp_ocr->fields(),'deliveries'=>array(),'workflow'=>$workflow));
    }
    public function file($id)
    {
        $capture=$this->spp->capture($id); if (!$capture) show_404();
        if (!preg_match('/^[a-f0-9]{48}\.(jpg|png|pdf)$/D',$capture['StoredName'])) show_404();
        $path=APPPATH.'storage/pipeline/'.$capture['StoredName']; if (!is_file($path)) show_404();
        header('Content-Type: '.$capture['MimeType']); header('X-Content-Type-Options: nosniff'); header('Cache-Control: private, no-store');
        header('Content-Length: '.filesize($path)); header('Content-Disposition: inline; filename="SPP-'.(int)$id.'.'.pathinfo($path,PATHINFO_EXTENSION).'"');
        readfile($path);
    }
}
