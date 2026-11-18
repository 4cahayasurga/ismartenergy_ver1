<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmKategori_C extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		// error_reporting(E_ALL);
	}

	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Daftar Kategori';
		// $data['bodyclass'] = 'sidebar-collapse skin-red-light';

		$query = $this->db->query("SELECT * FROM Kategori  ");
		$data['ListKategori'] = $query->result();
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmKategori_V/main', $data);
		$this->load->view('templates/footer');
	}

	public function SimpanKategori(){
		if (!$this->input->post()) {
			is_pesan('error','terjadi kesalahan');
			redirect('BasicData/FrmKategori_C','refresh');
		}

		// print_rr($_POST);
		// exit;

		$KategoriId = strtoupper($this->input->post('KategoriId'));
		$Keterangan = strtoupper($this->input->post('Keterangan'));
		$typeAksi = $this->input->post('typeAksi');
		$KategoriIdLama = $this->input->post('KategoriIdLama');

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		if ($typeAksi=='Simpan') {
			$cek = $this->db->query("SELECT * FROM Kategori WHERE KategoriId='$KategoriId'");
			if ($cek->num_rows() > 0 ) {
				is_pesan('error','Kategori : '.$KategoriId.' sudah terpakai');
				redirect('BasicData/FrmKategori_C');
			}

			$this->db->trans_begin();

			$this->db->query("INSERT INTO Kategori (KategoriId,Keterangan,UserEntry,TimeEntry) VALUES ('$KategoriId','$Keterangan','$UserEntry','$TimeEntry')  ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmKategori_C');
			}else{
				$this->db->trans_commit();
				is_pesan('success','Saved!');
				redirect('BasicData/FrmKategori_C');
			}
			
		}

		if ($typeAksi=='Update') {

			if ($KategoriId !=$KategoriIdLama) {
				$cek = $this->db->query("SELECT * FROM Kategori WHERE KategoriId='$KategoriId'");
				if ($cek->num_rows() > 0 ) {
					is_pesan('error','Kategori : '.$KategoriId.' sudah terpakai');
					redirect('BasicData/FrmKategori_C');
				}
			}

			$this->db->trans_begin();

			$this->db->query("UPDATE Kategori SET KategoriId='$KategoriId', Keterangan='$Keterangan' WHERE KategoriId='$KategoriIdLama' ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmKategori_C');
			}else{
				$this->db->trans_commit();
				is_pesan('success','Updated!');
				redirect('BasicData/FrmKategori_C');
			}

			
		}

		
	}


}

/* End of file FrmAlokasi.php */
/* Location: ./application/controllers/BasicData/FrmAlokasi.php */