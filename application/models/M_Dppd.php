
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Dppd extends CI_Model
{

	public function GetTbl1()
	{
		return $this->db->query("SELECT A.JobNo AS JobNo, A.*, B.JobNm, 
(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
(SELECT TOP 1 CaraBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'CaraBayar' 
FROM PdHdr A LEFT JOIN Job B ON A.JobNo=B.JobNo WHERE A.JobNo='21570' AND A.Alokasi='A' ORDER BY NoPD DESC")->result();
		// LEFT JOIN Job C ON A.JobNo=C.JobNo WHERE A.JobNo='$JobNo' ORDER BY A.NoPD DESC")->result();
	}

	function uraianPD($NoPD = null)
	{
		return $this->db->query("SELECT * FROM PdDtl WHERE NoPD ='$NoPD' ")->result();
	}

	public function GetAlokasi()
	{
		// "SELECT * FROM Alokasi";

		$UserID = $this->session->userdata('MIS_LOGGED_ID');
		$query = "select ax.Alokasi, bx.Keterangan from
					(select item as Alokasi 
					from
						dbo.SplitString (
							(select top 1 a.AksesAlokasi from (select * from Login) as a
							left outer join
							(select * from Alokasi) as b
							on b.Alokasi = a.AksesAlokasi
							Where a.UserID= '$UserID')
							, ',')
					) as ax
					left outer join 
						Alokasi as bx
					on bx.Alokasi = ax.Alokasi
					group by ax.Alokasi, bx.Keterangan";
		$eksekusi = $this->db->query($query);
		return $eksekusi->result();
	}

	// function InvoicePD($NoPD = null){
	// 	return $this->db->query("SELECT * FROM InvPD A LEFT JOIN Invoice B ON A.NoKO=B.NoKO AND A.InvNo=B.InvNo WHERE A.NoPD='$NoPD'")->result();
	// }

	// function PembayaranPD($NoPD =null){
	// 	return $this->db->query("SELECT BLE.* FROM PdHdr, BLE WHERE (PdHdr.NoPD=BLE.NoPD OR PdHdr.NoRef=BLE.NoPD) AND PdHdr.NoPD='$NoPD'")->result();
	// }

}
