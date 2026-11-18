<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbb_operator extends CI_Controller
{
    private $user_id;
    private $spbb_ids;

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Spbb_model');
        $this->user_id = $this->session->userdata('MIS_LOGGED_ID');
        $role = $this->Spbb_model->get_role($this->user_id);
        $this->load->library('Pipeline_access');$menu=array('queue'=>'operator_queue','shift'=>'operator_shift','start'=>'operator_queue','complete'=>'operator_queue')[$this->router->fetch_method()]??'operator_dashboard';$this->pipeline_access->require_menu($menu);if(!$this->session->userdata('pipeline_form_token'))$this->session->set_userdata('pipeline_form_token',bin2hex(random_bytes(24)));if (!in_array($role['role'],array('OPERATOR','PEMILIK_SPBB','SUPERADMIN'),true)) {
            show_error('Akses khusus Operator SPBB', 403);
        }
        $this->load->model('Pipeline_billing_model','billing');$this->spbb_ids=array_map('intval',array_column($this->billing->branches(),'SpbbID'));
        if (!count($this->spbb_ids)) {
            show_error('Operator belum memiliki akses SPBB. Hubungi SuperAdmin.', 403);
        }
    }

    public function queue(){$this->index();}
    public function shift(){$this->index();}
    public function index()
    {
        $date_from = $this->input->get('date_from', true) ?: date('Y-m-d');
        $date_to = $this->input->get('date_to', true) ?: date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_from)) {
            $date_from = date('Y-m-d');
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_to)) {
            $date_to = date('Y-m-d');
        }
        if ($date_from > $date_to) {
            $temporary = $date_from;
            $date_from = $date_to;
            $date_to = $temporary;
        }
        foreach(array($date_from,$date_to) as $date){$d=DateTime::createFromFormat('!Y-m-d',$date);if(!$d||$d->format('Y-m-d')!==$date)show_error('Tanggal tidak valid.',422);}
        $data = array(
            'judul' => 'Operator SPBB','pipeline_token'=>$this->session->userdata('pipeline_form_token'),'operator_menu'=>$this->router->fetch_method(),'can_queue'=>$this->pipeline_access->allowed('operator_queue'),'can_shift'=>$this->pipeline_access->allowed('operator_shift'),
            'spbb_list' => $this->Spbb_model->spbb_list_by_ids($this->spbb_ids),
            'waiting_orders' => $this->Spbb_model->operator_orders($this->spbb_ids, array('SIAP_PENGISIAN')),
            'active_orders' => array_values(array_filter(
                $this->Spbb_model->operator_orders($this->spbb_ids, array('SEDANG_PENGISIAN')),
                function ($row) { return $row['OperatorStartedBy'] === $this->user_id; }
            )),
            'today_completed' => array_values(array_filter(
                $this->Spbb_model->operator_orders($this->spbb_ids, array('SELESAI')),
                function ($row) { return $row['CompletedBy'] === $this->user_id && substr($row['CompletedAt'], 0, 10) === date('Y-m-d'); }
            )),
            'shift_recap' => $this->Spbb_model->operator_shift_recap($this->spbb_ids, $date_from, $date_to),
            'date_from' => $date_from,
            'date_to' => $date_to,
        );
        $this->render('spbb/operator_dashboard', $data);
    }

    /** Perubahan status memakai token, area, kanal dan kunci baris yang sama. */
    private function mutate($order_id,$complete=false){
        if($this->input->method()!=='post')show_error('Gunakan form operator.',405);$token=$this->input->post('pipeline_token');if(!is_string($token)||!hash_equals($this->session->userdata('pipeline_form_token'),$token))show_error('Form kedaluwarsa.',403);
        $this->db->trans_begin();try{$q=$this->db->query('SELECT OrderID,SpbbID,OrderChannel FROM SpbbOrder WITH (UPDLOCK,HOLDLOCK) WHERE OrderID=?',array((int)$order_id));$row=$q->row_array();if(!$row||!in_array((int)$row['SpbbID'],$this->spbb_ids,true)||$row['OrderChannel']!=='SPBB')throw new RuntimeException('Pesanan di luar area / kanal SPBB operator.');
          if($complete){$quantity=$this->input->post('ActualQuantity');if(!is_string($quantity)||!preg_match('/^\d+(\.\d{1,3})?$/D',$quantity))throw new RuntimeException('Volume aktual tidak valid.');list($ok,$message)=$this->Spbb_model->complete_order((int)$order_id,$this->user_id,$quantity,$this->input->post('ShiftCode'));}else list($ok,$message)=$this->Spbb_model->start_filling((int)$order_id,$this->user_id,$this->spbb_ids);
          if(!$ok||!$this->db->trans_status())throw new RuntimeException($message);$this->db->trans_commit();$this->session->set_flashdata('Berhasil',$message);
        }catch(Exception $e){$this->db->trans_rollback();$this->session->set_flashdata('Gagal',$e->getMessage());}redirect('Spbb_operator/queue');
    }
    public function start($order_id){$this->mutate($order_id);}
    public function complete($order_id){$this->mutate($order_id,true);}

    private function render($view, $data)
    {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}
