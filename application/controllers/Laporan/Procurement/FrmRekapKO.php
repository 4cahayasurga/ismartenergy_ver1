<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmRekapKO extends CI_Controller {

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

		$this->load->model('Model_FrmRekapKO');
		$this->Company = $this->config->item('Company');
	}

	public function index()
	{
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';
		$data['judul'] = 'Rekap KO';

		$DariTgl = date('Y-m-01');
		$SampaiTgl = date('Y-m-t');

		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;
		$data['ListJob'] = $this->Model_FrmRekapKO->getJobNo();

		// print_rr($data);
		// exit;

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar');
		$this->load->view('laporan_view/procurement/RekapKO/RekapKO_halaman_utama',$data);
		$this->load->view('templates/footer');
	}

	function get_table_RekapKO(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);

		$JobNo = $this->input->post('JobNo',TRUE);
		$DariTgl = $this->input->post('DariTgl',TRUE);
		$SampaiTgl = $this->input->post('SampaiTgl',TRUE);
		$AllPeriode = ($this->input->post('AllPeriode')) ? 1 : 0;

		if ($AllPeriode == 1) {
			$DariTgl = '1900-01-01';
			$SampaiTgl = '2999-01-01';
		}

		$data['JobNo'] = $JobNo;
		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;
		$data['AllPeriode'] = $AllPeriode;
		// print_rr($data);
		// exit;




		$QueryAwal = $this->db->query("SELECT A.*, B.VendorNm FROM KoHdr A JOIN Vendor B ON A.VendorId = B.VendorId WHERE A.JobNo='$JobNo' AND TglKO>='$DariTgl' AND TglKO<='$SampaiTgl' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL ORDER BY NoKO");

		$NoKO = '';

		$TotalKO = 0;
		$TotalKOH = 0;
		$TotalTerbayar = 0;
		$NoUrut = 1;
		$SisaPembayaran = 0;

		$arrayKO = array();

		$CekKontrak = 0;

		$SUM_TotalKO = 0;
		$SUM_TotalKOH = 0;
		$SUM_TotalTerbayar = 0;
		$SUM_SisaPembayaran = 0;

		foreach($QueryAwal->result() as $dataAwal ){
			$NoKO = $dataAwal->NoKO;
			$TotalKO = ($dataAwal->SubTotal - $dataAwal->DiscAmount) + $dataAwal->PPN;


			// === Start Pembayaran / BLE ===
			$QueryPembayaran = $this->db->query("SELECT * FROM BLE WHERE NoKO='$NoKO' AND TglBayar>='$DariTgl' AND TglBayar<='$SampaiTgl' ORDER BY NoKO");
			$CekPembayaran = $QueryPembayaran->num_rows();
			$arrayTerbayar = array();
			$NoUrutTerbayar = 1;
			if($CekPembayaran == 0){
				$TotalTerbayar = 0;
			}else{
				foreach($QueryPembayaran->result_array() AS $rowPembayaran ){
					$TotalTerbayar += $rowPembayaran['Amount'];
					$arrayTerbayar[] = array(
						'NoUrutTerbayar' => $NoUrutTerbayar++,
						'NoPD' => $rowPembayaran['NoPD'],
						'TglBayar' => $rowPembayaran['TglBayar'],
						'Amount' => $rowPembayaran['Amount'],
						'Keterangan' => $rowPembayaran['Keterangan'],
					);
				}
			}
			// === End Pembayaran / BLE ===


			// === Start Pembayaran / BLE ===
			$QueryKontrak = $this->db->query("
				SELECT NoKO, TglKO, SubTotal-DiscAmount+PPN AS 'TtlKO' FROM KoHdr WHERE PotongKO='$NoKO' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ");
			$CekKontrak = $QueryKontrak->num_rows();
			$arrayKontrak = array();
			$NoUrutKontrak = 1;
			foreach($QueryKontrak->result_array() AS $rowKontrak ){
				$TotalKOH += $rowKontrak['TtlKO'];
				$arrayKontrak[] = array(
					'NoUrutKontrak' => $NoUrutKontrak++,
					'NoKO' => $rowKontrak['NoKO'],
					'TglKO' => $rowKontrak['TglKO'],
					'TtlKO' => $rowKontrak['TtlKO'],
				);

			}
			// === End Pembayaran / BLE ===



			// if ($dataAwal->KategoriId == 'KONTRAK') {
			// 	$QueryKontrak = $this->db->query("SELECT NoKO, TglKO, SubTotal-DiscAmount+PPN AS 'TtlKO' FROM KoHdr WHERE PotongKO='KO21570014' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ");
			// 	$CekKontrak = $QueryKontrak->num_rows();
			// }else{
			// 	$CekKontrak = 0;
			// }



			// $QueryPembayaran = $this->db->query("SELECT * FROM BLE WHERE NoKO='$NoKO' AND TglBayar>='$DariTgl' AND TglBayar<='$SampaiTgl' ORDER BY NoKO");
			// $Jumlah = $QueryPembayaran->num_rows();
			// foreach($QueryPembayaran->result_array() AS $dataBayar ){
			// 	echo 'Amount : '.$dataBayar['NoKO'].'<br>';
			// }

			$SisaPembayaran = $TotalKO - $TotalTerbayar;

			$arrayKO[] = (object) array(
				'NoUrut' => $NoUrut++,
				'NoKO' => $NoKO,
				'TglKO' => $dataAwal->TglKO,
				'VendorNm' => $dataAwal->VendorNm,
				'KategoriId' => $dataAwal->KategoriId,
				'TotalKO' => $TotalKO,
				'BLE' => $CekPembayaran,
				'TotalTerbayar' => $TotalTerbayar,
				'DataTerbayar' => $arrayTerbayar,
				'SisaPembayaran' => $SisaPembayaran,
				'ClosedBy' => ($dataAwal->ClosedBy == '' || $dataAwal->ClosedBy == NULL ) ? '' : $dataAwal->ClosedBy,
				'CekKontrak' => $CekKontrak,
				'TotalKOH' => $TotalKOH,
				'DataKontrak' => $arrayKontrak,

			);

			$SUM_TotalKO += $TotalKO;
			$SUM_TotalKOH += $TotalKOH;
			$SUM_TotalTerbayar += $TotalTerbayar;
			$SUM_SisaPembayaran += $SisaPembayaran;

			// $TotalKO = 0;
			$TotalTerbayar = 0;
			// $TotalKOH = 0;

			// echo 'No KO : '.$TotalKO.'<br>';

			
		}
		// $SUM_TotalKO = 
		$data['arrayKO'] = $arrayKO;
		$data['SUM_TotalKO'] = $SUM_TotalKO;
		$data['SUM_TotalKOH'] = $SUM_TotalKOH;
		$data['SUM_TotalTerbayar'] = $SUM_TotalTerbayar;
		$data['SUM_SisaPembayaran'] = $SUM_SisaPembayaran;
		// die;

		// print_rr($data);
		// foreach($arrayKO as $apa){
		// 	echo 'apa : '.$apa['NoKO'].'<br>';
		// }
		// die;

		$this->load->view('laporan_view/procurement/RekapKO/RekapKO_table',$data);
	}

	function RekapKO_ExportPDF($JobNo='',$DariTgl='',$SampaiTgl='',$AllPeriode=''){
		if ($JobNo == '') {
			redirect('Laporan/Procurement/FrmRekapKO','refresh');
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        $JobNm = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;


		if ($AllPeriode == 1) {
			$DariTgl = '1900-01-01';
			$SampaiTgl = '2999-01-01';
		}

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $JobNm;
		$data['DariTgl'] = $DariTgl;
		$data['SampaiTgl'] = $SampaiTgl;
		$data['AllPeriode'] = $AllPeriode;

		$QueryAwal = $this->db->query("SELECT A.*, B.VendorNm FROM KoHdr A JOIN Vendor B ON A.VendorId = B.VendorId WHERE A.JobNo='$JobNo' AND TglKO>='$DariTgl' AND TglKO<='$SampaiTgl' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL ORDER BY NoKO");

		$NoKO = '';

		$TotalKO = 0;
		$TotalKOH = 0;
		$TotalTerbayar = 0;
		$NoUrut = 1;
		$SisaPembayaran = 0;

		$arrayKO = array();

		$CekKontrak = 0;

		$SUM_TotalKO = 0;
		$SUM_TotalKOH = 0;
		$SUM_TotalTerbayar = 0;
		$SUM_SisaPembayaran = 0;

		foreach($QueryAwal->result() as $dataAwal ){
			$NoKO = $dataAwal->NoKO;
			$TotalKO = ($dataAwal->SubTotal - $dataAwal->DiscAmount) + $dataAwal->PPN;


			// === Start Pembayaran / BLE ===
			$QueryPembayaran = $this->db->query("SELECT * FROM BLE WHERE NoKO='$NoKO' AND TglBayar>='$DariTgl' AND TglBayar<='$SampaiTgl' ORDER BY NoKO");
			$CekPembayaran = $QueryPembayaran->num_rows();
			$arrayTerbayar = array();
			$NoUrutTerbayar = 1;
			if($CekPembayaran == 0){
				$TotalTerbayar = 0;
			}else{
				foreach($QueryPembayaran->result_array() AS $rowPembayaran ){
					$TotalTerbayar += $rowPembayaran['Amount'];
					$arrayTerbayar[] = array(
						'NoUrutTerbayar' => $NoUrutTerbayar++,
						'NoPD' => $rowPembayaran['NoPD'],
						'TglBayar' => $rowPembayaran['TglBayar'],
						'Amount' => $rowPembayaran['Amount'],
						'Keterangan' => $rowPembayaran['Keterangan'],
					);
				}
			}
			// === End Pembayaran / BLE ===


			// === Start Pembayaran / BLE ===
			$QueryKontrak = $this->db->query("
				SELECT NoKO, TglKO, SubTotal-DiscAmount+PPN AS 'TtlKO' FROM KoHdr WHERE PotongKO='$NoKO' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ");
			$CekKontrak = $QueryKontrak->num_rows();
			$arrayKontrak = array();
			$NoUrutKontrak = 1;
			foreach($QueryKontrak->result_array() AS $rowKontrak ){
				$TotalKOH += $rowKontrak['TtlKO'];
				$arrayKontrak[] = array(
					'NoUrutKontrak' => $NoUrutKontrak++,
					'NoKO' => $rowKontrak['NoKO'],
					'TglKO' => $rowKontrak['TglKO'],
					'TtlKO' => $rowKontrak['TtlKO'],
				);

			}
			// === End Pembayaran / BLE ===



			// if ($dataAwal->KategoriId == 'KONTRAK') {
			// 	$QueryKontrak = $this->db->query("SELECT NoKO, TglKO, SubTotal-DiscAmount+PPN AS 'TtlKO' FROM KoHdr WHERE PotongKO='KO21570014' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ");
			// 	$CekKontrak = $QueryKontrak->num_rows();
			// }else{
			// 	$CekKontrak = 0;
			// }



			// $QueryPembayaran = $this->db->query("SELECT * FROM BLE WHERE NoKO='$NoKO' AND TglBayar>='$DariTgl' AND TglBayar<='$SampaiTgl' ORDER BY NoKO");
			// $Jumlah = $QueryPembayaran->num_rows();
			// foreach($QueryPembayaran->result_array() AS $dataBayar ){
			// 	echo 'Amount : '.$dataBayar['NoKO'].'<br>';
			// }

			$SisaPembayaran = $TotalKO - $TotalTerbayar;

			$arrayKO[] = (object) array(
				'NoUrut' => $NoUrut++,
				'NoKO' => $NoKO,
				'TglKO' => $dataAwal->TglKO,
				'VendorNm' => $dataAwal->VendorNm,
				'KategoriId' => $dataAwal->KategoriId,
				'TotalKO' => $TotalKO,
				'BLE' => $CekPembayaran,
				'TotalTerbayar' => $TotalTerbayar,
				'DataTerbayar' => $arrayTerbayar,
				'SisaPembayaran' => $SisaPembayaran,
				'ClosedBy' => ($dataAwal->ClosedBy == '' || $dataAwal->ClosedBy == NULL ) ? '' : $dataAwal->ClosedBy,
				'CekKontrak' => $CekKontrak,
				'TotalKOH' => $TotalKOH,
				'DataKontrak' => $arrayKontrak,

			);

			$SUM_TotalKO += $TotalKO;
			$SUM_TotalKOH += $TotalKOH;
			$SUM_TotalTerbayar += $TotalTerbayar;
			$SUM_SisaPembayaran += $SisaPembayaran;

			// $TotalKO = 0;
			$TotalTerbayar = 0;
			// $TotalKOH = 0;

			// echo 'No KO : '.$TotalKO.'<br>';

			
		}
		// $SUM_TotalKO = 
		$data['arrayKO'] = $arrayKO;
		$data['SUM_TotalKO'] = $SUM_TotalKO;
		$data['SUM_TotalKOH'] = $SUM_TotalKOH;
		$data['SUM_TotalTerbayar'] = $SUM_TotalTerbayar;
		$data['SUM_SisaPembayaran'] = $SUM_SisaPembayaran;

		// print_rr($data);
		// die;



        $this->load->view('laporan_view/procurement/RekapKO/pdf_tengah',$data);


        // print_rr($data);
	}

}

/* End of file FrmRekapKO.php */
/* Location: ./application/controllers/Laporan/Procurement/FrmRekapKO.php */