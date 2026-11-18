<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FrmDipa extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company=$this->config->item('Company');
	}

	public function ExportExcel_Dipa($JobNo='')
	{
		if ($JobNo=='') {
			echo 'Job tidak ditemukan';
			die;
		}

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

		$sheet->setCellValue('A1', "No");
		$sheet->setCellValue('B1', "Tahun");
		$sheet->setCellValue('C1', "DIPA (Rp)");
		$sheet->setCellValue('D1', "DIPA Pagu Bruto (Rp)");

		$sheet->getStyle('A1')->applyFromArray($style_col);
		$sheet->getStyle('B1')->applyFromArray($style_col);
		$sheet->getStyle('C1')->applyFromArray($style_col);
		$sheet->getStyle('D1')->applyFromArray($style_col);

		$no = 1;
		$numrow = 2;
		$sumBudget=0;
		$sumPaguBudget=0;

		$tbldipa = $this->db->query("SELECT * FROM DIPA WHERE JobNo='$JobNo'")->result();

		foreach ($tbldipa as $row) :

			$sumBudget += (float) $row->Budget;
			$sumPaguBudget += (float) $row->PaguBudget;

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $row->Tahun);
			$sheet->setCellValue('C' . $numrow, UbahGabunganUang1($row->Budget));
			$sheet->setCellValue('D' . $numrow, UbahGabunganUang1($row->PaguBudget));

			$no++;
			$numrow++;
		endforeach;
		// print_rr($tbldipa);
		// die;

		$new_gaya = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
		];

		$new_gaya1 = [
			'font' => ['bold' => true],
		];


		// die;
		$sheet->setCellValue('A' . $numrow, "TOTAL");
		$sheet->mergeCells('A' . $numrow . ':B' . $numrow);
		$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya1);

		$sheet->setCellValue('C' . $numrow, number_format($sumBudget));
		$sheet->getStyle('C' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('D' . $numrow, number_format($sumPaguBudget));
		$sheet->getStyle('D' . $numrow)->applyFromArray($new_gaya);
		// die;
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->setTitle("dipa");
		// die;
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="dipa.xlsx"');
		header('Cache-Control: max-age=0');
		// exit;
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function ExportExcel_RencanaTermin($JobNo=''){
		if ($JobNo=='') {
			echo 'Job tidak ditemukan';
			die;
		}

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

		$sheet->setCellValue('A1', "No");
		$sheet->setCellValue('B1', "Tgl Rencana Termin");
		$sheet->setCellValue('C1', "Jenis");
		$sheet->setCellValue('D1', "Uraian");
		$sheet->setCellValue('E1', "Presentase (%)");
		$sheet->setCellValue('F1', "Bruto (Rp)");
		$sheet->setCellValue('G1', "Netto (Rp)");

		$sheet->getStyle('A1')->applyFromArray($style_col);
		$sheet->getStyle('B1')->applyFromArray($style_col);
		$sheet->getStyle('C1')->applyFromArray($style_col);
		$sheet->getStyle('D1')->applyFromArray($style_col);
		$sheet->getStyle('E1')->applyFromArray($style_col);
		$sheet->getStyle('F1')->applyFromArray($style_col);
		$sheet->getStyle('G1')->applyFromArray($style_col);

		$no = 1;
		$numrow = 2;
		$sumBruto=0;
		$sumNetto=0;

		$tblRtermin = $this->db->query("SELECT * FROM RencanaTermin WHERE JobNo='$JobNo'")->result();

		foreach ($tblRtermin as $row) :

			$sumBruto += (float) $row->Bruto;
			$sumNetto += (float) $row->Netto;

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, tgl_baru($row->TglRencana));
			$sheet->setCellValue('C' . $numrow, $row->Jenis);
			$sheet->setCellValue('D' . $numrow, $row->Uraian);
			$sheet->setCellValue('E' . $numrow, $row->Persentase);
			$sheet->setCellValue('F' . $numrow, UbahGabunganUang1($row->Bruto));
			$sheet->setCellValue('G' . $numrow, UbahGabunganUang1($row->Netto));

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

		$new_gaya1 = [
			'font' => ['bold' => true],
		];


		$sheet->setCellValue('A' . $numrow, "TOTAL");
		$sheet->mergeCells('A' . $numrow . ':E' . $numrow);
		$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya1);

		$sheet->setCellValue('F' . $numrow, number_format($sumBruto));
		$sheet->getStyle('F' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('G' . $numrow, number_format($sumNetto));
		$sheet->getStyle('G' . $numrow)->applyFromArray($new_gaya);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->setTitle("Rencana Termin");

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="RencanaTermin.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

}

/* End of file FrmDipa.php */
/* Location: ./application/controllers/FrmDipa.php */