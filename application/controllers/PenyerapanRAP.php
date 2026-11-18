<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenyerapanRAP extends CI_Controller
{
	var $Company;
	public function index()
	{
		$this->load->model('M_PenyerapanRAP');
		$this->Company = $this->config->item('Company');

		$UserId = $this->session->userdata('MIS_LOGGED_ID');
		// $json = json_decode($UserId, true);
		// $DataSesiUser = $json['UserID'];
		$GetAksesJob = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='$UserId' ")->row();
		$explode = explode(",", $GetAksesJob->AksesJob);
		$implode = "'" . implode("','", $explode) . "'";

		// print_rr($implode);
		// exit();

		$data['Job'] = $this->db->query("SELECT * From Job Where TipeJob='Project' and Company='$this->Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan') and JobNo IN($implode) Order by JobNo DESC")->result();

		$GetAlokasi = $this->M_PenyerapanRAP->GetAlokasi();
		$data['AksesAlokasi'] = $GetAlokasi;


		// $this->parseData['alokasi'] = getAlokasi($UserId);
		// $this->parseData['job'] = getJob($UserId);
		// $this->parseData['query1'] = $this->M_PenyerapanRAP->GetTbl1();
		// $this->parseData['DetailPenyerapan'] = $this->M_PenyerapanRAP->DetailPenyerapan();
		// $this->parseData['InvoicePD'] = $this->M_QueryPd->InvoicePD();
		// $this->parseData['PembayaranPD'] = $this->M_QueryPd->PembayaranPD();

		$data['judul'] = 'Penyerapan RAP';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/PdPj/FrmPenyerapanRAP", $data);
		$this->load->view('templates/footer');
	}
}
