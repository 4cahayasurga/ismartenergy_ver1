
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_RekapPayment extends CI_Model
{

	public function GetTbl1()
	{
		return $this->db->query("SELECT * FROM RAP WHERE JobNo='21570' AND Alokasi='B'")->result();
		// LEFT JOIN Job C ON A.JobNo=C.JobNo WHERE A.JobNo='$JobNo' ORDER BY A.NoPD DESC")->result();
	}

	// function uraianPD($NoPD = null)
	// {
	// 	return $this->db->query("SELECT * FROM PdDtl WHERE NoPD ='$NoPD' ")->result();
	// }

	function DetailPenyerapan($JobNo = '', $KdRAP = '')
	{
		return $this->db->query("SELECT A.* FROM PdDtl A JOIN PdHdr B ON A.NoPD=B.NoPD WHERE B.JobNo='21570' AND B.Alokasi='B' AND B.RejectBy IS NULL AND A.KdRAP='$KdRAP'")->result();
	}

	// function PembayaranPD($NoPD =null){
	// 	return $this->db->query("SELECT BLE.* FROM PdHdr, BLE WHERE (PdHdr.NoPD=BLE.NoPD OR PdHdr.NoRef=BLE.NoPD) AND PdHdr.NoPD='$NoPD'")->result();
	// }
}
