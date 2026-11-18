<?php
error_reporting(E_ALL);
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FrmRPPM_C extends CI_Controller
{

	var $Company;
	var $UserID;
	var $UserName;

	var $JobNo_Global;
	var $Alokasi_Global;
	var $Minggu_Global;

	public function __construct()
	{
		parent::__construct();
		is_login();
		PenambahanField_TglPengakuan_Di_RPPM_tbl();
		if ($this->session->userdata('PecahToken')->RPPM != 1) {
			echo 'akses di-tolak';
			die;
		}

		$this->Company = $this->config->item('Company');
		$this->UserID = $this->session->userdata('MIS_LOGGED_ID');
		$this->UserName = $this->session->userdata('MIS_LOGGED_NAME');
		Create_RPPM_tbl();

		if ($this->session->userdata('Sesi_RPPM_tbl')) {
			$this->JobNo_Global = explode('|', $this->session->userdata('Sesi_RPPM_tbl'))[0];
			$this->Alokasi_Global = explode('|', $this->session->userdata('Sesi_RPPM_tbl'))[1];
			$this->Minggu_Global = explode('|', $this->session->userdata('Sesi_RPPM_tbl'))[2];
		}

		// -- PERMINTAAN PAK MUL, 11 DESEMBER 2023
		PembuatanField_TotalHargaKontrak_Satuan();

		// -- DITAMBAHKAN pada tanggal : 12 DESEMBER 2023.
		PenambahanField_RPPMHistory_To_Login();

		// ubah decimal bobot persen dari 18,3 menjadi 18,5
		// $this->db->query("ALTER TABLE RPPM_tbl ADD Bobot_Persen [decimal](10, 3) NULL");

	}

	public function index($JobNo = '', $Alokasi = '')
	{



		$JobNo = ($JobNo == '') ? $this->JobNo_Global : $JobNo;
		$Alokasi = ($Alokasi == '') ? $this->Alokasi_Global : $Alokasi;

		$data['judul'] = 'Form Entry RPPM (Realisasi Pelaksanaan Progress Mingguan)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['ListJob'] = $this->GetJob();
		$data['JobNo'] = $JobNo;

		$data['ListAlokasi'] = $this->GetAlokasi();
		$data['Alokasi'] = $Alokasi;

		$data['js_saya'] = 'BasicData_View/FrmRPPM_View/js_RPPM';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmRPPM_View/halaman_utama', $data);
		$this->load->view('templates/footer');
	}

	function table_RPPM()
	{
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$JobNm = $this->input->post('JobNm', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$KeteranganAlokasi = $this->input->post('Keterangan', TRUE);
		$Minggu = $this->input->post('Minggu', TRUE);
		$MingguKe = ($Minggu == '') ? 0 : (int) $Minggu;
		$PrdAwal = ($this->input->post('PrdAwal', TRUE)) ? $this->input->post('PrdAwal', TRUE) : '';
		$PrdAkhir = ($this->input->post('PrdAkhir', TRUE)) ? $this->input->post('PrdAkhir', TRUE) : '';

		$flag_tab = $this->input->post('flag_tab');


		$data['flag_tab_Main'] = ($flag_tab == '') ? 'active' : '';
		$data['flag_tab_History'] = ($flag_tab == '1') ? 'active' : '';

		$Sesi_RPPM_tbl = $JobNo . '|' . $Alokasi . '|' . $MingguKe;
		$this->session->set_userdata('Sesi_RPPM_tbl', $Sesi_RPPM_tbl);

		$MyQuery = $this->db->query("

			DECLARE @Minggu int;
			DECLARE @JobNo nvarchar(10);
			DECLARE @Alokasi nvarchar(1);

			SET @Minggu=$MingguKe;
			SET @JobNo='$JobNo';
			SET @Alokasi='$Alokasi';
			SELECT 

			ISNULL(RAP.NoUrut,0) AS NoUrut,
			ISNULL(RAP.JobNo,NULL) AS JobNo,
			ISNULL(RAP.Alokasi,NULL) AS Alokasi,
			ISNULL(RAP.Header,NULL) AS Header,
			ISNULL(RAP.Tipe,NULL) AS Tipe,
			ISNULL(RAP.KdRAP,NULL) AS KdRAP,
			ISNULL(RAP.Uraian,NULL) AS Uraian,
			ISNULL(RAP.Uom,NULL) AS Uom,
			ISNULL(RAP.Vol,0) AS Vol,
			ISNULL(RAP.HrgRAB,0) AS HrgRAB,

			-- START MINGGU YANG LALU --
			ISNULL((SELECT ISNULL(SUM(VolumeInputan),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu<@Minggu GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian),0) AS 'Minggu_YangLalu',
			-- END MINGGU YANG LALU --

			-- START Bobot Persen YANG LALU --
			ISNULL((SELECT ISNULL(SUM(Bobot_Persen),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu<@Minggu GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian),0) AS 'BobotPersen_YangLalu',
			-- END Bobot Persen YANG LALU --

			-- START MINGGU SAAT INI --
			ISNULL((SELECT ISNULL(SUM(VolumeInputan),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu=@Minggu GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian),0) AS 'Minggu_SaatIni',
			-- END MINGGU SAAT INI --

			-- START Bobot Persen SAAT INI --
			ISNULL((SELECT ISNULL(SUM(Bobot_Persen),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu=@Minggu GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian),0) AS 'BobotPersen_SaatIni',
			-- END Bobot Persen SAAT INI --

			-- START MINGGU SAMPAI DENGAN SEKARANG --
			ISNULL
			(
				(SELECT ISNULL
					(
						SUM(VolumeInputan),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu BETWEEN 0 AND @Minggu  GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian
					)
				,0) AS 'Minggu_SampaiDgnSekarang',
			-- END MINGGU SEKARANG --

			-- START Bobot Persen SAMPAI DENGAN SEKARANG --
			ISNULL
			(
				(SELECT ISNULL
					(
						SUM(Bobot_Persen),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu BETWEEN 0 AND @Minggu   GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian
					)
				,0) AS 'BobotPersen_SampaiDgnSekarang'
			-- END Bobot Persen SEKARANG --
			FROM RAP 
			WHERE RAP.JobNo=@JobNo AND RAP.Alokasi=@Alokasi
			ORDER BY RAP.NoUrut ASC
			");

		$ArrayBuatan = array();

		$sumFooter_Vol = 0;
		$sumFooter_HrgSatuan = 0;
		$sumFooter_TtlKontrak = 0;

		$sumFooter_VolLalu = 0;
		$sumFooter_BobotLalu = 0;

		$sumFooter_VolSkrg = 0;
		$sumFooter_BobotSkrg = 0;

		$sumFooter_VolsdSkrg = 0;
		$sumFooter_BobotsdSkrg = 0;

		// -- DITAMBAHKAN TANGGAL : 12 DES 2023;
		$sumFooter_TtlKontrak_NEW = 0;
		foreach ($MyQuery->result() as $row) {
			$Vol = ($row->Vol == '' || $row->Vol == NULL) ? 0 : (float) $row->Vol;
			$HrgRAB = ($row->HrgRAB == '' || $row->HrgRAB == NULL) ? 0 : (float) $row->HrgRAB;

			if ($row->Tipe == 'Header') {
				continue;
			}

			$sumFooter_TtlKontrak_NEW = $sumFooter_TtlKontrak_NEW + ($Vol * $HrgRAB);
		}
		$data['sumFooter_TtlKontrak'] = $sumFooter_TtlKontrak_NEW;



		foreach ($MyQuery->result() as $row) {

			$BobotRAB = 0;

			$Vol = ($row->Vol == '' || $row->Vol == NULL) ? 0 : (float) $row->Vol;
			$HrgRAB = ($row->HrgRAB == '' || $row->HrgRAB == NULL) ? 0 : (float) $row->HrgRAB;

			$JumlahHarga = 0;
			$JumlahHarga = $HrgRAB * $Vol;


			$BobotRAB = $this->calculate_percentage_for_display($JumlahHarga, $sumFooter_TtlKontrak_NEW);


			$Minggu_YangLalu = ($row->Minggu_YangLalu == '' || $row->Minggu_YangLalu == NULL) ? 0 : (float) $row->Minggu_YangLalu;
			$BobotPersen_YangLalu = ($row->BobotPersen_YangLalu == '' || $row->BobotPersen_YangLalu == NULL) ? 0 : (float) $row->BobotPersen_YangLalu;

			$Minggu_SaatIni = ($row->Minggu_SaatIni == '' || $row->Minggu_SaatIni == NULL) ? 0 : (float) $row->Minggu_SaatIni;
			$BobotPersen_SaatIni = ($row->BobotPersen_SaatIni == '' || $row->BobotPersen_SaatIni == NULL) ? 0 : (float) $row->BobotPersen_SaatIni;

			$Minggu_SampaiDgnSekarang = ($row->Minggu_SampaiDgnSekarang == '' || $row->Minggu_SampaiDgnSekarang == NULL) ? 0 : (float) $row->Minggu_SampaiDgnSekarang;
			$BobotPersen_SampaiDgnSekarang = ($row->BobotPersen_SampaiDgnSekarang == '' || $row->BobotPersen_SampaiDgnSekarang == NULL) ? 0 : (float) $row->BobotPersen_SampaiDgnSekarang;

			$sumFooter_Vol += $Vol;

			$sumFooter_HrgSatuan += $HrgRAB;
			$sumFooter_TtlKontrak += $HrgRAB * $Vol;


			$sumFooter_VolLalu += $Minggu_YangLalu;
			$sumFooter_BobotLalu += $BobotPersen_YangLalu;

			$sumFooter_VolSkrg += $Minggu_SaatIni;
			$sumFooter_BobotSkrg += $BobotPersen_SaatIni;

			$sumFooter_VolsdSkrg += $Minggu_SampaiDgnSekarang;
			$sumFooter_BobotsdSkrg += $BobotPersen_SampaiDgnSekarang;

			$ArrayBuatan[] = (object) array(
				'NoUrut' => $row->NoUrut,
				'JobNo' => $row->JobNo,
				'Alokasi' => $row->Alokasi,
				'KdRAP' => $row->KdRAP,
				'Uraian' => $row->Uraian,
				'Uom' => $row->Uom,
				'Vol' => $Vol,
				'HrgRAB' => $HrgRAB,
				'JumlahHarga' => $JumlahHarga,

				'Minggu_YangLalu' => $Minggu_YangLalu,
				'BobotPersen_YangLalu' => $BobotPersen_YangLalu,

				'Minggu_SaatIni' => $Minggu_SaatIni,
				'BobotPersen_SaatIni' => $BobotPersen_SaatIni,

				'Minggu_SampaiDgnSekarang' => $Minggu_SampaiDgnSekarang,
				'BobotPersen_SampaiDgnSekarang' => $BobotPersen_SampaiDgnSekarang,

				'Header' => $row->Header,

				'Tipe' => $row->Tipe,
				'BobotRAB' => $BobotRAB,
			);
		}


		$data['dataRAP'] = $ArrayBuatan;


		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $JobNm;
		$data['Alokasi'] = $Alokasi;
		$data['MingguKe'] = $MingguKe;
		$data['PrdAwal'] = $PrdAwal;
		$data['PrdAkhir'] = $PrdAkhir;
		$data['KeteranganAlokasi'] = $KeteranganAlokasi;

		$data['sumFooter_Vol'] = $sumFooter_Vol;

		$data['sumFooter_HrgSatuan'] = $sumFooter_HrgSatuan;

		// $data['sumFooter_TtlKontrak'] = $sumFooter_TtlKontrak;

		$data['sumFooter_VolLalu'] = $sumFooter_VolLalu;
		$data['sumFooter_BobotLalu'] = $sumFooter_BobotLalu;

		$data['sumFooter_VolSkrg'] = $sumFooter_VolSkrg;
		$data['sumFooter_BobotSkrg'] = $sumFooter_BobotSkrg;

		$data['sumFooter_VolsdSkrg'] = $sumFooter_VolsdSkrg;
		$data['sumFooter_BobotsdSkrg'] = $sumFooter_BobotsdSkrg;

		// print_rr($data);
		// die;
		$HistoryData = $this->db->query("SELECT * FROM RPPM_tbl WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY LedgerNo DESC ")->result();

		// $HistoryData = $this->DataHistory($JobNo,$Alokasi);
		$data['HistoryData'] = $HistoryData;
		// print_rr($data['HistoryData']);
		// die;

		$sum_Bobot_Persen 	= 0;
		$sum_VolumeInputan 	= 0;
		foreach ($HistoryData as $myrow) {
			$Bobot_Persen = ($myrow->Bobot_Persen == '' || $myrow->Bobot_Persen == NULL) ? 0 : (float) $myrow->Bobot_Persen;
			$VolumeInputan = ($myrow->VolumeInputan == '' || $myrow->VolumeInputan == NULL) ? 0 : (float) $myrow->VolumeInputan;

			$sum_Bobot_Persen += $Bobot_Persen;
			$sum_VolumeInputan += $VolumeInputan;
		}


		$data['sum_Bobot_Persen'] = UbahDecimal_Nol_Tiga($sum_Bobot_Persen);
		$data['sum_VolumeInputan'] = UbahDecimal_Nol_Tiga($sum_VolumeInputan);

		$data['RPPMHistory_Akses'] = "";
		if ($this->session->userdata('PecahToken')->RPPMHistory != 1) {
			$data['RPPMHistory_Akses'] = 'style="display:none;';
		} else {
			$data['RPPMHistory_Akses'] = "";
		}
		$this->load->view('BasicData_View/FrmRPPM_View/table_RPPM', $data);
	}
	function GetBobotRAB($JobNo = '', $Alokasi = '')
	{
		$ListRAP = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC ")->result();
		$TtlRAB = 0;
		$TtlRAP = 0;
		foreach ($ListRAP as $row_hitung) {

			// if ($row_hitung->Tipe=='Header') {
			// 	continue;
			// }

			$Vol = ($row_hitung->Vol == '' || $row_hitung->Vol == NULL) ? 0 : (float) $row_hitung->Vol;
			$HrgSatuan = ($row_hitung->HrgSatuan == '' || $row_hitung->HrgSatuan == NULL) ? 0 : (float) $row_hitung->HrgSatuan;
			$HrgRAB = ($row_hitung->HrgRAB == '' || $row_hitung->HrgRAB == NULL) ? 0 : (float) $row_hitung->HrgRAB;

			$TtlRAP = $TtlRAP + ($Vol * $HrgSatuan);
			$TtlRAB = $TtlRAB + ($Vol * $HrgRAB);

			$TotalHargaKontrak = $Vol * $HrgRAB;
			$BobotRAB = $this->calculate_percentage_for_display($TotalHargaKontrak, $TtlRAB);
		}

		foreach ($ListRAP as $myrow) {
			$Vol = ($myrow->Vol == '' || $myrow->Vol == NULL) ? 0 : (float) $myrow->Vol;
			$HrgRAB = ($myrow->HrgRAB == '' || $myrow->HrgRAB == NULL) ? 0 : (float) $myrow->HrgRAB;
			$TotalHargaKontrak = $Vol * $HrgRAB;
			$BobotRAB = $this->calculate_percentage_for_display($TotalHargaKontrak, $TtlRAB);
		}

		return $BobotRAB;
	}
	function GetBobotRAB2()
	{
		// print_rr($_POST);
		// die;
		$KdRAP = $this->input->post('KdRAP');
		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$query = $this->db->query("SELECT ISNULL(Tipe,NULL) AS Tipe, ISNULL(Vol,0) as Vol, ISNULL(HrgRAB,0) AS HrgRAB, ISNULL(HrgSatuan,0) AS HrgSatuan FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP='$KdRAP' ");
		$ListRAP = $query->row();

		$TtlRAB = 0;

		$Vol = ($ListRAP->Vol == '' || $ListRAP->Vol == NULL) ? 0 : (float) $ListRAP->Vol;
		$HrgSatuan = ($ListRAP->HrgSatuan == '' || $ListRAP->HrgSatuan == NULL) ? 0 : (float) $ListRAP->HrgSatuan;
		$HrgRAB = ($ListRAP->HrgRAB == '' || $ListRAP->HrgRAB == NULL) ? 0 : (float) $ListRAP->HrgRAB;

		$TtlRAP = $Vol * $HrgSatuan;
		$TtlRAB = $Vol * $HrgRAB;

		$PerulanganRAP = $this->db->query("SELECT ISNULL(Tipe,NULL) AS Tipe, ISNULL(Vol,0) as Vol, ISNULL(HrgRAB,0) AS HrgRAB FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC ");
		$get_sumHrgRAB_KaliVol = 0;
		if ($PerulanganRAP->num_rows() > 0) {
			foreach ($PerulanganRAP->result() as $myrow) {

				if ($myrow->Tipe == 'Header') {
					continue;
				}
				$myVol = CekNull_Angka_Dan_Bikin_Float($myrow->Vol);
				$myHrgRAB = CekNull_Angka_Dan_Bikin_Float($myrow->HrgRAB);

				$get_sumHrgRAB_KaliVol += $myVol * $myHrgRAB;
			}
		}
		$BobotRAB = $this->calculate_percentage_for_display($TtlRAB, $get_sumHrgRAB_KaliVol);
		$data = [
			'VolRAP' => $Vol,
			'BobotRAB' => $BobotRAB,
			// 'BobotRAB' => $TtlRAB,
			'HargaSatuan' => $HrgSatuan,
			'HargaRAB' => $HrgRAB,
			'TotalHargaKontrak' => $Vol * $HrgRAB,
			'TotalHargaKontrakSatuan' => $Vol * $HrgSatuan,
		];
		echo json_encode($data);
	}

	function DataHistory($JobNo, $Alokasi)
	{

		$HistoryData = $this->db->query("SELECT * FROM RPPM_tbl WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY LedgerNo DESC ");
		$ArrayBuatan = array();

		if ($HistoryData->num_rows() > 0) {
			foreach ($HistoryData->result() as $row) {

				$JobNo 			= ($row->JobNo == '' || $row->JobNo == NULL) ? NULL : $row->JobNo;
				$KdRAP 			= ($row->KdRAP == '' || $row->KdRAP == NULL) ? NULL : $row->KdRAP;
				$Versi 			= ($row->Versi == '' || $row->Versi == NULL) ? NULL : $row->Versi;
				$NoUrut 		= ($row->NoUrut == '' || $row->NoUrut == NULL) ? 0 : $row->NoUrut;
				$Uraian 		= ($row->Uraian == '' || $row->Uraian == NULL) ? NULL : $row->Uraian;
				$Alokasi 		= ($row->Alokasi == '' || $row->Alokasi == NULL) ? NULL : $row->Alokasi;
				$Tipe 			= ($row->Tipe == '' || $row->Tipe == NULL) ? NULL : $row->Tipe;
				$Header 		= ($row->Header == '' || $row->Header == NULL) ? NULL : $row->Header;
				$Uom 			= ($row->Uom == '' || $row->Uom == NULL) ? NULL : $row->Uom;

				$Vol 			= ($row->Vol == '' || $row->Vol == NULL) ? 0 : (float) $row->Vol;
				$HrgSatuan 		= ($row->HrgSatuan == '' || $row->HrgSatuan == NULL) ? 0 : (float) $row->HrgSatuan;
				$HrgRAB 		= ($row->HrgRAB == '' || $row->HrgRAB == NULL) ? 0 : $row->HrgRAB;
				$TotalTerserap 			= ($row->TotalTerserap == '' || $row->TotalTerserap == NULL) ? 0 : (float)  $row->TotalTerserap;

				$Tahun 			= ($row->Tahun == '' || $row->Tahun == NULL) ? NULL : $row->Tahun;
				$Jenis 			= ($row->Jenis == '' || $row->Jenis == NULL) ? NULL : $row->Jenis;
				$LedgerNo 		= ($row->LedgerNo == '' || $row->LedgerNo == NULL) ? NULL : $row->LedgerNo;
				$PPN 			= ($row->PPN == '' || $row->PPN == NULL) ? 0 : (float)  $row->PPN;
				$VolumeInputan 	= ($row->VolumeInputan == '' || $row->VolumeInputan == NULL) ? 0 : (float)  $row->VolumeInputan;
				$Jumlah 	= ($row->Jumlah == '' || $row->Jumlah == NULL) ? 0 : $row->Jumlah;
				$PrdAwal 		= Validasi_Tgl($row->PrdAwal);
				$PrdAkhir 		= Validasi_Tgl($row->PrdAkhir);
				$Minggu 		= ($row->Minggu == '' || $row->Minggu == NULL) ? 0 : $row->Minggu;
				$Bobot_Persen 		= ($row->Bobot_Persen == '' || $row->Bobot_Persen == NULL) ? 0 :  (float) $row->Bobot_Persen;
				$TglPengakuan 		= Validasi_Tgl($row->TglPengakuan);


				$TotalHargaKontrak 		= ($row->TotalHargaKontrak == '' || $row->TotalHargaKontrak == NULL) ? 0 : (float)  $row->TotalHargaKontrak;

				$TotalHargaKontrak_Satuan 		= ($row->TotalHargaKontrak_Satuan == '' || $row->TotalHargaKontrak_Satuan == NULL) ? 0 : (float)  $row->TotalHargaKontrak_Satuan;

				$JumlahHarga_Satuan 		= ($row->JumlahHarga_Satuan == '' || $row->JumlahHarga_Satuan == NULL) ? 0 : (float)  $row->JumlahHarga_Satuan;


				$ArrayBuatan[] = (object) array(
					'JobNo' => $JobNo,
					'KdRAP' => $KdRAP,
					'Versi' => $Versi,
					'NoUrut' => $NoUrut,
					'Uraian' => $Uraian,
					'Alokasi' => $Alokasi,
					'Tipe' => $Tipe,
					'Header' => $Header,
					'Uom' => $Uom,
					'Vol' => $Vol,
					'HrgSatuan' => $HrgSatuan,
					'HrgRAB' => $HrgRAB,
					'TotalTerserap' => $TotalTerserap,
					'Tahun' => $Tahun,
					'Jenis' => $Jenis,
					'LedgerNo' => $LedgerNo,
					'PPN' => $PPN,
					'VolumeInputan' => $VolumeInputan,
					'Jumlah' => $Jumlah,
					'PrdAwal' => $PrdAwal,
					'PrdAkhir' => $PrdAkhir,
					'Minggu' => $Minggu,
					'Bobot_Persen' => $Bobot_Persen,
					'TglPengakuan' => $TglPengakuan,
					'TotalHargaKontrak' => $TotalHargaKontrak,
					'TotalHargaKontrak_Satuan' => $TotalHargaKontrak_Satuan,
					'JumlahHarga_Satuan' => $JumlahHarga_Satuan,
				);
			}
		}
		return $ArrayBuatan;
	}

	function HitungTotalHarga()
	{
		if (!$this->input->post()) {
			exit;
		}

		$HrgRAB = $this->input->post('HrgRAB', TRUE);
		$VolumeInputan = $this->input->post('VolumeInputan', TRUE);
		$Hitung = 0;

		$searchForValue = ',';

		if (strpos($HrgRAB, $searchForValue) !== false) {
			$HrgRAB = str_replace(",", "", $HrgRAB);
		}

		if (strpos($VolumeInputan, $searchForValue) !== false) {
			$VolumeInputan = str_replace(",", "", $VolumeInputan);
		}

		$Hitung = $HrgRAB * $VolumeInputan;
		echo UbahDecimal_Nol_Dua($Hitung);
	}

	function SimpanRPPM()
	{
		if (!$this->input->post()) {
			exit;
		}

		// print_rr($_POST);
		// exit;

		$KdRAP = $this->input->post('KdRAP', TRUE);
		$Uraian = $this->input->post('Uraian', TRUE);
		$Uom = $this->input->post('Uom', TRUE);
		$Vol = $this->input->post('Vol', TRUE);
		$VolumeInputan = $this->input->post('VolumeInputan', TRUE);
		$TotalHarga = $this->input->post('TotalHarga', TRUE);
		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);

		$Header = $this->input->post('Header', TRUE);
		$Tipe = $this->input->post('Tipe', TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');


		// START 25 AGUSTUS 2023
		$PrdAwal = ($this->input->post('PrdAwal')) ? $this->input->post('PrdAwal', TRUE) : NULL;
		$PrdAkhir = ($this->input->post('PrdAkhir')) ? $this->input->post('PrdAkhir', TRUE) : NULL;
		$Minggu = ($this->input->post('Minggu')) ? $this->input->post('Minggu', TRUE) : 0;
		$TglPengakuan = ($this->input->post('TglPengakuan')) ? $this->input->post('TglPengakuan', TRUE) : NULL;
		$Bobot_Persen = ($this->input->post('Bobot_Persen')) ? $this->input->post('Bobot_Persen', TRUE) : 0;
		// END 25 AGUSTUS 2023

		$searchForValue = ',';

		if (strpos($Vol, $searchForValue) !== false) {
			$Vol = str_replace(",", "", $Vol);
		}

		if (strpos($VolumeInputan, $searchForValue) !== false) {
			$VolumeInputan = str_replace(",", "", $VolumeInputan);
		}

		if (strpos($TotalHarga, $searchForValue) !== false) {
			$TotalHarga = str_replace(",", "", $TotalHarga);
		}

		if (strpos($Bobot_Persen, $searchForValue) !== false) {
			$Bobot_Persen = str_replace(",", "", $Bobot_Persen);
		}

		$data = array(
			'JobNo' => $JobNo,
			'KdRAP' => $KdRAP,
			'Alokasi' => $Alokasi,
			'Uraian' => $Uraian,
			'Uom' => $Uom,
			'Vol' => $Vol,
			'VolumeInputan' => $VolumeInputan,
			'Jumlah' => $TotalHarga,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'Header' => $Header,
			'Tipe' => $Tipe,

			'PrdAwal' => $PrdAwal,
			'PrdAkhir' => $PrdAkhir,
			'TglPengakuan' => $TglPengakuan,
			'Minggu' => $Minggu,
			'Bobot_Persen' => $Bobot_Persen,
		);


		// -- CEK AKUMULASI VOLUME INPUTAN PADA RAP
		// $query_RAP = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND  ")

		$this->db->trans_begin();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
		} else {
			$this->db->trans_commit();
			echo 'berhasil';
			is_pesan('success', 'Saved!');
		}

		$this->db->insert('RPPM_tbl', $data);
	}

	function GetListMinggu()
	{
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$Query = $this->db->query("SELECT DISTINCT(Minggu) AS 'Minggu'  FROM RPPM_tbl WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'");

		$opsi_balik = '';
		$opsi_balik .= '<option value=""></option>';

		if ($Query->num_rows() > 0) {
			foreach ($Query->result() as $row) :
				$opsi_balik .= '<option value="' . $row->Minggu . '">' . $row->Minggu . '</option>';
			endforeach;
		}
		echo $opsi_balik;
	}

	function dapatkan_periode()
	{
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Minggu = $this->input->post('Minggu', TRUE);
		$MingguKe = (int) $Minggu;

		$query = $this->db->query("SELECT TOP 1 * FROM RPPM_tbl WHERE Minggu=$MingguKe AND JobNo='$JobNo'");
		if ($query->num_rows() == 0) {
			echo json_encode(array('status' => 'periode_tidak_ada'));
			exit;
		}

		$row = $query->row();

		$data = array('PrdAwal' => $row->PrdAwal, 'PrdAkhir' => $row->PrdAkhir);
		echo json_encode($data);
	}

	private function GetAlokasi()
	{
		$AksesAlokasi = $this->session->userdata('PecahToken')->AksesAlokasi;
		// $AksesAlokasi = $this->db->query("SELECT AksesAlokasi FROM Login WHERE UserID='liasw'")->row()->AksesAlokasi;
		$queryAlokasi = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi WHERE Alokasi<>'A' ");
		$ArrayAlokasi = array();
		foreach ($queryAlokasi->result_array() as $row) :

			if (!preg_match('/\b' . $row['Alokasi'] . '\b/', $AksesAlokasi)) {
				continue;
			}

			$ArrayAlokasi[] = array('Alokasi' => $row['Alokasi'], 'Keterangan' => $row['Keterangan']);
		endforeach;

		return $ArrayAlokasi;
	}

	private function GetJob()
	{

		$AksesJob = $this->session->userdata('PecahToken')->AksesJob;
		$QueryJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$this->Company' ");
		$ArrayJob = array();
		foreach ($QueryJob->result_array() as $myjob) :

			if (!preg_match('/\b' . $myjob['JobNo'] . '\b/', $AksesJob)) {
				continue;
			}
			$ArrayJob[] = array('JobNo' => $myjob['JobNo'], 'JobNm' => $myjob['JobNm']);
		endforeach;

		return $ArrayJob;
	}

	function RPPM_MultipleForm($JobNo = '', $Alokasi = '')
	{
		if ($JobNo == '' || $Alokasi == '') {
			redirect('Entry/FrmRPPM_C');
			die;
		}

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $this->db->query("SELECT ISNULL(JobNm,NULL) AS JobNm FROM Job WHERE JobNo='$JobNo' ")->row()->JobNm;
		$data['Alokasi'] = $Alokasi;
		$data['KeteranganAlokasi'] = $this->db->query("SELECT ISNULL(Keterangan,NULL) AS Keterangan FROM Alokasi WHERE Alokasi='$Alokasi' ")->row()->Keterangan;

		$data['judul'] = 'Entry RPPM';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';
		$data['js_saya'] = 'BasicData_View/FrmRPPM_View/js_RPPM_MultipleForm';

		// $ListRAP = $this->db->query("SELECT TOP 2 * FROM RAP WHERE HrgRAB != 0 ORDER BY NoUrut ASC")->result();


		$ListRAP = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC ")->result();

		$data['ListRAP'] = $ListRAP;

		$forSelectKdRAP = array();

		$TtlRAB = 0;
		$TtlRAP = 0;
		foreach ($ListRAP as $row_hitung) {

			if ($row_hitung->Tipe == 'Header') {
				continue;
			}

			$Vol = ($row_hitung->Vol == '' || $row_hitung->Vol == NULL) ? 0 :  $row_hitung->Vol;
			$HrgSatuan = ($row_hitung->HrgSatuan == '' || $row_hitung->HrgSatuan == NULL) ? 0 :  $row_hitung->HrgSatuan;
			$HrgRAB = ($row_hitung->HrgRAB == '' || $row_hitung->HrgRAB == NULL) ? 0 : $row_hitung->HrgRAB;

			$TtlRAP = $TtlRAP + ($Vol * $HrgSatuan);
			$TtlRAB = $TtlRAB + ($Vol * $HrgRAB);
		}
		// echo 
		// echo number_format($TtlRAP).' - '.number_format($TtlRAB).'<br>';
		// die;

		foreach ($ListRAP as $myrow) {
			$KdRAP = ($myrow->KdRAP == '' || $myrow->KdRAP == NULL) ? NULL : $myrow->KdRAP;
			$Uraian = ($myrow->Uraian == '' || $myrow->Uraian == NULL) ? NULL : $myrow->Uraian;
			$Uom = ($myrow->Uom == '' || $myrow->Uom == NULL) ? '' : $myrow->Uom;
			$Tipe = ($myrow->Tipe == '' || $myrow->Tipe == NULL) ? NULL : $myrow->Tipe;
			$Header = ($myrow->Header == '' || $myrow->Header == NULL) ? '' : $myrow->Header;
			$Vol = ($myrow->Vol == '' || $myrow->Vol == NULL) ? 0 : (float) $myrow->Vol;
			$HrgSatuan = ($myrow->HrgSatuan == '' || $myrow->HrgSatuan == NULL) ? 0 : (float) $myrow->HrgSatuan;
			$HrgRAB = ($myrow->HrgRAB == '' || $myrow->HrgRAB == NULL) ? 0 : (float) $myrow->HrgRAB;

			$TotalHargaKontrak = $Vol * $HrgRAB;
			$TotalHargaKontrak_Satuan = $Vol * $HrgSatuan;

			$BobotRAB = $this->calculate_percentage_for_display($TotalHargaKontrak, $TtlRAB);
			// var_dump($Vol.'=='.$HrgRAB);die;
			// var_dump("Kode RAp = ".$myrow->KdRAP."\nTotal Harga Kontrak = ".$TotalHargaKontrak."\nTotal Harga RAB =".$TtlRAB."\nJumlah = ".$TotalHargaKontrak/$TtlRAB);die;
			// $BobotRAB = $TotalHargaKontrak / $TtlRAB;

			// $query_Vol_Akumulasi = $this->db->query("")


			$forSelectKdRAP[] = (object) array(
				'KdRAP' => $KdRAP,
				'Uraian' => $Uraian,
				'Tipe' => $Tipe,
				'Uom' => $Uom,
				'Vol' => $Vol,
				'HrgSatuan' => $HrgSatuan,
				'HrgRAB' => $HrgRAB,
				'TotalHargaKontrak' => $TotalHargaKontrak,
				'Header' => $Header,
				'TotalHargaKontrak_Satuan' => $TotalHargaKontrak_Satuan,
				'BobotRAB' => $BobotRAB,
			);
		}

		// print_rr($forSelectKdRAP);
		// die;
		$data['forSelectKdRAP'] = $forSelectKdRAP;
		$data['json_ListRAP'] = json_encode($forSelectKdRAP);

		$MingguBuatan = 0;
		$cekMinggu = $this->db->query("SELECT MAX(Minggu) AS Minggu FROM RPPM_tbl WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'");
		if ($cekMinggu->num_rows() > 0) {
			$rowMinggu = $cekMinggu->row_array();
			$MingguBuatan = (int) $rowMinggu['Minggu'] + 1;
		} else {
			$MingguBuatan = 1;
		}

		$data['MingguAutoCreate'] = $MingguBuatan;

		// $TglRPPM = date('Y-m-d');
		// $dapat_hitungan = $this->dapatkan_hari_senin_atau_monday($TglRPPM);
		// $PrdAwal_minggu = $dapat_hitungan['PrdAwal_minggu'];
		// $PrdAkhir_minggu = $dapat_hitungan['PrdAkhir_minggu'];
		// $HitungMinggu = $this->hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu);
		// print_rr($dapat_hitungan);
		// die;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmRPPM_View/RPPM_MultipleForm', $data);
		$this->load->view('templates/footer');
	}

	function dapatkan_hari_senin_atau_monday($TglPD = '')
	{

		$TglPD_nama_hari = date('l', strtotime($TglPD));
		$TglPD_tanggal = ($TglPD_nama_hari == 'Sunday') ? date('Y-m-d', strtotime('-1 days', strtotime($TglPD))) : date('Y-m-d', strtotime('+1 days', strtotime($TglPD)));
		$PrdAwal_minggu = date('Y-m-d', strtotime('previous monday', strtotime($TglPD_tanggal)));
		$PrdAkhir_minggu = date('Y-m-d', strtotime('+6 days', strtotime($PrdAwal_minggu)));
		return array('PrdAwal_minggu' => $PrdAwal_minggu, 'PrdAkhir_minggu' => $PrdAkhir_minggu);
	}

	function SimpanMultipleInput_RPPM()
	{
		if (!$this->input->post()) {
			die;
		}

		// if ($this->session->userdata('PecahToken')->RPPMHistory!=1) {
		// 	echo 'akses di-tolak';
		// 	die;
		// }

		// print_rr($_POST);
		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		$PrdAwal = $this->input->post('PrdAwal');
		$PrdAkhir = $this->input->post('PrdAkhir');
		$MingguKe = $this->input->post('MingguKe');
		$TglPengakuan = $this->input->post('TglPengakuan');

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		// print_rr($_POST);

		// die;


		$this->db->trans_begin();

		try {

			$Array_KodeRAP = array();
			if ($this->input->post('KdRAP')) :
				$NoUrut = 0;
				foreach ($this->input->post('KdRAP') as $key => $value) :
					$NoUrut++;

					$Uraian = $this->input->post('Uraian');
					$Uom = $this->input->post('Uom');
					$Tipe = $this->input->post('Tipe');
					$Header = $this->input->post('Header');

					$Vol = CekDanHapusKoma($this->input->post('Vol'));
					$HrgRAB = CekDanHapusKoma($this->input->post('HrgRAB'));
					$HrgSatuan = CekDanHapusKoma($this->input->post('HrgSatuan'));
					$TotalHargaKontrak = CekDanHapusKoma($this->input->post('TotalHargaKontrak'));
					$Bobot_Persen = CekDanHapusKoma($this->input->post('Bobot_Persen'));
					$VolumeInputan = CekDanHapusKoma($this->input->post('VolumeInputan'));
					$TotalHarga = CekDanHapusKoma($this->input->post('TotalHarga'));

					$TotalHargaKontrak_Satuan = CekDanHapusKoma($this->input->post('TotalHargaKontrak_Satuan'));
					$TotalHarga_Satuan = CekDanHapusKoma($this->input->post('TotalHarga_Satuan'));

					$KdRAP_bayangan = '';
					$VolRAP = 0;
					$VolRPPM_YangSudahMasuk = 0;
					$VolRPPM_Tambah_VolumeInputan = 0;

					$KdRAP_bayangan = trim($value);
					$query_Vol_Akumulasi = $this->db->query("SELECT ISNULL(Vol,0) AS VolRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP='$KdRAP_bayangan'  ")->row();
					$VolRAP = (float) $query_Vol_Akumulasi->VolRAP;


					$query_RPPM_tbl_Cek = $this->db->query("SELECT coalesce(sum(VolumeInputan),0) AS VolRPPM_YangSudahMasuk  FROM RPPM_tbl WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP='$KdRAP_bayangan' GROUP BY KdRAP")->row_array();
					$VolRPPM_YangSudahMasuk = (float) $query_RPPM_tbl_Cek['VolRPPM_YangSudahMasuk'];


					// echo $VolRAP.'<br />'.$VolRPPM_YangSudahMasuk;

					$VolRPPM_Tambah_VolumeInputan =  $VolRPPM_YangSudahMasuk +  (float) $VolumeInputan[$key];
					// echo $VolRPPM_Tambah_VolumeInputan;

					if ($VolRPPM_Tambah_VolumeInputan > $VolRAP) {
						$pesan = "Total Akumulasi Volume Input melebihi volume RAP.\n";
						$pesan = $pesan . " Volume RAP " . $KdRAP_bayangan . " : " . UbahDecimal_Nol_Tiga($VolRAP) . "\n";
						$pesan = $pesan . "Total Akumulasi Volume termasuk current : " . UbahDecimal_Nol_Tiga($VolRPPM_Tambah_VolumeInputan) . "\n";
						$pesan = $pesan . "Volume Yang sudah masuk : " . UbahDecimal_Nol_Tiga($VolRPPM_YangSudahMasuk) . "\n";
						echo $pesan;
						$this->db->trans_rollback();
						die;
					}




					$Array_KodeRAP[] = array(
						'JobNo' 	=> $JobNo,
						'KdRAP' 	=> $value,
						'NoUrut' 	=> $NoUrut,
						'Uraian' 	=> $Uraian[$key],
						'Alokasi' 	=> $Alokasi,

						'Tipe' 		=> $Tipe[$key],
						'Header' 	=> $Header[$key],
						'Uom' 		=> $Uom[$key],
						'Vol' 		=> $Vol[$key],
						'HrgSatuan' => $HrgSatuan[$key],
						'HrgRAB' => $HrgRAB[$key],
						'TotalTerserap' => 0,
						'UserEntry' => $UserEntry,
						'TimeEntry' => $TimeEntry,
						'VolumeInputan' => $VolumeInputan[$key],
						'Jumlah' => $TotalHarga[$key],
						'PrdAwal' => $PrdAwal,
						'PrdAkhir' => $PrdAkhir,
						'Minggu' => $MingguKe,
						'Bobot_Persen' => $Bobot_Persen[$key],
						'TglPengakuan' => $TglPengakuan,


						'TotalHargaKontrak' => $TotalHargaKontrak[$key],
						'TotalHargaKontrak_Satuan' => $TotalHargaKontrak_Satuan[$key],
						'JumlahHarga_Satuan' => $TotalHarga_Satuan[$key],

					);

				endforeach;


				$this->db->insert_batch('RPPM_tbl', $Array_KodeRAP);
			endif;



			echo 'berhasil';
			is_pesan('success', 'Sukses, data telah ditambahkan');
			$this->db->trans_commit();
		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}
	}

	function HapusRPPM()
	{

		if ($this->session->userdata('PecahToken')->RPPMHistory != 1) {
			echo 'akses di-tolak';
			die;
		}

		$this->db->trans_begin();

		try {
			if ($this->input->post('LedgerNo')) {
				$LedgerNo = trim($_POST['LedgerNo']);
				$sql = "DELETE FROM RPPM_tbl WHERE LedgerNo IN ($LedgerNo) ";
				$this->db->query($sql);
			}
			echo 'berhasil';
			$this->db->trans_commit();
		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}
	}

	function FrmUpdateRPPM($LedgerNo = '')
	{


		// print_rr($_POST);

		// $LedgerNo = $this->input->post('LedgerNo');
		$LedgerNo = str_replace("-", ",", $LedgerNo);
		// echo $LedgerNo;
		// die;
		$query = $this->db->query("SELECT * FROM RPPM_tbl WHERE LedgerNo IN ($LedgerNo) ")->result();
		// echo "SELECT * FROM RPPM_tbl WHERE LedgerNo IN (LedgerNo)";
		// die;
		$data['dataRPPM'] = $query;

		$data['JobNo'] = $this->JobNo_Global;
		$data['JobNm'] = $this->db->query("SELECT ISNULL(JobNm,NULL) AS JobNm FROM Job WHERE JobNo='$this->JobNo_Global' ")->row()->JobNm;
		$data['Alokasi'] = $this->Alokasi_Global;
		$data['KeteranganAlokasi'] = $this->db->query("SELECT ISNULL(Keterangan,NULL) AS Keterangan FROM Alokasi WHERE Alokasi='$this->Alokasi_Global' ")->row()->Keterangan;

		$data['judul'] = 'Entry RPPM';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';
		$data['js_saya'] = 'BasicData_View/FrmRPPM_View/js_RPPM_MultipleForm_Update';

		$forSelectKdRAP = array();
		foreach ($query as $myrow) {
			$KdRAP = ($myrow->KdRAP == '' || $myrow->KdRAP == NULL) ? NULL : $myrow->KdRAP;
			$Uraian = ($myrow->Uraian == '' || $myrow->Uraian == NULL) ? NULL : $myrow->Uraian;
			$Uom = ($myrow->Uom == '' || $myrow->Uom == NULL) ? '' : $myrow->Uom;
			$Tipe = ($myrow->Tipe == '' || $myrow->Tipe == NULL) ? NULL : $myrow->Tipe;
			$Header = ($myrow->Header == '' || $myrow->Header == NULL) ? '' : $myrow->Header;
			$Vol = ($myrow->Vol == '' || $myrow->Vol == NULL) ? 0 : (float) $myrow->Vol;
			$HrgSatuan = ($myrow->HrgSatuan == '' || $myrow->HrgSatuan == NULL) ? 0 : (float) $myrow->HrgSatuan;
			$HrgRAB = ($myrow->HrgRAB == '' || $myrow->HrgRAB == NULL) ? 0 : (float) $myrow->HrgRAB;
			$TotalHargaKontrak = $Vol * $HrgRAB;

			$forSelectKdRAP[] = (object) array(
				'KdRAP' => $KdRAP,
				'Uraian' => $Uraian,
				'Tipe' => $Tipe,
				'Uom' => $Uom,
				'Vol' => $Vol,
				'HrgSatuan' => $HrgSatuan,
				'HrgRAB' => $HrgRAB,
				'TotalHargaKontrak' => $TotalHargaKontrak,
				'Header' => $Header,
			);
		}
		$data['forSelectKdRAP'] = $forSelectKdRAP;
		$data['json_ListRAP'] = json_encode($forSelectKdRAP);

		// print_rr($query);
		// die;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('BasicData_View/FrmRPPM_View/RPPM_MultipleForm_Update', $data);
		$this->load->view('templates/footer');
	}

	function AksiUpdateRPPM()
	{
		if (!$this->input->post()) {
			die;
		}
		// echo json_encode($this->input->post());
		// die;
		// echo $this->input->post('TotalHargKontrakSatuanBaru');

		if ($this->session->userdata('PecahToken')->RPPMHistory != 1) {
			echo 'akses di-tolak';
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		$NoUrut 		= $this->input->post('NoUrut_Update');
		$KdRAP 			= $this->input->post('KdRAP_Update');
		$Uraian 		= $this->input->post('Uraian_Update');
		$Uom 			= $this->input->post('Uom_Update');
		$HrgRAB 		= CekDanHapusKoma($this->input->post('HrgRAB_Update'));
		$HrgSatuan 		= CekDanHapusKoma($this->input->post('HrgSatuan_Update'));

		$PrdAwal 		= $this->input->post('PrdAwal_Update');
		$PrdAkhir 		= $this->input->post('PrdAkhir_Update');
		$Minggu 		= $this->input->post('Minggu_Update');
		$TglPengakuan 	= $this->input->post('TglPengakuan_Update');
		$Bobot_Persen 	= CekDanHapusKoma($this->input->post('Bobot_Persen_Update'));
		$VolumeInputan 	= CekDanHapusKoma($this->input->post('VolumeInputan_Update'));
		$LedgerNo 		= $this->input->post('LedgerNo_Update');
		$TotalHargaKontrak = CekDanHapusKoma($this->input->post('TotalHargKontrakBaru'));
		$TotalHargaKontrakSatuan = CekDanHapusKoma($this->input->post('TotalHargKontrakSatuanBaru'));

		$HrgRAB 		= (float) $HrgRAB;
		$HrgSatuan 		= (float) $HrgSatuan;
		$VolumeInputan 	= (float) $VolumeInputan;
		$Bobot_Persen 	= (float) $Bobot_Persen;

		// Volume Input * HrgRAB
		$JumlahHarga 			= $VolumeInputan * $HrgRAB;
		$JumlahHarga_Satuan 	= $VolumeInputan * $HrgSatuan;

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		// get input data from RAP
		$VolRAP = $this->input->post('VolRAP');
		$HrgSatuanFromRAP = CekDanHapusKoma($this->input->post('HrgSatuanFromRAP'));
		$HrgRABFromRAP = CekDanHapusKoma($this->input->post('HrgRABFromRAP'));

		$this->db->trans_begin();

		try {
			$set_Param = array(
				'Vol' => $VolRAP,
				'HrgSatuan' => $HrgSatuanFromRAP,
				'HrgRAB' => $HrgRABFromRAP,
				'PrdAwal' => $PrdAwal,
				'PrdAkhir' => $PrdAkhir,
				'Minggu' => $Minggu,
				'TglPengakuan' => $TglPengakuan,
				'TotalHargaKontrak' => $TotalHargaKontrak,
				'TotalHargaKontrak_Satuan' => $TotalHargaKontrakSatuan,
				'Bobot_Persen' => $Bobot_Persen,
				'VolumeInputan' => $VolumeInputan,
				'Jumlah' => $JumlahHarga,
				'JumlahHarga_Satuan' => $JumlahHarga_Satuan,
				'UserEntry' => $UserEntry,
				'TimeEntry' => $TimeEntry,
			);

			$where_Param = array('LedgerNo' => $LedgerNo);

			$this->db->set($set_Param)->where($where_Param)->update('RPPM_tbl');
			is_pesan('success', 'Update Berhasil');
			echo 'berhasil';
			$this->db->trans_commit();
		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}


		// echo '<hr>';
		// print_rr($set_Param);
	}

	function format_percentage($percentage, $precision = 5)
	{
		// return round($percentage, $precision);
		return $percentage;
	}

	function calculate_percentage($number, $total)
	{

		// Can't divide by zero so let's catch that early.
		if ($total == 0) {
			return 0;
		}

		return ($number / $total) * 100;
	}

	function calculate_percentage_for_display($number, $total)
	{
		return $this->format_percentage($this->calculate_percentage($number, $total));
	}

	function export_to_excel($JobNo, $Alokasi, $MingguKe)
	{
		if ($MingguKe == '0') {
			$this->session->set_flashdata('message2', 'Mohon lengkapi data terlebih dahulu!');
			redirect('BasicData/FrmRPPM_C');
			die;
		}
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$untuk_header = [
			'font' => ['bold' => true],
		];

		$styl_bold_border_center = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 1,
			],
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];
		$style_border_center = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 1,
			],
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];
		$style_border_right = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'indent' => 1,
			],
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];
		$style_border_left = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				'indent' => 1,
			],
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];

		$sheet->mergeCells('A1:A3');
		$sheet->mergeCells('B1:B3');
		$sheet->mergeCells('C1:C3');
		$sheet->mergeCells('D1:D3');
		$sheet->mergeCells('E1:E3');
		$sheet->mergeCells('F1:F3');
		$sheet->mergeCells('G1:G3');
		$sheet->mergeCells('H1:M1');
		$sheet->mergeCells('H2:I2');
		$sheet->mergeCells('J2:K2');
		$sheet->mergeCells('L2:M2');

		$sheet->setCellValue('A1', "No");
		$sheet->setCellValue('B1', "Kode RAP");
		$sheet->setCellValue('C1', "Uraian Pekerjaan");
		$sheet->setCellValue('D1', "Satuan");
		$sheet->setCellValue('E1', "Volume");
		$sheet->setCellValue('F1', "Harga Satuan Kontrak");
		$sheet->setCellValue('G1', "Total Harga Kontrak");
		$sheet->setCellValue('H1', "Prestasi Pekerjaan");
		$sheet->setCellValue('H2', "Yang Lalu");
		$sheet->setCellValue('H3', "Volume");
		$sheet->setCellValue('J2', "Sekarang");
		$sheet->setCellValue('L2', "s/d Sekarang");
		$sheet->setCellValue('L3', "Volume");
		$sheet->setCellValue('I3', "Bobot (%)");
		$sheet->setCellValue('J3', "Volume");
		$sheet->setCellValue('K3', "Bobot %");
		$sheet->setCellValue('M3', "Bobot %");

		$sheet->getStyle('A1:A3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('B1:B3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('C1:C3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('D1:D3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('E1:E3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('F1:F3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('G1:G3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('H1:M1')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('H2')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('H3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('J2')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('L2:M2')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('L3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('I3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('J3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('K3')->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('M3')->applyFromArray($styl_bold_border_center);

		$query = $this->db->query("

			DECLARE @Minggu int;
			DECLARE @JobNo nvarchar(10);
			DECLARE @Alokasi nvarchar(1);

			SET @Minggu=$MingguKe;
			SET @JobNo='$JobNo';
			SET @Alokasi='$Alokasi';
			SELECT 

			ISNULL(RAP.NoUrut,0) AS NoUrut,
			ISNULL(RAP.JobNo,NULL) AS JobNo,
			ISNULL(RAP.Alokasi,NULL) AS Alokasi,
			ISNULL(RAP.Header,NULL) AS Header,
			ISNULL(RAP.Tipe,NULL) AS Tipe,
			ISNULL(RAP.KdRAP,NULL) AS KdRAP,
			ISNULL(RAP.Uraian,NULL) AS Uraian,
			ISNULL(RAP.Uom,NULL) AS Uom,
			ISNULL(RAP.Vol,0) AS Vol,
			ISNULL(RAP.HrgRAB,0) AS HrgRAB,

			-- START MINGGU YANG LALU --
			ISNULL((SELECT ISNULL(SUM(VolumeInputan),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu<@Minggu GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian),0) AS 'Minggu_YangLalu',
			-- END MINGGU YANG LALU --

			-- START Bobot Persen YANG LALU --
			ISNULL((SELECT ISNULL(SUM(Bobot_Persen),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu<@Minggu GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian),0) AS 'BobotPersen_YangLalu',
			-- END Bobot Persen YANG LALU --

			-- START MINGGU SAAT INI --
			ISNULL((SELECT ISNULL(SUM(VolumeInputan),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu=@Minggu GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian),0) AS 'Minggu_SaatIni',
			-- END MINGGU SAAT INI --

			-- START Bobot Persen SAAT INI --
			ISNULL((SELECT ISNULL(SUM(Bobot_Persen),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu=@Minggu GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian),0) AS 'BobotPersen_SaatIni',
			-- END Bobot Persen SAAT INI --

			-- START MINGGU SAMPAI DENGAN SEKARANG --
			ISNULL
			(
				(SELECT ISNULL
					(
						SUM(VolumeInputan),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu BETWEEN 0 AND @Minggu  GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian
					)
				,0) AS 'Minggu_SampaiDgnSekarang',
			-- END MINGGU SEKARANG --

			-- START Bobot Persen SAMPAI DENGAN SEKARANG --
			ISNULL
			(
				(SELECT ISNULL
					(
						SUM(Bobot_Persen),0) FROM RPPM_tbl WHERE JobNo=@JobNo AND Alokasi=@Alokasi AND KdRAP=RAP.KdRAP AND Minggu BETWEEN 0 AND @Minggu   GROUP BY RPPM_tbl.KdRAP, RPPM_tbl.Uraian
					)
				,0) AS 'BobotPersen_SampaiDgnSekarang'
			-- END Bobot Persen SEKARANG --
			FROM RAP 
			WHERE RAP.JobNo=@JobNo AND RAP.Alokasi=@Alokasi
			ORDER BY RAP.NoUrut ASC
			");

		$no = 1;
		$numrow = 4;

		$sumFooter_BobotLalu = 0;
		$sumFooter_BobotSkrg = 0;
		$sumFooter_BobotsdSkrg = 0;
		foreach ($query->result() as $row) :

			$Vol_Dua = ($row->Vol == '' || $row->Vol == NULL) ? 0 : (float) $row->Vol;
			$HrgRAB_Dua = ($row->HrgRAB == '' || $row->HrgRAB == NULL) ? 0 : (float) $row->HrgRAB;

			$HrgSatuanKontrak_RAB = 0;
			$HrgSatuanKontrak_RAB = $HrgRAB_Dua * $Vol_Dua;

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $row->KdRAP);
			$sheet->setCellValue('C' . $numrow, $row->Uraian);
			$sheet->setCellValue('D' . $numrow, $row->Uom);
			$sheet->setCellValue('E' . $numrow, $Vol_Dua);
			$sheet->setCellValue('F' . $numrow, $HrgRAB_Dua);
			$sheet->setCellValue('G' . $numrow, $HrgSatuanKontrak_RAB);
			$sheet->setCellValue('H' . $numrow, $row->Minggu_YangLalu);
			$sheet->setCellValue('I' . $numrow, $row->BobotPersen_YangLalu);
			$sheet->setCellValue('J' . $numrow, $row->Minggu_SaatIni);
			$sheet->setCellValue('K' . $numrow, $row->BobotPersen_SaatIni);
			$sheet->setCellValue('L' . $numrow, $row->Minggu_SampaiDgnSekarang);
			$sheet->setCellValue('M' . $numrow, $row->BobotPersen_SampaiDgnSekarang);

			$Minggu_YangLalu = ($row->Minggu_YangLalu == '' || $row->Minggu_YangLalu == NULL) ? 0 : (float) $row->Minggu_YangLalu;
			$BobotPersen_YangLalu = ($row->BobotPersen_YangLalu == '' || $row->BobotPersen_YangLalu == NULL) ? 0 : (float) $row->BobotPersen_YangLalu;

			$Minggu_SaatIni = ($row->Minggu_SaatIni == '' || $row->Minggu_SaatIni == NULL) ? 0 : (float) $row->Minggu_SaatIni;
			$BobotPersen_SaatIni = ($row->BobotPersen_SaatIni == '' || $row->BobotPersen_SaatIni == NULL) ? 0 : (float) $row->BobotPersen_SaatIni;

			$Minggu_SampaiDgnSekarang = ($row->Minggu_SampaiDgnSekarang == '' || $row->Minggu_SampaiDgnSekarang == NULL) ? 0 : (float) $row->Minggu_SampaiDgnSekarang;
			$BobotPersen_SampaiDgnSekarang = ($row->BobotPersen_SampaiDgnSekarang == '' || $row->BobotPersen_SampaiDgnSekarang == NULL) ? 0 : (float) $row->BobotPersen_SampaiDgnSekarang;

			$sumFooter_BobotLalu += $BobotPersen_YangLalu;
			$sumFooter_BobotSkrg += $BobotPersen_SaatIni;
			$sumFooter_BobotsdSkrg += $BobotPersen_SampaiDgnSekarang;

			$sheet->getStyle('A' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_border_left);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_border_left);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_border_left);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_border_right);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('I' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('J' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('K' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('L' . $numrow)->applyFromArray($style_border_center);
			$sheet->getStyle('M' . $numrow)->applyFromArray($style_border_center);


			$cellValueF = $sheet->getCell('F' . $numrow)->getValue();
			if ($cellValueF != 0) {
				$sheet->getStyle('F' . $numrow)->getNumberFormat()->setFormatCode('#,##0.00');
			}
			$cellValueG = $sheet->getCell('G' . $numrow)->getValue();
			if ($cellValueG != 0) {
				$sheet->getStyle('G' . $numrow)->getNumberFormat()->setFormatCode('#,##0.00');
			}
			$cellValueH = $sheet->getCell('H' . $numrow)->getValue();
			if ($cellValueH != 0) {
				$sheet->getStyle('H' . $numrow)->getNumberFormat()->setFormatCode('0.000');
			}
			$cellValueI = $sheet->getCell('I' . $numrow)->getValue();
			if ($cellValueI != 0) {
				$sheet->getStyle('I' . $numrow)->getNumberFormat()->setFormatCode('0.000');
			}
			$cellValueJ = $sheet->getCell('J' . $numrow)->getValue();
			if ($cellValueJ != 0) {
				$sheet->getStyle('J' . $numrow)->getNumberFormat()->setFormatCode('0.000');
			}
			$cellValueK = $sheet->getCell('K' . $numrow)->getValue();
			if ($cellValueK != 0) {
				$sheet->getStyle('K' . $numrow)->getNumberFormat()->setFormatCode('0.000');
			}
			$cellValueL = $sheet->getCell('L' . $numrow)->getValue();
			if ($cellValueL != 0) {
				$sheet->getStyle('L' . $numrow)->getNumberFormat()->setFormatCode('0.000');
			}
			$cellValueM = $sheet->getCell('M' . $numrow)->getValue();
			if ($cellValueM != 0) {
				$sheet->getStyle('M' . $numrow)->getNumberFormat()->setFormatCode('0.000');
			}


			if ($row->Tipe == 'Header' && $row->Header == '0') {
				$sheet->getStyle('B' . $numrow . ':M' . $numrow)->applyFromArray($untuk_header);
			}


			$no++;
			$numrow++;
		endforeach;


		// create SUM FOOTER
		$sumHargaKontrak = 0;
		foreach ($query->result() as $row) {
			if ($row->Tipe == 'Header') {
				continue;
			}
			$sumHargaKontrak += $row->Vol * $row->HrgRAB;
		}

		$sheet->setCellValue('A' . $numrow, 'TOTAL');
		$sheet->setCellValue('G' . $numrow,  $sumHargaKontrak);
		$sheet->setCellValue('I' . $numrow,  $sumFooter_BobotLalu);
		$sheet->setCellValue('K' . $numrow,  $sumFooter_BobotSkrg);
		$sheet->setCellValue('M' . $numrow,  $sumFooter_BobotsdSkrg);

		$sheet->mergeCells('A' . $numrow . ':F' . $numrow);

		$sheet->getStyle('A' . $numrow . ':F' . $numrow)->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('G' . $numrow)->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('H' . $numrow . ':I' . $numrow)->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('J' . $numrow . ':K' . $numrow)->applyFromArray($styl_bold_border_center);
		$sheet->getStyle('L' . $numrow . ':M' . $numrow)->applyFromArray($styl_bold_border_center);

		$sheet->getStyle('G' . $numrow)->getNumberFormat()->setFormatCode('#,##0.00');
		$sheet->getStyle('I' . $numrow)->getNumberFormat()->setFormatCode('0.000');
		$sheet->getStyle('K' . $numrow)->getNumberFormat()->setFormatCode('0.000');
		$sheet->getStyle('M' . $numrow)->getNumberFormat()->setFormatCode('0.000');
		// end create SUM FOOTER

		$maxWidths = [];
		foreach ($query->result() as $row) {
			$Vol_Dua = ($row->Vol == '' || $row->Vol == NULL) ? 0 : (float) $row->Vol;
			$HrgRAB_Dua = ($row->HrgRAB == '' || $row->HrgRAB == NULL) ? 0 : (float) $row->HrgRAB;

			$HrgSatuanKontrak_RAB = 0;
			$HrgSatuanKontrak_RAB = $HrgRAB_Dua * $Vol_Dua;

			$maxWidths['A'] = max(strlen($no), $maxWidths['A'] ?? 0);
			$maxWidths['B'] = max(strlen($row->KdRAP), $maxWidths['B'] ?? 0);
			$maxWidths['D'] = max(strlen($row->Uom), $maxWidths['D'] ?? 0);
			$maxWidths['E'] = max(strlen($row->Vol), $maxWidths['E'] ?? 0);
			$maxWidths['F'] = max(strlen($HrgSatuanKontrak_RAB), $maxWidths['G'] ?? 0);
			$maxWidths['G'] = max(strlen($row->HrgRAB), $maxWidths['G'] ?? 0);
			$maxWidths['H'] = max(strlen($row->Minggu_YangLalu), $maxWidths['H'] ?? 0);
			$maxWidths['I'] = max(strlen($row->BobotPersen_YangLalu), $maxWidths['J'] ?? 0);
			$maxWidths['J'] = max(strlen($row->Minggu_SaatIni), $maxWidths['J'] ?? 0);
			$maxWidths['K'] = max(strlen($row->BobotPersen_SaatIni), $maxWidths['J'] ?? 0);
			$maxWidths['L'] = max(strlen($row->Minggu_SampaiDgnSekarang), $maxWidths['J'] ?? 0);
			$maxWidths['M'] = max(strlen($row->BobotPersen_SampaiDgnSekarang), $maxWidths['J'] ?? 0);
		}

		$sheet->getColumnDimension('C')->setWidth(50);
		foreach ($maxWidths as $column => $width) {
			$sheet->getColumnDimension($column)->setWidth($width + 2);
		}

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$sheet->setTitle("RPPM");
		// die;
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="rppm.xlsx"');
		header('Cache-Control: max-age=0');
		// exit;
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}

/* End of file FrmRPPM_C.php */
/* Location: ./application/controllers/FrmRPPM_C.php */
