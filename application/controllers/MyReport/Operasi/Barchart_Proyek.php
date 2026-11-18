<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Barchart_Proyek extends CI_Controller {

	var $Company;
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('MyReport_model/M_CurvaProgressReport');
		$this->load->helper('bantu');

		$this->Company = $this->config->item('Company');
	}

	function index(){

		$data['judul'] = 'REPORT OPERASI (BAR CHART PROYEK)';
		$data['bodyclass'] = 'sidebar-collapse skin-red-light';
		$data['data_job'] = $this->db->query("SELECT JobNo, JobNm FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND Company='$this->Company'")->result_array();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/operasi/barchart_proyek/view_barchart_proyek', $data);
		$this->load->view('templates/footer');
	}

	function get_data_barchart(){
		if (!$this->input->post('JobNo')) {
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;

		$query_kontrak = "SELECT * FROM Job WHERE JobNo='$JobNo' ";
		$ekse_kontrak = $this->db->query($query_kontrak)->row();

		// print_rr($ekse_kontrak);
		// exit;

		$this->load->view('report_view/operasi/barchart_proyek/tbl_barchart', $data);
	}

}

/* End of file Barchart_Proyek.php */
/* Location: ./application/controllers/MyReport/Operasi/Barchart_Proyek.php */