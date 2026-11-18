<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmBank_C extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'DAFTAR BANK';
		$data['bodyclass'] = 'skin-blue';
		$query = $this->db->query("SELECT * FROM Bank");
		$data['ListBank'] = $query->result();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmBank_V/main', $data);
		$this->load->view('templates/footer');
	}

	public function SimpanBank(){
		if (!$this->input->post()) {
			is_pesan('error','terjadi kesalahan');
			redirect('BasicData/FrmBank_C','refresh');
		}

		// print_rr($_POST);
		// exit;

		$Bank = strtoupper($this->input->post('Bank'));
		$Bank_Id = $this->input->post('Bank_Id');
		$typeAksi = $this->input->post('typeAksi');
		$BankLama = $this->input->post('BankLama');

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		if ($typeAksi=='Simpan') {
			$cek = $this->db->query("SELECT * FROM Bank WHERE Bank='$Bank'");
			if ($cek->num_rows() > 0 ) {
				is_pesan('error','Bank : '.$Bank.' sudah terpakai');
				redirect('BasicData/FrmBank_C');
			}

			$this->db->trans_begin();

			$this->db->query("INSERT INTO Bank (Bank,UserEntry,TimeEntry) VALUES ('$Bank','$UserEntry','$TimeEntry')  ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmBank_C');	
			}else{
				$this->db->trans_commit();
				is_pesan('success','Saved!');
				redirect('BasicData/FrmBank_C');
			}
			
		}

		if ($typeAksi=='Update') {

			if ($Bank !=$BankLama) {
				$cek = $this->db->query("SELECT * FROM Bank WHERE Bank='$Bank'");
				if ($cek->num_rows() > 0 ) {
					is_pesan('error','Bank : '.$Bank.' sudah terpakai');
					redirect('BasicData/FrmBank_C');
				}
			}

			$this->db->trans_begin();

			$this->db->query("UPDATE Bank SET Bank='$Bank' WHERE Bank_Id='$Bank_Id' ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmBank_C');
			}else{
				$this->db->trans_commit();
				is_pesan('success','Updated!');
				redirect('BasicData/FrmBank_C');
			}

			
		}

		
	}

}

/* End of file FrmBank.php */
/* Location: ./application/controllers/BasicData/FrmBank.php */