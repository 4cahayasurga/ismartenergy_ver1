<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_penerimaan_termin extends CI_Model {

	function dapat_list_termin_induk($JobNo,$LedgerNo=''){
		// $array['JobNo'] = $JobNo;
		// $array['Jenis'] = $JobNo;

		// if ($LedgerNo !='') {
		// 	$array['LedgerNo'] = $LedgerNo;
		// }

		$query_LedgerNo = '';
		if ($LedgerNo !='') {
			$query_LedgerNo = "AND LedgerNo='$LedgerNo'";
		}

		// $query = $this->db->query("SELECT * FROM TerminInduk WHERE JobNo='$JobNo' AND Jenis <> 'UM' ".$query_LedgerNo." ORDER BY LedgerNo DESC ");

		$query = $this->db->query("SELECT * FROM TerminInduk WHERE JobNo='$JobNo' ".$query_LedgerNo." ORDER BY LedgerNo DESC ");


		return $query;

		

		// return $this->db
		// ->where($array)
		// ->order_by('LedgerNo','DESC')
		// ->get('TerminInduk');
	}

	function simpan_termin_induk_to_database($data){
		$this->db->trans_begin();

		$this->db->insert('TerminInduk',$data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function update_termin_induk_to_database($data,$LedgerNo){
		$this->db->trans_begin();

		$this->db->set($data)->where('LedgerNo',$LedgerNo)->update('TerminInduk');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}


	//termin member
	function termin_member_list_data($JobNo,$LedgerNo=''){
		$query_LedgerNo = '';
		if ($LedgerNo !='') {
			$query_LedgerNo = "AND LedgerNo='$LedgerNo'";
		}

		// $query = $this->db->query("SELECT * FROM TerminMember WHERE JobNo='$JobNo' AND Jenis <> 'UM' ".$query_LedgerNo." ORDER BY LedgerNo DESC ");
		$query = $this->db->query("SELECT * FROM TerminMember WHERE JobNo='$JobNo' ".$query_LedgerNo." ORDER BY LedgerNo DESC ");
		// $query = $this->db->query("SELECT * FROM TerminMember WHERE Jenis <> 'UM' ".$query_LedgerNo." ORDER BY JobNo DESC ");

		return $query;
	}

	function dapat_member_dari_job($JobNo){
		$query = $this->db->query("SELECT JobNm,JobNm, Member1, Member2 FROM Job WHERE JobNo='$JobNo' ")->row_array();
		return $query;
	}

	function simpan_termin_member($data){
		$this->db->trans_begin();

		$this->db->insert('TerminMember',$data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function update_termin_member($data,$where){
		$this->db->trans_begin();

		$this->db->set($data)->where($where)->update('TerminMember');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}


	//akhir termin member

}

/* End of file Model_penerimaan_termin.php */
/* Location: ./application/models/Penerimaan_termin_model/Model_penerimaan_termin.php */