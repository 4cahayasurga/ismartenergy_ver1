<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_Hutang_KO extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{

		

		

		$user_id = $this->session->userdata('MIS_LOGGED_ID');


		$data['judul'] = 'REKAP HUTANG KO';
		$data['bodyclass'] = 'sidebar-collapse skin-green-light';

		// $TglAwal = date('01-M-Y');
		// $TglAkhir = date('t-M-Y');

		$TglAwal = date('Y-m-01');
		$TglAkhir = date('Y-m-t');

		$data['PrdAwal'] = $TglAwal;
		$data['PrdAkhir'] = $TglAkhir;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('laporan_view/procurement/rekap_hutang_ko_view', $data);
		$this->load->view('templates/footer');
		
	}

	function get_table_HutangKO(){
		if (!$this->input->post()) {
			exit;
		}

		// print_rr($_POST);
		// exit;

		$JobNo = $this->input->post('JobNo', TRUE);
		$PrdAwal = $this->input->post('PrdAwal', TRUE);
		$PrdAkhir = $this->input->post('PrdAkhir', TRUE);

		$data['JobNo'] = $JobNo;
		$data['PrdAwal'] = $PrdAwal;
		$data['PrdAkhir'] = $PrdAkhir;

		$query_HutangKO = $this->db->query("SELECT 
			a.JobNo,
			a.JobNm,
			a.Netto,
			a.PersenKSO,
			a.PrdAwal,
			a.PrdAkhir,
			IIF(a.PrdAwal=NULL,'1/1/1900',a.PrdAwal) AS 'TanggalPHO',
			(SELECT ISNULL(SUM(Vol*HrgSatuan),0) FROM RAP WHERE JobNo=a.JobNo And Alokasi='B' AND Tipe='Detail') AS 'RAP'
			FROM Job a WHERE a.JobNo='$JobNo'");

		// $query_HutangKO = $this->db->query("SELECT 
		// 	a.JobNo,
		// 	a.JobNm,
		// 	(a.Netto * IIF(a.PersenKSO=0,100,a.PersenKSO) / 100) AS 'NettKontrak',
		// 	IIF(a.PrdAwal=NULL,'1/1/1900',a.PrdAwal) AS 'TanggalPHO',
		// 	(SELECT ISNULL(SUM(Vol*HrgSatuan),0) FROM RAP WHERE JobNo=a.JobNo And Alokasi='B' AND Tipe='Detail') AS 'RAP',

		// 	(CASE
		// 		WHEN a.ClosedBy = NULL THEN (SELECT SUM(c.SubTotal +  c.PPN - c.DiscAmount) FROM KoHdr c, Vendor d WHERE c.JobNo=a.JobNo AND c.ApprovedBy IS NOT NULL AND c.CanceledBy IS NULL AND c.VendorId=d.VendorId)
		// 		ELSE 0
		// 	END) AS 'KO1'

		// 	FROM Job a WHERE a.JobNo='$JobNo'");

		$data['data_HutangKO'] = $query_HutangKO->result_array();



		// print_rr($data);
		// exit;

		$this->load->view('laporan_view/procurement/table_hutangKO', $data);
	}




}

/* End of file Rekap_Hutang_KO.php */
/* Location: ./application/controllers/Report/Procurement/Rekap_Hutang_KO.php */