<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FrmPayment_PD extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->helper('bantu');
		$this->load->model('Model_FrmPayment');
		$this->Company = $this->config->item('Company');
	}

	public function view($TipePembayaran=''){

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$DariTgl = date('Y-m-d',strtotime('-7 days'));
		$SampaiTgl = date('Y-m-d');

		$data['judul'] = 'Pembayaran Permintaan Dana';
		$data['bodyclass'] = 'sidebar-collapse skin-red-light';




		if ($TipePembayaran == '' || $TipePembayaran == 0 ) {
			$data['TipePembayaran'] = 0;
		}else{
			$data['TipePembayaran'] = 1;
		}

		$data['ListJob'] = $this->Model_FrmPayment->ListJob();
		$data['ListAlokasi'] = $this->Model_FrmPayment->ListAlokasi();
		$data['ListTipeForm'] = $this->Model_FrmPayment->ListTipeForm();

		$data['Keterangan'] = ['BIAYA ADMIN','PINDAH DANA','BUNGA','LAINNYA'];

		$data['ListRekening'] = $this->Model_FrmPayment->getListRekening();
		$data['ListBank'] = $this->Model_FrmPayment->getListBank();
		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmPayment_PD_View/halaman_utama_payment', $data);
		$this->load->view('templates/footer');
	}

	function getTablePayment(){
		if (!$this->input->post()) {
			exit;
		}

		$TipePembayaran = $this->input->post('TipePembayaran',TRUE);
		$DariTgl = ($this->input->post('DariTgl',TRUE)) ? $this->input->post('DariTgl',TRUE) : ''  ;
		$SampaiTgl = ($this->input->post('SampaiTgl',TRUE)) ? $this->input->post('SampaiTgl',TRUE) : '';

		$data['TipePembayaran'] = $TipePembayaran;

		if ($TipePembayaran == 0) {
			$data['list_data_payment_belum_dibayar'] = $this->Model_FrmPayment->get_list_data_payment_belum_dibayar();
			$this->load->view('FrmPayment_PD_View/table_belum_dibayar',$data);
		}

		if ($TipePembayaran == 1) {
			$data['DariTgl'] = $DariTgl;
			$data['SampaiTgl'] = $SampaiTgl;
			$data['list_SudahBayar_PD'] = $this->Model_FrmPayment->dapatkan_ListDataSudahBayar($DariTgl,$SampaiTgl);

			$this->load->view('FrmPayment_PD_View/table_sudah_dibayar',$data);

		}

	}

	function tampil_FrmPembayaran(){
		if (!$this->input->post()) {
			exit;
		}

		if (!$this->input->post('NoPD',TRUE)) {
			exit;
		}

		$NoPD = $this->input->post('NoPD',TRUE);
		$getPD = $this->Model_FrmPayment->get_DataPD($NoPD);
		$data['NoPD'] = $NoPD;
		$data['mydataPD'] = $getPD;

		$data['ListJob'] = $this->Model_FrmPayment->ListJob();
		$data['ListAlokasi'] = $this->Model_FrmPayment->ListAlokasi();
		$data['ListTipeForm'] = $this->Model_FrmPayment->ListTipeForm();

		$data['Keterangan'] = ['BIAYA ADMIN','PINDAH DANA','BUNGA','LAINNYA'];

		$data['ListRekening'] = $this->Model_FrmPayment->getListRekening();
		$data['ListBank'] = $this->Model_FrmPayment->getListBank();
		$this->load->view('FrmPayment_PD_View/FrmPembayaran',$data);
	}

	function AksiPembayaran(){
		if(!$this->input->post()){
			exit;
		}

		// print_rr($_POST);
		// $JobNo = $this->input->post('JobNo',TRUE);
		// $Alokasi = $this->input->post('Alokasi',TRUE);
		// $TipeForm = $this->input->post('TipeForm',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);

		$TglBayar = $this->input->post('TglByr',TRUE);
		$Keterangan = $this->input->post('Keterangan',TRUE);
		$RekId = $this->input->post('NoRekPengirim',TRUE);
		$CaraBayar = $this->input->post('Via',TRUE);
		$JenisTrf = $this->input->post('JenisTransfer',TRUE);
		$SumberKas = $this->input->post('SumberKas',TRUE);
		$NoCG = $this->input->post('NoCG',TRUE);

		$NoRek = $this->input->post('NoRekPenerima',TRUE);
		$AtasNama = $this->input->post('AtasNamaPenerima',TRUE);
		$Bank = $this->input->post('BankPenerima',TRUE);
		$Amount = $this->input->post('TotalPD',TRUE);
		$TimeEntry = date('Y-m-d H:i:s');
		$NmPenerimaTunai = $this->input->post('NamaPenerimaTunai',TRUE);
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");

		$data = array(
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
			'TglBayar' => $TglBayar,
		);

	}

	function FrmDetailPD($LedgerNo='',$NoPD='', $TipePembayaran=''){
		if ($LedgerNo =='' || $NoPD =='') {
			redirect('FrmPayment_PD/'.$TipePembayaran);
		}

		// $user_id = $this->session->userdata('MIS_LOGGED_ID');
		$data['judul'] = 'Form Pembayaran';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		// $data['LedgerNo'] = $LedgerNo;
		$data['NoPD'] = $NoPD;
		$data['TipePembayaran'] = $TipePembayaran;

		// $data['ListJob'] = $this->Model_FrmPayment->ListJob();
		// $data['ListAlokasi'] = $this->Model_FrmPayment->ListAlokasi();
		// $data['ListTipeForm'] = $this->Model_FrmPayment->ListTipeForm();

		// $data['Keterangan'] = ['BIAYA ADMIN','PINDAH DANA','BUNGA','LAINNYA'];

		$data['ListRekening'] = $this->Model_FrmPayment->getListRekening();
		$data['ListBank'] = $this->Model_FrmPayment->getListBank();

		// $getPD = $this->Model_FrmPayment->get_DataPD($NoPD,$LedgerNo);
		// $data['mydataPD'] = $getPD;

		// $data['mydataPD'] = $this->db->query("SELECT A.*, B.JobNm FROM PdHdr A JOIN Job B ON A.JobNo=B.JobNo WHERE NoPD='$NoPD'")->row();
		$query =  $this->Model_FrmPayment->dapatkan_DataPD($NoPD);
		$data['mydataPD'] = $query;

		$Alokasi = $query->Alokasi;
		$NoPJ = NULL;
		$Saldo = 0;
		$JobNo = $query->JobNo;
		$TipeForm = $query->TipeForm;
		$KSO = $query->KSO;
		$TotalPD = $query->TotalPD;
		// echo $KSO;

		if ($Alokasi <> 'B' OR $Alokasi <> 'C' ) {
			$query_NoPJSebelumnya = $this->db->query("SELECT NoPJSebelumnya FROM PdHdr WHERE NoPD='$NoPD' ");

			if ($query_NoPJSebelumnya->num_rows() > 0) {

				$row_NoPJSebelumnya = $query_NoPJSebelumnya->row();

				if ($row_NoPJSebelumnya->NoPJSebelumnya == NULL || $row_NoPJSebelumnya->NoPJSebelumnya == '' ) {
					$NoPJ = NULL;
					$Saldo = 0;
				}else{
					$NoPJSebelumnya = $row_NoPJSebelumnya->NoPJSebelumnya;
					$NoPJ = $row_NoPJSebelumnya->NoPJSebelumnya;
					$query_lagi = $this->db->query("SELECT Saldo FROM PdHdr WHERE NoPJ='$NoPJSebelumnya' ");
					if ($query_lagi->num_rows() > 0) {
						$myrow = $query_lagi->row();
						$Saldo = $myrow->Saldo;
					}
				}

			}
		}else{
			$myquery_cek = $this->db->query("SELECT TOP 1 NoPJ,Saldo FROM PdHdr WHERE NoPD<'$NoPD' AND JobNo='$JobNo' AND TipeForm='$TipeForm' AND KSO='$KSO' AND RejectBy IS NULL ORDER BY NoPD DESC");
			if ($myquery_cek->num_rows()> 0) {
				$row_cek = $myquery_cek->row();
				$NoPJ = $row_cek->NoPJ;
				$Saldo = $row_cek->Saldo;
			}
		}



		$data['NoPJ'] = $NoPJ;
		$data['Saldo'] = $Saldo;
		$TotalPembayaran = 0;
		$TotalPembayaran = ($TotalPD - $Saldo);
		$data['TotalPembayaran'] = $TotalPembayaran;
		$data['TotalPD'] = $TotalPD;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmPayment_PD_View/FrmDetailPD', $data);
		$this->load->view('templates/footer');
	}
	function validasiNULL($data=''){
		return ($data == NULL || $data='') ? NULL : $data;
	}

	function AksiSimpanPembayaranPD(){
		if (!$this->input->post()) {
			exit;
		}

		// print_rr($_POST);
		// exit;

		$TipePembayaran = $this->input->post('TipePembayaran',TRUE);

		
		$NoPD = $this->input->post('NoPD',TRUE);
		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);
		$TipeForm = $this->input->post('TipeForm',TRUE);

		$TglBayar =  $this->input->post('TglByr',TRUE);
		$Keterangan = $this->input->post('Keterangan',TRUE);
		$RekId = $this->input->post('NoRekPengirim',TRUE);
		$CaraBayar = $this->input->post('Via',TRUE);
		$JenisTrf = $this->input->post('JenisTransfer',TRUE);
		$SumberKas = $this->input->post('SumberKas',TRUE);
		$NoCG = $this->input->post('NoCG',TRUE);

		$NoRek = $this->input->post('NoRekPenerima',TRUE);
		$AtasNama = $this->input->post('AtasNamaPenerima',TRUE);
		$Bank = $this->input->post('BankPenerima',TRUE);
		$Amount = $this->input->post('TotalPembayaran',TRUE);
		$TimeEntry = date('Y-m-d H:i:s');
		$NmPenerimaTunai = $this->input->post('NamaPenerimaTunai',TRUE);
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");

		$data = array(



			'TglBayar' => $TglBayar,
			'NoPD' => $NoPD,
			'JobNo' => $JobNo,
			'Keterangan' => $Keterangan,
			'Alokasi' => $Alokasi,
			
			'TipeForm' => $TipeForm,
			'NoKO' => $NoKO,
			'RekId' => $RekId,
			'CaraBayar' => $CaraBayar,
			'JenisTrf' => $JenisTrf,
			'NoCG' => $NoCG,

			'NoRek' => $NoRek,
			'AtasNama' => $AtasNama,
			'Bank' => $Bank,
			'Amount' => HapusKomaPHP($Amount),
			'NmPenerimaTunai' => $NmPenerimaTunai,
			'SumberKas' => $SumberKas,
			'TimeEntry' => $TimeEntry,
			'UserEntry' => $UserEntry,
			// 'NoPD' => $NoPD,
			// 'JenisTrf' => $JenisTrf,
			// 'SumberKas' => $SumberKas,
			// 'NoCG' => $NoCG,
			// 'NoKO' => $NoKO,
		);

		// print_rr($data);
		// exit;

		$this->db->trans_start();
		$this->db->insert('BLE',$data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			// echo 'gagal';
			is_pesan('error','gagal melakukan pembayaran');
			redirect('FrmPayment_PD/view/'.$TipePembayaran);
		}else{
			// echo 'berhasil';
			is_pesan('success','berhasil melakukan pembayaran');
			redirect('FrmPayment_PD/view/'.$TipePembayaran);
		}
		// if ($CaraBayar !='' || $CaraBayar !=NULL) {
		// 	$data['JenisTrf'] = $JenisTrf;
		// 	$data['NoCG'] = $NoCG;
		// 	$data['SumberKas'] = $SumberKas;
		// }else{
		// 	$data['JenisTrf'] = NULL;
		// 	$data['NoCG'] = NULL;
		// 	$data['SumberKas'] = NULL;
		// }


		// if ($CaraBayar !='' || $CaraBayar !=NULL) {
		// 	$data['NmPenerimaTunai'] = $NmPenerimaTunai;
		// }else{
		// 	$data['NmPenerimaTunai'] = NULL;
		// }



		// print_rr($data);
	}


	function AksiDeletePembayaran(){
		if (!$this->input->post()) {
			exit;
		}

		// print_rr($_POST);
		// exit;

		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);


		$cek_PJ = $this->db->query("SELECT NoPJ FROM PdHdr WHERE NoPD='$NoPD' AND NoPJ IS NOT NULL ")->num_rows();
		if ($cek_PJ > 0) {
			echo 'pj_ada';
			die;
		}

		$this->db->trans_start();

		$this->db->query("DELETE FROM BLE WHERE LedgerNo='$LedgerNo' ");

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE ) {
			echo 'gagal';
		}else{
			echo 'berhasil';
		}
	}

	function PdfExport_Payment($LedgerNo=''){
		if ($LedgerNo == '') {
			redirect('FrmPayment_PD/view','refresh');
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$myquery = $this->db->query("
			SELECT A.*, B.JobNm, C.NoRek AS 'RekKirim',  
			C.AtasNama AS 'NamaKirim', C.Bank AS 'BankKirim',
			CASE
			WHEN A.CaraBayar='TRF' THEN 'TRANSFER'
			WHEN A.CaraBayar='CG' THEN 'CG'
			WHEN A.CaraBayar='CASH' THEN 'CASH'
			ELSE ''
			END AS 'CaraBayar1'

			FROM BLE A  
			LEFT JOIN Job B ON A.JobNo=B.JobNo  
			LEFT JOIN Rekening C ON A.RekId=C.RekId  
			WHERE A.LedgerNo='$LedgerNo' ")->row();

		$data['mydata'] = $myquery;

		$this->load->view('FrmPayment_PD_View/PDF_tengah', $data);
	}

	function PaymentExportExcel(){

		if (!$this->input->post()) {
			exit;
		}

		if (!is_array($this->input->post('IdPayment_array',TRUE))) {
			exit;
		}


		$IdPayment_array = $this->input->post('IdPayment_array',TRUE);
		$DariTgl = ($this->input->post('DariTgl',TRUE)) ? $this->input->post('DariTgl',TRUE) : ''  ;
		$SampaiTgl = ($this->input->post('SampaiTgl',TRUE)) ? $this->input->post('SampaiTgl',TRUE) : '';

		$IdPayment_array = implode(',',$IdPayment_array);
		$mydata = $this->Model_FrmPayment->dapatkan_ListDataSudahBayar_For_ExportExcel($IdPayment_array,$DariTgl,$SampaiTgl);

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

		$sheet->setCellValue('A1', "Pembayaran Permintaan Dana");
		$sheet->mergeCells('A1:E1');
		$sheet->getStyle('A1')->getFont()->setBold(true);

		$sheet->setCellValue('A2', "Tgl Bayar");
		$sheet->setCellValue('B2', tgl_baru($DariTgl));
		$sheet->setCellValue('C2', 's.d');
		$sheet->setCellValue('D2', tgl_baru($SampaiTgl));
		$sheet->getStyle('A2')->getFont()->setBold(false);


		$sheet->setCellValue('A5', "No");
		$sheet->setCellValue('B5', "Tgl Bayar");
		$sheet->setCellValue('C5', "Tipe Form");
		$sheet->setCellValue('D5', "Alokasi");
		$sheet->setCellValue('E5', "No. PD");
		$sheet->setCellValue('F5', "Job Name");
		$sheet->setCellValue('G5', "Job No");
		$sheet->setCellValue('H5', "Nama Penerima");
		$sheet->setCellValue('I5', "Rekening Penerima");
		$sheet->setCellValue('J5', "Bank Penerima");
		$sheet->setCellValue('K5', "Nilai (Rp)");
		$sheet->setCellValue('L5', "Media");
		$sheet->setCellValue('M5', "Via");
		$sheet->setCellValue('N5', "No. Cek/BG");
		$sheet->setCellValue('O5', "Rekening Pengirim");

		$sheet->setCellValue('P5', "No. KO");
		$sheet->setCellValue('Q5', "Keterangan");

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

		$sheet->getStyle('O5')->applyFromArray($style_col);
		$sheet->getStyle('P5')->applyFromArray($style_col);
		$sheet->getStyle('Q5')->applyFromArray($style_col);


		$no = 1;
		$numrow = 6;
		$SubTotalPembayaran = 0;

		foreach ($mydata as $key) :

			$SubTotalPembayaran += $key->Amount;

			$sheet->setCellValue('A' . $numrow, $no++);
			$sheet->setCellValue('B' . $numrow, tgl_baru($key->TglBayar));
			$sheet->setCellValue('C' . $numrow, $key->TipeForm);
			$sheet->setCellValue('D' . $numrow, $key->Alokasi);
			$sheet->setCellValue('E' . $numrow, $key->NoPD);
			$sheet->setCellValue('F' . $numrow, $key->JobNm);
			$sheet->setCellValue('G' . $numrow, $key->JobNo);
			$sheet->setCellValue('H' . $numrow, $key->AtasNama);
			$sheet->setCellValue('I' . $numrow, $key->NoRek);
			$sheet->setCellValue('J' . $numrow, $key->Bank);
			$sheet->setCellValue('K' . $numrow, number_format($key->Amount));
			$sheet->setCellValue('L' . $numrow, $key->CaraBayar);
			$sheet->setCellValue('M' . $numrow, $key->JenisTrf);
			$sheet->setCellValue('N' . $numrow, $key->NoCG);

			$sheet->setCellValue('O' . $numrow, $key->RekId);
			$sheet->setCellValue('P' . $numrow, $key->NoKO);
			$sheet->setCellValue('Q' . $numrow, $key->Keterangan);

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

		$sheet->setCellValue('A' . $numrow, "Sum = ".number_format($SubTotalPembayaran));
		$sheet->mergeCells('A' . $numrow . ':K' . $numrow);
		$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya);


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
		$sheet->getColumnDimension('O')->setWidth(30);
		$sheet->getColumnDimension('P')->setWidth(30);
		$sheet->getColumnDimension('Q')->setWidth(30);

		$sheet->getDefaultRowDimension()->setRowHeight(-1);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

		$sheet->setTitle("Pembayaran Permintaan Dana");

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Pembayaran Permintaan Dana.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');


	}

}

/* End of file FrmPayment_PD.php */
/* Location: ./application/controllers/FrmPayment_PD.php */