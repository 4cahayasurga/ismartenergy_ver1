<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL);
class Invoice extends CI_Controller {

	var $Company;

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		Pembuatan_InvNo('tblBarangMasuk_Detail');

		// -- ADD DATE : 19 JANUARI 2024
		PembuatanField_Persetujuan_TagihanLebihBesar();

		// -- ADD DATE : 22 JANUARI 2024
		PembuatanTable_InvoiceSJ();

		// -- ADD DATE : 24 JANUARI 2024
		AddField_SisaInvoice_To_tblBarangMasuk_Detail();
		AddField_SisaInvoice_To_Invoice_SJ();
	}

	function percobaan(){
		$this->load->view('percobaan2');
	}

	function index($JobNo=''){

		$data['judul'] 			= 'Invoice';
		$data['title'] 			= 'Invoice';
		$data['bodyclass'] 		= 'sidebar-collapse skin-blue-light';
		$data['ListJob'] 		= $this->GetJob();
		$data['JobNo'] 			= $JobNo;
		$data['js_saya'] 		= 'Invoice_View/js_halaman_utama_invoice';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Invoice_View/halaman_utama_invoice', $data);
		$this->load->view('templates/footer');
	}



	function get_tableInvoice(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$myquery = $this->db->query("SELECT A.*, C.VendorNm FROM Invoice A LEFT JOIN KoHdr B ON A.NoKO=B.NoKO LEFT JOIN Vendor C ON B.VendorId=C.VendorId WHERE A.JobNo='$JobNo' ORDER BY InvDate DESC")->result();

		$data['ListInvoice'] 	= $myquery;
		$data['JobNo'] 			= $JobNo;

		$this->load->view('Invoice_View/tableInvoice', $data);
	}

	// -- START PENYIMPANAN (INSERT) KE DATABASE
	function AddNew($JobNo){
		if ($JobNo=='') {
			redirect('Entry/Procurement/Invoice');
			die;
		}

		$data['judul'] 			= 'Entry Invoice';
		$data['title'] 			= 'Entry Invoice';
		$data['bodyclass'] 		= 'sidebar-collapse skin-blue-light';

		$data['JobNo'] 			= $JobNo;
		$data['JobNm'] 			= $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
		$data['ListNoKO'] 		= $this->get_ListNoKO($JobNo);
		$data['Aksi']			= 'NEW';

		$data['js_saya'] 		= 'Invoice_View/js_FormEntryInvoice';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Invoice_View/FormEntryInvoice', $data);
		$this->load->view('templates/footer');
		
	}

	function get_ListNoKO($JobNo){
		$query = $this->db->query("
			BEGIN
			SELECT A.NoKO, FORMAT(A.TglKO,'dd-MMM-yyyy') AS TglKO, 
			B.VendorNm, FORMAT((A.SubTotal - A.DiscAmount) + A.PPN,'N0') AS TotalKO
			FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId WHERE
			JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ORDER BY NoKO DESC
			END
			");
		return $query->result();
	}


	function List_SJ(){
		if (!$this->input->post()) {
			echo die;
		}

		$JobNo 			= $this->input->post('JobNo');
		$NoKO 			= $this->input->post('NoKO');
		$Aksi 			= $this->input->post('Aksi');
		$InvNo_validasi = ($Aksi=='NEW') ? "AND InvNo IS NULL" : "";
		
		$NoSJ_Lama 			= ($this->input->post('NoSJ_Lama')) ? $this->input->post('NoSJ_Lama') : NULL ;


		$myquery = $this->db->query("SELECT DISTINCT(NoSJ) AS 'NoSJ' FROM tblBarangMasuk_Detail WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND ApprovedBy IS NOT NULL ".$InvNo_validasi."  ");

		$arr = '<option value="">:: pilih salah satu ::</option>';
		if ($myquery->num_rows() > 0) {
			foreach($myquery->result() as $row):

				$selected = "";
				if($NoSJ_Lama!=NULL){
					if ($NoSJ_Lama==$row->NoSJ) {
						$selected = "selected";
					}
				}

				$arr .='<option value='.$row->NoSJ.' '.$selected.'>'.$row->NoSJ.'</option>';
			endforeach;
		}

		echo $arr;
	}

	function table_SisaInvoice(){
		if (!$this->input->post()) {
			die;
		}

		$NoKO = $this->input->post('NoKO');
		$JobNo = $this->input->post('JobNo');
		$NoSJ_SisaInvoice = $this->input->post('NoSJ_SisaInvoice');

		$query = $this->db->query("SELECT a.*, b.LedgerNo, 
			(ISNULL(a.VolBrgMasuk,0)*ISNULL(a.HrgSatuan,0)) AS Total,
			ISNULL(b.VolKO,0) AS VolKO,
			ISNULL(b.VolBrgMasuk,0) AS VolBrgMasuk_Detail,
			ISNULL(a.VolBrgMasuk,0) AS VolBrgMasuk_Invoice,
			(ISNULL(b.VolBrgMasuk,0)-ISNULL(a.VolBrgMasuk,0)) AS Vol_SisaInvoice
			FROM Invoice_SJ a 
			INNER JOIN tblBarangMasuk_Detail b ON b.NoKO=a.NoKO AND b.NoSJ=a.NoSJ AND b.NoUrut=a.NoUrut AND b.InvNo=a.InvNo 
			WHERE a.NoSJ='$NoSJ_SisaInvoice' ");

		$MyArray = array();
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row):
				
				$VolBrgMasuk 	= 0;
				$HrgSatuan 	 	= 0;
				$Total 			= 0;

				$VolBrgMasuk 			= CekNull_Angka_Dan_Bikin_Float($row->VolBrgMasuk);
				$HrgSatuan 				= CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan);
				$Total 					= CekNull_Angka_Dan_Bikin_Float($row->Total);
				$VolKO 					= CekNull_Angka_Dan_Bikin_Float($row->VolKO);
				$VolBrgMasuk_Detail 	= CekNull_Angka_Dan_Bikin_Float($row->VolBrgMasuk_Detail);
				$VolBrgMasuk_Invoice 	= CekNull_Angka_Dan_Bikin_Float($row->VolBrgMasuk_Invoice);
				$Vol_SisaInvoice 		= CekNull_Angka_Dan_Bikin_Float($row->Vol_SisaInvoice);

				$IsChecked = 0;

				if ($row->Vol_SisaInvoice==0) {
					continue;
				}

				$MyArray[] = (object) array(
					'LedgerNo' 				=> (int) $row->LedgerNo,
					'NoUrut' 				=> (int) $row->NoUrut,
					'KdRAP' 				=> CekNULL_KhususString_Terbaru($row->KdRAP),
					'Uraian' 				=> CekNULL_KhususString_Terbaru($row->Uraian),
					'Uom' 					=> CekNULL_KhususString_Terbaru($row->Uom),
					'VolBrgMasuk' 			=> $VolBrgMasuk,
					'HrgSatuan' 			=> $HrgSatuan,
					'Total' 				=> $Total,
					'VolKO' 				=> $VolKO,
					'VolBrgMasuk_Detail'	=> $VolBrgMasuk_Detail,
					'IsChecked'				=> $IsChecked,
					'dari'					=> 'SisaInvoice',
					'VolBrgMasuk_Invoice' 	=> $VolBrgMasuk_Invoice,
					'Vol_SisaInvoice' 	=> $Vol_SisaInvoice,
				);
			endforeach;
		}


		$data['ListSisaInvoice'] = $MyArray;
		$this->load->view('Invoice_View/table_SisaInvoice',$data);

	}

	function ListSisaInvoice(){
		if (!$this->input->post()) {
			die;
		}

		$NoKO = $this->input->post('NoKO');
		$JobNo = $this->input->post('JobNo');

		// print_rr($_POST);

		$query = $this->db->query("WITH TmpQuery AS (SELECT A.NoSJ, A.NoUrut, ISNULL(A.VolBrgMasuk,0) AS VolBrgMasuk_Detail, ISNULL(B.VolBrgMasuk,0) AS VolBrgMasuk_Invoice
			FROM tblBarangMasuk_Detail A 
			LEFT JOIN Invoice_SJ B ON B.NoKO=A.NoKO AND B.NoSJ=A.NoSJ AND B.NoUrut=A.NoUrut AND B.KdRAP=A.KdRAP AND B.InvNo=A.InvNo
			WHERE A.NoKO='$NoKO' AND A.JobNo='$JobNo')
		SELECT DISTINCT(NoSJ) AS NoSJ FROM  TmpQuery WHERE VolBrgMasuk_Detail=VolBrgMasuk_Invoice");

		$html = "<option value=''>:: Pilih Salah Satu</option>";
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row):
				$html .='<option value="'.$row->NoSJ.'">'.$row->NoSJ.'</option>';
			endforeach;
		}

		echo $html;
		
	}

	function NoSJ_Berganti(){
		$JobNo 			= $this->input->post('JobNo');
		$NoKO 			= $this->input->post('NoKO');
		$Aksi 			= $this->input->post('Aksi');
		$NoSJ 			= $this->input->post('NoSJ');

		$NoSJ_Lama 			= ($this->input->post('NoSJ_Lama')) ? $this->input->post('NoSJ_Lama') : NULL ;
		$InvNo_Lama 			= ($this->input->post('InvNo_Lama')) ? $this->input->post('InvNo_Lama') : NULL ;
		

		$query = $this->db->query("SELECT *, ISNULL(VolBrgMasuk,0) * ISNULL(HrgSatuan,0) AS Total FROM tblBarangMasuk_Detail WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND NoSJ='$NoSJ' AND ApprovedBy IS NOT NULL ");

		// print_rr($_POST);
		// die;

		// PenghindarXss($query);

		$myarray = array();
		$sumTotal = 0;
		$sumTotal_Centang = 0;

		if ($query->num_rows()>0) {
			foreach($query->result() as $row) :

				// if ($Aksi=='UPD') {
				// 	if (condition) {
				// 		// code...
				// 	}
				// }

				if ($Aksi=='UPD') {
					if ($row->InvNo!=$InvNo_Lama) {
						if ($row->InvNo!='' || $row->InvNo!=NULL) {
							continue;
						}
					}
				}else{
					if ($row->InvNo!='' || $row->InvNo!=NULL) {
						continue;
					}
				}
				
				

				$VolBrgMasuk 	= 0;
				$HrgSatuan 	 	= 0;
				$Total 			= 0;

				$VolBrgMasuk 	= CekNull_Angka_Dan_Bikin_Float($row->VolBrgMasuk);
				$HrgSatuan 		= CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan);
				$Total 			= CekNull_Angka_Dan_Bikin_Float($row->Total);
				$VolKO 			= CekNull_Angka_Dan_Bikin_Float($row->VolKO);

				$sumTotal += $Total;


				// pengecekan SJ di Invoice_SJ
				$IsChecked = 0;
				$query1 = $this->db->query("SELECT * FROM Invoice_SJ WHERE NoKO='$NoKO' AND NoSJ='$row->NoSJ' AND InvNo='$row->InvNo' AND NoUrut='$row->NoUrut' AND KdRAP='$row->KdRAP'  ");

				if ($query1->num_rows() > 0) {
					$IsChecked = 1;
					$sumTotal_Centang = $sumTotal_Centang + $Total;
				}


				$myarray[] = (object) array(
					'LedgerNo' 		=> (int) $row->LedgerNo,
					'NoUrut' 		=> (int) $row->NoUrut,
					'KdRAP' 		=> CekNULL_KhususString_Terbaru($row->KdRAP),
					'Uraian' 		=> CekNULL_KhususString_Terbaru($row->Uraian),
					'Uom' 			=> CekNULL_KhususString_Terbaru($row->Uom),
					'VolBrgMasuk' 	=> $VolBrgMasuk,
					'HrgSatuan' 	=> $HrgSatuan,
					'Total' 		=> $Total,
					'VolKO' 		=> $VolKO,
					'IsChecked'		=> $IsChecked,
				);

			endforeach;
		}



		// print_rr($myarray);
		// die;

		$data['JobNo'] 	= $JobNo;
		$data['NoKO'] 	= $NoKO;
		$data['NoSJ']	= $NoSJ;
		$data['List_SJ']= $myarray;
		$data['sumTotal'] = $sumTotal;
		$data['sumTotal_Centang'] = $sumTotal_Centang;
		$data['Aksi'] = $Aksi;

		$data['status_invoice'] = '';
		if ($Aksi=='UPD') {
			$PengecekanInvoice = $this->db->query("SELECT * FROM InvPD WHERE InvNo='$InvNo_Lama' ");
			if ($PengecekanInvoice->num_rows() > 0) {
				$data['status_invoice'] = 'disabled';
			}
		}
		

		// print_rr($data);
		$this->load->view('Invoice_View/tableOrder',$data);
	}

	function CekInvoiceSudahDiGunakanAtauBelum($JobNo,$NoKO,$InvNo){
		$myquery = $this->db->query("SELECT InvNo FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND InvNo='$InvNo' ");
		if ($myquery->num_rows() > 0) {
			return 'sudah';
		}else{
			return 'belum';
		}
	}

	function Cek_TotalInvoiceMelebihiTotalKO($JobNo,$NoKO,$Txt_Total,$Aksi='',$InvNo=''){

		$Txt_Invoice = "";
		if ($Aksi=='NEW') {
			$Txt_Invoice = "";
		}else{
			$Txt_Invoice = " AND InvNo!='".$InvNo."' ";
		}

		$query = $this->db->query("SELECT ISNULL(SUM(Total),0) AS Total, (SELECT SubTotal - DiscAmount + PPN AS TotalKO FROM KoHdr WHERE NoKO='$NoKO') AS TotalKO FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO' ".$Txt_Invoice." ");
		// echo "SELECT ISNULL(SUM(Total),0) AS Total, (SELECT SubTotal - DiscAmount + PPN AS TotalKO FROM KoHdr WHERE NoKO='$NoKO') AS TotalKO FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO' ".$Txt_Invoice."";
		if ($query->num_rows() > 0) {

			$row = $query->row();

			$Total = (float) $row->Total;
			$TotalKO = (float) $row->TotalKO;

			$Jumlah = $Total + $Txt_Total;

			if ($TotalKO!=NULL) {
				if ( ($Jumlah) > $TotalKO  ) {
					return 'lebih_besar';
				}
			}
		}
	}

	function AksiSimpanInvoice(){
		if (!$this->input->post()) {
			die;
		}


		$JobNo 		= CekNULL_KhususString_Terbaru($this->input->post('JobNo'));
		$NoKO 		= CekNULL_KhususString_Terbaru($this->input->post('NoKO_txt'));
		$InvNo 		= CekNULL_KhususString_Terbaru($this->input->post('InvNo'));
		$InvDate 	= CekNull_Untuk_Tanggal($this->input->post('InvDate'));
		$DueDate 	= CekNULL_KhususString_Terbaru($this->input->post('DueDate'));
		$FPNo 		= CekNULL_KhususString_Terbaru($this->input->post('FPNo'));
		$FPDate 	= CekNull_Untuk_Tanggal($this->input->post('FPDate'));
		$Total 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('Total')));
		$PPN 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('PPN')));
		$PPH 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('PPH')));
		$Keterangan = CekNULL_KhususString_Terbaru($this->input->post('Keterangan'));
		$NoSJ 		= CekNULL_KhususString_Terbaru($this->input->post('NoSJ'));
		$Aksi 		= $this->input->post('Aksi');

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		if ($DueDate<$InvDate) {
			echo 'Jatuh Tempo < Tgl Invoice.';
			die;
		}

		if ($FPNo!='' || $FPDate!='' ) {
			if ($PPN==0) {
				echo 'PPN belum di-isi';
				die;
			}
		}

		if ($Total==0) {
			echo 'Total belum diisi.';
			die;
		}

		// -- CEK APAKAH INVOICE SUDAH DI GUNAKAN.
		if($Aksi=='NEW'){
			if ($this->CekInvoiceSudahDiGunakanAtauBelum($JobNo,$NoKO,$InvNo)=='sudah') {
				echo 'Sudah ada no. invoice '.$InvNo.'.';
				die;
			}
		}

		// -- VALIDASI TOTAL KO DENGAN TOTAL INVOICE.
		if ($Aksi=='NEW') {
			if ($this->Cek_TotalInvoiceMelebihiTotalKO($JobNo,$NoKO,$Total,$Aksi)=='lebih_besar') {
				echo 'Total invoice melebihi total '.$NoKO;
				die;
			}
		}else{

			if ($this->Cek_TotalInvoiceMelebihiTotalKO($JobNo,$NoKO,$Total,$Aksi,$InvNo)=='lebih_besar') {
				echo 'Total invoice melebihi total '.$NoKO;
				die;
			}
		}



		// -- START AKSI KE DATABASE
		$this->db->trans_begin();

		$myarray_TblBarang = array();

		if ($NoSJ!='') :

			if (isset($_POST['emp_checkbox'])) {

				if (count($_POST['emp_checkbox']) > 0) :

					$TotalCentang 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('TotalCentang')));
					$TotalBarang 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('TotalBarang')));

					if ($TotalCentang > $TotalBarang) {
						echo 'Total Centang > Total Barang';
						die;
					}

					foreach($_POST['emp_checkbox'] as $key => $value) :
						$NoUrut_Multiple	= (int) $this->input->post('NoUrut_Multiple')[$key];
						$KdRAP_Multiple 	= $this->input->post('KdRAP_Multiple')[$key];
						$Uraian_Multiple 	= $this->input->post('Uraian_Multiple')[$key];
						$Uom_Multiple 		= $this->input->post('Uom_Multiple')[$key];
						$VolBrgMasuk_Multiple = CekDanHapusKoma($this->input->post('VolBrgMasuk_Multiple')[$key]);
						$HrgSatuan_Multiple =  CekDanHapusKoma($this->input->post('HrgSatuan_Multiple')[$key]);
						$VolInput = CekDanHapusKoma($this->input->post('VolInput')[$key]);

						$myarray_TblBarang = array(
							'NoKO' 			=> $NoKO,
							'NoSJ' 			=> $NoSJ,
							'InvNo' 		=> $InvNo,
							'JobNo' 		=> $JobNo,
							'NoUrut' 		=> $NoUrut_Multiple,
							'KdRAP' 		=> $KdRAP_Multiple,
							'Uraian' 		=> $Uraian_Multiple,
							'Uom' 			=> $Uom_Multiple,
							'VolBrgMasuk' 	=> $VolInput,
							'HrgSatuan'		=> $HrgSatuan_Multiple,
							'UserEntry' 	=> $UserEntry,
							'TimeEntry' 	=> $TimeEntry,
						);

						// $this->db->insert('Invoice_SJ',$myarray_TblBarang);

						// -- INSERT SETIAP YANG DI CENTANG KE TABLE INVOICE SJ
						$this->db->query("INSERT INTO Invoice_SJ (NoKO, NoSJ, InvNo, JobNo, NoUrut, KdRAP, Uraian, Uom, VolBrgMasuk, HrgSatuan, UserEntry, TimeEntry ) VALUES ('$NoKO', '$NoSJ', '$InvNo', '$JobNo', '$NoUrut_Multiple', '$KdRAP_Multiple', '$Uraian_Multiple', '$Uom_Multiple', '$VolInput', '$HrgSatuan_Multiple', '$UserEntry', '$TimeEntry') ");

						// -- LALU UPDATE TABLE tblBarangMasuk_Detail UNTUK MENGISI NO INV YANG DI INPUT
						$this->db->query("UPDATE tblBarangMasuk_Detail SET InvNo='$InvNo' WHERE NoSJ='$NoSJ' AND NoKO='$NoKO' And NoUrut='$NoUrut_Multiple' ");

					endforeach;

					

				endif;

			}else{
				echo 'belum centang list barang';
				die;
			}

		endif;

		// -- INSERT DATA KE INVOICE
		$data_invoice = array(
			'JobNo' => $JobNo, 'NoKO' => $NoKO, 'InvNo' => $InvNo, 'InvDate' => $InvDate, 'DueDate' => $DueDate, 'PPN' => $PPN,
			'FPNo' => $FPNo, 'FPDate' => $FPDate, 'Total' => $Total, 'UserEntry' => $UserEntry, 'TimeEntry' => $TimeEntry,
			'Keterangan' => $Keterangan, 'NoSJ' => $NoSJ, 'PPH' => $PPH
		);

		$this->db->insert('Invoice',$data_invoice);

		if ($this->db->trans_status()===FALSE) {
			$this->db->trans_rollback();
			echo 'gagal menyimpan data';
		}else{
			is_pesan('success','Success');
			echo 'berhasil';
			$this->db->trans_commit();
		}

	}

	// -- END PENYIMPANAN (INSERT) KE DATABASE

	// -- START UPDATE INVOICE
	function FormUpdateInvoice($LedgerNo=''){
		if ($LedgerNo=='') {
			redirect('Entry/Procurement/Invoice');
			die;
		}

		$JobNo 		= NULL;
		$NoKO 		= NULL;
		$InvNo 		= NULL;
		$InvDate 	= NULL;
		$DueDate 	= NULL;
		$PPN 		= NULL;
		$FPNo 		= NULL;
		$FPDate 	= NULL;
		$Total 		= NULL;
		$Keterangan = NULL;
		$NoSJ 		= NULL;
		$PPH 		= NULL;

		$query_Invoice = $this->db->query("SELECT * FROM Invoice WHERE LedgerNo='$LedgerNo' ");
		if ($query_Invoice->num_rows() > 0) {
			$row = $query_Invoice->row();
			$JobNo 		= $row->JobNo;
			$NoKO 		= $row->NoKO;
			$InvNo 		= ($row->InvNo=='' || $row->InvNo==NULL) ? NULL : $row->InvNo;
			$InvDate 	= ($row->InvDate=='' || $row->InvDate==NULL || $row->InvDate=='1900-01-01' ) ? NULL : $row->InvDate;
			$DueDate 	= ($row->DueDate=='' || $row->DueDate==NULL || $row->DueDate=='1900-01-01' ) ? NULL : $row->DueDate;
			$PPN 		= ($row->PPN=='' || $row->PPN==NULL) ? 0 : $row->PPN;
			$FPNo 		= ($row->FPNo=='' || $row->FPNo==NULL) ? NULL : $row->FPNo;
			$FPDate 	= ($row->FPDate=='' || $row->FPDate==NULL || $row->FPDate=='1900-01-01' ) ? NULL : $row->FPDate;
			$Total 		= ($row->Total=='' || $row->Total==NULL) ? 0 : $row->Total;
			$Keterangan = ($row->Keterangan=='' || $row->Keterangan==NULL) ? NULL : $row->Keterangan;
			$NoSJ 		= ($row->NoSJ=='' || $row->NoSJ==NULL) ? NULL : $row->NoSJ;
			$PPH 		= ($row->PPH=='' || $row->PPH==NULL) ? 0 : $row->PPH;

		}


		$data['LedgerNo'] = $LedgerNo;
		$data['JobNo'] 	= $JobNo;
		$data['JobNm'] 	= ($JobNo!='' || $JobNo!=NULL) ? $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm : '';
		$data['NoKO'] 	= $NoKO;
		$data['InvNo'] 	= $InvNo;
		$data['InvDate'] = $InvDate;
		$data['DueDate'] = $DueDate;
		$data['PPN'] 	= number_format($PPN);
		$data['FPNo'] 	= $FPNo;
		$data['FPDate'] = $FPDate;
		$data['Total'] 	= number_format($Total);
		$data['Keterangan'] = $Keterangan;
		$data['NoSJ'] 	= $NoSJ;
		$data['PPH'] 	= number_format($PPH);
		$data['Aksi']	= 'UPD';
		$data['ListNoKO'] 		= $this->get_ListNoKO($JobNo);


		$data['judul'] 			= 'Form Update Invoice';
		$data['title'] 			= 'Form Update Invoice';
		$data['bodyclass'] 		= 'sidebar-collapse skin-blue-light';

		$data['js_saya'] 		= 'Invoice_View/js_FormUpdateInvoice';

		$data['status_invoice'] = '';
		$PengecekanInvoice = $this->db->query("SELECT * FROM InvPD WHERE InvNo='$InvNo' ");
		if ($PengecekanInvoice->num_rows() > 0) {
			$data['status_invoice'] = 'disabled';
		}

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Invoice_View/FormUpdateInvoice', $data);
		$this->load->view('templates/footer');

	}

	function AksiUpdateInvoice(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);

		$JobNo 		= CekNULL_KhususString_Terbaru($this->input->post('JobNo'));
		$NoKO 		= CekNULL_KhususString_Terbaru($this->input->post('NoKO_txt'));
		$InvNo 		= CekNULL_KhususString_Terbaru($this->input->post('InvNo'));
		$InvDate 	= CekNull_Untuk_Tanggal($this->input->post('InvDate'));
		$DueDate 	= CekNULL_KhususString_Terbaru($this->input->post('DueDate'));
		$FPNo 		= CekNULL_KhususString_Terbaru($this->input->post('FPNo'));
		$FPDate 	= CekNull_Untuk_Tanggal($this->input->post('FPDate'));
		$Total 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('Total')));
		$PPN 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('PPN')));
		$PPH 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('PPH')));
		$Keterangan = CekNULL_KhususString_Terbaru($this->input->post('Keterangan'));
		$NoSJ 		= CekNULL_KhususString_Terbaru($this->input->post('NoSJ'));
		$Aksi 		= $this->input->post('Aksi');

		

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		if ($DueDate<$InvDate) {
			echo 'Jatuh Tempo < Tgl Invoice.';
			die;
		}

		if ($FPNo!='' || $FPDate!='' ) {
			if ($PPN==0) {
				echo 'PPN belum di-isi';
				die;
			}
		}

		if ($Total==0) {
			echo 'Total belum diisi.';
			die;
		}

		// -- CEK APAKAH INVOICE SUDAH DI GUNAKAN.
		if($Aksi=='NEW'){
			if ($this->CekInvoiceSudahDiGunakanAtauBelum($JobNo,$NoKO,$InvNo)=='sudah') {
				echo 'Sudah ada no. invoice '.$InvNo.'.';
				die;
			}
		}

		// -- VALIDASI TOTAL KO DENGAN TOTAL INVOICE.
		if ($Aksi=='NEW') {
			if ($this->Cek_TotalInvoiceMelebihiTotalKO($JobNo,$NoKO,$Total,$Aksi)=='lebih_besar') {
				echo 'Total invoice melebihi total '.$NoKO;
				die;
			}
		}else{

			if ($this->Cek_TotalInvoiceMelebihiTotalKO($JobNo,$NoKO,$Total,$Aksi,$InvNo)=='lebih_besar') {
				echo 'Total invoice melebihi total '.$NoKO;
				die;
			}
		}

		// -- START AKSI UPDATE KE DATABASE
		$this->db->trans_begin();

		$myarray_TblBarang = array();

		if ($Aksi=='UPD') {

			$NoSJ_Lama 		= ($this->input->post('NoSJ_Lama')) ? $this->input->post('NoSJ_Lama') : NULL;
			
			// -- HAPUS TERLEBIH DAHULU DATA DI TABLE Invoice_SJ
			$this->db->query("DELETE Invoice_SJ WHERE InvNo='$InvNo'  ");

			// -- BUAT Field InvNo Di tblBarangMasuk_Detail Jadi NULL
			$this->db->query("UPDATE tblBarangMasuk_Detail SET InvNo=NULL WHERE InvNo='$InvNo' ");

			if ($NoSJ!='') :

				if (isset($_POST['emp_checkbox'])) {

					if (count($_POST['emp_checkbox']) > 0) :

						$TotalCentang 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('TotalCentang')));
						$TotalBarang 		= CekNull_Angka_Dan_Bikin_Float(CekDanHapusKoma($this->input->post('TotalBarang')));

						if ($TotalCentang > $TotalBarang) {
							echo 'Total Centang > Total Barang';
							die;
						}

						foreach($_POST['emp_checkbox'] as $key => $value) :
							$NoUrut_Multiple	= (int) $this->input->post('NoUrut_Multiple')[$key];
							$KdRAP_Multiple 	= $this->input->post('KdRAP_Multiple')[$key];
							$Uraian_Multiple 	= $this->input->post('Uraian_Multiple')[$key];
							$Uom_Multiple 		= $this->input->post('Uom_Multiple')[$key];
							$VolBrgMasuk_Multiple = CekDanHapusKoma($this->input->post('VolBrgMasuk_Multiple')[$key]);
							$HrgSatuan_Multiple =  CekDanHapusKoma($this->input->post('HrgSatuan_Multiple')[$key]);
							$VolInput = CekDanHapusKoma($this->input->post('VolInput')[$key]);

							$myarray_TblBarang = array(
								'NoKO' 			=> $NoKO,
								'NoSJ' 			=> $NoSJ,
								'InvNo' 		=> $InvNo,
								'JobNo' 		=> $JobNo,
								'NoUrut' 		=> $NoUrut_Multiple,
								'KdRAP' 		=> $KdRAP_Multiple,
								'Uraian' 		=> $Uraian_Multiple,
								'Uom' 			=> $Uom_Multiple,
								'VolBrgMasuk' 	=> $VolInput,
								'HrgSatuan'		=> $HrgSatuan_Multiple,
								'UserEntry' 	=> $UserEntry,
								'TimeEntry' 	=> $TimeEntry,
							);

							// -- INSERT SETIAP YANG DI CENTANG KE TABLE INVOICE SJ
							$this->db->query("INSERT INTO Invoice_SJ (NoKO, NoSJ, InvNo, JobNo, NoUrut, KdRAP, Uraian, Uom, VolBrgMasuk, HrgSatuan, UserEntry, TimeEntry ) VALUES ('$NoKO', '$NoSJ', '$InvNo', '$JobNo', '$NoUrut_Multiple', '$KdRAP_Multiple', '$Uraian_Multiple', '$Uom_Multiple', '$VolInput', '$HrgSatuan_Multiple', '$UserEntry', '$TimeEntry') ");

							// -- LALU UPDATE TABLE tblBarangMasuk_Detail UNTUK MENGISI NO INV YANG DI INPUT
							$this->db->query("UPDATE tblBarangMasuk_Detail SET InvNo='$InvNo' WHERE NoSJ='$NoSJ' AND NoKO='$NoKO' And NoUrut='$NoUrut_Multiple' ");

						endforeach;

					endif;

				}else{
					echo 'belum centang list barang';
					die;
				}

			endif;

			// -- LAKUKAN UPDATE TABLE 'Invoice'
			$this->db->query("UPDATE Invoice SET DueDate='$DueDate', PPN='$PPN', FPNo='$FPNo', FPDate='$FPDate', Total='$Total', UserEntry='$UserEntry', TimeEntry='$TimeEntry', Keterangan='$Keterangan', NoSJ='$NoSJ', PPH='$PPH' WHERE InvNo='$InvNo'  ");

			
		}


		if ($this->db->trans_status()===FALSE) {
			$this->db->trans_rollback();
			echo 'gagal meng-update data';
		}else{
			is_pesan('success','Update Sukses');
			echo 'berhasil';
			$this->db->trans_commit();
		}


	}

	// -- END UPDATE INVOICE

	function CekStatusInvoice(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo 	= $this->input->post('JobNo');
		$NoKO 	= $this->input->post('NoKO');
		$InvNo 	= $this->input->post('InvNo');

		$cek = $this->db->query("SELECT NoPD FROM InvPD WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND InvNo='$InvNo' ");
		if ($cek->num_rows() > 0) {
			$row = $cek->row_array();
			if ($row['NoPD']!='' || $row['NoPD']!=NULL) {
				echo 'invoice tidak bisa di delete, sudah digunakan untuk '.$row['NoPD'];
				die;
			}
		}

		echo 'lanjut';
	}

	function HapusInvoice(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo 	= $this->input->post('JobNo');
		$NoKO 	= $this->input->post('NoKO');
		$InvNo 	= $this->input->post('InvNo');
		$NoSJ 	= $this->input->post('NoSJ');
		$LedgerNo 	= $this->input->post('LedgerNo');


		$this->db->trans_begin();
		try {

			$this->db->query("DELETE FROM Invoice WHERE LedgerNo='$LedgerNo'");

			if ($NoSJ!='') {
				$this->db->query("UPDATE tblBarangMasuk_Detail SET InvNo=NULL WHERE NoKO='$NoKO' AND NoSJ='$NoSJ' ");
			}

			$this->db->query("DELETE Invoice_SJ WHERE InvNo='$InvNo' ");

			$this->db->trans_commit();
			echo 'berhasil';
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo 'gagal menyimpan data'; 
		}


	}

	function cek_apa_sudah_ada_InvPD($JobNo='',$NoKO='',$InvNo=''){
		$query = $this->db->query("SELECT * FROM InvPD WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND InvNo='$InvNo' ");
		if ($query->num_rows() > 0 ) {
			return true;
		}else{
			return false;
		}

	}


	private function GetJob(){
		$AksesJob = $this->session->userdata('PecahToken')->AksesJob;
		$QueryJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE StatusJob<>'Closed' AND Company='$this->Company' ");
		$ArrayJob = array();
		foreach($QueryJob->result_array() as $myjob ):

			if (!preg_match('/\b' . $myjob['JobNo'] . '\b/', $AksesJob)) {
				continue;
			}
			$ArrayJob[] = (object) array('JobNo' => $myjob['JobNo'], 'JobNm' => $myjob['JobNm']);
		endforeach;

		return $ArrayJob;
	}



}

/* End of file Invoice.php */
/* Location: ./application/controllers/Entry/Procurement/Invoice.php */
