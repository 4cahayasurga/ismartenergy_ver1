<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_pro_query_invoice extends CI_Model {

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}


	function get_data_query_invoice($JobNo, $VendorId, $PrdAwal='', $PrdAkhir=''){

		$Company_where = "WHERE Company='$this->Company'";

		$cek_validasi ="";
		$cek_tanggal = "";


		$cek_validasi = $Company_where;

		if ($JobNo !== 'All_Job') {
			$cek_validasi = $cek_validasi." AND JobNo='".$JobNo."'  ";
		}

		if ($VendorId !== 'All_Vendor') {
			$cek_validasi = $cek_validasi." AND VendorId='".$VendorId."'  ";
		}

		if ($PrdAwal !=='' AND $PrdAkhir !=='' ) {
			$cek_tanggal = " AND BLE.TglBayar BETWEEN '".$PrdAwal."' AND  '".$PrdAkhir."'  ";
			
		}

		
		$myquery = $this->db->query("

		WITH TmpQuery AS (
		SELECT Invoice.*, Invoice.JobNo + ' - ' + Job.JobNm AS 'JobNo1', Job.Company,  
		Vendor.VendorId,  
		ISNULL((SELECT SUM(PaymentAmount) FROM BLE, InvPD, PdHdr WHERE  
		(BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND PdHdr.NoKO=Invoice.NoKO AND  
		InvPD.NoPD=PdHdr.NoPD AND InvPD.InvNo=Invoice.InvNo ".$cek_tanggal."  ),0) AS PaymentAmount
		FROM Invoice  
		LEFT JOIN Job ON Job.JobNo = Invoice.JobNo  
		LEFT JOIN KoHdr ON KoHdr.NoKO=Invoice.NoKO  
		LEFT JOIN Vendor ON Vendor.VendorId=KoHdr.VendorId)  
		SELECT * FROM TmpQuery ".$cek_validasi."  ORDER BY NoKO DESC
		");

		return $myquery;
	
	}

	

}

/* End of file Model_pro_query_invoice.php */
/* Location: ./application/models/MyReport_model/Model_pro_query_invoice.php */