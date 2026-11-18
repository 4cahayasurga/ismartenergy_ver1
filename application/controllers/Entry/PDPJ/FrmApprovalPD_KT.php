<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmApprovalPD_KT extends CI_Controller {

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

		if ($this->session->userdata('PecahToken')->ApprovalPD_KT!=1) {
			redirect('Welcome');
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

	function index($JobNo='',$Alokasi=''){
		$NamaController = "";
		if ($this->session->userdata('SesiJob_KT')) {
			$JobNo = explode('|', $this->session->userdata('SesiJob_KT'))[0];
			$Alokasi = explode('|', $this->session->userdata('SesiJob_KT'))[1];
			$NamaController = explode('|', $this->session->userdata('SesiJob_KT'))[2];
			$this->session->unset_userdata('SesiJob_KT');
		}

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['NamaController'] = $NamaController;

		
		$data['title'] = 'Approval Permintaan Dana (Cost Control)';
		$data['judul'] = 'Approval Permintaan Dana (Cost Control)';
		$data['atur_kiri'] = '1';

		// print_rr($this->session->userdata('SesiJob_KT'));
		// die;
		$data['ListJob'] = $this->ListJob();
		$data['ListAlokasi'] = $this->ListAlokasi(); 
		$data['bodyclass'] = 'sidebar-collapse skin-blue';

		

		$data['js_saya'] = 'entry_view/PDPJ_View/FrmApprovalPD_KT_VIEW/js_ApprovalPD_KT';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/FrmApprovalPD_KT_VIEW/main_ApprovalPD_KT_VIEW', $data);
		$this->load->view('templates/footer');
	}

	function tampil_table(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		$TxtQuery = "SELECT * FROM PdHdr WHERE JobNo='".$JobNo."' AND Alokasi='".$Alokasi."' AND ApprovedByAK IS NOT NULL ORDER BY NoPD DESC"; 
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

		$this->session->set_userdata(array('SesiJob_KT' => $JobNo.'|'.$Alokasi.'|FrmApprovalPD_KT'));

		$this->load->view('entry_view/PDPJ_View/FrmApprovalPD_KT_VIEW/tampil_table', $data);
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

			if ($row['ApprovedByKK']!='' || $row['ApprovedByKK']!=NULL) {
				echo $NoPD.' sudah di-approved oleh Direksi Keuangan. ';
				die;
			}

		}else{
			echo 'data tidak ditemukan';
		}

		$query_BLE = $this->db->query("SELECT * FROM BLE WHERE NoPD='$NoPD' ");
		if ($query_BLE->num_rows() > 0) {
			echo 'Sudah ada pembayaran untuk '.$NoPD;
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
				'ApprovedByKT' => ($TxtApprove=='APPROVE') ? $this->session->userdata('MIS_LOGGED_NAME') : NULL,
				'TimeApprovedKT' => ($TxtApprove=='APPROVE') ? date('Y-m-d H:i:s') : NULL,
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

}

/* End of file FrmApprovalPD_KT.php */
/* Location: ./application/controllers/Entry/PDPJ/FrmApprovalPD_KT.php */