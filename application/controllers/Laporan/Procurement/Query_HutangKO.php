<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Query_HutangKO extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('Laporan_model/M_query_hutangko');
		$this->load->helper('bantu');
	}

	private function get_ListJob()
	{
		$Company = $this->config->item('Company');
		$query = $this->db->query(" SELECT JobNo,JobNm FROM Job WHERE Company='$Company' ");
		return $query->result_array();
	}

	private function get_ListVendor()
	{
		$query = $this->db->query(" SELECT VendorId,VendorNm FROM Vendor ORDER BY VendorNm ");
		return $query->result_array();
	}



	public function index()
	{

		$data['judul'] = 'QUERY HUTANG KO';
		$data['bodyclass'] = 'sidebar-collapse skin-red';

		$data['DetailJob'] = $this->get_ListJob();
		$data['DetailVendor'] = $this->get_ListVendor();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('laporan_view/procurement/query_hutangko/list_data_query_hutangko', $data);
		$this->load->view('templates/footer');
	}


	function getTable_QueryHutangKO()
	{
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$VendorId = $this->input->post('VendorId', TRUE);
		$Status = $this->input->post('Status', TRUE);

		$getData = $this->M_query_hutangko->QueryPDF($JobNo, $VendorId, $Status);
		$data['dataKO'] = $getData;

		// print_rr($getData);
		// exit;

		$data['JobNo'] = $JobNo;
		$data['VendorId'] = $VendorId;
		$data['Status'] = $Status;

		// $array = array();
		$TotalKO = 0;
		$PaymentKO = 0;
		$RemainingKO = 0;
		$TotalInv = 0;
		$PaymentInv = 0;
		$RemainingInv = 0;

		foreach ($getData as $row) :
			$TotalKO += $row['TotalKO'];
			$PaymentKO += $row['PaymentKO'];

			$RemainingKO += $row['RemainingKO'];
			$TotalInv += $row['TotalInv'];
			$PaymentInv += $row['PaymentInv'];
			$RemainingInv += $row['RemainingInv'];
		endforeach;

		$data['TotalKO'] = $TotalKO;
		$data['PaymentKO'] = $PaymentKO;
		$data['RemainingKO'] = $RemainingKO;
		$data['TotalInv'] = $TotalInv;
		$data['PaymentInv'] = $PaymentInv;
		$data['RemainingInv'] = $RemainingInv;

		// echo $TotalKO;

		$this->load->view('laporan_view/procurement/query_hutangko/table_QueryHutangKO_JSON', $data);
	}



	function getJSON_QqueryHutangKO()
	{
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$VendorId = $this->input->post('VendorId', TRUE);
		$Status = $this->input->post('Status', TRUE);

		header('Content-Type: application/json');
		echo $this->M_query_hutangko->getJSON_QqueryHutangKO($JobNo, $VendorId, $Status);
	}

	function getDetail_QueryHutangKO()
	{
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$NoKO = $this->input->post('NoKO', TRUE);


		$data['NoKO'] = $NoKO;

		$query2 = [];
		$query = $this->db->query("
			SELECT *,
			ISNULL((SELECT SUM(PaymentAmount) FROM BLE, InvPD, PdHdr 
			WHERE (BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND 
			PdHdr.NoKO=Invoice.NoKO AND 
			InvPD.NoPD=PdHdr.NoPD AND 
			InvPD.InvNo=Invoice.InvNo),0) AS PaymentAmount
			FROM Invoice WHERE NoKO='$NoKO'")->result();

		foreach ($query as $row) {
			$query_cek_ko = $this->db->query("
					SELECT A.InvNo, A.NoPD,
					ISNULL((SELECT SUM(PaymentAmount) 
					FROM BLE, InvPD, PdHdr 
					WHERE (BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND 
					PdHdr.NoKO=InvPD.NoKO AND InvPD.NoPD=PdHdr.NoPD AND 
					InvPD.NoKO=A.NoKO AND InvPD.InvNo=A.InvNo AND InvPD.NoPD=A.NoPD),0) AS BLEAmount
					FROM InvPD A 
					WHERE NoKO='$row->NoKO' AND InvNo='$row->InvNo'")->result();
			foreach ($query_cek_ko as $row_ko) {
				$query2[] = [
					"InvNo" => $row_ko->InvNo,
					"NoPD" => $row_ko->NoPD,
					"BLEAmount" => $row_ko->BLEAmount,
				];
			}
		}

		$data["data"] = [
			"data1" => $query,
			"data2" => $query2
		];
		echo json_encode($data);
	}
	function getDetail_QueryHutangKO2()
	{
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$NoKO = $this->input->post('NoKO', TRUE);


		$data['NoKO'] = $NoKO;

		$query = $this->db->query("
			SELECT *,
			ISNULL((SELECT SUM(PaymentAmount) FROM BLE, InvPD, PdHdr 
			WHERE (BLE.NoPD=PdHdr.NoPD OR BLE.NoPD=PdHdr.NoRef) AND 
			PdHdr.NoKO=Invoice.NoKO AND 
			InvPD.NoPD=PdHdr.NoPD AND 
			InvPD.InvNo=Invoice.InvNo),0) AS PaymentAmount
			FROM Invoice WHERE NoKO='$NoKO'")->result_array();
		$data['detail'] = $query;
		$this->load->view('laporan_view/procurement/query_hutangko/detail_QueryHutangKO', $data);
	}

	public function export_excel($JobNo, $VendorId, $Status, $no)
	{
		// GET DATA FOR EXCEL
		$Company = $this->config->item('Company');
		$getJob = $this->db->get_where('Job', ['Company' => $Company, 'JobNo' => $JobNo])->row_array();
		if ($VendorId != 'ALL') {
			$getVendor = $this->db->get_where('Vendor', ['VendorId' => $VendorId])->row_array();
			$VendorNm = $getVendor['VendorNm'];
		} else {
			$VendorNm = $VendorId;
		}
		$query = $this->M_query_hutangko->QueryPDF($JobNo, $VendorId, $Status);
		// END GET DATA FOR EXCEL

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// STYLE
		$styleTitle = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			]
		];

		$style_headerTable = [
			'font' => ['bold' => true],
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
			]
		];
		$style_row_center = [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
			]
		];
		$style_row_left = [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
			]
		];
		$style_row_right = [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
			]
		];
		// END STYLE

		// title 
		$sheet->mergeCells('A1:M1');
		$sheet->setCellValue('A1', "REPORT QUERY HUTANG KO");
		$sheet->getStyle('A1')->applyFromArray($styleTitle);
		$sheet->mergeCells('A2:M2');
		$sheet->setCellValue('A2', $JobNo . ' - ' . $getJob['JobNm']);
		$sheet->getStyle('A2')->applyFromArray($styleTitle);
		$sheet->mergeCells('A3:M3');
		$sheet->setCellValue('A3', 'Vendor : ' . $VendorNm);
		$sheet->getStyle('A3')->applyFromArray($styleTitle);
		// end title

		// header table
		$sheet->setCellValue('A5', 'No');
		$sheet->setCellValue('B5', 'Job No');
		$sheet->setCellValue('C5', 'Job Name');
		$sheet->setCellValue('D5', 'No. KO');
		$sheet->setCellValue('E5', 'Tanggal KO');
		$sheet->setCellValue('F5', 'Tipe KO');
		$sheet->setCellValue('G5', 'Vendor');
		$sheet->setCellValue('H5', 'Amount KO');
		$sheet->setCellValue('I5', 'Payment KO');
		$sheet->setCellValue('J5', 'Remaining KO');
		$sheet->setCellValue('K5', 'Amount Invoice');
		$sheet->setCellValue('L5', 'Payment Invoice');
		$sheet->setCellValue('M5', 'Remaining Invoice');

		$sheet->getStyle('A5')->applyFromArray($style_headerTable);
		$sheet->getStyle('B5')->applyFromArray($style_headerTable);
		$sheet->getStyle('C5')->applyFromArray($style_headerTable);
		$sheet->getStyle('D5')->applyFromArray($style_headerTable);
		$sheet->getStyle('E5')->applyFromArray($style_headerTable);
		$sheet->getStyle('F5')->applyFromArray($style_headerTable);
		$sheet->getStyle('G5')->applyFromArray($style_headerTable);
		$sheet->getStyle('H5')->applyFromArray($style_headerTable);
		$sheet->getStyle('I5')->applyFromArray($style_headerTable);
		$sheet->getStyle('J5')->applyFromArray($style_headerTable);
		$sheet->getStyle('K5')->applyFromArray($style_headerTable);
		$sheet->getStyle('L5')->applyFromArray($style_headerTable);
		$sheet->getStyle('M5')->applyFromArray($style_headerTable);

		$spreadsheet->getActiveSheet()->getStyle('A5:M5')->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('d8dee9');
		// end header table

		// start value table
		$no = 1;
		$numrow = 6;
		foreach ($query as $row) {
			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $row['JobNo']);
			$sheet->setCellValue('C' . $numrow, $row['JobNm']);
			$sheet->setCellValue('D' . $numrow, $row['NoKO']);
			$sheet->setCellValue('E' . $numrow, $row['TglKO']);
			$sheet->setCellValue('F' . $numrow, $row['KategoriId']);
			$sheet->setCellValue('G' . $numrow, $row['VendorNm']);
			$sheet->setCellValue('H' . $numrow, $row['TotalKO']);
			$sheet->setCellValue('I' . $numrow, $row['PaymentKO']);
			$sheet->setCellValue('J' . $numrow, $row['RemainingKO']);
			$sheet->setCellValue('K' . $numrow, $row['TotalInv']);
			$sheet->setCellValue('L' . $numrow, $row['PaymentInv']);
			$sheet->setCellValue('M' . $numrow, $row['RemainingInv']);

			$sheet->getStyle('A' . $numrow)->applyFromArray($style_row_center);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_row_center);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_row_left);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_row_left);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_row_center);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_row_left);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_row_left);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_row_right);
			$sheet->getStyle('I' . $numrow)->applyFromArray($style_row_right);
			$sheet->getStyle('J' . $numrow)->applyFromArray($style_row_right);
			$sheet->getStyle('K' . $numrow)->applyFromArray($style_row_right);
			$sheet->getStyle('L' . $numrow)->applyFromArray($style_row_right);
			$sheet->getStyle('M' . $numrow)->applyFromArray($style_row_right);

			$no++;
			$numrow++;
		}
		// end value table

		// sum Footer
		$TotalKO = 0;
		$PaymentKO = 0;
		$RemainingKO = 0;
		$TotalInv = 0;
		$PaymentInv = 0;
		$RemainingInv = 0;

		foreach ($query as $row) :
			$TotalKO += $row['TotalKO'];
			$PaymentKO += $row['PaymentKO'];

			$RemainingKO += $row['RemainingKO'];
			$TotalInv += $row['TotalInv'];
			$PaymentInv += $row['PaymentInv'];
			$RemainingInv += $row['RemainingInv'];
		endforeach;

		$sheet->mergeCells('A' . $numrow . ':G' . $numrow);
		$sheet->setCellValue('A' . $numrow, 'SUM');
		$sheet->setCellValue('H' . $numrow, $TotalKO);
		$sheet->setCellValue('I' . $numrow, $PaymentKO);
		$sheet->setCellValue('J' . $numrow, $RemainingKO);
		$sheet->setCellValue('K' . $numrow, $TotalInv);
		$sheet->setCellValue('L' . $numrow, $PaymentInv);
		$sheet->setCellValue('M' . $numrow, $RemainingInv);
		$sheet->getStyle('A' . $numrow . ':L' . $numrow)->applyFromArray($style_headerTable);
		$sheet->getStyle('H' . $numrow)->applyFromArray($style_headerTable);
		$sheet->getStyle('I' . $numrow)->applyFromArray($style_headerTable);
		$sheet->getStyle('J' . $numrow)->applyFromArray($style_headerTable);
		$sheet->getStyle('K' . $numrow)->applyFromArray($style_headerTable);
		$sheet->getStyle('L' . $numrow)->applyFromArray($style_headerTable);
		$sheet->getStyle('M' . $numrow)->applyFromArray($style_headerTable);

		$spreadsheet->getActiveSheet()->getStyle('A' . $numrow . ':M' . $numrow)->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('d8dee9');

		// end sum Footer

		$sheet->getStyle('H:M')->getNumberFormat()->setFormatCode("#,###,0");
		$sheet->getColumnDimension('A')->setWidth(4);
		$sheet->getColumnDimension('B')->setWidth(6);
		$sheet->getColumnDimension('C')->setWidth(25);
		$sheet->getColumnDimension('D')->setWidth(10);
		$sheet->getColumnDimension('E')->setWidth(12);
		$sheet->getColumnDimension('F')->setWidth(10);
		$sheet->getColumnDimension('G')->setWidth(33);
		$sheet->getColumnDimension('H')->setWidth(15);
		$sheet->getColumnDimension('I')->setWidth(15);
		$sheet->getColumnDimension('J')->setWidth(15);
		$sheet->getColumnDimension('K')->setWidth(15);
		$sheet->getColumnDimension('L')->setWidth(15);
		$sheet->getColumnDimension('M')->setWidth(16);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
		$sheet->setTitle("Report Query Hutang KO");
		// Proses file excel
		// $tgl_hari_ini = date('ymdHis');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="report_query_hutangKO-' . date('ymdHis') . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function export_excel_detail($JobNo, $VendorId, $Status)
	{
		$getData = $this->M_query_hutangko->QueryDetailPembayaran($JobNo);
		$Company = $this->config->item('Company');
		$getJob = $this->db->get_where('Job', ['Company' => $Company, 'JobNo' => $JobNo])->row_array();
		if ($VendorId != 'ALL') {
			$getVendor = $this->db->get_where('Vendor', ['VendorId' => $VendorId])->row_array();
			$VendorNm = $getVendor['VendorNm'];
		} else {
			$VendorNm = $VendorId;
		}

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		 $styleTitle = [
			'font' => [
				'bold' => true
			],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ]
        ];
		$styleHeader = [
			'font' => ['bold' => true],
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
			],
		];
		 $styleLeft = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
		$styleRight = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
		$styleCenter = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];

		$sheet->setCellValue('A1', 'QUERY HUTANG KO');
		$sheet->setCellValue('A2', 'JOB : '.$JobNo.' - '.$getJob['JobNm']);
		$sheet->getStyle('A1')->applyFromArray($styleTitle);
		$sheet->getStyle('A2')->applyFromArray($styleTitle);

		$sheet->setCellValue('A4', 'No');
		$sheet->setCellValue('B4', 'JobNo');
		$sheet->setCellValue('C4', 'JobName');
		$sheet->setCellValue('D4', 'NoKO');
		$sheet->setCellValue('E4', 'Tgl KO');
		$sheet->setCellValue('F4', 'Kategori');
		$sheet->setCellValue('G4', 'Id Vendor');
		$sheet->setCellValue('H4', 'Nama Vendor');
		$sheet->setCellValue('I4', 'Total KO');
		$sheet->setCellValue('J4', 'Payement KO');
		$sheet->setCellValue('K4', 'Remaining KO');
		$sheet->setCellValue('L4', 'Total Inv');
		$sheet->setCellValue('M4', 'Payment Inv');
		$sheet->setCellValue('N4', 'Remaining Inv');
		$sheet->setCellValue('O4', 'Company');
		$sheet->setCellValue('P4', 'Tgl Bayar');
		
		$sheet->getStyle('A4')->applyFromArray($styleHeader);
		$sheet->getStyle('B4')->applyFromArray($styleHeader);
		$sheet->getStyle('C4')->applyFromArray($styleHeader);
		$sheet->getStyle('D4')->applyFromArray($styleHeader);
		$sheet->getStyle('E4')->applyFromArray($styleHeader);
		$sheet->getStyle('F4')->applyFromArray($styleHeader);
		$sheet->getStyle('G4')->applyFromArray($styleHeader);
		$sheet->getStyle('H4')->applyFromArray($styleHeader);
		$sheet->getStyle('I4')->applyFromArray($styleHeader);
		$sheet->getStyle('J4')->applyFromArray($styleHeader);
		$sheet->getStyle('K4')->applyFromArray($styleHeader);
		$sheet->getStyle('L4')->applyFromArray($styleHeader);
		$sheet->getStyle('M4')->applyFromArray($styleHeader);
		$sheet->getStyle('N4')->applyFromArray($styleHeader);
		$sheet->getStyle('O4')->applyFromArray($styleHeader);
		$sheet->getStyle('P4')->applyFromArray($styleHeader);


		$no=1;
		$numrow=5;
		foreach($getData as $row){
			$sheet->setCellValue('A'.$numrow, $no);
			$sheet->setCellValue('B'.$numrow, $row->JobNo);
			$sheet->setCellValue('C'.$numrow, $row->JobNm);
			$sheet->setCellValue('D'.$numrow, $row->NoKO);
			$sheet->setCellValue('E'.$numrow, $row->TglKO);
			$sheet->setCellValue('F'.$numrow, $row->KategoriId);
			$sheet->setCellValue('G'.$numrow, $row->VendorId);
			$sheet->setCellValue('H'.$numrow, $row->VendorNm);
			$sheet->setCellValue('I'.$numrow, $row->TotalKO);
			$sheet->setCellValue('J'.$numrow, $row->PaymentKO);
			$sheet->setCellValue('K'.$numrow, $row->RemainingKO);
			$sheet->setCellValue('L'.$numrow, $row->TotalInv);
			$sheet->setCellValue('M'.$numrow, $row->PaymentInv);
			$sheet->setCellValue('N'.$numrow, $row->RemainingInv);
			$sheet->setCellValue('O'.$numrow, $row->Company);
			$sheet->setCellValue('P'.$numrow, $row->TglBayar);

			$sheet->getStyle('A'.$numrow)->applyFromArray($styleCenter);
			$sheet->getStyle('B'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('C'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('D'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('E'.$numrow)->applyFromArray($styleCenter);
			$sheet->getStyle('F'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('G'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('H'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('I'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('J'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('K'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('L'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('M'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('N'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('O'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('P'.$numrow)->applyFromArray($styleLeft);


			$sheet->getStyle('I' . $numrow.':N'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
			$numrow++;
			$no++;
		}

		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(8);
		$sheet->getColumnDimension('C')->setWidth(28);
		$sheet->getColumnDimension('D')->setWidth(15);
		$sheet->getColumnDimension('E')->setWidth(12);
		$sheet->getColumnDimension('F')->setWidth(12);
		$sheet->getColumnDimension('G')->setWidth(12);
		$sheet->getColumnDimension('H')->setWidth(29);
		$sheet->getColumnDimension('I')->setWidth(18);
		$sheet->getColumnDimension('J')->setWidth(18);
		$sheet->getColumnDimension('K')->setWidth(18);
		$sheet->getColumnDimension('L')->setWidth(18);
		$sheet->getColumnDimension('M')->setWidth(18);
		$sheet->getColumnDimension('N')->setWidth(18);
		$sheet->getColumnDimension('O')->setWidth(8);
		$sheet->getColumnDimension('P')->setWidth(12);

		  // Set judul file excel nya
        $sheet->setTitle("Query Hutang KO");

        // Proses file excel
        // $tgl_hari_ini = date('ymdHis');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="query_hutang_ko-' . date('ymdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');


	}
	public function export_excel_tes_lama($JobNo)
	{
		$getJob = $this->db->get_where('Job', ['JobNo' => $JobNo])->row_array();
		$query = $this->db->query("SELECT 
			t1.NoKO as NoKO, 
			t2.InvNo,
			t1.SubTotal as TotalKO,
			t2.NoPD as NoPD, t5.Total as TotalInvoice, t3.TotalPD as TotalPD, t2.PaymentAmount as TotalBayar,  t4.TglBayar
			from
			KoHdr t1
			Join InvPD t2
			On t1.NoKO=t2.NoKO
			Join PdHdr t3
			On t2.NoPD=t3.NoPD
			join BLE t4
			On t3.NoPD=t4.NoPD
			join Invoice t5
			On t5.InvNo=t2.InvNo
			where t1.JobNo = '$JobNo'
			-- where t1.NoKO = 'KO21570020'
			-- where t1.NoKO = 'KO1483450'
			-- where t1.NoKO IN ('KO1483450', 'KO1483469', 'KO1483469', 'KO1483471')
			order by t1.NoKO ASC
				");
		
		$data = $query->result();
		$arr = [];
		foreach($data as $key => $row){
			if(isset($arr[$key-1]) && $arr[$key-1]['NoInv'] == $row->InvNo){
				$TotalInv = 0;
			}else{
				$TotalInv = $row->TotalInvoice;
			}
			$arr[] = [
				"NoKO" => $row->NoKO,
				"NoInv" => $row->InvNo,
				"TotalKO" => $row->TotalKO,
				"NoPD" => $row->NoPD,
				"TotalPD" => $row->TotalPD,
				"TotalInv" => $TotalInv,
				"TotalBayar" => $row->TotalBayar,
				"TglBayar" => $row->TglBayar,
			];
		}
		// print_rr($arr);die;

		// start excel
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$styleTitle = [
			'font' => [
				'bold' => true
			],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ]
        ];
		$styleHeader = [
			'font' => ['bold' => true],
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FFFF00', // Kode warna kuning dalam format ARGB
				],
			],
			 'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
		];
		$styleLeft = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				'indent' => 1
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
		$styleRight = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'indent' => 1
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
		$styleCenter = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
		$styleFooter = [
			'font' => ['bold' => true],
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'fab005'
				],
			],
			 'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
		];
		// Title
		$sheet->setCellValue('A2', 'Detail Query Hutang KO');
		$sheet->setCellValue('A3', 'Job Number : '. $getJob['JobNo'] . ' - '.$getJob['JobNm']);
		$sheet->getStyle('A2:A3')->applyFromArray($styleTitle);
		// table
		$sheet->setCellValue('A5', 'No');
		$sheet->setCellValue('B5', 'No KO');
		$sheet->setCellValue('C5', 'Total KO (Rp)');
		$sheet->setCellValue('D5', 'No PD');
		$sheet->setCellValue('E5', 'Total PD (Rp)');
		$sheet->setCellValue('F5', 'No Invoice (Rp)');
		$sheet->setCellValue('G5', 'Total Invoice (Rp)');
		$sheet->setCellValue('H5', 'Total Bayar (Rp)');
		$sheet->setCellValue('I5', 'Tanggal Bayar (Rp)');
		$sheet->setCellValue('J5', 'Sisa Invoice (Rp)');
		$sheet->setCellValue('K5', 'Sisa PD Belum Bayar (Rp)');
		$sheet->setCellValue('L5', 'Sisa KO (Rp)');
		$sheet->getStyle('A5')->applyFromArray($styleHeader);
		$sheet->getStyle('B5')->applyFromArray($styleHeader);
		$sheet->getStyle('C5')->applyFromArray($styleHeader);
		$sheet->getStyle('D5')->applyFromArray($styleHeader);
		$sheet->getStyle('E5')->applyFromArray($styleHeader);
		$sheet->getStyle('F5')->applyFromArray($styleHeader);
		$sheet->getStyle('G5')->applyFromArray($styleHeader);
		$sheet->getStyle('H5')->applyFromArray($styleHeader);
		$sheet->getStyle('I5')->applyFromArray($styleHeader);
		$sheet->getStyle('J5')->applyFromArray($styleHeader);
		$sheet->getStyle('K5')->applyFromArray($styleHeader);
		$sheet->getStyle('L5')->applyFromArray($styleHeader);
		$no=1;
		$numrow=6;
		$previousNoKO = '';
		$previousNoPD = '';
		$previousNoInv = '';
		$startMerge = $numrow;
		$startMerge2 = $numrow;
		$startMergeNoKO = $numrow;
		$totalInvoice = 0;
		foreach($arr as $key => $row){
			$sheet->setCellValue('A'.$numrow, $no);
			// $sheet->setCellValue('B'.$numrow, $row['NoKO']);
			// $sheet->setCellValue('C'.$numrow, $row['TotalKO']);
			$sheet->setCellValue('D'.$numrow, $row['NoPD']);
			if($previousNoKO == $row['NoKO']){
				$sheet->setCellValue('B'.$numrow, '');
				$sheet->setCellValue('C'.$numrow, '');
			}else{
				$sheet->setCellValue('B'.$numrow, $row['NoKO']);
				$sheet->setCellValue('C'.$numrow, $row['TotalKO']);
				$startMergeNoKO = $numrow;
			}
			if ($previousNoPD == $row['NoPD']) {
				$sheet->setCellValue('E'.$numrow, '');
				$sheet->setCellValue('J'.$numrow, '=SUM(J'.($numrow-1).')-H'.$numrow);
				$sheet->setCellValue('K'.$numrow, '=K'.($numrow-1).'-H'.$numrow);
			} else {
				$startMerge = $numrow;
				$sheet->setCellValue('E'.$numrow, $row['TotalPD']);
				$sheet->setCellValue('K'.$numrow, '=E'.$startMerge.'-H'.$numrow);
			}
			if($previousNoInv == $row['NoInv']){
				$sheet->setCellValue('G'.$numrow, '');
			}else{
				$sheet->setCellValue('G'.$numrow, $row['TotalInv']);
				$startMerge2 = $numrow;
			}
			$sheet->setCellValue('F'.$numrow, $row['NoInv']);
			$sheet->setCellValue('H'.$numrow, $row['TotalBayar']);
			$sheet->setCellValue('I'.$numrow, $row['TglBayar']);
			$sheet->getStyle('A'.$numrow)->applyFromArray($styleCenter);
			$sheet->getStyle('B'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('C'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('D'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('E'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('F'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('G'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('H'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('I'.$numrow)->applyFromArray($styleCenter);
			$sheet->getStyle('J'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('K'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('L'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('C'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
			$sheet->getStyle('E'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
			$sheet->getStyle('G' . $numrow.':H'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
			$sheet->getStyle('J' . $numrow.':L'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
			if (isset($arr[$key + 1])) {
				if ($arr[$key + 1]['NoPD'] != $row['NoPD']) {
					if ($startMerge != $numrow) {
						$sheet->mergeCells('E'.$startMerge.':E'.$numrow);
						$sheet->mergeCells('L'.$startMerge.':L'.$numrow);
						$sheet->setCellValue('J'.$startMerge, '=SUM(G'.$startMerge.':G'.$numrow.')-H'.$startMerge);
						$sheet->setCellValue('L'.$startMerge, '=C'.$startMergeNoKO.'-SUM(H'.$startMergeNoKO.':H'.$numrow.')');
					}else{
						$sheet->setCellValue('J'.$numrow, '=SUM(G'.$startMerge2.')-H'.$numrow);
						$sheet->setCellValue('K'.$numrow, '=E'.$numrow.'-H'.$numrow);
						$sheet->setCellValue('L'.$numrow, '=C'.$numrow.'-H'.$numrow);
					}
				}
			} else {
				if ($startMerge != $numrow) {
					$sheet->mergeCells('E'.$startMerge.':E'.$numrow);
					$sheet->mergeCells('L'.$startMerge.':L'.$numrow);
					$sheet->setCellValue('J'.$startMerge, '=SUM(G'.$startMerge.':G'.$numrow.')-H'.$startMerge);
				}else{
					$sheet->setCellValue('J'.$numrow, '=SUM(G'.$startMerge2.')-H'.$numrow);
					$sheet->setCellValue('K'.$numrow, '=E'.$startMerge.'-H'.$numrow);
					$sheet->setCellValue('L'.$numrow, '=C'.$numrow.'-H'.$numrow);
				}
			}
			if (isset($arr[$key + 1])) {
				if ($arr[$key + 1]['NoKO'] != $row['NoKO']) {
					if ($startMergeNoKO != $numrow) {
						$sheet->mergeCells('B'.$startMergeNoKO.':B'.$numrow);
						$sheet->mergeCells('C'.$startMergeNoKO.':C'.$numrow);
					}
				}
				if ($arr[$key + 1]['NoInv'] != $row['NoInv']) {
					if ($startMerge2 != $numrow) {
						$sheet->mergeCells('G'.$startMerge2.':G'.$numrow);
					}
				}
			} else {
				if ($startMergeNoKO != $numrow) {
					$sheet->mergeCells('B'.$startMergeNoKO.':B'.$numrow);
					$sheet->mergeCells('C'.$startMergeNoKO.':C'.$numrow);
				}
				if ($startMerge2 != $numrow) {
					$sheet->mergeCells('G'.$startMerge2.':G'.$numrow);
				}
			}
			$previousNoKO = $row['NoKO'];
			$previousNoPD = $row['NoPD'];
			$previousNoInv = $row['NoInv'];
			$no++;
			$numrow++;
		}
		// SUM FOOTER
		$sheet->mergeCells('A'.$numrow.':B'.$numrow);
		$sheet->setCellValue('A'.$numrow, 'SUM :');
		$sheet->setCellValue('C'.$numrow, '=SUM(C6:C'.($numrow-1).')');
		$sheet->setCellValue('E'.$numrow, '=SUM(E6:E'.($numrow-1).')');
		$sheet->setCellValue('G'.$numrow, '=SUM(G6:G'.($numrow-1).')');
		$sheet->setCellValue('H'.$numrow, '=SUM(H6:H'.($numrow-1).')');
		$sheet->setCellValue('L'.$numrow, '=SUM(L6:L'.($numrow-1).')');
		$sheet->getStyle('A'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('C'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('E'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('G'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('H'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('L'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('C'.$numrow.':L'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
		$conditional = new Conditional();
		$conditional->setConditionType(Conditional::CONDITION_CELLIS)
            ->setOperatorType(Conditional::OPERATOR_LESSTHANOREQUAL)
            ->addCondition('0');	
		$conditional->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_RED);
		$conditionalStyles = $sheet->getStyle('J6:J'.$numrow)->getConditionalStyles();
		$conditionalStyles = $sheet->getStyle('K6:K'.$numrow)->getConditionalStyles();
		$conditionalStyles = $sheet->getStyle('L6:L'.$numrow)->getConditionalStyles();
		$conditionalStyles[] = $conditional;
		$sheet->getStyle('J6:J'.$numrow)->setConditionalStyles($conditionalStyles);
		$sheet->getStyle('K6:K'.$numrow)->setConditionalStyles($conditionalStyles);
		$sheet->getStyle('L6:L'.$numrow)->setConditionalStyles($conditionalStyles);
		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(16);
		$sheet->getColumnDimension('C')->setWidth(18);
		$sheet->getColumnDimension('D')->setWidth(20);
		$sheet->getColumnDimension('E')->setWidth(19);
		$sheet->getColumnDimension('F')->setWidth(24);
		$sheet->getColumnDimension('G')->setWidth(19);
		$sheet->getColumnDimension('H')->setWidth(19);
		$sheet->getColumnDimension('I')->setWidth(19);
		$sheet->getColumnDimension('J')->setWidth(16);
		$sheet->getColumnDimension('K')->setWidth(22);
		$sheet->getColumnDimension('L')->setWidth(19);
		$sheet->setTitle("Detail Query Hutang KO");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="detail_query_hutang_ko-' . date('ymdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');		
	}
	public function export_excel_tes($JobNo)
	{
		$getJob = $this->db->get_where('Job', ['JobNo' => $JobNo])->row_array();
		$query = $this->db->query("SELECT 
			t1.JobNo,
			t1.NoKO, 
			t1.SubTotal as TotalKO,
			t2.NoPD,
			t2.InvNo,
			t2.PaymentAmount as TotalBayar, 
			t3.TotalPD,
			t4.TglBayar,
			(SELECT Total FROM Invoice where NoKO=t1.NoKO and InvNo=t2.InvNo) as TotalInvoice
			from
			KoHdr t1
			join InvPD t2 on t1.NoKO=t2.NoKO
			join PdHdr t3 on t2.NoPD=t3.NoPD
			join BLE t4 on t2.NoPD=t4.NoPD
			where t1.JobNo = '$JobNo'
			--where t1.NoKO IN ('KO1483450', 'KO1483469', 'KO1483469', 'KO1483471')
			");
		
		$data = $query->result();
		$arr = [];
		foreach($data as $key => $row){
			$arr[] = [
				"NoKO" => $row->NoKO,
				"NoInv" => $row->InvNo,
				"TotalKO" => $row->TotalKO,
				"NoPD" => $row->NoPD,
				"TotalPD" => $row->TotalPD,
				"TotalInv" => $row->TotalInvoice,
				"TotalBayar" => $row->TotalBayar,
				"TglBayar" => $row->TglBayar,
			];
		}
		// print_rr($arr);die;

		// start excel
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$styleTitle = [
			'font' => [
				'bold' => true
			],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
            ]
        ];
		$styleHeader = [
			'font' => ['bold' => true],
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FFFF00', // Kode warna kuning dalam format ARGB
				],
			],
			 'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
		];
		$styleLeft = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
				'indent' => 1
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
		$styleRight = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'indent' => 1
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
		$styleCenter = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
		$styleFooter = [
			'font' => ['bold' => true],
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			],
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'fab005'
				],
			],
			 'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
		];
		// Title
		$sheet->setCellValue('A2', 'Detail Query Hutang KO');
		$sheet->setCellValue('A3', 'Job Number : '. $getJob['JobNo'] . ' - '.$getJob['JobNm']);
		$sheet->getStyle('A2:A3')->applyFromArray($styleTitle);
		// table
		$sheet->setCellValue('A5', 'No');
		$sheet->setCellValue('B5', 'No KO');
		$sheet->setCellValue('C5', 'Total KO (Rp)');
		$sheet->setCellValue('D5', 'No PD');
		$sheet->setCellValue('E5', 'Total PD (Rp)');
		$sheet->setCellValue('F5', 'No Invoice (Rp)');
		$sheet->setCellValue('G5', 'Total Invoice (Rp)');
		$sheet->setCellValue('H5', 'Total Bayar (Rp)');
		$sheet->setCellValue('I5', 'Tanggal Bayar (Rp)');
		$sheet->setCellValue('J5', 'Sisa Invoice (Rp)');
		$sheet->setCellValue('K5', 'Sisa PD Belum Bayar (Rp)');
		$sheet->setCellValue('L5', 'Sisa KO (Rp)');
		$sheet->getStyle('A5')->applyFromArray($styleHeader);
		$sheet->getStyle('B5')->applyFromArray($styleHeader);
		$sheet->getStyle('C5')->applyFromArray($styleHeader);
		$sheet->getStyle('D5')->applyFromArray($styleHeader);
		$sheet->getStyle('E5')->applyFromArray($styleHeader);
		$sheet->getStyle('F5')->applyFromArray($styleHeader);
		$sheet->getStyle('G5')->applyFromArray($styleHeader);
		$sheet->getStyle('H5')->applyFromArray($styleHeader);
		$sheet->getStyle('I5')->applyFromArray($styleHeader);
		$sheet->getStyle('J5')->applyFromArray($styleHeader);
		$sheet->getStyle('K5')->applyFromArray($styleHeader);
		$sheet->getStyle('L5')->applyFromArray($styleHeader);
		$no=1;
		$numrow=6;
		$previousNoKO = '';
		$previousNoPD = '';
		$previousNoInv = '';
		$startMerge = $numrow;
		$startMerge2 = $numrow;
		$startMergeNoKO = $numrow;
		$totalInvoice = 0;
		foreach($arr as $key => $row){
			$sheet->setCellValue('A'.$numrow, $no);
			// $sheet->setCellValue('D'.$numrow, $row['NoPD']);
			if($previousNoKO == $row['NoKO']){
				$sheet->setCellValue('B'.$numrow, '');
				$sheet->setCellValue('C'.$numrow, '');
			}else{
				$sheet->setCellValue('B'.$numrow, $row['NoKO']);
				$sheet->setCellValue('C'.$numrow, $row['TotalKO']);
				$startMergeNoKO = $numrow;
			}
			if ($previousNoPD == $row['NoPD']) {
				$sheet->setCellValue('D'.$numrow, '');
				$sheet->setCellValue('E'.$numrow, '');
				$sheet->setCellValue('J'.$numrow, '=SUM(J'.($numrow-1).')-H'.$numrow);
				$sheet->setCellValue('K'.$numrow, '=K'.($numrow-1).'-H'.$numrow);
			} else {
				$startMerge = $numrow;
				$sheet->setCellValue('D'.$numrow, $row['NoPD']);
				$sheet->setCellValue('E'.$numrow, $row['TotalPD']);
				$sheet->setCellValue('K'.$numrow, '=E'.$startMerge.'-H'.$numrow);
			}
			if($previousNoInv == $row['NoInv']){
				$sheet->setCellValue('G'.$numrow, '');
			}else{
				$sheet->setCellValue('G'.$numrow, $row['TotalInv']);
				$startMerge2 = $numrow;
			}
			$sheet->setCellValue('F'.$numrow, $row['NoInv']);
			$sheet->setCellValue('H'.$numrow, $row['TotalBayar']);
			$sheet->setCellValue('I'.$numrow, $row['TglBayar']);
			$sheet->getStyle('A'.$numrow)->applyFromArray($styleCenter);
			$sheet->getStyle('B'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('C'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('D'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('E'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('F'.$numrow)->applyFromArray($styleLeft);
			$sheet->getStyle('G'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('H'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('I'.$numrow)->applyFromArray($styleCenter);
			$sheet->getStyle('J'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('K'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('L'.$numrow)->applyFromArray($styleRight);
			$sheet->getStyle('C'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
			$sheet->getStyle('E'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
			$sheet->getStyle('G' . $numrow.':H'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
			$sheet->getStyle('J' . $numrow.':L'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
			if (isset($arr[$key + 1])) {
				if ($arr[$key + 1]['NoKO'] != $row['NoKO']) {
					if ($startMergeNoKO != $numrow) {
						$sheet->mergeCells('B'.$startMergeNoKO.':B'.$numrow);
						$sheet->mergeCells('C'.$startMergeNoKO.':C'.$numrow);
					}
				}
				if ($arr[$key + 1]['NoInv'] != $row['NoInv']) {
					if ($startMerge2 != $numrow) {
						$sheet->mergeCells('G'.$startMerge2.':G'.$numrow);
					}
				}
			} else {
				if ($startMergeNoKO != $numrow) {
					$sheet->mergeCells('B'.$startMergeNoKO.':B'.$numrow);
					$sheet->mergeCells('C'.$startMergeNoKO.':C'.$numrow);
				}
				if ($startMerge2 != $numrow) {
					$sheet->mergeCells('G'.$startMerge2.':G'.$numrow);
				}
			}
			if (isset($arr[$key + 1])) {
				if ($arr[$key + 1]['NoPD'] != $row['NoPD']) {
					if ($startMerge != $numrow) {
						$sheet->mergeCells('D'.$startMerge.':D'.$numrow);
						$sheet->mergeCells('E'.$startMerge.':E'.$numrow);
						$sheet->mergeCells('L'.$startMerge.':L'.$numrow);
						$sheet->setCellValue('J'.$startMerge, '=SUM(G'.$startMerge.':G'.$numrow.')-H'.$startMerge);
						$sheet->setCellValue('L'.$startMerge, '=C'.$startMergeNoKO.'-SUM(H'.$startMergeNoKO.':H'.$numrow.')');
					}else{
						$sheet->setCellValue('J'.$numrow, '=SUM(G'.$startMerge2.')-H'.$numrow);
						$sheet->setCellValue('K'.$numrow, '=E'.$numrow.'-H'.$numrow);
						$sheet->setCellValue('L'.$numrow, '=C'.$startMergeNoKO.'-H'.$numrow);
					}
				}
			} else {
				if ($startMerge != $numrow) {
					$sheet->mergeCells('D'.$startMerge.':D'.$numrow);
					$sheet->mergeCells('E'.$startMerge.':E'.$numrow);
					$sheet->mergeCells('L'.$startMerge.':L'.$numrow);
					$sheet->setCellValue('J'.$startMerge, '=SUM(G'.$startMerge.':G'.$numrow.')-H'.$startMerge);
				}else{
					$sheet->setCellValue('J'.$numrow, '=SUM(G'.$startMerge2.')-H'.$numrow);
					$sheet->setCellValue('K'.$numrow, '=E'.$startMerge.'-H'.$numrow);
					$sheet->setCellValue('L'.$numrow, '=C'.$startMergeNoKO.'-H'.$numrow);
				}
			}
			
			$previousNoKO = $row['NoKO'];
			$previousNoPD = $row['NoPD'];
			$previousNoInv = $row['NoInv'];
			$no++;
			$numrow++;
		}
		// SUM FOOTER
		$sheet->mergeCells('A'.$numrow.':B'.$numrow);
		$sheet->setCellValue('A'.$numrow, 'SUM :');
		$sheet->setCellValue('C'.$numrow, '=SUM(C6:C'.($numrow-1).')');
		$sheet->setCellValue('E'.$numrow, '=SUM(E6:E'.($numrow-1).')');
		$sheet->setCellValue('G'.$numrow, '=SUM(G6:G'.($numrow-1).')');
		$sheet->setCellValue('H'.$numrow, '=SUM(H6:H'.($numrow-1).')');
		$sheet->setCellValue('L'.$numrow, '=SUM(L6:L'.($numrow-1).')');
		$sheet->getStyle('A'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('C'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('E'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('G'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('H'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('L'.$numrow)->applyFromArray($styleFooter);
		$sheet->getStyle('C'.$numrow.':L'.$numrow)->getNumberFormat()->setFormatCode('#,##0');
		$conditional = new Conditional();
		$conditional->setConditionType(Conditional::CONDITION_CELLIS)
            ->setOperatorType(Conditional::OPERATOR_LESSTHANOREQUAL)
            ->addCondition('0');	
		$conditional->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_RED);
		$conditionalStyles = $sheet->getStyle('J6:J'.$numrow)->getConditionalStyles();
		$conditionalStyles = $sheet->getStyle('K6:K'.$numrow)->getConditionalStyles();
		$conditionalStyles = $sheet->getStyle('L6:L'.$numrow)->getConditionalStyles();
		$conditionalStyles[] = $conditional;
		$sheet->getStyle('J6:J'.$numrow)->setConditionalStyles($conditionalStyles);
		$sheet->getStyle('K6:K'.$numrow)->setConditionalStyles($conditionalStyles);
		$sheet->getStyle('L6:L'.$numrow)->setConditionalStyles($conditionalStyles);
		$sheet->getColumnDimension('A')->setWidth(5);
		$sheet->getColumnDimension('B')->setWidth(16);
		$sheet->getColumnDimension('C')->setWidth(18);
		$sheet->getColumnDimension('D')->setWidth(20);
		$sheet->getColumnDimension('E')->setWidth(19);
		$sheet->getColumnDimension('F')->setWidth(24);
		$sheet->getColumnDimension('G')->setWidth(19);
		$sheet->getColumnDimension('H')->setWidth(19);
		$sheet->getColumnDimension('I')->setWidth(19);
		$sheet->getColumnDimension('J')->setWidth(16);
		$sheet->getColumnDimension('K')->setWidth(22);
		$sheet->getColumnDimension('L')->setWidth(19);
		$sheet->setTitle("Detail Query Hutang KO");
        // Proses file excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="detail_query_hutang_ko-' . date('ymdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');		
	}
}

/* End of file Query_HutangKO.php */
/* Location: ./application/controllers/Laporan/Procurement/Query_HutangKO.php */