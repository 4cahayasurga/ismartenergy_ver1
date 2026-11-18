
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapPDJaminan extends CI_Controller
{

	var $Company;
	public function __construct()
	{
		parent::__construct();
		redirect('Laporan/Report_PD_Jaminan','refresh');
		is_login();

		$this->Company = $this->config->item('Company');

		$this->load->model('M_RekapPD');
	}


	public function index()
	{
	

		$UserId = $this->session->userdata('MIS_LOGGED_ID');
		// $json = json_decode($UserId, true);
		// $DataSesiUser = $json['UserID'];
		$GetAksesJob = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='$UserId' ")->row();
		$explode = explode(",", $GetAksesJob->AksesJob);
		$implode = "'" . implode("','",
			$explode
		) . "'";

		$data['Job'] = $this->db->query("SELECT * From Job Where TipeJob='Project' and Company='$this->Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan') and JobNo IN($implode) Order by JobNo DESC")->result();

		// $GetAlokasi = $this->M_QueryPd->GetAlokasi();
		// $data['AksesAlokasi'] = $GetAlokasi;
		
		// $this->parseData['query1'] = $this->M_QueryPd->GetTbl1();
		// $this->parseData['UraianPD'] = $this->M_QueryPd->uraianPD();
		// $this->parseData['InvoicePD'] = $this->M_QueryPd->InvoicePD();
		// $this->parseData['PembayaranPD'] = $this->M_QueryPd->PembayaranPD();

		$data['judul'] = 'Rekap PD Jaminan';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/PdPj/RekapPDJaminan", $data);
		$this->load->view('templates/footer');
	}

	function dapatkan_table()
	{
		
	}
	
}
