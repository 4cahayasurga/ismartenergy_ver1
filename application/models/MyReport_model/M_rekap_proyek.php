<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_rekap_proyek extends CI_Model {

	function getDataRekap($Tahun,$TglAwal,$TglAkhir){

		$Company = $this->config->item('Company');

		$query = $this->db->query("
			SELECT JobNo, JobNm, Netto, PersenKSO, ISNULL(Netto*(PersenKSO/100),0) AS 'NettoShare',
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND TglPD>='$TglAwal' AND TglPD<='$TglAkhir' AND PdHdr.Alokasi='A') AS 'AlokasiA',
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND TglPD>='$TglAwal' AND TglPD<='$TglAkhir' AND PdHdr.Alokasi='B') AS 'AlokasiB',
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND TglPD>='$TglAwal' AND TglPD<='$TglAkhir' AND PdHdr.Alokasi='C') AS 'AlokasiC', 
			(SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo) AS 'TerminInduk',
			(CASE
				WHEN Job.KSO='1' AND Job.TipeManajerial='JO Partial' THEN 
				(CASE 
					WHEN Job.Own='1' THEN
					(SELECT ISNULL(SUM(TerminMember1 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo)
					WHEN Job.Own='2' THEN 
					(SELECT ISNULL(SUM(TerminMember2 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo)
					END)
				ELSE 
				(SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo)
				END) AS 'TerminMember' 
			FROM Job WHERE TipeJob='Project' AND StatusJob='Pelaksanaan' AND Job.Company='$Company' ORDER BY JobNo

			");
		return $query;
	}

	function getDataRekap_11FEB2024($Tahun,$TglAwal,$TglAkhir){

		$Company = $this->config->item('Company');

		$query = $this->db->query("

			SELECT JobNo, JobNm, Netto, PersenKSO, ISNULL(Netto*(PersenKSO/100),0) AS 'NettoShare', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND YEAR(TglPD)>='$Tahun' AND PdHdr.Alokasi='A') AS 'AlokasiA', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND YEAR(TglPD)>='$Tahun' AND PdHdr.Alokasi='B') AS 'AlokasiB', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND YEAR(TglPD)>='$Tahun' AND PdHdr.Alokasi='C') AS 'AlokasiC', 
			(SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo) AS 'TerminInduk', 
			(CASE 
				WHEN Job.KSO='1' AND Job.TipeManajerial='JO Partial' THEN 
				(CASE 
					WHEN Job.Own='1' THEN 
					(SELECT ISNULL(SUM(TerminMember1 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo) 
					WHEN Job.Own='2' THEN 
					(SELECT ISNULL(SUM(TerminMember2 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo) 
					END) 
				ELSE 
				(SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo) 
				END) AS 'TerminMember' 
			FROM Job WHERE TipeJob='Project' AND StatusJob='Pelaksanaan' AND Job.Company='$Company' ORDER BY JobNo

			");
		return $query;
	}

	function getDataRekap_lama($Tahun,$TglAwal,$TglAkhir){

		$Company = $this->config->item('Company');

		$query = $this->db->query("

			SELECT JobNo, JobNm, Netto, PersenKSO, ISNULL(Netto*(PersenKSO/100),0) AS 'NettoShare', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND YEAR(TglPD)>='$Tahun' AND PdHdr.Alokasi='A') AS 'AlokasiA', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND YEAR(TglPD)>='$Tahun' AND PdHdr.Alokasi='B') AS 'AlokasiB', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND YEAR(TglPD)>='$Tahun' AND PdHdr.Alokasi='C') AS 'AlokasiC', 
			(SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo) AS 'TerminInduk', 
			(CASE 
				WHEN Job.KSO='1' AND Job.TipeManajerial='JO Partial' THEN 
				(CASE 
					WHEN Job.Own='1' THEN 
					(SELECT ISNULL(SUM(TerminMember1 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo) 
					WHEN Job.Own='2' THEN 
					(SELECT ISNULL(SUM(TerminMember2 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo) 
					END) 
				ELSE 
				(SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo) 
				END) AS 'TerminMember' 
			FROM Job WHERE TipeJob='Project' AND StatusJob='Pelaksanaan' AND Job.Company='$Company' ORDER BY JobNo

			");
		return $query;
	}	

	function getDataRekap_old($Tahun,$TglAwal,$TglAkhir){

		$Company = $this->config->item('Company');

		$query = $this->db->query("

			SELECT JobNo, JobNm, Netto, PersenKSO, ISNULL(Netto*(PersenKSO/100),0) AS 'NettoShare', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND TglPD>='$TglAwal' AND TglPD<='$TglAkhir' AND PdHdr.Alokasi='A') AS 'AlokasiA', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND TglPD>='$TglAwal' AND TglPD<='$TglAkhir' AND PdHdr.Alokasi='B') AS 'AlokasiB', 
			(SELECT ISNULL(SUM(BLE.Amount),0) FROM PdHdr, BLE WHERE PdHdr.Jobno=Job.JobNo AND (PdHdr.NoPd=BLE.NoPd OR PdHdr.NoRef=BLE.NoPd) AND TglPD>='$TglAwal' AND TglPD<='$TglAkhir' AND PdHdr.Alokasi='C') AS 'AlokasiC', 
			(SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo) AS 'TerminInduk', 
			(CASE 
				WHEN Job.KSO='1' AND Job.TipeManajerial='JO Partial' THEN 
				(CASE 
					WHEN Job.Own='1' THEN 
					(SELECT ISNULL(SUM(TerminMember1 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo) 
					WHEN Job.Own='2' THEN 
					(SELECT ISNULL(SUM(TerminMember2 + CadanganKSO),0) FROM TerminMember WHERE JobNo=Job.JobNo) 
					END) 
				ELSE 
				(SELECT ISNULL(SUM((TerminInduk/1.1)*0.97),0) FROM TerminInduk WHERE JobNo=Job.JobNo) 
				END) AS 'TerminMember' 
			FROM Job WHERE TipeJob='Project' AND StatusJob='Pelaksanaan' AND Job.Company='$Company' ORDER BY JobNo

			");
		return $query;
	}	

}

/* End of file M_rekap_proyek.php */
/* Location: ./application/models/MyReport_model/M_rekap_proyek.php */