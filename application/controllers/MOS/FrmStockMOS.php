<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmStockMOS extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		tblBarangMasuk_Header_Pembuatan();
		tblBarangMasuk_Detail_Pembuatan();
		tblPemakaian_MOS_Pembuatan();
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

	public function view($JobNo='',$flag='')
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$dataHeader['judul'] = 'STOCK (Material On Site)';
		$dataHeader['bodyclass'] = 'sidebar-collapse skin-purple-light';

		$data['judul'] = 'STOCK (Material On Site)';


		$data['JobNo'] = $JobNo;
		$data['JobNm'] = '';

		$JobNm = '';
		if ($JobNo !='') {
			$data['JobNm'] = $this->db->SELECT('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
		}

		$data['flag'] = $flag;

		$data['ListJob'] = $this->dapat_list_job();

		$DariTgl = date("Y-m-d", strtotime( "-7 days" )); 
		$SampaiTgl = date("Y-m-d");

		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;


		$this->load->view('templates/header', $dataHeader);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmStockMOS_view/main_page_stock', $data);
		$this->load->view('templates/footer');
	}

	function table_stock(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		// print_rr($_POST);

		$JobNo = $this->input->post('JobNo');
		$DariTgl = $this->input->post('DariTgl');
		$SampaiTgl = $this->input->post('SampaiTgl');

		$query = $this->db->query("SELECT * FROM tblBarangMasuk_Detail WHERE JobNo='$JobNo' AND TimeEntry>='$DariTgl' AND TimeEntry<='$SampaiTgl'  ")->result();
		$data['dataBarangMasuk'] = $query;
		$this->load->view('FrmStockMOS_view/table_stock', $data);
	}

	// function get_NoMK(){
	// 	if (!$this->input->post('error')) {
	// 		echo 'error';
	// 		exit;
	// 	}

	// 	$JobNo = $this->input->post('JobNo');
	// 	$query = $this->db->where('JobNo',$JobNo)->get('tbl_PemakaianMOS')->result();

	// 	$html ="";
	// 	$html .="<option value=''>Pilih</option>";
	// 	foreach($query as $row){
	// 		$html .="<option value='".$row->NoMK."'>Pilih</option>";
	// 	}

	// }

}

/* End of file FrmStockMOS.php */
/* Location: ./application/controllers/MOS/FrmStockMOS.php */