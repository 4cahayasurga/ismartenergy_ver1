<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class FrmRptQueryPDPJ extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function index()
	{

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Query PD/PJ';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['dataJob'] = $this->ListJob();
		$data['dataAlokasi'] = $this->ListAlokasi();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmRptQueryPDPJ_VIEW/halaman_utama', $data);
		$this->load->view('templates/footer');
	}

	function ListJob($pengurutan = '')
	{
		$CI = &get_instance();
		$user_id = $CI->session->userdata('MIS_LOGGED_ID');
		$var_pengurutan = '';
		if ($pengurutan != '') {
			$var_pengurutan = 'ORDER BY ax.JobNo DESC';
		}

		$Company = $this->config->item('Company');

		$query = $CI->db->query("

			select ax.JobNo, bx.JobNm from
			(select item as JobNo
			from
			dbo.SplitString ((select top 1 a.AksesJob from (select * from Login) as a
			left outer join
			(select * from Job) as b
			on b.JobNo = a.AksesJob
			Where a.UserID= '$user_id'), ',')) as ax
			left outer join 
			Job as bx
			on bx.JobNo = ax.JobNo WHERE bx.Company='" . $Company . "'  group by ax.JobNo, bx.JobNm " . $var_pengurutan . "

			");

		return $query->result();
	}

	function ListAlokasi($Alokasi = '')
	{
		$CI = &get_instance();
		$user_id = $CI->session->userdata('MIS_LOGGED_ID');

		$isi_where = '';

		if ($Alokasi !== '') {
			$isi_where = "WHERE ax.Alokasi='$Alokasi'";
		}

		$query = "select ax.Alokasi, bx.Keterangan, CONCAT_WS(' - ',ax.Alokasi,bx.Keterangan) as AlokasiGabung from
		(select item as Alokasi 
		from
		dbo.SplitString (
		(select top 1 a.AksesAlokasi from (select * from Login) as a
		left outer join
		(select * from Alokasi) as b
		on b.Alokasi = a.AksesAlokasi
		Where a.UserID= '$user_id')
		, ',')
		) as ax
		left outer join 
		Alokasi as bx
		on bx.Alokasi = ax.Alokasi
		" . $isi_where . "
		group by ax.Alokasi, bx.Keterangan";

		if ($Alokasi !== '') {
			$eksekusi = $CI->db->query($query)->row();
			return $eksekusi;
		} else {
			$eksekusi = $CI->db->query($query)->result();
			return $eksekusi;
		}
	}

	function TampilkanTable_QueryPDPJ()
	{

		$this->session->unset_userdata('dataSession_QueryPDPJ');
		$this->session->unset_userdata('dataSession_QueryPDPJDataSum');

		$JobNo = $this->input->post('JobNo', true);
		$JobNm = $this->input->post('JobNm', true);
		$Alokasi = $this->input->post('Alokasi', true);

		$data['dataPDPJ'] = $this->PanggilDB_QueryPDPJ($JobNo, $Alokasi);
		$data['dataSum'] = $this->session->userdata('dataSession_QueryPDPJDataSum');
		// var_dump($data['dataSum']);
		// die;

		$this->load->view('FrmRptQueryPDPJ_VIEW/table_QueryPDPJ', $data);
	}

	function TampilkanTable_QueryPDPJ2()
	{
		$data = $this->session->userdata('dataSession_QueryPDPJ');
		$data2 = $this->session->userdata('dataSession_QueryPDPJDataSum');

		echo json_encode(["data" => $data, "dataSum" => $data2]);
	}

	function getDataForChildRowTablePDPJ($dataInput = '')
	{
		if ($dataInput == '') {
			$NoPD = $this->input->post("NoPD", true);
		} else {
			$NoPD = $dataInput;
		}
		$getPddtl = $this->db->query("select KdRAP, Uraian, Vol, HrgSatuan, PjUraian, PjVol, Uom, PjHrgSatuan From PdDtl WHERE NoPD = '$NoPD' ORDER BY NoPD ASC")->result();
		$getInvoice = $this->db->query("select t1.NoKO, t1.NoPD, t1.InvNo, t1.PaymentAmount, t2.InvDate, t2.DueDate, t2.Total, t2.PPN, t2.FPNo, t2.FPDate from InvPD AS t1 JOIN Invoice AS t2 ON t1.InvNo=t2.InvNo where t1.NoPD = '$NoPD'")->result();
		$getPembayaran = $this->db->query("select * from BLE  WHERE NoPD = '$NoPD'")->result();

		$arrayForPddtl = [];
		$SumSubTotalPD = 0;
		$SumSubTotalPJ = 0;
		foreach ($getPddtl as $row) {
			$SumSubTotalPD += $row->Vol * $row->HrgSatuan;
			$SumSubTotalPJ += $row->PjVol * $row->PjHrgSatuan;
			$arrayForPddtl[] = [
				"KdRAP" => $row->KdRAP,
				"Uraian" => $row->Uraian,
				"Vol" => $row->Vol,
				"Uom" => $row->Uom,
				"HrgSatuan" => number_format($row->HrgSatuan),
				"SubTotal" => number_format($row->Vol * $row->HrgSatuan),
				"PjUraian" => $row->PjUraian,
				"PjVol" => number_format($row->PjVol),
				"PjHrgSatuan" => number_format($row->PjHrgSatuan),
				"PjSubtotal" => number_format($row->PjVol * $row->PjHrgSatuan),
			];
		}

		$arrayForInvoice = [];
		$SumTotalInv = 0;
		foreach ($getInvoice as $row) {
			$SumTotalInv += $row->PaymentAmount;
			$arrayForInvoice[] = [
				"NoKO" => $row->NoKO,
				"InvNo" => $row->InvNo,
				"InvDate" => $row->InvDate,
				"DueDate" => $row->DueDate,
				"TotalInv" => number_format($row->Total),
				"PPN" => $row->PPN,
				"FPNo" => $row->FPNo,
				"FPDate" => $row->FPDate,
				"PaymentAmount" => number_format($row->PaymentAmount),
			];
		}

		$arrayForPembayaran = [];
		$SumNilaiBayar = 0;
		foreach ($getPembayaran as $row) {
			$SumNilaiBayar += $row->Amount;
			$arrayForPembayaran[] = [
				"TglBayar" => $row->TglBayar,
				"AtasNama" => $row->AtasNama,
				"NoRek" => $row->NoRek,
				"Bank" => $row->Bank,
				"Amount" => number_format($row->Amount),
				"CaraBayar" => $row->CaraBayar,
				"JenisTrf" => $row->JenisTrf,
				"NoCG" => $row->NoCG,
				"RekId" => $row->RekId,
				"NoKO" => $row->NoKO,
				"Keterangan" => $row->Keterangan,
			];
		};

		$data = [
			'Pddtl' => $arrayForPddtl,
			'SumSubTotalPD' => number_format($SumSubTotalPD),
			'SumSubTotalPJ' => number_format($SumSubTotalPJ),
			'Invoice' => $arrayForInvoice,
			'SumTotalInv' => number_format($SumTotalInv),
			'Pembayaran' => $arrayForPembayaran,
			'SumNilaiBayar' => number_format($SumNilaiBayar),
		];

		if ($dataInput == '') {
			echo json_encode($data);
		} else {
			return $data;
		}
	}

	function PanggilDB_QueryPDPJ($JobNo, $Alokasi)
	{

		$Company = $this->config->item('Company');

		$QueryTambahan = "WHERE C.Company='" . $Company . "'  ";
		$QueryTambahan .= ($JobNo == 'All Job') ? "" : " AND A.JobNo='$JobNo' ";
		$QueryTambahan .= ($Alokasi == 'All Alokasi') ? "" : " AND A.Alokasi='$Alokasi' ";

		$MyQuery = "  SELECT A.JobNo + ' - ' + C.JobNm AS 'JobNo1', A.*, B.SubTotal, B.DiscAmount, B.PPN,
		(SELECT TOP 1 RekId FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'RekId',
		(SELECT TOP 1 TglBayar FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'TglBayar',
		(SELECT TOP 1 JenisTrf FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'JenisTrf',
		(SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoPD=A.NoPD OR NoPD=A.NoRef) AS 'BLEAmount'
		FROM PdHdr A
		LEFT JOIN KoHdr B ON A.NoKO=B.NoKO
		LEFT JOIN Job C ON A.JobNo=C.JobNo
		" . $QueryTambahan . "  ORDER BY A.NoPD DESC";

		$QueryEksekusi = $this->db->query($MyQuery)->result();

		$MyArray = array();
		$ArrayUraianPD = array();
		$ArrayInvoicePD = array();
		$ArrayPembayaran = array();

		$NomorUrut = 1;
		$sumTotalPD = 0;
		$sumTotalPJ = 0;
		$sumSaldo = 0;
		$sumTotalBayar = 0;
		$sumTotalKO = 0;
		foreach ($QueryEksekusi as $row) :

			$Saldo1 = 0;
			$TotalKO = 0;

			$NoPD = $row->NoPD;
			$NoPJ = $row->NoPJ;
			$TotalPD = $row->TotalPD;
			$TotalPJ = $row->TotalPJ;

			// == START VALIDASI SALDO1 ==
			if ($NoPJ != '' || $NoPJ != NULL) {
				if ($TotalPJ == 0) {
					$Saldo1 = 0;
				} else {
					$Saldo1 = $TotalPD - $TotalPJ;
				}
			} else {
				$Saldo1 = 0;
			}
			// == END VALIDASI SALDO1 ==


			// == START VALIDASI Total KO ==
			$TotalKO = 0;
			if ($row->NoKO <> '' || substr($row->NoKO, 0, 2) == 'KO') {
				$SubTotal = $row->SubTotal;
				$DiscAmount = $row->DiscAmount;
				$PPN = $row->PPN;
				$TotalKO = ($SubTotal - $DiscAmount) + $PPN;
			}
			// == END VALIDASI Total KO ==

			// == START QUERY URAIAN PD / PdDtl ==
			// $QueryUraianPD = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$NoPD' ");
			// if ($QueryUraianPD->num_rows() > 0 ) {
			// 	foreach($QueryUraianPD->result_array() as $dataUraianPD ):
			// 		$ArrayUraianPD[] = array(
			// 			'KdRAP' => $dataUraianPD['KdRAP'],
			// 			'Uraian' => $dataUraianPD['Uraian'],
			// 			'Vol' => $dataUraianPD['Vol'],
			// 			'Uom' => $dataUraianPD['Uom'],
			// 			'HrgSatuan' => $dataUraianPD['HrgSatuan'],
			// 			'SubTotalPD' => ($dataUraianPD['HrgSatuan'] * $dataUraianPD['Vol'] ),
			// 			'PjUraian' => $dataUraianPD['PjUraian'],
			// 			'PjVol' => $dataUraianPD['PjVol'],
			// 			'Uom' => $dataUraianPD['Uom'],
			// 			'PjHrgSatuan' => $dataUraianPD['PjHrgSatuan'],
			// 			'SubTotalPJ' => ($dataUraianPD['PjHrgSatuan'] * $dataUraianPD['PjVol'] ),
			// 		);

			// 	endforeach;
			// }
			// == END QUERY URAIAN PD / PdDtl ==



			// == START QUERY Invoice PD ==
			// $QueryInvoice = $this->db->query("SELECT * FROM InvPD A LEFT JOIN Invoice B ON A.NoKO=B.NoKO AND A.InvNo=B.InvNo WHERE A.NoPD='$row->NoPD' ");
			// if ($QueryInvoice->num_rows() > 0 ) {
			// 	foreach($QueryInvoice->result_array() as $dataInvoice ):
			// 		$ArrayInvoicePD[] = array(
			// 			'NoKO' => $dataInvoice['NoKO'],
			// 			'InvNo' => $dataInvoice['InvNo'],
			// 			'InvDate' => $dataInvoice['InvDate'],
			// 			'DueDate' => $dataInvoice['DueDate'],
			// 			'Total' => $dataInvoice['Total'],
			// 			'PPN' => $dataInvoice['PPN'],
			// 			'FPNo' => $dataInvoice['FPNo'],
			// 			'FPDate' => $dataInvoice['FPDate'],
			// 			'PaymentAmount' => $dataInvoice['PaymentAmount'],
			// 		);

			// 	endforeach;
			// }
			// == END QUERY Invoice PD ==


			// == START QUERY PEMBAYARAN ==
			// $QueryPembayaran = $this->db->query("SELECT BLE.* FROM PdHdr, BLE WHERE (PdHdr.NoPD=BLE.NoPD OR PdHdr.NoRef=BLE.NoPD) AND PdHdr.NoPD='$NoPD' ");
			// if ($QueryPembayaran->num_rows() > 0 ) {
			// 	foreach($QueryPembayaran->result_array() as $dataPembayaran ):
			// 		$ArrayPembayaran[] = array(
			// 			'TglBayar' => $dataPembayaran['TglBayar'],
			// 			'AtasNama' => $dataPembayaran['AtasNama'],
			// 			'NoRek' => $dataPembayaran['NoRek'],
			// 			'Bank' => $dataPembayaran['Bank'],
			// 			'Amount' => $dataPembayaran['Amount'],
			// 			'CaraBayar' => $dataPembayaran['CaraBayar'],
			// 			'JenisTrf' => $dataPembayaran['JenisTrf'],
			// 			'NoCG' => $dataPembayaran['NoCG'],
			// 			'RekId' => $dataPembayaran['RekId'],
			// 			'NoKO' => $dataPembayaran['NoKO'],
			// 			'Keterangan' => $dataPembayaran['Keterangan'],
			// 		);

			// 	endforeach;
			// }
			// == END QUERY PEMBAYARAN ==


			$MyArray[] = (object) array(
				'NomorUrut' => $NomorUrut++,
				'JobNo1' => $row->JobNo1,
				'JobNo' => $row->JobNo,
				'NoPD' => $row->NoPD,
				'TglPD' => $row->TglPD,
				'NoRef' => $row->NoRef,
				'Alokasi' => $row->Alokasi,
				'TipeForm' => $row->TipeForm,
				'Deskripsi' => $row->Deskripsi,
				'NoKO' => $row->NoKO,
				'DiscAmount' => $row->DiscAmount,
				'TotalKO' => $TotalKO,
				'NoTagihan' => $row->NoTagihan,
				'NoRek' => $row->NoRek,
				'AtasNama' => $row->AtasNama,
				'TotalPD' => $TotalPD == null ? 0 : $TotalPD,
				'TglBayar' => $row->TglBayar,
				'JenisTrf' => $row->JenisTrf,
				'BLEAmount' => $row->BLEAmount  == null ? 0 : $row->BLEAmount,
				'RekId' => $row->RekId,
				'NoPJ' => $NoPJ,
				'TglPJ' => $row->TglPJ,
				'TotalPJ' => $TotalPJ  == null ? 0 : $TotalPJ,
				'Saldo' => $row->Saldo  == null ? 0 : $row->Saldo,
				'Saldo1' => $Saldo1  == null ? 0 : $Saldo1,
				// 'CountArrayUraianPD' => count($ArrayUraianPD),
				// 'ArrayUraianPD' => $ArrayUraianPD,
				// 'CountInvoice' => count($ArrayInvoicePD),
				// 'ArrayInvoicePD' => $ArrayInvoicePD,
				// 'CountPembayaran' => count($ArrayPembayaran),
				// 'ArrayPembayaran' => $ArrayPembayaran,
			);

			$sumTotalPD += $TotalPD == null ? 0 : $TotalPD;
			$sumTotalPJ += $TotalPJ == null ? 0 : $TotalPJ;
			$sumSaldo += $Saldo1  == null ? 0 : $Saldo1;
			$sumTotalBayar += $row->BLEAmount  == null ? 0 : $row->BLEAmount;
			$sumTotalKO += $TotalKO;


		endforeach;

		$dataSum = [
			'sumTotalPD' => $sumTotalPD,
			'sumTotalPJ' => $sumTotalPJ,
			'sumSaldo' => $sumSaldo,
			'sumTotalBayar' => $sumTotalBayar,
			'sumTotalKO' => $sumTotalKO,
		];

		$dataSession = [
			'dataQuery' => $this->session->set_userdata('dataSession_QueryPDPJ', $MyArray),
			'dataSum' => $this->session->set_userdata('dataSession_QueryPDPJDataSum', $dataSum),
		];

		return $dataSession;
	}

	function ExpToExcel()
	{
		if (isset($_POST['checklistExport'])) {
			$checklistExport = $_POST['checklistExport'] ?? [];
			$isShowDetail = $_POST['showDetail'] ?? [];
			$IdCheckList = implode(',', $checklistExport);

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

			$style_left = [
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
			$style_right = [
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
			$style_center = [
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

			if (count($isShowDetail) >= 1) {
				$sheet->setCellValue('A1', 'No');
				$sheet->mergeCells('B1:F1');
				$sheet->setCellValue('B1', 'Job No');
				$sheet->mergeCells('G1:H1');
				$sheet->setCellValue('G1', 'No PD');
				$sheet->mergeCells('I1:J1');
				$sheet->setCellValue('I1', 'Tgl PD');
				$sheet->mergeCells('K1:M1');
				$sheet->setCellValue('K1', 'No Ref Lapangan');
				$sheet->mergeCells('N1:P1');
				$sheet->setCellValue('N1', 'Alokasi');
				$sheet->mergeCells('Q1:R1');
				$sheet->setCellValue('Q1', 'Tipe Form');
				$sheet->mergeCells('S1:Z1');
				$sheet->setCellValue('S1', 'Deskripsi');
				$sheet->mergeCells('AA1:AB1');
				$sheet->setCellValue('AA1', 'No KO');
				$sheet->mergeCells('AC1:AD1');
				$sheet->setCellValue('AC1', 'Total KO (Rp)');
				$sheet->mergeCells('AE1:AF1');
				$sheet->setCellValue('AE1', 'No Tagihan');
				$sheet->setCellValue('AG1', 'Rekening Penerima');
				$sheet->setCellValue('AH1', 'Atas Nama');
				$sheet->setCellValue('AI1', 'Total PD (Rp)');
				$sheet->setCellValue('AJ1', 'Tgl Bayar');
				$sheet->setCellValue('AK1', 'Via');
				$sheet->setCellValue('AL1', 'Total Pembayaran (Rp)');
				$sheet->setCellValue('AM1', 'Rekening Pengirim');
				$sheet->setCellValue('AN1', 'No PJ');
				$sheet->setCellValue('AO1', 'Tgl PJ');
				$sheet->setCellValue('AP1', 'Total PJ (Rp)');
				$sheet->setCellValue('AQ1', 'Saldo PJ (Rp)');

				$sheet->getStyle('A1')->applyFromArray($style_center);
				$sheet->getStyle('B1')->applyFromArray($style_center);
				$sheet->getStyle('G1')->applyFromArray($style_center);
				$sheet->getStyle('I1')->applyFromArray($style_center);
				$sheet->getStyle('K1')->applyFromArray($style_center);
				$sheet->getStyle('N1')->applyFromArray($style_center);
				$sheet->getStyle('Q1')->applyFromArray($style_center);
				$sheet->getStyle('S1')->applyFromArray($style_center);
				$sheet->getStyle('AA1')->applyFromArray($style_center);
				$sheet->getStyle('AC1')->applyFromArray($style_center);
				$sheet->getStyle('AE1')->applyFromArray($style_center);
				$sheet->getStyle('AG1')->applyFromArray($style_center);
				$sheet->getStyle('AH1')->applyFromArray($style_center);
				$sheet->getStyle('AI1')->applyFromArray($style_center);
				$sheet->getStyle('AJ1')->applyFromArray($style_center);
				$sheet->getStyle('AK1')->applyFromArray($style_center);
				$sheet->getStyle('AL1')->applyFromArray($style_center);
				$sheet->getStyle('AM1')->applyFromArray($style_center);
				$sheet->getStyle('AN1')->applyFromArray($style_center);
				$sheet->getStyle('AO1')->applyFromArray($style_center);
				$sheet->getStyle('AP1')->applyFromArray($style_center);
				$sheet->getStyle('AQ1')->applyFromArray($style_center);
				$sheet->getStyle('A1:AQ1')->getFont()->setName('Times New Roman')->setSize(10);

				$spreadsheet->getActiveSheet()->getStyle('A1:AQ1')->getFill()
					->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
					->getStartColor()->setARGB('ced4da');
				$spreadsheet->getActiveSheet()->getStyle('A1:AQ1')->getFont()->getColor()->setARGB('FF000000');


				$numrow = 2;
				$no = 1;
				foreach ($this->session->userdata('dataSession_QueryPDPJ') as $row) {
					if (!preg_match('/\b' . $row->NomorUrut . '\b/', $IdCheckList)) {
						continue;
					}
					$sheet->setCellValue('A' . $numrow, $no);
					$sheet->mergeCells('B' . $numrow . ':F' . $numrow);
					$sheet->setCellValue('B' . $numrow, $row->JobNo1);
					$sheet->mergeCells('G' . $numrow . ':H' . $numrow);
					$sheet->setCellValue('G' . $numrow, $row->NoPD);
					$sheet->mergeCells('I' . $numrow . ':J' . $numrow);
					$sheet->setCellValue('I' . $numrow, $row->TglPD);
					$sheet->mergeCells('K' . $numrow . ':M' . $numrow);
					$sheet->setCellValue('K' . $numrow, $row->NoRef);
					$sheet->mergeCells('N' . $numrow . ':P' . $numrow);
					$sheet->setCellValue('N' . $numrow, $row->Alokasi);
					$sheet->mergeCells('Q' . $numrow . ':R' . $numrow);
					$sheet->setCellValue('Q' . $numrow, $row->TipeForm);
					$sheet->mergeCells('S' . $numrow . ':Z' . $numrow);
					$sheet->setCellValue('S' . $numrow, $row->Deskripsi);
					$sheet->mergeCells('AA' . $numrow . ':AB' . $numrow);
					$sheet->setCellValue('AA' . $numrow, $row->NoKO);
					$sheet->mergeCells('AC' . $numrow . ':AD' . $numrow);
					$sheet->setCellValue('AC' . $numrow, $row->TotalKO);
					$sheet->mergeCells('AE' . $numrow . ':AF' . $numrow);
					$sheet->setCellValue('AE' . $numrow, $row->NoTagihan);
					$sheet->setCellValue('AG' . $numrow, $row->NoRek);
					$sheet->setCellValue('AH' . $numrow, $row->AtasNama);
					$sheet->setCellValue('AI' . $numrow, $row->TotalPD);
					$sheet->setCellValue('AJ' . $numrow, $row->TglBayar);
					$sheet->setCellValue('AK' . $numrow, $row->JenisTrf);
					$sheet->setCellValue('AL' . $numrow, $row->BLEAmount);
					$sheet->setCellValue('AM' . $numrow, $row->RekId);
					$sheet->setCellValue('AN' . $numrow, $row->NoPJ);
					$sheet->setCellValue('AO' . $numrow, $row->TglPJ);
					$sheet->setCellValue('AP' . $numrow, $row->TotalPJ);
					$sheet->setCellValue('AQ' . $numrow, $row->Saldo1);

					$sheet->getStyle('A' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('B' . $numrow . ':F' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('G' . $numrow . ':H' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('I' . $numrow . ':J' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('K' . $numrow . ':M' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('N' . $numrow . ':P' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('Q' . $numrow . ':R' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('S' . $numrow . ':Z' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('AA' . $numrow . ':AB' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('AC' . $numrow . ':AD' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('AE' . $numrow . ':AF' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('AF' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('AG' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('AH' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('AI' . $numrow)->applyFromArray($style_right);
					$sheet->getStyle('AJ' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('AK' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('AL' . $numrow)->applyFromArray($style_right);
					$sheet->getStyle('AM' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('AN' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('AO' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('AP' . $numrow)->applyFromArray($style_right);
					$sheet->getStyle('AQ' . $numrow)->applyFromArray($style_right);

					$sheet->getStyle('A' . $numrow . ':AQ' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
					$sheet->getStyle('B' . $numrow . ':F' . $numrow)->getAlignment()->setWrapText(true);
					$sheet->getStyle('S' . $numrow . ':Z' . $numrow)->getAlignment()->setWrapText(true);
					$sheet->getStyle('AH' . $numrow)->getAlignment()->setWrapText(true);
					$sheet->getStyle('AM' . $numrow)->getAlignment()->setWrapText(true);

					$mergedText = $sheet->getCell('S' . $numrow)->getValue();
					$numberOfLines = ceil(strlen($mergedText) / 30);
					$sheet->getRowDimension($numrow)->setRowHeight($numberOfLines * 15);

					if (in_array($row->NomorUrut, $isShowDetail)) {
						$dataDetail = $this->getDataForChildRowTablePDPJ($row->NoPD);
						$numrow++;
						$numrow++;
						$sheet->mergeCells('B' . $numrow . ':C' . $numrow);
						$sheet->setCellValue('B' . $numrow, 'No KO');
						$sheet->setCellValue('D' . $numrow, 'Invoice');
						$sheet->mergeCells('E' . $numrow . ':F' . $numrow);
						$sheet->setCellValue('E' . $numrow, 'Tgl Invoice');
						$sheet->mergeCells('G' . $numrow . ':H' . $numrow);
						$sheet->setCellValue('G' . $numrow, 'Due Date');
						$sheet->mergeCells('I' . $numrow . ':K' . $numrow);
						$sheet->setCellValue('I' . $numrow, 'Total Invoice');
						$sheet->mergeCells('L' . $numrow . ':N' . $numrow);
						$sheet->setCellValue('L' . $numrow, 'PPN (Rp)');
						$sheet->mergeCells('O' . $numrow . ':R' . $numrow);
						$sheet->setCellValue('O' . $numrow, 'No. Faktur Pajak');
						$sheet->mergeCells('S' . $numrow . ':T' . $numrow);
						$sheet->setCellValue('S' . $numrow, 'Tgl Faktur Pajak');
						$sheet->mergeCells('U' . $numrow . ':W' . $numrow);
						$sheet->setCellValue('U' . $numrow, 'Payment Amount (Rp)');

						$sheet->getStyle('B' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('C' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('D' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('E' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('F' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('G' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('H' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('I' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('J' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('K' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('L' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('M' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('N' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('O' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('P' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('Q' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('R' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('S' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('T' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('U' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('V' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('W' . $numrow)->applyFromArray($style_center);

						$sheet->getStyle('A' . $numrow . ':W' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
						$spreadsheet->getActiveSheet()->getStyle('B' . $numrow . ':U' . $numrow)->getFill()
							->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
							->getStartColor()->setARGB('ced4da');
						$spreadsheet->getActiveSheet()->getStyle('B' . $numrow . ':U' . $numrow)->getFont()->getColor()->setARGB('FF000000');

						$DataInvoice = $dataDetail['Invoice'];
						if (count($DataInvoice) >= 1) {
							$numrow++;
							foreach ($DataInvoice as $inv) {
								$sheet->mergeCells('B' . $numrow . ':C' . $numrow);
								$sheet->mergeCells('E' . $numrow . ':F' . $numrow);
								$sheet->mergeCells('G' . $numrow . ':H' . $numrow);
								$sheet->mergeCells('I' . $numrow . ':K' . $numrow);
								$sheet->mergeCells('L' . $numrow . ':N' . $numrow);
								$sheet->mergeCells('O' . $numrow . ':R' . $numrow);
								$sheet->mergeCells('S' . $numrow . ':T' . $numrow);
								$sheet->mergeCells('U' . $numrow . ':W' . $numrow);

								$sheet->setCellValue('B' . $numrow, $inv['NoKO']);
								$sheet->setCellValue('D' . $numrow, $inv['InvNo']);
								$sheet->setCellValue('E' . $numrow, $inv['InvDate']);
								$sheet->setCellValue('G' . $numrow, $inv['DueDate']);
								$sheet->setCellValue('I' . $numrow, $inv['TotalInv']);
								$sheet->setCellValue('L' . $numrow, $inv['PPN']);
								$sheet->setCellValue('O' . $numrow, $inv['FPNo']);
								$sheet->setCellValue('S' . $numrow, $inv['FPDate']);
								$sheet->setCellValue('U' . $numrow, $inv['PaymentAmount']);

								$sheet->getStyle('B' . $numrow . ':C' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('E' . $numrow . ':F' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('G' . $numrow . ':H' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('I' . $numrow . ':K' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('L' . $numrow . ':N' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('O' . $numrow . ':R' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('S' . $numrow . ':T' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('U' . $numrow . ':W' . $numrow)->applyFromArray($style_center);

								$sheet->getStyle('A' . $numrow . ':W' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
								$numrow++;
							}
							$sheet->mergeCells('B' . $numrow . ':C' . $numrow);
							$sheet->mergeCells('E' . $numrow . ':F' . $numrow);
							$sheet->mergeCells('G' . $numrow . ':H' . $numrow);
							$sheet->mergeCells('I' . $numrow . ':K' . $numrow);
							$sheet->mergeCells('L' . $numrow . ':N' . $numrow);
							$sheet->mergeCells('O' . $numrow . ':R' . $numrow);
							$sheet->mergeCells('S' . $numrow . ':T' . $numrow);
							$sheet->mergeCells('U' . $numrow . ':W' . $numrow);

							$sheet->setCellValue('U' . $numrow, 'Sum : ' . $dataDetail['SumTotalInv']);

							$sheet->getStyle('B' . $numrow . ':W' . $numrow)->applyFromArray($style_right);
							$sheet->getStyle('B' . $numrow . ':U' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
							$spreadsheet->getActiveSheet()->getStyle('B' . $numrow . ':U' . $numrow)->getFill()
								->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
								->getStartColor()->setARGB('FAE7B5');
						}

						$numrow++;
						$numrow++;

						$sheet->setCellValue('B' . $numrow, 'Kode RAP');
						$sheet->mergeCells('C' . $numrow . ':J' . $numrow);
						$sheet->setCellValue('C' . $numrow, 'Uraian');
						$sheet->mergeCells('K' . $numrow . ':M' . $numrow);
						$sheet->setCellValue('K' . $numrow, 'Volume PD');
						$sheet->mergeCells('N' . $numrow . ':P' . $numrow);
						$sheet->setCellValue('N' . $numrow, 'Uom');
						$sheet->mergeCells('Q' . $numrow . ':S' . $numrow);
						$sheet->setCellValue('Q' . $numrow, 'Hrg Satuan (Rp)');
						$sheet->mergeCells('T' . $numrow . ':U' . $numrow);
						$sheet->setCellValue('T' . $numrow, 'Sub Total PD (Rp)');
						$sheet->setCellValue('V' . $numrow, 'Uraian PJ');
						$sheet->mergeCells('W' . $numrow . ':X' . $numrow);
						$sheet->setCellValue('W' . $numrow, 'Volume PJ');
						$sheet->mergeCells('Y' . $numrow . ':Z' . $numrow);
						$sheet->setCellValue('Y' . $numrow, 'Uom');
						$sheet->mergeCells('AA' . $numrow . ':AB' . $numrow);
						$sheet->setCellValue('AA' . $numrow, 'Hrg Satuan PJ (Rp)');
						$sheet->mergeCells('AC' . $numrow . ':AD' . $numrow);
						$sheet->setCellValue('AC' . $numrow, 'Sub Total PJ (Rp)');

						$sheet->getStyle('B' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('C' . $numrow . ':J' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('K' . $numrow . ':M' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('N' . $numrow . ':P' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('Q' . $numrow . ':S' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('T' . $numrow . ':U' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('V' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('W' . $numrow . ':X' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('Y' . $numrow . ':Z' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('AA' . $numrow . ':AB' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('AC' . $numrow . ':AD' . $numrow)->applyFromArray($style_center);

						$sheet->getStyle('A' . $numrow . ':AC' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
						$spreadsheet->getActiveSheet()->getStyle('B' . $numrow . ':AC' . $numrow)->getFill()
							->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
							->getStartColor()->setARGB('ced4da');
						$spreadsheet->getActiveSheet()->getStyle('B' . $numrow . ':AC' . $numrow)->getFont()->getColor()->setARGB('FF000000');

						$DataPdDtl = $dataDetail['Pddtl'];
						if (count($DataPdDtl) >= 1) {
							foreach ($DataPdDtl as $pddtl) {
								$numrow++;
								$sheet->mergeCells('C' . $numrow . ':J' . $numrow);
								$sheet->mergeCells('K' . $numrow . ':M' . $numrow);
								$sheet->mergeCells('N' . $numrow . ':P' . $numrow);
								$sheet->mergeCells('Q' . $numrow . ':S' . $numrow);
								$sheet->mergeCells('T' . $numrow . ':U' . $numrow);
								$sheet->mergeCells('W' . $numrow . ':X' . $numrow);
								$sheet->mergeCells('Y' . $numrow . ':Z' . $numrow);
								$sheet->mergeCells('AA' . $numrow . ':AB' . $numrow);
								$sheet->mergeCells('AC' . $numrow . ':AD' . $numrow);

								$sheet->setCellValue('B' . $numrow, $pddtl['KdRAP']);
								$sheet->setCellValue('C' . $numrow, $pddtl['Uraian']);
								$sheet->setCellValue('K' . $numrow, $pddtl['Vol']);
								$sheet->setCellValue('N' . $numrow, $pddtl['Uom']);
								$sheet->setCellValue('Q' . $numrow, $pddtl['HrgSatuan']);
								$sheet->setCellValue('T' . $numrow, $pddtl['SubTotal']);
								$sheet->setCellValue('V' . $numrow, $pddtl['PjUraian']);
								$sheet->setCellValue('W' . $numrow, $pddtl['PjVol']);
								$sheet->setCellValue('Y' . $numrow, $pddtl['Uom']);
								$sheet->setCellValue('AA' . $numrow, $pddtl['PjHrgSatuan']);
								$sheet->setCellValue('AC' . $numrow, $pddtl['PjSubtotal']);

								$sheet->getStyle('B' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('C' . $numrow . ':J' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('K' . $numrow . ':M' . $numrow)->applyFromArray($style_right);
								$sheet->getStyle('N' . $numrow . ':P' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('Q' . $numrow . ':S' . $numrow)->applyFromArray($style_right);
								$sheet->getStyle('T' . $numrow . ':U' . $numrow)->applyFromArray($style_right);
								$sheet->getStyle('V' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('W' . $numrow . ':X' . $numrow)->applyFromArray($style_right);
								$sheet->getStyle('Y' . $numrow . ':Z' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('AA' . $numrow . ':AB' . $numrow)->applyFromArray($style_right);
								$sheet->getStyle('AC' . $numrow . ':AD' . $numrow)->applyFromArray($style_right);

								$sheet->getStyle('B' . $numrow . ':AC' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
								$sheet->getStyle('C' . $numrow . ':J' . $numrow)->getAlignment()->setWrapText(true);
								$mergedText = $sheet->getCell('C' . $numrow)->getValue();
								$numberOfLines = ceil(strlen($mergedText) / 30);
								$sheet->getRowDimension($numrow)->setRowHeight($numberOfLines * 15);
							}
							$numrow++;
							$sheet->mergeCells('T' . $numrow . ':U' . $numrow);
							$sheet->mergeCells('AC' . $numrow . ':AD' . $numrow);
							$sheet->setCellValue('T' . $numrow, 'Sum : ' . $dataDetail['SumSubTotalPD']);
							$sheet->setCellValue('AC' . $numrow, 'Sum : ' . $dataDetail['SumSubTotalPJ']);
							$sheet->getStyle('B' . $numrow . ':T' . $numrow)->applyFromArray($style_right);
							$sheet->getStyle('U' . $numrow . ':AD' . $numrow)->applyFromArray($style_right);
							$sheet->getStyle('B' . $numrow . ':AD' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
							$spreadsheet->getActiveSheet()->getStyle('B' . $numrow . ':AD' . $numrow)->getFill()
								->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
								->getStartColor()->setARGB('FAE7B5');
						}

						$numrow++;
						$numrow++;

						$sheet->mergeCells('C' . $numrow . ':E' . $numrow);
						$sheet->mergeCells('F' . $numrow . ':G' . $numrow);
						$sheet->mergeCells('H' . $numrow . ':I' . $numrow);
						$sheet->mergeCells('J' . $numrow . ':K' . $numrow);
						$sheet->mergeCells('L' . $numrow . ':M' . $numrow);
						$sheet->mergeCells('N' . $numrow . ':P' . $numrow);
						$sheet->mergeCells('Q' . $numrow . ':R' . $numrow);
						$sheet->mergeCells('S' . $numrow . ':T' . $numrow);
						$sheet->mergeCells('U' . $numrow . ':V' . $numrow);
						$sheet->mergeCells('W' . $numrow . ':X' . $numrow);

						$sheet->setCellValue('B' . $numrow, 'Tgl Bayar');
						$sheet->setCellValue('C' . $numrow, 'Nama Penerima');
						$sheet->setCellValue('F' . $numrow, 'Rekening Penerima');
						$sheet->setCellValue('H' . $numrow, 'Bank Penerima');
						$sheet->setCellValue('J' . $numrow, 'Nilai Bayar');
						$sheet->setCellValue('L' . $numrow, 'Media');
						$sheet->setCellValue('N' . $numrow, 'Via');
						$sheet->setCellValue('Q' . $numrow, 'No Cek/BG');
						$sheet->setCellValue('S' . $numrow, 'Rek Pengirim');
						$sheet->setCellValue('U' . $numrow, 'No KO');
						$sheet->setCellValue('W' . $numrow, 'Keterangan');

						$sheet->getStyle('B' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('C' . $numrow . ':E' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('F' . $numrow . ':G' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('H' . $numrow . ':I' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('J' . $numrow . ':K' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('L' . $numrow . ':M' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('N' . $numrow . ':P' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('Q' . $numrow . ':R' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('S' . $numrow . ':T' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('U' . $numrow . ':V' . $numrow)->applyFromArray($style_center);
						$sheet->getStyle('W' . $numrow . ':X' . $numrow)->applyFromArray($style_center);

						$sheet->getStyle('A' . $numrow . ':X' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
						$spreadsheet->getActiveSheet()->getStyle('B' . $numrow . ':X' . $numrow)->getFill()
							->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
							->getStartColor()->setARGB('ced4da');
						$spreadsheet->getActiveSheet()->getStyle('B' . $numrow . ':X' . $numrow)->getFont()->getColor()->setARGB('FF000000');

						$DataPembayaran = $dataDetail['Pembayaran'];
						if (count($DataPembayaran) >= 1) {
							foreach ($DataPembayaran as $bayar) {
								$numrow++;
								$sheet->mergeCells('C' . $numrow . ':E' . $numrow);
								$sheet->mergeCells('F' . $numrow . ':G' . $numrow);
								$sheet->mergeCells('H' . $numrow . ':I' . $numrow);
								$sheet->mergeCells('J' . $numrow . ':K' . $numrow);
								$sheet->mergeCells('L' . $numrow . ':M' . $numrow);
								$sheet->mergeCells('N' . $numrow . ':P' . $numrow);
								$sheet->mergeCells('Q' . $numrow . ':R' . $numrow);
								$sheet->mergeCells('S' . $numrow . ':T' . $numrow);
								$sheet->mergeCells('U' . $numrow . ':V' . $numrow);
								$sheet->mergeCells('W' . $numrow . ':X' . $numrow);

								$sheet->setCellValue('B' . $numrow, $bayar['TglBayar']);
								$sheet->setCellValue('C' . $numrow, $bayar['AtasNama']);
								$sheet->setCellValue('F' . $numrow, $bayar['NoRek']);
								$sheet->setCellValue('H' . $numrow, $bayar['Bank']);
								$sheet->setCellValue('J' . $numrow, $bayar['Amount']);
								$sheet->setCellValue('L' . $numrow, $bayar['CaraBayar']);
								$sheet->setCellValue('N' . $numrow, $bayar['JenisTrf']);
								$sheet->setCellValue('Q' . $numrow, $bayar['NoCG']);
								$sheet->setCellValue('S' . $numrow, $bayar['RekId']);
								$sheet->setCellValue('U' . $numrow, $bayar['NoKO']);
								$sheet->setCellValue('W' . $numrow, $bayar['Keterangan']);

								$sheet->getStyle('B' . $numrow)->applyFromArray($style_center);
								$sheet->getStyle('C' . $numrow . ':E' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('F' . $numrow . ':G' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('H' . $numrow . ':I' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('J' . $numrow . ':K' . $numrow)->applyFromArray($style_right);
								$sheet->getStyle('L' . $numrow . ':M' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('N' . $numrow . ':P' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('Q' . $numrow . ':R' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('S' . $numrow . ':T' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('U' . $numrow . ':V' . $numrow)->applyFromArray($style_left);
								$sheet->getStyle('W' . $numrow . ':X' . $numrow)->applyFromArray($style_left);

								$sheet->getStyle('A' . $numrow . ':X' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
								$sheet->getStyle('C' . $numrow . ':E' . $numrow)->getAlignment()->setWrapText(true);
								$sheet->getStyle('S' . $numrow . ':T' . $numrow)->getAlignment()->setWrapText(true);
								$mergedText = $sheet->getCell('S' . $numrow)->getValue();
								$numberOfLines = ceil(strlen($mergedText) / 30);
								$sheet->getRowDimension($numrow)->setRowHeight($numberOfLines * 15);
							}
							$numrow++;
							$sheet->mergeCells('J' . $numrow . ':K' . $numrow);
							$sheet->setCellValue('J' . $numrow, 'Sum : ' . $dataDetail['SumNilaiBayar']);
							$sheet->getStyle('B' . $numrow . ':X' . $numrow)->applyFromArray($style_right);
							$sheet->getStyle('B' . $numrow . ':X' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
							$spreadsheet->getActiveSheet()->getStyle('B' . $numrow . ':X' . $numrow)->getFill()
								->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
								->getStartColor()->setARGB('FAE7B5');
						}
						$numrow++;
					}
					$numrow++;
					$no++;

					$sheet->getColumnDimension('A')->setWidth(3);
					$sheet->getColumnDimension('B')->setWidth(15);
					$sheet->getColumnDimension('C')->setWidth(1.29);
					$sheet->getColumnDimension('D')->setWidth(15);
					$sheet->getColumnDimension('E')->setWidth(2.14);
					$sheet->getColumnDimension('F')->setWidth(8.71);
					$sheet->getColumnDimension('G')->setWidth(10);
					$sheet->getColumnDimension('H')->setWidth(7);
					$sheet->getColumnDimension('I')->setWidth(6);
					$sheet->getColumnDimension('J')->setWidth(7.57);
					$sheet->getColumnDimension('K')->setWidth(10);
					$sheet->getColumnDimension('L')->setWidth(3.57);
					$sheet->getColumnDimension('M')->setWidth(2.71);
					$sheet->getColumnDimension('N')->setWidth(4);
					$sheet->getColumnDimension('O')->setWidth(2.14);
					$sheet->getColumnDimension('P')->setWidth(5.71);
					$sheet->getColumnDimension('Q')->setWidth(6);
					$sheet->getColumnDimension('R')->setWidth(6);
					$sheet->getColumnDimension('S')->setWidth(6.71);
					$sheet->getColumnDimension('T')->setWidth(25);
					$sheet->getColumnDimension('U')->setWidth(9);
					$sheet->getColumnDimension('V')->setWidth(9);
					$sheet->getColumnDimension('W')->setWidth(5);
					$sheet->getColumnDimension('X')->setWidth(7.14);
					$sheet->getColumnDimension('Y')->setWidth(4);
					$sheet->getColumnDimension('Z')->setWidth(2.57);
					$sheet->getColumnDimension('AA')->setWidth(11);
					$sheet->getColumnDimension('AB')->setWidth(7);
					$sheet->getColumnDimension('AC')->setWidth(6);
					$sheet->getColumnDimension('AD')->setWidth(8.29);
					$sheet->getColumnDimension('AE')->setWidth(20.71);
					$sheet->getColumnDimension('AF')->setWidth(16.57);
					$sheet->getColumnDimension('AG')->setWidth(42.43);
					$sheet->getColumnDimension('AH')->setWidth(16.29);
					$sheet->getColumnDimension('AI')->setWidth(13);
					$sheet->getColumnDimension('AJ')->setWidth(11);
					$sheet->getColumnDimension('AK')->setWidth(14);
					$sheet->getColumnDimension('AL')->setWidth(20);
					$sheet->getColumnDimension('AM')->setWidth(18);
					$sheet->getColumnDimension('AN')->setWidth(13);
					$sheet->getColumnDimension('AO')->setWidth(13);
					$sheet->getColumnDimension('AP')->setWidth(13);
					$sheet->getColumnDimension('AQ')->setWidth(13);
				}
			} else {
				$spreadsheet->getActiveSheet()->getStyle('A5:V5')->getFill()
					->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
					->getStartColor()->setARGB('ced4da');

				$sheet->setCellValue('A1', "REPORT QUERY PD/PJ");
				$sheet->mergeCells('A1:E1');
				$sheet->getStyle('A1')->getFont()->setBold(true);

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

				$no = 1;
				$numrow = 6;

				$TotalKO = 0;
				$Saldo1 = 0;

				$sumTotalPD = 0;
				$sumTotalPembayaran = 0;
				$sumTotalPJ = 0;

				foreach ($this->session->userdata('dataSession_QueryPDPJ') as $key) :
					if (!preg_match('/\b' . $key->NomorUrut . '\b/', $IdCheckList)) {
						continue;
					}

					$sheet->setCellValue('A' . $numrow, $no);
					$sheet->setCellValue('B' . $numrow, $key->JobNo1);
					$sheet->setCellValue('C' . $numrow, $key->NoPD);
					$sheet->setCellValue('D' . $numrow, tgl_baru($key->TglPD));
					$sheet->setCellValue('E' . $numrow, $key->NoRef);
					$sheet->setCellValue('F' . $numrow, $key->Alokasi);
					$sheet->setCellValue('G' . $numrow, $key->TipeForm);
					$sheet->setCellValue('H' . $numrow, $key->Deskripsi);
					$sheet->setCellValue('I' . $numrow, $key->NoKO);
					$sheet->setCellValue('J' . $numrow, (float) $key->TotalKO);
					$sheet->setCellValue('K' . $numrow, $key->NoTagihan);
					$sheet->setCellValue('L' . $numrow, $key->NoRek);
					$sheet->setCellValue('M' . $numrow, $key->AtasNama);
					$sheet->setCellValue('N' . $numrow, (float) $key->TotalPD);

					$sheet->setCellValue('O' . $numrow, ($key->TglBayar == '1900-01-01' or $key->TglBayar == NULL or $key->TglBayar == '') ? '' : tgl_baru($key->TglBayar));
					$sheet->setCellValue('P' . $numrow, $key->JenisTrf);
					$sheet->setCellValue('Q' . $numrow, (float) $key->BLEAmount);
					$sheet->setCellValue('R' . $numrow, $key->RekId);
					$sheet->setCellValue('S' . $numrow, $key->NoPJ);
					$sheet->setCellValue('T' . $numrow, tgl_baru($key->TglPJ));
					$sheet->setCellValue('U' . $numrow, (float) $key->TotalPJ);

					$sheet->setCellValue('V' . $numrow, (float) $key->Saldo1);


					$sheet->getStyle('A' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('B' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('C' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('D' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('E' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('F' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('G' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('H' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('I' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('J' . $numrow)->applyFromArray($style_right);
					$sheet->getStyle('K' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('L' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('M' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('N' . $numrow)->applyFromArray($style_right);

					$sheet->getStyle('O' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('P' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('Q' . $numrow)->applyFromArray($style_right);
					$sheet->getStyle('R' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('S' . $numrow)->applyFromArray($style_left);
					$sheet->getStyle('T' . $numrow)->applyFromArray($style_center);
					$sheet->getStyle('U' . $numrow)->applyFromArray($style_right);
					$sheet->getStyle('V' . $numrow)->applyFromArray($style_right);

					$sheet->getStyle('A' . $numrow . ':V' . $numrow)->getFont()->setName('Times New Roman')->setSize(10);
					$no++;
					$numrow++;

				endforeach;


				$sheet->getColumnDimension('A')->setWidth(5);
				$sheet->getColumnDimension('B')->setWidth(35);
				$sheet->getStyle('B')->getAlignment()->setWrapText(true);
				$sheet->getColumnDimension('C')->setWidth(25);
				$sheet->getColumnDimension('D')->setWidth(11);
				$sheet->getColumnDimension('E')->setWidth(18);
				$sheet->getColumnDimension('F')->setWidth(7);
				$sheet->getColumnDimension('G')->setWidth(10);
				$sheet->getColumnDimension('H')->setWidth(40);
				$sheet->getStyle('H')->getAlignment()->setWrapText(true);
				$sheet->getColumnDimension('I')->setWidth(12);
				$sheet->getColumnDimension('J')->setWidth(20);
				$sheet->getStyle('J')->getNumberFormat()->setFormatCode('#,##0');
				$sheet->getColumnDimension('K')->setWidth(12);
				$sheet->getColumnDimension('L')->setWidth(30);
				$sheet->getColumnDimension('M')->setWidth(30);
				$sheet->getStyle('M')->getAlignment()->setWrapText(true);
				$sheet->getColumnDimension('N')->setWidth(17);
				$sheet->getStyle('N')->getNumberFormat()->setFormatCode('#,##0');
				$sheet->getColumnDimension('O')->setWidth(12);
				$sheet->getColumnDimension('P')->setWidth(12);
				$sheet->getColumnDimension('Q')->setWidth(17);
				$sheet->getStyle('Q')->getNumberFormat()->setFormatCode('#,##0');
				$sheet->getColumnDimension('R')->setWidth(26);
				$sheet->getStyle('R')->getAlignment()->setWrapText(true);
				$sheet->getColumnDimension('S')->setWidth(15);
				$sheet->getColumnDimension('T')->setWidth(12);
				$sheet->getColumnDimension('U')->setWidth(14);
				$sheet->getStyle('U')->getNumberFormat()->setFormatCode('#,##0');
				$sheet->getColumnDimension('V')->setWidth(14);
				$sheet->getStyle('V')->getNumberFormat()->setFormatCode('#,##0');
			}


			// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
			// $sheet->getDefaultRowDimension()->setRowHeight(-1);

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
		} else {
			$this->session->set_flashdata('message', 'Pilih data terlebih dahulu');
			redirect('FrmRptQueryPDPJ');
		}
	}
}

/* End of file FrmQueryPDPJ.php */
/* Location: ./application/controllers/FrmQueryPDPJ.php */