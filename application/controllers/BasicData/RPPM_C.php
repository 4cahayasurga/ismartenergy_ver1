<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RPPM_C extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}
	
	public function index($JobNo='',$Alokasi='')
	{
		$data['judul'] = 'RPPM (Realisasi Pelaksanaan Progress Mingguan)';
		$data['title'] = 'RPPM (Realisasi Pelaksanaan Progress Mingguan)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['ListJob'] = ListJob();
		$data['JobNo'] = $JobNo;

		$data['ListAlokasi'] = ListAlokasi(); 
		$data['Alokasi'] = $Alokasi;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmRPPM_View/halaman_utama', $data);
		$this->load->view('templates/footer');
	}

}

/* End of file RPPM_C.php */
/* Location: ./application/controllers/BasicData/RPPM_C.php */ 
