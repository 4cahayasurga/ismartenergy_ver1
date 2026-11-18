<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmPJLap extends CI_Controller {

	var $Company;
	var $StatusPJ = 'Lapangan';
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	public function index($JobNo='',$Alokasi='')
	{

		$data['title'] = 'Permintaan Dana Lapangan';
		$data['judul'] = 'Permintaan Dana Lapangan';
		$data['atur_kiri'] = '1';
		$data['ListJob'] = $this->ListJob();
		$data['ListAlokasi'] = $this->ListAlokasi(); 

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/FrmPJLap_View/halaman_utama', $data);
		$this->load->view('templates/footer');
	}


}

/* End of file FrmPJLap.php */
/* Location: ./application/controllers/Entry/PDPJ/FrmPJLap.php */