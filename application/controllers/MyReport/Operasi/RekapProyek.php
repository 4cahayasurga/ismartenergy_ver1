<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use \Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RekapProyek extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('MyReport_model/M_rekap_proyek');
		$this->load->helper('bantu');
	}

	public function index()
	{

		$data['judul'] = 'REPORT OPERASI (REKAP PROYEK : Kontrak, Expense & Termin)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/operasi/rekap_proyek/view_rekap_proyek', $data);
		$this->load->view('templates/footer');
	}

	function getData(){
		if (!$this->input->post('Tahun',TRUE)) {
			die;
		}


		$Tahun = $this->input->post('Tahun',TRUE);
		// echo $Tahun;
		// die;

		$TglAwal = $Tahun.'-'.date('01-01');
		$TglAkhir = $Tahun.'-'.date('12-t');

		// echo $TglAkhir;
		// exit;

		$dapatRP = $this->M_rekap_proyek->getDataRekap($Tahun,$TglAwal,$TglAkhir);
		// echo $dapatRP;
		// exit;
		$data['dapatRP'] = $dapatRP->result_array();

		$this->load->view('report_view/operasi/rekap_proyek/tableRekapProgress', $data);
	}

	function getPDF_RP($Tahun=''){
		if ($Tahun == '') {
			die;
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$TglAwal = $Tahun.'-'.date('01-01');
		$TglAkhir = $Tahun.'-'.date('12-t');
		$dapatRP = $this->M_rekap_proyek->getDataRekap($Tahun,$TglAwal,$TglAkhir);
		$data['dapatRP'] = $dapatRP->result_array();

		$data['Tahun'] = $Tahun;

		$this->load->view('report_view/operasi/rekap_proyek/RP_pdf_tengah', $data);

	}


	function RP_export_excel($Tahun){
		if ($Tahun == '') {
			die;
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$Export_by = 'Export By '.$UserId.' ';


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
			]
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
		$sheet->setCellValue('A1', "REKAP PROYEK : Kontrak, Expense & Termin (REPORT)"); 
		$sheet->mergeCells('A1:E1'); 
		$sheet->getStyle('A1')->getFont()->setBold(true);

		$sheet->setCellValue('F1', $Export_by); 
		$sheet->mergeCells('F1:J1'); 
		$sheet->getStyle('F1')->getFont()->setBold(true);

		$sheet->setCellValue('A2', 'Tahun : '.$Tahun.' '); 
		$sheet->mergeCells('A2:E2'); 
		$sheet->getStyle('A2')->getFont()->setBold(true);

		$sheet->setCellValue('A3', "Job No");
		$sheet->setCellValue('B3', "Job Name"); 
		$sheet->setCellValue('C3', "Netto Kontrak Induk (Rp)");
		$sheet->setCellValue('D3', "% Share");
		$sheet->setCellValue('E3', "Netto Share (Rp)");
		$sheet->setCellValue('F3', "Total Alokasi A (Rp)");
		$sheet->setCellValue('G3', "Total Alokasi B (Rp)");
		$sheet->setCellValue('H3', "Total Alokasi C (Rp)");
		$sheet->setCellValue('I3', "Termin Induk (Rp)");
		$sheet->setCellValue('J3', "Termin Member (Rp)");

		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);
		$sheet->getStyle('F3')->applyFromArray($style_col);
		$sheet->getStyle('G3')->applyFromArray($style_col);
		$sheet->getStyle('H3')->applyFromArray($style_col);
		$sheet->getStyle('I3')->applyFromArray($style_col);
		$sheet->getStyle('J3')->applyFromArray($style_col);


		$SUM_Netto = 0;
		$SUM_NettoShare = 0;
		$SUM_AlokasiA = 0;
		$SUM_AlokasiB = 0;
		$SUM_AlokasiC = 0;
		$SUM_TerminInduk = 0;
		$SUM_TerminMember = 0;

		$TglAwal = $Tahun.'-'.date('01-01');
		$TglAkhir = $Tahun.'-'.date('12-t');

		$queryRekap = $this->M_rekap_proyek->getDataRekap($Tahun,$TglAwal,$TglAkhir)->result();

		$no = 1;
		$numrow = 4; 
		$row_untuk_sum=4;

		foreach($queryRekap as $data){ 

			$SUM_Netto +=  $data->Netto;
			$SUM_NettoShare += $data->NettoShare;
			$SUM_AlokasiA += $data->AlokasiA;
			$SUM_AlokasiB += $data->AlokasiB;
			$SUM_AlokasiC += $data->AlokasiC;
			$SUM_TerminInduk += $data->TerminInduk;
			$SUM_TerminMember += $data->TerminMember;


			$sheet->setCellValue('A'.$numrow, $data->JobNo);
			$sheet->setCellValue('B'.$numrow, $data->JobNm);
			$sheet->setCellValue('C'.$numrow, uang_koma_genap_decimal($data->Netto));
			$sheet->setCellValue('D'.$numrow, $data->PersenKSO);
			$sheet->setCellValue('E'.$numrow, uang_koma_genap_decimal($data->NettoShare));
			$sheet->setCellValue('F'.$numrow, uang_koma_genap_decimal($data->AlokasiA));
			$sheet->setCellValue('G'.$numrow, uang_koma_genap_decimal($data->AlokasiB));
			$sheet->setCellValue('H'.$numrow, uang_koma_genap_decimal($data->AlokasiC));
			$sheet->setCellValue('I'.$numrow, uang_koma_genap_decimal($data->TerminInduk));
			$sheet->setCellValue('J'.$numrow, uang_koma_genap_decimal($data->TerminMember));


			$sheet->getStyle('A'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('B'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('C'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('D'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('E'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('F'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('G'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('H'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('I'.$numrow)->applyFromArray($style_row);
			$sheet->getStyle('J'.$numrow)->applyFromArray($style_row);

			$no++; 
			$numrow++;
			$row_untuk_sum++;

		}

		$sheet->setCellValue('A'.$row_untuk_sum, '');
		$sheet->setCellValue('B'.$row_untuk_sum, '');
		$sheet->setCellValue('C'.$row_untuk_sum, 'SUM = '.uang_koma_genap_decimal($SUM_Netto));
		$sheet->setCellValue('D'.$row_untuk_sum, '');
		$sheet->setCellValue('E'.$row_untuk_sum, 'SUM = '.uang_koma_genap_decimal($SUM_NettoShare));
		$sheet->setCellValue('F'.$row_untuk_sum, 'SUM = '.uang_koma_genap_decimal($SUM_AlokasiA));
		$sheet->setCellValue('G'.$row_untuk_sum,'SUM = '.uang_koma_genap_decimal($SUM_AlokasiB));
		$sheet->setCellValue('H'.$row_untuk_sum,'SUM = '.uang_koma_genap_decimal($SUM_AlokasiC));
		$sheet->setCellValue('I'.$row_untuk_sum, 'SUM = '.uang_koma_genap_decimal($SUM_TerminInduk));
		$sheet->setCellValue('J'.$row_untuk_sum, 'SUM = '.uang_koma_genap_decimal($SUM_TerminMember));

		$sheet->getColumnDimension('A')->setWidth(20); 
		$sheet->getColumnDimension('B')->setWidth(20);
		$sheet->getColumnDimension('C')->setWidth(20); 
		$sheet->getColumnDimension('D')->setWidth(20); 
		$sheet->getColumnDimension('E')->setWidth(20);
		$sheet->getColumnDimension('F')->setWidth(20);
		$sheet->getColumnDimension('G')->setWidth(20);
		$sheet->getColumnDimension('H')->setWidth(20);
		$sheet->getColumnDimension('I')->setWidth(20);
		$sheet->getColumnDimension('J')->setWidth(20);


		$sheet->getDefaultRowDimension()->setRowHeight(-1);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$sheet->setTitle("REPORT ( REKAP PROYEK )");

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="RekapProyekReport-'.date("ymdHis").'.xlsx"'); 
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');

	}

}

/* End of file RekapProyek.php */
/* Location: ./application/controllers/MyReport/Operasi/RekapProyek.php */