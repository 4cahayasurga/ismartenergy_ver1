<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class FrmUsageMOS extENDs CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		Pembuatan_Table_Pakai_MOS();
	}

	public function view($JobNo='',$flag='')
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'PEMAKAIAN MOS (Material On Site)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';
		$data['ListJob'] = $this->dapat_list_job();

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = '';

		$JobNm = '';
		if ($JobNo !='') {
			$data['JobNm'] = $this->db->SELECT('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
		}

		$data['TxtJob'] = ($JobNo !='') ? 'readonly' : '';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('MOS_view/MOSPemakaian/halaman_utama', $data);
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

	function getNoKO()
	{
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo');
		// $query = $this->db->query("SELECT * FROM SjHdr_Cadangan WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL ")->result();
		$query = $this->db->query("SELECT DISTINCT(NoKO) AS 'NoKO' FROM SjHdr_Cadangan WHERE JobNo='$JobNo' ")->result();

		// $where = array('JobNo' => $JobNo,)
		// $query = $this->db->where()

		$html = '<option value="">-- Pilih --</option>';
		foreach($query as $row):
			$html.= '<option value="'.$row->NoKO.'">'.$row->NoKO.'</option>';
		endforeach;

		echo $html;
	}

	function tampil_table_PemakaianSJ(){
		if (!$this->input->post('NoKO')) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);


		$ArrayKO = array();
		$query = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ");
		foreach ($query->result() as $row) {

			$VolYangSudahDiTerima = 0;
			$TotalVolPakai = 0;

			$CekJumlahVolume = $this->db->query("SELECT SUM(VolTerima) AS 'VolYangSudahDiterima' FROM SjDtl_Cadangan WHERE NoKO='$row->NoKO' AND NoUrut='$row->NoUrut'   ")->row();
			$VolYangSudahDiTerima = $CekJumlahVolume->VolYangSudahDiterima;

			$queryVolPakai = $this->db->query("SELECT ISNULL(SUM(VolPakai),0) AS 'TotalVolPakai' FROM tbl_PakaiMOS WHERE NoKO='$row->NoKO' AND NoUrut='$row->NoUrut'   ")->row();

			$TotalVolPakai = $queryVolPakai->TotalVolPakai;


			$ArrayKO[] = (object) array(
				'NoUrut' => $row->NoUrut,
				'NoKO' => $row->NoKO,
				'JobNo' => $row->JobNo,
				'Alokasi' => $row->Alokasi,
				'KdRAP' => $row->KdRAP,
				'Uraian' => $row->Uraian,
				'Uom' => $row->Uom,
				'HrgSatuan' => $row->HrgSatuan,
				'VolKO' => $row->Vol,
				'VolYangSudahDiterima' => $VolYangSudahDiTerima,
				'VolTerima' => 0,
				'TotalVolPakai' => $TotalVolPakai,
			);
		}

		// print_rr($ArrayKO);
		// exit;

		$data['ArrayKO'] = $ArrayKO;

		$data['mydataSJ'] = $query;
		$data['JobNo'] = $JobNo;

		$this->load->view('MOS_view/MOSPemakaian/table_PemakaianSJ', $data);
	}

	function UpdateVolTerpakai(){
		if (!$this->input->post('NoSJ')) {
			die;
		}

		$NoSJ = $this->input->post('NoSJ');
		$NoUrut = $this->input->post('NoUrut');
		$VolTerpakai = $this->input->post('VolTerpakai');

		$this->db->trans_begin();

		$this->db->query("UPDATE SjDtl_Cadangan SET VolTerpakai='$VolTerpakai' WHERE NoSJ='$NoSJ' AND NoUrut='$NoUrut'  ");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
		}else{
			$this->db->trans_commit();
			echo 'berhasil';
		}

		// print_rr($_POST);
	}

	function SaveEntryPemakaian(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);

		$NoUrut = $this->input->post('NoUrut');
		$NoKO = $this->input->post('NoKO');
		$Alokasi = $this->input->post('Alokasi');
		$KdRAP = $this->input->post('KdRAP');
		$Uraian = $this->input->post('Uraian');
		$Uom = $this->input->post('Uom');
		$HrgSatuan = $this->input->post('HrgSatuan');
		$JobNo = $this->input->post('JobNo');
		$VolPakai = $this->input->post('VolPakai');

		$searchForValue = ',';

		if( strpos($VolPakai, $searchForValue) !== false ) {
			$VolPakai = str_replace(",", "", $VolPakai);
		}

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$data = array(
			'NoUrut' => $NoUrut,
			'Alokasi' => $Alokasi,
			'KdRAP' => $KdRAP,
			'Uraian' => $Uraian,
			'Uom' => $Uom,
			'HrgSatuan' => $HrgSatuan,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'JobNo' => $JobNo,
			'NoKO' => $NoKO,
			'VolPakai' => $VolPakai,
		);
		// print_rr($_POST);

		$this->db->trans_begin();
		$this->db->insert('tbl_PakaiMOS',$data);
		if ($this->db->trans_status() === FALSE) {
			echo 'gagal';
			$this->db->trans_rollback();
		}else{
			echo 'berhasil';
			$this->db->trans_commit();
		}

		

	}


}