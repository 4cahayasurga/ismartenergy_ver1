<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// perubahan 16 JUNI 2023

class Approved_spr extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		if (empty($this->session->userdata('MIS_LOGGED_NAME'))) {
			redirect('Auth/logout','refresh');
		}
		$this->load->model('Approved_spr_model');
	}

	public function view($JobNo='')
	{
		$UserID = $this->session->userdata('MIS_LOGGED_ID');
		// print_rr($_SESSION);

		$data['judul'] = 'APPROVAL SURAT PERMINTAAN MATERIAL/ALAT';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['JobNo'] = $JobNo;
		$data['ListJob'] = Tampil_Job_Berdasarkan_AksesJob();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('approved_spr/view', $data);
		$this->load->view('templates/footer');
	}

	function tbl_ApprovalSPR(){
		if (!$this->input->post()) {
			echo '';
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$data['dataPRHdr'] = $this->db->query("SELECT * FROM PRHdr WHERE JobNo='$JobNo' ORDER BY NoSPR DESC")->result();
		$data['JobNo'] = $JobNo;
		$this->load->view('approved_spr/tbl_ApprovalSPR',$data);

	}

	function view_old($JobNo){
		if (empty($JobNo)) {
			redirect('Job','refresh');
			exit;
		}

		$data['appspr'] = GetDetailJob($JobNo);
		$data['judul'] = 'APPROVAL SURAT PERMINTAAN MATERIAL/ALAT';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("approved_spr/view_16JUNI2023", $data);
		$this->load->view('templates/footer');

	}

	function CekNoSPR(){
		if (!$this->input->post()) {
			exit;
		}

		$NoSPR = $this->input->post('NoSPR',TRUE);
		$TxtApprove = $this->input->post('TxtApprove',TRUE);

		$query = $this->db->query("SELECT NoSPR FROM KoHdr WHERE NoSPR='$NoSPR' ");
		if ($query->num_rows() > 0 ) {
			echo 'ada_ko';
		}else{
			echo 'lanjut';
		}

	}

	function AksiApprove(){
		if (!$this->input->post()) {
			exit;
		}

		// print_rr($_POST);

		$NoSPR = $this->input->post('NoSPR',TRUE);
		$TxtApprove = $this->input->post('TxtApprove',TRUE);

		

		$this->db->trans_begin();

		if ($TxtApprove == 'APPROVE') {
			
			$ApprovedBy = $this->session->userdata('MIS_LOGGED_NAME');
			$TimeApproved = date('Y-m-d H:i:s');
			$this->db->query("UPDATE PRHdr SET ApprovedBy='$ApprovedBy', TimeApproved='$TimeApproved' WHERE NoSPR='$NoSPR' ");
		}else{
			$this->db->query("UPDATE PRHdr SET ApprovedBy=null, TimeApproved=null WHERE NoSPR='$NoSPR' ");
		}

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE ) {
			$this->db->trans_rollback();
			echo 'gagal';
		}else{
			$this->db->trans_commit();
			echo 'berhasil';
		}

	}

	function get_json_spr(){
		if ($this->input->post('JobNo') == '') {
			echo '';
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		header('Content-Type: application/json');
		echo $this->Approved_spr_model->get_json_spr($JobNo);
	}

	function getDetailSingkat(){
		if (!$this->input->post('NoSPR')) {
			
		}
		$NoSPR = $this->input->post('NoSPR');
		$JobNo = $this->input->post('JobNo');
		$where = array(
			'JobNo' => $JobNo,
			'NoSPR' => $NoSPR
		);
		$field = 'JobNo,JobNm';
		$data['row'] =  $this->Approved_spr_model->getDetailSpr($where)->row();
		$data['spr'] = GetDetailJobWhereField($JobNo,$field);

		$where_dtl = array(
			'NoSPR' => $NoSPR
		);

		$data['data'] = $this->Approved_spr_model->getdtl($where_dtl);
		$this->load->view('approved_spr/detail-singkat',$data);
	}

	function getDetail($JobNo='',$NoSPR=''){
		if ($JobNo =='' || $NoSPR== '') {
			redirect('Job','refresh');
			exit;
		}

		$where = array(
			'JobNo' => $JobNo,
			'NoSPR' => $NoSPR
		);

		$field = 'JobNo,JobNm,Lokasi';


		$data['row'] =  $this->Approved_spr_model->getDetailSpr($where)->row();
		$data['spr'] = GetDetailJobWhereField($JobNo,$field);

		$where_dtl = array(
			'NoSPR' => $NoSPR
		);

		$data['data'] = $this->Approved_spr_model->getdtl($where_dtl);


		$data['judul'] = 'Edit - Review Surat Permintaan Material/Alat';
		// echo $NoSPR;
		// print_rr($data);
		// exit;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("approved_spr/view_full", $data);
		$this->load->view('templates/footer');
	}

	function changeStatus(){
		if ($this->input->post()) {
			$NoSPR = $this->input->post('NoSPR');
			$JobNo = $this->input->post('JobNo');
			$ApprovedBy = $this->input->post('ApprovedBy');

			$appBy = $this->session->userdata('MIS_LOGGED_NAME');
			$TimeApproved = date('Y-m-d H:i:s');

			$data = array(
				'ApprovedBy' => $appBy,
				'TimeApproved' => $TimeApproved
			);

			$where = array(
				'JobNo' => $JobNo,
				'NoSPR' => $NoSPR,
			);

			if ($ApprovedBy != '' || $ApprovedBy != NULL) {
				$data['ApprovedBy'] = NULL;
				$data['TimeApproved'] = NULL;
			}

			$simpan = $this->Approved_spr_model->ubahStatus($data,$where);
			if ($simpan == TRUE) {
				echo 'success';
				exit;
			}
			echo 'gagal';

		}
	}


	// function getDetail($JobNo,$NoSPR){
	// 	if ($JobNo == '' || $NoSPR =='') {
	// 		redirect('Job','refresh');
	// 		exit;
	// 	}

	// 	$data['judul'] = 'List Deta';
	// 	$this->load->view('templates/header', $data);
	// 	$this->load->view('templates/sidebar');
	// 	$this->load->view("spr/edit/form_edit_spr", $data);
	// 	$this->load->view('templates/footer');

	// }

	// function getDetailSingkat($JobNo,$NoSPR){
	// 	if ($JobNo == '' || $NoSPR =='') {
	// 		redirect('Job','refresh');
	// 		exit;
	// 	}
	// 	$data['NoSPR'] = $NoSPR;
	// 	$data['JobNo'] = $JobNo;

	// 	$this->load->view('approved_spr/detail_singkat',$data);


	// }

	// public function index()
	// {
	// 	if ($JobNo == '') {
	// 		redirect('Job', 'refresh');
	// 		exit;
	// 	}

	// 	$data['judul'] = 'Update Permintaan Material / Alat';
	// 	$this->load->view('templates/header', $data);
	// 	$this->load->view('templates/sidebar');
	// 	$this->load->view("spr/edit/form_edit_spr", $data);
	// 	$this->load->view('templates/footer');




	// }

}

/* End of file Approved_spr.php */
/* Location: ./application/controllers/Approved_spr.php */