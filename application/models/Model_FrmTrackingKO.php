<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_FrmTrackingKO extends CI_Model {

	function getJobNo(){
		$Company = $this->config->item('Company');
		$query = $this->db->query("SELECT JobNo, JobNm FROM Job WHERE Company='$Company' ")->result();
		return $query;
	}

	function pembuatan_koma_implode_JobNo(){
		$comma_separated = "";
		$AksesJob = AksesJob_HanyaJobNumber();
		$arrayJob = array();

		foreach($AksesJob as $JobAkses){
			$arrayJob[] = $JobAkses->JobNo;
		}

		$comma_separated = implode("','", $arrayJob);
		$comma_separated = "'".$comma_separated."'";

		return $comma_separated;
	}

}

/* End of file Model_FrmTrackingKO.php */
/* Location: ./application/models/Model_FrmTrackingKO.php */