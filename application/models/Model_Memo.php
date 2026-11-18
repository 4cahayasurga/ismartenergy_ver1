<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Memo extends CI_Model {

	function get_data_for_Sent($NIK=''){
		$q = $this->db->query("
			SELECT Memo.*, Karyawan.Nama AS 'NmKaryawan' FROM Memo, Karyawan WHERE
			Memo.Pembuat='$NIK' AND Memo.Kepada=Karyawan.NIK ORDER BY Memo.TglMemo DESC, Memo.NoMemo DESC")->result();

		return $q;
	}	

	function get_list_Karyawan(){
		$q = $this->db->query("
			SELECT Karyawan.NIK, Karyawan.Nama, JobPosition.PositionName, Karyawan.Golongan 
			FROM Karyawan, JobPosition WHERE Active='1' AND 
			(LokasiKerja='Pusat' OR LokasiKerja='PT Minarta Dutahutama (Makasar)') AND 
			Karyawan.ID_JobPosition=JobPosition.ID_JobPosition ORDER BY Nama
			")->result();

		return $q;
	}

	function MembuatKodeMemo($NIK=NULL,$TglMemo=NULL){

		$KodeMemo = $this->DapatKodeMemo($NIK);

		if ($KodeMemo == 'job_belum_di_set') {
			return 'job_belum_di_set';
			exit;
		}

		$DrTgl = date('Y-m-01', strtotime($TglMemo));
		$SpTgl = (date('m',strtotime($TglMemo)) == '12') ? date('Y',strtotime('+1 year')).'-01-01' : date('Y-m',strtotime('+1 month')).'-01';

		// $DrTgl = '2022-03-01';
		// $SpTgl = '2022-04-01';
		$q = $this->db->query("SELECT LEFT(NoMemo,4) AS 'NoMemo' FROM Memo WHERE Divisi='$KodeMemo' AND TglMemo>='$DrTgl' AND TglMemo<='$SpTgl' ORDER BY NoMemo DESC");

		$bulan_saat_ini = date('m',strtotime($TglMemo));

		$NoMemo_Gabungan = NULL;

		if ($q->num_rows() > 0 ) {
			$row = $q->row();

			$NoMemo = sprintf('%04d', $row->NoMemo + 1);

			$NoMemo_Gabungan = $NoMemo.'/'.$KodeMemo.'/'.$this->getRomawi($bulan_saat_ini).'/'.date('Y');

		}else{
			$NoMemo = sprintf('%04d', + 1);

			$NoMemo_Gabungan = $NoMemo.'/'.$KodeMemo.'/'.$this->getRomawi($bulan_saat_ini).'/'.date('Y');
		}

		return $NoMemo_Gabungan;

	}

	function DapatKodeMemo($NIK=''){
		$q = $this->db->query("
			SELECT JobPosition.KodeMemo
			FROM Karyawan, JobPosition 
			WHERE NIK='$NIK' AND
			Karyawan.ID_JobPosition=JobPosition.ID_JobPosition");
		
		if ($q->num_rows() > 0) {
			$row = $q->row();
			return $row->KodeMemo;
		}else{
			// return NULL;
			return 'job_belum_di_set';
		}
	}

	private function getRomawi($bln){
		switch ($bln){
			case 1:

			return "I";

			break;

			case 2:

			return "II";

			break;

			case 3:

			return "III";

			break;

			case 4:

			return "IV";

			break;

			case 5:

			return "V";

			break;

			case 6:

			return "VI";

			break;

			case 7:

			return "VII";

			break;

			case 8:

			return "VIII";

			break;

			case 9:

			return "IX";

			break;

			case 10:

			return "X";

			break;

			case 11:

			return "XI";

			break;

			case 12:

			return "XII";

			break;

		}

	}


	function get_data_memo($LedgerNo){
		$q = $this->db->query("SELECT * FROM Memo WHERE LedgerNo='$LedgerNo' ")->row();
		return $q;
	}



	function dapat_data_MemoInbox($NIK){
		$q = $this->db->query("SELECT Memo.*, Karyawan.Nama AS 'NmKaryawan' FROM Memo, Karyawan WHERE (Memo.Kepada='$NIK' OR Memo.Tembusan LIKE '%$NIK%') AND Memo.Pembuat=Karyawan.NIK ORDER BY Memo.TglMemo DESC, Memo.NoMemo DESC ")->result();
		return $q;
	}




}

/* End of file Model_Memo.php */
/* Location: ./application/models/Model_Memo.php */