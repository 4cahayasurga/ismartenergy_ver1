<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Report_PD_Jaminan extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('Laporan_model/Model_report_pd_jaminan');
	}

	function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

        $data['judul'] = 'REKAP PD JAMINAN';
        $data['bodyclass'] = 'sidebar-collapse skin-blue-light';

        $data['tgl_pertama'] = date('Y-m-01');
        $data['tgl_terakhir'] = date('Y-m-t');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('laporan_view/rekap_pd_jaminan/view_rekap', $data);
        $this->load->view('templates/footer');
	}

	function tbl_rekap(){

		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Company = $this->config->item('Company');

		$data['JobNo'] = $JobNo;
		$data['Company'] = $Company;

		$dapat_data_job = $this->Model_report_pd_jaminan->dapat_data_job($JobNo,$Company);
		$data['data_job'] = $dapat_data_job;

		$this->load->view('laporan_view/rekap_pd_jaminan/tbl_rekap_pd_jaminan', $data);


	}

	function pdf_rekap_pd_jaminan($JobNo=''){
		// echo 'aaa';
		// exit;

		if ($JobNo == '') {
			redirect('Report_PD_Jaminan','refresh');
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');

        $query_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'")->row_array();
        

        $Company = $this->config->item('Company');

		

		$dapat_data_job = $this->Model_report_pd_jaminan->dapat_data_job($JobNo,$Company);
		$data['data_job'] = $dapat_data_job;

		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;
		$data['JobNo'] = $JobNo;
		$data['Company'] = $Company;
		$data['JobNm'] = $query_job['JobNm'];


		$this->load->view('laporan_view/rekap_pd_jaminan/print_pdf/pdf_tengah_pd_jaminan', $data);



	}

}

/* End of file Report_PD_Jaminan.php */
/* Location: ./application/controllers/Laporan/Report_PD_Jaminan.php */