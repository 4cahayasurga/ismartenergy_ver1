<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_FrmPayment extends CI_Model {

	function get_list_data_payment_belum_dibayar(){

		$Company = $this->config->item('Company');

		$query = $this->db->query("
			WITH TmpStr AS (
			SELECT A.LedgerNo, A.JobNo, B.JobNm, A.NoPD, A.TglPD, A.Alokasi, A.TipeForm, A.NoKO, A.Deskripsi, A.TotalPD, A.ApprovedByKK, A.TimeApprovedKK,  
			(SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=A.NoPD OR NoPD=A.NoRef)) AS CountBLE  
			FROM PdHdr A, Job B  
			WHERE A.JobNo=B.JobNo AND A.ApprovedByKK IS NOT NULL AND A.RejectBy IS NULL AND B.Company='$Company')  
			SELECT * FROM TmpStr WHERE TmpStr.CountBLE = 0 ORDER BY TimeApprovedKK DESC, TotalPD DESC" )->result();
		
		return $query;
	}

	function get_dataPD_old($NoPD=''){
		$query = $this->db->query("SELECT a.* FROM PdHdr a WHERE NoPD='$NoPD' ");
		return $query->row();
	}

	function get_dataPD($NoPD='',$LedgerNo=''){
		$query = $this->db->query("SELECT a.*, 
			(SELECT JobNm FROM Job WHERE JobNo=a.JobNo) AS 'JobName',
			(SELECT Keterangan FROM Alokasi WHERE Alokasi=a.Alokasi ) AS 'AlokasiKeterangan',
			(SELECT Keterangan FROM TipeForm WHERE TipeForm=a.TipeForm ) AS 'TipeFormKeterangan'
			FROM PdHdr a 
			WHERE NoPD='$NoPD' AND LedgerNo='$LedgerNo' ");
		return $query->row();
	}


	function ListJob(){
		$Company = $this->config->item('Company');

		$query = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$Company' ")->result();
		return $query;
	}

	function ListAlokasi(){
		$query = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi")->result();
		return $query;
	}

	function ListTipeForm(){
		$query = $this->db->query("SELECT TipeForm,Keterangan FROM TipeForm")->result();
		return $query;
	}

	function getListRekening(){
		$Company = $this->config->item('Company');
		$query = $this->db->query("SELECT * FROM Rekening WHERE Company='$Company' OR Company='*'")->result();
		return $query;
	}

	function getListBank(){
		$query = $this->db->query(" SELECT Bank FROM Bank ")->result();
		return $query;
	}


	function dapatkan_DataPD($NoPD=''){
		$myquery = $this->db->query("SELECT A.*, B.JobNm,
			(SELECT Keterangan FROM Alokasi WHERE Alokasi=A.Alokasi ) AS 'AlokasiKeterangan',
			(SELECT Keterangan FROM TipeForm WHERE TipeForm=A.TipeForm ) AS 'TipeFormKeterangan'


			FROM PdHdr A JOIN Job B ON A.JobNo=B.JobNo WHERE NoPD='$NoPD'");
		return $myquery->row();

	}

	function dapatkan_ListDataSudahBayar($DariTgl='',$SampaiTgl=''){
		$Company = $this->config->item('Company');
		$query = $this->db->query("SELECT A.*,  B.JobNm FROM BLE A JOIN Job B ON A.JobNo=B.JobNo WHERE TglBayar>='$DariTgl' AND TglBayar<='$SampaiTgl' AND B.Company='$Company' ORDER BY A.TimeEntry DESC")->result();
		return $query;
	}

	function dapatkan_ListDataSudahBayar_For_ExportExcel($IdPayment_array,$DariTgl='',$SampaiTgl=''){
		$Company = $this->config->item('Company');
		$query = $this->db->query("SELECT A.*,  B.JobNm FROM BLE A JOIN Job B ON A.JobNo=B.JobNo WHERE B.Company='$Company' AND A.LedgerNo IN (".$IdPayment_array.") ORDER BY A.TimeEntry DESC")->result();
		return $query;
	}

}

/* End of file Model_FrmPayment.php */
/* Location: ./application/models/Model_FrmPayment.php */