<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Pertanggung_jawaban_entry_controller extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    is_login();
  }

  public function index($JobNo='')
  {
    if ($JobNo == '') {
     redirect('Welcome/index');
     exit;
   }

   $user_id = $this->session->userdata('MIS_LOGGED_ID');

    $data['judul'] = 'ENTRY PERMINTAAN DANA (LAPANGAN)';
    $data['bodyclass'] = 'sidebar-collapse skin-red';

    $tgl_saat_ini = date('Y-m-d');

    $data['tgl_pertama'] = date('Y-m-d', strtotime('-7 days'));
    $data['tgl_terakhir'] = $tgl_saat_ini;
    $data['JobNo'] = $JobNo;

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('view_pdpj/pd_lapangan/pd_lapangan_list_data', $data);
    $this->load->view('templates/footer');

 }

}


/* End of file PDPJ/Pertanggung_jawaban_entry_controller.php */
/* Location: ./application/controllers/PDPJ/Pertanggung_jawaban_entry_controller.php */