<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RptQuery_PDPJ extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		redirect('FrmRptQueryPDPJ');

		if (!$this->session->userdata('MIS_LOGGED_ID')) {
			$this->session->sess_destroy();
			redirect('Auth/index');
		}

		if ($this->session->userdata('MIS_LOGGED_ID') == NULL) {
			$this->session->sess_destroy();
			redirect('Auth/index');
		}

		$this->load->model('Model_RptQuery_PDPJ');
	}

	

	public function index(){

		$data['bodyclass'] = 'sidebar-collapse skin-blue';
		$data['judul'] = 'Query PD/PJ';

		$data['ListJobNo'] = $this->Model_RptQuery_PDPJ->validasi_JobNo();
		$data['ListAlokasi'] = $this->Model_RptQuery_PDPJ->validasi_Alokasi();

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar');
		$this->load->view('RptQuery_PDPJ_view/main_RptQuery_PDPJ',$data);
		$this->load->view('templates/footer');
	}

	function tampilkan_table_QueryPDPJ_tanpa_server_side(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$JobNo_koma_implode = $this->Model_RptQuery_PDPJ->pembuatan_koma_implode_JobNo();
		$Alokasi_koma_implode = $this->Model_RptQuery_PDPJ->koma_Alokasi();


		$data['ListData_QueryPDPJ'] = $this->Model_RptQuery_PDPJ->dapatkan_data_QueryPDPJ($JobNo,$Alokasi);
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;


		$this->load->view('RptQuery_PDPJ_view/tbl_QueryPDPJ_tanpa_server_side',$data);

	}

	function tampilkan_table_QueryPDPJ(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$JobNo_koma_implode = $this->Model_RptQuery_PDPJ->pembuatan_koma_implode_JobNo();
		$Alokasi_koma_implode = $this->Model_RptQuery_PDPJ->koma_Alokasi();


		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;


		$this->load->view('RptQuery_PDPJ_view/table_QueryPDPJ',$data);

	}

	function data_json_QueryPDPJ(){

		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo', true);
		$Alokasi = $this->input->post('Alokasi', true);


		header('Content-Type: application/json');
		echo $this->get_data($JobNo, $Alokasi);
	}

	private function get_data($JobNo, $Alokasi){


		$Company = $this->config->item('Company');
		$QueryLanjutan = "";
		$QueryLanjutan = "WHERE C.Company='$Company' ";


		if ($JobNo == "All Job") {
			$JobList = $this->Model_RptQuery_PDPJ->pembuatan_koma_implode_JobNo();
			$QueryLanjutan = $QueryLanjutan."AND A.JobNo IN (".$JobList.")";
		}else{
			$QueryLanjutan = $QueryLanjutan." AND A.JobNo='$JobNo' ";
		}

		if ($Alokasi == "All Alokasi") {
			$listAlokasi = $this->Model_RptQuery_PDPJ->koma_Alokasi();
			$QueryLanjutan = $QueryLanjutan." AND A.Alokasi IN (".$listAlokasi.") ";
		}else{
			$QueryLanjutan = $QueryLanjutan." AND A.Alokasi='$Alokasi' ";
		}

		$myquery = $this->db->query("

			DROP TABLE IF EXISTS #temp_tbl_query_pdpj
			CREATE TABLE #temp_tbl_query_pdpj (

			LedgerNo bigint NOT NULL,
			JobNo nvarchar(10) NULL,
			JobNo1 ntext NULL,
			NoPD nvarchar(20) NULL,
			TglPD date NULL,
			NoRef nvarchar(50) NULL,
			Alokasi nvarchar(1) NULL,
			TipeForm nvarchar(10) NULL,
			Deskripsi nvarchar(255) NULL,
			NoKO nvarchar(15) NULL,
			SubTotal money NULL,
			DiscAmount money NULL,
			PPN money NULL,
			TotalKO money NULL,
			NoTagihan nvarchar(100) NULL,
			NoRek ntext NULL,
			AtasNama nvarchar(100) NULL,
			TotalPD money NULL,
			TglBayar date NULL,

			JenisTrf ntext NULL,
			BLEAmount money NULL,
			RekId ntext NULL,
			NoPJ nvarchar(20) NULL,
			TglPJ date NULL,
			TotalPJ money NULL,
			Saldo1 money NULL,

			);

			INSERT INTO #temp_tbl_query_pdpj (LedgerNo, JobNo, JobNo1, NoPD, TglPD, Alokasi, TipeForm, Deskripsi, NoKO, SubTotal, DiscAmount, PPN, TotalKO, NoTagihan, NoRek, AtasNama, TotalPD, TglBayar
			, JenisTrf, BLEAmount, RekId, NoPJ, TglPJ, TotalPJ, Saldo1)

			SELECT  A.LedgerNo, A.JobNo, A.JobNo + ' - ' + C.JobNm AS 'JobNo1', 
			A.NoPD, A.TglPD, A.NoRef, A.Alokasi, A.TipeForm, A.Deskripsi, A.NoKO, B.SubTotal, B.DiscAmount, B.PPN, 0, A.NoTagihan, A.NoRek, 
			A.AtasNama, A.TotalPD, 
			(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
			(SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
			(SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount',
			(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId',
			A.NoPJ, A.TglPJ, TotalPJ, 0
			FROM PdHdr A 
			LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
			LEFT JOIN Job C ON A.JobNo=C.JobNo 
			".$QueryLanjutan."  
			");

		$this->load->library('Datatables');

		$this->datatables->select("JobNo, NoPD, Alokasi");
		$this->datatables->from("#temp_tbl_query_pdpj");
		// $this->db->limit(1);
		// $this->datatables->edit_column('TglPD', '$1', 'tgl_baru(TglPD)');
        // $this->datatables->edit_column('TotalPD', '$1', 'number_format(TotalPD)');
        // $this->datatables->edit_column('TglBayar', '$1', 'tgl_baru(TglBayar)');
        // $this->datatables->edit_column('BLEAmount', '$1', 'UbahGabunganUang(BLEAmount)');
		// $this->datatables->edit_column('TglPJ', '$1', 'tgl_baru(TglPJ)');
        // $this->datatables->edit_column('TotalPJ', '$1', 'number_format(TotalPJ)');
        // $this->db->order_by('LedgerNo', 'DESC');
		return $this->datatables->generate();
	}


	function ExcelExport_QueryPDPJ(){

		if (empty($this->input->post('LedgerNo'))) {
			is_pesan('info','centang terlebih dahulu');
			redirect('RptQuery_PDPJ');
			// echo 'belum pilih';
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);
		$JobNo1 = $this->input->post('JobNo1',TRUE);

		// print_rr($_POST);
		// die;

		$LedgerNo = "";
		if ($this->input->post('LedgerNo')) {
			$LedgerNo = $this->input->post('LedgerNo');
			$LedgerNo = implode(",",$LedgerNo);

		}


		$Company = $this->config->item('Company');

		$myquery = $this->db->query("SELECT * FROM PdHdr WHERE LedgerNo IN (".$LedgerNo.") ")->result();

			// print_rr($myquery);
			// die;


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

			$spreadsheet->getActiveSheet()->getStyle('A5:V5')->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('7f8c8d');

			$sheet->setCellValue('A1', "REPORT QUERY PD/PJ");
			$sheet->mergeCells('A1:E1');
			$sheet->getStyle('A1')->getFont()->setBold(true);

			$sheet->setCellValue('A2', "Job No");
			$sheet->setCellValue('B2', $JobNo1);
			$sheet->mergeCells('B2:C2');
			$sheet->getStyle('A2')->getFont()->setBold(false);

			$sheet->setCellValue('A3', "Alokasi");
			$sheet->setCellValue('B3', $Alokasi);
			$sheet->mergeCells('B3:C3');
			$sheet->getStyle('A3')->getFont()->setBold(false);


			$sheet->setCellValue('A4', "");
			$sheet->setCellValue('B4', "");
			$sheet->mergeCells('B4:C4');
			$sheet->getStyle('A4')->getFont()->setBold(false);

			$sheet->setCellValue('A5', "No");
			$sheet->setCellValue('B5', "Job No");
			$sheet->setCellValue('C5', "No PD");
			$sheet->setCellValue('D5', "Tgl PD");
			$sheet->setCellValue('E5', "No Ref.Lapangan");
			$sheet->setCellValue('F5', "Alokasi");
			$sheet->setCellValue('G5', "Tipe Form");
			$sheet->setCellValue('H5', "Deskripsi");
			$sheet->setCellValue('I5', "No. KO");
			$sheet->setCellValue('J5', "Total KO (Rp)");
			$sheet->setCellValue('K5', "No. Tagihan");
			$sheet->setCellValue('L5', "Rekening Penerima");
			$sheet->setCellValue('M5', "Atas Nama");
			$sheet->setCellValue('N5', "Total PD (Rp)");


			// $sheet->setCellValue('A5', "No");
			// $sheet->setCellValue('B5', "");
			// $sheet->setCellValue('C5', "No PD");
			// $sheet->setCellValue('D5', "Alokasi");
			// $sheet->setCellValue('E5', "Tgl PD");
			// $sheet->setCellValue('F5', "No Ref.Lapangan");
			// $sheet->setCellValue('G5', "Alokasi");
			// $sheet->setCellValue('H5', "Tipe Form");
			// $sheet->setCellValue('I5', "Deskripsi");
			// $sheet->setCellValue('J5', "No. KO");
			// $sheet->setCellValue('K5', "Total KO (Rp)");
			// $sheet->setCellValue('L5', "No. Tagihan");
			// $sheet->setCellValue('M5', "Rekening Penerima");
			// $sheet->setCellValue('N5', "Atas Nama");

			//tambahan
			$sheet->setCellValue('O5', "Tgl Bayar");
			$sheet->setCellValue('P5', "Via");
			$sheet->setCellValue('Q5', "Total Pembayaran (Rp)");
			$sheet->setCellValue('R5', "Rekening Pengirim");
			$sheet->setCellValue('S5', "No PJ");
			$sheet->setCellValue('T5', "Tgl PJ");
			$sheet->setCellValue('U5', "Total PJ (Rp)");
			$sheet->setCellValue('V5', "Saldo (Rp)");




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

			//tambahan
			$sheet->getStyle('O5')->applyFromArray($style_col);
			$sheet->getStyle('P5')->applyFromArray($style_col);
			$sheet->getStyle('Q5')->applyFromArray($style_col);
			$sheet->getStyle('R5')->applyFromArray($style_col);
			$sheet->getStyle('S5')->applyFromArray($style_col);
			$sheet->getStyle('T5')->applyFromArray($style_col);
			$sheet->getStyle('U5')->applyFromArray($style_col);

			$sheet->getStyle('V5')->applyFromArray($style_col);
			// $sheet->getStyle('W5')->applyFromArray($style_col);


			$myquery = $this->db->query("SELECT A.JobNo + ' - ' + C.JobNm AS 'JobNo1', A.*, B.SubTotal, B.DiscAmount, B.PPN, 
				(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId', 
				(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar', 
				(SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf', 
				(SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount' 
				FROM PdHdr A 
				LEFT JOIN KoHdr B ON A.NoKO=B.NoKO 
				LEFT JOIN Job C ON A.JobNo=C.JobNo 
				WHERE C.Company='$Company' AND A.LedgerNo IN (".$LedgerNo.") ")->result();

				$no = 1;
				$numrow = 6;

				$TotalKO = 0;
				$Saldo1 = 0;

				$sumTotalPD = 0;
				$sumTotalPembayaran = 0;
				$sumTotalPJ = 0;

				foreach ($myquery as $key) :

					$sumTotalPD += $key->TotalPD;
					$sumTotalPembayaran += $key->BLEAmount;
					$sumTotalPJ += $key->TotalPJ;

					if ( ($key->NoKO !='' OR  $key->NoKO !=NULL OR $key->NoKO !='0') && substr($key->NoKO, 0, 2) == 'KO' ) {

						$SubTotal = $key->SubTotal;
						$DiscAmount = $key->DiscAmount;
						$PPN = $key->PPN;
						$TotalKO = ($SubTotal - $DiscAmount + $PPN);
					}

					$sheet->setCellValue('A' . $numrow, $no);
					$sheet->setCellValue('B' . $numrow, "");
					$sheet->setCellValue('C' . $numrow, $key->NoPD);
					$sheet->setCellValue('D' . $numrow, date('d-M-Y',strtotime($key->TglPD)));
					$sheet->setCellValue('E' . $numrow, $key->NoRef);
					$sheet->setCellValue('F' . $numrow, $key->Alokasi);
					$sheet->setCellValue('G' . $numrow, $key->TipeForm);
					$sheet->setCellValue('H' . $numrow, $key->Deskripsi);
					$sheet->setCellValue('I' . $numrow, $key->NoKO);
					$sheet->setCellValue('J' . $numrow, number_format($TotalKO));
					$sheet->setCellValue('K' . $numrow, $key->NoTagihan);
					$sheet->setCellValue('L' . $numrow, $key->NoRek);
					$sheet->setCellValue('M' . $numrow, $key->AtasNama);
					$sheet->setCellValue('N' . $numrow, number_format($key->TotalPD));

					$sheet->setCellValue('O' . $numrow, ($key->TglBayar == '1900-01-01' OR $key->TglBayar == NULL OR $key->TglBayar == '') ? '' : date('d-M-Y',strtotime($key->TglBayar)));
					$sheet->setCellValue('P' . $numrow, $key->JenisTrf);
					$sheet->setCellValue('Q' . $numrow, number_format($key->BLEAmount));
					$sheet->setCellValue('R' . $numrow, $key->RekId);
					$sheet->setCellValue('S' . $numrow, $key->NoPJ);
					$sheet->setCellValue('T' . $numrow, ($key->TglPJ == '1900-01-01' OR $key->TglPJ == NULL OR $key->TglPJ == '') ? '' : date('d-M-Y',strtotime($key->TglPJ)));
					$sheet->setCellValue('U' . $numrow, number_format($key->TotalPJ));
					if ($key->NoPJ == NULL OR $key->NoPJ == '') {
						$Saldo1 = 0;
					}else{
						$TtlPD = $key->TotalPD;
						$TtlPJ = $key->TotalPJ;
						$Saldo1 = $TtlPD + $TtlPJ;
					}	
					$sheet->setCellValue('V' . $numrow, number_format($Saldo1));



					// $sheet->setCellValue('A' . $numrow, $no);
					// $sheet->setCellValue('B' . $numrow, 0);
					// $sheet->setCellValue('C' . $numrow, 0);
					// $sheet->setCellValue('D' . $numrow, 0);
					// $sheet->setCellValue('E' . $numrow, 0);
					// $sheet->setCellValue('F' . $numrow, 0);
					// $sheet->setCellValue('G' . $numrow, 0);
					// $sheet->setCellValue('H' . $numrow, 0);
					// $sheet->setCellValue('I' . $numrow, 0);
					// $sheet->setCellValue('J' . $numrow, 0);
					// $sheet->setCellValue('K' . $numrow, 0);
					// $sheet->setCellValue('L' . $numrow, 0);
					// $sheet->setCellValue('M' . $numrow, 0);
					// $sheet->setCellValue('N' . $numrow, 0);


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
					$sheet->getStyle('S' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('T' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('U' . $numrow)->applyFromArray($style_row);
					$sheet->getStyle('V' . $numrow)->applyFromArray($style_row);
					// $sheet->getStyle('w' . $numrow)->applyFromArray($style_row);

					$no++;
					$numrow++;
				endforeach;


				$new_gaya = [
					'font' => ['bold' => true],
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
						'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
					],
				];

				$sheet->setCellValue('A' . $numrow, "Sum = ".number_format($sumTotalPD));
				$sheet->mergeCells('A' . $numrow . ':N' . $numrow);
				$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya);

				$sheet->setCellValue('P' . $numrow, "Sum = ".number_format($sumTotalPembayaran));
				$sheet->mergeCells('P' . $numrow . ':Q' . $numrow);
				$sheet->getStyle('P' . $numrow)->applyFromArray($new_gaya);

				$sheet->setCellValue('S' . $numrow, "Sum = ".number_format($sumTotalPJ));
				$sheet->mergeCells('S' . $numrow . ':U' . $numrow);
				$sheet->getStyle('S' . $numrow)->applyFromArray($new_gaya);

				// $sheet->setCellValue('J' . $numrow, new_decimal($sumTotalPD));
				// $sheet->getStyle('J' . $numrow)->applyFromArray($new_gaya);


				$sheet->getColumnDimension('A')->setWidth(5);
				$sheet->getColumnDimension('B')->setWidth(15);
				$sheet->getColumnDimension('C')->setWidth(25);
				$sheet->getColumnDimension('D')->setWidth(20);
				$sheet->getColumnDimension('E')->setWidth(30);
				$sheet->getColumnDimension('F')->setWidth(30);
				$sheet->getColumnDimension('G')->setWidth(30);
				$sheet->getColumnDimension('H')->setWidth(30);
				$sheet->getColumnDimension('I')->setWidth(30);
				$sheet->getColumnDimension('J')->setWidth(30);
				$sheet->getColumnDimension('K')->setWidth(30);
				$sheet->getColumnDimension('L')->setWidth(30);
				$sheet->getColumnDimension('M')->setWidth(30);
				$sheet->getColumnDimension('N')->setWidth(30);

				// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
				$sheet->getDefaultRowDimension()->setRowHeight(-1);

				// Set orientasi kertas jadi LANDSCAPE
				$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        		// Set judul file excel nya
				$sheet->setTitle("Report Query PDPJ");

        		// Proses file excel
        		// $tgl_hari_ini = date('ymdHis');
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename="report_query_pdpj-' . date('ymdHis') . '.xlsx"');
				header('Cache-Control: max-age=0');

				$writer = new Xlsx($spreadsheet);
				$writer->save('php://output');


				// print_rr($myquery);
			}


		}

		/* End of file RptQuery_PDPJ.php */
/* Location: ./application/controllers/RptQuery_PDPJ.php */