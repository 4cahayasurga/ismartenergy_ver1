<?php
defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(0);
use \Mpdf\Mpdf;
require_once APPPATH . 'third_party/libraryExcel/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';

class ENT_RAP extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		is_login();

		redirect('Entry/RAPFrm');



		$this->load->model('Entry_model/Rap_model');
		if (!$this->db->field_exists('LedgerNo', 'RAP'))
		{
			echo 'Field Ledger tidak ada';
			die;
		}

		if (!$this->db->field_exists('PPN', 'RAP'))
		{
			$this->db->query("ALTER TABLE RAP ADD PPN money ");
		}

		// DITAMBAHKAN PADA TANGGAL 18 AGUSTUS 2023

		if (!$this->db->field_exists('PPN_New_Persen', 'RAP'))
		{
			$this->db->query("ALTER TABLE RAP ADD PPN_New_Persen int(2) NULL ");
		}

		if (!$this->db->field_exists('PPN_New_Nominal', 'RAP'))
		{
			$this->db->query("ALTER TABLE RAP ADD PPN_New_Nominal money NULL ");
		}

		if (!$this->db->field_exists('PPN_New_Checklist', 'RAP'))
		{
			$this->db->query("ALTER TABLE RAP ADD PPN_New_Checklist char(1) NULL DEFAULT((0)) ");
		}

		if (!$this->db->field_exists('Jumlah_HrgSatuan_kali_Vol', 'RAP'))
		{
			$this->db->query("ALTER TABLE RAP ADD Jumlah_HrgSatuan_kali_Vol money NULL ");
		}

		if (!$this->db->field_exists('Total_Jumlah_Tambah_PPN_Nominal', 'RAP'))
		{
			$this->db->query("ALTER TABLE RAP ADD Total_Jumlah_Tambah_PPN_Nominal money NULL ");
		}


		// END DITAMBAHKAN PADA TANGGAL 18 AGUSTUS 2023


		// -- ADD DATE : 07 DESEMBER 2023
		if (!$this->db->field_exists('PPN_New_Persen', 'RAPH'))
		{
			$this->db->query("ALTER TABLE RAPH ADD PPN money ");
			$this->db->query("ALTER TABLE RAPH ADD PPN_New_Persen char(2) NULL ");
			$this->db->query("ALTER TABLE RAPH ADD PPN_New_Nominal money NULL ");
			$this->db->query("ALTER TABLE RAPH ADD PPN_New_Checklist char(1) NULL DEFAULT((0)) ");
			$this->db->query("ALTER TABLE RAPH ADD Jumlah_HrgSatuan_kali_Vol money NULL ");
			$this->db->query("ALTER TABLE RAPH ADD Total_Jumlah_Tambah_PPN_Nominal money NULL ");
		}
		// -- END DATE : 07 DESEMBER 2023

	}

	function index($JobNo='')
	{

		// if ($JobNo!='') {
		// 	redirect('Entry/RAPFrm/index/'.$JobNo);
		// }else{
		// 	redirect('Entry/RAPFrm')
		// }

		// if($JobNo == ''){
		// 	redirect('welcome/index','refresh');
		// 	exit;
		// }

		// if (isset($_SESSION['failed'])) {
		// 	echo $_SESSION['failed'];
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

				SET @SubTotalRAP = (SELECT SUM(HrgSatuan * Vol) as SubTotalRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header' AND Header='0' AND Versi='$Versi')
				SET @SubTotalRAB = (SELECT SUM(HrgRAB * Vol) as SubTotalRAB FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header' AND Header='0' AND Versi='$Versi')

				SELECT 
				*,
				IIF(Tipe='Header',NULL,HrgSatuan) as HrgSatuanRAP_Custom,
				IIF(Tipe='Header',NULL,HrgRAB) as HrgSatuanRAB_Custom,

				Vol * HrgSatuan as JumlahHargaRAP,


				Vol * HrgRAB as JumlahHargaRAB,


				@SubTotalRAP as SubTotalRAP,
				@SubTotalRAB as SubTotalRAB

				FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi'   ORDER BY NoUrut ASC


				");
		} else {
			$query_dapat_rap = $this->db->query("
				declare @SubTotalRAP money
				declare @SubTotalRAB money
				SET @SubTotalRAP = '0'
				SET @SubTotalRAB = '0'

				SET @SubTotalRAP = (SELECT SUM(HrgSatuan * Vol) as SubTotalRAP FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' AND Tipe='Header' AND Header='0' AND Versi='$Versi')
				SET @SubTotalRAB = (SELECT SUM(HrgRAB * Vol) as SubTotalRAB FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' AND Tipe='Header' AND Header='0' AND Versi='$Versi')

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

		$data['jumlah_baris'] = $query_dapat_rap->num_rows();
		$data['mydata'] = $query_dapat_rap->result_array();

		if ($cek_tipe_versi=='P') {
			$SUM_RAP = $this->db->query("SELECT SUM(HrgSatuan*Vol) AS 'SUMRAP', SUM(HrgRAB*Vol) AS 'SUMRAB', SUM(PPN_New_Nominal*Vol) AS 'SUMPPN' FROM RAP WHERE JobNo='$JobNo'  AND Alokasi='$Alokasi' AND Tipe !='Header' AND Versi='$Versi' ")->row_array();
		}else{
			$SUM_RAP = $this->db->query("SELECT SUM(HrgSatuan*Vol) AS 'SUMRAP', SUM(HrgRAB*Vol) AS 'SUMRAB', SUM(PPN_New_Nominal*Vol) AS 'SUMPPN' FROM RAPH WHERE JobNo='$JobNo'  AND Alokasi='$Alokasi' AND Tipe !='Header' AND Versi='$Versi' ")->row_array();
		}

		// print_rr($SUM_RAP);
		// die;

		
		$data['SUM_RAP'] = $SUM_RAP;


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

		// print_rr($Versi);
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

				vol * PPN_New_Nominal as JumlahPPN,


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

		// print_rr($_POST);
		// exit;

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$Tipe = $this->input->post('Tipe',TRUE);
		$Header = $this->input->post('Header',TRUE);
		$KdRAP = $this->input->post('KdRAP',TRUE);
		$Uraian = $this->input->post('Uraian',TRUE);
		$Uom = $this->input->post('Uom',TRUE);
		$Vol = ($this->input->post('Vol') == '' || $this->input->post('Vol') == NULL ) ? 0 : $this->input->post('Vol',TRUE);
		$HrgSatuan= ($this->input->post('HrgSatuan') == '' || $this->input->post('HrgSatuan') == NULL ) ? 0 : $this->input->post('HrgSatuan',TRUE);
		// $PPN = $this->input->post('PPN',TRUE);
		$HrgRAB = ($this->input->post('HrgRAB') == '' || $this->input->post('HrgRAB') == NULL ) ? 0 : $this->input->post('HrgRAB',TRUE);

		$Versi = $this->input->post('Versi',TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');


		$PPN_New_Nominal = ($this->input->post('PPN_New_Nominal') == '' || $this->input->post('PPN_New_Nominal') == NULL ) ? 0 : $this->input->post('PPN_New_Nominal',TRUE);
		$PPN_New_Checklist = ($this->input->post('PPN_New_Checklist')) ? 1 : 0;

		$Jumlah_HrgSatuan_kali_Vol = ($this->input->post('Jumlah_HrgSatuan_kali_Vol') == '' || $this->input->post('Vol') == NULL ) ? 0 : $this->input->post('Jumlah_HrgSatuan_kali_Vol',TRUE);
		$Total_Jumlah_Tambah_PPN_Nominal = ($this->input->post('Total_Jumlah_Tambah_PPN_Nominal') == '' || $this->input->post('Total_Jumlah_Tambah_PPN_Nominal') == NULL ) ? 0 : $this->input->post('Total_Jumlah_Tambah_PPN_Nominal',TRUE);

		$PPN_New_Persen = 11;

		$searchForValue = ',';

		if( strpos($HrgSatuan, $searchForValue) !== false ) {
			$HrgSatuan = str_replace(",", "", $HrgSatuan);
		}

		if (is_numeric($HrgSatuan) !=1) {
			$HrgSatuan = 0;
		}

		if( strpos($Vol, $searchForValue) !== false ) {
			$Vol = str_replace(",", "", $Vol);
		}

		if (is_numeric($Vol) !=1) {
			$Vol = 0;
		}


		if( strpos($PPN_New_Nominal, $searchForValue) !== false ) {
			$PPN_New_Nominal = str_replace(",", "", $PPN_New_Nominal);
		}

		if (is_numeric($PPN_New_Nominal) !=1) {
			$PPN_New_Nominal = 0;
		}

		if( strpos($Jumlah_HrgSatuan_kali_Vol, $searchForValue) !== false ) {
			$Jumlah_HrgSatuan_kali_Vol = str_replace(",", "", $Jumlah_HrgSatuan_kali_Vol);
		}

		if (is_numeric($Jumlah_HrgSatuan_kali_Vol) !=1) {
			$Jumlah_HrgSatuan_kali_Vol = 0;
		}

		if( strpos($Total_Jumlah_Tambah_PPN_Nominal, $searchForValue) !== false ) {
			$Total_Jumlah_Tambah_PPN_Nominal = str_replace(",", "", $Total_Jumlah_Tambah_PPN_Nominal);
		}

		if (is_numeric($Total_Jumlah_Tambah_PPN_Nominal) !=1) {
			$Total_Jumlah_Tambah_PPN_Nominal = 0;
		}



		

		if($Tipe == 'Header'){
			$Vol = 1;
			$HrgSatuan = 0;
			$HrgRAB = 0;
		}

		// == MENDAPATAKAN NOMOR URUT ==
		$NoUrut = 1;
		$QueryNoUrut = $this->db->query("SELECT TOP 1 NoUrut FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut DESC ")->row();
		if (isset($QueryNoUrut)) {
			$NoUrut = (int) $QueryNoUrut->NoUrut;
			$NoUrut = $NoUrut + 1;
		}else{
			$NoUrut = 1;
		}
		// if ($QueryNoUrut->num_rows()<1 ) {
		// 	$NoUrut=1;
		// }else{
		// 	$NoUrut = (int) $QueryNoUrut->NoUrut();
		// 	$NoUrut++;
		// }
		// echo $NoUrut;
		// exit;

		$data = array(
			'Tipe' => $Tipe,
			'NoUrut' => $NoUrut,
			'Header' => $Header,
			'KdRAP' => $KdRAP,
			'Uraian' => $Uraian,
			'Uom' => $Uom,
			'Vol' => $Vol,
			'HrgSatuan' => $HrgSatuan,
			'HrgRAB' => $HrgRAB,
			'Versi' => $Versi,
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry
		);

		// print_rr($data);
		// // die;

		if ($Tipe == 'Detail') {
			$data['PPN_New_Persen'] = $PPN_New_Persen;
			$data['PPN_New_Nominal'] = $PPN_New_Nominal;
			$data['PPN_New_Checklist'] = $PPN_New_Checklist;
			$data['Jumlah_HrgSatuan_kali_Vol'] = $Jumlah_HrgSatuan_kali_Vol;
			$data['Total_Jumlah_Tambah_PPN_Nominal'] = $Total_Jumlah_Tambah_PPN_Nominal;

		}

		if($this->cek_KdRAP($JobNo,$Alokasi,$KdRAP) == 'ada'){
			echo 'rap_sudah_ada';
			exit;
		}

		if($Header == '0' && $Tipe == 'Detail') {
			echo 'detail_dilarang_top_header';
			exit;
		}

		$simpan_RAP = $this->Rap_model->aksi_simpan_RAP_baru($data);

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

		// $Vol = $this->input->post('Vol',TRUE);
		// $HrgSatuan = $this->input->post('HrgSatuan',TRUE);
		// $HrgRAB = $this->input->post('HrgRAB',TRUE);

		// $Vol = str_replace("," , "" , $Vol);
		// $HrgSatuan = str_replace("," , "" , $HrgSatuan);
		// $HrgRAB = str_replace("," , "" , $HrgRAB);

		// $data = array(
		// 	'Vol' => $Vol,
		// 	'HrgSatuan' => $HrgSatuan,
		// 	'HrgRAB' => $HrgRAB,
		// );
		// print_rr($data);
		// exit;

		// echo $this->


		
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


		// -- DI TAMBAHKAN PPN BARU - PERINTAH PAK MULL, 07 DESEMBER 2023

		$this->db->query("
			INSERT INTO RAPH (JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,UserEntry,TimeEntry,Tahun,Jenis,PPN,PPN_New_Persen,PPN_New_Nominal,PPN_New_Checklist,Jumlah_HrgSatuan_kali_Vol,Total_Jumlah_Tambah_PPN_Nominal)
			SELECT JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,'$UserEntry','$TimeEntry',Tahun,Jenis,PPN,PPN_New_Persen,PPN_New_Nominal,PPN_New_Checklist,Jumlah_HrgSatuan_kali_Vol,Total_Jumlah_Tambah_PPN_Nominal FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi'
			");

		// $this->db->query("
		// 	INSERT INTO RAPH (JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,UserEntry,TimeEntry,Tahun,Jenis)
		// 	SELECT JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,'$UserEntry','$TimeEntry',Tahun,Jenis FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi'
		// 	");
		// -- DI TAMBAHKAN PPN BARU - PERINTAH PAK MULL, 07 DESEMBER 2023

		$this->db->query("UPDATE RAP SET Versi='$RubahMenjadiVersi' WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' ");


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}

	}

	function atur_posisi_RAP(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$Versi = $this->input->post('Versi', TRUE);

		$TipeBtn = $this->input->post('TipeBtn', TRUE);
		$NoUrut = $this->input->post('NoUrut', TRUE);
		$KdRAP = $this->input->post('KdRAP', TRUE);



		if ($TipeBtn == 'BtnUP') {
			$NoUrutAtas = $this->input->post('NoUrutAtas', TRUE);
			$KdRAPAtas = $this->input->post('KdRAPAtas', TRUE);
			$NoUrutAtas_ubah_ke = $this->input->post('NoUrutAtas_ubah_ke', TRUE);

			$this->db->trans_begin();


			$this->db->query("UPDATE RAP SET NoUrut='$NoUrutAtas_ubah_ke' WHERE JobNo='$JobNo' AND KdRAP='$KdRAPAtas'  ");
			$this->db->query("UPDATE RAP SET NoUrut='$NoUrutAtas' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP'  ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo 'gagal';
			} else {
				$this->db->trans_commit();
				echo 'berhasil';
			}

			exit;

		}

		if ($TipeBtn == 'BtnDown') {

			$NoUrut_ingin_turun = $this->input->post('NoUrut_ingin_turun', TRUE);
			$KdRAP_ingin_turun = $this->input->post('KdRAP_ingin_turun', TRUE);
			$NoUrut_turun_ke = $this->input->post('NoUrut_turun_ke', TRUE);

			$NoUrut_dibawahnya = $this->input->post('NoUrut_dibawahnya', TRUE);
			$KdRAP_dibawahnya = $this->input->post('KdRAP_dibawahnya', TRUE);

			$this->db->trans_begin();


			$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_turun_ke' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_ingin_turun'  ");
			$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_ingin_turun' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_dibawahnya'  ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo 'gagal';
			} else {
				$this->db->trans_commit();
				echo 'berhasil';
			}
			
			exit;


		}


	}


	function btn_export_excel(){
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


		$this->load->view('entry_master/RAP/form_import_excel', $data);
	}


	function dapatkan_TemplateExcel(){	

		$this->load->helper('download');
		$file = 'bahan_import_excel/Template_Excel_RAP.xlsx';
		force_download($file, NULL);

	}

	function cobacoba(){

		if (empty($this->input->post('JobNo')) || empty($this->input->post('Alokasi'))) {
			is_pesan('error','terjadi kesalahan');
			die;
		}

		if ($this->input->post('Versi_tipe') == 'H') {
			is_pesan('error','Hanya bisa import untuk RAP Versi Terbaru');
			exit;
		}

		if (!isset($_FILES["BahanExcel"]["name"])) {
			is_pesan('error','terjadi kesalahan');
			exit;
		}

		$file = $_FILES["BahanExcel"]["tmp_name"];
		$objPHPExcel = PHPExcel_IOFactory::load($file); 

		$totalrow = $objPHPExcel->getActiveSheet()->getHighestRow();		
		$inserted = 0;
		$failed = array();
		$data = array();

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$Versi = $this->input->post('Versi', TRUE);
		$Versi_tipe = $this->input->post('Versi_tipe', TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		

		for($row=2; $row<=$totalrow; $row++){

			$JobNo = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();
			$KdRAP = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
			$Uraian = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
			$Tipe = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue();
			$Header = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();
			$Header =  (string) $Header;
			$Uom = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();
			$Vol = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue();
			$HrgSatuan = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue();
			$HrgRAB = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $row)->getValue();

			$NomorExcel = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $row)->getValue();


			if($Tipe == 'Header'){
				$Vol = 1;
				$HrgSatuan = 0;
				$HrgRAB = 0;
			}

			if($this->cek_KdRAP($JobNo,$Alokasi,$KdRAP) == 'ada'){
				$failed[] = 'Kode RAP sudah terpakai (Nomor : '.$NomorExcel.')'  ;
				break;
			}

			$data[] = array(
				'JobNo' => $JobNo,
				'KdRAP' => $KdRAP,
				'Versi' => $Versi,
				'NoUrut' => $NomorExcel,
				'Uraian' => $Uraian,
				'Alokasi' => $Alokasi,
				'Tipe' => $Tipe,
				'Header' => $Header,
				'Uom' => $Uom,
				'Vol' => $Vol,
				'HrgSatuan' => $HrgSatuan,
				'HrgRAB' => $HrgRAB,
				'UserEntry' => $UserEntry,
				'TimeEntry' => $TimeEntry,
			);

			

			$inserted++;
		}

		if (count($failed) > 0) {
			$_SESSION['failed'] = implode($failed, "<br/>");
			exit;
		}
		is_pesan('success',' (<b>'.$inserted.'</b>) berhasil di import  ');
		

		$this->db->trans_start();
		$this->db->insert_batch('RAP',$data);
		$this->db->trans_complete();

	} 


	
}

/* End of file ENT_RAP.php */
/* Location: ./application/controllers/Entry_master/ENT_RAP.php */
