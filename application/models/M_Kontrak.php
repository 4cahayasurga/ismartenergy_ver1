<?php 

class M_Kontrak extends CI_Model{

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	public function get_ko($JobNo=null)
	{

		return $this->db->query("SELECT A.*, B.VendorNm FROM KoHdr A inner join Vendor B On A.VendorId = B.VendorId Where A.JobNo='$JobNo'")->result();
	}

	public function tblAddmKO($JobNo)
	{
		return $this->db->query("SELECT A.*, B.VendorNm FROM KoHdr A inner join Vendor B On A.VendorId = B.VendorId Where A.JobNo='$JobNo' AND ClosedBy IS NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ORDER BY NoKO")->result();
		// return $query->result();
	}

	public function approveKO()
	{
		return $this->db->query("SELECT A.*, B.*, C.JobNm FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId 
			JOIN Job C ON A.JobNo=C.JobNo WHERE  A.TimeApproved IS NULL AND A.CanceledBy IS NULL AND A.ClosedBy IS NULL AND C.Company='$this->Company' ORDER BY A.NoKO DESC")->result();
	}

	public function approveKOyes()
	{
		return $this->db->query("SELECT A.*, B.VendorNm, C.JobNm FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId JOIN Job C ON A.JobNo=C.JobNo 
WHERE A.TimeApproved IS NOT NULL AND A.CanceledBy IS NULL AND A.ClosedBy IS NULL AND C.Company='$this->Company' ORDER BY A.NoKO DESC")->result();
	}
	public function ListAddmKO($NoKO)
	{
		return $this->db->query("SELECT A.*, B.VendorNm FROM KoHdrH A inner join Vendor B On A.VendorId = B.VendorId Where A.NoKO='$NoKO'  AND CanceledBy IS NULL AND ClosedBy IS NULL ORDER BY NoKO")->result();
		// return $query->result();
	}
}
?>
