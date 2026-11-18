<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approved_spr_model extends CI_Model {

	private $table;


	public function __construct()
	{
		parent::__construct();
		$this->table="PRHdr";
		$this->load->library('Datatables');
	}

	public function get_json_spr($JobNo = '')
	{

		$this->datatables->select('
			JobNo,
			NoSPR,
			TglSPR,
			Kepada,
			UtkPekerjaan,
			ApprovedBy,
			TimeApproved,
			TimeEntry
			
			');
		$this->datatables->from('PRHdr');
		if ($JobNo != '') {
			$this->datatables->where('JobNo', $JobNo);
		}
		$this->db->order_by('TimeEntry', 'DESC');


		$this->datatables->add_column('view', '
			<button type="button" onclick="getDetailSingkat(\'$1\',\'$2\')"  class="badge badge-xs btn-danger">SELECT</button>', 'JobNo,NoSPR');

		$this->datatables->add_column('approved', '$1', 'ApproveAndUnapprovedSPR(JobNo,NoSPR,ApprovedBy)');
		$this->datatables->edit_column('TimeApproved', '$1', 'tgl_indo_waktu(TimeApproved)');


		
		return $this->datatables->generate();
	}	

	function getDetailSpr($where){
		return $this->db->get_where($this->table,$where);
	}

	function getdtl($where_dtl){
		return $this->db->order_by('NoUrut', 'DESC')->get_where('PRDtl', $where_dtl);
	}

	function UbahStatus($data,$where){
		$this->db->trans_begin();
		
		$this->db->set($data)->where($where)->update($this->table);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

}

/* End of file Approved_spr_model.php */
/* Location: ./application/models/Approved_spr_model.php */
