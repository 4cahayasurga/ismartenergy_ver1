<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TataKelola_new extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('Master_model/Model_tata_kelola');
	}

	function data_list_tatakelola($JobNo='')
	{
		if($this->session->userdata('PecahToken')->Akses_TataKelola == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		if ($JobNo == '') {
			redirect('Welcome/index');
			exit;
		}

		$dataJob = $this->Model_tata_kelola->dapatkan_data_job($JobNo);
		
		if ($dataJob->num_rows() == 0) {
			is_pesan('error','data tidak ditemukan');
			redirect('Welcome/index','refresh');
			exit;
		}

		$data['dataJob'] = $dataJob->row_array();

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Tata Kelola';
		$data['bodyclass'] = 'sidebar-collapse skin-purple';

		$tgl_saat_ini = date('Y-m-d');

		$data['tgl_pertama'] = date('Y-m-d', strtotime('-7 days'));
		$data['tgl_terakhir'] = $tgl_saat_ini;
		$data['JobNo'] = $JobNo;

		//flag
		$data['myflag'] = 'tata_kelola';

		$myselect = '';

		if ($data['dataJob']['TipeManajerial'] == 'Single') {
			$myselect = 'selected';
		}

		if ($data['dataJob']['TipeManajerial'] == 'JO Integrated') {
			$myselect = 'selected';
		}


		if ($data['dataJob']['TipeManajerial'] == 'JO Partial') {
			$myselect = 'selected';
		}

		$data['myselect'] = $myselect;

		// print_rr($dataJob->row_array());
		// exit;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('master_view/tata_kelola/tata_kelola_form_awal', $data);
		$this->load->view('templates/footer');

	}

	function aksi_ubah_tatakelola(){

		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Akses_TataKelola == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		if ($this->input->post('myflag') <> 'tata_kelola'  ) {
			exit;
		}
		// print_rr($_POST);
		// echo "<hr>";
		// print_rr($data_gabungan);
		// exit;

		// $JobNo = $this->input->post('JobNo',TRUE);


		$KSO = $this->input->post('KSO',TRUE);
		$TipeManajerial = $this->input->post('TipeManajerial',TRUE);
		$NoRekKSO = $this->input->post('NoRekKSO',TRUE);
		$Own_check = ($this->input->post('Own_check',TRUE)) ? $this->input->post('Own_check',TRUE) : NULL ;

		$set_array = array(
			'KSO' => $KSO,
			'TipeManajerial' => $TipeManajerial,
			'NoRekKSO' => $NoRekKSO,
		);

		if ($KSO == 1 && $TipeManajerial == 'Single') {
			echo 'tidakboleh';
			exit;
		}





		//Leader
		
		$Member1 = ($this->input->post('Member1',TRUE)) ? $this->input->post('Member1',TRUE) :'' ;
		$PersenShare1 = ($this->input->post('PersenShare1',TRUE)) ? $this->input->post('PersenShare1',TRUE) :'' ;
		$BrutoShare1 = ($this->input->post('BrutoShare1',TRUE)) ? $this->input->post('BrutoShare1',TRUE) :'' ;
		$AtasNamaInduk = ($this->input->post('AtasNamaInduk',TRUE)) ? $this->input->post('AtasNamaInduk',TRUE) :'' ;
		$BankInduk = ($this->input->post('BankInduk',TRUE)) ? $this->input->post('BankInduk',TRUE) :'' ;
		$NoRekInduk = ($this->input->post('NoRekInduk',TRUE)) ? $this->input->post('NoRekInduk',TRUE) :'' ;
		$KetShare1 = ($this->input->post('KetShare1',TRUE)) ? $this->input->post('KetShare1',TRUE) :'' ;
		//akhir leader


		//member
		$Member2 = ($this->input->post('Member2',TRUE)) ? $this->input->post('Member2',TRUE) :'' ;
		$PersenShare2 = ($this->input->post('PersenShare2',TRUE)) ? $this->input->post('PersenShare2',TRUE) :'' ;
		$BrutoShare2 = ($this->input->post('BrutoShare2',TRUE)) ? $this->input->post('BrutoShare2',TRUE) :'' ;
		$AtasNamaMember = ($this->input->post('AtasNamaMember',TRUE)) ? $this->input->post('AtasNamaMember',TRUE) :'' ;
		$BankMember = ($this->input->post('BankMember',TRUE)) ? $this->input->post('BankMember',TRUE) :'' ;
		$NoRekMember = ($this->input->post('NoRekMember',TRUE)) ? $this->input->post('NoRekMember',TRUE) :'' ;
		$KetShare2 = ($this->input->post('KetShare2',TRUE)) ? $this->input->post('KetShare2',TRUE) :'' ;

		//akhir member

		$data_gabungan = array(
			'KSO' => $KSO,
			'TipeManajerial' => $TipeManajerial,
			'NoRekKSO' => $NoRekKSO,
			'Own' => $Own_check,

			//leader
			'Member1' => $Member1,
			'PersenShare1' => $PersenShare1,
			'BrutoShare1' => $BrutoShare1,
			'AtasNamaInduk' => $AtasNamaInduk,
			'BankInduk' => $BankInduk,
			'NoRekInduk' => $NoRekInduk,
			'KetShare1' => $KetShare1,

			//akhir leader


			//member
			'Member2' => $Member2,
			'PersenShare2' => $PersenShare2,
			'BrutoShare2' => $BrutoShare2,
			'AtasNamaMember' => $AtasNamaMember,
			'BankMember' => $BankMember,
			'NoRekMember' => $NoRekMember,
			'KetShare2' => $KetShare2,
			//akhir member
		);

		// echo $BrutoShare1;
		// echo "<br></hr>";
		// echo str_replace(".", "", $BrutoShare1);
		// exit;

		// print_rr($_POST);
		// echo "<hr>";
		// print_rr($data_gabungan);
		// exit;


		$query = $this->Model_tata_kelola->update_tatakelola($JobNo,$set_array,$data_gabungan,$KSO);
		if ($query == TRUE) {
			is_pesan('success', 'berhasil mengubah data');
			echo 'success';
			exit;
		}
		is_pesan('error', 'gagal mengubah data');
		echo 'gagal';
		exit;

		
	}



}

/* End of file TataKelola_new.php */
/* Location: ./application/controllers/Master/TataKelola_new.php */