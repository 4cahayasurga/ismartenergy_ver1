<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class DaftarHarga extends CI_Controller {

	var $Company;

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('MyReport_model/Model_DaftarHarga');
	}

	function view(){


		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'DAFTAR HARGA (PROCUREMENT)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/procurement/daftar_harga/frm_harga', $data);
		$this->load->view('templates/footer');
	}

	function dapatkan_list_data_harga(){
		if (!$this->input->post()) {
			die;
		}

		if (!$this->input->post('flag')) {
			die;
		}

		$SubTotal = NULL;


		$this->load->view('report_view/procurement/daftar_harga/tbl_daftar_harga');

	}


	function list_harga_json(){
		if (!$this->input->post()) {
			die;
		}

		if (!$this->input->post('flag')) {
			die;
		}

		header('Content-Type: application/json');
		echo $this->Model_DaftarHarga->dapatkan_list_harga_json();


	}

	function PDF_dfthrg(){

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$data['judul'] = 'REKAP DAFTAR HARGA (PROCUREMENT)';
		$data['dataHrg'] = $this->Model_DaftarHarga->get_data_hrg();

		$this->load->view('report_view/procurement/daftar_harga/pdf_tengah',$data);

	}

	function ExpExcel_DaftarHarga($flag=''){
		if ($flag == '') {
			die;
		}

		if ($flag !='1') {
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

		$spreadsheet->getActiveSheet()->getStyle('A5:J5')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('7f8c8d');

		$sheet->setCellValue('A1', "REKAP DAFTAR HARGA (PROCUREMENT)");
		$sheet->mergeCells('A1:J1');
		$sheet->getStyle('A1')->getFont()->setBold(true);

		$sheet->setCellValue('A2', "");
		$sheet->setCellValue('B2', '');
		$sheet->mergeCells('B2:J2');
		$sheet->getStyle('A2')->getFont()->setBold(false);

		$sheet->setCellValue('A3', "");
		$sheet->setCellValue('B3', '');
		$sheet->mergeCells('B3:J3');
		$sheet->getStyle('A3')->getFont()->setBold(false);

		$sheet->setCellValue('A4', "");
		$sheet->setCellValue('B4',"");
		$sheet->mergeCells('B4:J4');
		$sheet->getStyle('A4')->getFont()->setBold(false);

		$sheet->setCellValue('A5', "No");
		$sheet->setCellValue('B5', "Uraian");
		$sheet->setCellValue('C5', "U.O.M");
		$sheet->setCellValue('D5', "Vol");
		$sheet->setCellValue('E5', "Harga Satuan (Rp)");
		$sheet->setCellValue('F5', "Total Harga (Rp)");
		$sheet->setCellValue('G5', "Vendor");
		$sheet->setCellValue('H5', "No KO");
		$sheet->setCellValue('I5', "Tgl KO");
		$sheet->setCellValue('J5', "Job Name");


		$sheet->getStyle('A5:J5')->getFont()->setBold(true);

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
		


		$getData = $this->Model_DaftarHarga->get_data_hrg()->result_array();


		$no = 1;
		$row = 6;

		// print_rr($getData);
		// exit;

		foreach($getData as $dataHrg){
			$sheet->setCellValue('A' . $row, $no);
			$sheet->setCellValue('B' . $row, $dataHrg['Uraian'] );
			$sheet->setCellValue('C' . $row, $dataHrg['Uom']);
			$sheet->setCellValue('D' . $row, new_decimal($dataHrg['Vol']));
			$sheet->setCellValue('E' . $row, UangBiasa($dataHrg['HrgSatuan']));
			$sheet->setCellValue('F' . $row, UangBiasa($dataHrg['TotalHarga']));
			$sheet->setCellValue('G' . $row, $dataHrg['VendorNm']);
			$sheet->setCellValue('H' . $row, $dataHrg['NoKO']);
			$sheet->setCellValue('I' . $row, tgl_baru_tahun_pendek($dataHrg['TglKO']));
			$sheet->setCellValue('J' . $row, $dataHrg['JobNm']);

			$sheet->getStyle('A' . $row)->applyFromArray($style_row);
			$sheet->getStyle('B' . $row)->applyFromArray($style_row);
			$sheet->getStyle('C' . $row)->applyFromArray($style_row);
			$sheet->getStyle('D' . $row)->applyFromArray($style_row);
			$sheet->getStyle('E' . $row)->applyFromArray($style_row);
			$sheet->getStyle('F' . $row)->applyFromArray($style_row);
			$sheet->getStyle('G' . $row)->applyFromArray($style_row);
			$sheet->getStyle('H' . $row)->applyFromArray($style_row);
			$sheet->getStyle('I' . $row)->applyFromArray($style_row);
			$sheet->getStyle('J' . $row)->applyFromArray($style_row);


			$no++;
			$row++;


		}

		$new_gaya = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
		];




		$sheet->getDefaultRowDimension()->setRowHeight(-1);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$sheet->setTitle("REKAP-DAFTAR-HARGA-PROCUREMENT");

         // exit;

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="RkpDaftarHarga-PROCUREMENT-' . date('ymdHis') . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	// private function dapatkan_list_harga_json(){
	// 	$this->load->library('Datatables');
	// 	$Company = $this->Company;

	// 	$this->db->query("
	// 		DROP TABLE IF EXISTS #tmp_daftar_harga
	// 		CREATE TABLE #tmp_daftar_harga(
	// 		LedgerNo bigint identity NOT NULL,
	// 		Uraian nvarchar(255) NULL,
	// 		Uom nvarchar(15) NULL,
	// 		Vol decimal(10, 3) NULL,
	// 		HrgSatuan money NULL,
	// 		TotalHarga money NULL,
	// 		VendorNm nvarchar(100) NULL,
	// 		NoKO  nvarchar(15) NULL,
	// 		TglKO date NULL,
	// 		KategoriId nvarchar(30) NULL, 
	// 		JobNo nvarchar(10) NULL,
	// 		JobNm nvarchar(100) NULL,
	// 	);
	// 	TRUNCATE TABLE #tmp_daftar_harga

	// 	INSERT INTO #tmp_daftar_harga (Uraian, Uom, Vol, HrgSatuan, TotalHarga, VendorNm, NoKO, TglKO, KategoriId, JobNo, JobNm)
	// 	SELECT A.Uraian, A.Uom, A.Vol, A.HrgSatuan, (A.Vol * A.HrgSatuan) AS 'TotalHarga',  D.VendorNm,  B.NoKO, B.TglKO, B.KategoriId, C.JobNo, C.JobNm  FROM KoDtl A  
	// 	LEFT JOIN KoHdr B ON A.NoKO=B.NoKO  
	// 	LEFT JOIN Job C ON B.JobNo=C.JobNo  
	// 	LEFT JOIN Vendor D ON B.VendorId=D.VendorId  
	// 	WHERE C.Company='$Company' ");

	// 	$this->datatables->select("*");
	// 	$this->datatables->from('#tmp_daftar_harga');
	// 	$this->datatables->edit_column('HrgSatuan', '$1', '$this->UangBiasa(HrgSatuan)');

	// 	return $this->datatables->generate();

	// }

	private function UangBiasa($uang)
	{
		return number_format($uang);
	}


}

/* End of file DaftarHarga.php */
/* Location: ./application/controllers/MyReport/Procurement/DaftarHarga.php */