<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Approval_PJ_Keuangan extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Datatables');
	}

	function model_dapatkan_data_json_approval_pj_keuangan($JobNo,$Alokasi){


		$this->datatables->select("
			LedgerNo, 
			NoPD, 
			TglPD, 
			TipeForm, 
			Deskripsi, 
			TotalPD, 
			NoPJ, 
			TglPJ, 
			TotalPJ, 
			PjApprovedByAK, 
			PjTimeApprovedAK, 

			PjApprovedByKT, 
			PjTimeApprovedKT,

			PjApprovedByKK, 
			PjTimeApprovedKK, 

			JobNo, 
			Alokasi");
		$this->datatables->from('PdHdr');
		$this->datatables->where('JobNo',$JobNo);
		$this->datatables->where('Alokasi',$Alokasi);
		$this->datatables->where("(PjApprovedByKT IS NOT NULL OR (Alokasi <> 'B' AND Alokasi <> 'C' AND PjApprovedByAK IS NOT NULL)) ", NULL, FALSE);


		$this->datatables->edit_column('TglPD','$1','tgl_baru_tahun_pendek(TglPD)');
		$this->datatables->edit_column('TotalPD','$1','UbahGabunganUang(TotalPD)');

		$this->datatables->edit_column('TglPJ','$1','tgl_baru_tahun_pendek(TglPJ)');
		$this->datatables->edit_column('TotalPJ','$1','UbahGabunganUang(TotalPJ)');
		$this->datatables->edit_column('PjTimeApprovedKK','$1','tgl_baru_tahun_pendek_dengan_waktu(PjTimeApprovedKK)');

		$this->datatables->add_column('aksi_pj','<a class="btn btn-xs btn-default" href="'.site_url("PDPJ/PJ_keuangan_entry_controller/detail_pj_keuangan/$1/epj_keu/$2").'" target="_blank"><i class="fa fa-eye"> Detail</i></a>','LedgerNo,NoPD');

		return $this->datatables->generate();
	}

	function aksi_update_approval_pj_keuangan($flag_status_approve,$NoPD){

		$this->db->trans_begin();

		$PjApprovedByKK = $this->session->userdata("MIS_LOGGED_NAME");
		$PjTimeApprovedKK = date('Y-m-d H:i:s');

		if ($flag_status_approve == 'APPROVE') {

			$this->db->query("UPDATE PdHdr SET PjApprovedByKK='$PjApprovedByKK', PjTimeApprovedKK='$PjTimeApprovedKK' WHERE NoPD='$NoPD'  ");
		}

		if ($flag_status_approve == 'UNAPPROVE') {

			$this->db->query("UPDATE PdHdr SET PjApprovedByKK=NULL, PjTimeApprovedKK=NULL WHERE NoPD='$NoPD'  ");
		}


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}

	}


}

/* End of file Model_Approval_PJ_Keuangan.php */
/* Location: ./application/models/Approval_PDPJ_model/Model_Approval_PJ_Keuangan.php */