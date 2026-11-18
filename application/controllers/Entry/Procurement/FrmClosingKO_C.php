<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \Mpdf\Mpdf;
class FrmClosingKO_C extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	public function index($JobNo=''){

		$data['title'] = 'CLOSING KO';
		$data['judul'] = 'CLOSING KO';
		$data['bodyclass'] = 'skin-blue';
		$data['ListJob'] = ListJob();

		$data['JobNo'] = $JobNo;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/procurement/ClosingKO_V/halaman_utama_ClosingKO', $data);
		$this->load->view('templates/footer');
	}

	function table_ClosingKO(){
		if (!$this->input->post()) {
			echo '';
			die;
		}

		$JobNo = ($this->input->post('JobNo',TRUE)) ? $this->input->post('JobNo',TRUE) : '';

		$data['ListTable'] = $this->db->query("SELECT A.*, A.SubTotal-A.DiscAmount+A.PPN AS 'TotalKO', B.VendorNm, (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoKO=A.NoKO) AS PaymentAmount FROM KoHdr A LEFT JOIN Vendor B ON A.VendorId=B.VendorId WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL ORDER BY NoKO DESC")->result();

		$data['JobNo'] = $JobNo;
		$this->load->view('entry_view/procurement/ClosingKO_V/table_ClosingKO', $data);

	}

	function aksi_penutupan_KO(){

		if (!$this->input->post('JobNo')) {
			is_pesan('error','Terjadi Kesalahan');
			redirect('Entry/Procurement/FrmClosingKO_C/');
			exit;
		}

		if (!$this->input->post('NoKO')) {
			is_pesan('error','Terjadi Kesalahan');
			redirect('Entry/Procurement/FrmClosingKO_C/index/'.$this->input->post('JobNo'));
			exit;
		}

		$NoKO = ($this->input->post('NoKO')) ? $this->input->post('NoKO') : '';
		$JobNo = ($this->input->post('JobNo')) ? $this->input->post('JobNo') : '';
		$ClosedReason = ($this->input->post('ClosedReason')) ? $this->input->post('ClosedReason') : '';


		// == START CEK PENUTUPAN KO ==
		$queryClosed = $this->db->query("SELECT NoKO, ClosedBy FROM KoHdr WHERE NoKO='$NoKO' ");
		$rowClosed = $queryClosed->row_array();

		if ($rowClosed['ClosedBy'] !='' || $rowClosed['ClosedBy'] !=NULL ) {
			is_pesan('info',$NoKO.' sudah di-tutup.');
			redirect('Entry/Procurement/FrmClosingKO_C/index/'.$JobNo);
			die;
		}
		// == END CEK PENUTUPAN KO ==


		// == START CEK PEMBAYARAN ATAU KASIR ==
		$queryKasir = $this->db->query("SELECT NoPD, (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE BLE.NoPD=PdHdr.NoPD) AS 'KasirBayar' FROM PdHdr WHERE NoKO='$NoKO' AND RejectBy IS NULL")->result_array();
		foreach($queryKasir as $rowKasir):
			if ($rowKasir['KasirBayar'] == '0' || $rowKasir['KasirBayar'] == 0) {
				is_pesan('info','Tidak bisa tutup KO. <br> '.$rowKasir['NoPD'].' belum dibayar.');
				redirect('Entry/Procurement/FrmClosingKO_C/index/'.$JobNo);
				break;
			}
		endforeach;
		// == END CEK PEMBAYARAN ATAU KASIR ==

		// == START CEK PEMBAYARAN ATAU INVOICE ==
		$query_Invoice = $this->db->query("SELECT NoKO, InvNo FROM Invoice WHERE NoKO='$NoKO'")->result();
		foreach($query_Invoice as $row_Invoice):
			
			$queryLanjutan = $this->db->query("SELECT NoPD, (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE BLE.NoPD=InvPD.NoPD) AS 'KasirBayar' FROM InvPD WHERE NoKO='$row_Invoice->NoKO' AND InvNo='$row_invoice->InvNo'")->result_array();

			foreach($queryLanjutan as $rowLanjutan):
				if ($rowLanjutan['KasirBayar'] == 0 || $rowLanjutan['KasirBayar'] =='0') {
					is_pesan('info', 'Tidak bisa tutup KO <br> Invoice '.$row_invoice->InvNo.' pada '.$rowLanjutan['NoPD'].' belum dibayar');
					redirect('Entry/Procurement/FrmClosingKO_C/index/'.$JobNo);
					break;
				}
			endforeach;

		endforeach;
		// == END CEK PEMBAYARAN ATAU INVOICE ==

		$ClosedBy = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeClosed = date('Y-m-d H:i:s');
		$this->db->query("UPDATE KoHdr SET ClosedBy='$ClosedBy',TimeClosed='$TimeClosed', ClosedReason='$ClosedReason' WHERE NoKO='$NoKO' ");
		is_pesan('success','Berhasil');
		redirect('Entry/Procurement/FrmClosingKO_C/index/'.$JobNo);


		
	}

	function ExportExcel_dataPenutupanKO($JobNo=''){
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', "No KO"); 
		$sheet->setCellValue('B1', "Tgl KO");
		$sheet->setCellValue('C1', "Vendor"); 

		$no=1;
		$numrow=2;
		$query = $this->db->query("SELECT A.*, A.SubTotal-A.DiscAmount+A.PPN AS 'TotalKO', B.VendorNm, (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoKO=A.NoKO) AS PaymentAmount FROM KoHdr A LEFT JOIN Vendor B ON A.VendorId=B.VendorId WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL ORDER BY NoKO DESC")->result();
		foreach ($query as $data) :
			$sheet->setCellValue('A'.$numrow, $data->NoKO);
			$sheet->setCellValue('B'.$numrow, $data->TglKO);
			$sheet->setCellValue('C'.$numrow, $data->VendorNm);
		endforeach;

		$writer = new Xlsx($spreadsheet);

		$filename = 'penutupanKO';

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
		header('Cache-Control: max-age=0');

		$writer->save('php://output');
	}

	function ExportPDF_dataPenutupanKO($JobNo=''){
		if ($JobNo == '') {
			exit;
		}
		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;
		$data['ListTable'] = $this->db->query("SELECT A.*, A.SubTotal-A.DiscAmount+A.PPN AS 'TotalKO', B.VendorNm, (SELECT ISNULL(SUM(Amount),0) FROM BLE WHERE NoKO=A.NoKO) AS PaymentAmount FROM KoHdr A LEFT JOIN Vendor B ON A.VendorId=B.VendorId WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL ORDER BY NoKO DESC")->result();
		$this->load->view('entry_view/procurement/ClosingKO_V/pdf/content', $data);
	}




}

/* End of file ApprovalPDKeuangan_C.php */
/* Location: ./application/controllers/Entry/PDPJ/ApprovalPDKeuangan_C.php */
