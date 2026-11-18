<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_CurvaProgressReport extends CI_Model {

	function getNilaiKontrak($JobNo){
		$query = $this->db->query("
			SELECT a.JobNo, a.JobNm, a.Bruto, a.Bruto AS 'NilaiKontrak' , (SELECT SUM(TerminInduk) FROM TerminInduk WHERE JobNo=a.JobNo ) AS 'TerminAkumulatif' FROM Job a WHERE a.JobNo='$JobNo' ")->row_array();
		return $query;
	}

	function getRingkasanRAP($JobNo){
		$query = $this->db->query("SELECT *  FROM RingkasanRAP WHERE JobNo='$JobNo' ");
		return $query->row_array();
	}

	function getVersi($JobNo){
		$query = $this->db->query("SELECT Alokasi, ISNULL(SUM(Amount),0) AS 'TtlBLE' FROM BLE WHERE JobNo='21570'  GROUP BY Alokasi");
		return $query->result_array();
	}

}

/* End of file M_CuravProgressReport.php */
/* Location: ./application/models/MyReport_model/M_CuravProgressReport.php */