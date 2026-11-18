<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Rekap_KO extends CI_Controller {

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('MyReport_model/Model_Rekap_KO');
	}



	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'PROCUREMENT - REKAP KO';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['tgl_pertama'] = date('Y-m-01');
		$data['tgl_terakhir'] = date('Y-m-t');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/procurement/rekap_ko/view_rekap_ko', $data);
		$this->load->view('templates/footer');

	}

	function tbl_rekap_ko(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', true);
		$dari_tgl = $this->input->post('dari_tgl', true);
		$sampai_tgl = $this->input->post('sampai_tgl', true);
		$semua_periode = $this->input->post('semua_periode', true);

		$data['JobNo'] = $JobNo;
		$data['dari_tgl'] = $dari_tgl;
		$data['sampai_tgl'] = $sampai_tgl;
		$data['semua_periode'] = $semua_periode;

		$dapat_data_ko = $this->Model_Rekap_KO->dapat_data_ko($JobNo,$dari_tgl,$sampai_tgl,$semua_periode)->result_array();
		$data['dataRekapKO'] = $dapat_data_ko;
		$this->load->view('report_view/procurement/rekap_ko/tbl_rekap_ko', $data);

		
	}

	function pdf_rekap_ko($JobNo,$dari_tgl,$sampai_tgl,$semua_periode){

		if ($JobNo == '' || $dari_tgl == '' ||  $sampai_tgl == '' || $semua_periode == '') {
			redirect('welcome','refresh');
		}

		// echo $JobNo.' - '.$dari_tgl.' - '.$sampai_tgl.' - '.$semua_periode;

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
       	$tglSkrg = date('d-M-Y');

        	$query_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'")->row_array();

		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;
		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $query_job['JobNm'];

		$data['JobNo'] = $JobNo;
		$data['dari_tgl'] = $dari_tgl;
		$data['sampai_tgl'] = $sampai_tgl;
		$data['semua_periode'] = $semua_periode;

		$dapat_data_ko = $this->Model_Rekap_KO->dapat_data_ko($JobNo,$dari_tgl,$sampai_tgl,$semua_periode)->result();
		$data['dataRekapKO'] = $dapat_data_ko;

		$this->load->view('report_view/procurement/rekap_ko/print_pdf/pdf_tengah_rekap_ko', $data);
	}

}

/* End of file Rekap_KO.php */
/* Location: ./application/controllers/MyReport/Procurement/Rekap_KO.php */