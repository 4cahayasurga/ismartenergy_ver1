<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Datatables');
	}

	public function get_Inv_table($JobNo = '')
	{

		return $this->db->query("select a.LedgerNo, a.NoKO,c.VendorNm,a.InvNo,a.InvDate,a.Duedate,a.Total,a.PPN,a.PPH,a.FPNo,a.FPDate,a.Keterangan, a.TimeEntry from Invoice a inner join KoHdr b on a.NoKO=b.NoKO inner join Vendor c on b.VendorId=c.VendorId Where a.JobNo='$JobNo'")->result();

		// return $this->db->query("select a.NoKO,c.VendorNm,a.InvNo,a.InvDate,a.Duedate,a.Total,a.PPN,a.PPH,a.FPNo,a.FPDate,a.TimeEntry from Invoice a inner join KoHdr b on a.NoKO=b.NoKO inner join Vendor c on b.VendorId=c.VendorId Where a.JobNo='$JobNo'")->result();
	
		
	}

	function NoKO($JobNo)
	{
		return $query = $this->db->query("SELECT a.NoKO,a.VendorId, b.VendorNm, a.TglKO, a.NoSPR FROM KoHdr a inner join Vendor b On a.VendorId=b.VendorId Where JobNo='$JobNo'")->result();
	}


}




 ?>
