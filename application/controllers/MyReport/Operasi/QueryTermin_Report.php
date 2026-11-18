<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Mpdf\Mpdf;
error_reporting(0);

class QueryTermin_Report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('MyReport_model/M_QueryTermin_Report');
		$this->load->helper('bantu');
	}

	public function index()
	{


		$data['judul'] = 'REPORT OPERASI (QUERY TERMIN)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/operasi/query_termin/query_termin_main', $data);
		$this->load->view('templates/footer');
	}

	

	function getTable_QueryTermin(){
		if (!$this->input->post()) {
			exit;
		}


		$JobNo = $this->input->post('JobNo',TRUE);
		$Tahun = $this->input->post('Tahun',TRUE);
		$ambilJob = dapat_data_job($JobNo);
		$data['JobNo'] = $JobNo;
		$data['Tahun'] = $Tahun;
		$data['JobNm'] = $ambilJob['JobNm'];

		$data['dataJob'] = $this->getJobNo($JobNo);
		$this->load->view('report_view/operasi/query_termin/getTable_QueryTermin', $data);

		// print_rr($_POST);

	}

	private function getJobNo($JobNo){
		$this->db->select('JobNo, JobNm, Bruto, Netto');
		$this->db->from('Job');
		$this->db->where('StatusJob','Pelaksanaan');
		$this->db->where_in('JobNo',array('21570'));
		return $this->db->get()->result_array();
		// $query = $this->db->query("SELECT JobNo, JobNm, Bruto, Netto FROM Job WHERE JobNo IN ('$JobNo') AND StatusJob='Pelaksanaan' ORDER BY JobNo")->result_array();
		// return $query;
	}


	function QTerminPDF($JobNo,$Tahun){

		if ($JobNo=='' && $Tahun == '') {
			exit;
		}

    	$ambilJob = dapat_data_job($JobNo);
		

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
    	$tglSkrg = date('d-M-Y');
    	$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

    	$data['JobNo'] = $JobNo;
		$data['Tahun'] = $Tahun;
    	$data['JobNm'] = $ambilJob['JobNm'];

		$this->load->view('report_view/operasi/query_termin/pdf_tengah', $data);
	}

	// function percobaan_query(){
	// 	$nomor=1;
	// 	$Bruto = 0;
	// 	$Persentase = 0;
	// 	$Netto1 = 0;
	// 	$Counter=NULL;

	// 	$Persentase2 = 0;
	// 	$Netto2 = 0;

	// 	$TglBuatan_pertama = $Tahun.'-01-01';
	// 	$TglBuatan_kedua = $Tahun.'-12-31';
	// }

}

/* End of file QueryTermin_Report.php */
/* Location: ./application/controllers/Laporan/Operasi/QueryTermin_Report.php */