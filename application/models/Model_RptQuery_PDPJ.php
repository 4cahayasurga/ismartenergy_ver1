<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_RptQuery_PDPJ extends CI_Model {

	function validasi_JobNo(){
		$Company = $this->config->item('Company');
		$select_job_berdasarkan_company = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$Company'")->result();

		$AksesJob = AksesJob_HanyaJobNumber();
		$arrayJob = array();

		foreach($AksesJob as $JobAkses){
			$arrayJob[] = $JobAkses->JobNo;
		}

		$array_return = array();

		foreach($select_job_berdasarkan_company as $list_job){

			if (!in_array($list_job->JobNo, $arrayJob)) {
				continue;
			}

			$array_return[] = array(
				'JobNo' => $list_job->JobNo,
				'JobNm' => $list_job->JobNm,
			);
		}

		return $array_return;
	}

	function validasi_Alokasi(){

		$SelectAlokasi = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi")->result();

		$AksesAlokasi = AksesAlokasi_HanyaNamaAlokasi();
		$arrayAlokasi = array();

		foreach($AksesAlokasi as $JobAkses){
			$arrayAlokasi[] = $JobAkses->Alokasi;
		}

		$array_return = array();

		foreach($SelectAlokasi as $AlokasiList){

			if (!in_array($AlokasiList->Alokasi, $arrayAlokasi)) {
				continue;
			}

			$array_return[] = array(
				'Alokasi' => $AlokasiList->Alokasi,
				'Keterangan' => $AlokasiList->Keterangan,
			);
		}

		return $array_return;
	}


	function pembuatan_koma_implode_JobNo(){
		$comma_separated = "";
		$AksesJob = AksesJob_HanyaJobNumber();
		$arrayJob = array();

		foreach($AksesJob as $JobAkses){
			$arrayJob[] = $JobAkses->JobNo;
		}

		$comma_separated = implode("','", $arrayJob);
		$comma_separated = "'".$comma_separated."'";

		return $comma_separated;
	}

	function koma_Alokasi(){
		$comma_separated = "";
		$AksesJob = AksesAlokasi_HanyaNamaAlokasi();
		$arrayJob = array();

		foreach($AksesJob as $JobAkses){
			$arrayJob[] = $JobAkses->Alokasi;
		}

		$comma_separated = implode("','", $arrayJob);
		$comma_separated = "'".$comma_separated."'";

		return $comma_separated;
	}


	function dapatkan_data_QueryPDPJ($JobNo, $Alokasi){

		$Company = $this->config->item('Company');
		$QueryLanjutan = "";
		$QueryLanjutan = "WHERE C.Company='$Company' ";


		if ($JobNo == "All Job") {
			$JobList = $this->Model_RptQuery_PDPJ->pembuatan_koma_implode_JobNo();
			$QueryLanjutan = $QueryLanjutan."AND A.JobNo IN (".$JobList.")";
		}else{
			$QueryLanjutan = $QueryLanjutan." AND A.JobNo='$JobNo' ";
		}

		if ($Alokasi == "All Alokasi") {
			$listAlokasi = $this->Model_RptQuery_PDPJ->koma_Alokasi();
			$QueryLanjutan = $QueryLanjutan." AND A.Alokasi IN (".$listAlokasi.") ";
		}else{
			$QueryLanjutan = $QueryLanjutan." AND A.Alokasi='$Alokasi' ";
		}


		$myquery = $this->db->query("SELECT A.JobNo + ' - ' + C.JobNm AS 'JobNo1', A.*, B.SubTotal, B.DiscAmount, B.PPN, 
			(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
			(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
			(SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
			(SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount' 
			FROM PdHdr A 
			LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
			LEFT JOIN Job C ON A.JobNo=C.JobNo 
			".$QueryLanjutan."  ORDER BY A.NoPD DESC")->result();
		return $myquery;
		exit;


		// $myquery = $this->db->query("

		// 	DROP TABLE IF EXISTS #temp_tbl_query_pdpj
		// 	CREATE TABLE #temp_tbl_query_pdpj (

		// 	LedgerNo bigint NOT NULL,
		// 	JobNo nvarchar(10) NULL,
		// 	JobNo1 ntext NULL,
		// 	NoPD nvarchar(20) NULL,
		// 	TglPD date NULL,
		// 	NoRef nvarchar(50) NULL,
		// 	Alokasi nvarchar(1) NULL,
		// 	TipeForm nvarchar(10) NULL,
		// 	Deskripsi nvarchar(255) NULL,
		// 	NoKO nvarchar(15) NULL,
		// 	SubTotal money NULL,
		// 	DiscAmount money NULL,
		// 	PPN money NULL,
		// 	TotalKO money NULL,
		// 	NoTagihan nvarchar(100) NULL,
		// 	NoRek ntext NULL,
		// 	AtasNama nvarchar(100) NULL,
		// 	TotalPD money NULL,
		// 	TglBayar date NULL,

		// 	JenisTrf ntext NULL,
		// 	BLEAmount money NULL,
		// 	RekId ntext NULL,
		// 	NoPJ nvarchar(20) NULL,
		// 	TglPJ date NULL,
		// 	TotalPJ money NULL,
		// 	Saldo1 money NULL,

		// 	);

		// 	INSERT INTO #temp_tbl_query_pdpj (LedgerNo, JobNo, JobNo1, NoPD, TglPD, NoRef, Alokasi, TipeForm, Deskripsi, NoKO, SubTotal, DiscAmount, PPN, TotalKO, NoTagihan, NoRek, AtasNama, TotalPD, TglBayar
		// 	, JenisTrf, BLEAmount, RekId, NoPJ, TglPJ, TotalPJ, Saldo1)

		// 	SELECT  A.LedgerNo, A.JobNo, A.JobNo + ' - ' + C.JobNm AS 'JobNo1', 
		// 	A.NoPD, A.TglPD, A.NoRef, A.Alokasi, A.TipeForm, A.Deskripsi, A.NoKO, B.SubTotal, B.DiscAmount, B.PPN, 0, A.NoTagihan, A.NoRek, 
		// 	A.AtasNama, A.TotalPD, 
		// 	(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
		// 	(SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
		// 	(SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount',
		// 	(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId',
		// 	A.NoPJ, A.TglPJ, TotalPJ, 0
		// 	FROM PdHdr A 
		// 	LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
		// 	LEFT JOIN Job C ON A.JobNo=C.JobNo 
		// 	".$QueryLanjutan."  
		// 	");

		// $myquery = $this->db->query("SELECT * FROM #temp_tbl_query_pdpj ")->result();

		// foreach($myquery as $data){

		// }

		// return $this->db->query("SELECT * FROM #temp_tbl_query_pdpj  ORDER BY TglPD DESC")->result();

	}

}

/* End of file Model_RptQuery_PDPJ.php */
/* Location: ./application/models/Model_RptQuery_PDPJ.php */