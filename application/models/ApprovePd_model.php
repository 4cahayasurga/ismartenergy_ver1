<?php
defined('BASEPATH') or exit('No direct script access allowed');


class ApprovePd_model extends CI_Model
{

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
      
      ApprovedByAK,
      TimeApprovedAK
      ');
		$this->datatables->from('PdHdr');
		if ($JobNo != '') {
			$this->datatables->where('JobNo', $JobNo);
			$this->datatables->where('Alokasi', $alokasi);
		}
		if ($cari != '') {
			$this->db->like('NoPD', $cari);
		}

		// $this->datatables->where('NoPj !=', '');
		// $this->datatables->add_column('view', '<a onclick="EditPemanfaatan($1)" data-toggle="tooltip" data-placement="top" title="Edit Data" class="btn btn-sm btn-warning waves-effect waves-light"><i class="fa fa-pencil" ></i> </a> <button onclick="hapus($1)" type="button" class="btn btn-sm btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Hapus Data"><i class="fa fa-trash"></i> </button>', 'NoPD');
		$this->datatables->add_column('view', '$1', 'NoPD');
		$this->db->order_by('TimeEntry', 'DESC');
		$this->datatables->edit_column('TotalPD', '$1', 'UbahGabunganUang(TotalPD)');
		// $this->datatables->edit_column('TotalPJ', '$1', 'UbahGabunganUang(TotalPJ)');
		// $this->datatables->edit_column('TglPj', '$1', 'tgl_indo(TglPj)');
		$this->datatables->edit_column('TimeApprovedAK', '$1', 'tgl_indo_waktu(TimeApprovedAK)');
		$this->datatables->edit_column('view', '$1', 'ApproveUnapprove(ApprovedByAK,NoPD,JobNo)');
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

	function getDataApprovePd($JobNo, $alokasi, $cari = '')
	{
		$this->db->select('
    JobNo,
    NoPD,
    TglPD,
    TipeForm,
    Deskripsi,
    TotalPD,
    
    ApprovedByAK,
    TimeApprovedAK
    ');
		$this->db->from('PdHdr');
		$this->db->where('JobNo', $JobNo);
		$this->db->where('Alokasi', $alokasi);
		if ($cari != '') {
			$this->db->like('NoPD', $cari);
		}
		$this->db->order_by('TimeEntry', 'DESC');
		return $this->db->get();
	}
}

/* End of file ApprovePd_model.php */
/* Location: ./application/models/ApprovePd_model.php */
