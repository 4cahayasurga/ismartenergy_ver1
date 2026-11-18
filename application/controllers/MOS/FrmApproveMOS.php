<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(0);
class FrmApproveMOS extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		is_login();
	}


	private function dapat_list_job($pengurutan='')
	{

		$CI = &get_instance();
		$user_id = $CI->session->userdata('MIS_LOGGED_ID');
		$Company = $CI->config->item('Company');

		$var_pengurutan = '';
		if($pengurutan !=''){
			$var_pengurutan = 'ORDER BY ax.JobNo DESC';
		}

		$query = $CI->db->query("

			SELECT ax.JobNo, bx.JobNm FROM
			(SELECT item as JobNo
				FROM
				dbo.SplitString ((SELECT top 1 a.AksesJob FROM (SELECT * FROM Login) as a
					left outer join
					(SELECT * FROM Job) as b
					on b.JobNo = a.AksesJob
					Where a.UserID= '$user_id'), ',')) as ax
			left outer join 
			Job as bx
			on bx.JobNo = ax.JobNo WHERE bx.Company='$Company' group by ax.JobNo, bx.JobNm ".$var_pengurutan."

			");

		return $query->result();
	}

	function view($JobNo='',$flag='')
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Approve MOS (Material On Site)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = '';

		$JobNm = '';
		if ($JobNo !='') {
			$data['JobNm'] = $this->db->SELECT('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
		}

		$data['flag'] = $flag;

		$data['ListJob'] = $this->dapat_list_job();


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmApproveMOS/halaman_utama_approve', $data);
		$this->load->view('templates/footer');
	}

	function TampilTableMOS(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$query = $this->db->query("SELECT * FROM tblBarangMasuk_Header WHERE JobNo='$JobNo' ORDER BY NoSJ DESC ")->result();
		$data['mydata'] = $query;

		$data['JobNo'] = $JobNo;
		$this->load->view('FrmApproveMOS/halaman_tbl_mos', $data);
	}

	function AksiSimpanApprove(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo');
		$TxtApprove = $this->input->post('TxtApprove');
		$NoSJ = $this->input->post('NoSJ');
		$NoKO = $this->input->post('NoKO');

		$ApprovedBy = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeApproved = date('Y-m-d H:i:s');

		$this->db->trans_start();

		$data = array();
		if ($TxtApprove == 'Approve') {
			$data = array(
				'ApprovedBy' => $ApprovedBy,
				'TimeApproved' => $TimeApproved
			);
		}else{

			$CekNoSJ = $this->db->query("SELECT * FROM tblPemakaian_MOS WHERE NoKO='$NoKO' ")->num_rows();
			if ($CekNoSJ > 0) {
				echo 'sudah_ada';
				die;
			}

			$data = array(
				'ApprovedBy' => NULL,
				'TimeApproved' => NULL
			);
		}

		$this->db->set($data)->where('LedgerNo',$LedgerNo)->update('tblBarangMasuk_Header');
		$this->db->set($data)->where('NoSJ',$NoSJ)->update('tblBarangMasuk_Detail');

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			echo 'gagal';
		}else{
			echo 'berhasil';
			// is_pesan('success','data berhasil diupdate');
		}
	}

}

/* End of file FrmApproveMOS.php */
/* Location: ./application/controllers/MOS/FrmApproveMOS.php */
