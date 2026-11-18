<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_rppm extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('Datatables');
  }

  public function tblProgress($JobNo = '')
  {
		if ($JobNo != '') {
			return $this->db->query("SELECT * FROM Progress WHERE JobNo=$JobNo ORDER BY Tahun DESC, Bulan DESC")->result();
			// return $this->db->query("SELECT TOP 1 * FROM Progress WHERE JobNo='$JobNo' ORDER BY TimeEntry DESC ");
		}
  }

  public function get_json_progress_fisik($JobNo = '')
  {

    $this->datatables->select('
		  LedgerNo,
			JobNo,
			Tahun,
			Bulan,
      RencanaK,
      RealisasiK,
      RealisasiKeuK,
      RencanaTB,
      RealisasiTB,
      RealisasiKeuTB,
      UserEntry,
			TimeEntry
			
			');
    $this->datatables->from('Progress');
    if ($JobNo != '') {
      $this->datatables->where('JobNo', $JobNo);
    }
		    $this->datatables->add_column('view', '<a onclick="EditRppm($1)" data-toggle="tooltip" data-placement="top" class="btn btn-xs btn-warning waves-effect waves-light"><i class="fa fa-pencil" title="Edit Data"></i> Edit</a> <button onclick="hapus($1)" type="button" class="btn btn-xs btn-danger waves-effect waves-light"><i class="fa fa-trash"></i> Hapus</button>', 'LedgerNo');
    $this->db->order_by('Tahun', 'DESC');
	$this->db->order_by('Bulan', 'DESC');
    return $this->datatables->generate();
  }
  public function TambahProgressFisik($data)
  {
    $this->db->trans_start();
    $this->db->insert('Progress', $data);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return FALSE;
    } else {
      $this->db->trans_commit();
      return TRUE;
    }
  }

  function GetDataProgressFisik($JobNo)
  {
    if ($JobNo != '') {
			return $this->db->query("SELECT * FROM Progress WHERE JobNo='$JobNo' ORDER BY Tahun DESC, Bulan DESC");
      // return $this->db->query("SELECT TOP 1 * FROM Progress WHERE JobNo='$JobNo' ORDER BY TimeEntry DESC ");
    }
  }

	function GetDataEmon($LedgerNo='')
	{
		if ($LedgerNo !=''){
			// return $this->db->query("SELECT * FROM Progress WHERE JobNo='21570' ORDER BY Tahun DESC, Bulan DESC");
			return $this->db->query("SELECT * FROM Progress WHERE LedgerNo='$LedgerNo' ORDER BY TimeEntry DESC ");
		}
	}

	function EditProgress($LedgerNo, $data)
	{
		$this->db->trans_start();
		$this->db->set($data);
		$this->db->where('LedgerNo', $LedgerNo);
		$this->db->update('Progress');
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}		
	}

	function Hapus($LedgerNo)
	{
		$this->db->trans_start();
		$this->db->where('LedgerNo', $LedgerNo)->delete('Progress');
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

/* End of file M_rppm_model.php */
/* Location: ./application/models/M_rppm_model.php */
