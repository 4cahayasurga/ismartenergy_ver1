
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapKO extends CI_Controller
{

	var $Company;
	public function index()
	{
		redirect('MyReport/Procurement/Rekap_KO','refresh');
		$this->load->model('M_RekapKO');
		$this->Company = $this->config->item('Company');

		// $Tbl1 = $this->M_QueryPd->GetTbl1();
		// $data['query'] = $Tbl1;

		$UserId = $this->session->userdata('MIS_LOGGED_ID');

		$GetAksesJob = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='$UserId' ")->row();
		$explode = explode(",", $GetAksesJob->AksesJob);
		$implode = "'" . implode(
			"','",
			$explode
		) . "'";

		$data['Job'] = $this->db->query("SELECT * From Job Where TipeJob='Project' and Company='$this->Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan') and JobNo IN($implode) Order by JobNo DESC")->result();

		 $data['dari_tgl'] = date('Y-m-d', strtotime('-2 month'));
        $data['sampai_tgl'] = date('Y-m-d');

		$data['judul'] = 'Rekap KO ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/Procurement/FrmRekapKO", $data);
		$this->load->view('templates/footer');
	}
}
