<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_FrmCuti extends CI_Model {

	function TemukanAtasan($NIK){
		$myquery = $this->db->query("SELECT ID_JobPosition FROM Karyawan WHERE NIK='$NIK'  ");
		if ($myquery->num_rows() < 1) {
			return NULL;
		}

		$row = $myquery->row();
		$ID_JobPosition = $row->ID_JobPosition;

		$cari = $this->cari_atasan($ID_JobPosition);
		return $cari;

	}

	function getKaryawanPengganti(){
		$q = $this->db->query("Select Nama From Karyawan Order By NIK DESC")->result();
		return $q;
	}

	private function cari_atasan($ID_JobPosition){
		// $q = $this->db->query("
		// 	SELECT A.ParentID_JobPosition, B.NIK FROM JobPosition A
		// 	LEFT JOIN Karyawan B ON A.ParentID_JobPosition=B.ID_JobPosition
		// 	WHERE A.ID_JobPosition='$ID_JobPosition' ");

		//Golongan di atas 3 adalah Manager dan sampai ke atas, ini berguna untuk di taruh di field kepada utk validasi Head Action
		$q = $this->db->query("SELECT A.ParentID_JobPosition, B.NIK FROM JobPosition A
			LEFT JOIN Karyawan B ON A.ParentID_JobPosition=B.ID_JobPosition
			WHERE A.ID_JobPosition='$ID_JobPosition' AND B.Active='1' AND B.Golongan >='3' ");

		$row = $q->row();

		if ($q->num_rows() > 0) {
			return $row->NIK;
		}else{
			$this->cari_atasan($row->ParentID_JobPosition);
		}
	}

	function simpan_cuti($table,$data){
		$this->db->trans_begin();

		$this->db->insert($table,$data);

		//make transaction complete
		$this->db->trans_complete();

		//check if transaction status TRUE or FALSE
		if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
			$this->db->trans_rollback();
			return FALSE;
		} else {
            //if everything went right, commit the data to the database
			$this->db->trans_commit();
			return TRUE;
		}
	}	

	function q_table_cuti($NIK=''){
		// return $this->db->query("SELECT * FROM RequestADO WHERE NIK='$NIK' ORDER BY DrTgl DESC")->result();
		return $this->db->query("SELECT * FROM RequestADO WHERE NIK='$NIK' ORDER BY DrTgl DESC")->result();
	}

	function dapat_data_cuti($LedgerNo){
		$q = $this->db->query("SELECT *, CONVERT(VARCHAR(5),JamMasuk) As 'Jam1', CONVERT(VARCHAR(5),JamKeluar) AS 'Jam2' FROM RequestADO WHERE LedgerNo='$LedgerNo' ")->row();
		return $q;
	}

	function update_cuti($table,$set,$where){
		$this->db->trans_begin();

		$this->db->set($set)->where($where)->update($table);

		//make transaction complete
		$this->db->trans_complete();

		//check if transaction status TRUE or FALSE
		if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
			$this->db->trans_rollback();
			return FALSE;
		} else {
            //if everything went right, commit the data to the database
			$this->db->trans_commit();
			return TRUE;
		}
	}


	function cek_status_cuti($LedgerNo,$table){
		$q = $this->db->where('LedgerNo',$LedgerNo)->get($table)->row();
		return $q;
	}


	function hapus_cuti($table,$where){
		$this->db->trans_begin();

		$this->db->where($where)->delete($table);

		//make transaction complete
		$this->db->trans_complete();

		//check if transaction status TRUE or FALSE
		if ($this->db->trans_status() === FALSE) {
            //if something went wrong, rollback everything
			$this->db->trans_rollback();
			return FALSE;
		} else {
            //if everything went right, commit the data to the database
			$this->db->trans_commit();
			return TRUE;
		}
	}


	function cek_sisa_cuti($NIK=''){

		$cuti = 12;

		$hari_ini = date("Y-m-d");
		$TglMasuk = date('Y-m-01', strtotime($hari_ini));
		$TglKeluar = date('Y-m-t', strtotime($hari_ini));
		$q = $this->db->query("
			SELECT ISNULL(SUM(CASE WHEN Status='Cuti' THEN 1 ELSE 0.5 END),0) AS 'TtlCuti'
			FROM Absensi WHERE NIK='$NIK' AND TglMasuk>='$TglMasuk' AND TglMasuk<='$TglKeluar' AND
			(Status='Cuti' OR Status='Cuti 1/2 Hari')");
		$row = $q->row();

		if ($q->num_rows() > 0) {
			return $cuti - uang_koma_genap_decimal($row->TtlCuti);
		}
		// 	return NULL;
		// }
	}

	function grid_cuti_karyawan($NIK){
		$q = $this->db->query("SELECT * FROM RequestADO WHERE NIK='$NIK' ORDER BY DrTgl DESC")->result();
		return $q;
	}



	// function get_approve_cuti($NIK){
	// 	return $this->db->query("SELECT 
 //            A.*, (SELECT Nama FROM Karyawan WHERE NIK=A.NIK ) AS 'NamaRequest',
 //            (SELECT DiBaca FROM MyNotif WHERE LedgerNo=A.LedgerNo ) AS 'DiBaca' 
 //            FROM RequestADO A 
 //            WHERE A.Kepada='$NIK' AND 
 //            (A.SDMAction IS NULL OR A.SDMAction='Pending' )  
 //            AND (A.HeadAction IS NULL OR A.HeadAction='Pending') 
 //            ORDER BY A.DrTgl DESC   ")->result();
	// }

}

/* End of file Model_FrmCuti.php */
/* Location: ./application/models/Model_FrmCuti.php */