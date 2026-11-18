<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approved_pd_lap extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->akses = array(
			'UserID' => GetAksesUser('UserID')

		);
		$this->akses = GetAksesUser('UserID');
		$this->load->model('Approved_pd_lap_model');
	}

	public function view($JobNo='')
	{
		if ($JobNo == '') {

			redirect('Job/index', 'refresh');
			exit;
		}


		$data['judul'] = 'Approve PD (LAPANGAN)';
		$data['Job'] = GetJob($JobNo)->row();
		$data['alokasi'] = GetAlokasi($this->akses);
		$data['JobNo'] = $JobNo;
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('approved_pd_lap/approve_pd_lap_view', $data);
		$this->load->view('templates/footer');
	}

	function show_table()
	{
		if ($this->input->post()) {
			$JobNo = $this->input->post('JobNo');
			$alokasi = $this->input->post('alokasi');
			$cari = $this->input->post('cari');

			$data['JobNo'] = $JobNo;
			$data['alokasi'] = $alokasi;
			$data['cari'] = $cari;

			// echo $alokasi;
			// exit;
			$this->load->view('approved_pd_lap/table_approve_pd_lap', $data);
		}
	}


	function get_json_pd($JobNo = '', $alokasi = '', $cari = '')
	{
		if ($JobNo != '') {
			header('Content-Type: application/json');
			echo $this->Approved_pd_lap_model->get_json_pd($JobNo, $alokasi, $cari);
		}
	}

	function status()
	{
		if ($this->input->post()) {
			$ApprovedByKT = $this->input->post('ApprovedByKT');
			$JobNo = $this->input->post('JobNo');
			$NoPD = $this->input->post('NoPD');

			if ($ApprovedByKT == '') {
				$data = array(
					'ApprovedByKT' => $this->session->userdata('MIS_LOGGED_NAME'),
					'TimeApprovedKT' => date("Y-m-d H:i:s")
				);
			} else {
				$data = array(
					'ApprovedByKT' => NULL,
					'TimeApprovedKT' => NULL
				);
			}
			$where = array(
				'NoPD' => $NoPD,
				'JobNo' => $JobNo
			);
			$simpan = $this->Approved_pd_lap_model->ApproveUnapprove($where, $data);
			if ($simpan === TRUE) {

				if ($ApprovedByKT == '') {
					echo json_encode(array(
						'status' => 'success',
						'approve' => 'ya'
					));
					exit;
				} else {
					echo json_encode(array(
						'status' => 'success',
						'approve' => 'no'
					));
					exit;
				}
			}
			echo json_encode(array(
				'status' => 'no'
			));
			exit;
		}
	}


}

/* End of file Approved_pd_lap.php */
/* Location: ./application/controllers/Approved_pd_lap.php */