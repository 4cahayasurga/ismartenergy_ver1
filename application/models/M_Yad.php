<?php 

class M_Yad extends CI_Model{
	
	public function getRAP($JobNo=null)
	{
		$query = $this->db->query("select SUM(vol * HrgSatuan) as TTLRAP from RAP where Alokasi IN('B','C') AND Tipe !='Header' AND JobNo='$JobNo'");
		return $query;
	}

	function terpakai($JobNo=null)
	{
		$query = $this->db->query("SELECT SUM(Amount) AS Terpakai From BLE Where Alokasi IN ('B','C') and JobNo='$JobNo'");
		return $query;
	}

	function YAD($JobNo=null)
	{
		$query = $this->db->query("select SUM(Amount) as getYAD from YAD Where JobNo='$JobNo'");
		return $query;
	}

	function TotalKO($JobNo=null)
	{
		$query = $this->db->query("SELECT SUM(Subtotal-DiscAmount+PPN) as TotalKO FROM KoHdr WHERE ApprovedBy is not null and CanceledBy is NUll AND JobNo='$JobNo'");
		return $query;
	}

	function TotalInv($JobNo=null)
	{
		$query = $this->db->query("SELECT SUM(TOTAL) as TotalINV FROM Invoice WHERE JobNo='$JobNo'");
		return $query;
	}

	function grafikYAD($JobNo=null)
	{
		$query = $this->db->query("select 
				isnull ((select (SUM(vol * HrgSatuan)) from RAP where Alokasi IN('B','C') AND Tipe !='Header' AND JobNo='$JobNo'),0) as TTLRAP,
				isnull ((select (SUM(SubTotal-DiscAmount+PPN)) FROM KoHdr where ApprovedBy is not null and CanceledBy is NUll AND JobNo='$JobNo'),0) AS TOTALKO,
				isnull ((SELECT (SUM(Total)) FROM Invoice WHERE JobNo='$JobNo'), 0)AS TOTALINV,
				isnull ((select (SUM(SubTotal-DiscAmount+PPN)) FROM KoHdr where ApprovedBy is not null and CanceledBy is NUll AND JobNo='$JobNo') - ((SELECT (SUM(Total)) FROM Invoice WHERE JobNo='$JobNo')),0) AS WIP,
				isnull ((select (SUM(Amount))  from YAD Where JobNo='$JobNo'),0) as getYAD");
		return $query;
	}

}
