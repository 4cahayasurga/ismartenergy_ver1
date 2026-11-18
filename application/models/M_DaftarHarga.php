
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_DaftarHarga extends CI_Model
{
	public function datatable()
	{
		$Company = $this->config->item('Company');

		return $query = $this->db->query("SELECT A.*, B.NoKO, B.TglKO, B.KategoriId, C.JobNm, D.VendorNm FROM KoDtl A 
			LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
			LEFT JOIN Job C ON B.JobNo=C.JobNo 
			LEFT JOIN Vendor D ON B.VendorId=D.VendorId 
			WHERE C.Company='$Company' and uraian is not null ")->result();
	}
}
