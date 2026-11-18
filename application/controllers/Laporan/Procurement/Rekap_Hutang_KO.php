<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rekap_Hutang_KO extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{

		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'Rekap Hutang KO';
		$data['bodyclass'] = 'sidebar-collapse skin-green-light';

		$DariTgl = date('Y-m-01');
		$SampaiTgl = date('Y-m-t');

		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;


		$data_job = $this->validasi_JobNo();
		$data['data_job'] = $data_job;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('laporan_view/procurement/rekap_hutang_ko_view', $data);
		$this->load->view('templates/footer');
		
	}

	function dapatkan_Table_HutangKO(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$DariTgl = $this->input->post('DariTgl', TRUE);
		$SampaiTgl = $this->input->post('SampaiTgl', TRUE);

		if ($JobNo == 'All Job') {
			$JobNo = $this->pembuatan_koma_implode_JobNo();
		}

		$data['dataHutangKO'] = $this->QueryHutangKO($JobNo,$DariTgl,$SampaiTgl);

		$data['JobNo'] = $JobNo;
		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;

		// print_rr($data['dataHutangKO']);
		// exit;

		$this->load->view('laporan_view/procurement/table_hutangKO',$data);
	}

	private function pembuatan_koma_implode_JobNo(){
		$comma_separated = "";

		$Company = $this->config->item('Company');
		$AksesJob = $this->db->query("SELECT JobNo FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND (StatusJob='Pelaksanaan' OR StatusJob='Pemeliharaan') AND Company='$Company' ")->result();
		$arrayJob = array();

		foreach($AksesJob as $JobAkses){
			$arrayJob[] = $JobAkses->JobNo;
		}

		$comma_separated = implode("','", $arrayJob);
		$comma_separated = "'".$comma_separated."'";

		return $comma_separated;
	}

	private function QueryHutangKO($JobNo, $DariTgl,$SampaiTgl){
		
		$QueryAwal = $this->db->query("
			SELECT 
			a.* ,
			a.Netto * IIF(a.PersenKSO=0,100,a.PersenKSO) / 100 AS 'NettKontrak',
			(SELECT ISNULL(SUM(Vol*HrgSatuan),0) FROM RAP WHERE JobNo=a.JobNo And Alokasi='B' AND Tipe='Detail') AS 'RAP'
			FROM Job a WHERE a.JobNo IN ('$JobNo') ORDER BY a.JobNo DESC ");

		$JobNo = NULL;
		$TglPHO = NULL;
		$Tahun = NULL;

		$ArrayTahun = array();
		$BuatArray = array();
		// $DariTgl = '2022-01-01';
		// $SampaiTgl = '2022-12-31';

		$RAP = 0;
		$RAP_Persen = 0;

		$KO1 = 0;
		$KO2 = 0;
		$NettKontrak = 0;
		$PersenKSO = 0;

		$KO1_Persen = 0;
		$KO2_Persen = 0;

		$BLE1 = 0;
		$BLE2 = 0;

		$BLE1_Persen = 0;
		$BLE2_Persen = 0;


		$Invoice1 = 0;
		$Invoice2 = 0;
		$TtlInv = 0;

		$Invoice1_Persen = 0;


		$SaldoKO = 0;
		$SaldoKO_Persen = 0;
		$JobNm = '';


		$SubTotal_NettKontrak = 0;
		$SubTotal_RAP = 0;

		$ArraySubTotal = array();

		foreach ($QueryAwal->result() as $JobAwal) :
			$JobNo = $JobAwal->JobNo;
			$JobNm = $JobAwal->JobNm;
			$TglPHO = ($JobAwal->PrdAwal == NULL || $JobAwal->PrdAwal == '' ) ? '1900-01-01' : $JobAwal->PrdAwal;
			$Tahun = date('Y', strtotime($TglPHO));

			// $PersenKSO = ($JobAwal->PersenKSO == 0) ? '1900-01-01' : $JobAwal->PersenKSO;
			// $PersenKSO = 
			// $NettKontrak = ($JobAwal->Netto * ($JobAwal->PersenKSO == 0) ? '1900-01-01' :    );
			$NettKontrak = $JobAwal->NettKontrak;
			$NettKontrak = $NettKontrak / 1000;
			$NettKontrak = round($NettKontrak);

			$RAP = $JobAwal->RAP;
			$RAP = $RAP / 1000;
			$RAP = round($RAP);


			
			// ======================= START QUERY PENCARIAN KO ======================================
			$query_KoHdr = $this->db->query("SELECT KoHdr.*, Vendor.VendorNm FROM KoHdr, Vendor WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND KoHdr.VendorId=Vendor.VendorId ");

			if ($query_KoHdr->num_rows() > 0 ) {

				$NoKO = NULL;

				foreach($query_KoHdr->result_array() as $data_KoHdr ) : 
					
					$NoKO = $data_KoHdr['NoKO'];

					// ==== Mulai KO1 ======
					if ($data_KoHdr['ClosedBy'] == NULL || $data_KoHdr['ClosedBy'] == '' ) {
						$KO1 += ($data_KoHdr['SubTotal'] + $data_KoHdr['PPN'] - $data_KoHdr['DiscAmount'] );  
					}else{
						$QuerySUM_KoHdr = $this->db->query("SELECT ISNULL(SUM(BLE.Amount),0) AS 'BLEAmount' FROM BLE, KoHdr WHERE BLE.NoKO=KoHdr.NoKO AND KoHdr.NoKO='$NoKO' ");

						if ($QuerySUM_KoHdr->num_rows() > 0 ) {
							$rowSUM_KoHdr = $QuerySUM_KoHdr->row_array();
							$KO1 += $rowSUM_KoHdr['BLEAmount'];
						}
					}
					// ==== Akhir KO1 ======


					// ==== Mulai KO2 ======
					if ($data_KoHdr['TglKO'] >= $DariTgl AND $data_KoHdr['TglKO'] <= $SampaiTgl  ) {

						if ($data_KoHdr['ClosedBy'] == NULL || $data_KoHdr['ClosedBy'] == '' ) {
							$KO2 += ($data_KoHdr['SubTotal'] + $data_KoHdr['PPN'] - $data_KoHdr['DiscAmount'] );  
						}else{
							$QuerySUM_KoHdr_KO2 = $this->db->query("SELECT ISNULL(SUM(BLE.Amount),0) as 'BLEAmount' FROM BLE, KoHdr WHERE BLE.NoKO=KoHdr.NoKO AND KoHdr.NoKO='$NoKO' ");

							if ($QuerySUM_KoHdr_KO2	->num_rows() > 0 ) {

								$rowSUM_KoHdr_KO2 = $QuerySUM_KoHdr_KO2->row_array();
								$KO2 += $rowSUM_KoHdr_KO2['BLEAmount'];
							}
						}


					}
					// ==== Akhir KO2 ======


				endforeach;


			}

			// ======================= END QUERY PENCARIAN KO =======================================


			// ======================= START QUERY PENCARIAN BLE ======================================

			$query_BLE = $this->db->query("SELECT BLE.Amount, BLE.TglBayar FROM BLE, KoHdr 
				WHERE BLE.JobNo='$JobNo' And BLE.NoKO=KoHdr.NoKO And 
				KoHdr.ApprovedBy Is Not NULL And KoHdr.CanceledBy Is NULL ");

			if ($query_BLE->num_rows() > 0 ) :

				foreach($query_BLE->result_array() as $data_BLE ) {
					
					$BLE1 += $data_BLE['Amount'];
					if ($data_BLE['TglBayar'] >= $DariTgl AND $data_BLE['TglBayar'] <= $SampaiTgl  ) {
						$BLE2 += $data_BLE['Amount'];
					}
				}

			endif;
			// ======================= END QUERY PENCARIAN BLE =======================================


			// ======================= START QUERY PENCARIAN INVOICE 1 ======================================
			$query_Invoice = $this->db->query("SELECT ISNULL(SUM(Invoice.Total),0) AS 'NilaiInvoice'  FROM Invoice, KoHdr
				WHERE Invoice.JobNo='$JobNo'
				And Invoice.NoKO=KoHdr.NoKO And KoHdr.ApprovedBy Is Not NULL And KoHdr.CanceledBy Is NULL
				And KoHdr.ClosedBy Is NULL And Invoice.InvDate>='$DariTgl' And Invoice.InvDate<='$SampaiTgl' ");

			if ($query_Invoice->num_rows() > 0 ) :

				foreach($query_Invoice->result_array() as $data_Invoice ) {
					
					$Invoice1 = $data_Invoice['NilaiInvoice'];
					
				}

			endif;
			// ======================= END QUERY PENCARIAN INVOICE 1 =======================================


			// ======================= START QUERY PENCARIAN Ttl Inv ======================================

			$query_TtlInv = $this->db->query("SELECT ISNULL(SUM(Invoice.Total),0) AS 'TtlInv' FROM Invoice, KoHdr
				WHERE Invoice.JobNo='$JobNo'
				And Invoice.NoKO=KoHdr.NoKO And KoHdr.ApprovedBy Is Not NULL And KoHdr.CanceledBy Is NULL
				And KoHdr.ClosedBy Is NULL ");

			if ($query_TtlInv->num_rows() > 0 ) :

				foreach($query_TtlInv->result_array() as $data_TtlInv) {
					
					$TtlInv = $data_TtlInv['TtlInv'];
					
				}

			endif;
			// ======================= END QUERY PENCARIAN Ttl Inv =======================================


			// ======================= START QUERY PENCARIAN INVOICE 2 ======================================

			$query_Invoice2 = $this->db->query("SELECT ISNULL(SUM(InvPD.PaymentAmount),0) AS 'Invoice2' FROM BLE, InvPD, KoHdr WHERE InvPD.JobNo='$JobNo' And BLE.NoPD=InvPD.NoPD And InvPD.NoKO=KoHdr.NoKO And KoHdr.ClosedBy Is NULL And KoHdr.CanceledBy Is NULL And KoHdr.ApprovedBy Is Not NULL ");

			if ($query_Invoice2->num_rows() > 0 ) :

				foreach($query_Invoice2->result_array() as $data_Invoice2) {
					
					$Invoice2 = $data_Invoice2['Invoice2'];
					$Invoice2 = $TtlInv - $Invoice2;
					
				}

			endif;
			// ======================= END QUERY PENCARIAN INVOICE 2 =======================================

			$SaldoKO = ($KO1 - $BLE1);
			$SaldoKO = $SaldoKO / 1000;
			$SaldoKO = round($SaldoKO);

			$KO1 = $KO1 / 1000;
			$KO1 = round($KO1);

			$KO2 = $KO2 / 1000;
			$KO2 = round($KO2);

			$BLE1 = $BLE1 / 1000;
			$BLE1 = round($BLE1);

			$BLE2 = $BLE2 / 1000;
			$BLE2 = round($BLE2);

			$Invoice1 = $Invoice1 / 1000;
			$Invoice1 = round($Invoice1);

			$Invoice2 = $Invoice2 / 1000;
			$Invoice2 = round($Invoice2);

			$TtlInv = $TtlInv / 1000;
			$TtlInv = round($TtlInv);

			// $Invoice2 = $TtlInv - $Invoice2;
			

			$RAP =  ($RAP?:1);
			$RAP_Persen = ( $RAP /  $NettKontrak ) * 100;
			$KO1_Persen = ($KO1 / $RAP ) * 100;
			$KO2_Persen = ($KO2 / $RAP ) * 100;

			$BLE1_Persen = ($BLE1 / $RAP ) * 100;
			$BLE2_Persen = ($BLE2 / $RAP ) * 100;


			$SaldoKO_Persen = ($SaldoKO / $RAP ) * 100;





			// $BuatArray[] = array(
			// 	'JobNo' => $JobNo,
			// 	'JobNm' => $JobNm,
			// 	'Tahun' => $Tahun,
			// 	'NettKontrak' => number_format($NettKontrak),
			// 	'TglPHO' => tgl_baru($TglPHO),
			// 	'RAP' => number_format(($RAP == 1) ? 0 : $RAP),
			// 	'RAP_Persen' => uang_koma_genap_decimal_kedua($RAP_Persen,

			// 	'KO1' => number_format($KO1),
			// 	'KO1_Persen' => uang_koma_genap_decimal_kedua($KO1_Persen,
			// 	'KO2' => number_format($KO2),
			// 	'KO2_Persen' => uang_koma_genap_decimal_kedua($KO2_Persen,

			// 	'BLE1' => number_format($BLE1),
			// 	'BLE2' => number_format($BLE2),
			// 	'BLE1_Persen' => uang_koma_genap_decimal_kedua($BLE1_Persen,
			// 	'BLE2_Persen' => uang_koma_genap_decimal_kedua($BLE2_Persen,

			// 	'Invoice1' => number_format($Invoice1),
			// 	'TtlInv' => number_format($TtlInv),
			// 	'SaldoKO' => number_format($SaldoKO),
			// 	'SaldoKO_Persen' => uang_koma_genap_decimal_kedua($SaldoKO_Persen,
			// 	'Invoice2' => number_format($Invoice2),

			// );

			$BuatArray[] = array(
				'JobNo' => $JobNo,
				'JobNm' => $JobNm,
				'Tahun' => $Tahun,
				'NettKontrak' => $NettKontrak,
				'TglPHO' => tgl_baru($TglPHO),
				'RAP' => ($RAP == 1) ? 0 : $RAP,
				'RAP_Persen' => $RAP_Persen,

				'KO1' => $KO1,
				'KO1_Persen' => $KO1_Persen,
				'KO2' => $KO2,
				'KO2_Persen' => $KO2_Persen,

				'BLE1' => $BLE1,
				'BLE2' => $BLE2,
				'BLE1_Persen' => $BLE1_Persen,
				'BLE2_Persen' => $BLE2_Persen,

				'Invoice1' => $Invoice1,
				'TtlInv' => $TtlInv,
				'SaldoKO' => $SaldoKO,
				'SaldoKO_Persen' => $SaldoKO_Persen,
				'Invoice2' => $Invoice2,

			);

		endforeach;

		return $BuatArray;
	}

	private function validasi_JobNo(){
		$Company = $this->config->item('Company');
		$query = $this->db->query("SELECT JobNo, JobNm FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND (StatusJob='Pelaksanaan' OR StatusJob='Pemeliharaan') AND Company='$Company' ")->result_array();
		return $query;
	}


	function get_table_HutangKO(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$PrdAwal = $this->input->post('PrdAwal', TRUE);
		$PrdAkhir = $this->input->post('PrdAkhir', TRUE);

		$data['JobNo'] = $JobNo;
		$data['PrdAwal'] = $PrdAwal;
		$data['PrdAkhir'] = $PrdAkhir;


		$query_coba = $this->db->query("
			BEGIN

			DECLARE @JobNo nvarchar(10)
			DECLARE @DrTgl date
			DECLARE @SpTgl date


			SET @JobNo = '$JobNo'
			SET @DrTgl = '$PrdAwal'
			SET @SpTgl = '$PrdAkhir'

			SELECT 
			a.JobNo, 
			a.JobNm,  
			a.TglKontrak,
			a.Netto, 
			a.PersenKSO,
			@DrTgl AS DrTgl,
			@SpTgl AS SpTgl,
			a.Netto * IIF(a.PersenKSO=0,100,a.PersenKSO) / 100 AS 'NettKontrak',
			IIF(@DrTgl=NULL,'1900-01-01',@DrTgl) AS 'TglPHO',
			(SELECT ISNULL(SUM(Vol*HrgSatuan),0) FROM RAP WHERE JobNo=a.JobNo And Alokasi='B' AND Tipe='Detail') AS 'RAP'
			FROM Job a WHERE JobNo IN (@JobNo) ORDER BY JobNo DESC

			END")->result_array();

		$data['data'] = $query_coba;
		$this->load->view('laporan_view/procurement/table_hutangKO', $data);

	}


	function get_table_HutangKO_lama(){

		$JobNo = $this->input->post('JobNo', TRUE);
		$PrdAwal = $this->input->post('PrdAwal', TRUE);
		$PrdAkhir = $this->input->post('PrdAkhir', TRUE);

		$data['JobNo'] = $JobNo;
		$data['PrdAwal'] = $PrdAwal;
		$data['PrdAkhir'] = $PrdAkhir;
	}

	function tes_ya(){

		$DrTgl = '2022-07-01';
		$SpTgl = '2022-07-30';
		$query_coba = $this->db->query("
			BEGIN

			DECLARE @JobNo nvarchar(10)
			DECLARE @DrTgl date
			DECLARE @SpTgl date

			DECLARE @JobNm nvarchar(100)
			DECLARE @Netto money
			DECLARE @PersenKSO decimal(5,2)

			SET @JobNo = '20031'
			SET @DrTgl = '2022-07-01'
			SET @SpTgl = '2022-07-30'
			SET @JobNm = (SELECT JobNm FROM Job WHERE JobNo=@JobNo)
			SET @Netto = (SELECT Netto FROM Job WHERE JobNo=@JobNo)
			SET @PersenKSO = (SELECT PersenKSO FROM Job WHERE JobNo=@JobNo)

			SELECT 
			a.JobNo, 
			a.JobNm,  
			a.Netto, 
			a.PersenKSO,
			@DrTgl AS DrTgl,
			@SpTgl AS SpTgl,
			a.Netto * IIF(a.PersenKSO=0,100,a.PersenKSO) AS 'NettKontrak',
			IIF(a.PrdAwal=NULL,'1900-01-01',a.PrdAwal) AS 'TglPHO',
			(SELECT ISNULL(SUM(Vol*HrgSatuan),0) FROM RAP WHERE JobNo=a.JobNo And Alokasi='B' AND Tipe='Detail') AS 'RAP'
			FROM Job a WHERE a.JobNo=@JobNo ORDER BY JobNo DESC
			END")->result_array();

		$data['data'] = $query_coba;
		$this->load->view('tes_ya',$data);
	}

	function PdfExport_HutangKO($JobNo,$DariTgl,$SampaiTgl){
		if ($JobNo == '') {
			redirect('Welcome','refresh');
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;

		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;

		if ($JobNo == 'All Job') {
			$JobNo = $this->pembuatan_koma_implode_JobNo();
		}

		$data['dataHutangKO'] = $this->QueryHutangKO($JobNo,$DariTgl,$SampaiTgl);
		$this->load->view('laporan_view/procurement/pdf_tengah_HutangKO',$data);
	}

	function ExcelHutangKO($JobNo,$DariTgl,$SampaiTgl){
		if ($JobNo == '') {
			redirect('Welcome','refresh');
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;

		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;

		if ($JobNo == 'All Job') {
			$JobNo = $this->pembuatan_koma_implode_JobNo();
		}

		$dataHutangKO = $this->QueryHutangKO($JobNo,$DariTgl,$SampaiTgl);

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$style_col = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
			],
		];

		$style_row = [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
			]
		];

		$spreadsheet->getActiveSheet()->getStyle('A5:R5')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('7f8c8d');

		$sheet->setCellValue('A1', "Rekap Hutang KO");
		$sheet->mergeCells('A1:E1');
		$sheet->getStyle('A1')->getFont()->setBold(true);

		$sheet->setCellValue('A2', "Job No : ");
		$sheet->setCellValue('B2', $JobNo);
		$sheet->mergeCells('B2:C2');
		$sheet->getStyle('A2')->getFont()->setBold(false);

		$sheet->setCellValue('A3', "Periode : ");
		$sheet->setCellValue('B3',tgl_baru($DariTgl)." s.d ".tgl_baru($SampaiTgl));
		$sheet->mergeCells('B3:C3');
		$sheet->getStyle('A3')->getFont()->setBold(false);



		$sheet->setCellValue('A4', "");
		$sheet->mergeCells('A4:E4');
		$sheet->getStyle('A4')->getFont()->setBold(false);

		$sheet->setCellValue('F4', "RAP");
		$sheet->mergeCells('F4:G4');
		$sheet->getStyle('F4')->getFont()->setBold(true);
		$sheet->getStyle('F5')->applyFromArray($style_col);

		$sheet->setCellValue('H4', "Total KO dlm Periode");
		$sheet->mergeCells('H4:I4');
		$sheet->getStyle('H4')->getFont()->setBold(true);

		$sheet->setCellValue('J4', "Total KO");
		$sheet->mergeCells('J4:K4');
		$sheet->getStyle('J4')->getFont()->setBold(true);

		$sheet->setCellValue('L4', "Total Pembayaran KO dlm periode ini");
		$sheet->mergeCells('L4:M4');
		$sheet->getStyle('L4')->getFont()->setBold(true);

		$sheet->setCellValue('N4', "Total Pembayaran KO");
		$sheet->mergeCells('N4:O4');
		$sheet->getStyle('N4')->getFont()->setBold(true);

		$sheet->setCellValue('P4', "Saldo KO");
		$sheet->mergeCells('P4:Q4');
		$sheet->getStyle('P4')->getFont()->setBold(true);

		$sheet->setCellValue('A5', "No");
		$sheet->setCellValue('B5', "Job No");
		$sheet->setCellValue('C5', "Job Name");
		$sheet->setCellValue('D5', "Tanggal PHO");
		$sheet->setCellValue('E5', "Netto Porsi Kontrak (Rp)");

		// ==== RAP =====
		$sheet->setCellValue('F5', "Rp");
		$sheet->setCellValue('G5', "% thd netto kontrak");
		// ==== END RAP =====

		// ==== Total KO dlm periode =====
		$sheet->setCellValue('H5', "Rp");
		$sheet->setCellValue('I5', "% thd RAP");
		// ==== End Total KO dlm periode =====

		// ==== Total KO =====
		$sheet->setCellValue('J5', "Rp");
		$sheet->setCellValue('K5', "% thd RAP");
		// ==== End Total KO  =====

		// ==== Total Pembayaran KO dlm periode ini =====
		$sheet->setCellValue('L5', "Rp");
		$sheet->setCellValue('M5', "% thd RAP");
		// ==== End Total Pembayaran KO dlm periode ini  =====

		// ==== Total Pembayaran KO =====
		$sheet->setCellValue('N5', "Rp");
		$sheet->setCellValue('O5', "% thd RAP");
		// ==== End Total Pembayaran KO =====

		// ==== Saldo KO =====
		$sheet->setCellValue('P5', "Rp");
		$sheet->setCellValue('Q5', "% thd RAP");
		// ==== End Saldo KO =====

		// ===== Total Hutang KO =====
		$sheet->setCellValue('R5', "");
		// ===== END Total Hutang KO ======

		$sheet->getStyle('A5')->applyFromArray($style_col);
		$sheet->getStyle('B5')->applyFromArray($style_col);
		$sheet->getStyle('C5')->applyFromArray($style_col);
		$sheet->getStyle('D5')->applyFromArray($style_col);
		$sheet->getStyle('E5')->applyFromArray($style_col);
		$sheet->getStyle('F5')->applyFromArray($style_col);
		$sheet->getStyle('G5')->applyFromArray($style_col);
		$sheet->getStyle('H5')->applyFromArray($style_col);
		$sheet->getStyle('I5')->applyFromArray($style_col);
		$sheet->getStyle('J5')->applyFromArray($style_col);
		$sheet->getStyle('K5')->applyFromArray($style_col);
		$sheet->getStyle('L5')->applyFromArray($style_col);
		$sheet->getStyle('M5')->applyFromArray($style_col);
		$sheet->getStyle('N5')->applyFromArray($style_col);

		$sheet->getStyle('O5')->applyFromArray($style_col);
		$sheet->getStyle('P5')->applyFromArray($style_col);
		$sheet->getStyle('Q5')->applyFromArray($style_col);
		$sheet->getStyle('R5')->applyFromArray($style_col);



		$no = 1;
		$numrow = 7;
		$row_subtitle = 0;

		$SubTotal = 0;

		$SubTotal_RAP = 0;
		$SubTotal_RAP_Persen = 0;

		$SubTotal_KO2 = 0;
		$SubTotal_KO2_Persen = 0;

		$SubTotal_KO1 = 0;
		$SubTotal_KO1_Persen = 0;

		$SubTotal_BLE1 = 0;
		$SubTotal_BLE1_Persen = 0;

		$SubTotal_BLE2 = 0;
		$SubTotal_BLE2_Persen = 0;

		$SubTotal_SaldoKO = 0;
		$SubTotal_SaldoKO_Persen = 0;

		$SubTotal_Invoice2 = 0;


		$GrandTotal = 0;
		$GrandTotal_RAP = 0;
		$GrandTotal_KO2 = 0;
		$GrandTotal_KO1 = 0;
		$GrandTotal_BLE1 = 0;
		$GrandTotal_BLE2 = 0;
		$GrandTotal_Invoice2 = 0;
		$GrandTotal_SaldoKO = 0;


		$NomorGrandTotal = 0;

		foreach ($dataHutangKO as $dataHutangKO) :

			$SubTotal += $dataHutangKO['NettKontrak'];

			// == RAP ==
			$SubTotal_RAP += $dataHutangKO['RAP'];
			$SubTotal_RAP_Persen += $dataHutangKO['RAP_Persen'];
			// == END RAP ==

			$SubTotal_KO1 += $dataHutangKO['KO1'];
			$SubTotal_KO1_Persen += $dataHutangKO['KO1_Persen'];


			$SubTotal_KO2 += $dataHutangKO['KO2'];
			$SubTotal_KO2_Persen += $dataHutangKO['KO2_Persen'];


			$SubTotal_BLE1 += $dataHutangKO['BLE1'];
			$SubTotal_BLE1_Persen += $dataHutangKO['BLE1_Persen'];

			$SubTotal_BLE2 += $dataHutangKO['BLE2'];
			$SubTotal_BLE2_Persen += $dataHutangKO['BLE2_Persen'];

			$SubTotal_SaldoKO += $dataHutangKO['SaldoKO'];
			$SubTotal_SaldoKO_Persen += $dataHutangKO['SaldoKO_Persen'];

			$SubTotal_Invoice2 += $dataHutangKO['Invoice2'];


			$GrandTotal += $SubTotal;
			$GrandTotal_RAP += $SubTotal_RAP;
			$GrandTotal_KO1 += $SubTotal_KO1;
			$GrandTotal_KO2 += $SubTotal_KO2;
			$GrandTotal_BLE1 += $SubTotal_BLE1;
			$GrandTotal_BLE2 += $SubTotal_BLE2;
			$GrandTotal_SaldoKO += $SubTotal_SaldoKO;
			$GrandTotal_Invoice2 += $SubTotal_Invoice2;

			$sheet->setCellValue('A6',"TAHUN ".date('Y',strtotime($dataHutangKO['TglPHO'])));
			$sheet->mergeCells('A6:N6');
			$sheet->getStyle('A6')->getFont()->setBold(true);
			$sheet->getStyle('A6')->applyFromArray($style_row);

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $dataHutangKO['JobNo']);
			$sheet->setCellValue('C' . $numrow, $dataHutangKO['JobNm']);
			$sheet->setCellValue('D' . $numrow, $dataHutangKO['TglPHO']);
			$sheet->setCellValue('E' . $numrow, number_format($dataHutangKO['NettKontrak']));

			$sheet->setCellValue('F' . $numrow, number_format($dataHutangKO['RAP']));
			$sheet->setCellValue('G' . $numrow, uang_koma_genap_decimal_kedua($dataHutangKO['RAP_Persen']).'%');

			$sheet->setCellValue('H' . $numrow, number_format($dataHutangKO['KO2']));
			$sheet->setCellValue('I' . $numrow, uang_koma_genap_decimal_kedua($dataHutangKO['KO2_Persen']).'%');

			$sheet->setCellValue('J' . $numrow, number_format($dataHutangKO['KO1']));
			$sheet->setCellValue('K' . $numrow, uang_koma_genap_decimal_kedua($dataHutangKO['KO1_Persen']).'%');

			$sheet->setCellValue('L' . $numrow, number_format($dataHutangKO['BLE2']));
			$sheet->setCellValue('M' . $numrow, uang_koma_genap_decimal_kedua($dataHutangKO['BLE2_Persen']).'%');

			$sheet->setCellValue('N' . $numrow, number_format($dataHutangKO['BLE1']));
			$sheet->setCellValue('O' . $numrow, uang_koma_genap_decimal_kedua($dataHutangKO['BLE1_Persen']).'%');

			$sheet->setCellValue('P' . $numrow, number_format($dataHutangKO['SaldoKO']));
			$sheet->setCellValue('Q' . $numrow, uang_koma_genap_decimal_kedua($dataHutangKO['SaldoKO_Persen']).'%');

			$sheet->setCellValue('R' . $numrow, number_format($dataHutangKO['Invoice2']));

			$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('I' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('J' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('K' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('L' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('M' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('N' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('O' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('P' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('Q' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('R' . $numrow)->applyFromArray($style_row);

			$no++;
			$numrow++;

			$row_subtitle = $numrow;
			$NomorGrandTotal = $numrow+1;
		endforeach;



		$new_gaya = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
		];


		$sheet->setCellValue('A' . $row_subtitle, "SUBTOTAL");
		$sheet->mergeCells('A' . $row_subtitle . ':D' . $row_subtitle);
		$sheet->getStyle('A'.$row_subtitle)->getFont()->setBold(true);

		$sheet->setCellValue('E' . $row_subtitle, number_format($SubTotal));
		$sheet->setCellValue('F' . $row_subtitle, number_format($SubTotal_RAP));

		$sheet->setCellValue('H' . $row_subtitle, number_format($SubTotal_KO2));
		$sheet->setCellValue('I' . $row_subtitle, "");

		$sheet->setCellValue('J' . $row_subtitle, number_format($SubTotal_KO1));
		$sheet->setCellValue('K' . $row_subtitle, "");

		$sheet->setCellValue('L' . $row_subtitle, number_format($SubTotal_BLE2));
		$sheet->setCellValue('M' . $row_subtitle, "");

		$sheet->setCellValue('N' . $row_subtitle, number_format($SubTotal_BLE1));
		$sheet->setCellValue('O' . $row_subtitle, "");

		$sheet->setCellValue('P' . $row_subtitle, number_format($SubTotal_SaldoKO));
		$sheet->setCellValue('Q' . $row_subtitle, "");

		$sheet->setCellValue('R' . $row_subtitle, number_format($SubTotal_Invoice2));

		// === GRAND TOTAL ==
		$sheet->setCellValue('A' . $NomorGrandTotal, "GRAND TOTAL");
		$sheet->mergeCells('A' . $NomorGrandTotal . ':D' . $NomorGrandTotal);
		$sheet->getStyle('A'.$NomorGrandTotal)->getFont()->setBold(true);

		$sheet->setCellValue('E' . $NomorGrandTotal, number_format($GrandTotal));
		$sheet->setCellValue('F' . $NomorGrandTotal, number_format($GrandTotal_RAP));

		$sheet->setCellValue('H' . $NomorGrandTotal, number_format($GrandTotal_KO2));
		$sheet->setCellValue('I' . $NomorGrandTotal, "");

		$sheet->setCellValue('J' . $NomorGrandTotal, number_format($GrandTotal_KO1));
		$sheet->setCellValue('K' . $NomorGrandTotal, "");

		$sheet->setCellValue('L' . $NomorGrandTotal, number_format($GrandTotal_BLE2));
		$sheet->setCellValue('M' . $NomorGrandTotal, "");

		$sheet->setCellValue('N' . $NomorGrandTotal, number_format($GrandTotal_BLE1));
		$sheet->setCellValue('O' . $NomorGrandTotal, "");

		$sheet->setCellValue('P' . $NomorGrandTotal, number_format($GrandTotal_SaldoKO));
		$sheet->setCellValue('Q' . $NomorGrandTotal, "");

		$sheet->setCellValue('R' . $NomorGrandTotal, number_format($GrandTotal_Invoice2));
	

			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);

			// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        	// Set judul file excel nya
		$sheet->setTitle("Rekap Hutang KO");

			// exit;
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="RekapHutangKO'.date('ymdHis').'.xlsx"');
		header('Cache-Control: max-age=0');

			// exit;
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');


	}


}

/* End of file Rekap_Hutang_KO.php */
/* Location: ./application/controllers/Report/Procurement/Rekap_Hutang_KO.php */