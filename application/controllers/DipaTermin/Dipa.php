<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dipa extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();

		$this->load->model('Penerimaan_termin_model/M_dipa');
	}

	function aksi_tambah_dipa_terbaru(){
		if (!$this->input->post()) {
			echo json_encode(array('status' => 'gagal'));
			die;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Tahun      = $this->input->post('Tahun',TRUE);
		$Budget     = str_replace(',', '', $this->input->post('Dipa',TRUE));
		$PaguBruto  = str_replace(',', '', $this->input->post('PaguBudget',TRUE));
		$UserEntry  = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  = date("Y-m-d H:i:s");

		// echo $PaguBruto;

		// $cek_total
		echo json_encode(array('status' => $PaguBruto));
		die;

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$where = array(
			'JobNo' => $JobNo,
			'Tahun' => $Tahun
		);
		$cek_tahun = $this->db->query("SELECT * FROM Dipa WHERE JobNo='$JobNo' AND Tahun='$Tahun' ");
		if ($cek_tahun->num_rows() > 0) {
			echo json_encode(array('status' => 'ada_tahun','Tahun' => $Tahun,'JobNo' => $JobNo));
			die;

		}

		$data = array(
			'JobNo'     => $JobNo,
			'Tahun'     => $Tahun,
			'Budget'    => $Budget,
			'PaguBudget' => $PaguBruto,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
		);

		$this->db->trans_start();

		$this->db->insert('Dipa',$data);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			echo json_encode(array('status' => 'gagal','Tahun' => $Tahun,'JobNo' => $JobNo));
			die;
		}else{
			echo json_encode(array('status' => 'berhasil','Tahun' => $Tahun,'JobNo' => $JobNo));
			die;
		}


	}


	function aksi_tambah_dipa(){

		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$Tahun 	= $this->input->post('Tahun',TRUE);

		$cek_tahun = $this->db->query("SELECT * FROM Dipa WHERE JobNo='$JobNo' AND Tahun='$Tahun' ");
		if ($cek_tahun->num_rows() > 0) {
			is_pesan('warning','Tahun '.$Tahun.' sudah terpakai ');
			redirect('Job/dipa/'.$JobNo,'refresh');
			die;
		}


		$Budget     = str_replace(',', '', $this->input->post('Dipa',TRUE));
		$PaguBruto  = str_replace(',', '', $this->input->post('PaguBudget',TRUE));
		$UserEntry  = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  = date("Y-m-d H:i:s");

		$PaguBruto_dipa = $this->input->post('PaguBruto_dipa',TRUE);
		$TotalPaguBudget_DIPA = $this->input->post('TotalPaguBudget_DIPA',TRUE);

		$hitung = 0;
		$hitung = $PaguBruto + $TotalPaguBudget_DIPA;



		if ($hitung > $PaguBruto_dipa) {
			is_pesan('info','DIPA pagu bruto lebih besar dari pagu bruto kontrak');
			redirect('Job/dipa/'.$JobNo,'refresh');
			die;
		}


		$data = array(
			'JobNo'     => $JobNo,
			'Tahun'     => $Tahun,
			'Budget'    => $Budget,
			'PaguBudget' => $PaguBruto,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
		);

		$simpan = $this->M_dipa->aksi_simpan_dipa($data);
		if ($simpan == TRUE) {
			is_pesan('success','Berhasil menambahkan data');
			redirect('Job/dipa/'.$JobNo,'refresh');
			echo 'berhasil';
			exit;
		}
		echo 'gagal';
		is_pesan('error','Gagal saat menambahkan data');
		redirect('Job/dipa/'.$JobNo,'refresh');
		exit;
	}

	function aksi_hapus_dipa($JobNo='',$id_Dipa=''){


		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}


		if ($JobNo == ''  || $id_Dipa=='') {
			redirect('welcome/index','refresh');
			exit;
		}

		$where = array(
			'JobNo' => $JobNo,
			'id_Dipa' => $id_Dipa
		);

		$hapus_dipa = $this->M_dipa->aksi_hapus_dipa($where);
		if ($hapus_dipa == TRUE) {
			is_pesan('info','Berhasil menghapus data');
			redirect('Job/dipa/'.$JobNo,'refresh');
			exit;
		}
		is_pesan('error','Gagal saat menghapus data');
		redirect('Job/dipa/'.$JobNo,'refresh');
		exit;
	}

	function aksi_ubah_dipa(){

		if(!$this->input->post()){
			is_pesan('error','Percobaan Berbahaya, Anda Terdeteksi');
			redirect('welcome','refresh');
			exit;
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}


		$id_Dipa      = $this->input->post('id_Dipa',TRUE);
		$Tahun      = $this->input->post('Tahun',TRUE);
		$TahunLama      = $this->input->post('TahunLama',TRUE);

		if ($Tahun != $TahunLama) {

			$cek_tahun = $this->db->query("SELECT * FROM Dipa WHERE JobNo='$JobNo' AND Tahun='$Tahun' ");
			if ($cek_tahun->num_rows() > 0) {
				is_pesan('warning','Tahun '.$Tahun.' sudah terpakai ');
				redirect('Job/dipa/'.$JobNo,'refresh');
				die;
			}
		}

		$Budget     = str_replace(',', '', $this->input->post('Dipa',TRUE));
		$PaguBruto  = str_replace(',', '', $this->input->post('PaguBudget',TRUE));
		$UserEntry  = $this->session->userdata('MIS_LOGGED_NAME'); 	
		$TimeEntry  = date("Y-m-d H:i:s");

		$PaguBruto_dipa = $this->input->post('PaguBruto_dipa',TRUE);
		$TotalPaguBudget_DIPA = $this->input->post('TotalPaguBudget_DIPA',TRUE);

		$hitung = 0;
		$hitung = $PaguBruto + $TotalPaguBudget_DIPA;


		if ($hitung > $PaguBruto_dipa) {
			is_pesan('info','DIPA pagu bruto lebih besar dari pagu bruto kontrak');
			redirect('Job/dipa/'.$JobNo,'refresh');
			die;
		}



		$data = array(
			'Tahun'     => $Tahun,
			'Budget'    => $Budget,
			'PaguBudget' => $PaguBruto,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
		);

		$where = array(
			'JobNo' => $JobNo,
			'id_Dipa' => $id_Dipa
		);

		// print_rr($where);
		// echo "<hr>";
		// print_rr($data);
		// exit;

		$simpan = $this->M_dipa->aksi_update_data($data,$where);
		if ($simpan == TRUE) {
			is_pesan('success','Berhasil mengubah data');
			redirect('Job/dipa/'.$JobNo,'refresh');
			exit;
		}
		is_pesan('error','Gagal saat mengubah data');
		redirect('Job/dipa/'.$JobNo,'refresh');
		exit;


		
	}

}

/* End of file Dipa.php */
/* Location: ./application/controllers/DipaTermin/Dipa.php */