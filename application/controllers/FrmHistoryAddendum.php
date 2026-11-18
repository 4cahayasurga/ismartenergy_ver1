<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FrmHistoryAddendum extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	public function ExportExcel_HistoryAddendum($JobNo='')
	{
		if ($JobNo=='') {
			echo 'Error, Job tidak ditemukan';
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
		$sheet->setCellValue('B1', "No.Kontrak/Addendum");
		$sheet->setCellValue('C1', "Tgl Kontrak/Addendum");
		$sheet->setCellValue('D1', "Addendum Ke");
		$sheet->setCellValue('E1', "Pagu Bruto Kontrak (Rp)");
		$sheet->setCellValue('F1', "Pagu Netto Kontrak (Rp)");
		$sheet->setCellValue('G1', "Bruto Kontrak (Rp)");
		$sheet->setCellValue('H1', "Netto Kontrak (Rp)");
		$sheet->setCellValue('I1', "Tgl Mulai Kontrak");
		$sheet->setCellValue('J1', "Tgl Selesai Kontrak");
		$sheet->setCellValue('K1', "Keterangan Addendum");

		$sheet->getStyle('A1')->applyFromArray($style_col);
		$sheet->getStyle('B1')->applyFromArray($style_col);
		$sheet->getStyle('C1')->applyFromArray($style_col);
		$sheet->getStyle('D1')->applyFromArray($style_col);
		$sheet->getStyle('E1')->applyFromArray($style_col);
		$sheet->getStyle('F1')->applyFromArray($style_col);
		$sheet->getStyle('G1')->applyFromArray($style_col);
		$sheet->getStyle('H1')->applyFromArray($style_col);
		$sheet->getStyle('I1')->applyFromArray($style_col);
		$sheet->getStyle('J1')->applyFromArray($style_col);
		$sheet->getStyle('K1')->applyFromArray($style_col);

		$no = 1;
		$numrow = 2;
		$sum_PaguBrutoKontrak=0;
		$sum_PaguNettoKontrak=0;
		$sum_BrutoKontrak=0;
		$sum_NettoKontrak=0;

		$addendum = $this->db->query("SELECT * FROM JobH WHERE JobNo='$JobNo' ORDER BY TimeEntry DESC ")->result();

		foreach ($addendum as $jb) : 

			$sum_PaguBrutoKontrak += (float) $jb->PaguBruto;
			$sum_PaguNettoKontrak += (float) $jb->PaguNetto;
			$sum_BrutoKontrak += (float) $jb->Bruto;
			$sum_NettoKontrak += (float) $jb->Netto;

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $jb->NoKontrak);
			$sheet->setCellValue('C' . $numrow, TglIndoGarisMiring_tanpa_waktu($jb->TglKontrak));
			$sheet->setCellValue('D' . $numrow, $jb->AddendumKe);
			$sheet->setCellValue('E' . $numrow, UbahGabunganUang($jb->PaguBruto));
			$sheet->setCellValue('F' . $numrow, UbahGabunganUang($jb->PaguNetto));
			$sheet->setCellValue('G' . $numrow, UbahGabunganUang($jb->Bruto));
			$sheet->setCellValue('H' . $numrow, UbahGabunganUang($jb->Netto));
			$sheet->setCellValue('I' . $numrow, TglIndoGarisMiring_tanpa_waktu($jb->PrdAwal));
			$sheet->setCellValue('J' . $numrow, TglIndoGarisMiring_tanpa_waktu($jb->PrdAkhir));
			$sheet->setCellValue('K' . $numrow, $jb->RemarkAddendum);

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

		// die;

		$sheet->setCellValue('A' . $numrow, "TOTAL");
		$sheet->mergeCells('A' . $numrow . ':D' . $numrow);
		$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya1);

		$sheet->setCellValue('E' . $numrow, UbahGabunganUang($sum_PaguBrutoKontrak));
		$sheet->getStyle('E' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('F' . $numrow, UbahGabunganUang($sum_PaguNettoKontrak));
		$sheet->getStyle('F' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('G' . $numrow, UbahGabunganUang($sum_BrutoKontrak));
		$sheet->getStyle('G' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('H' . $numrow, UbahGabunganUang($sum_NettoKontrak));
		$sheet->getStyle('H' . $numrow)->applyFromArray($new_gaya);
		

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->setTitle("History Addendum");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Histrory Addendum.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');

		
	}

}

/* End of file FrmHistoryAddendum.php */
/* Location: ./application/controllers/FrmHistoryAddendum.php */