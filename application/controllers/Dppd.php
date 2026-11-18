
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dppd extends CI_Controller
{
	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		
		$this->load->model('M_kontrak_po');
		$this->load->helper('bantu');
		$this->load->helper('racode');
	}

	public function index()
	{
		$this->load->model('M_Dppd');

		// $Tbl1 = $this->M_QueryPd->GetTbl1();
		// $data['query'] = $Tbl1;

		$UserId = $this->session->userdata('MIS_LOGGED_ID');

		// $json = json_decode($UserId, true);
		// $DataSesiUser = $json['UserID'];
		$GetAksesJob = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='$UserId' ")->row();
		$explode = explode(",", $GetAksesJob->AksesJob);
		$implode = "'" . implode("','", $explode) . "'";

		// print_rr($implode);
		// exit();

		$data['Job'] = $this->db->query("SELECT * From Job Where TipeJob='Project' and Company='$this->Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan') and JobNo IN($implode) Order by JobNo DESC")->result();

		// $this->parseData['alokasi'] = getAlokasi($UserId);
		$GetAlokasi = $this->M_Dppd->GetAlokasi();
		$data['AksesAlokasi'] = $GetAlokasi;

		// $this->parseData['job'] = getJob($UserId);
		// $data['job'] = GetJob($UserId);

		// $this->parseData['query1'] = $this->M_Dppd->GetTbl1();
		$query1 = $this->M_Dppd->GetTbl1();
		$data['query1'] = $query1;


		// $this->parseData['UraianPD'] = $this->M_Dppd->uraianPD();
		$uraianPD = $this->M_Dppd->uraianPD();
		$data['UraianPD']=$uraianPD;

		$data['judul'] = 'Daftar Permintaan Dana Dan Pertanggung jawaban ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/PdPj/FrmDPPD", $data);
		$this->load->view('templates/footer');
	}
}
