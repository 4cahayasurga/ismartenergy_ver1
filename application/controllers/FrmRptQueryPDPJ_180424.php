<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

	function getDataForChildRowTablePDPJ()
	{
		$NoPD = $this->input->post("NoPD", true);
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
		echo json_encode($data);
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

			// $sheet->setCellValue('A2', "Job No");
			// $sheet->setCellValue('B2', $JobNo1);
			// $sheet->mergeCells('B2:C2');
			// $sheet->getStyle('A2')->getFont()->setBold(false);

			// $sheet->setCellValue('A3', "Alokasi");
			// $sheet->setCellValue('B3', $Alokasi);
			// $sheet->mergeCells('B3:C3');
			// $sheet->getStyle('A3')->getFont()->setBold(false);


			// $sheet->setCellValue('A4', "");
			// $sheet->setCellValue('B4', "");
			// $sheet->mergeCells('B4:C4');
			// $sheet->getStyle('A4')->getFont()->setBold(false);

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
				$sheet->setCellValue('J' . $numrow, number_format($TotalKO));
				$sheet->setCellValue('K' . $numrow, $key->NoTagihan);
				$sheet->setCellValue('L' . $numrow, $key->NoRek);
				$sheet->setCellValue('M' . $numrow, $key->AtasNama);
				$sheet->setCellValue('N' . $numrow, number_format($key->TotalPD));

				$sheet->setCellValue('O' . $numrow, ($key->TglBayar == '1900-01-01' or $key->TglBayar == NULL or $key->TglBayar == '') ? '' : tgl_baru($key->TglBayar));
				$sheet->setCellValue('P' . $numrow, $key->JenisTrf);
				$sheet->setCellValue('Q' . $numrow, number_format($key->BLEAmount));
				$sheet->setCellValue('R' . $numrow, $key->RekId);
				$sheet->setCellValue('S' . $numrow, $key->NoPJ);
				$sheet->setCellValue('T' . $numrow, tgl_baru($key->TglPJ));
				$sheet->setCellValue('U' . $numrow, number_format($key->TotalPJ));

				$sheet->setCellValue('V' . $numrow, number_format($key->Saldo1));


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
		} else {
			$this->session->set_flashdata('message', 'Pilih data terlebih dahulu');
			redirect('FrmRptQueryPDPJ');
		}
	}
}

/* End of file FrmQueryPDPJ.php */
/* Location: ./application/controllers/FrmQueryPDPJ.php */