<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_PiutangProgressFisik extends CI_Model {

	var $Company;
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->Company = $this->config->item('Company');

    }

	function getDataPiutang(){
		 $this->db->query("
			TRUNCATE TABLE tempPiutang
			
			INSERT INTO tempPiutang(JobNo,JobNm,Kategori,DateRPPM, PersenRPPM, RpRPPM, RpTermin, PersenTermin)
			SELECT JobNo, JobNm, Kategori,
			(SELECT TOP 1 Tgl2 FROM RPPM WHERE JobNo=Job.JobNo ORDER BY Tgl2 DESC) AS 'DateRPPM',
			(SELECT TOP 1 ISNULL(Induk,0) FROM RPPM WHERE JobNo=Job.JobNo ORDER BY Tgl2 DESC) AS 'PersenRPPM',
			(SELECT TOP 1 ISNULL(Induk,0)/100 * (Job.Bruto/1.1) FROM RPPM WHERE JobNo=Job.JobNo ORDER BY Tgl2 DESC) AS 'RpRPPM',
			(SELECT ISNULL(SUM(BrutoBOQ/1.1),0) FROM TerminInduk WHERE JobNo=Job.JobNo AND Jenis='Termin') AS 'RpTermin',
			(SELECT ISNULL((SUM(BrutoBOQ/1.1)/(Job.Bruto/1.1))*100,0) FROM TerminInduk WHERE JobNo=Job.JobNo AND Jenis='Termin') AS 'PersenTermin'
			FROM Job WHERE TipeJob='Project' AND StatusJob='Pelaksanaan' AND Company='$this->Company'");
		return $this->db->affected_rows();
	}

}

/* End of file M_PiutangProgressFisik.php */
/* Location: ./application/models/MyReport_model/M_PiutangProgressFisik.php */