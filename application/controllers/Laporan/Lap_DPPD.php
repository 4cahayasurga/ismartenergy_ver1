<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Lap_DPPD extends CI_Controller
{

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('Laporan_model/Model_DPPD');
	}

	function index()
	{

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Daftar Permintaan Dana & Pertanggungjawaban Dana';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';

		$data['tgl_pertama'] = date('Y-m-01');
		$data['tgl_terakhir'] = date('Y-m-t');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('laporan_view/dppd/view_dppd', $data);
		$this->load->view('templates/footer');
	}

	private function pembuatan_dppd($JobNo, $Alokasi, $dari_tgl, $sampai_tgl, $all_periode)
	{

		$data_dppd = $this->Model_DPPD->dapat_data_dppd($JobNo, $Alokasi, $dari_tgl, $sampai_tgl, $all_periode);

		$VendorId = '';
		$VendorNm = '';
		$RekPenerima = '';
		$CaraBayar = '';

		$RekPengirim = '';

		$nomor_urut = 1;
		$myarray = array();

		$sumTotalPD = 0;
		$sumTotalBayar = 0;
		foreach ($data_dppd->result_array() as $myData) :

			$VendorNm = $myData['Nama'];
			$RekPenerima = $myData['Bank'] . '/' . $myData['AtasNama'] . '/' . $myData['NoRek'];
			$RekPengirim = '';


			$queryRek = $this->db->where('RekId', $myData['RekId'])->get('Rekening');

			if ($queryRek->num_rows() > 1) {
				$rowRek = $queryRek->row();
				$RekPengirim = $rowRek->Bank . ' / ' . $rowRek->AtasNama . '/' . $rowRek->NoRek;
			}

			$sumTotalPD += $myData['TotalPD'];
			$sumTotalBayar += $myData['TotalBayar'];

			$myarray[] = array(
				'nomor_urut' => $nomor_urut,
				'NoPD' => $myData['NoPD'],
				'NoRef' => $myData['NoRef'],
				'TglPD' => $myData['TglPD'],
				'Deskripsi' => $myData['Deskripsi'],
				'PrdAwal' => $myData['PrdAwal'],
				'PrdAkhir' => $myData['PrdAkhir'],
				'Alokasi' => $myData['Alokasi'],
				'NoKO' => $myData['NoKO'],
				'NoTagihan' => $myData['NoTagihan'],
				'Nama' => $myData['Nama'],
				'VendorId' => $VendorId,
				'VendorNm	' => $VendorNm,

				'TotalPD' => $myData['TotalPD'],
				'TotalBayar' => $myData['TotalBayar'],
				'TglBayar' => $myData['TglBayar'],
				'CaraBayar' => $myData['CaraBayar'],
				'NoPJ' => $myData['NoPJ'],
				'TglPJ' => $myData['TglPJ'],
				'TotalPJ' => $myData['TotalPJ'],
				'TipeForm' => $myData['TipeForm'],
				'RekPengirim' => $RekPengirim,
				'RekPenerima' => $RekPenerima,
				'RekId' => $myData['RekId'],
				'SumTotalBayar' => 0

			);

			$nomor_urut++;

		endforeach;

		$dataSession = [
			'myarray' => $myarray,
			'sumtotalpd' => $sumTotalPD,
			'sumtotalbayar' => $sumTotalBayar
		];


		// $this->session->set_userdata('session_lapDPPD', $myarray);
		$this->session->set_userdata('session_lapDPPD', $dataSession);

		return $dataSession;
	}

	function view_table_dppd()
	{
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo', true);
		$Alokasi = $this->input->post('Alokasi', true);
		$dari_tgl = $this->input->post('dari_tgl', true);
		$sampai_tgl = $this->input->post('sampai_tgl', true);
		$all_periode = $this->input->post('all_periode', true);

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['dari_tgl'] = $dari_tgl;
		$data['sampai_tgl'] = $sampai_tgl;
		$data['all_periode'] = $all_periode;

		if ($all_periode == 1) {
			$dari_tgl = '';
			$sampai_tgl = '';
		}

		$myarray = $this->pembuatan_dppd($JobNo, $Alokasi, $dari_tgl, $sampai_tgl, $all_periode);

		$data['myarray'] = $myarray['myarray'];
		$data['sumtotalpd'] = $myarray['sumtotalpd'];
		$data['sumtotalbayar'] = $myarray['sumtotalbayar'];

		$this->load->view('laporan_view/dppd/table_dppd', $data);
	}



	function createPDF_RptDPPD($JobNo, $Alokasi, $dari_tgl, $sampai_tgl, $all_periode)
	{

		if ($JobNo == '' || $Alokasi == '' ||  $dari_tgl == '' || $sampai_tgl == '' || $all_periode == '') {
			redirect('Welcome', 'refresh');
		}

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['dari_tgl'] = $dari_tgl;
		$data['sampai_tgl'] = $sampai_tgl;
		$data['all_periode'] = $all_periode;

		if ($all_periode == 1) {
			$dari_tgl = '';
			$sampai_tgl = '';
		}

		$myarray = $this->pembuatan_dppd($JobNo, $Alokasi, $dari_tgl, $sampai_tgl, $all_periode);
		$data['myarray'] = $myarray;

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');

		$query_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'")->row_array();

		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;
		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $query_job['JobNm'];




		$this->load->view('laporan_view/dppd/myprint_folder/pdf_tengah_dppd', $data);
	}

	public function exportExcelWithHeader($JobNo, $Alokasi, $dari_tgl, $sampai_tgl)
	{
		if ($JobNo == '' || $Alokasi == '' ||  $dari_tgl == '' || $sampai_tgl == '') {
			redirect('Welcome', 'refresh');
		}
		$getDataSession = $this->session->userdata('session_lapDPPD');
		$dataSession = $getDataSession['myarray'];
		$sumtotalpd = $getDataSession['sumtotalpd'];
		$sumtotalbayar = $getDataSession['sumtotalbayar'];

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$styleAllBorder = [
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
		];
		$styleHeader = 	[
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 1,
			],
		];
		$styleLeft = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
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
		$styleRight = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
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
		$styleCenter = [
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

		$query_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'")->row_array();
		$titleJob = $JobNo . ' - ' . $query_job['JobNm'];
		$getAlokasi = $this->db->query("SELECT * FROM Alokasi WHERE Alokasi = '$Alokasi'")->row_array();
		$titleAlokasi = $getAlokasi['Alokasi'] . ' - ' . $getAlokasi['Keterangan'];
		$titlePeriode = 'Periode : ' . tgl_indo($dari_tgl) . ' s/d ' . tgl_indo($sampai_tgl);

		$sheet->mergeCells('A2:L2');
		$sheet->mergeCells('A3:L3');
		$sheet->mergeCells('A4:L4');
		$sheet->mergeCells('A5:L5');
		$sheet->setCellValue('A2', 'DAFTAR PERMINTAAN DANA & PERTANGGUNG JAWABAN DANA');
		$sheet->setCellValue('A3', $titleJob);
		$sheet->setCellValue('A4', $titleAlokasi);
		$sheet->setCellValue('A5', $titlePeriode);
		$sheet->getStyle('A2:L5')->applyFromArray($styleHeader);
		$sheet->getStyle('A2')->applyFromArray(['font' => ['bold' => true]]);
		$sheet->getStyle('A2')->getFont()->setSize(14);

		$sheet->setCellValue('A10', 'No');
		$sheet->setCellValue('B10', 'No PD');
		$sheet->setCellValue('C10', 'Tgl PD');
		$sheet->setCellValue('D10', 'Deskripsi');
		$sheet->setCellValue('E10', 'Prd Awal');
		$sheet->setCellValue('F10', 'Prd Akhir');
		$sheet->setCellValue('G10', 'No KO');
		$sheet->setCellValue('H10', 'Nama');
		$sheet->setCellValue('I10', 'Total PD');
		$sheet->setCellValue('J10', 'Total Bayar');
		$sheet->setCellValue('K10', 'Tgl Bayar');
		$sheet->setCellValue('L10', 'Via');
		$sheet->setCellValue('M10', 'Rek Pengirim');
		$sheet->setCellValue('N10', 'Rek Penerima');
		$sheet->setCellValue('O10', 'No PJ');
		$sheet->setCellValue('P10', 'Tgl PJ');
		$sheet->setCellValue('Q10', 'Total PJ');
		$sheet->setCellValue('R10', 'Tipe Form');

		$sheet->getStyle('A10')->applyFromArray($styleHeader);
		$sheet->getStyle('B10')->applyFromArray($styleHeader);
		$sheet->getStyle('C10')->applyFromArray($styleHeader);
		$sheet->getStyle('D10')->applyFromArray($styleHeader);
		$sheet->getStyle('E10')->applyFromArray($styleHeader);
		$sheet->getStyle('F10')->applyFromArray($styleHeader);
		$sheet->getStyle('G10')->applyFromArray($styleHeader);
		$sheet->getStyle('H10')->applyFromArray($styleHeader);
		$sheet->getStyle('I10')->applyFromArray($styleHeader);
		$sheet->getStyle('J10')->applyFromArray($styleHeader);
		$sheet->getStyle('K10')->applyFromArray($styleHeader);
		$sheet->getStyle('L10')->applyFromArray($styleHeader);
		$sheet->getStyle('M10')->applyFromArray($styleHeader);
		$sheet->getStyle('N10')->applyFromArray($styleHeader);
		$sheet->getStyle('O10')->applyFromArray($styleHeader);
		$sheet->getStyle('P10')->applyFromArray($styleHeader);
		$sheet->getStyle('Q10')->applyFromArray($styleHeader);
		$sheet->getStyle('R10')->applyFromArray($styleHeader);
		$sheet->getStyle('A10:R10')->applyFromArray(['font' => ['bold' => true]]);
		$sheet->getStyle('A10:R10')->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('3bc9db');

		$sheet->setCellValue('I9', 'Sum : ' . number_format($sumtotalpd));
		$sheet->setCellValue('J9', 'Sum : ' . number_format($sumtotalbayar));
		$sheet->getStyle('I9')->applyFromArray($styleHeader);
		$sheet->getStyle('J9')->applyFromArray($styleHeader);
		$sheet->getStyle('I9')->getNumberFormat()->setFormatCode('#,##0');
		$sheet->getStyle('J9')->getNumberFormat()->setFormatCode('#,##0');
		$sheet->getStyle('I9:J9')->applyFromArray(['font' => ['bold' => true]]);
		$sheet->getStyle('I9:J9')->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('3bc9db');


		$noPD = 1;
		$numrow = 12;
		foreach ($dataSession as $row) {
			$query_detail = $this->db->select('*, (Vol * HrgSatuan) AS TotalPD_detail ,(PjVol * PjHrgSatuan) AS TotalPJ_detail')->where('NoPD', $row['NoPD'])->get('PdDtl');
			$sheet->setCellValue('A' . $numrow, $noPD);
			$sheet->setCellValue('B' . $numrow, $row['NoPD']);
			$sheet->setCellValue('C' . $numrow, $row['TglPD']);
			$sheet->setCellValue('D' . $numrow, $row['Deskripsi']);
			$sheet->setCellValue('E' . $numrow, tgl_baru($row['PrdAwal']));
			$sheet->setCellValue('F' . $numrow, tgl_baru($row['PrdAkhir']));
			$sheet->setCellValue('G' . $numrow, $row['NoKO']);
			$sheet->setCellValue('H' . $numrow, $row['Nama']);
			$sheet->setCellValue('I' . $numrow, uang_koma_genap_decimal($row['TotalPD']));
			$sheet->setCellValue('J' . $numrow, uang_koma_genap_decimal($row['TotalBayar']));
			$sheet->setCellValue('K' . $numrow, tgl_baru($row['TglBayar']));
			$sheet->setCellValue('L' . $numrow, $row['CaraBayar']);
			$sheet->setCellValue('M' . $numrow, $row['RekId']);
			$sheet->setCellValue('N' . $numrow, $row['RekPenerima']);
			$sheet->setCellValue('O' . $numrow, $row['NoPJ']);
			$sheet->setCellValue('P' . $numrow, tgl_baru($row['TglPJ']));
			$sheet->setCellValue('Q' . $numrow, uang_koma_genap_decimal($row['TotalPJ']));
			$sheet->setCellValue('R' . $numrow, $row['TipeForm']);

			$sheet->getStyle('A' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('B' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('C' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('D' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('E' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('F' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('G' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('H' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('I' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('J' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('K' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('L' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('M' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('N' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('O' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('P' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('Q' . $numrow)->applyFromArray($styleAllBorder);
			$sheet->getStyle('R' . $numrow)->applyFromArray($styleAllBorder);

			$sheet->getStyle('A' . $numrow . ':R' . $numrow)->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('a5d8ff');
			$numrow++;
			$sheet->setCellValue('A' . $numrow, '');
			$sheet->setCellValue('B' . $numrow, 'No');
			$sheet->setCellValue('C' . $numrow, 'KdRAP');
			$sheet->setCellValue('D' . $numrow, 'Uraian');
			$sheet->setCellValue('E' . $numrow, 'Vol');
			$sheet->setCellValue('F' . $numrow, 'Uom');
			$sheet->setCellValue('G' . $numrow, 'Hrg Satuan');
			$sheet->setCellValue('H' . $numrow, 'Sub Total');
			$sheet->setCellValue('I' . $numrow, 'Uraian PJ');
			$sheet->setCellValue('J' . $numrow, 'Vol');
			$sheet->setCellValue('K' . $numrow, 'Uom');
			$sheet->setCellValue('L' . $numrow, 'Hrg Satuan');
			$sheet->setCellValue('M' . $numrow, 'Sub Total');

			$sheet->getStyle('B' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('C' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('D' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('E' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('F' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('G' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('H' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('I' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('J' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('K' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('L' . $numrow)->applyFromArray($styleHeader);
			$sheet->getStyle('M' . $numrow)->applyFromArray($styleHeader);

			$sheet->getStyle('B' . $numrow . ':M' . $numrow)->getFill()
				->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
				->getStartColor()->setARGB('ccd4db');
			$numrow++;
			if ($query_detail->num_rows() > 0) {
				$no = 1;
				foreach ($query_detail->result() as $data_detail) {
					$sheet->setCellValue('A' . $numrow, '');
					$sheet->setCellValue('B' . $numrow, $no);
					$sheet->setCellValue('C' . $numrow, $data_detail->KdRAP);
					$sheet->setCellValue('D' . $numrow, $data_detail->Uraian);
					$sheet->setCellValue('E' . $numrow, new_decimal($data_detail->Vol));
					$sheet->setCellValue('F' . $numrow, $data_detail->Uom);
					$sheet->setCellValue('G' . $numrow, $data_detail->HrgSatuan);
					$sheet->setCellValue('H' . $numrow, $data_detail->TotalPD_detail);
					$sheet->setCellValue('I' . $numrow, $data_detail->PjUraian);
					$sheet->setCellValue('J' . $numrow, new_decimal($data_detail->PjVol));
					$sheet->setCellValue('K' . $numrow, $data_detail->Uom);
					$sheet->setCellValue('L' . $numrow, $data_detail->PjHrgSatuan);
					$sheet->setCellValue('M' . $numrow, $data_detail->TotalPJ_detail);

					$sheet->getStyle('B' . $numrow)->applyFromArray($styleCenter);
					$sheet->getStyle('C' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('D' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('E' . $numrow)->applyFromArray($styleCenter);
					$sheet->getStyle('F' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('G' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('H' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('I' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('J' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('K' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('L' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('M' . $numrow)->applyFromArray($styleRight);

					$sheet->getStyle('G' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->getStyle('H' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->getStyle('L' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->getStyle('M' . $numrow)->getNumberFormat()->setFormatCode('#,##0');

					$no++;
					$numrow++;
				}
			}
			$noPD++;
			$numrow++;
		}


		$sheet->getColumnDimension('A')->setWidth(4);
		$sheet->getColumnDimension('B')->setWidth(19);
		$sheet->getColumnDimension('C')->setWidth(13);
		$sheet->getColumnDimension('D')->setWidth(100);
		$sheet->getColumnDimension('E')->setWidth(12);
		$sheet->getColumnDimension('F')->setWidth(12);
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->getColumnDimension('H')->setWidth(35);
		$sheet->getColumnDimension('I')->setWidth(20);
		$sheet->getColumnDimension('J')->setWidth(20);
		$sheet->getColumnDimension('K')->setWidth(12);
		$sheet->getColumnDimension('L')->setWidth(14);
		$sheet->getColumnDimension('M')->setWidth(54);
		$sheet->getColumnDimension('N')->setWidth(57);
		$sheet->getColumnDimension('O')->setWidth(14);
		$sheet->getColumnDimension('P')->setWidth(12);
		$sheet->getColumnDimension('Q')->setWidth(12);
		$sheet->getColumnDimension('R')->setWidth(11);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$sheet->setTitle("DftrPermintaanDana");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="daftarPermintaanDana&penanggungJawabanDana.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
	public function exportExcelDetailOnly($JobNo, $Alokasi, $dari_tgl, $sampai_tgl)
	{
		if ($JobNo == '' || $Alokasi == '' ||  $dari_tgl == '' || $sampai_tgl == '') {
			redirect('Welcome', 'refresh');
		}
		$getDataSession = $this->session->userdata('session_lapDPPD');
		$dataSession = $getDataSession['myarray'];
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$styleHeader = 	[
			'borders' => [
				'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 1,
			],
		];
		$styleLeft = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
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
		$styleRight = [
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
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
		$styleCenter = [
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

		$query_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'")->row_array();
		$titleJob = $JobNo . ' - ' . $query_job['JobNm'];
		$getAlokasi = $this->db->query("SELECT * FROM Alokasi WHERE Alokasi = '$Alokasi'")->row_array();
		$titleAlokasi = $getAlokasi['Alokasi'] . ' - ' . $getAlokasi['Keterangan'];
		$titlePeriode = 'Periode : ' . tgl_indo($dari_tgl) . ' s/d ' . tgl_indo($sampai_tgl);

		$sheet->mergeCells('A2:M2');
		$sheet->mergeCells('A3:M3');
		$sheet->mergeCells('A4:M4');
		$sheet->mergeCells('A5:M5');
		$sheet->setCellValue('A2', 'DAFTAR PERMINTAAN DANA & PERTANGGUNG JAWABAN DANA');
		$sheet->setCellValue('A3', $titleJob);
		$sheet->setCellValue('A4', $titleAlokasi);
		$sheet->setCellValue('A5', $titlePeriode);
		$sheet->getStyle('A2:O5')->applyFromArray($styleHeader);
		$sheet->getStyle('A2')->applyFromArray(['font' => ['bold' => true]]);
		$sheet->getStyle('A2')->getFont()->setSize(14);

		$sheet->setCellValue('A7', 'No');
		$sheet->setCellValue('B7', 'No PD');
		$sheet->setCellValue('C7', 'Tgl PD');
		$sheet->setCellValue('D7', 'Tgl Bayar');
		$sheet->setCellValue('E7', 'KdRAP');
		$sheet->setCellValue('F7', 'Uraian');
		$sheet->setCellValue('G7', 'Vol');
		$sheet->setCellValue('H7', 'Uom');
		$sheet->setCellValue('I7', 'Hrg Satuan');
		$sheet->setCellValue('J7', 'Sub Total');
		$sheet->setCellValue('K7', 'Total Bayar');
		$sheet->setCellValue('L7', 'Uraian PJ');
		$sheet->setCellValue('M7', 'Vol');
		$sheet->setCellValue('N7', 'Uom');
		$sheet->setCellValue('O7', 'Hrg Satuan');
		$sheet->setCellValue('P7', 'Sub Total');

		$sheet->getStyle('A7')->applyFromArray($styleHeader);
		$sheet->getStyle('B7')->applyFromArray($styleHeader);
		$sheet->getStyle('C7')->applyFromArray($styleHeader);
		$sheet->getStyle('D7')->applyFromArray($styleHeader);
		$sheet->getStyle('E7')->applyFromArray($styleHeader);
		$sheet->getStyle('F7')->applyFromArray($styleHeader);
		$sheet->getStyle('G7')->applyFromArray($styleHeader);
		$sheet->getStyle('H7')->applyFromArray($styleHeader);
		$sheet->getStyle('I7')->applyFromArray($styleHeader);
		$sheet->getStyle('J7')->applyFromArray($styleHeader);
		$sheet->getStyle('K7')->applyFromArray($styleHeader);
		$sheet->getStyle('L7')->applyFromArray($styleHeader);
		$sheet->getStyle('M7')->applyFromArray($styleHeader);
		$sheet->getStyle('N7')->applyFromArray($styleHeader);
		$sheet->getStyle('O7')->applyFromArray($styleHeader);
		$sheet->getStyle('P7')->applyFromArray($styleHeader);
		$sheet->getStyle('A7:P7')->getFill()
			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
			->getStartColor()->setARGB('ccd4db');


		$no = 1;
		$numrow = 8;
		foreach ($dataSession as $row) {
			$noPD = $row['NoPD'];
			$query_detail = $this->db->query("select a.*, (a.Vol*a.HrgSatuan) as TotalPD_detail,(PjVol * PjHrgSatuan) AS TotalPJ_detail, b.TglPD, c.TglBayar FROM PdDtl a JOIN PdHdr b ON a.nopd=b.nopd LEFT JOIN BLE c ON b.nopd=c.NoPD where a.NoPD='$noPD'");
			if ($query_detail->num_rows() > 0) {
				$length = $query_detail->num_rows() - 1;
				$addLenght = $length + $numrow;
				$sheet->mergeCells('A' . $numrow . ':A' . $addLenght);
				$sheet->mergeCells('B' . $numrow . ':B' . $addLenght);
				$sheet->mergeCells('C' . $numrow . ':C' . $addLenght);
				$sheet->mergeCells('D' . $numrow . ':D' . $addLenght);
				$sheet->mergeCells('K' . $numrow . ':K' . $addLenght);
				// TOTAL BAYAR FROM BLE BERDASRKAN NOPD
				$queryTotalBayar = $this->db->query("SELECT Amount FROM BLE WHERE NoPD = '$noPD'")->row_array();
				$sheet->setCellValue('K' . $numrow, $queryTotalBayar == NULL ? 0 : $queryTotalBayar['Amount']);
				// TOTAL BAYAR FROM BLE BERDASRKAN NOPD
				foreach ($query_detail->result() as $data_detail) {
					$sheet->setCellValue('A' . $numrow, $no);
					$sheet->setCellValue('B' . $numrow, $row['NoPD']);
					$sheet->setCellValue('C' . $numrow, $data_detail->TglPD);
					$sheet->setCellValue('D' . $numrow, $data_detail->TglBayar);
					$sheet->setCellValue('E' . $numrow, $data_detail->KdRAP);
					$sheet->setCellValue('F' . $numrow, $data_detail->Uraian);
					$sheet->setCellValue('G' . $numrow, new_decimal($data_detail->Vol));
					$sheet->setCellValue('H' . $numrow, $data_detail->Uom);
					$sheet->setCellValue('I' . $numrow, $data_detail->HrgSatuan);
					$sheet->setCellValue('J' . $numrow, $data_detail->TotalPD_detail);

					$sheet->setCellValue('L' . $numrow, $data_detail->PjUraian);
					$sheet->setCellValue('M' . $numrow, new_decimal($data_detail->PjVol));
					$sheet->setCellValue('N' . $numrow, $data_detail->Uom);
					$sheet->setCellValue('O' . $numrow, $data_detail->PjHrgSatuan);
					$sheet->setCellValue('P' . $numrow, $data_detail->TotalPJ_detail);

					$sheet->getStyle('A' . $numrow)->applyFromArray($styleCenter);
					$sheet->getStyle('B' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('C' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('D' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('E' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('F' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('G' . $numrow)->applyFromArray($styleCenter);
					$sheet->getStyle('H' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('I' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('J' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('K' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('L' . $numrow)->applyFromArray($styleLeft);
					$sheet->getStyle('M' . $numrow)->applyFromArray($styleCenter);
					$sheet->getStyle('N' . $numrow)->applyFromArray($styleCenter);
					$sheet->getStyle('O' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('P' . $numrow)->applyFromArray($styleRight);
					$sheet->getStyle('I' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->getStyle('J' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->getStyle('K' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->getStyle('O' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
					$sheet->getStyle('P' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
					$length--;
					if (($length + 1) < 1) {
						$no++;
					}
					$numrow++;
				}
			}
		}

		$sheet->setCellValue('K6', 'SUM : ' . number_format($getDataSession['sumtotalbayar']));
		$sheet->getStyle('K6')->applyFromArray([
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 1,
			],
			'font' => [
				'bold' => true
			]
		]);

		// $columns = ['A', 'B', 'C', 'D', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
		// foreach ($columns as $column) {
		// 	$sheet->getColumnDimension($column)->setAutoSize(true);
		// }

		$sheet->getColumnDimension('A')->setWidth(4);
		$sheet->getColumnDimension('B')->setWidth(22);
		$sheet->getColumnDimension('C')->setWidth(18);
		$sheet->getColumnDimension('D')->setWidth(18);
		$sheet->getColumnDimension('E')->setWidth(13);
		$sheet->getColumnDimension('F')->setWidth(80);
		$sheet->getColumnDimension('G')->setWidth(9);
		$sheet->getColumnDimension('H')->setWidth(8);
		$sheet->getColumnDimension('I')->setWidth(16);
		$sheet->getColumnDimension('J')->setWidth(16);
		$sheet->getColumnDimension('K')->setWidth(16);
		$sheet->getColumnDimension('L')->setWidth(35);
		$sheet->getColumnDimension('M')->setWidth(8);
		$sheet->getColumnDimension('N')->setWidth(8);
		$sheet->getColumnDimension('O')->setWidth(16);
		$sheet->getColumnDimension('P')->setWidth(16);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$sheet->setTitle("DftrPermintaanDana");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="daftarPermintaanDana&penanggungJawabanDana.xlsx"');
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}

/* End of file Lap_DPPD.php */
/* Location: ./application/controllers/Laporan/Lap_DPPD.php */
