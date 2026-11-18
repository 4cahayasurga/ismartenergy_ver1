<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmTrackPD extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function view()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Tracking PD';
		$data['bodyclass'] = 'sidebar-collapse skin-red-light';

		$data['ListJob'] = $this->dapat_list_job();
		$data['ListAlokasi'] = list_job_alokasi();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmTrackPD_view/main_page', $data);
		$this->load->view('templates/footer');
	}

	function dapat_list_job($pengurutan='')
	{

		$CI = &get_instance();
		$user_id = $CI->session->userdata('MIS_LOGGED_ID');
		$Company = $CI->config->item('Company');

		$var_pengurutan = '';
		if($pengurutan !=''){
			$var_pengurutan = 'ORDER BY ax.JobNo DESC';
		}

		$query = $CI->db->query("

			select ax.JobNo, bx.JobNm from
			(select item as JobNo
			from
			dbo.SplitString ((select top 1 a.AksesJob from (select * from Login) as a
			left outer join
			(select * from Job) as b
			on b.JobNo = a.AksesJob
			Where a.UserID= '$user_id'), ',')) as ax
			left outer join 
			Job as bx
			on bx.JobNo = ax.JobNo WHERE bx.Company='$Company' group by ax.JobNo, bx.JobNm ".$var_pengurutan."

			");

		return $query->result();
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

	function tableTrackPD(){
		if (!$this->input->post('JobNo')) {
			echo 'error';
			exit;
		}


		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		$Alokasi = ($Alokasi == '0') ? '' : $Alokasi;
		// echo $Alokasi;
		// exit;

		$myquery = $this->db->query("
			SELECT *, (SELECT TOP 1 TglBayar FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef) ORDER BY TimeEntry DESC) As TglBayar
			FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi LIKE '%$Alokasi%' ORDER BY TglPD DESC, NoPD DESC ")->result();


		$data['mydata'] = $myquery;

		// print_rr($data);
		// exit;

		$this->load->view('FrmTrackPD_view/tableTrackPD',$data);

	}

}

/* End of file FrmTrackPD.php */
/* Location: ./application/controllers/FrmTrackPD.php */