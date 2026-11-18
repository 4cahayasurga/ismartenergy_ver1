<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use \Mpdf\Mpdf;

class PiutangProgressFisik_report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('MyReport_model/M_PiutangProgressFisik');
		$this->load->helper('bantu');
		if (!$this->db->table_exists('tempPiutang'))
		{
			echo 'Table tempPiutang tidak ditemukan';
			exit;
		}
	}

	public function index()
	{

		$query =$this->M_PiutangProgressFisik->getDataPiutang();

		if ($query ==  0) {
			echo 'terjadi kesalahan';
			die;
		}

		$data['judul'] = 'REPORT OPERASI (PIUTANG PROGRESS FISIK)';
		$data['bodyclass'] = 'sidebar-collapse skin-purple';

		$dataPrimary = 0;
		$dataKategoriNull = 0;
		$dataSecondary = 0;
		$dataSYC = 0;
		

		$queryPrimary = $this->db->query("SELECT *, PersenRPPM-PersenTermin AS 'SisaPersen', RpRPPM-RpTermin AS 'RpSisa' FROM tempPiutang WHERE Kategori='Primary' ORDER BY JobNo")->result_array();

		$queryKategoriNull = $this->db->query("SELECT *, PersenRPPM-PersenTermin AS 'SisaPersen', RpRPPM-RpTermin AS 'RpSisa' FROM tempPiutang WHERE Kategori IS NULL ORDER BY JobNo")->result_array();

		$querySecondary = $this->db->query("SELECT *, PersenRPPM-PersenTermin AS 'SisaPersen', RpRPPM-RpTermin AS 'RpSisa' FROM tempPiutang WHERE Kategori='Secondary' ORDER BY JobNo")->result_array();


		$querySYC = $this->db->query("SELECT *, PersenRPPM-PersenTermin AS 'SisaPersen', RpRPPM-RpTermin AS 'RpSisa' FROM tempPiutang WHERE Kategori='SYC' ORDER BY JobNo")->result_array();


		$SUBTOTAL = $this->db->query("SELECT SUM(RpRPPM) AS 'SUBTOTAL_RpRPPM', SUM(RpTermin) AS 'SUBTOTAL_RpTermin', SUM(RpRPPM - RpTermin) AS 'SUBTOTAL_RpSisa' FROM tempPiutang ")->row_array();

		$data['SUBTOTAL_RpRPPM'] = $SUBTOTAL['SUBTOTAL_RpRPPM'];
		$data['SUBTOTAL_RpTermin'] = $SUBTOTAL['SUBTOTAL_RpTermin'];
		$data['SUBTOTAL_RpSisa'] = $SUBTOTAL['SUBTOTAL_RpSisa'];

		// print_rr($data);
		// die;



		if (count($queryPrimary) > 0) {
			$dataPrimary = 1;
		}

		if (count($queryKategoriNull) > 0) {
			$dataKategoriNull = 1;
		}


		if (count($querySecondary) > 0) {
			$dataSecondary = 1;
		}

		if (count($querySYC) > 0) {
			$dataSYC = 1;
		}



		

		$data['queryPrimary'] = $queryPrimary;
		$data['dataPrimary'] = $dataPrimary;

		$data['querySecondary'] = $querySecondary;
		$data['dataSecondary'] = $dataSecondary;

		$data['querySYC'] = $querySYC;
		$data['dataSYC'] = $dataSYC;

		$data['queryKategoriNull'] = $queryKategoriNull;
		$data['dataKategoriNull'] = $dataKategoriNull;

		// print_rr($data);
		// die;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/operasi/piutang_progress_fisik/piutang_progress_fisik_utama', $data);
	}

	function myPDF_Piutang(){

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;


		$dataPrimary = 0;
		$dataKategoriNull = 0;
		$dataSecondary = 0;
		$dataSYC = 0;
		

		$queryPrimary = $this->db->query("SELECT *, PersenRPPM-PersenTermin AS 'PersenSisa', RpRPPM-RpTermin AS 'RpSisa' FROM tempPiutang WHERE Kategori='Primary' ORDER BY JobNo")->result_array();

		// print_rr($queryPrimary);
		// exit;

		$queryKategoriNull = $this->db->query("SELECT *, PersenRPPM-PersenTermin AS 'PersenSisa', RpRPPM-RpTermin AS 'RpSisa' FROM tempPiutang WHERE Kategori IS NULL ORDER BY JobNo")->result_array();

		$querySecondary = $this->db->query("SELECT *, PersenRPPM-PersenTermin AS 'PersenSisa', RpRPPM-RpTermin AS 'RpSisa' FROM tempPiutang WHERE Kategori='Secondary' ORDER BY JobNo")->result_array();


		$querySYC = $this->db->query("SELECT *, PersenRPPM-PersenTermin AS 'PersenSisa', RpRPPM-RpTermin AS 'RpSisa' FROM tempPiutang WHERE Kategori='SYC' ORDER BY JobNo")->result_array();


		$SUBTOTAL = $this->db->query("SELECT SUM(RpRPPM) AS 'SUBTOTAL_RpRPPM', SUM(RpTermin) AS 'SUBTOTAL_RpTermin', SUM(RpRPPM - RpTermin) AS 'SUBTOTAL_RpSisa' FROM tempPiutang ")->row_array();

		$data['SUBTOTAL_RpRPPM'] = $SUBTOTAL['SUBTOTAL_RpRPPM'];
		$data['SUBTOTAL_RpTermin'] = $SUBTOTAL['SUBTOTAL_RpTermin'];
		$data['SUBTOTAL_RpSisa'] = $SUBTOTAL['SUBTOTAL_RpSisa'];

		// print_rr($data);
		// die;



		if (count($queryPrimary) > 0) {
			$dataPrimary = 1;
		}

		if (count($queryKategoriNull) > 0) {
			$dataKategoriNull = 1;
		}


		if (count($querySecondary) > 0) {
			$dataSecondary = 1;
		}

		if (count($querySYC) > 0) {
			$dataSYC = 1;
		}



		

		$data['queryPrimary'] = $queryPrimary;
		$data['dataPrimary'] = $dataPrimary;

		$data['querySecondary'] = $querySecondary;
		$data['dataSecondary'] = $dataSecondary;

		$data['querySYC'] = $querySYC;
		$data['dataSYC'] = $dataSYC;

		$data['queryKategoriNull'] = $queryKategoriNull;
		$data['dataKategoriNull'] = $dataKategoriNull;



		$this->load->view('report_view/operasi/piutang_progress_fisik/pdf_tengah', $data);
	}

}

/* End of file PiutangProgressFisik_report.php */
/* Location: ./application/controllers/MyReport/Operasi/PiutangProgressFisik_report.php */