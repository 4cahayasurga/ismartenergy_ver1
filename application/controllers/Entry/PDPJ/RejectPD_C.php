<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class RejectPD_C extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	public function index($JobNo='',$Alokasi='')
	{
		$data['title'] = 'Reject Permintaan Dana';
		$data['judul'] = 'Reject Permintaan Dana';
		$data['bodyclass'] = 'skin-blue-light';
		$data['ListJob'] = ListJob();
		$data['ListAlokasi'] = ListAlokasi(); 

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('RejectPD_V/main_RejectPD', $data);
		$this->load->view('templates/footer');
	}

	function table_RejectPD(){
		if (!$this->input->post()) {
			echo 'Error';
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$query = $this->db->query("SELECT * FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'  AND (ApprovedByAK IS NOT NULL OR ApprovedByKT IS NOT NULL OR ApprovedByKK IS NOT NULL) ORDER BY NoPD DESC")->result();
		$SubTotal = 0;
		foreach($query as $row):
			$SubTotal += (float) $row->TotalPD;
		endforeach;
		// print_rr($query);
		$data['SubTotal'] = $SubTotal;
		$data['dataReject'] = $query;
		$this->load->view('RejectPD_V/table_RejectPD', $data);
	}

	function aksi_reject(){
		if (!$this->input->post()) {
			is_pesan('error','error');
			redirect('Entry/PDPJ/RejectPD_C');
			die;
		}

		$NoPD = $this->input->post('NoPD',TRUE);
		$AlasanReject = $this->input->post('AlasanReject',TRUE);

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);


		// == START CEK BLE ==
		$queryBLE = $this->db->query("SELECT LedgerNo FROM BLE WHERE NoPD='$NoPD'");
		if ($queryBLE->num_rows()>0) {
			is_pesan('error','Sudah ada pembayaran untuk '.$NoPD);
			redirect('Entry/PDPJ/RejectPD_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		}
		// == END CEK BLE ==

		// == START CEK REJECT ==
		$queryReject = $this->db->query("SELECT RejectBy FROM PdHdr WHERE NoPD='$NoPD'");
		if ($queryReject->num_rows()>0) {
			$row = $queryReject->row();
			if ($row->RejectBy !=NULL || $row->RejectBy!='') :
				is_pesan('error',$NoPD.' sudah di-reject oleh '.$row->RejectBy);
				redirect('Entry/PDPJ/RejectPD_C/index/'.$JobNo.'/'.$Alokasi);
				die;
			endif;
		}
		// == END CEK REJECT ==

		// == START EKSEKUSI == 

		$RejectBy = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeReject = date('Y-m-d H:i:s');

		$this->db->trans_begin();

		$this->db->query("DELETE FROM InvPD WHERE NoPD='$NoPD'");
		$this->db->query("UPDATE PdHdr SET RejectBy='$RejectBy',TimeReject='$TimeReject',AlasanReject='$AlasanReject' WHERE NoPD='$NoPD'");
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			is_pesan('error',$NoPD. ' gagal di-reject');
			redirect('Entry/PDPJ/RejectPD_C/index/'.$JobNo.'/'.$Alokasi);
		}
		else
		{
			$this->db->trans_commit();
			is_pesan('success',$NoPD. ' berhasil di-reject');
			redirect('Entry/PDPJ/RejectPD_C/index/'.$JobNo.'/'.$Alokasi);
		}
		// == END EKSEKUSI ==
		
	}

}

/* End of file RejectPD.php */
/* Location: ./application/controllers/Entry/PDPJ/RejectPD.php */