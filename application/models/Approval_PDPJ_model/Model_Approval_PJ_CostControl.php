<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Approval_PJ_CostControl extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Datatables');
	}

	function model_dapatkan_data_json_approval_pj_costcontrol($JobNo='',$Alokasi='',  $dari_tgl='', $sampai_tgl='', $dari_tgl_pj='', $sampai_tgl_pj='')
	{
		$this->load->library('Datatables');

		// $JobNo = $where['JobNo'];
		// $Alokasi = $where['Alokasi'];


		$this->datatables->select("
			LedgerNo,
			NoPD,
			NoRef,
			TglPD,
			Alokasi,
			TipeForm,
			NoKO,
			Deskripsi,
			TotalPD,
			ApprovedByKT,
			TimeApprovedKT,
			CASE
				WHEN ApprovedByKK IS NULL THEN 'belum_KK'
				ELSE 'sudah_KK'
			END AS 'ApprovedByKK', 
			TimeEntry,
			JobNo,
			NoPJ,
			TglPJ,
			TotalPJ,
			PjApprovedByAK,
			PjTimeApprovedAK,
			PjApprovedByKT,
			PjTimeApprovedKT,
			


			");
		$this->datatables->from('PdHdr');
		$this->datatables->where('JobNo',$JobNo);
		if ($Alokasi <> '') {
			$this->datatables->where('Alokasi',$Alokasi);
		}

		$this->datatables->where('PjApprovedByAK IS NOT NULL', NULL, FALSE);
		
		if ($dari_tgl !== '' && $sampai_tgl !== '') {
			$this->datatables->where('TglPD >=', $dari_tgl);
			$this->datatables->where('TglPD <=', $sampai_tgl);
		}

		if ($dari_tgl_pj !== '' && $sampai_tgl_pj !== '') {
			$this->datatables->where('TglPJ >=', $dari_tgl_pj);
			$this->datatables->where('TglPJ <=', $sampai_tgl_pj);
		}

		$atur_url ='';
		$atur_url .= site_url('PDPJ/PJ_lapangan_entry_controller/detail_pj_lapangan/');
		$atur_url .= $JobNo.'/'.$Alokasi;
		$atur_url .= ($dari_tgl == '') ? '/0' : '/'.$dari_tgl;
		$atur_url .= ($sampai_tgl == '') ? '/0' : '/'.$sampai_tgl;
		$atur_url .= ($dari_tgl_pj == '') ? '/0' : '/'.$dari_tgl_pj;
		$atur_url .= ($sampai_tgl_pj == '') ? '/0' : '/'.$sampai_tgl_pj;
		$atur_url .= '/0';

		// $this->datatables->where("(NoRef IS NOT NULL OR NoRef<>'')", NULL, FALSE);
		$this->datatables->where('RejectBy IS NULL', NULL, FALSE);

		$this->datatables->add_column('aksi_pj_js','<button type="button" class="btn btn-sm btn-success" onclick="detail_PJLapangan($1)" id="lihat_pj_lapangan_$1" data-NoPD="$2" data-NoPJ="$3"  title="LIHAT PJ LAPANGAN" data-url_back="'.$atur_url.'" ><i class="fa fa-eye"></i></button>','LedgerNo,NoPD,NoPJ');

		// $this->datatables->add_column('aksi_pj','','LedgerNo,NoPD');
		// $this->datatables->add_column('aksi_pj','<a class="btn btn-xs btn-default" href="'.site_url("PDPJ/PJ_lapangan_entry_controller/detail_pj_lapangan/$1/epj_lap/$2/$3").'"><i class="fa fa-eye"> Detail</i></a>','LedgerNo,NoPD,NoPJ');

		// $this->datatables->add_column('aksi_pj','<a class="btn btn-sm btn-success" href="'.site_url("PDPJ/PJ_lapangan_entry_controller/detail_pj_lapangan/$1/epj_lap/$2/$3").'"><i class="fa fa-eye"></i></a>','LedgerNo,NoPD,NoPJ');


		$this->datatables->edit_column('TglPD','$1','TanggalSaya(TglPD)');
		$this->datatables->edit_column('TotalPD', '$1', 'UbahGabunganUang(TotalPD)');

		$this->datatables->edit_column('TglPJ','$1','TanggalSaya(TglPJ)');
		$this->datatables->edit_column('TotalPJ', '$1', 'UbahGabunganUang(TotalPJ)');

		// $this->datatables->edit_column('PjTimeApprovedAK', '$1', 'TanggalSaya(PjTimeApprovedAK)');
		$this->datatables->edit_column('PjTimeApprovedKT', '$1', 'TanggalSaya(PjTimeApprovedKT)');

		return $this->datatables->generate();
	}

	// function model_dapatkan_data_json_approval_pj_costcontrol($JobNo,$Alokasi){

	// 	$this->datatables->select("
	// 		LedgerNo, 
	// 		NoPD, 
	// 		TglPD, 
	// 		TipeForm, 
	// 		Deskripsi, 
	// 		TotalPD, 
	// 		NoPJ, 
	// 		TglPJ, 
	// 		TotalPJ, 
	// 		PjApprovedByAK, 
	// 		PjTimeApprovedAK, 

	// 		PjApprovedByKT, 
	// 		PjTimeApprovedKT,

	// 		PjApprovedByKK, 
	// 		PjTimeApprovedKK, 

	// 		JobNo, 
	// 		Alokasi");
	// 	$this->datatables->from('PdHdr');
	// 	$this->datatables->where('JobNo',$JobNo);
	// 	$this->datatables->where('Alokasi',$Alokasi);
	// 	$this->datatables->where('PjApprovedByAK IS NOT NULL', NULL, FALSE);

	// 	$this->datatables->edit_column('TglPD','$1','tgl_baru_tahun_pendek(TglPD)');
	// 	$this->datatables->edit_column('TotalPD','$1','UbahGabunganUang(TotalPD)');

	// 	$this->datatables->edit_column('TglPJ','$1','tgl_baru_tahun_pendek(TglPJ)');
	// 	$this->datatables->edit_column('TotalPJ','$1','UbahGabunganUang(TotalPJ)');
	// 	$this->datatables->edit_column('PjTimeApprovedKT','$1','tgl_baru_tahun_pendek_dengan_waktu(PjTimeApprovedKT)');
	// 	// $this->datatables->edit_column('TglPJ','$1','tgl_baru_tahun_pendek(TglPJ)');
	// 	// $this->datatables->edit_column('TotalPD','$1','UbahGabunganUang(TotalPD)');
	// 	// $this->datatables->edit_column('PjTimeApprovedKT','$1','tgl_baru_tahun_pendek_dengan_waktu(PjTimeApprovedKT)');
	// 	// $this->datatables->edit_column('TotalPJ','$1','UbahGabunganUang(TotalPJ)');

	// 	$this->datatables->add_column('aksi_pj','<a class="btn btn-xs btn-default" href="'.site_url("PDPJ/PJ_lapangan_entry_controller/detail_pj_lapangan/$1/epj_lap/$2").'" target="_blank"><i class="fa fa-eye"> Detail</i></a>','LedgerNo,NoPD');

	// 	return $this->datatables->generate();
	// }

	function aksi_update_approval_pj_costcontrol($flag_status_approve,$NoPD){

		$this->db->trans_begin();

		$PjApprovedByKT = $this->session->userdata("MIS_LOGGED_NAME");
		$PjTimeApprovedKT = date('Y-m-d H:i:s');

		if ($flag_status_approve == 'APPROVE') {

			$this->db->query("UPDATE PdHdr SET PjApprovedByKT='$PjApprovedByKT', PjTimeApprovedKT='$PjTimeApprovedKT' WHERE NoPD='$NoPD'  ");
		}

		if ($flag_status_approve == 'UNAPPROVE') {

			$this->db->query("UPDATE PdHdr SET PjApprovedByKT=NULL, PjTimeApprovedKT=NULL WHERE NoPD='$NoPD'  ");
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

/* End of file Model_Approval_PJ_CostControl.php */
/* Location: ./application/models/Approval_PDPJ_model/Model_Approval_PJ_CostControl.php */