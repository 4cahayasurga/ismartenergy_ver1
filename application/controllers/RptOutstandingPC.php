<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class RptOutstandingPC extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->session->userdata('MIS_LOGGED_ID')) {
			$this->session->sess_destroy();
			redirect('Auth/index');
		}

		if ($this->session->userdata('MIS_LOGGED_ID') == NULL) {
			$this->session->sess_destroy();
			redirect('Auth/index');
		}
	}

	public function index()
	{
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';
		$data['judul'] = 'Rekap Outstanding/Approved PD';

		$PrdAwal = date('d-M-Y', strtotime(' -6 days'));
		$PrdAkhir = date('d-M-Y');
		$data['PrdAwal'] = $PrdAwal;
		$data['PrdAkhir'] = $PrdAkhir;
		// $PrdAkhir = date('Y-m-d', strtotime($PrdAwal. ' +6 days'));


		$data['ListJobNo'] = dapat_list_job();
		$data['ListAlokasi'] = AksesAlokasi();
		

		// print_rr($data);
		// exit;

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar');
		$this->load->view('RptOutstandingPC_view/main_rpt_outstanding_pc',$data);
		$this->load->view('templates/footer');
	}

	function ExcelExport_Outstanding(){
		if (empty($this->input->post('id_stok'))) {
			is_pesan('error','centang terlebih dahulu');
			redirect('RptOutstandingPC');
			// echo 'belum pilih';
			exit;
		}

		$PrdAwal = $this->input->post('PrdAwal',TRUE);
		$PrdAkhir = $this->input->post('PrdAkhir',TRUE);
		$By = $this->input->post('By',TRUE);
		$Type = $this->input->post('Type',TRUE);

		// print_rr($_POST);
		// die;
		$LedgerNo = "";
		if ($this->input->post('id_stok')) {
			$LedgerNo = $this->input->post('id_stok');
			$LedgerNo = implode(",",$LedgerNo);

		}

		$validasi_By = "";

		$validasi_By = ($By == 'PC') ? "PdHdr.ApprovedByKT AS 'ApprovedBy', " : "PdHdr.ApprovedByKK AS 'ApprovedBy', ";
		
		$ValidasiLanjutan = "";

		if ($By == 'PC') {

			if ($Type == 'Approved') {
				$ValidasiLanjutan = "ApprovedByKT IS NOT NULL AND TimeApprovedKT>='$PrdAwal' AND TimeApprovedKT<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L') ";
			}

			if($Type == 'OutStanding'){
				$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKT IS NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L')  ";
			}

		}

		if ($By == 'KEU') {

			if ($Type == 'Approved') {
				$ValidasiLanjutan = "ApprovedByKK IS NOT NULL AND TimeApprovedKK>='$PrdAwal' AND  TimeApprovedKK<='$PrdAkhir'  ";
			}

			if ($Type == 'OutStanding') {
				$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' ";
			}

		}


		$validasi_LedgerNo = "";
		$validasi_LedgerNo = " AND LedgerNo IN ($LedgerNo) ";
		// echo $validasi_LedgerNo;
		// exit;

		$AksesAlokasi = AksesAlokasi_HanyaNamaAlokasi();
		$arrayAlokasi = array();
		foreach($AksesAlokasi as $Al){
			$arrayAlokasi[] = $Al->Alokasi;
		}

		$AksesJob = AksesJob_HanyaJobNumber();
		$arrayJob = array();
		foreach($AksesJob as $JobAkses){
			$arrayJob[] = $JobAkses->JobNo;
		}

		
		$Company = $this->config->item('Company');

		$myquery = $this->db->query("
			SELECT  JobNm, CompanyId, PdHdr.JobNo AS 'JobNo1', PdHdr.AtasNama AS 'AtasNama1', PdHdr.*,
			".$validasi_By."
			(SELECT TOP 1 DueDate FROM Invoice, InvPD WHERE InvPD.NoPD=PdHdr.NoPD AND 
				InvPD.NoKo=Invoice.Noko AND InvPD.InvNo=Invoice.InvNo ORDER BY DueDate DESC) AS 'DueDate' 
			FROM PdHdr, Job WHERE PdHdr.JobNo=Job.JobNo AND RejectBy IS NULL AND ".$ValidasiLanjutan."
			AND Job.Company='$Company' ".$validasi_LedgerNo."  ")->result();


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

		$spreadsheet->getActiveSheet()->getStyle('A5:N5')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('7f8c8d');

		$sheet->setCellValue('A1', "REPORT OUTSTANDING / APPROVED PD");
		$sheet->mergeCells('A1:E1');
		$sheet->getStyle('A1')->getFont()->setBold(true);

		$sheet->setCellValue('A2', "By");
		$sheet->setCellValue('B2', ($By == 'PC') ? 'Project Control' : 'Keuangan' );
		$sheet->mergeCells('B2:C2');
		$sheet->getStyle('A2')->getFont()->setBold(false);

		$sheet->setCellValue('A3', "Type");
		$sheet->setCellValue('B3', $Type);
		$sheet->mergeCells('B3:C3');
		$sheet->getStyle('A3')->getFont()->setBold(false);


		$sheet->setCellValue('A4', "Periode");
		$sheet->setCellValue('B4', tgl_baru($PrdAwal) . ' s.d ' . tgl_baru($PrdAkhir));
		$sheet->mergeCells('B4:C4');
		$sheet->getStyle('A4')->getFont()->setBold(false);


		$sheet->setCellValue('A5', "No");
		$sheet->setCellValue('B5', "Job No");
		$sheet->setCellValue('C5', "Job Name");
		$sheet->setCellValue('D5', "No PD");
		$sheet->setCellValue('E5', "Alokasi");
		$sheet->setCellValue('F5', "Tipe Form");
		$sheet->setCellValue('G5', "Atas Nama");
		$sheet->setCellValue('H5', "Rekening Penerima");
		$sheet->setCellValue('I5', "No. KO");
		$sheet->setCellValue('J5', "Total PD (Rp)");
		$sheet->setCellValue('K5', "Tgl PD");
		$sheet->setCellValue('L5', "Jth Tempo");
		$sheet->setCellValue('M5', "Deskripsi");
		$sheet->setCellValue('N5', "Approved By");




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


		$no = 1;
		$numrow = 6;

		$TtlTrf = 0;
		$Saldo = 0;

		$sum_TotalPD = 0;
		foreach ($myquery as $mydata) :

			// $sum_total += $data->TotalPD;

			if ($By == 'KEU' && $Type == 'OutStanding' && ($mydata->Alokasi == 'B' || $mydata->Alokasi == 'C' || $mydata->Alokasi == 'L'  ) ) {

				if ($mydata->ApprovedByKT  == NULL || $mydata->ApprovedByKT == '' ) {
					continue;
				}	

			}

			if (!in_array($mydata->Alokasi, $arrayAlokasi)) {
				continue;
			}

			if (!in_array($mydata->JobNo1, $arrayJob)) {
				continue;
			}


									// cari saldo
			if (  ($mydata->TglPD >= '12/12/19' || $mydata->TglPD >= '2019-12-12') || ($mydata->Alokasi <> 'B'  && $mydata->Alokasi <> 'C') ) {

										//query_no_pj_Sebelumnya
				$query_NoPJSebelumnya = $this->db->query("SELECT NoPJSebelumnya FROM PdHdr WHERE NoPD='$mydata->NoPD' ");
				if ($query_NoPJSebelumnya->num_rows() > 0) {
					$row = $query_NoPJSebelumnya->row();
					if ($row->NoPJSebelumnya == NULL || $row->NoPJSebelumnya == '' ) {
						$Saldo = 0;
					}else{
						$query_saldo = $this->db->query("SELECT Saldo FROM PdHdr WHERE NoPJ='$mydata->NoPJSebelumnya' ");
						if ($query_saldo->num_rows() > 0 ) {
							$row_saldo = $query_saldo->row();
							$Saldo = $row_saldo->Saldo;
						}
					}
				}

			}else{
				$ceksaldo_kedua = $this->db->query("
					SELECT TOP 1 NoPJ,Saldo 
					FROM PdHdr 
					WHERE NoPD<'$mydata->NoPD' AND JobNo='$mydata->JobNo' AND 
					TipeForm='$mydata->TipeForm' AND KSO='$mydata->KSO' AND RejectBy IS NULL 
					ORDER BY NoPD DESC	");
				if ($ceksaldo_kedua->num_rows() > 0 ) {
					$row_cek_saldo = $ceksaldo_kedua->row();
					$Saldo = $row_cek_saldo->Saldo;
				}
			}

			$TtlTrf = $mydata->TotalPD - $Saldo;

			$sum_TotalPD += $TtlTrf;

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $mydata->JobNo1);
			$sheet->setCellValue('C' . $numrow, $mydata->JobNm.' - '.$mydata->CompanyId);
			$sheet->setCellValue('D' . $numrow, $mydata->NoPD);
			$sheet->setCellValue('E' . $numrow, $mydata->Alokasi);
			$sheet->setCellValue('F' . $numrow, $mydata->TipeForm);
			$sheet->setCellValue('G' . $numrow, $mydata->AtasNama1);
			$sheet->setCellValue('H' . $numrow, $mydata->NoRek);
			$sheet->setCellValue('I' . $numrow, $mydata->NoKO);
			$sheet->setCellValue('J' . $numrow, new_decimal($TtlTrf));
			$sheet->setCellValue('K' . $numrow, tgl_baru($mydata->TglPD));
			$sheet->setCellValue('L' . $numrow, tgl_baru($mydata->DueDate));
			$sheet->setCellValue('M' . $numrow, $mydata->Deskripsi);
			$sheet->setCellValue('N' . $numrow, $mydata->ApprovedBy);

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

		$sheet->setCellValue('A' . $numrow, "Sub Total");
		$sheet->mergeCells('A' . $numrow . ':I' . $numrow);
		$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('J' . $numrow, new_decimal($sum_TotalPD));
		$sheet->getStyle('J' . $numrow)->applyFromArray($new_gaya);


        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('G')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('H')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('I')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('J')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('K')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('L')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('M')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('N')->setWidth(30); // Set width kolom E

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $sheet->setTitle("Report OutStanding");

        // Proses file excel
        // $tgl_hari_ini = date('ymdHis');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="report_outstanding-' . date('ymdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');


		// print_rr($myquery);
    }


    function panggil_table_outstanding(){

    	if (!$this->input->post()) {
    		echo 'gagal';
    		exit;
    	}

    	$PrdAwal = $this->input->post('PrdAwal',TRUE);
    	$PrdAkhir = $this->input->post('PrdAkhir',TRUE);
    	$By = $this->input->post('By',TRUE);
    	$Type = $this->input->post('Type',TRUE);

    	$PrdAwal = date('Y-m-d', strtotime($PrdAwal));
    	$PrdAkhir = date('Y-m-d', strtotime($PrdAkhir));

    	$validasi_By = ($By == 'PC') ? "PdHdr.ApprovedByKT AS 'ApprovedBy', " : "PdHdr.ApprovedByKK AS 'ApprovedBy', ";

    	$ValidasiLanjutan = "";

    	if ($By == 'PC') {

    		if ($Type == 'Approved') {
    			$ValidasiLanjutan = "ApprovedByKT IS NOT NULL AND TimeApprovedKT>='$PrdAwal' AND TimeApprovedKT<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L') ";
    		}

    		if($Type == 'OutStanding'){
    			$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKT IS NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L')  ";
    		}

    	}

    	if ($By == 'KEU') {

    		if ($Type == 'Approved') {
    			$ValidasiLanjutan = "ApprovedByKK IS NOT NULL AND TimeApprovedKK>='$PrdAwal' AND  TimeApprovedKK<='$PrdAkhir'  ";
    		}

    		if ($Type == 'OutStanding') {
    			$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' ";
    		}

    	}

    	$AksesAlokasi = AksesAlokasi_HanyaNamaAlokasi();
    	$arrayAlokasi = array();
    	foreach($AksesAlokasi as $Al){
    		$arrayAlokasi[] = $Al->Alokasi;
    	}

    	$AksesJob = AksesJob_HanyaJobNumber();
    	$arrayJob = array();
    	foreach($AksesJob as $JobAkses){
    		$arrayJob[] = $JobAkses->JobNo;
    	}


    	$Company = $this->config->item('Company');

    	$myquery = $this->db->query("
    		SELECT JobNm, CompanyId, PdHdr.JobNo AS 'JobNo1', PdHdr.AtasNama AS 'AtasNama1', PdHdr.*,
    		".$validasi_By."
    		(SELECT TOP 1 DueDate FROM Invoice, InvPD WHERE InvPD.NoPD=PdHdr.NoPD AND 
    			InvPD.NoKo=Invoice.Noko AND InvPD.InvNo=Invoice.InvNo ORDER BY DueDate DESC) AS 'DueDate' 
    		FROM PdHdr, Job WHERE PdHdr.JobNo=Job.JobNo AND RejectBy IS NULL AND ".$ValidasiLanjutan."
    		AND Job.Company='$Company' ")->result();



    	$data['arrayAlokasi'] = $arrayAlokasi;
    	$data['arrayJob'] = $arrayJob;
    	$data['myquery'] = $myquery;
    	$data['By'] = $By;
    	$data['Type'] = $Type; 
    	$data['PrdAwal'] = $PrdAwal;
    	$data['PrdAkhir'] = $PrdAkhir;


    	$this->load->view('RptOutstandingPC_view/table_outstanding_pc',$data);
    }


    function aksi_print_from_checkbox(){

    	$PrdAwal = $this->input->post('PrdAwal',TRUE);
    	$PrdAkhir = $this->input->post('PrdAkhir',TRUE);
    	$By = $this->input->post('By',TRUE);
    	$Type = $this->input->post('Type',TRUE);

    	$LedgerNo = "";
    	if ($this->input->post('id_stok')) {
    		$LedgerNo = $this->input->post('id_stok');
    		$LedgerNo = implode(",",$LedgerNo);
    	}


    	$validasi_By = "";

    	$validasi_By = ($By == 'PC') ? "PdHdr.ApprovedByKT AS 'ApprovedBy', " : "PdHdr.ApprovedByKK AS 'ApprovedBy', ";

    	$ValidasiLanjutan = "";

    	if ($By == 'PC') {

    		if ($Type == 'Approved') {
    			$ValidasiLanjutan = "ApprovedByKT IS NOT NULL AND TimeApprovedKT>='$PrdAwal' AND TimeApprovedKT<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L') ";
    		}

    		if($Type == 'OutStanding'){
    			$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKT IS NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L')  ";
    		}

    	}

    	if ($By == 'KEU') {

    		if ($Type == 'Approved') {
    			$ValidasiLanjutan = "ApprovedByKK IS NOT NULL AND TimeApprovedKK>='$PrdAwal' AND  TimeApprovedKK<='$PrdAkhir'  ";
    		}

    		if ($Type == 'OutStanding') {
    			$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' ";
    		}

    	}


    	$validasi_LedgerNo = "";
    	$validasi_LedgerNo = " AND LedgerNo IN ($LedgerNo) ";


    	$AksesAlokasi = AksesAlokasi_HanyaNamaAlokasi();
    	$arrayAlokasi = array();
    	foreach($AksesAlokasi as $Al){
    		$arrayAlokasi[] = $Al->Alokasi;
    	}

    	$AksesJob = AksesJob_HanyaJobNumber();
    	$arrayJob = array();
    	foreach($AksesJob as $JobAkses){
    		$arrayJob[] = $JobAkses->JobNo;
    	}


    	$Company = $this->config->item('Company');

    	$myquery = $this->db->query("
    		SELECT JobNm, CompanyId, PdHdr.JobNo AS 'JobNo1', PdHdr.AtasNama AS 'AtasNama1', PdHdr.*,
    		".$validasi_By."
    		(SELECT TOP 1 DueDate FROM Invoice, InvPD WHERE InvPD.NoPD=PdHdr.NoPD AND 
    			InvPD.NoKo=Invoice.Noko AND InvPD.InvNo=Invoice.InvNo ORDER BY DueDate DESC) AS 'DueDate' 
    		FROM PdHdr, Job WHERE PdHdr.JobNo=Job.JobNo AND RejectBy IS NULL AND ".$ValidasiLanjutan."
    		AND Job.Company='$Company'  ")->result();
		// print_rr($myquery);
		// exit;

    	$this->load->library('Pdfgenerator');

        // title dari pdf
    	$this->data['title_pdf'] = 'Laporan Penjualan Toko Kita';


    	$file_pdf = "Rekap Outstanding/Approved PD - Print By - ".$this->session->userdata('MIS_LOGGED_ID').'_'.date('dmyHis');

    	$paper = 'A4';

    	$orientation = "Landscape";

    	$q = $myquery;


    	$this->data['data_outstanding_pc'] = $q;

    	$html = $this->load->view('RptOutstandingPC_view/export_pdf_outstanding_pc',$this->data, true);	    

    	$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);

		// echo 'success';


    }

    function iseng(){
    	$this->load->library('Pdfgenerator');
    	echo $this->pdfgenerator->coba_coba();
    }

    function export_pdf_outstanding(){

    	if (empty($this->input->post('id_stok'))) {
    		is_pesan('error','centang terlebih dahulu');
    		redirect('RptOutstandingPC');
			// echo 'belum pilih';
    		exit;
    	}

    	$PrdAwal = $this->input->post('PrdAwal',TRUE);
    	$PrdAkhir = $this->input->post('PrdAkhir',TRUE);
    	$By = $this->input->post('By',TRUE);
    	$Type = $this->input->post('Type',TRUE);

		// print_rr($_POST);
		// die;
    	$LedgerNo = "";
    	if ($this->input->post('id_stok')) {
    		$LedgerNo = $this->input->post('id_stok');
    		$LedgerNo = implode(",",$LedgerNo);

    	}

    	$validasi_By = "";

    	$validasi_By = ($By == 'PC') ? "PdHdr.ApprovedByKT AS 'ApprovedBy', " : "PdHdr.ApprovedByKK AS 'ApprovedBy', ";

    	$ValidasiLanjutan = "";

    	if ($By == 'PC') {

    		if ($Type == 'Approved') {
    			$ValidasiLanjutan = "ApprovedByKT IS NOT NULL AND TimeApprovedKT>='$PrdAwal' AND TimeApprovedKT<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L') ";
    		}

    		if($Type == 'OutStanding'){
    			$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKT IS NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L')  ";
    		}

    	}

    	if ($By == 'KEU') {

    		if ($Type == 'Approved') {
    			$ValidasiLanjutan = "ApprovedByKK IS NOT NULL AND TimeApprovedKK>='$PrdAwal' AND  TimeApprovedKK<='$PrdAkhir'  ";
    		}

    		if ($Type == 'OutStanding') {
    			$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' ";
    		}

    	}


    	$validasi_LedgerNo = "";
    	$validasi_LedgerNo = " AND LedgerNo IN ($LedgerNo) ";
		// echo $validasi_LedgerNo;
		// exit;

    	$AksesAlokasi = AksesAlokasi_HanyaNamaAlokasi();
    	$arrayAlokasi = array();
    	foreach($AksesAlokasi as $Al){
    		$arrayAlokasi[] = $Al->Alokasi;
    	}

    	$AksesJob = AksesJob_HanyaJobNumber();
    	$arrayJob = array();
    	foreach($AksesJob as $JobAkses){
    		$arrayJob[] = $JobAkses->JobNo;
    	}


    	$Company = $this->config->item('Company');

    	$myquery = $this->db->query("
    		SELECT  JobNm, CompanyId, PdHdr.JobNo AS 'JobNo1', PdHdr.AtasNama AS 'AtasNama1', PdHdr.*,
    		".$validasi_By."
    		(SELECT TOP 1 DueDate FROM Invoice, InvPD WHERE InvPD.NoPD=PdHdr.NoPD AND 
    			InvPD.NoKo=Invoice.Noko AND InvPD.InvNo=Invoice.InvNo ORDER BY DueDate DESC) AS 'DueDate' 
    		FROM PdHdr, Job WHERE PdHdr.JobNo=Job.JobNo AND RejectBy IS NULL AND ".$ValidasiLanjutan."
    		AND Job.Company='$Company' ".$validasi_LedgerNo."  ")->result();


    	$this->load->library('Pdfgenerator');

        // title dari pdf
    	$this->data['title_pdf'] = 'Laporan Penjualan Toko Kita';


    	$file_pdf = "Rekap Outstanding/Approved PD - Print By - ".$this->session->userdata('MIS_LOGGED_ID').'_'.date('dmyHis');

    	$paper = 'A3';

    	$orientation = "Landscape";

    	$q = $myquery;


    	$this->data['arrayAlokasi'] = $arrayAlokasi;
    	$this->data['arrayJob'] = $arrayJob;
    	$this->data['data_outstanding_pc'] = $q;
    	$this->data['printed_by'] = "Printed On ".date('d-M-Y')." By ".$this->session->userdata("MIS_LOGGED_NAME");
    	$this->data['By'] = $By;
    	$this->data['Type'] = $Type;
    	$this->data['PrdAwal'] = $PrdAwal;
    	$this->data['PrdAkhir'] = $PrdAkhir;
		// print_rr($this->data);
		// echo $LedgerNo;
		// die;

    	$html = $this->load->view('RptOutstandingPC_view/export_pdf_outstanding_pc',$this->data, true);	    

    	$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);
		// $this->pdfgenerator->pdf_otomatis_download($html, $file_pdf,$paper,$orientation);

		// echo 'success';


    }



    function export_to_excel()
    {

    	if (empty($this->input->post('id_stok'))) {
    		is_pesan('error','centang terlebih dahulu');
    		redirect('RptOutstandingPC');
    		exit;
    	}



    	$PrdAwal = $this->input->post('PrdAwal',TRUE);
    	$PrdAkhir = $this->input->post('PrdAkhir',TRUE);
    	$By = $this->input->post('By',TRUE);
    	$Type = $this->input->post('Type',TRUE);

		// print_rr($_POST);
		// die;
    	$LedgerNo = "";
    	if ($this->input->post('id_stok')) {
    		$LedgerNo = $this->input->post('id_stok');
    		$LedgerNo = implode(",",$LedgerNo);

    	}

    	$validasi_By = "";

    	$validasi_By = ($By == 'PC') ? "PdHdr.ApprovedByKT AS 'ApprovedBy', " : "PdHdr.ApprovedByKK AS 'ApprovedBy', ";

    	$ValidasiLanjutan = "";

    	if ($By == 'PC') {

    		if ($Type == 'Approved') {
    			$ValidasiLanjutan = "ApprovedByKT IS NOT NULL AND TimeApprovedKT>='$PrdAwal' AND TimeApprovedKT<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L') ";
    		}

    		if($Type == 'OutStanding'){
    			$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKT IS NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' AND (PdHdr.Alokasi='B' OR PdHdr.Alokasi='C' OR PdHdr.Alokasi='L')  ";
    		}

    	}

    	if ($By == 'KEU') {

    		if ($Type == 'Approved') {
    			$ValidasiLanjutan = "ApprovedByKK IS NOT NULL AND TimeApprovedKK>='$PrdAwal' AND  TimeApprovedKK<='$PrdAkhir'  ";
    		}

    		if ($Type == 'OutStanding') {
    			$ValidasiLanjutan = "(ApprovedByAK IS NOT NULL AND ApprovedByKK IS NULL) AND TglPD>='$PrdAwal' AND TglPD<='$PrdAkhir' ";
    		}

    	}


    	$validasi_LedgerNo = "";
    	$validasi_LedgerNo = " AND LedgerNo IN ($LedgerNo) ";
		// echo $validasi_LedgerNo;
		// exit;

    	$AksesAlokasi = AksesAlokasi_HanyaNamaAlokasi();
    	$arrayAlokasi = array();
    	foreach($AksesAlokasi as $Al){
    		$arrayAlokasi[] = $Al->Alokasi;
    	}

    	$AksesJob = AksesJob_HanyaJobNumber();
    	$arrayJob = array();
    	foreach($AksesJob as $JobAkses){
    		$arrayJob[] = $JobAkses->JobNo;
    	}


    	$Company = $this->config->item('Company');

    	$myquery = $this->db->query("
    		SELECT  JobNm, CompanyId, PdHdr.JobNo AS 'JobNo1', PdHdr.AtasNama AS 'AtasNama1', PdHdr.*,
    		".$validasi_By."
    		(SELECT TOP 1 DueDate FROM Invoice, InvPD WHERE InvPD.NoPD=PdHdr.NoPD AND 
    			InvPD.NoKo=Invoice.Noko AND InvPD.InvNo=Invoice.InvNo ORDER BY DueDate DESC) AS 'DueDate' 
    		FROM PdHdr, Job WHERE PdHdr.JobNo=Job.JobNo AND RejectBy IS NULL AND ".$ValidasiLanjutan."
    		AND Job.Company='$Company' ".$validasi_LedgerNo."  ")->result();

    	print_rr($_POST);	
    	exit;
		// ========================= batas 




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

    	$spreadsheet->getActiveSheet()->getStyle('A5:N5')->getFill()
    	->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    	->getStartColor()->setARGB('7f8c8d');

    	$sheet->setCellValue('A1', "REPORT OUTSTANDING / APPROVED PD");
    	$sheet->mergeCells('A1:E1');
    	$sheet->getStyle('A1')->getFont()->setBold(true);

    	$sheet->setCellValue('A2', "By");
    	$sheet->setCellValue('B2', $By_pilihan);
    	$sheet->mergeCells('B2:C2');
    	$sheet->getStyle('A2')->getFont()->setBold(false);

    	$sheet->setCellValue('A3', "Type");
    	$sheet->setCellValue('B3', $Type_pilihan);
    	$sheet->mergeCells('B3:C3');
    	$sheet->getStyle('A3')->getFont()->setBold(false);


    	$sheet->setCellValue('A4', "Periode");
    	$sheet->setCellValue('B4', tgl_baru($dari_tgl) . ' s.d ' . tgl_baru($sampai_tgl));
    	$sheet->mergeCells('B4:C4');
    	$sheet->getStyle('A4')->getFont()->setBold(false);


    	$sheet->setCellValue('A5', "No");
    	$sheet->setCellValue('B5', "Job No");
    	$sheet->setCellValue('C5', "Job Name");
    	$sheet->setCellValue('D5', "No PD");
    	$sheet->setCellValue('E5', "Alokasi");
    	$sheet->setCellValue('F5', "Tipe Form");
    	$sheet->setCellValue('G5', "Atas Nama");
    	$sheet->setCellValue('H5', "Rekening Penerima");
    	$sheet->setCellValue('I5', "No. KO");
    	$sheet->setCellValue('J5', "Total PD (Rp)");
    	$sheet->setCellValue('K5', "Tgl PD");
    	$sheet->setCellValue('L5', "Jth Tempo");
    	$sheet->setCellValue('M5', "Deskripsi");
    	$sheet->setCellValue('N5', "Approved By");




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


    	$no = 1;
    	$numrow = 6;

    	$sum_total = 0;
    	foreach ($query as $data) :

    		$sum_total += $data->TotalPD;

    		$sheet->setCellValue('A' . $numrow, $no);
    		$sheet->setCellValue('B' . $numrow, $data->NoPD);
    		$sheet->setCellValue('C' . $numrow, $data->JobNm);
    		$sheet->setCellValue('D' . $numrow, $data->NoPD);
    		$sheet->setCellValue('E' . $numrow, $data->Alokasi);
    		$sheet->setCellValue('F' . $numrow, $data->TipeForm);
    		$sheet->setCellValue('G' . $numrow, $data->AtasNama);
    		$sheet->setCellValue('H' . $numrow, $data->NoRek);
    		$sheet->setCellValue('I' . $numrow, $data->NoKO);
    		$sheet->setCellValue('J' . $numrow, new_decimal($data->TotalPD));
    		$sheet->setCellValue('K' . $numrow, tgl_baru($data->TglPD));
    		$sheet->setCellValue('L' . $numrow, tgl_baru($data->DueDate));
    		$sheet->setCellValue('M' . $numrow, $data->Deskripsi);
    		$sheet->setCellValue('N' . $numrow, $data->ApprovedBy);


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

    	$sheet->setCellValue('A' . $numrow, "Sub Total");
    	$sheet->mergeCells('A' . $numrow . ':I' . $numrow);
    	$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya);

    	$sheet->setCellValue('J' . $numrow, new_decimal($sum_total));
        // $sheet->mergeCells('J'.$numrow);
    	$sheet->getStyle('J' . $numrow)->applyFromArray($new_gaya);


        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('G')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('H')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('I')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('J')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('K')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('L')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('M')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('N')->setWidth(30); // Set width kolom E

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $sheet->setTitle("Report OutStanding");

        // Proses file excel
        // $tgl_hari_ini = date('ymdHis');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="report_outstanding-' . date('ymdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

}

/* End of file RptOutstandingPC.php */
/* Location: ./application/controllers/RptOutstandingPC.php */