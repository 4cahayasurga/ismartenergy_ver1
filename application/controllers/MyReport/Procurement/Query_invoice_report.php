<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Query_invoice_report extends CI_Controller {

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('MyReport_model/Model_pro_query_invoice');
	}

	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'PROCUREMENT - QUERY INVOICE';
		$data['bodyclass'] = 'sidebar-collapse skin-yellow-light';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/procurement/query_invoice/view_query_invoice', $data);
		$this->load->view('templates/footer');

	}

	function tbl_query_invoice(){

		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$VendorId = $this->input->post('VendorId',TRUE);

		$PrdAwal = ($this->input->post('PrdAwal',TRUE)) ? $this->input->post('PrdAwal',TRUE) : '';
		$PrdAkhir = ($this->input->post('PrdAkhir',TRUE)) ? $this->input->post('PrdAkhir',TRUE) : '';



		$get_data_query = $this->Model_pro_query_invoice->get_data_query_invoice($JobNo,$VendorId,$PrdAwal,$PrdAkhir);

		$data = array(
			'JobNo' => $JobNo,
			'VendorId' => $VendorId,
			'PrdAwal' => $PrdAwal,
			'PrdAkhir' => $PrdAkhir,
		);
		$data['data_query_inv'] = $get_data_query->result();
		$this->load->view('report_view/procurement/query_invoice/tbl_query_invoice', $data);
		
	}

	function get_detail_invoice(){
		if (!$this->input->post()) {
			die;
		}

		$NoKO = $this->input->post('NoKO',TRUE);
		$InvNo = $this->input->post('InvNo',TRUE);

		$query = $this->db->query("
			WITH TmpQuery AS (
			SELECT A.InvNo, A.NoPD, 
			ISNULL((SELECT SUM(PaymentAmount) FROM BLE, InvPD, PdHdr WHERE 
			(BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=InvPD.NoKO AND InvPD.NoPD=PdHdr.NoPD AND 
			InvPD.NoKO=A.NoKO AND InvPD.InvNo=A.InvNo AND InvPD.NoPD=A.NoPD),0) AS PaymentAmount, 
			(SELECT TglBayar FROM BLE WHERE BLE.NoPD=A.NoPD) AS 'TglBayar' 
			FROM InvPD A WHERE NoKO='$NoKO' AND InvNo='$InvNo') SELECT * FROM TmpQuery ")->result();


		$data['invoice_detail'] = $query;

		$this->load->view('report_view/procurement/query_invoice/detail_invoice', $data);

	}

	function pdf_query_invoice($JobNo,$VendorId,$PrdAwal='',$PrdAkhir=''){

		if ($JobNo == '' || $VendorId == '') {
			redirect('welcome','refresh');
		}

		$get_data_query = $this->Model_pro_query_invoice->get_data_query_invoice($JobNo,$VendorId,$PrdAwal,$PrdAkhir);
		$data['data_query_inv'] = $get_data_query->result();

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;
		

		$JobKata = NULL;
		$VendorKata = NULL;

		if ($VendorId == 'All_Vendor' ) {
			$VendorKata = 'All Vendor';
		}else{
			$query_vendor = $this->db->query("SELECT VendorNm FROM Vendor WHERE VendorId='$VendorId' ")->row();
			$VendorKata = $query_vendor->VendorNm;
		}

		if ($JobNo == 'All_Job') {
			$JobKata = 'All Job';
		}else{
			$query_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'")->row();
			$JobKata = $JobNo.' - '.$query_job->JobNm;
		}

		$data['VendorKata'] = $VendorKata;
		$data['JobKata'] = $JobKata;
		$data['JobNo'] = $JobNo;
		$data['VendorId'] = $VendorId;
		$data['PrdAwal'] = $PrdAwal;
		$data['PrdAkhir'] = $PrdAkhir;



		$this->load->view('report_view/procurement/query_invoice/print_pdf/pdf_tengah', $data);



		// $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
  //      	$tglSkrg = date('d-M-Y');

  //       	$query_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'")->row_array();

		// $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;
		// $data['JobNo'] = $JobNo;
		// $data['JobNm'] = $query_job['JobNm'];

		// $data['JobNo'] = $JobNo;
		// $data['dari_tgl'] = $dari_tgl;
		// $data['sampai_tgl'] = $sampai_tgl;
		// $data['semua_periode'] = $semua_periode;

		// $dapat_data_ko = $this->Model_Rekap_KO->dapat_data_ko($JobNo,$dari_tgl,$sampai_tgl,$semua_periode)->result();
		// $data['dataRekapKO'] = $dapat_data_ko;

		// $this->load->view('report_view/procurement/rekap_ko/print_pdf/pdf_tengah_rekap_ko', $data);
	}



}

/* End of file Pro_query_invoice.php */
/* Location: ./application/controllers/MyReport/Procurement/Pro_query_invoice.php */
