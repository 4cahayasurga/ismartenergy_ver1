<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmApprovalPD_KK extends CI_Controller {

	var $Company;
	var $PD_Status;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		PembuatanInvoiceSementara(); 
		tbl_NewInvoice();
		PembuatanTable_PdDtl_Temp_Keuangan();
		PembuatanTable_Invoice_Temp_Keuangan();

		// == START ADD PD_PPN,23 Nov 2023.
		PenambahanField_PD_PPN();
		// PenambahanField_InputDari();
		// == END ADD PD_PPN,23 Nov 2023.

		if ($this->session->userdata('PecahToken')->ApprovalPD_KK!=1) {
			redirect('Welcome');
		}

	}

	function index($JobNo='',$Alokasi=''){
		$NamaController = "";
		if ($this->session->userdata('SesiJob_KK')) {
			$JobNo = explode('|', $this->session->userdata('SesiJob_KK'))[0];
			$Alokasi = explode('|', $this->session->userdata('SesiJob_KK'))[1];
			$NamaController = explode('|', $this->session->userdata('SesiJob_KK'))[2];
			$this->session->unset_userdata('SesiJob_KK');
		}

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['NamaController'] = $NamaController;

		
		$data['title'] = 'Approval Permintaan Dana (Keuangan)';
		$data['judul'] = 'Approval Permintaan Dana (Keuangan)';
		$data['atur_kiri'] = '1';

		$data['ListJob'] = $this->ListJob();
		$data['ListAlokasi'] = $this->ListAlokasi(); 
		$data['bodyclass'] = 'sidebar-collapse skin-blue';

		$data['js_saya'] = 'entry_view/PDPJ_View/FrmApprovalPD_KK_VIEW/js_ApprovalPD_KK';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/FrmApprovalPD_KK_VIEW/main_ApprovalPD_KK_VIEW', $data);
		$this->load->view('templates/footer');
	}

	function tampil_table(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		// $TxtQuery = "SELECT * FROM PdHdr WHERE JobNo='".$JobNo."' AND Alokasi='".$Alokasi."' AND ApprovedByAK IS NOT NULL ORDER BY NoPD DESC"; 
		$TxtQuery = "SELECT * FROM PdHdr WHERE JobNo='".$JobNo."' AND Alokasi='".$Alokasi."' AND (ApprovedByKT IS NOT NULL OR (Alokasi<>'B' AND Alokasi<>'C' AND ApprovedByAK IS NOT NULL)) ORDER BY NoPD DESC"; 
		$query = $this->db->query($TxtQuery);

		$Ttl = 0;
		foreach ($query->result() as $row) {
			$TotalPD = ($row->TotalPD=='' || $row->TotalPD==NULL) ? 0 : (float) $row->TotalPD;
			$Ttl += $TotalPD;
		}

		$data['Ttl'] = number_format($Ttl);
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['ListPD'] = $query->result();

		$this->session->set_userdata(array('SesiJob_KK' => $JobNo.'|'.$Alokasi.'|FrmApprovalPD_KK'));

		$this->load->view('entry_view/PDPJ_View/FrmApprovalPD_KK_VIEW/tampil_table', $data);
	}

	function CekStatusApprovePD(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD = $this->input->post('NoPD');
		$query = $this->db->query("SELECT * FROM PdHdr WHERE NoPD='$NoPD' ");

		if ($query->num_rows() > 0) {

			$row = $query->row_array();
			if ($row['RejectBy']!='' || $row['RejectBy']!=NULL) {
				echo $NoPD.' sudah di-reject oleh '.$row['RejectBy'];
				die;
			}

			// if ($row['ApprovedByKK']!='' || $row['ApprovedByKK']!=NULL) {
			// 	echo $NoPD.' sudah di-approved oleh Direksi Keuangan. ';
			// 	die;
			// }

		}else{
			echo 'data tidak ditemukan';
		}

		$query_BLE = $this->db->query("SELECT * FROM BLE WHERE NoPD='$NoPD' ");
		if ($query_BLE->num_rows() > 0) {
			echo $NoPD.' Sudah ada pembayaran';
			die;
		}
		
		echo 'lanjut';

	}

	function lakukanApprove(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD = $this->input->post('NoPD');
		$TxtApprove = $this->input->post('TxtApprove');
		// echo $TxtApprove;
		$this->db->trans_begin();

		try {
			$set = array(
				'ApprovedByKK' => ($TxtApprove=='APPROVE') ? $this->session->userdata('MIS_LOGGED_NAME') : NULL,
				'TimeApprovedKK' => ($TxtApprove=='APPROVE') ? date('Y-m-d H:i:s') : NULL,
			);

			$where = array('NoPD' => $NoPD);
			$this->db->set($set)->where($where)->update('PdHdr');
			
			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}

	}

	private function ListJob(){

		$AksesJob = $this->session->userdata('PecahToken')->AksesJob;
		$QueryJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$this->Company' ");
		$ArrayJob = array();
		foreach($QueryJob->result_array() as $myjob ):

			if (!preg_match('/\b' . $myjob['JobNo'] . '\b/', $AksesJob)) {
				continue;
			}
			$ArrayJob[] = (object) array('JobNo' => $myjob['JobNo'], 'JobNm' => $myjob['JobNm']);
		endforeach;

		return $ArrayJob;
	}

	private function ListAlokasi(){
		$AksesAlokasi = $this->session->userdata('PecahToken')->AksesAlokasi;
		$queryAlokasi = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi ");
		$ArrayAlokasi = array();
		foreach($queryAlokasi->result_array() as $row ):

			if (!preg_match('/\b' . $row['Alokasi'] . '\b/', $AksesAlokasi)) {
				continue;
			}

			$ArrayAlokasi[] = (object) array('Alokasi' => $row['Alokasi'], 'Keterangan' => $row['Keterangan']);
		endforeach;

		return $ArrayAlokasi;
	}

}

/* End of file FrmApprovalPD_KK.php */
/* Location: ./application/controllers/Entry/PDPJ/FrmApprovalPD_KK.php */