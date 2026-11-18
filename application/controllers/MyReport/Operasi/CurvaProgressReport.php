<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use \Mpdf\Mpdf;

class CurvaProgressReport extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('MyReport_model/M_CurvaProgressReport');
		$this->load->helper('bantu');
	}

	function index(){

		$data['judul'] = 'REPORT OPERASI (CURVA PROGRESS)';
		$data['bodyclass'] = 'sidebar-collapse skin-red';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/operasi/curva_progress/view_curva_progress', $data);
		$this->load->view('templates/footer');
	}

	function getDataProgress(){
		if (!$this->input->post('JobNo')) {
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);

		$ambilJob = dapat_data_job($JobNo);
		
		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $ambilJob['JobNm'];


		$data['result'] = $this->db->query("Report_GraphCurva '$JobNo'  ")->result();

		$this->load->view('report_view/operasi/curva_progress/mytable_progress', $data);

	}






	
		

	

}

/* End of file CurvaProgressReport.php */
/* Location: ./application/controllers/MyReport/Operasi/CurvaProgressReport.php */