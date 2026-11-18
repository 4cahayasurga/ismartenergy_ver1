<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ApprovalPDCostControl_C extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		redirect('Entry/PDPJ/FrmApprovalPD_KT');
		$this->Company = $this->config->item('Company');
	}

	public function index($JobNo='',$Alokasi=''){

		$PrdAwal = '';
		$PrdAkhir = '';

		if ($this->input->post('JobNo') ) {
			$JobNo = $this->input->post('JobNo',TRUE);
			$Alokasi = $this->input->post('Alokasi',TRUE);

			$PrdAwal = $this->input->post('PrdAwal',TRUE);
			$PrdAkhir = $this->input->post('PrdAkhir',TRUE);
		}


		$data['title'] = 'Approval Permintaan Dana (Cost Control)';
		$data['judul'] = 'Approval Permintaan Dana (Cost Control)';
		// $data['atur_kiri'] = '1';
		// $data['bodyclass'] = 'skin-blue-light';

		$data['ListJob'] = ListJob();
		$data['ListAlokasi'] = ListAlokasi(); 

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;

		$data['PrdAwal'] = $PrdAwal;
		$data['PrdAkhir'] = $PrdAkhir;

		$TxtPeriode = "";

		if ($PrdAwal!='' && $PrdAkhir!='') {
			$TxtPeriode = " AND TglPD >='".$PrdAwal."' AND TglPD <='".$PrdAkhir."'  ";
		}
		
		$data['SumTotalPD'] = 0;

		if ($JobNo !='' && $Alokasi!='' ) {
			$data['ListTable'] = $this->db->query("SELECT * FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND ApprovedByAK IS NOT NULL ".$TxtPeriode." ORDER BY NoPD DESC ")->result();
			$data['SumTotalPD'] = $this->db->query("SELECT COALESCE(SUM(TotalPD),0) AS 'SumTotalPD' FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND ApprovedByAK IS NOT NULL ".$TxtPeriode."")->row()->SumTotalPD;
		}


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/ApprovalCostControl_V/main', $data);
		$this->load->view('templates/footer');
	}
	
	function ProsesApprove($NoPD='',$JobNo='',$Alokasi='',$TxtApprove=''){
		if ($NoPD =='' ) {
			is_pesan('error','Terjadi Kesalahan');
			redirect('Entry/PDPJ/ApprovalPDCostControl_C');
		}

		if ($TxtApprove == 'UNAPPROVE') :

			$Cek = $this->db->query("SELECT ISNULL(NULL,ApprovedByKK) AS ApprovedByKK, ISNULL(NULL,RejectBy) AS 'RejectBy' , JobNo, Alokasi FROM PdHdr WHERE NoPD='$NoPD'")->row();
			if ($Cek->RejectBy != NULL || $Cek->RejectBy !='') {
				is_pesan('warning', $NoPD.' sudah di-reject oleh '.$Cek->RejectBy);
				redirect('Entry/PDPJ/ApprovalPDCostControl_C/index/'.$JobNo.'/'.$Alokasi);

			}

			if ($Cek->ApprovedByKK != NULL || $Cek->ApprovedByKK !='') {
				is_pesan_saya('warning', $NoPD.' sudah di-approved oleh Direksi Keuangan.');
				redirect('Entry/PDPJ/ApprovalPDCostControl_C/index/'.$JobNo.'/'.$Alokasi);
			}

			// Cek Pembayaran
			$PembayaranCek = $this->db->query("SELECT * FROM BLE WHERE NoPD='$NoPD' ")->num_rows();
			if ($PembayaranCek > 0) {
				is_pesan('warning', $NoPD.' sudah ada pembayaran.');
				redirect('Entry/PDPJ/ApprovalPDCostControl_C/index/'.$JobNo.'/'.$Alokasi);
			}

		endif;

		$this->db->trans_begin();

		$ApprovedByKT = ($TxtApprove == 'APPROVE') ? $this->session->userdata('MIS_LOGGED_NAME') : NULL;
		$TimeApprovedKT = ($TxtApprove == 'APPROVE') ? date('Y-m-d H:i:s') : NULL;

		$this->db->query("UPDATE PdHdr SET ApprovedByKT='$ApprovedByKT',TimeApprovedKT='$TimeApprovedKT' WHERE NoPD='$NoPD'  ");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			is_pesan('error','Failed!');
			redirect('Entry/PDPJ/ApprovalPDCostControl_C/index/'.$JobNo.'/'.$Alokasi);
		}else{
			$this->db->trans_commit();
			is_pesan('success','Saved!');
			redirect('Entry/PDPJ/ApprovalPDCostControl_C/index/'.$JobNo.'/'.$Alokasi);
		}

	}

}

/* End of file ApprovalPDCostControl_C.php */
/* Location: ./application/controllers/Entry/PDPJ/ApprovalPDCostControl_C.php */