<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TeamPC_C extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		PenambahanTEAMPO_TEAMPC();
	}

	public function index()
	{
		$data['title'] = 'TEAM PC';
		$data['judul'] = 'TEAM PC';
		$data['bodyclass'] = 'skin-blue';

		// $data['JobList'] = $this->db->query("SELECT * FROM Job WHERE Company='$this->Company'  ")->result();
		$data['JobList'] = $this->db->query("SELECT * FROM Job WHERE Company IN ('MDH', 'KIP', 'DLL') ")->result();
		$data['KaryawanList'] = $this->db->query("SELECT * FROM Karyawan WHERE active=1 ")->result();
		$data['tableList'] = $this->db->query("SELECT * FROM Job WHERE TeamPC IS NOT NULL ")->result();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('TeamPC_V/main', $data);
		$this->load->view('templates/footer');
	}

	function SimpanTeamPC(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Kopro = $this->input->post('Kopro', TRUE);
		$ProjectMgr = $this->input->post('ProjectMgr',TRUE);
		$TeamPC = $this->input->post('KaryawanPC',TRUE);
		$TeamPO = $this->input->post('KaryawanPO',TRUE);

		// $CekPC = $this->db->query("SELECT * FROM Job WHERE JobNo='$JobNo' AND TeamPC='$TeamPC' ")->num_rows();
		// if ($CekPC > 0) {
		// 	is_pesan('info','Job '.$JobNo.' - '.$TeamPC.' sudah ada');
		// 	redirect('TeamPC_C');
		// 	die;
		// }

		// $CekPO = $this->db->query("SELECT * FROM Job WHERE JobNo='$JobNo' AND TeamPO='$TeamPO' ")->num_rows();
		// if ($CekPC > 0) {
		// 	is_pesan('info','Job '.$JobNo.' - '.$TeamPO.' sudah ada');
		// 	redirect('TeamPC_C');
		// 	die;
		// }

		// $CekPO = $this->db->query("SELECT * FROM Job WHERE JobNo='$JobNo' AND ProjectMgr='$ProjectMgr' ")->num_rows();
		// if ($CekPC > 0) {
		// 	is_pesan('info', 'Job ' . $JobNo . ' - ' . $TeamPO . ' sudah ada');
		// 	redirect('TeamPC_C');
		// 	die;
		// }

		$set = array(
			'Kopro' => $Kopro,
			'ProjectMgr' => $ProjectMgr,
			'TeamPC' => $TeamPC,
			'TeamPO' => $TeamPO
		);

		if ($Kopro=='') {
			unset($set['Kopro']);
		}

		if ($ProjectMgr=='') {
			unset($set['ProjectMgr']);
		}

		if ($TeamPC=='') {
			unset($set['TeamPC']);
		}

		if ($TeamPO=='') {
			unset($set['TeamPO']);
		}

		$where = array(
			'JobNo' => $JobNo,
		);

		$this->db->set($set)->where($where)->update('Job');
		is_pesan('success','saved!');
		redirect('TeamPC_C');
	}

}

/* End of file TeamPC_C.php */
/* Location: ./application/controllers/TeamPC_C.php */
