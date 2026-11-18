<?php 
defined('BASEPATH') or exit('No direct script acess allowed');

class ApprovedSpr extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_job');
	}

	public function getDataSpr($JobNo)
	{
		$GetSpr = $this->db->query("SELECT * FROM PrHdr Where JobNo = $JobNo");
		$data['GetSpr'] = $GetSpr;
	}
}
 ?>
