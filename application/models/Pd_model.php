<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pd_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getPD($JobNo, $NoPD)
	{
		$this->db->select('b.JobNm,b.Lokasi,a.*');
		$this->db->join('Job b', 'a.JobNo = b.JobNo');
		$this->db->where(array(
			'a.JobNo' => $JobNo,
			'a.NoPD' => $NoPD

		));
		return $this->db->get('PdHdr a');
	}



	function getPdDetail($NoPD = '')
	{
		$this->db->where('NoPD', $NoPD);
		return $this->db->get('PdDtl');
	}
	function do_edit_detail($where, $data)
	{
		$this->db->trans_start();
		$this->db->set($data)->where($where)->update('PdDtl');
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function do_hapus($where)
	{
		$this->db->trans_start();
		$this->db->where($where)->delete('PdDtl');
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function EditPD($where, $data)
	{
		$this->db->trans_start();
		$this->db->set($data)->where($where)->update('PdHdr');
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function getDataPd($JobNo, $alokasi, $cari = '')
	{
		$this->db->select('
    	b.NoPD,
		b.NoRef,
		b.TglPD,
		b.Alokasi,
		b.TipeForm,
		b.NoKO,
		b.Deskripsi,
		b.TotalPD,
		b.ApprovedByAK,
		b.TimeApprovedAK
    ');
		$this->db->from('PdHdr b');
		$this->db->where('b.JobNo', $JobNo);
		$this->db->where('b.Alokasi', $alokasi);
		if ($cari != '') {
			$this->db->like('NoPD', $cari);
			$this->db->or_like('NoKO', $cari);
		}
		$this->db->order_by('b.TimeEntry', 'DESC');
		return $this->db->get();
	}
}

/* End of file Pd_model.php */
/* Location: ./application/models/Pd_model.php */
