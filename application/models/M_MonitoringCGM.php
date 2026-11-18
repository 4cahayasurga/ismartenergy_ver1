
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_MonitoringCGM extends CI_Model
{

	public function GetTbl1()
	{
		$Company = $this->config->item('Company');
		return $this->db->query("SELECT CGMundur.*, Job.JobNm, PdHdr.NoKO, 
		(SELECT TglBayar FROM BLE WHERE NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef) AS 'TglBayar' 
		FROM CGMundur,PdHdr, Job 
		WHERE CGMundur.NoPD=PdHdr.NoPD AND Job.JobNo=PdHdr.JobNo AND CGMundur.TglJT>='2019-01-24' AND CGMundur.TglJT<='2019-10-07' AND Job.Company='$Company' 
		ORDER BY CGMundur.LedgerNo")->result();
	}

	
}
