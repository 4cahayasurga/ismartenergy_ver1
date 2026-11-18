<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Mpdf\Mpdf;

class MyPDF_export extends CI_Controller {

	var $Company;
	function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}


	function PDF_PermintaanDana($NoPD='')
	{
		if ($NoPD=='') {
			die;
		}

		// echo 'aa';.
		// exit;


		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;


		$get_data_PD = $this->db->query("SELECT * FROM PdHdr WHERE NoPD='$NoPD' ");
		$data['dataPD'] = $get_data_PD->row();

		$get_detail_PD = $this->db->query("SELECT (Vol * HrgSatuan) AS 'JumlahHarga', * FROM PdDtl WHERE NoPD='$NoPD' ")->result();
		$data['detailPD'] = $get_detail_PD;

		// print_rr($get_detail_PD);
		// exit;

		$this->load->view('MyPDF_export_view/PD_view/tengah', $data);
	}


	function pdf_export($from='',$parData=''){
		if ($from == '') {
			die;
		}

		
		
	}

}

/* End of file MyPDF_export.php */
/* Location: ./application/controllers/MyPDF_export.php */