<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PembatalanKO_C extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	public function index($JobNo=''){

		$data['title'] = 'PEMBATALAN KO';
		$data['judul'] = 'PEMBATALAN KO';
		$data['bodyclass'] = 'skin-blue';
		$data['ListJob'] = ListJob();

		$data['JobNo'] = $JobNo;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/procurement/PembatalanKO_V/main_PembatalanKO', $data);
		$this->load->view('templates/footer');
	}

	function table_PembatalanKO(){
		$JobNo = $this->input->post('JobNo',TRUE);
		$NoKO = ($this->input->post('NoKO',TRUE)) ? $this->input->post('NoKO',TRUE) : '';

		$ValidasiNoKO = "";

		if ($NoKO !='') :
			$ValidasiNoKO = " AND A.NoKO LIKE '%".$NoKO."%' ";
		endif;

		$data['ListTable'] = $this->db->query("
			SELECT A.*, 
			B.VendorNm, 
			(SELECT ISNULL(SUM(Amount),0) AS 'Amount' FROM BLE WHERE NoKO=A.NoKO) AS PaymentAmount,
			(SELECT TOP 1 COUNT(NoKO) FROM BLE WHERE NoKO=A.NoKO) AS 'Pembayaran'
			FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId 
			WHERE JobNo='$JobNo' ".$ValidasiNoKO."  
			ORDER BY NoKO DESC")->result();

		// print_rr($data);

		$data['JobNo'] = $JobNo;
		$data['NoKO'] = $NoKO;
		$this->load->view('entry_view/procurement/PembatalanKO_V/table_PembatalanKO', $data);

	}



	function Pengecekan(){

		if (!$this->input->post('JobNo')) {
			is_pesan('error','Terjadi Kesalahan');
			redirect('Entry/Procurement/PembatalanKO_C/');
			exit;
		}

		if (!$this->input->post('NoKO')) {
			is_pesan('error','Terjadi Kesalahan');
			redirect('Entry/Procurement/PembatalanKO_C/index/'.$this->input->post('JobNo'));
			exit;
		}

		$NoKO = ($this->input->post('NoKO')) ? $this->input->post('NoKO') : '';
		$JobNo = ($this->input->post('JobNo')) ? $this->input->post('JobNo') : '';
		$CancelReason = ($this->input->post('CancelReason')) ? $this->input->post('CancelReason') : '';

		$query = $this->db->select('CanceledBy,ClosedBy')->where('NoKO',$NoKO)->get('KoHdr');
		$row = $query->row_array();

		if ($query->num_rows() == 0) {
			is_pesan('error','data tidak ditemukan');
			redirect('Entry/Procurement/PembatalanKO_C/index/'.$JobNo);
			die;
		}

		if ($query->num_rows() > 0) {

			
			if ($row['CanceledBy'] !='' || $row['CanceledBy'] !=NULL ) {
				is_pesan('info',$NoKO.' sudah di-batalkan.');
				redirect('Entry/Procurement/PembatalanKO_C/index/'.$JobNo);
				die;
			}

			if ($row['ClosedBy'] !='' || $row['ClosedBy'] !=NULL ) {
				is_pesan('info',$NoKO.' sudah di-tutup.');
				redirect('Entry/Procurement/PembatalanKO_C/index/'.$JobNo);
				die;
			}

			// == Cek Pembayaran, Jika sudah ada tidak dapat di cancel ==
			$queryPembayaran = $this->db->query("SELECT TOP 1 NoKO FROM BLE WHERE NoKO='$NoKO' ")->num_rows();
			if ($queryPembayaran > 0) {
				is_pesan('info','Sudah ada pembayaran untuk '.$NoKO);
				redirect('Entry/Procurement/PembatalanKO_C/index/'.$JobNo);
				die;
			}

		}

		$this->db->trans_begin();

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		$this->db->query("UPDATE KoHdr SET CanceledBy='$UserEntry',TimeCancel='$TimeEntry',CancelReason='$CancelReason' WHERE NoKO='$NoKO' ");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			is_pesan('error',$NoKO.' gagal eksekusi data, silahkan ulangi');
			redirect('Entry/Procurement/PembatalanKO_C/index/'.$JobNo);
			die;
		}else{
			$this->db->trans_commit();
			is_pesan('success', 'Canceled Success');
			redirect('Entry/Procurement/PembatalanKO_C/index/'.$JobNo);
			die;
		}
		
	}
	

}

/* End of file ApprovalPDKeuangan_C.php */
/* Location: ./application/controllers/Entry/PDPJ/ApprovalPDKeuangan_C.php */