<?php 

class M_Mos extends CI_Model{
	public function gettblmos($JobNo=null)
	{

		return $this->db->query("SELECT A.NoSJ As No_MOS, A.TglSJ As TglTerima,D.VendorNm, A.NoKO As No_Kontrak, A.ApprovedBy as ApproveBY, A.TimeApproved As ApproveOn,
							B.*, E.Vol As Vol_PO FROM SjHdr A
							join SjDtl B ON A.NoSj = B.NoSJ
							join KoHdr C ON A.NoKO=C.NoKO
							join Vendor D ON C.VendorId = D.VendorId
							join KoDtl E On C.NoKO=E.NoKO and e.KdRAP = b.kdrap and e.NoUrut=b.NoUrut
							Where A.JobNo=$JobNo" )->result();
	}

	public function getDataMos($JobNo=null)
	{
		return $this->db->query("select a.NoSJ, a.JobNo, b.KdRAP, b.Uraian, b.Vol from SjHdr a,  Sjdtl b where a.nosj=b.nosj and a.JobNo='$JobNo'")->result();
	}

	

	function NoKO($JobNo)
	{
		return $query = $this->db->query("SELECT a.NoKO,a.VendorId, b.VendorNm, a.TglKO, a.NoSPR FROM KoHdr a inner join Vendor b On a.VendorId=b.VendorId Where JobNo='$JobNo'")->result(); 
	}

	public function simpan_MOSIN($table,$data)
	{
		$this->db->insert($table,$data);
	}

	function NoMOS($JobNo)
	{
		return $this->db->query(
			"select case 
			when LEN(b.NoSJ) = 1 then CONCAT('MOS','$JobNo','0000',b.NoSJ)
			when LEN(b.NoSJ) = 2 then CONCAT('MOS','$JobNo','000',b.NoSJ)
			when LEN(b.NoSJ) = 3 then CONCAT('MOS','$JobNo','00',b.NoSJ)
			when LEN(b.NoSJ) = 4 then CONCAT('MOS','$JobNo','0',b.NoSJ)
			else CONCAT('MOS','$JobNo',b.NoSJ)
			end as NoSJ
						from
						(select 
							case
								when a.val = 0 then 1
								else ((SELECT TOP 1 (right (NoSJ,5)) as NoSJ FROM SjHdr Where JobNo='$JobNo' order by NoSJ DESC) * 1) + 1
							end as NoSJ
						from 
							(
								SELECT TOP 1 count(NoSJ) as val FROM SjHdr Where JobNo='$JobNo'
							) as a
						) as b"
		);
	}

	// function nabar($JobNo)
	// {
	// 	return $this->db->query("SELECT Uraian from SjDtl a join SjHdr b on a.NoSJ=b.NoSJ  where JobNo='$JobNo'")->result();
	// }

	
}
?>
