<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LogoList extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
        // $this->load->model('Approve_permintaan_dana_keuangan_model');
		$this->load->helper('bantu');
	}

	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'List Logo Job ';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['dataJob'] = dapat_list_job();


		$myquery = $this->db->query("SELECT * FROM tbl_logo ORDER BY LogoID DESC ")->result();
		$data['data_logo'] = $myquery;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Logo_view/main_logo', $data);
		$this->load->view('templates/footer');
	}

	function form_add_logo(){
		if (!$this->input->post()) {
			exit;
		}
		print_rr($_POST);
	}


	function add_logo_action(){

		if (isset($_FILES['LogoFoto'])) {
			$this->load->library('upload');
			$config['upload_path']          = 'LogoKontrak/';
			$config['allowed_types']        = 'gif|jpg|png|jpeg|';
			$config['max_size']             = 2000;
			$config['encrypt_name'] = TRUE;

			$this->upload->initialize($config);


			if (!$this->upload->do_upload('LogoFoto')) {
				is_pesan('error','upload gagal!');
				redirect('LogoList');

			} else {
				$LogoFoto = $this->upload->data('file_name');
				$this->db->trans_begin();

				$this->db->query("INSERT INTO tbl_logo(LogoFoto) VALUES ('$LogoFoto') ");

				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					is_pesan('error','upload gagal!');
					redirect('LogoList');
				}
				else
				{
					$this->db->trans_commit();
					is_pesan('success','upload berhasil!');
					redirect('LogoList');
				}
				
			}


		}else{
			is_pesan('error','error!');
			redirect('LogoList');
		}	
	}

	function LihatFoto(){
		if (!$this->input->post()) {
			exit;
		}
		$LogoDefault = base_url('assets/logo_kosong.png');

		$LogoID = $this->input->post('LogoID',TRUE);
		$q = $this->db->query("SELECT * FROM tbl_logo WHERE LogoID='$LogoID' ");
		$row = $q->row();
		if ($q->num_rows() > 0) {
			if (file_exists('LogoKontrak/'.$row->LogoFoto)) {
				$LogoDefault = base_url('LogoKontrak/'.$row->LogoFoto);
				echo '<img class="img" src="'.$LogoDefault.'" />';
			}else{
				echo '<img class="img" src="'.$LogoDefault.'" />';
			}
		}
	}

	function PilihFoto(){
		if (!$this->input->post('flag')) {
			exit;
		}
		// print_rr($_POST);
		$data['data_logo'] = $this->db->query("SELECT * FROM tbl_logo ORDER BY LogoID DESC  ")->result();
		$this->load->view('Logo_view/pilih_foto',$data);
	}


	// function table_logo(){
	// 	if (!$this->input->post()) {
	// 		exit;
	// 	}



	// }

}

/* End of file LogoList.php */
/* Location: ./application/controllers/LogoList.php */