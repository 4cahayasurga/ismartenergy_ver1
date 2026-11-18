<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_dipa extends CI_Model {

	function aksi_simpan_dipa($data){
		$this->db->trans_begin();

		$this->db->insert('DIPA',$data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function aksi_hapus_dipa($where){
		$this->db->trans_begin();

		$this->db->where($where)->delete('DIPA');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function aksi_update_data($data,$where){
		$this->db->trans_begin();

		$this->db->set($data)->where($where)->update('DIPA');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

}

/* End of file M_dipa.php */
/* Location: ./application/models/Penerimaan_termin_model/M_dipa.php */