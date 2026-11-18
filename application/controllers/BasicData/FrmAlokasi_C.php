<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmAlokasi_C extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		// error_reporting(E_ALL);
	}

	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'DAFTAR ALOKASI';
		$data['bodyclass'] = 'skin-blue';
		$query = $this->db->query("SELECT * FROM Alokasi  ");
		$data['ListAlokasi'] = $query->result();
		


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmAlokasi_V/main', $data);
		$this->load->view('templates/footer');
	}

	public function SimpanAlokasi(){
		if (!$this->input->post()) {
			is_pesan('error','terjadi kesalahan');
			redirect('BasicData/FrmAlokasi_C','refresh');
		}


		$Alokasi = strtoupper($this->input->post('Alokasi'));
		$Keterangan = strtoupper($this->input->post('Keterangan'));
		$typeAksi = $this->input->post('typeAksi');
		$AlokasiLama = $this->input->post('AlokasiLama');

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		if ($typeAksi=='Simpan') {
			$cek = $this->db->query("SELECT * FROM Alokasi WHERE Alokasi='$Alokasi'");
			if ($cek->num_rows() > 0 ) {
				is_pesan('error','Alokasi : '.$Alokasi.' sudah terpakai');
				redirect('BasicData/FrmAlokasi_C');
			}

			$this->db->trans_begin();

			$this->db->query("INSERT INTO Alokasi (Alokasi,Keterangan,UserEntry,TimeEntry) VALUES ('$Alokasi','$Keterangan','$UserEntry','$TimeEntry')  ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmAlokasi_C');
			}else{
				$this->db->trans_commit();
				is_pesan('success','Saved!');
				redirect('BasicData/FrmAlokasi_C');
			}
			
		}

		if ($typeAksi=='Update') {

			$CekAlokasi = $this->db->query("SELECT * FROM PdHdr WHERE Alokasi='$Alokasi' ");
			if ($CekAlokasi->num_rows() > 0 ) {
				is_pesan('error','Alokasi sudah terpakai, tidak dapat di-ubah atau di-hapus');
				redirect('BasicData/FrmAlokasi_C');
			}

			if ($Alokasi !=$AlokasiLama) {
				$cek = $this->db->query("SELECT * FROM Alokasi WHERE Alokasi='$Alokasi'");
				if ($cek->num_rows() > 0 ) {
					is_pesan('error','Alokasi : '.$Alokasi.' sudah terpakai');
					redirect('BasicData/FrmAlokasi_C');
				}
			}

			$this->db->trans_begin();

			$this->db->query("UPDATE Alokasi SET Alokasi='$Alokasi', Keterangan='$Keterangan' WHERE Alokasi='$AlokasiLama' ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmAlokasi_C');
			}else{
				$this->db->trans_commit();
				is_pesan('success','Updated!');
				redirect('BasicData/FrmAlokasi_C');
			}

			
		}

		
	}

	function hapus($Alokasi=''){
		if ($Alokasi == '') {
			is_pesan('error','gagal saat menghapus data');
			redirect('BasicData/FrmAlokasi_C');
		}

		$CekAlokasi = $this->db->query("SELECT * FROM PdHdr WHERE Alokasi='$Alokasi' ");
		if ($CekAlokasi->num_rows() > 0 ) {
			is_pesan('error','Alokasi sudah terpakai, tidak dapat di-ubah atau di-hapus');
			redirect('BasicData/FrmAlokasi_C');
		}

		$this->db->trans_begin();

		$this->db->query("DELETE FROM Alokasi WHERE Alokasi='$Alokasi'  ");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			is_pesan('error','gagal menghapus data, silahkan ulangi proses');
			redirect('BasicData/FrmAlokasi_C');
		}else{
			$this->db->trans_commit();
			is_pesan('success','Deleted!');
			redirect('BasicData/FrmAlokasi_C');
		}
	}

}

/* End of file FrmAlokasi.php */
/* Location: ./application/controllers/BasicData/FrmAlokasi.php */