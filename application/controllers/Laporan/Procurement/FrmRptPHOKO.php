<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmRptPHOKO extends CI_Controller {

	var $Company;
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

		$this->load->model('Model_FrmRptPHOKO');
		$this->Company = $this->config->item('Company');
	}

	public function index(){

		$data['bodyclass'] = 'sidebar-collapse skin-purple-light';
		$data['judul'] = 'Rekap Hutang KO (PHO)';

		$DariTgl = date('Y-m-01');
		$SampaiTgl = date('Y-m-t');

		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;
		$data['ListJob'] = $this->Model_FrmRptPHOKO->getJobNo();

		// print_rr($data);
		// exit;

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar');
		$this->load->view('laporan_view/procurement/FrmRptPHOKO_View/halaman_utama_PHO',$data);
		$this->load->view('templates/footer');
	}

	function table_HutangKOPHO(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$DariTgl = $this->input->post('DariTgl',TRUE);
		$SampaiTgl = $this->input->post('SampaiTgl',TRUE);

		// print_rr($_POST);
		// die;

		$data['JobNo'] = $JobNo;
		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;
		$this->load->view('laporan_view/procurement/FrmRptPHOKO_View/table_HutangKOPHO',$data);
	}

	function table_HutangKOPHO_old(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$DariTgl = $this->input->post('DariTgl',TRUE);
		$SampaiTgl = $this->input->post('SampaiTgl',TRUE);

		// print_rr($_POST);
		// die;

		// $QueryPanggilJob = $this->db->query("SELECT *,  
		// 	CASE
		// 	WHEN PersenKSO=0 THEN 100
		// 	ELSE PersenKSO
		// 	END AS 'KSOPersen'
		// 	FROM Job WHERE JobNo IN ('1819','1855','20005','20031','21006','21017','21570','21640','22015','22091','23002') ORDER BY JobNo DESC")->result();

		$QueryPanggilJob = $this->db->query("SELECT *,  
			CASE
			WHEN PersenKSO=0 THEN 100
			ELSE PersenKSO
			END AS 'KSOPersen'
			FROM Job WHERE JobNo IN ('$JobNo') ORDER BY JobNo DESC")->result();

		$KO1 	=0;
		$KO2 	=0;
		$BLE1 	=0;
		$BLE2 	=0;
		$Invoice1 =0;
		$Invoice2 =0;
		$TtlInv=0;
		$RAP=0;
		$NettKontrak =0;

		$TglPHO = '1900-01-01';


		$MyArray_KO = array();

		foreach($QueryPanggilJob as $dataJob) :

			$TglPHO = $dataJob->TglPHO;
			if ($TglPHO <= date('Y-m-d') ) {
				// echo "PHO / PEMELIHARAAN ";
			}else{
				// echo "PHO RENCANA TAHUN ".date('Y',strtotime($TglPHO));
			}

			$MyQuery_KO = $this->db->query("SELECT KoHdr.*, Vendor.VendorNm FROM KoHdr, Vendor WHERE JobNo='$dataJob->JobNo'AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND KoHdr.VendorId=Vendor.VendorId")->result_array();


			// ===== SEPUTAR KO ======
			foreach($MyQuery_KO as $dataKO){
				
				$NoKO 	= $dataKO['NoKO'];

				// ===== TOTAL KO =====
				if ($dataKO['ClosedBy'] == NULL || $dataKO['ClosedBy'] == '') {
					$KO1 += ($dataKO['SubTotal'] + $dataKO['PPN']) - $dataKO['DiscAmount'];
				}else{
					$CekBLE = $this->db->query("SELECT ISNULL(SUM(BLE.Amount),0) AS 'SumBLE' FROM BLE, KoHdr WHERE BLE.NoKO=KoHdr.NoKO AND KoHdr.NoKO='$NoKO' ");

					if ($CekBLE->num_rows() > 0) {
						$row_BLE = $CekBLE->row_array();
						$KO1 += $row_BLE['SumBLE'];
					}
				}
				// ===== END TOTAL KO =====


				// ======= TOTAL KO dlm periode ini ======
				if ($dataKO['TglKO'] >= $DariTgl AND $dataKO['TglKO'] <= $SampaiTgl ) :

					if ($dataKO['ClosedBy'] == NULL || $dataKO['ClosedBy'] == '') {
						$KO2 += ($dataKO['SubTotal'] + $dataKO['PPN']) - $dataKO['DiscAmount'];
					}else{
						$CekBLE = $this->db->query("SELECT ISNULL(SUM(BLE.Amount),0) FROM BLE, KoHdr WHERE BLE.NoKO=KoHdr.NoKO AND KoHdr.NoKO='$dataKO[NoKO]' ");

						if ($CekBLE->num_rows() > 0) {
							$row_BLE = $CekBLE->row_array();
							$KO2 += $row_BLE['SumBLE'];
						}
					}

					
				endif;
				// ======= END TOTAL KO dlm periode ini ======


				// ==== BLE 1 ==== 

				// ==== END BLE 2 =====
				
				
			}

			// ===== END SEPUTAR KO ======


			// ===== SEPUTAR BLE (Pembayaran) ======
			$BLE_Query = $this->db->query("SELECT BLE.Amount, BLE.TglBayar FROM BLE, KoHdr WHERE BLE.JobNo='$dataJob->JobNo' AND BLE.NoKO=KoHdr.NoKO AND KoHdr.ApprovedBy IS NOT NULL And KoHdr.CanceledBy IS NULL")->result_array();

			foreach($BLE_Query as $BLEData) :
				// == Total pembayaran KO ===
				$BLE1 += $BLEData['Amount'];

				// ==== Total pembayaran KO dlm periode ====
				if ($BLEData['TglBayar'] >='$DariTgl' AND $BLEData['TglBayar'] <='$SampaiTgl' ) {
					$BLE2 += $BLEData['Amount'];
				}
			endforeach;
			// ===== END SEPUTAR BLE (Pembayaran) ======

			// ===== SEPUTAR Invoice ======
			$QueryInvoice = $this->db->query("
				SELECT ISNULL(SUM(Invoice.Total),0) AS 'Invoice1' FROM Invoice, KoHdr 
				WHERE Invoice.JobNo='$dataJob->JobNo' AND Invoice.NoKO=KoHdr.NoKO AND KoHdr.ApprovedBy IS NOT NULL AND KoHdr.CanceledBy IS NULL 
				AND KoHdr.ClosedBy IS NULL AND Invoice.InvDate>='$DariTgl' AND Invoice.InvDate<='$SampaiTgl' ");
			if ($QueryInvoice->num_rows() > 0 ) {
				$row = $QueryInvoice->row_array();
				$Invoice1 = $row['Invoice1'];
			}

			$QueryTtlInv = $this->db->query("SELECT ISNULL(SUM(Invoice.Total),0) AS 'TtlInv' FROM Invoice, KoHdr
				WHERE Invoice.JobNo='$dataJob->JobNo'
				AND Invoice.NoKO=KoHdr.NoKO AND KoHdr.ApprovedBy IS Not NULL AND KoHdr.CanceledBy IS NULL
				AND KoHdr.ClosedBy IS NULL");
			if ($QueryTtlInv->num_rows() > 0 ) {
				$row = $QueryTtlInv->row_array();
				$TtlInv = $row['TtlInv'];
			}


			$QueryInvoice2 = $this->db->query("SELECT ISNULL(SUM(InvPD.PaymentAmount),0)  AS 'Invoice2' FROM BLE, InvPD, KoHdr 
				WHERE InvPD.JobNo='$dataJob->JobNo' And BLE.NoPD=InvPD.NoPD 
				And InvPD.NoKO=KoHdr.NoKO And KoHdr.ClosedBy Is NULL And KoHdr.CanceledBy Is NULL 
				And KoHdr.ApprovedBy Is Not NULL");
			if ($QueryInvoice2->num_rows() > 0 ) {
				$row = $QueryInvoice2->row_array();
				$Invoice2 = $TtlInv - $row['Invoice2'];
			}
			// ===== END SEPUTAR Invoice  ======


			// ======= SEPUTAR RAP ========
			$QueryRAP = $this->db->query("SELECT ISNULL(SUM(Vol*HrgSatuan),0) AS 'RAP' FROM RAP WHERE JobNo='$dataJob->JobNo' And Alokasi='B' AND Tipe='Detail'");
			if ($QueryRAP->num_rows() > 0 ) {
				$row = $QueryRAP->row_array();
				$RAP = $row['RAP'];
			}
			// ======= END SEPUTAR RAP ========

			$NettKontrak = (($dataJob->Netto * $dataJob->KSOPersen ) / 100);
			
			// echo "<pre>";
			// echo $dataJob->JobNo.' - '.$dataJob->JobNm.' - Total KO : '.$KO1.' - Total KO dlm periode ini : '.$KO2.' - Total pembayaran KO : '.$BLE1.' - Total pembayaran KO dlm periode : '.$BLE2.' - Invoice1 : '.$Invoice1.' TtlInv :'.$TtlInv.' - Invoice2 : '.$Invoice2.' - RAP : '.$RAP.' - NettKontrak : '.$NettKontrak ;
			// echo "</pre>";

			
			$MyArray_KO[] = array(
				'JobNo' => $dataJob->JobNo,
				'JobNm' => $dataJob->JobNm,
				'KO1' => $KO1,
				'KO2' => $KO2,
				'BLE1' => ($BLE1  / 1000),
				'BLE2' => $BLE2 / 1000,
				'Invoice1' => $Invoice1  / 1000,
				'TtlInv' => $TtlInv,
				'Invoice2' => $Invoice2,
				'RAP' => ($RAP / 1000),
				'NettKontrak' => $NettKontrak,
				'TglPHO' => $dataJob->TglPHO
			); 

		endforeach;

		// exit;
		// $data['mydata'] = $MyArray_KO/;
		$data['JobNo'] = $JobNo;
		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;

		// $data = array(
		// 	'JobNo' => $JobNo,
		// 	'DariTgl' => $DariTgl,
		// 	'SampaiTgl' => $SampaiTgl
		// );

		// print_rr($MyArray_KO);
		// exit;

		$this->load->view('laporan_view/procurement/FrmRptPHOKO_View/table_HutangKOPHO',$data);
	}

	function index_cadangan(){


		if ($this->session->userdata('MIS_LOGGED_ID') == '') {
			exit;
		}

		$UserID = $this->session->userdata('MIS_LOGGED_ID');
		$Company = $this->config->item('Company');

		$AksesJob = $this->db->query("SELECT AksesJob From Login WHERE UserID='liasw'  ")->result();
		$PanggilJob = $this->db->query("SELECT JobNo FROM Job WHERE Kategori='Primary' AND TipeJob='Project' AND (StatusJob='Pelaksanaan' OR StatusJob='Pemeliharaan') AND Company='$Company'")->result();

		$AksesJob_Array = array();
		foreach($AksesJob as $AksesJob_data ){
			$AksesJob_Array[] = $AksesJob_data->AksesJob;
		}

		$JobArray = array();
		foreach($PanggilJob as $PanggilJob_Data ){
			$JobArray[]= $PanggilJob_Data->JobNo;
		}


		$result=array_diff($AksesJob_Array,$JobArray);

		$array_buatan = array();
		foreach($result as $JobNoData){
			$JobNoData = explode(',',$JobNoData);
			$JobNm = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNoData' ")->row()->JobNm;
			$array_buatan['JobNo'] = $JobNoData;
			$array_buatan['JobNm'] = $JobNm->JobNm;
		}


		// $array_return = array();
		// foreach ($result as $key => $value) {
		// 	echo $key['JobNo'];
		// }

		// return $result;
		print_rr($array_buatan);
		// echo '<hr>';
		// print_rr($PanggilJob);
	}

	

}

/* End of file FrmRptPHOKO.php */
/* Location: ./application/controllers/Laporan/Procurement/FrmRptPHOKO.php */