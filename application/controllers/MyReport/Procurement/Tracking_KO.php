<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Tracking_KO extends CI_Controller {

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('MyReport_model/Model_Tracking_KO');
	}

	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'PROCUREMENT - TRACKING KO';
		$data['bodyclass'] = 'sidebar-collapse skin-green-light';

		$data['tgl_pertama'] = date('Y-m-01');
		$data['tgl_terakhir'] = date('Y-m-t');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/procurement/tracking_ko/view_tracking_ko', $data);
		$this->load->view('templates/footer');

	}

	function tbl_tracking_ko(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', true);
		$Status = $this->input->post('Status', true);

		$dapat_data_TrackingKO = $this->Model_Tracking_KO->dapat_data_TrackingKO($JobNo,$Status);

		$data['dataTrackingKO'] = $dapat_data_TrackingKO->result_array();
		$data['JobNo'] = $JobNo;
		$data['Status'] = $Status;

		$data_ko = 0;

		$NoKO = NULL;
		if ($dapat_data_TrackingKO->num_rows() > 0 ) {

			foreach($dapat_data_TrackingKO->result() AS $data_saya ){
				$NoKO = $data_saya->NoKO;

				if ($Status <> 'All_Status' ) {
					$query = $this->db->query("SELECT TOP 1 * FROM TraceKO WHERE NoKO='$NoKO' ORDER BY Tanggal DESC");
					if ($query->num_rows() > 0 ) {
						
						if ($Status == $query->row() ) {
							$data_ko=1;
						}
					}
				}
			}
			
			

		}
		$data['data_ko'] = $data_ko;

		$this->load->view('report_view/procurement/tracking_ko/tbl_tracking_ko', $data);
	}


	

	function pdf_tracking_ko($JobNo='',$Status=''){
		if ($JobNo == '' || $Status== '' ) {
			is_pesan('warning','terjadi kesalahan');
			redirect('MyReport/Procurement/Tracking_KO','refresh');
		}

		// echo 'aa';
		// exit;

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));

		$tglSkrg = date('d-M-Y');

		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$job_nama = NULL;

		if ($JobNo == 'All_Job') {
			$job_nama = 'All Job';
		}else{
			$JobNm = $this->db->select('JobNm ')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
			$job_nama = $JobNo.' - '.$JobNm;
		}

		$data['job_nama'] = $job_nama;
		$data['JobNo'] = $JobNo;
		$data['Status'] = $Status;

		$nama_status = NULL;
		if ($Status=='All_Status') {
			$nama_status = 'All Status';
		}else if($Status==0){
			$nama_status = 'In Process';
		}else{
			$nama_status = 'Delivered';
		}

		$data['nama_status'] = $nama_status;

		$dapat_data_TrackingKO = $this->Model_Tracking_KO->dapat_data_TrackingKO($JobNo,$Status);

		$data['dataTrackingKO'] = $dapat_data_TrackingKO->result_array();

		// print_rr($data);
		// exit;

		
		$this->load->view('report_view/procurement/tracking_ko/print_pdf/pdf_tengah_tracking_ko', $data);


	}

	function tbl_tracking_ko_lama(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', true);
		$Status = $this->input->post('Status', true);

		// echo $Status;
		// exit;

		$TotalKO = 0;
		$NoKO = NULL;
		$TglKO = NULL;
		$VendorNm =NULL;

		$array = array();

		$dapat_data_TrackingKO = $this->Model_Tracking_KO->dapat_data_TrackingKO($JobNo,$Status)->result_array();
		$data['dataTrackingKO'] = $dapat_data_TrackingKO;
		$data['JobNo'] = $JobNo;
		$data['Status'] = $Status;
		$this->load->view('report_view/procurement/tracking_ko/tbl_tracking_ko', $data);

		// if ($dapat_data_TrackingKO->num_rows() > 0) {

		// 	foreach($dapat_data_TrackingKO->result_array() as $data_tracking ){

		// 		$NoKO = $data_tracking['NoKO'];
		// 		$TglKO = $data_tracking['TglKO'];
		// 		$VendorNm = $data_tracking['VendorNm'];
		// 		$TotalKO = $data_tracking['SubTotal'] - $data_tracking['DiscAmount'] + $data_tracking['PPN'];

		// 		$array[] = array(
		// 			'NoKo' => $data_tracking['NoKO'],
		// 			'TglKO' => $data_tracking['TglKO'],
		// 			'VendorNm' => $data_tracking['VendorNm'],
		// 			'TotalKO' => $TotalKO
		// 		);



		// 	}

		// }

		// $data['data_tracking'] = $array;
		// $data['data_tracking'] = $dapat_data_TrackingKO;
		// $data['Status'] = $Status;
		// $this->load->view('report_view/procurement/tracking_ko/tbl_tracking_ko', $data);

		// $colors = ['red', 'green', 'blue'];

		// foreach ($colors as $color) {
		// 	echo $color . '<br>';
		// }

		// print_rr($array);
		// die;

		// $data['dataTrackingKO'] = $dapat_data_TrackingKO;

		// $data['JobNo'] = $JobNo;
		// $data['Status'] = $Status;
		// $this->load->view('report_view/procurement/tracking_ko/tbl_tracking_ko', $data);
		

		// $dapat_data_ko = $this->Model_Rekap_KO->dapat_data_ko($JobNo,$dari_tgl,$sampai_tgl,$semua_periode)->result_array();
		// $data['dataRekapKO'] = $dapat_data_ko;
		// $this->load->view('report_view/procurement/rekap_ko/tbl_rekap_ko', $data);

	}

}

/* End of file Tracking_KO.php */
/* Location: ./application/controllers/MyReport/Procurement/Tracking_KO.php */