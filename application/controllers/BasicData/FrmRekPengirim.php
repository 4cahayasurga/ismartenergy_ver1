<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmRekPengirim extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'DAFTAR REKENING';
		$data['bodyclass'] = 'skin-blue';
		$query = $this->db->query("SELECT RekId, Bank, NoRek, AtasNama FROM Rekening WHERE Company='$this->Company' OR Company='*'");
		$data['ListRekening'] = $query->result();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmRekPengirim_V/main', $data);
		$this->load->view('templates/footer');
	}

}

/* End of file FrmRekPengirim.php */
/* Location: ./application/controllers/BasicData/FrmRekPengirim.php */