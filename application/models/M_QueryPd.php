
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_QueryPd extends CI_Model
{

	public function GetTbl1()
	{
		return $this->db->query("SELECT A.JobNo + ' - ' + C.JobNm AS 'JobNo1', A.*, B.SubTotal, B.DiscAmount, B.PPN, 
		(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
		(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
		(SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
		(SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount' 
		FROM PdHdr A 
		LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
		LEFT JOIN Job C ON A.JobNo=C.JobNo WHERE A.JobNo='21570' ORDER BY A.NoPD DESC")->result();
		// LEFT JOIN Job C ON A.JobNo=C.JobNo WHERE A.JobNo='$JobNo' ORDER BY A.NoPD DESC")->result();
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

	function uraianPD($NoPD = null)
	{
		return $this->db->query("SELECT * FROM PdDtl WHERE NoPD ='$NoPD' ")->result();
	}

	function InvoicePD($NoPD = null)
	{
		return $this->db->query("SELECT * FROM InvPD A LEFT JOIN Invoice B ON A.NoKO=B.NoKO AND A.InvNo=B.InvNo WHERE A.NoPD='$NoPD'")->result();
	}

	function PembayaranPD($NoPD = null)
	{
		return $this->db->query("SELECT BLE.* FROM PdHdr, BLE WHERE (PdHdr.NoPD=BLE.NoPD OR PdHdr.NoRef=BLE.NoPD) AND PdHdr.NoPD='$NoPD'")->result();
	}

	function GetTbl_new($where)
	{
		$JobNo = $where['JobNo'];
		$Alokasi = $where['Alokasi'];
		$dari_tgl = $where['dari_tgl'];
		$sampai_tgl = $where['sampai_tgl'];
		$jumlah_data = $where['jumlah_data'];

		$atur_query_job = "A.JobNo='$JobNo' AND ";
		if ($JobNo == 'semua') {
			$atur_query_job = '';
		}

		$untuk_limit_data = "TOP " . $jumlah_data;

		$tgl_validasi = '';
		if ($dari_tgl !== '' && $sampai_tgl !== '') {
			$tgl_validasi = "AND TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl' ";
		}

		return $this->db->query("
		SELECT " . $untuk_limit_data . " C.Company, A.JobNo + ' - ' + C.JobNm AS 'JobNo1', A.*, B.SubTotal, B.DiscAmount, B.PPN, A.Alokasi,
		(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
		(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
		(SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
		(SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount' 
		FROM PdHdr A 
		LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
		LEFT JOIN Job C ON A.JobNo=C.JobNo WHERE " . $atur_query_job . " A.Alokasi='A' " . $tgl_validasi . " AND (C.Company !='REN' AND C.Company !='AMK' AND C.Company !='AST' )  ORDER BY A.TimeEntry DESC
		
		")->result();


		// return $this->db->query("SELECT " . $untuk_limit_data . " A.JobNo + ' - ' + C.JobNm AS 'JobNo1', A.*, B.SubTotal, B.DiscAmount, B.PPN, A.Alokasi,
		// (SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
		// (SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
		// (SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
		// (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount' 
		// FROM PdHdr A 
		// LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
		// LEFT JOIN Job C ON A.JobNo=C.JobNo WHERE " . $atur_query_job . " A.Alokasi='$Alokasi' " . $tgl_validasi . " ORDER BY A.TimeEntry DESC")->result();
	}

	function uraianPD_new($NoPD = null)
	{
		return $this->db->query("SELECT *, (HrgSatuan * Vol) as total_pd, (PjHrgSatuan * PjVol) as total_pj FROM PdDtl WHERE NoPD ='$NoPD' ")->result_array();
	}

	function InvoicePD_new($NoPD = null)
	{
		return $this->db->query("SELECT * FROM InvPD A LEFT JOIN Invoice B ON A.NoKO=B.NoKO AND A.InvNo=B.InvNo WHERE A.NoPD='$NoPD'")->result_array();
	}

	function PembayaranPD_new($NoPD = null)
	{
		return $this->db->query("SELECT BLE.* FROM PdHdr, BLE WHERE (PdHdr.NoPD=BLE.NoPD OR PdHdr.NoRef=BLE.NoPD) AND PdHdr.NoPD='$NoPD'")->result_array();
	}
}
