
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TrackingKO extends CI_Controller
{

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('M_RekapPD');
	}


	public function index()
	{
		
		redirect('MyReport/Procurement/Tracking_KO','refresh');
		

		$UserId = $this->session->userdata('MIS_LOGGED_ID');
		$GetAksesJob = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='$UserId' ")->row();
		$explode = explode(",", $GetAksesJob->AksesJob);
		$implode = "'" . implode("','",
			$explode
		) . "'";

		$data['Job'] = $this->db->query("SELECT * From Job Where TipeJob='Project' and Company='$this->Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan') and JobNo IN($implode) Order by JobNo DESC")->result();

		$data['judul'] = 'Tracking KO';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Procurement/FrmTrackingKO", $data);
		$this->load->view('templates/footer');
	}

	function dapatkan_table()
	{
		
	}
	
}
