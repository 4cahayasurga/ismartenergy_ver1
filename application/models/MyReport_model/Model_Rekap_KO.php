<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Rekap_KO extends CI_Model {

	function dapat_data_ko($JobNo,$dari_tgl,$sampai_tgl,$semua_periode){

		$query_periode_tgl = "";

		

		if ($semua_periode <> '1'  ) {
			$query_periode_tgl = " AND a.TglKO >= '$dari_tgl' AND  a.TglKO <= '$sampai_tgl' ";
		}else{

			$dari_tgl = '1900-1-1';
			$sampai_tgl = '2999-1-1';
		}

		

		$query_ko = $this->db->query("
			SELECT a.NoKO, a.TglKO, a.JobNo, 
			a.VendorId, a.KategoriId, a.SubTotal, 
			a.TglKO, 
			a.ClosedBy,
			(SELECT VendorNm FROM Vendor WHERE VendorId=a.VendorId ) as 'VendorNm' 
			FROM KoHdr a 
			WHERE a.JobNo='$JobNo' AND 
			ApprovedBy IS NOT NULL AND 
			CanceledBy IS NULL 
			".$query_periode_tgl."
			ORDER BY a.NoKO
			");

		return $query_ko;

	}

	function dapat_data_ko_untuk_print($JobNo,$dari_tgl,$sampai_tgl,$semua_periode){

		$query_periode_tgl = "";

		

		if ($semua_periode <> '1'  ) {
			$query_periode_tgl = " AND a.TglKO >= '$dari_tgl' AND  a.TglKO <= '$sampai_tgl' ";
		}else{

			$dari_tgl = '1900-1-1';
			$sampai_tgl = '2999-1-1';
		}

		

		$query_ko = $this->db->query("
			SELECT a.NoKO, a.TglKO, a.JobNo, 
			a.VendorId, a.KategoriId, a.SubTotal, 
			a.TglKO, 
			(SELECT VendorNm FROM Vendor WHERE VendorId=a.VendorId ) as 'VendorNm' 
			FROM KoHdr a 
			WHERE a.JobNo='$JobNo' AND 
			ApprovedBy IS NOT NULL AND 
			CanceledBy IS NULL 
			".$query_periode_tgl."
			ORDER BY a.NoKO
			")->result_array();

		return $query_ko;

	}

	function dapat_detail_KO_bayar(){
		$query = $this->db->query("SELECT * FROM BLE WHERE NoKO='KO21570025' AND TglBayar>='2022-01-01' AND TglBayar<='2022-01-31' ORDER BY NoKO ");
	}



   //      $query_ko = $this->db->query("

   //      	SELECT A.*, B.VendorNm 
			// FROM KoHdr A JOIN Vendor B ON A.VendorId = B.VendorId 
			// WHERE A.JobNo='$JobNo' AND 
			// TglKO>='$dari_tgl' AND 
			// TglKO<='$sampai_tgl' AND 
			// ApprovedBy IS NOT NULL AND 
			// CanceledBy IS NULL ORDER BY NoKO

   //      ");



}

/* End of file Model_Rekap_KO.php */
/* Location: ./application/models/MyReport_model/Model_Rekap_KO.php */