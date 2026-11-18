<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PD_keuangan extends CI_Controller {

	public function index()
	{
		parent::__construct();
		is_login();
		$this->load->model('Menu_Entry_model/M_pd_keuangan');
	}

	public function list_data($JobNo='')
	{

		if ($JobNo == '') {
			redirect('Welcome/index','refresh');
			exit;
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'PERMINTAAN DANA (KEUANGAN) ';
		$data['bodyclass'] = 'sidebar-collapse skin-green';

		$data['JobNo'] = $JobNo;
		$data['dataJob'] = dapat_data_job($JobNo);
		$data['data_alokasi'] = AksesAlokasi();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Menu_entry_view/pd_keuangan/list_pd_keuangan', $data);
		$this->load->view('templates/footer');
	}

}

/* End of file PD_keuangan.php */
/* Location: ./application/controllers/Menu_Entry/PD_keuangan.php */