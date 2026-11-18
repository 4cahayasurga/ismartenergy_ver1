<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Mpdf\Mpdf;

class ENT_RAP extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('Entry_model/Rap_model');
		if (!$this->db->field_exists('LedgerNo', 'RAP'))
		{
			echo 'Field Ledger tidak ada';
			die;
		}

	}

	function index($JobNo='')
	{

		// if($JobNo == ''){
		// 	redirect('welcome/index','refresh');
		// 	exit;
		// }
		

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'RAP LIST';
		$data['bodyclass'] = 'sidebar-collapse skin-red';

		$tgl_saat_ini = date('Y-m-d');

		$data['tgl_pertama'] = date('Y-m-d', strtotime('-7 days'));
		$data['tgl_terakhir'] = $tgl_saat_ini;

		$data['JobNo'] = ($JobNo != '') ? $JobNo : '';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_master/RAP/view_rap', $data);
		$this->load->view('templates/footer');
	}

	function dapat_versi()
	{
		if ($this->input->post('JobNo') == '') {
			echo 'no';
			exit;
		}

		if ($this->input->post('Alokasi') == '') {
			echo 'no';
			exit;
		}


		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$Versi = $this->Rap_model->get_versi($JobNo, $Alokasi);

		$versi_rap = '0.0';
		$versi_tipe = '';

		$myoption = '';

		$dapat_versi_rap = $this->db->query("SELECT TOP 1 Versi FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ");
		if ($dapat_versi_rap->num_rows() > 0) {
			$row = $dapat_versi_rap->row_array();
			$versi_rap = $row['Versi'];
		} else {
			$versi_rap = '0.0';
		}

		$myoption .= '<option value="' . $versi_rap . '" data-versi_tipe="RAP"  >' . $versi_rap . '</option>';
		$dapat_versi_lama = $this->db->query("SELECT DISTINCT Versi FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY Versi DESC");
		if ($dapat_versi_lama->num_rows() > 0) {
			foreach ($dapat_versi_lama->result_array() as $myversi) :
				$myoption .= '<option value="' . $myversi['Versi'] . '" data-versi_tipe="RAPH"  >' . $myversi['Versi'] . '</option>';
			endforeach;
		}
		echo $myoption;
		exit;
	}



	function list_data_rap()
	{
		if (!$this->db->field_exists('LedgerNo', 'RAPH'))
		{
			echo 'Field LedgerNo pada RAPH tidak ada';
			die;
		}


		if (empty($this->input->post('JobNo')) || empty($this->input->post('Alokasi'))) {
			die;
		}

		

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$Versi = $this->input->post('Versi', TRUE);
		$Versi_tipe = $this->input->post('Versi_tipe', TRUE);
		$cek_tipe_versi = substr($Versi_tipe, -1);
		// echo $cek_tipe_versi;

		if ($cek_tipe_versi == 'P') {
			$query_dapat_rap = $this->db->query("
				declare @SubTotalRAP money
				declare @SubTotalRAB money
				SET @SubTotalRAP = '0'
				SET @SubTotalRAB = '0'

				SET @SubTotalRAP = (SELECT SUM(HrgSatuan * Vol) as SubTotalRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header' AND Header='0')
				SET @SubTotalRAB = (SELECT SUM(HrgRAB * Vol) as SubTotalRAB FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header' AND Header='0')

				SELECT 
				*,
				IIF(Tipe='Header',NULL,HrgSatuan) as HrgSatuanRAP_Custom,
				IIF(Tipe='Header',NULL,HrgRAB) as HrgSatuanRAB_Custom,

				Vol * HrgSatuan as JumlahHargaRAP,


				Vol * HrgRAB as JumlahHargaRAB,


				@SubTotalRAP as SubTotalRAP,
				@SubTotalRAB as SubTotalRAB

				FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'  ORDER BY NoUrut ASC


				");
		} else {
			$query_dapat_rap = $this->db->query("
				declare @SubTotalRAP money
				declare @SubTotalRAB money
				SET @SubTotalRAP = '0'
				SET @SubTotalRAB = '0'

				SET @SubTotalRAP = (SELECT SUM(HrgSatuan * Vol) as SubTotalRAP FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' AND Tipe='Header' AND Header='0')
				SET @SubTotalRAB = (SELECT SUM(HrgRAB * Vol) as SubTotalRAB FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' AND Tipe='Header' AND Header='0')

				SELECT 
				*,
				IIF(Tipe='Header',NULL,HrgSatuan) as HrgSatuanRAP_Custom,
				IIF(Tipe='Header',NULL,HrgRAB) as HrgSatuanRAB_Custom,

				Vol * HrgSatuan as JumlahHargaRAP,


				Vol * HrgRAB as JumlahHargaRAB,


				@SubTotalRAP as SubTotalRAP,
				@SubTotalRAB as SubTotalRAB

				FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi'  ORDER BY NoUrut ASC


				");
		}

		$data['mydata'] = $query_dapat_rap->result_array();


		$Versi_ubah = explode('.', $Versi);
		$array = array(
			'myarray' => $Versi_ubah[0] . '-' . $Versi_ubah[1]
		);
		$Versi_baru = implode('-', $array);


		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['Versi'] = $Versi;
		$data['Versi_baru'] = $Versi_baru;
		$data['Versi_tipe'] = $cek_tipe_versi;

		// print_rr($data);
		// die;
		$this->load->view('entry_master/RAP/list_rap_view', $data);
	}

	function mypdf_rap($JobNo = '', $Alokasi = '', $Versi = '', $Versi_tipe = '')
	{
		if (empty($JobNo) or empty($Alokasi) or empty($Versi)) {
			die;
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$dapat_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'  ")->row_array();
		$dapat_alokasi = $this->db->query("SELECT Keterangan FROM Alokasi WHERE Alokasi='$Alokasi'  ")->row_array();

		$Versi_ubah = explode('-', $Versi);
		$array = array(
			'myarray' => $Versi_ubah[0] . '.' . $Versi_ubah[1]
		);
		$Versi_baru = implode('.', $array);
		$cek_tipe_versi = substr($Versi_tipe, -1);

		$data['JobNm'] = $dapat_job['JobNm'];
		$data['Keterangan'] = $dapat_alokasi['Keterangan'];
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['Versi'] = $Versi;
		$data['Versi_baru'] = $Versi_baru;

		if ($cek_tipe_versi == 'P') {
			$query_dapat_rap = $this->db->query("
				declare @SubTotalRAP money
				declare @SubTotalRAB money
				SET @SubTotalRAP = '0'
				SET @SubTotalRAB = '0'

				SET @SubTotalRAP = (SELECT SUM(HrgSatuan * Vol) as SubTotalRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header' AND Header='0')
				SET @SubTotalRAB = (SELECT SUM(HrgRAB * Vol) as SubTotalRAB FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header' AND Header='0')

				SELECT 
				*,
				IIF(Tipe='Header',NULL,HrgSatuan) as HrgSatuanRAP_Custom,
				IIF(Tipe='Header',NULL,HrgRAB) as HrgSatuanRAB_Custom,

				Vol * HrgSatuan as JumlahHargaRAP,


				Vol * HrgRAB as JumlahHargaRAB,


				@SubTotalRAP as SubTotalRAP,
				@SubTotalRAB as SubTotalRAB

				FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'  ORDER BY NoUrut ASC


				");
		} else {
			$query_dapat_rap = $this->db->query("
				declare @SubTotalRAP money
				declare @SubTotalRAB money
				SET @SubTotalRAP = '0'
				SET @SubTotalRAB = '0'

				SET @SubTotalRAP = (SELECT SUM(HrgSatuan * Vol) as SubTotalRAP FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi_baru' AND Tipe='Header' AND Header='0')
				SET @SubTotalRAB = (SELECT SUM(HrgRAB * Vol) as SubTotalRAB FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi_baru' AND Tipe='Header' AND Header='0')

				SELECT 
				*,
				IIF(Tipe='Header',NULL,HrgSatuan) as HrgSatuanRAP_Custom,
				IIF(Tipe='Header',NULL,HrgRAB) as HrgSatuanRAB_Custom,

				Vol * HrgSatuan as JumlahHargaRAP,


				Vol * HrgRAB as JumlahHargaRAB,


				@SubTotalRAP as SubTotalRAP,
				@SubTotalRAB as SubTotalRAB

				FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi_baru'  ORDER BY NoUrut ASC


				");
		}


		$data['mydata'] = $query_dapat_rap->result_array();
		$grand_total_rap = $this->db->query("SELECT SUM(HrgSatuan * Vol) as SubTotalRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header' AND Header='0'")->row_array();
		$data['grand_total_rap'] = $grand_total_rap['SubTotalRAP'];



		$this->load->view('entry_master/RAP/print/mypdf_rap_content', $data);
	}



	function add_new_RAP()
	{
		if (empty($this->input->post('JobNo')) || empty($this->input->post('Alokasi'))) {
			echo 'no';
			die;
		}

		if ($this->input->post('Versi_tipe') == 'H') {
			echo 'versi_lama';
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$Versi = $this->input->post('Versi', TRUE);
		$Versi_tipe = $this->input->post('Versi_tipe', TRUE);

		$data = array(
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,
			'Versi' => $Versi,
			'Versi_tipe' => $Versi_tipe,
		);

		$dapat_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'  ")->row_array();
		$list_header = $this->db->query("SELECT KdRAP,Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header'")->result_array();
		$dapat_alokasi = $this->db->query("SELECT Keterangan FROM Alokasi WHERE Alokasi='$Alokasi'  ")->row_array();


		$data['JobNm'] = $dapat_job['JobNm'];
		$data['Keterangan'] = $dapat_alokasi['Keterangan'];
		$data['header_rap'] = $list_header;


		$this->load->view('entry_master/RAP/form-add-rap', $data);

		// print_rr($_POST);
	}

	function aksi_simpan_RAP(){
		if (!$this->input->post()) {
			echo 'no';
			exit;
		}

		

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$Tipe = $this->input->post('Tipe',TRUE);
		$Header = $this->input->post('Header',TRUE);
		$KdRAP = $this->input->post('KdRAP',TRUE);
		$Uraian = $this->input->post('Uraian',TRUE);
		$Uom = $this->input->post('Uom',TRUE);
		$Vol = $this->input->post('Vol',TRUE);
		$HrgSatuan= $this->input->post('HrgSatuan',TRUE);
		$HrgRAB = $this->input->post('HrgRAB',TRUE);

		$Versi = $this->input->post('Versi',TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		if($Tipe == 'Header'){
			$Vol = 1;
			$HrgSatuan = 0;
			$HrgRAB = 0;
		}

		$data = array(
			'Tipe' => $Tipe,
			'Header' => $Header,
			'KdRAP' => $KdRAP,
			'Uraian' => $Uraian,
			'Uom' => $Uom,
			'Vol' => $Vol,
			'HrgSatuan' => HapusFormatUang($HrgSatuan),
			'HrgRAB' => HapusFormatUang($HrgRAB),
			'Versi' => $Versi,
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry
		);

		if($this->cek_KdRAP($JobNo,$Alokasi,$KdRAP) == 'ada'){
			echo 'rap_sudah_ada';
			exit;
		}

		If($Header == '0' && $Tipe == 'Detail') {
			echo 'detail_dilarang_top_header';
			exit;
		}

		$buat_NoUrut = $this->dapat_NoUrut($JobNo,$Alokasi);

		//ambil no urut
		$data['NoUrut'] = $buat_NoUrut;

		$simpan_RAP = $this->Rap_model->aksi_simpan_RAP_baru($data);
		// echo $simpan_RAP;
		// die;
		if ($simpan_RAP == TRUE) {
			echo 'berhasil';
			die;
		}

		echo 'gagal';
		exit;


	}

	


	function dapat_data_rap(){
		if (!$this->input->post()) {
			die;
		}

		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$data_rap = $this->db->where('LedgerNo',$LedgerNo)->get('RAP');

		if ($data_rap->num_rows() == 0) {
			echo 'data_kosong';
			die;
		}

		$row_rap = $data_rap->row_array();

		$JobNo = $row_rap['JobNo'];
		$Alokasi = $row_rap['Alokasi'];


		$dapat_job = $this->db->select('JobNo,JobNm')->where('JobNo',$JobNo)->get('Job')->row_array();
		$dapat_alokasi = $this->db->query("SELECT Keterangan FROM Alokasi WHERE Alokasi='$Alokasi'  ")->row_array();

		

		$list_header = $this->db->query("SELECT KdRAP,Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header'")->result_array();


		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $dapat_job['JobNm'];
		$data['Alokasi'] = $row_rap['Alokasi'];
		$data['Keterangan'] = $dapat_alokasi['Keterangan'];

		$data['data_RAP'] = $row_rap;
		$data['header_rap'] = $list_header;


		$this->load->view('entry_master/RAP/form-edit-rap', $data);

	}

	private function dapat_NoUrut($JobNo,$Alokasi){
		$query = $this->db->query("SELECT TOP 1 NoUrut FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut DESC")->row_array();

		//nomor urut tambah satu
		$tambah_satu = $query['NoUrut'] + 1;
		return $tambah_satu;
	}

	private function cek_KdRAP($JobNo,$Alokasi,$KdRAP){
		$myquery = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND KdRAP='$KdRAP' AND Alokasi='$Alokasi'  ");
		if($myquery->num_rows() > 0){
			return 'ada';
		}else{

			return 'belum';
		}

	}

	function get_form_edit(){
		if (!$this->input->post('LedgerNo')) {
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$MyRAP = $this->Rap_model->Query_Get_Data_RAP($LedgerNo);

		$JobNo = $MyRAP['JobNo'];
		$Alokasi = $MyRAP['Alokasi'];
		$KdRAP = $MyRAP['KdRAP'];
		$Tipe = $MyRAP['Tipe'];


		$RAP_status = 0;

		// $CekHeaderAdaDetail = $this->CekHeaderAdaDetail($JobNo,$Alokasi,$KdRAP);
		// if ($CekHeaderAdaDetail == 'ada') {
		// 	$RAP_status = 1;
		// }

		if ($Tipe == 'Header') {
			$RAP_status = 1;
		}

		$data['RAP_status'] = $RAP_status;

		$data['MyRAP'] = $MyRAP;
		$data['list_header'] = $this->dapat_list_header($JobNo,$Alokasi);

		// print_rr($data);
		// die;

		$this->load->view('entry_master/RAP/form-edit-rap',$data);
	}

	function Aksi_Edit_RAP(){
		if (!$this->input->post()) {
			exit;
		}

		$myupdate = $this->Rap_model->new_edit_rap();
		if ($myupdate > 0) {
			echo 'berhasil';
			exit;
		}
		echo 'gagal';
		exit;


		
	}




	private function dapat_list_header($JobNo,$Alokasi){
		$list_header = $this->db->query("SELECT KdRAP,Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header'")->result_array();
		return $list_header;
	}

	private function CekHeaderAdaDetail($JobNo,$Alokasi,$KdRAP){

		$myquery = $this->db->query("SELECT KdRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Header='$KdRAP' ");
		// return $myquery->num_rows();

		if ($myquery->num_rows() > 0 ) {
			return 'ada';
		}else{
			return 'kosong';
		}

		// return $this->db->query("SELECT KdRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Header='$KdRAP' ");
		
	}

	function AksiHapusRAP(){
		if (!$this->input->post('LedgerNo')) {
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$data_rap = $this->db->query("SELECT * FROM RAP WHERE LedgerNo='$LedgerNo' ")->row_array();

		$Tipe = $data_rap['Tipe'];
		$JobNo = $data_rap['JobNo'];
		$Alokasi = $data_rap['Alokasi'];
		$KdRAP = $data_rap['KdRAP'];

		// if ($Tipe == 'Header') {
		$cek_rap = $this->CekHeaderAdaDetail($JobNo,$Alokasi,$KdRAP);
		if ($cek_rap == 'ada') {
			echo 'header_memiliki_detail';
			exit;
		}

		$cekPDRAP = $this->cekPDRAP($JobNo,$Alokasi,$KdRAP);
		if ($cekPDRAP == 'ada') {
			echo 'ada_pd';
			exit;
		}

		$cek_KO_RAP = $this->cek_KO_RAP($JobNo,$Alokasi,$KdRAP);
		if ($cek_KO_RAP == 'ada') {
			echo 'ada_ko';
			exit;
		}
		
		$delete = $this->db->where('LedgerNo',$LedgerNo)->delete('RAP');
		if ($delete > 0) {
			echo 'berhasil';
			exit;
		}
		echo 'gagal';
		exit;
	}

	private function cekPDRAP($JobNo,$Alokasi,$KdRAP){
		$cek_PD = $this->db->query("SELECT * FROM PdHdr A JOIN PdDtl B ON A.NoPD=B.NoPD WHERE A.JobNo='$JobNo' AND A.Alokasi='$Alokasi' AND A.RejectBy IS NULL AND B.KdRAP='$KdRAP' ");

		if ($cek_PD->num_rows() > 0 ) {
			return 'ada';
		}else{
			return 'kosong';
		}
	}


	private function cek_KO_RAP($JobNo,$Alokasi,$KdRAP){
		$cek_KO = $this->db->query("SELECT * FROM KoHdr A JOIN KoDtl B ON A.NoKO=B.NoKO WHERE A.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND B.KdRAP='$KdRAP'");

		if ($cek_KO->num_rows() > 0 ) {
			return 'ada';
		}else{
			return 'kosong';
		}

	}


	function dapat_form_tambah_versi(){
		if (empty($this->input->post('JobNo')) || empty($this->input->post('Alokasi'))) {
			echo 'no';
			die;
		}

		if ($this->input->post('Versi_tipe') == 'H') {
			echo 'versi_lama';
			exit;
		}
		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$Versi = $this->input->post('Versi', TRUE);
		$Versi_tipe = $this->input->post('Versi_tipe', TRUE);

		$data = array(
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,
			'Versi' => $Versi,
			'Versi_tipe' => $Versi_tipe,
		);

		$dapat_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'  ")->row_array();
		$list_header = $this->db->query("SELECT KdRAP,Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header'")->result_array();
		$dapat_alokasi = $this->db->query("SELECT Keterangan FROM Alokasi WHERE Alokasi='$Alokasi'  ")->row_array();


		$data['JobNm'] = $dapat_job['JobNm'];
		$data['Keterangan'] = $dapat_alokasi['Keterangan'];
		$data['header_rap'] = $list_header;

		$data['Versi'] = $Versi;


		$this->load->view('entry_master/RAP/form-tambah-versi', $data);
	}

	function aksi_tambah_versi(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$Versi = $this->input->post('Versi', TRUE);

		$Perubahan = $this->input->post('Perubahan',TRUE);
		$VersiTerkini = $this->input->post('VersiTerkini',TRUE);
		$RubahMenjadiVersi = $this->input->post('RubahMenjadiVersi',TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');


		// echo $RubahMenjadiVersi;
		// exit;

		$update = $this->ubah_versi($JobNo,$Alokasi,$Versi,$RubahMenjadiVersi);
		if ($update == TRUE) {
			is_pesan('success','berhasil membuat versi terbaru');
			echo 'berhasil';
			exit;
		}
		is_pesan('error','gagal membuat versi terbaru');
		echo 'gagal';
		exit;

		// $this->db->query("
		// 	INSERT INTO RAPH (JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,UserEntry,TimeEntry,Tahun,Jenis)
		// 	SELECT JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,'$UserEntry','$TimeEntry',Tahun,Jenis FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi'
		// 	");

		// $this->db->query("UPDATE RAP SET Versi='$RubahMenjadiVersi' WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' ");

		// echo $this->db->affected_rows();


		// print_rr($_POST);
	}

	private function ubah_versi($JobNo,$Alokasi,$Versi,$RubahMenjadiVersi){

		$this->db->trans_begin();

        
		$this->db->query("
			INSERT INTO RAPH (JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,UserEntry,TimeEntry,Tahun,Jenis)
			SELECT JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,'$UserEntry','$TimeEntry',Tahun,Jenis FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi'
			");

		$this->db->query("UPDATE RAP SET Versi='$RubahMenjadiVersi' WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' ");


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }

	}




	
}

/* End of file ENT_RAP.php */
	/* Location: ./application/controllers/Entry_master/ENT_RAP.php */