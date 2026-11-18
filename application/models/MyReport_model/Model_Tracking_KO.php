<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_Tracking_KO extends CI_Model {

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	function dapat_data_TrackingKO($JobNo){

		$validasi_job = ($JobNo <> 'All_Job') ? "AND A.JobNo='$JobNo'" : "AND C.Company='$this->Company'";

		$query = "
		SELECT A.*, B.VendorNm FROM KoHdr A 
		JOIN Vendor B ON A.VendorId = B.VendorId
		JOIN Job C ON A.JobNo = C.JobNo
		WHERE A.KategoriId='PO' ".$validasi_job." ORDER BY NoKO ";

		return $this->db->query($query);

	// 	$query = "SELECT A.*, B.VendorNm FROM KoHdr A 
	// JOIN Vendor B ON A.VendorId = B.VendorId
	// JOIN Job C ON A.JobNo = C.JobNo
	// WHERE A.KategoriId='PO' AND A.JobNo='21640' AND C.Company='MDH' ORDER BY NoKO"

	}

}

/* End of file Model_Tracking_KO.php */
/* Location: ./application/models/MyReport_model/Model_Tracking_KO.php */