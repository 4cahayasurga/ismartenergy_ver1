<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmRekening_C extends CI_Controller {

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	public function index()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'Daftar Rekening';

		$query = $this->db->query("SELECT RekId, Bank, NoRek, AtasNama, LedgerNo, PaymentPD FROM Rekening WHERE Company='$this->Company' OR Company='*'  ");
		$data['ListRekening'] = $query->result();
		$data['ListBank'] = $this->db->query("SELECT Bank FROM Bank")->result();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmRekening_V/main', $data);
		$this->load->view('templates/footer');
	}

	public function SimpanRekening(){
		if (!$this->input->post()) {
			is_pesan('error','terjadi kesalahan');
			redirect('BasicData/FrmRekening_C','refresh');
		}

		$RekId = strtoupper($this->input->post('RekId'));
		$Bank = strtoupper($this->input->post('Bank'));
		$NoRek = strtoupper($this->input->post('NoRek'));
		$AtasNama = strtoupper($this->input->post('AtasNama'));
		$typeAksi = $this->input->post('typeAksi');
		$RekIdLama = $this->input->post('RekIdLama');
		$PaymentPD = ($this->input->post('PaymentPD')) ? 1 : 0 ;
		$LedgerNo = ($this->input->post('LedgerNo')) ? $this->input->post('LedgerNo') : 0 ;

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		if ($typeAksi=='Simpan') {
			$cek = $this->db->query("SELECT * FROM Rekening WHERE RekId='$RekId'");
			if ($cek->num_rows() > 0 ) {
				is_pesan('error','Rekening ID : '.$RekId.' sudah terpakai');
				redirect('BasicData/FrmRekening_C');
			}

			$this->db->trans_begin();

			$this->db->query("INSERT INTO Rekening (RekId,Bank,NoRek,AtasNama,UserEntry,TimeEntry,Company,PaymentPD) VALUES ('$RekId', '$Bank', '$NoRek', '$AtasNama', '$UserEntry', '$TimeEntry', '$this->Company', '$PaymentPD') ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmRekening_C');
			}else{
				$this->db->trans_commit();
				is_pesan('success','Saved!');
				redirect('BasicData/FrmRekening_C');
			}
			
		}

		if ($typeAksi=='Update') {

			if ($RekId !=$RekIdLama) {
				$cek = $this->db->query("SELECT * FROM Rekening WHERE RekId='$RekId'");
				if ($cek->num_rows() > 0 ) {
					is_pesan('error','Rekening ID  : '.$RekId.' sudah terpakai');
					redirect('BasicData/FrmRekening_C');
				}
			}

			$this->db->trans_begin();

			$this->db->query("UPDATE Rekening SET Bank='$Bank', NoRek='$NoRek', AtasNama='$AtasNama', UserEntry='$UserEntry', TimeEntry='$TimeEntry', RekId='$RekId', PaymentPD='$PaymentPD' WHERE LedgerNo='$LedgerNo' ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				is_pesan('error','gagal, silahkan ulangi proses');
				redirect('BasicData/FrmRekening_C');
			}else{
				$this->db->trans_commit();
				is_pesan('success','Updated!');
				redirect('BasicData/FrmRekening_C');
			}

			
		}

		
	}

}

/* End of file FrmRekening.php */
/* Location: ./application/controllers/BasicData/FrmRekening.php */