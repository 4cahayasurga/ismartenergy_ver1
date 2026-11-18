<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval_PJ_CostControl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('Approval_PDPJ_model/Model_Approval_PJ_CostControl');
	}

	function Pj_cost_control_data_list($JobNo='',$Alokasi='',$dari_tgl='',$sampai_tgl='',$dari_tgl_pj='',$sampai_tgl_pj='')
	{
		if ($JobNo == '') {
			redirect('Welcome/index');
			exit;
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Approval Pertanggungjawaban Dana (Cost Control)';
		$data['bodyclass'] = 'sidebar-collapse skin-seccess';

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = ($Alokasi !='' ) ? $Alokasi : ''  ;

		$panggil_job = $this->db->query("SELECT TOP 1 JobNo, JobNm,Lokasi FROM Job WHERE JobNo='$JobNo'  ")->row();
		$data['JobNo'] = ($JobNo == '') ? $panggil_job->JobNo : $JobNo;
		$data['JobNm'] = $panggil_job->JobNm;
		$data['Lokasi'] = $panggil_job->Lokasi;

		$data['dari_tgl'] = $dari_tgl;
		$data['sampai_tgl'] = $sampai_tgl;

		$data['dari_tgl_pj'] = $dari_tgl_pj;
		$data['sampai_tgl_pj'] = $sampai_tgl_pj;

		$data['data_alokasi'] = AksesAlokasi();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('view_approval_PDPJ/cost_control/pj_costcontrol_data_list', $data);
		$this->load->view('templates/footer');

	}

	function tampilkan_table_data_approval_pj_costcontrol(){
		if (!$this->input->post()) {
			die;
		}


		$JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $dari_tgl = $this->input->post('dari_tgl', TRUE);
        $sampai_tgl = $this->input->post('sampai_tgl', TRUE);

        $dari_tgl_pj = $this->input->post('dari_tgl_pj', TRUE);
        $sampai_tgl_pj = $this->input->post('sampai_tgl_pj', TRUE);

        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;

        $data['dari_tgl_pj'] = $dari_tgl_pj;
        $data['sampai_tgl_pj'] = $sampai_tgl_pj;

        $this->load->view('view_approval_PDPJ/cost_control/table_data_approval_pj_costcontrol',$data);

	}


	// function tampilkan_table_data_approval_pj_costcontrol(){

	// 	if (!$this->input->post()) {
	// 		exit;
	// 	}

	// 	if ($this->input->post('approval_pj_costcontrol') <> 'approval_pj_costcontrol' ) {
	// 		exit;
	// 	}

	// 	$JobNo = $this->input->post('JobNo',TRUE);
	// 	$Alokasi = $this->input->post('Alokasi',TRUE);

	// 	$data['JobNo'] = $JobNo;
	// 	$data['Alokasi'] = $Alokasi;

	// 	$dapat_TotalPD_dan_TotalPJ = $this->db->query("SELECT SUM(TotalPD) as TotalPD,SUM(TotalPJ) as TotalPJ FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND PjApprovedByAK IS NOT NULL")->row_array();

	// 	$data['TotalPJ'] = $dapat_TotalPD_dan_TotalPJ['TotalPJ'];
	// 	$data['TotalPD'] = $dapat_TotalPD_dan_TotalPJ['TotalPD'];



	// 	$this->load->view('view_approval_PDPJ/cost_control/table_data_approval_pj_costcontrol',$data);

	// }

	function dapatkan_data_json_approval_pj_costcontrol(){

		if (!$this->input->post()) {
			exit;
		}


		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$dari_tgl = $this->input->post('dari_tgl', TRUE);
        $sampai_tgl = $this->input->post('sampai_tgl', TRUE);

        $dari_tgl_pj = $this->input->post('dari_tgl_pj', TRUE);
        $sampai_tgl_pj = $this->input->post('sampai_tgl_pj', TRUE);

		$where = array(
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,

		);

		header('Content-Type: application/json');
		echo $this->Model_Approval_PJ_CostControl->model_dapatkan_data_json_approval_pj_costcontrol($JobNo,$Alokasi, $dari_tgl, $sampai_tgl, $dari_tgl_pj,$sampai_tgl_pj);

	}

	function aksi_approve_pj_costcontrol(){
		if (!$this->input->post()) {
			exit;
		}

		// echo 'aaa';
		// echo json_encode(array('ini' =>'ggg'));
		// exit;


		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);
		$flag_status_approve = ($this->input->post('flag_status_approve',TRUE) == 0) ? 'APPROVE' : 'UNAPPROVE';

		$dapat_data = $this->db->query("SELECT *,(SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE FROM PdHdr WHERE NoPD='$NoPD'");

		$jumlah_data = $dapat_data->num_rows();
		$row_pd = $dapat_data->row_array();


		//cek ada data atau tidak
		if ($jumlah_data == 0) {
			$myreturn = array(
				'status' => 'data_tidak_ada', 
				'pesan' => 'Data tidak ditemukan', 
				'NoPD' => $NoPD,
			);
			echo json_encode($myreturn);
			exit;
		}



		//cek reject
		if ($row_pd['RejectBy'] !=NULL || $row_pd['RejectBy'] !='' ) {
			$RejectBy = $row_pd['RejectBy'];
			$myreturn = array(
				'status' => 'reject', 
				'pesan' => 'Data sudah di reject oleh '.$RejectBy.'', 
				'NoPD' => $NoPD,
			);
			echo json_encode($myreturn);
			exit;
		}

		//cek pembayaran
		if ($row_pd['CountBLE'] == 0 ) {
			$myreturn = array(
				'status' => 'belum_bayar', 
				'pesan' => 'Belum ada pembayaran untuk No '.$NoPD.' ', 
				'NoPD' => $NoPD,
			);
			echo json_encode($myreturn);
			exit;
		}



		if ($flag_status_approve == 'APPROVE') {

			if ($row_pd['NoPJ'] == NULL || $row_pd['NoPJ'] == '' ) {

				$myreturn = array(
					'status' => 'pj_masih_kosong', 
					'pesan' => 'No PJ masih kosong ', 
					'NoPD' => $NoPD,
				);
				echo json_encode($myreturn);
				exit;
			}

			$aksi_update_pj = $this->Model_Approval_PJ_CostControl->aksi_update_approval_pj_costcontrol($flag_status_approve,$NoPD);
			if ($aksi_update_pj == TRUE) {
				echo json_encode(array('status' => 'berhasil','pesan' => 'berhasil mengubah data'));
				exit;
			}
			echo json_encode(array('status' => 'gagal','pesan' => 'gagal mengubah data' ));
			exit;
		}

		// echo json_encode(array('status'=> 'ok'));
		// exit;

		if ($flag_status_approve == 'UNAPPROVE') {


			if ($row_pd['PjApprovedByKK'] != NULL || $row_pd['PjApprovedByKK'] != '' ) {

				$myreturn = array(
					'status' => 'sudah_approvedKK', 
					'pesan' => 'Sudah di-approved oleh Keuangan.',  
					'NoPD' => $NoPD,
				);
				echo json_encode($myreturn);
				exit;
			}

			// exit;
			$aksi_update_pj = $this->Model_Approval_PJ_CostControl->aksi_update_approval_pj_costcontrol($flag_status_approve,$NoPD);

			if ($aksi_update_pj == TRUE) {
				echo json_encode(array('status' => 'berhasil','pesan' => 'berhasil mengubah data'));
				exit;
			}

			echo json_encode(array('status' => 'gagal','pesan' => 'gagal mengubah data' ));
			exit;
			
		}


	}




}

/* End of file Approval_PJ_CostControl.php */
/* Location: ./application/controllers/Approval_PDPJ/Approval_PJ_CostControl.php */