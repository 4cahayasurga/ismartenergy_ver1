<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmTipeForm_C extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'Daftar Tipe Form';
		// $data['bodyclass'] = 'sidebar-collapse skin-red-light';

		$query = $this->db->query("SELECT TipeForm, Alokasi, Keterangan FROM TipeForm");
		$data['ListTipeForm'] = $query->result();
		$data['ListAlokasi'] = $this->GetAlokasi();


		// print_rr($this->GetAlokasi());
		// exit;
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmTipeForm_V/main', $data);
		$this->load->view('templates/footer');
	}

	private function GetAlokasi(){

		$AksesAlokasi = $this->session->userdata('PecahToken')->AksesAlokasi;
		// $AksesAlokasi = 'B,C';
		$QueryAlokasi = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi")->result_array();
		$ArrayAlokasi = array();
		foreach($QueryAlokasi as $ListAlokasi ){
			if ($ListAlokasi['Alokasi'] == '' || $ListAlokasi['Alokasi'] == NULL) {
				continue;
			}
			if (!preg_match('/\b' . $ListAlokasi['Alokasi'] . '\b/', $AksesAlokasi)) {
				continue;
			}
			$ArrayAlokasi[] = (object) array(
				'Alokasi' => $ListAlokasi['Alokasi'],
				'Keterangan' => $ListAlokasi['Keterangan'],
				'GabungAlokasi' => $ListAlokasi['Alokasi'].' - '.$ListAlokasi['Keterangan'],
			);
		}
		return $ArrayAlokasi;

	}

	public function SimpanTipeForm(){
		if (!$this->input->post()) {
			is_pesan('error','terjadi kesalahan');
			redirect('BasicData/FrmTipeForm_C','refresh');
		}

		// print_rr($_POST);
		// exit;

		$TipeForm = strtoupper($this->input->post('TipeForm'));
		$Alokasi = strtoupper($this->input->post('Alokasi'));
		$Keterangan = strtoupper($this->input->post('Keterangan'));

		$typeAksi = $this->input->post('typeAksi');
		$TipeFormLama = $this->input->post('TipeFormLama');

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		if ($typeAksi=='Simpan') {
			$cek = $this->db->query("SELECT * FROM TipeForm WHERE TipeForm='$TipeForm'");
			if ($cek->num_rows() > 0 ) {
				is_pesan('error','TipeForm : '.$TipeForm.' sudah terpakai');
				redirect('BasicData/FrmTipeForm_C');
			}

			$this->db->trans_begin();

			$this->db->query("INSERT INTO TipeForm (TipeForm,Alokasi,Keterangan,UserEntry,TimeEntry) VALUES ('$TipeForm','$Alokasi','$Keterangan','$UserEntry','$TimeEntry')  ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmTipeForm_C');
			}else{
				$this->db->trans_commit();
				is_pesan('success','Saved!');
				redirect('BasicData/FrmTipeForm_C');
			}
			
		}

		if ($typeAksi=='Update') {


			if ($TipeForm !=$TipeFormLama) {
				$cek = $this->db->query("SELECT * FROM TipeForm WHERE TipeForm='$TipeForm'");
				if ($cek->num_rows() > 0 ) {
					is_pesan('error','TipeForm : '.$TipeForm.' sudah terpakai');
					redirect('BasicData/FrmTipeForm_C');
				}
			}

			$this->db->trans_begin();

			$this->db->query("UPDATE TipeForm SET TipeForm='$TipeForm', Alokasi='$Alokasi', Keterangan='$Keterangan' WHERE TipeForm='$TipeFormLama' ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmTipeForm_C');
			}else{
				$this->db->trans_commit();
				is_pesan('success','Updated!');
				redirect('BasicData/FrmTipeForm_C');
			}

			
		}

		
	}

}

/* End of file FrmTipeForm_C.php */
/* Location: ./application/controllers/BasicData/FrmTipeForm_C.php */