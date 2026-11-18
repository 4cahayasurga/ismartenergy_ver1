
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RekapPayment extends CI_Controller
{

	public function index()
	{
		$this->load->model('M_RekapPayment');

		// $Tbl1 = $this->M_QueryPd->GetTbl1();
		// $data['query'] = $Tbl1;

		$UserId = $this->session->userdata('MIS_LOGGED_ID');

		$this->parseData['alokasi'] = getAlokasi($UserId);
		$this->parseData['job'] = getJob($UserId);

		$data['judul'] = 'Rekap Pembayaran';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/PdPj/FrmRekapPayment", $data);
		$this->load->view('templates/footer');
	}
}
