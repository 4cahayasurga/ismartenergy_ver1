<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FrmResumeK3 extends CI_Controller{

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('M_job');
	}

	public function index($JobNo)
	{
		$UserID = $this->session->userdata('MIS_LOGGED_ID');
		// print_rr($_SESSION);
		$this->load->model('m_job');

		$data['judul'] = 'Keselamatan dan Kesehatan Kerja';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$Appspr = $this->m_job->dataproyek($JobNo);
		$data['k3'] = $Appspr;

		$Q_K3 = $this->db->query("SELECT * FROM Tbl_ResumeK3 Where JobNo=$JobNo")->result();
		$data['DataK3'] = $Q_K3;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('K3/ResumeK3', $data);
		$this->load->view('templates/footer');
	}

	public function Simpan()
	{
		$JobNo	= $this->input->post('JobNo');
		$Periode 	= $this->input->post('PeriodeK3');
		$KasusKematian = $this->input->post('KasusKematian');
		$FR 		= $this->input->post('FR');
		$SR 		= $this->input->post('SR');
		$IR 		= $this->input->post('IR');
		$NCO 	= $this->input->post('NCO');
		$NCC 	= $this->input->post('NCC');
		$UserEntry 	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry	= date("Y-m-d H:i:s");

		$data = array(
			'JObNo' => $JobNo,
			'KasusKematian' => $KasusKematian,
			'Periode' => $Periode,
			'FR' => $FR,
			'SR' => $SR,
			'IR' => $IR,
			'NcOpen' => $NCO,
			'NcClose' => $NCC,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry 
		); 


		// print_rr($data);
		// exit;

		$this->M_job->SimpanData('Tbl_ResumeK3', $data);
		redirect('FrmResumeK3/Index/' . $JobNo);
	}

	

}
