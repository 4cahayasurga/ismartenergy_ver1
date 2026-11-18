<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approved_pd_lap_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Datatables');
	}
	public function get_json_pd($JobNo = '', $alokasi = '', $cari = '')
	{

		$this->datatables->select('
			JobNo,
			NoPD,
			TglPD,
			TipeForm,
			Deskripsi,
			TotalPD,

			ApprovedByKT,
			TimeApprovedKT
			');
		$this->datatables->from('PdHdr');
		$this->datatables->where('ApprovedByAK IS NOT NULL',NULL,FALSE);
		if ($JobNo != '') {
			$this->datatables->where('JobNo', $JobNo);
			$this->datatables->where('Alokasi', $alokasi);
		}
		if ($cari != '') {
			$this->db->like('NoPD', $cari);
		}


		
		$this->datatables->add_column('view', '$1', 'NoPD');
		$this->db->order_by('LedgerNo', 'DESC');
		$this->datatables->edit_column('TotalPD', '$1', 'UbahGabunganUang(TotalPD)');
		$this->datatables->edit_column('TimeApprovedKT', '$1', 'tgl_indo_waktu(TimeApprovedKT)');
		$this->datatables->edit_column('view', '$1', 'ApproveUnapprove(ApprovedByKT,NoPD,JobNo)');
		$this->datatables->add_column('view_detail', '<a href="' . site_url('Pd/edit/$1/$2') . '" class="btn btn-default btn-xs " data-toggle="tooltip" data-placement="top" title="Detail PD" ><i class="fa fa-eye"> Detail</i></a>', 'JobNo,NoPD');

		return $this->datatables->generate();
	}

	function ApproveUnapprove($where, $data)
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

}

/* End of file Approved_pd_lap_model.php */
/* Location: ./application/models/Approved_pd_lap_model.php */