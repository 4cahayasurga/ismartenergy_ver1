
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapOs extends CI_Controller
{

	public function index()
	{
		$this->load->model('M_PenyerapanRAP');

		// $Tbl1 = $this->M_QueryPd->GetTbl1();
		// $data['query'] = $Tbl1;

		$UserId = $this->session->userdata('MIS_LOGGED_ID');

		$this->parseData['alokasi'] = getAlokasi($UserId);
		$this->parseData['job'] = getJob($UserId);
		// $this->parseData['query1'] = $this->M_PenyerapanRAP->GetTbl1();
		// $this->parseData['DetailPenyerapan'] = $this->M_PenyerapanRAP->DetailPenyerapan();
		// $this->parseData['InvoicePD'] = $this->M_QueryPd->InvoicePD();
		// $this->parseData['PembayaranPD'] = $this->M_QueryPd->PembayaranPD();
		$data['judul'] = 'Rekap OutStanding / Approve PD ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/PdPj/FrmRekapOS", $data);
		$this->load->view('templates/footer');
	}
}
