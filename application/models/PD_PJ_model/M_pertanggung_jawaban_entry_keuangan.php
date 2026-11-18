<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pertanggung_jawaban_entry_keuangan extends CI_Model {

	


	public function __construct()
	{
		parent::__construct();
		$this->load->library('Datatables');
	}


	function data_json_pj_keuangan($where){

		$this->datatables->select("LedgerNo, NoPD, TglPD, TipeForm, Deskripsi, TotalPD, NoPJ, TglPJ, TotalPJ, PjApprovedByAK, PjTimeApprovedAK, JobNo, Alokasi ");
		$this->datatables->from('PdHdr');
		$this->datatables->where($where);

		$this->db->group_start();
		$this->db->where('NoRef IS NULL', NULL, FALSE);
		$this->db->or_where('NoRef', '');
		$this->db->group_end();

		$this->datatables->where('RejectBy IS NULL', NULL, FALSE);

		$this->datatables->edit_column('TglPD','$1','tgl_baru_tahun_pendek(TglPD)');
		$this->datatables->edit_column('TglPJ','$1','tgl_baru_tahun_pendek(TglPJ)');
		$this->datatables->edit_column('TotalPD','$1','UbahGabunganUang(TotalPD)');
		$this->datatables->edit_column('PjTimeApprovedAK','$1','tgl_baru_tahun_pendek_dengan_waktu(PjTimeApprovedAK)');
		$this->datatables->edit_column('TotalPJ','$1','UbahGabunganUang(TotalPJ)');

		$this->datatables->add_column('aksi_pj','<a class="btn btn-xs btn-default" href="'.site_url("PDPJ/PJ_keuangan_entry_controller/detail_pj_keuangan/$1/epj_keu/$2").'"><i class="fa fa-eye"> Detail</i></a>','LedgerNo,NoPD');

		return $this->datatables->generate();
	}

	function Dapat_TotalPJ($JobNo,$Alokasi){
		$query = $this->db->query("SELECT SUM(TotalPJ) as TotalPJ FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND NoRef IS  NULL AND NoRef='' AND RejectBy IS NULL")->row_array();

		return $query['TotalPJ'];
	}

	function cek_pembayaran_pdpj_keuangan($NoPD){

		$query = $this->db->query("SELECT *,(SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE
			FROM PdHdr 
			WHERE NoPD='$NoPD'");

		return $query['CountBLE'];
	}

	function simpan_pj_entry_mymodel($data,$where){
		$this->db->trans_begin();

		$update_PdHdr = $this->db->set($data)->where('LedgerNo',$where['LedgerNo'])->update('PdHdr');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function cek_ada_PD_atau_tidak($LedgerNo,$NoPD){
		$query = $this->db->query("SELECT NoPD FROM PdHdr WHERE LedgerNo='$LedgerNo' OR NoPD='$NoPD' ")->num_rows();
		return ($query == 0) ? 0 : 1;
	}

	function cek_pembayaran_BLE($LedgerNo,$NoPD){
		$query = $this->db->query("SELECT NoPD FROM BLE WHERE NoPD='$NoPD'")->num_rows();
		return ($query == 0) ? 0 : 1;
	}

	function aksi_update_pj($flag_status_approve,$NoPD){

		$this->db->trans_begin();

		$PjApprovedByAK = $this->session->userdata("MIS_LOGGED_NAME");
		$PjTimeApprovedAK = date('Y-m-d H:i:s');

		if ($flag_status_approve == 'APPROVE') {

			$this->db->query("UPDATE PdHdr SET PjApprovedByAK='$PjApprovedByAK', PjTimeApprovedAK='$PjTimeApprovedAK' WHERE NoPD='$NoPD'  ");
		}

		if ($flag_status_approve == 'UNAPPROVE') {

			$this->db->query("UPDATE PdHdr SET PjApprovedByAK=NULL, PjTimeApprovedAK=NULL WHERE NoPD='$NoPD'  ");
		}


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}

	}

	function get_data_pj_keuangan($LedgerNo,$NoPD){

		$query = $this->db->query("SELECT *,
			(SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE,
			(SELECT JobNm FROM Job WHERE JobNo=PdHdr.JobNo) as JobNm,
			(SELECT Lokasi FROM Job WHERE JobNo=PdHdr.JobNo ) as Lokasi
			FROM PdHdr 
			WHERE NoPD='$NoPD' AND LedgerNo='$LedgerNo' ");
		return $query;
	}

	function dapat_data_detail_PdDtl($LedgerNo,$NoPD,$NoPJ,$status_detail_PdDtl){

		if ($status_detail_PdDtl == 'PdDtl_cadangan') {

			$cek_data = $this->db->where('NoPD',$NoPD)->get('PdDtl_cadangan')->num_rows();

			if ($cek_data == 0) {
				$this->db->query("
					DELETE FROM PdDtl_cadangan WHERE NoPD='$NoPD'
					INSERT INTO PdDtl_cadangan (NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjHrgSatuan, PjUserEntry, PjTimeEntry)
					SELECT NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjHrgSatuan, PjUserEntry, PjTimeEntry FROM PdDtl WHERE NoPD='$NoPD'");

				$query = $this->db->select('*,(Vol * HrgSatuan) as JumlahHarga, (PjVol * PjHrgSatuan) as PjJumlahHarga ')->where('NoPD',$NoPD)->get('PdDtl_cadangan');
				return $query;
			}else{
				$query = $this->db->select('*,(Vol * HrgSatuan) as JumlahHarga, (PjVol * PjHrgSatuan) as PjJumlahHarga')->where('NoPD',$NoPD)->get('PdDtl_cadangan');
				return $query;
			}

			
		}else{
			$query = $this->db->select('*,(Vol * HrgSatuan) as JumlahHarga, (PjVol * PjHrgSatuan) as PjJumlahHarga')->where('NoPD',$NoPD)->get('PdDtl');
			return $query;
		}
	}

	private function pindah_data_PdDtl_ke_PdDtl_cadangan($NoPD){
		$this->db->query("
			DELETE FROM PdDtl_cadangan WHERE NoPD='$NoPD'
			INSERT INTO PdDtl_cadangan (NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjHrgSatuan, PjUserEntry, PjTimeEntry)
			SELECT NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjHrgSatuan, PjUserEntry, PjTimeEntry FROM PdDtl WHERE NoPD='$NoPD'
			");
	}

	private function pindah_data_PdDtl_ke_PdDtl_cadangan_old($NoPD){

		$this->db->query("
			DELETE FROM PdDtl_cadangan WHERE NoPD='$NoPD'
			INSERT INTO PdDtl_cadangan (NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjHrgSatuan, PjUserEntry, PjTimeEntry)
			SELECT NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjHrgSatuan, PjUserEntry, PjTimeEntry FROM PdDtl WHERE NoPD='$NoPD'
			");

		$cek = $this->db->query("SELECT * FROM PdDtl_cadangan WHERE NoPD='$NoPD' ")->num_rows();

		
	}

	function dapat_PdDtl($NoUrut,$NoPD,$JobNo,$Alokasi,$status_detail_PdDtl){

		if ($status_detail_PdDtl == 'PdDtl_cadangan') {
			return $this->db->query(" SELECT a.*, (SELECT Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP=a.KdRAP ) as UraianRAP FROM PdDtl_cadangan a WHERE a.NoUrut='$NoUrut' AND a.NoPD='$NoPD'")->row_array();
		}else{
			return $this->db->query(" SELECT a.*, (SELECT Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP=a.KdRAP ) as UraianRAP FROM PdDtl a WHERE a.NoUrut='$NoUrut' AND a.NoPD='$NoPD'")->row_array();
		}
	}


	function simpan_uraian_pj($data,$where,$status_detail_PdDtl){
		$this->db->trans_begin();

		$this->db->set($data)->where($where)->update($status_detail_PdDtl);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
		
	}

	function update_pdhdr_pembuatan_NoPJ($data_set,$NoPD,$LedgerNo){

		$this->db->trans_begin();

		$aksi_update_pdhdr_pembuatan_NoPJ = $this->db->set($data_set)->where('LedgerNo',$LedgerNo)->or_where('NoPD',$NoPD)->update('PdHdr');

		$aksi_query_hapus_PdDtl_nya_terlebih_dahulu = $this->db->query("DELETE FROM PdDtl WHERE NoPD='$NoPD'");

		$aksi_query_pindah_data_dari_PdDtl_cadangan_ke_PdDtl = $this->db->query("INSERT INTO PdDtl 
		(NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjUraian, PjVol, PjHrgSatuan, PjUserEntry, PjTimeEntry)
		SELECT NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjUraian, PjVol, PjHrgSatuan, PjUserEntry, PjTimeEntry FROM PdDtl_cadangan WHERE NoPD='$NoPD'");
		
		$aksi_query_hapus_data_di_PdDtl_cadangan = $this->db->query("DELETE FROM PdDtl_cadangan WHERE NoPD='$NoPD' ");

		

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}

	}


  // ------------------------------------------------------------------------

}

/* End of file M_pertanggung_jawaban_entry_keuangan.php */
/* Location: ./application/models/PD_PJ_model/M_pertanggung_jawaban_entry_keuangan.php */