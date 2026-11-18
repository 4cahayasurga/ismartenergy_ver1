<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL);
class FrmPDLap extends CI_Controller {


	var $PDStatus;
	var $aksi;
	var $UserID;
	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();


		redirect('Entry/PDPJ/EntryPDLap_C');
		$this->PDStatus = 'Lapangan';
		$this->aksi = 'NEW';
		$this->UserID = $this->session->userdata('MIS_LOGGED_ID');
		$this->Company = $this->config->item('Company');
		$this->load->model('PDPJ_Model/Model_FrmLapangan');


		PembuatanInvoiceSementara(); 
		tbl_NewInvoice();

		// == START ADD PD_PPN,23 Nov 2023.
		PenambahanField_PD_PPN();
		// == END ADD PD_PPN,23 Nov 2023.
	}

	public function view($JobNo='',$Alokasi='')
	{
		if ($this->session->userdata('PecahToken')->PD == 0) {
			echo 'Akses Ditolak';
			exit;
		}

		$data['title'] = 'Permintaan Dana Lapangan';
		$data['judul'] = 'Permintaan Dana Lapangan';
		$data['atur_kiri'] = '1';
		$data['ListJob'] = $this->ListJob();
		$data['ListAlokasi'] = $this->ListAlokasi(); 
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/FrmPD_Lap_View/halaman_utama', $data);
		$this->load->view('templates/footer');
	}

	function ListJob($pengurutan=''){
		$CI = &get_instance();
		$user_id = $CI->session->userdata('MIS_LOGGED_ID');
		$Company = $CI->config->item('Company');

		$var_pengurutan = '';
		if($pengurutan !=''){
			$var_pengurutan = 'ORDER BY ax.JobNo DESC';
		}


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
			on bx.JobNo = ax.JobNo WHERE bx.Company='".$this->Company."' group by ax.JobNo, bx.JobNm ".$var_pengurutan."

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

	function dapatkan_tablePD(){

		if (!$this->input->post()) {
			echo 'error';
			exit;
		}


		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$PDMIX = ($this->input->post('PDMIX')) ? 1 : 0;

		$query_tambah_PDMIX = "";
		if ($PDMIX == 1) {
			$query_tambah_PDMIX = "AND NoPD LIKE '%MIX%' ";
		}else{
			$query_tambah_PDMIX = "";
		}


		// $MyQuery = $this->db->query(" SELECT * FROM PdHdr WHERE JobNo='$JobNo' AND (NoRef IS NULL OR NoRef='') AND Alokasi='$Alokasi' ".$query_tambah_PDMIX."  ");

		$MyQuery = $this->db->query(" SELECT * FROM PdHdr WHERE JobNo='$JobNo' AND (NoRef IS NOT NULL OR NoRef !='') AND Alokasi='$Alokasi' ".$query_tambah_PDMIX."  ");

		$ArrayBuatan = array();
		$sumTotalPD = 0;

		foreach ($MyQuery->result() as $row) {

			$sumTotalPD += $row->TotalPD;


			$ArrayBuatan[] = (object) array(
				'LedgerNo' => $row->LedgerNo,
				'NoPD' => $row->NoPD,
				'NoRef' => $row->NoRef,
				'TglPD' => tgl_baru($row->TglPD),
				'Alokasi' => $row->Alokasi,
				'TipeForm' => $row->TipeForm,
				'NoKO' => $row->NoKO,
				'Deskripsi' => $row->Deskripsi,
				'TotalPD' => VolKetik($row->TotalPD),
				'ApprovedByAK' => $row->ApprovedByAK,
				'TimeApprovedAK' => tgl_baru_dengan_waktu($row->TimeApprovedAK),


			);
		}

		$data['dataPD'] = $ArrayBuatan;
		$data['sumTotalPD'] = $sumTotalPD;
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;

		$queryJob = $this->db->query("SELECT * FROM Job WHERE JobNo='$JobNo' ")->row();
		$data['TipeManajerial'] = $queryJob->TipeManajerial;
		$data['JobNm'] = $queryJob->JobNm;
		$data['StatusJob'] = $queryJob->StatusJob;
		$data['KSO'] = $queryJob->KSO;

		// print_rr($data);
		// exit;

		// $this->load->view('FrmPD_Lap_View/table_utama_PD', $data);
		$this->load->view('entry_view/PDPJ_View/FrmPD_Lap_View/table_utama_PD', $data);

	}

	function AksiApprove(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$NoPD = $this->input->post('NoPD');
		$TxtApprove = $this->input->post('TxtApprove');
		$QueryCek = $this->db->query("SELECT NoPD, NoRef, ApprovedByKT, ApprovedByKK, RejectBy, (SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE FROM PdHdr WHERE NoPD='$NoPD' ");


		if ($QueryCek->num_rows() > 0) {
			$row = $QueryCek->row();

			if ($row->RejectBy !=NULL) {
				echo 'sudah di-reject oleh '.$row->RejectBy;
				exit;
			}

			if ($row->CountBLE > 0) {
				echo 'sudah ada pembayaran untuk '.$NoPD;
				exit;
			}

			if ($row->ApprovedByKT != NULL) {
				echo 'sudah di-approved oleh Cost Control';
				exit;
			}

			if ($row->ApprovedByKK != NULL) {
				echo 'sudah di-approved oleh Model_FrmLapangan';
				exit;
			}

			if ($TxtApprove == 'APPROVE') {
				$set = array(
					'ApprovedByAK' => $this->session->userdata("MIS_LOGGED_NAME"),
					'TimeApprovedAK' => date('Y-m-d H:i:s'),
				);
			}else{
				$set = array(
					'ApprovedByAK' => NULL,
					'TimeApprovedAK' => NULL,
				);
			}

			$this->db->set($set)->where('NoPD',$NoPD)->update('PdHdr');
			if ($this->db->affected_rows() > 0) {
				echo 'berhasil';
			}else{
				echo 'gagal';
			}

		}else{
			echo 'data tidak ditemukan';
		}

	}

	function NoUrutOtomatis_PD($JobNo,$Alokasi){
		$query = $this->db->query("SELECT * FROM Counter WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'");

		$PDTxt = 'PD';
		$PDTxt .= $JobNo.''.$Alokasi;


		if ($query->num_rows() > 0) {

			$row = $query->row();
			$CounterPD = $row->CounterPD;
			$CounterPD++;
			$PDTxt .= sprintf('%05s', $CounterPD);

		}else{
			$CounterPD = 1;
			$PDTxt .= sprintf('%05s', $CounterPD);
		}

		return $PDTxt;
	}

	private function KodeUrutBuatan($JobNo = '', $Alokasi = '', $PD_Dari='')
	{
		$CI = &get_instance();

		$eksekusi = $CI->db->select('*')
		->where('JobNo', $JobNo)
		->where('Alokasi', $Alokasi)
		->get('Counter');

		$kode_statik = '';
		$NamaCounter = '';

		if ($PD_Dari == 'PDRKD') {

			$kode_statik = 'PDRKD';
			$NamaCounter = 'CounterKSO';

		}else if($PD_Dari == 'PDMIX'){

			$kode_statik = 'PDMIX';
			$NamaCounter = 'CounterPDMIX';

		}else if($PD_Dari == 'PD'){

			$kode_statik = 'PD';
			$NamaCounter = 'CounterPD';

		}


		$gabung = $kode_statik . $JobNo . $Alokasi;


		if ($eksekusi->num_rows() > 0) {
			$row = $eksekusi->row();
			$angka_counter_pd = $row->$NamaCounter + 1;
			$hitungan = sprintf('%05s', $angka_counter_pd);
			$gabungan = $gabung . $hitungan;
			return array(
				'KodeUrutPd' => $gabungan,
				'status_kode' => 'lama'
			);
		} else {
			$hitungan = '00001';
			$gabungan = $gabung . $hitungan;
			return array(
				'KodeUrutPd' => $gabungan,
				'status_kode' => 'baru'
			);
		}
	}

	// ========= FORM UNTUK TAMBAH PD ========
	function FrmAddNewPD($JobNo='',$Alokasi='',$StatusTambah=''){

		if ($this->session->userdata('PecahToken')->PD == 0) {
			echo 'Akses Ditolak';
			exit;
		}

		if ($JobNo == '' || $Alokasi == '' || $StatusTambah == '' ) {
			redirect('FrmPDLap/view');
		}

		// echo 'apa';
		// die;
		$data['judul'] = 'Form Entry Permintaan Dana';
		$data['atur_kiri'] = '1';

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;

		$data['dataTipeForm'] = $this->Model_FrmLapangan->TipeForm_DB($Alokasi);
		$data['JobNm'] = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo' ")->row()->JobNm;

		$data['no_urut_pd'] = $this->KodeUrutBuatan($JobNo, $Alokasi,$StatusTambah);


		$TglPD = date('Y-m-d');
		$dapat_hitungan = $this->Model_FrmLapangan->dapatkan_hari_senin_atau_monday($TglPD);
		$PrdAwal_minggu = $dapat_hitungan['PrdAwal_minggu'];
		$PrdAkhir_minggu = $dapat_hitungan['PrdAkhir_minggu'];
		$HitungMinggu = $this->Model_FrmLapangan->hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu);

		$data['PrdAkhir'] = $PrdAkhir_minggu;
		$data['PrdAwal'] = $PrdAwal_minggu;
		$data['Minggu'] = $HitungMinggu;

		$data['KeteranganAlokasi'] = $this->db->query("SELECT Keterangan FROM Alokasi WHERE Alokasi='$Alokasi' ")->row()->Keterangan;
		$data['ListBank'] = $this->Model_FrmLapangan->ListBank();
		// print_rr($data);
		// exit;

		$QueryListRAP = $this->db->query("SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC")->result();
		$data['ListRAP'] = $QueryListRAP;

		$data['StatusTambah'] = $StatusTambah;

		// echo $StatusTambah;
		// exit;

		// $this->load->view('template/header',$data);
		// $this->load->view('template/sidebar');
		// $this->load->view('template/navbar');
		// $this->load->view('FrmPD_Lap_View/form_entryPD', $data);
		// $this->load->view('template/footer');


		$NoRef = '';
		// == jika PD lapangan maka NoRef wajib ada, jika bukan kosongkan ==
		if ($this->PDStatus == 'Lapangan') {
			$NoRef = $this->Model_FrmLapangan->PembuatanNoRef_Lapangan($JobNo,$Alokasi,$StatusTambah);
		}else{
			$NoRef = '';
		}

		$data['NoRef'] = $NoRef;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/FrmPD_Lap_View/form_entryPD', $data);
		$this->load->view('templates/footer');
		
	}

	function dapat_data_ketika_TglPD_berubah_js(){
		if (!$this->input->post()) {
			echo json_encode(array('status'=>'gagal'));
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$TglPD = $this->input->post('TglPD');

		$dapat_hitungan = $this->Model_FrmLapangan->dapatkan_hari_senin_atau_monday($TglPD);
		$PrdAwal_minggu = $dapat_hitungan['PrdAwal_minggu'];
		$PrdAkhir_minggu = $dapat_hitungan['PrdAkhir_minggu'];
		$HitungMinggu = $this->Model_FrmLapangan->hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu);

		echo json_encode(
			array(
				'status' => 'berhasil',
				'PrdAwal' => $PrdAwal_minggu,
				'PrdAkhir' => $PrdAkhir_minggu,
				'Minggu' => $HitungMinggu
			)
		);

	}

	function get_ListKO(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$NoPD = $this->input->post('NoPD', TRUE);
		$TipeForm = $this->input->post('TipeForm',TRUE);

		$QueryTambahan = "";
		if ($TipeForm == '04B') {
			$QueryTambahan = "AND KategoriId='KONTRAK' ";
		}else if($TipeForm == '05B'){
			$QueryTambahan = "AND KategoriId='PO' ";
		}else{
			$QueryTambahan = "";
		}

		if ($this->PDStatus=='Lapangan') {
			$QueryTambahan = "AND KategoriId='KONTRAK' ";
		}


		$MyQuery = "SELECT * FROM KoHdr WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ".$QueryTambahan." ";

		$QueryEksekusi = $this->db->query($MyQuery)->result();

		$internal = $JobNo . '_INT';
		$html = '';
		$TotalTerbayar = 0;

		$html .='<option value="'.$internal.'" data-VendorId="" data-JobNo="'.$JobNo.'" data-Alokasi="'.$Alokasi.'">'.$internal.'</option>';
		
		foreach($QueryEksekusi as $row){

			if ($row->ClosedBy != NULL || $row->ClosedBy !=''  ) {
				continue;
			}

			$TotalTerbayar = 0;

			$QueryCek = $this->db->query("SELECT SUM(Amount) AS 'Amount' FROM BLE WHERE JobNo='$JobNo' AND NoKO='$row->NoKO' ");

			if ($QueryCek->num_rows() > 0 ) {
				$rowCek = $QueryCek->row_array();
				$TotalTerbayar =  ($rowCek['Amount'] == NULL) ? 0 : (float) $rowCek['Amount'];
			}

			if ($TipeForm == '05B') {
				$SubTotal = 0;
				$DiscAmount = 0;
				$PPN = 0;

				$SubTotal = (float) $row->SubTotal;
				$DiscAmount = (float) $row->DiscAmount;
				$PPN = (float) $row->PPN;

				$Hitung = 0;
				$Hitung = (float) ($SubTotal - $DiscAmount) + $PPN;

				if ($Hitung > $TotalTerbayar) {
					$html .="<option value='".$row->NoKO."' data-VendorId='".$row->VendorId."' data-JobNo='".$JobNo."' data-Alokasi='".$Alokasi."'  >".$row->NoKO."</option>";
				}

			}else{
				$html .="<option value='".$row->NoKO."' data-VendorId='".$row->VendorId."' data-JobNo='".$JobNo."' data-Alokasi='".$Alokasi."'  >".$row->NoKO."</option>";
			}

			
		}

		echo $html;

	}

	function getVendor(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$NoKO = $this->input->post('NoKO');
		$VenID = $this->input->post('VendorId',TRUE);

		if (substr($NoKO,-4) == '_INT') {
			$query = $this->db->query("SELECT Nama, Alamat, Telepon, NPWP, NoRek, AtasNama, Bank FROM Job WHERE JobNo='$JobNo' ")->row();

			$Nama = $query->Nama;
			$Alamat = $query->Alamat;
			$Telepon = $query->Telepon;
			$NPWP = $query->NPWP;
			$NoRek = $query->NoRek;
			$AtasNama = $query->AtasNama;
			$Bank = $query->Bank;
		}else{
			$query = $this->db->query("SELECT B.* FROM KoHdr A LEFT JOIN Vendor B ON A.VendorId=B.VendorId WHERE NoKO='$NoKO'  ")->row();
			$Nama = $query->VendorNm;
			$Alamat = $query->Alamat;
			$Telepon = $query->Telepon1;
			$NPWP = $query->NPWP;
			$NoRek = $query->NoRek;
			$AtasNama = $query->AtasNama;
			$Bank = $query->Bank;
		}

		$ArrayBuatan = (object) array(
			'Nama' => $Nama,
			'Alamat' => $Alamat,
			'Telepon' => $Telepon,
			'NPWP' => $NPWP,
			'NoRek' => $NoRek,
			'AtasNama' => $AtasNama,
			'Bank' => $Bank,
		);

		echo json_encode($ArrayBuatan);


	}

	function pindah_data(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$NoPD = $this->input->post('NoPD', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$JobNo = $this->input->post('JobNo', TRUE);
		$NoKO = $this->input->post('NoKO', TRUE);
		$VendorNm = $this->input->post('VendorNm', TRUE);

		$this->db->trans_begin();

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$Company = $this->Company;

		$where = array(
			'NoPD' => $NoPD,
			'Company' => $Company
		);


		$cek_data = $this->db->where($where)->get('PdDtl_sementara')->num_rows();
		if ($cek_data > 0) {
			$this->db->where($where)->delete('PdDtl_sementara');

		}
		
		$this->db->query("
			INSERT INTO PdDtl_sementara (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Company)
			SELECT '$NoPD', NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan,'$UserEntry', '$TimeEntry', '$Company' FROM KoDtl WHERE NoKO='$NoKO'
			");

		
		$ArrayCoba = array();
		$TotalBayar = 0;
		$TotalInvoice = 0;
		$QueryCoba = $this->db->query("
			SELECT *, 
			(SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS 'TotalPayment'
			FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO'
			");
		foreach($QueryCoba->result() as $row ){
			$QueryInvPD = $this->db->query("SELECT * FROM InvPD WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$row->NoKO' AND InvNo='$row->InvNo' ");

			if ($QueryInvPD->num_rows() > 0) {
				$rowInvPD = $QueryInvPD->row();
				$TotalBayar += (float) $rowInvPD->PaymentAmount;

				$ArrayCoba[] = (object)  array(
					'LedgerNo' => $row->LedgerNo,
					'NoKO' => $row->NoKO,
					'VendorNm' => $VendorNm,
					'InvNo' => $row->InvNo,
					'InvDate' => $row->InvDate,
					'DueDate' => $row->DueDate,
					'Total' => (float) $row->Total,
					'PPN' => (float) $row->PPN,
					'FPNo' => $row->FPNo,
					'FPDate' => $row->FPDate,
					'PaymentAmount' => (float) $rowInvPD->PaymentAmount,
					'TotalPayment' => ($row->TotalPayment == NULL) ? 0 : (float) $row->TotalPayment,
					'OriginalPayment' => (float) $rowInvPD->PaymentAmount,
					'IsChecked' => 1,
					'NoPD' => $NoPD,
					'JobNo' => $JobNo,
					'Company' => $this->Company,
				);

			}else{
				$rowInvPD = $QueryInvPD->row();

				$TotalPaymentValidasi =  ($row->TotalPayment == NULL) ? 0 : (float) $row->TotalPayment;

				if ((float) $row->Total <= $TotalPaymentValidasi ){
					continue;
				}

				$TotalInvoice += (float) $row->Total;

				$ArrayCoba[] = (object) array(
					'LedgerNo' => $row->LedgerNo,
					'NoKO' => $row->NoKO,
					'VendorNm' => $VendorNm,
					'InvNo' => $row->InvNo,
					'InvDate' => $row->InvDate,
					'DueDate' => $row->DueDate,
					'Total' => (float) $row->Total,
					'PPN' => (float) $row->PPN,
					'FPNo' => $row->FPNo,
					'FPDate' => $row->FPDate,
					'PaymentAmount' => ($row->TotalPayment == NULL) ? 0 : (float) $row->Total,
					'TotalPayment' => (float) ($row->Total - $row->TotalPayment),
					'OriginalPayment' => ($row->TotalPayment == NULL) ? 0 : (float) $row->TotalPayment,
					'IsChecked' => 0,
					'NoPD' => $NoPD,
					'JobNo' => $JobNo,
					'Company' => $this->Company,
				);

				
			}

		}

		$CekInvoice = $this->db->query("SELECT * FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company'  ")->num_rows();
		if ($CekInvoice > 0) {
			$this->db->query("DELETE FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company' ");
		}

		if (substr($NoKO,-4) != '_INT') {
			if (count($ArrayCoba) > 0) {
				$this->db->insert_batch('NewInvoice',$ArrayCoba);
			}
		}



		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
		} else {
			$this->db->trans_commit();
			echo 'berhasil';
		}

		
	}

	function tampilkan_tbl_PdDtl(){
		if (!$this->input->post()) {
			exit;
		}


		$NoPD = $this->input->post('NoPD', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$JobNo = $this->input->post('JobNo', TRUE);

		$NoKO = $this->input->post('NoKO', TRUE);
		$TipeForm = $this->input->post('TipeForm', TRUE);
		$KSO = cek_KSO_Job($JobNo);
		$KSO = ($KSO <> 0 ) ? 1 : 0;

		$TipeForm = $this->input->post('TipeForm', TRUE);
		$VendorNm = $this->input->post('VendorNm', TRUE);

		$data['NoPD'] = $NoPD;
		$data['Alokasi'] = $Alokasi;
		$data['JobNo'] = $JobNo;
		$data['NoKO'] = $NoKO;

		$where = array(
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,
			'NoPD' => $NoPD,
			'NoKO' => $NoKO,
			'TipeForm' => $TipeForm,
		);

		$data['KSO'] = $KSO;

		$data['dataPdDtl'] = $this->db->query("SELECT *, (Vol * HrgSatuan) as JumlahHarga FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ORDER BY NoUrut DESC")->result();

		$data['List_NoPJSebelumnya'] = $this->Panggil_List_NoPJSebelumnya($JobNo,$TipeForm,$KSO);

		$data['TabUraian'] = (!$this->input->post('TabKirim') || $this->input->post('TabKirim') =='' ) ? 'active' : '';
		$data['TabInvoice'] = ($this->input->post('TabKirim') || $this->input->post('TabKirim') !='') ? 'active' : '';

		$MyInvoice = $this->db->query("SELECT * FROM NewInvoice WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND Company='$this->Company' ");

		$DapatInvoice = $this->session->userdata('dataArrayInvoice');
		$data['InvoiceData'] = $MyInvoice->result();

		$data['TotalBayar'] = $this->db->query("SELECT SUM(TotalPayment) as 'TotalBayar' FROM NewInvoice WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND Company='$this->Company' AND IsChecked<>0 ")->row()->TotalBayar;

		$data['TotalInvoice'] = $this->db->query("SELECT SUM(Total) as 'TotalInvoice' FROM NewInvoice WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND Company='$this->Company' ")->row()->TotalInvoice;


		$this->load->view('entry_view/PDPJ_View/FrmPD_Lap_View/tblPdDtl_sementara', $data);

	}

	private function Panggil_List_NoPJSebelumnya($JobNo,$TipeForm,$KSO){
		return $this->db->query("WITH TmpQuery AS (
			SELECT NoPJ, Saldo, (SELECT COUNT(NoPJSebelumnya) FROM PdHdr B WHERE B.NoPJSebelumnya=PdHdr.NoPJ) AS 'AlreadyUsedPJ'
			FROM PdHdr WHERE JobNo='$JobNo' AND TipeForm='$TipeForm' AND KSO='$KSO' AND RejectBy IS NULL
			AND NoPJ IS NOT NULL AND NoRef IS NOT NULL 
			)
		SELECT * FROM TmpQuery WHERE AlreadyUsedPJ=0 AND Saldo<>0 ORDER BY NoPJ DESC")->result();
	}

	private function PanggilDataInvoice($JobNo,$NoPD,$NoKO,$VendorNm){
		if (substr($NoKO,-4) == '_INT') {
			return false;
		}

		$ArrayCoba = array();
		$TotalBayar = 0;
		$TotalInvoice = 0;
		$QueryCoba = $this->db->query("
			SELECT *, 
			(SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS 'TotalPayment'
			FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO'
			");
		foreach($QueryCoba->result() as $row ){
			$QueryInvPD = $this->db->query("SELECT * FROM InvPD WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$row->NoKO' AND InvNo='$row->InvNo' ");

			if ($QueryInvPD->num_rows() > 0) {
				$rowInvPD = $QueryInvPD->row();
				$TotalBayar += $rowInvPD->PaymentAmount;

				$ArrayCoba[] = (object)  array(
					'LedgerNo' => $row->LedgerNo,
					'NoKO' => $row->NoKO,
					'VendorNm' => $VendorNm,
					'InvNo' => $row->InvNo,
					'InvDate' => $row->InvDate,
					'DueDate' => $row->DueDate,
					'Total' => (float) $row->Total,
					'PPN' => (float) $row->PPN,
					'FPNo' => $row->FPNo,
					'FPDate' => $row->FPDate,
					'PaymentAmount' => (float) $rowInvPD->PaymentAmount,
					'TotalPayment' => (float) ($row->TotalPayment == NULL) ? 0 : $row->TotalPayment,
					'OriginalPayment' => (float) $rowInvPD->PaymentAmount,
					'IsChecked' => 1,
					'NoPD' => $NoPD,
					'JobNo' => $JobNo,
					'Company' => $this->Company,
				);

			}else{
				$rowInvPD = $QueryInvPD->row();

				$TotalPaymentValidasi = ($row->TotalPayment == NULL) ? 0 : $row->TotalPayment;

				if ($row->Total <= $TotalPaymentValidasi ){
					continue;
				}

				$TotalInvoice += $row->Total;

				$ArrayCoba[] = (object) array(
					'LedgerNo' => $row->LedgerNo,
					'NoKO' => $row->NoKO,
					'VendorNm' => $VendorNm,
					'InvNo' => $row->InvNo,
					'InvDate' => $row->InvDate,
					'DueDate' => $row->DueDate,
					'Total' => $row->Total,
					'PPN' => $row->PPN,
					'FPNo' => $row->FPNo,
					'FPDate' => $row->FPDate,
					'PaymentAmount' => ($row->TotalPayment == NULL) ? 0 : $row->Total,
					'TotalPayment' => ($row->Total - $row->TotalPayment),
					'OriginalPayment' => ($row->TotalPayment == NULL) ? 0 : $row->TotalPayment,
					'IsChecked' => 0,
					'NoPD' => $NoPD,
					'JobNo' => $JobNo,
					'Company' => $this->Company,
				);

				
			}

		}

		$CekInvoice = $this->db->query("SELECT * FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company'  ")->num_rows();
		if ($CekInvoice > 0) {
			$this->db->query("DELETE FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company' ");
		}

		if (count($ArrayCoba) > 0) {
			$this->db->insert_batch('NewInvoice',$ArrayCoba);
		}


	}

	function PengecekanNULL($nilai){
		return ($nilai=='' || $nilai==NULL) ? NULL : $nilai;
	}
	function PengecekanNULL_Jadi_Angka($nilai){
		return ($nilai=='' || $nilai==NULL) ? 0 : $nilai;
	}

	function SimpanUraian(){
		if (!$this->input->post()) {
			// echo 'error';
			exit;
		}

		
		$NoPD = $this->input->post('NoPD');
		$KdRAP = $this->input->post('KdRAP');
		$Uraian = $this->PengecekanNULL($this->input->post('Uraian'));
		$Uom = $this->PengecekanNULL($this->input->post('Uom'));

		$Vol = $this->PengecekanNULL_Jadi_Angka($this->input->post('Vol'));
		$Vol = CekDanHapusKoma($Vol);
		$Vol = (float) $Vol;

		$HrgSatuan = $this->PengecekanNULL_Jadi_Angka($this->input->post('HrgSatuan'));
		$HrgSatuan = CekDanHapusKoma($HrgSatuan);
		$HrgSatuan = (float) $HrgSatuan;

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$query = $this->db->query("SELECT TOP 1 NoUrut FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company'  ORDER BY NoUrut DESC ");

		$NoUrut = NULL;
		if ($query->num_rows() == 0) {
			$NoUrut = (int) 1;
		}else{
			$row = $query->row();
			$NoUrut = (int) $row->NoUrut + 1;
		}

		$this->db->trans_begin();


		$data = array(
			'NoPD' => $NoPD,
			'NoUrut' => $NoUrut,
			'KdRAP' => $KdRAP,
			'Uraian' => $Uraian,
			'Vol' => $Vol,
			'Uom' => $Uom,
			'HrgSatuan' => $HrgSatuan,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'Company' => $this->Company,
		);

		// print_rr($_POST);
		// echo '<hr>';
		// print_rr($data);
		// die;


		$this->db->insert('PdDtl_sementara',$data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			// return false;
			echo 'gagal';
		} else {
			$this->db->trans_commit();
			// return true;
			echo 'berhasil';
		}
	}

	function EditUraian(){
		if (!$this->input->post()) {
			exit;
		}

		$NoPD = $this->input->post('NoPD');
		$NoUrut = $this->input->post('NoUrut');

		$KdRAP = $this->input->post('KdRAP');
		$Uraian = $this->input->post('Uraian');
		$Uom = $this->input->post('Uom');

		$Vol = $this->PengecekanNULL_Jadi_Angka($this->input->post('Vol'));
		$Vol = CekDanHapusKoma($Vol);
		$Vol = (float) $Vol;

		$HrgSatuan = $this->PengecekanNULL_Jadi_Angka($this->input->post('HrgSatuan'));
		$HrgSatuan = CekDanHapusKoma($HrgSatuan);
		$HrgSatuan = (float) $HrgSatuan;

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$where = array(
			'NoUrut' => $NoUrut,
			'NoPD' => $NoPD,
			'Company' => $this->Company,
		);

		$set = array(
			'Uraian' => $Uraian,
			'Vol' => $Vol,
			'Uom' => $Uom,
			'HrgSatuan' => $HrgSatuan,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'Company' => $this->Company,
		);

		// print_rr($_POST);
		// echo '<hr>';
		// print_rr($set);
		// die;

		$this->db->trans_begin();



		$this->db->set($set)->where($where)->update('PdDtl_sementara');


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			// return false;
			echo 'gagal';
		} else {
			$this->db->trans_commit();
			// return true;
			echo 'berhasil';
		}
	}

	function MembuatMenjadiKoma($data)
	{
		$array = array();
		foreach ($data as $row) {
			$array[] = $row;
		}
		return implode(",", $array);
	}

	function CekDataInvoice_Utk_NoTagihan(){
		$NoPD = $this->input->post('NoPD');
		$JobNo = $this->input->post('JobNo');
		$TipeForm =  ($this->input->post('TipeForm')=='' || $this->input->post('TipeForm')==NULL) ? NULL : $this->input->post('TipeForm');
		$Alokasi = $this->input->post('Alokasi');

		$NoTagihan = "";
		$QueryInvoice = $this->db->query("SELECT ISNULL(InvNo,NULL) AS InvNo FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company' AND IsChecked<>0 ");
		if ($QueryInvoice->num_rows() > 0) {

			foreach ($QueryInvoice->result() as $row) {
				$NoTagihan != "" && $NoTagihan .= ";";
				$NoTagihan .= $row->InvNo;

				// $NoTagihan = implode(";", $row->InvNo);
			}
		}

		if (strlen($NoTagihan)>100) {
			echo substr ($NoTagihan, 0, 100);
		}else{
			echo $NoTagihan;
		}
		
	}

	function Aksi_Simpan_PD(){
		if (!$this->input->post()) {
			echo 'error';
			// die;
		}

		$NoPD = $this->input->post('NoPD');
		$JobNo = $this->input->post('JobNo');
		$TipeForm =  ($this->input->post('TipeForm')=='' || $this->input->post('TipeForm')==NULL) ? NULL : $this->input->post('TipeForm');
		$NoRef = $this->input->post('NoRef');
		$NoKO = $this->input->post('NoKO');

		if ($NoRef=='' || $NoRef==NULL) {
			echo json_encode(
				array(
					'pesan' => 'noref_belum_diisi',
					'teks' => 'No. BAP/Ref Lapangan	belum diisi.',
					'reload_halaman' => 'no',
				)
			);

			die;
		}

		if ($this->input->post('Alokasi')=='' || $this->input->post('Alokasi')==NULL) {
			echo json_encode(
				array(
					'pesan' => 'alokasi_belum_diisi',
					'teks' => 'Alokasi belum di-pilih.',
					'reload_halaman' => 'no',
				)
			);
			die;
		}

		if ($TipeForm==NULL) {
			echo json_encode(
				array(
					'pesan' => 'tipe_form_kosong',
					'teks' => 'Tipe form belum di-pilih.',
					'reload_halaman' => 'no',
				)
			);
			die;
		}

		if (!$this->input->post('BuktiPendukung') && $TipeForm=='05C') {
			echo json_encode(
				array(
					'pesan' => 'belum_pilih_bukti_pendukung',
					'teks' => 'Belum pilih bukti pendukung.',
					'reload_halaman' => 'no',
				)
			);
			die;
		}

		if (substr($NoKO, 0, 2)=='KO') {

			if (!$this->input->post('BuktiPendukung')) {
				echo json_encode(
					array(
						'pesan' => 'belum_pilih_bukti_pendukung',
						'teks' => 'Belum pilih bukti pendukung.',
						'reload_halaman' => 'no',
					)
				);
				die;
			}
		}




		// == START VALIDASI INVOICE ==
		$QueryInvoice = $this->db->query("SELECT * FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company'");
		if ($QueryInvoice->num_rows() > 0) {

			$CekCheckedInvoice = $this->db->query("SELECT * FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company' AND IsChecked<>0"); 

			if ($CekCheckedInvoice->num_rows() ==0) {
				echo json_encode(
					array(
						'pesan' => 'belum_pilih_invoice',
						'teks' => 'Belum pilih Invoice',
						'reload_halaman' => 'no',
					)
				);
				die;
			}

			// $NoTagihan = "";


			$TotalInvoice_Baru = $this->input->post('TotalInvoice');
			$TotalInvoice_Baru = CekDanHapusKoma($TotalInvoice_Baru);
			$TotalInvoice_Baru = (float) $TotalInvoice_Baru;

			$TotalBayar_Baru = $this->input->post('TotalBayar');
			$TotalBayar_Baru = CekDanHapusKoma($TotalBayar_Baru);
			$TotalBayar_Baru = (float) $TotalBayar_Baru;

			if ($TotalBayar_Baru > $TotalInvoice_Baru) {
				echo json_encode(
					array(
						'pesan' => 'total_centang_melebihi_total_invoice',
						'teks' => 'Total  Bayar  melebihi Total Invoice',
						'reload_halaman' => 'no',
					)
				);
				die;
			}

			$TotalPermintaan_baru = $this->input->post('TotalPermintaan');
			$TotalPermintaan_baru = CekDanHapusKoma($TotalPermintaan_baru);
			$TotalPermintaan_baru = (float) $TotalPermintaan_baru;

			if ($TotalPermintaan_baru > $TotalBayar_Baru) {
				echo json_encode(
					array(
						'pesan' => 'total_permintaan_melebihi_total_centang_invoice',
						'teks' => 'Total Permintaan melebihi Total Bayar Invoice',
						'reload_halaman' => 'no',
					)
				);
				die;
			}

		}

		if ($TipeForm=='04B' AND $TipeForm=='05B') {
			if ($CekCheckedInvoice->num_rows() ==0) {
				echo json_encode(
					array(
						'pesan' => 'belum_pilih_invoice',
						'teks' => 'Tipe Form Belum pilih Invoice',
						'reload_halaman' => 'no',
					)
				);
				die;
			}
		}
		// == END VALIDASI INVOICE ==

		// == START VALIDASI No KO JIKA KO depannya adalah 'KO'

		if (substr($NoKO, 0, 2)=='KO') {

			if (!$this->input->post('BuktiPendukung')) {
				echo json_encode(
					array(
						'pesan' => 'belum_pilih_bukti_pendukung',
						'teks' => 'Belum pilih bukti pendukung.',
						'reload_halaman' => 'no',
					)
				);
				die;
			}

			$TotalKO = 0;
			$TotalPD = 0;
			$TotalTerbayar = 0;

			// == dibawah ini query khusus aksi simpan pd
			$query_Cek_TotalPD = $this->db->query("SELECT * FROM PdHdr WHERE NoKO='$NoKO' AND RejectBy IS NULL");
			if ($query_Cek_TotalPD->num_rows()>0) {
				foreach ($query_Cek_TotalPD->result() as $row ) {
					$TotalPD += ($row->TotalPD=='' || $row->TotalPD==NULL) ? 0 : (float) $row->TotalPD;
				}
			}


			// === CEK TOTAL PEMBAYARAN
			$query_BLE = $this->db->query("SELECT ISNULL(SUM(Amount),0) AS Amount FROM BLE WHERE NoKO='$NoKO'");
			if ($query_BLE->num_rows() > 0) {
				$rowBLE = $query_BLE->row();
				$Amount = ($rowBLE->Amount=='' || $rowBLE->Amount==NULL) ? 0 : (float) $rowBLE->Amount;
				$TotalTerbayar += $Amount;
			}

			$TotalPermintaan_baru = $this->input->post('TotalPermintaan');
			$TotalPermintaan_baru = CekDanHapusKoma($TotalPermintaan_baru);
			$TotalPermintaan_baru = (float) $TotalPermintaan_baru;

			$TotalPD += $TotalPermintaan_baru;


			// == PENGECEKAN TOTAL KO
			$query_Cek_TotalKO = $this->db->query("SELECT * FROM KoHdr WHERE NoKO='$NoKO' ");
			if ($query_Cek_TotalKO->num_rows() > 0) {
				$row_KO = $query_Cek_TotalKO->row();

				if ($row_KO->ApprovedBy=='' || $row_KO->ApprovedBy==NULL) {
					echo json_encode(
						array(
							'pesan' => 'ko_belum_approved',
							'teks' => $NoKO. 'belum di-Approved',
							'reload_halaman' => 'no',
						)
					);
					die;
				}

				$SubTotal_KO = ($row_KO->SubTotal=='' || $row_KO->SubTotal==NULL) ? 0 : (float) $row_KO->SubTotal;
				$DiscAmount_KO = ($row_KO->DiscAmount=='' || $row_KO->DiscAmount==NULL) ? 0 : (float) $row_KO->DiscAmount;
				$PPN_KO = ($row_KO->PPN=='' || $row_KO->PPN==NULL) ? 0 : (float) $row_KO->PPN;

				$TotalKO = ($SubTotal_KO - $DiscAmount_KO + $PPN_KO);

				if ($TotalKO < $TotalPD) {

					$PesanKO = "";
					$PesanKO .= "<h3>Total Akumulasi PD melebihi nilai Kontrak/PO. <br />";
					$PesanKO .= "Nilai Kontrak/PO ".$NoKO." : ".number_format($TotalKO)." <br />";
					$PesanKO .= "Total Akumulasi PD termasuk current : ".number_format($TotalPD)." <br />";
					$PesanKO .= "Nilai Kontrak/PO yang sudah dibayar : ".number_format($TotalTerbayar)." <br /></h3>";

					// is_pesan('warning',$PesanKO);
					echo json_encode(
						array(
							'pesan' => 'Total_KO_Lebih_Kecil_Dari_Total_PD',
							'teks' => $PesanKO,
							'reload_halaman' => 'yes',
						)
					);
					die;
				}

				$TotalBayar_Baru = $this->input->post('TotalBayar');
				$TotalBayar_Baru = CekDanHapusKoma($TotalBayar_Baru);
				$TotalBayar_Baru = (float) $TotalBayar_Baru;

				if ($TotalBayar_Baru <> $TotalPermintaan_baru) {
					echo json_encode(
						array(
							'pesan' => 'total_invoice_tidak_sama_dengan_total_permintaan',
							'teks' => 'Total Invoice <> Total Permintaan.',
							'reload_halaman' => 'no',
						)
					);
					die;
				}

			}



		}
		// == END VALIDASI No KO JIKA KO depannya adalah 'KO'

		// == START VALIDASI BANK ==
		$Bank = ($this->input->post('Bank')=='' || $this->input->post('Bank')==NULL) ? NULL : $this->input->post('Bank');
		if ($Bank=='' || $Bank==NULL) {
			echo json_encode(
				array(
					'pesan' => 'bank_penerima_kosong',
					'teks' => 'Bank rekening penerima belum di-pilih.',
					'reload_halaman' => 'no',
				)
			);
			die;
		}
		// == END VALIDASI BANK ==

		$KSO = NULL;
		if (substr($NoPD,2,3) == 'RKD' ) {
			$KSO = 1;
		}else{
			$KSO = 0;
		}

		$Company = $this->Company;
		$CekUraian = $this->db->query("SELECT * FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$Company' ")->num_rows();
		if ($CekUraian < 1) {
			echo json_encode(
				array(
					'pesan' => 'uraian_kosong',
					'teks' => 'Belum input uraian permintaan dana.',
					'reload_halaman' => 'no',
				)
			);
			die;
		}

		// echo $this->input->post('status_kode');
		// echo 'sini';
		// die;

		$JobNo = $this->input->post('JobNo');
		$TglPD = $this->input->post('TglPD');
		$Jaminan = ($this->input->post('Jaminan')) ? 1 : 0;
		$Deskripsi = $this->input->post('Deskripsi');
		$PrdAwal = $this->input->post('PrdAwal');
		$PrdAkhir = $this->input->post('PrdAkhir');
		$Minggu = $this->input->post('Minggu');
		$Alokasi = $this->input->post('Alokasi');
		$TipeForm = $this->input->post('TipeForm');
		$BuktiPendukung = ($this->input->post('BuktiPendukung', TRUE)) ?  $this->MembuatMenjadiKoma($this->input->post('BuktiPendukung', TRUE)) : '';
		$Keterangan = $this->input->post('Keterangan');

		$NoTagihan = $this->input->post('NoTagihan');
		$Nama = $this->input->post('Nama');
		$Alamat = $this->input->post('Alamat');
		$Telepon = $this->input->post('Telepon');
		$NPWP = $this->input->post('NPWP');
		$NoRek = $this->input->post('NoRek');
		$AtasNama = $this->input->post('AtasNama');

		// $SubTotal = $this->input->post('SubTotal');

		$SubTotal = ($this->input->post('SubTotal') == '' || $this->input->post('SubTotal') == NULL) ? 0 : $this->input->post('SubTotal');
		$SubTotal = CekDanHapusKoma($SubTotal);
		$SubTotal = (float) $SubTotal;

		$Saldo = ($this->input->post('Saldo') == '' || $this->input->post('Saldo') == NULL) ? 0 : $this->input->post('Saldo');
		$Saldo = CekDanHapusKoma($Saldo);
		$Saldo = (float) $Saldo;

		$TotalPermintaan = ($this->input->post('TotalPermintaan') == '' || $this->input->post('TotalPermintaan') == NULL) ? 0 : $this->input->post('TotalPermintaan');
		$TotalPermintaan = CekDanHapusKoma($TotalPermintaan);

		$NoPJSebelumnya = ($this->input->post('NoPJSebelumnya') == '') ? NULL : $this->input->post('NoPJSebelumnya');

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$TotalInvoice = ($this->input->post('TotalInvoice')) ? CekDanHapusKoma($this->input->post('TotalInvoice')) : 0;
		$TotalInvoice = (float) $TotalInvoice;

		$TotalBayar = ($this->input->post('TotalBayar')) ? CekDanHapusKoma($this->input->post('TotalBayar')) : 0;
		$TotalBayar = (float) $TotalBayar;


		// == START FIELD PENAMBAHAN BARU PD PPN, DISURUH PAK MUL TANGGAL 23 NOVEMBER 2023
		$PD_PPN = ($this->input->post('PD_PPN')) ? 1 : 0;
		// == START FIELD PENAMBAHAN BARU PD PPN, DISURUH PAK MUL TANGGAL 23 NOVEMBER 2023

		$dataPdHdr = array(
			'NoPD' => $NoPD,
			'NoRef' => $NoRef,
			'JobNo' => $JobNo,
			'TglPD' => $TglPD,
			'Jaminan' => $Jaminan,
			'Deskripsi' => $Deskripsi,
			'PrdAwal' => $PrdAwal,
			'PrdAkhir' => $PrdAkhir,
			'Minggu' => $Minggu,
			'Alokasi' => $Alokasi,
			'TipeForm' => $TipeForm,
			'BuktiPendukung' => $BuktiPendukung,
			'Keterangan' => $Keterangan,
			'NoKO' => $NoKO,
			'NoTagihan' => $NoTagihan,
			'Nama' => $Nama,
			'Alamat' => $Alamat,
			'Telepon' => $Telepon,
			'NPWP' => $NPWP,
			'NoRek' => $NoRek,
			'AtasNama' => $AtasNama,
			'Bank' => $Bank,
			'NoPJSebelumnya' => $NoPJSebelumnya,
			'Saldo' => (float) $Saldo,
			// 'TotalPD' => (float) $TotalPermintaan,
			'TotalPD' => $SubTotal,

			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'KSO' => $KSO,

			'PD_PPN' => $PD_PPN,
		);

		$StatusTambah = $this->input->post('StatusTambah');
		$status_kode = $this->input->post('status_kode');

		// die;

		$this->db->trans_begin();

		$this->db->insert('PdHdr',$dataPdHdr);

		$this->OtomatisUbahCounter($JobNo, $Alokasi, $status_kode,$StatusTambah);

		$this->db->query("
			INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry) 
			SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry FROM PdDtl_sementara 
			WHERE Company='$Company' AND NoPD='$NoPD'");

		$this->db->query("DELETE FROM PdDtl_sementara WHERE Company='$Company' AND NoPD='$NoPD'");

		$ArrayInvoice_Buatan = array();

		if ($this->input->post('PilihanInvoice')) :

			foreach($this->input->post('PilihanInvoice') as $key => $value):

				$InvNo = $this->input->post('InvNo');
				$PaymentAmount = $this->input->post('PaymentAmount');
				$PaymentAmount = CekDanHapusKoma($PaymentAmount);
				$this->db->query("INSERT INTO InvPD (JobNo, NoKO, NoPD, InvNo, PaymentAmount, UserEntry, TimeEntry) VALUES ('$JobNo', '$NoKO', 
					'$NoPD','$InvNo[$key]', '$PaymentAmount[$key]', '$UserEntry', '$TimeEntry')  ");
			endforeach;

			$this->db->query("DELETE FROM NewInvoice WHERE NoPD='$NoPD' AND NoKO='$NoKO' AND Company='$this->Company' ");

		endif;

		if ($this->db->trans_status() === FALSE ) {
			$this->db->trans_rollback();
			// echo 'gagal';
			// is_pesan('error','Entry Data Failed');
			echo json_encode(
				array(
					'pesan' => 'gagal',
					'teks' => 'Entry Data Failed',
					'reload_halaman' => 'yes',
				)
			);
			// die;
		}else{
			$this->db->trans_commit();
			// echo 'berhasil';
			is_pesan('success','Saved Success');
			echo json_encode(
				array(
					'pesan' => 'berhasil',
					'teks' => 'Entry Data Sukses',
					'reload_halaman' => 'yes',

				)
			);

			// die;
		}

	}

	private function OtomatisUbahCounter($JobNo, $Alokasi, $status_kode,$status_tambah='')
	{

		$CI = &get_instance();

		$where_counter = array(
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,
		);

		$data_counter = array(
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,
			'UserEntry' => $CI->session->userdata("MIS_LOGGED_ID"),
			'TimeEntry' => date('Y-m-d H:i:s'),
		);

		if ($status_kode == 'baru') {

			$nama_counter = '';

			if ($status_tambah == 'PDRKD' ) {
				$nama_counter = 'CounterKSO';
			}else if ($status_tambah == 'PDMIX'){
				$nama_counter = 'CounterPDMIX';	
			}else if($status_tambah == 'PD'){
				$nama_counter = 'CounterPD';
			}

			$data_counter[$nama_counter] = 1;
			$CI->db->insert('Counter', $data_counter);

		} else {

			$nama_counter = '';
			if ($status_tambah == 'PDRKD' ) {
				$nama_counter = 'CounterKSO';
			}else if ($status_tambah == 'PDMIX'){
				$nama_counter = 'CounterPDMIX';	
			}else if($status_tambah == 'PD') {
				$nama_counter = 'CounterPD';
			}

			$query = $CI->db->select($nama_counter)->where($where_counter)->get('Counter');
			if ($query->num_rows() > 0) {
				$CounterRow = $query->row_array();

				$getCounterPD = $CounterRow[$nama_counter];
				$data_counter[$nama_counter] = (int) $getCounterPD + 1;
			}
			$CI->db->set($data_counter)->where($where_counter)->update('Counter');

		}


	}

	function aksi_hapus_detail(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}

		$NoPD = $this->input->post('NoPD');
		$NoUrut = $this->input->post('NoUrut');
		$Company = $this->Company;


		$data = array(
			'NoPD' => $NoPD,
			'NoUrut' => $NoUrut,
		);

		$this->db->where($data)->delete('PdDtl_sementara');
		if ($this->db->affected_rows() > 0) {
			echo 'berhasil';
		}else{
			echo 'gagal';
		}


		// print_rr($_POST);
		// echo '<hr>';
		// print_rr($data);
		// exit;
	}

	function aksi_hapus_detail_multiple(){


		if ($this->input->post('NoUrut')) {
			$NoPD = $this->input->post('NoPD');
			$NoUrut = trim($_POST['NoUrut']);	
			$sql = "DELETE FROM PdDtl_sementara WHERE NoUrut in ($NoUrut) AND  NoPD='$NoPD' AND Company='$this->Company' ";
			$this->db->query($sql);
		}

		echo 'berhasil';
		// if ($this->db->affected_rows()>0) {
		// 	echo 'berhasil';
		// }
		// print_rr($_POST);
	}

	// ==== START INVOICE ====
	function AksiUpdate_Invoice(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo');
		$StatusChecked = $this->input->post('StatusChecked');
		$InvNo = $this->input->post('InvNo');

		$TotalPaymentID = ($this->input->post('TotalPaymentID')) ? $this->input->post('TotalPaymentID') : 0;
		$TotalPayment = CekDanHapusKoma($TotalPaymentID);
		$TotalPayment = (float) $TotalPayment;

		$Total = ($this->input->post('Total')) ? $this->input->post('Total') : 0;
		$Total = CekDanHapusKoma($Total);
		$Total = (float) $Total;

		if ($TotalPayment > $Total) {
			echo 'kelebihan_bayar';
			exit;

		}
		// $this->db->query("UPDATE NewInvoice SET IsChecked='$StatusChecked', TotalPayment='$TotalPaymentID' WHERE LedgerNo='$LedgerNo' AND InvNo='$InvNo' ");
		// echo (float )$TotalPayment;
		// print_rr($_POST);
		// exit;

		$this->db->trans_begin();

		$this->db->query("UPDATE NewInvoice SET IsChecked='$StatusChecked', TotalPayment='$TotalPaymentID' WHERE LedgerNo='$LedgerNo' AND InvNo='$InvNo' ");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
		} else {
			$this->db->trans_commit();
			echo 'berhasil';
		}
	}
	// ==== START INVOICE ====

	// ========= END FORM UNTUK TAMBAH PD ========


	// =========  START FORM UNTUK EDIT PD ========
	function FrmSelectPD($JobNo='',$Alokasi='',$NoPD=''){

		if ($JobNo == '' || $Alokasi == '' || $NoPD == '' ) {
			redirect('FrmPDLap/view');
		}


		$data['title'] = 'Form Update Permintaan Dana';
		$data['judul'] = 'Form Update Permintaan Dana';
		$data['atur_kiri'] = '1';

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;

		$data['dataTipeForm'] = $this->db->query("SELECT TipeForm,Keterangan FROM TipeForm  WHERE Alokasi='$Alokasi'")->result();
		$data['JobNm'] = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo' ")->row()->JobNm;



		$TglPD = date('Y-m-d');
		$dapat_hitungan = $this->Model_FrmLapangan->dapatkan_hari_senin_atau_monday($TglPD);
		$PrdAwal_minggu = $dapat_hitungan['PrdAwal_minggu'];
		$PrdAkhir_minggu = $dapat_hitungan['PrdAkhir_minggu'];
		$HitungMinggu = $this->Model_FrmLapangan->hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu);

		$data['PrdAkhir'] = $PrdAkhir_minggu;
		$data['PrdAwal'] = $PrdAwal_minggu;
		$data['Minggu'] = $HitungMinggu;

		$data['KeteranganAlokasi'] = $this->db->query("SELECT Keterangan FROM Alokasi WHERE Alokasi='$Alokasi' ")->row()->Keterangan;
		$data['ListBank'] = $this->Model_FrmLapangan->ListBank();

		$QueryListRAP = $this->db->query("SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC")->result();
		$data['ListRAP'] = $QueryListRAP;

		$data['getPD'] = $this->db->query("SELECT * FROM PdHdr WHERE NoPD='$NoPD' ")->row();


		$data['StatusPD'] = $this->CekApprovePD($NoPD);

		// print_rr($this->CekApprovePD($NoPD));
		// echo $this->CekApprovePD($NoPD);
		// exit;

		// $this->load->view('template/header',$data);
		// $this->load->view('template/sidebar');
		// $this->load->view('template/navbar');
		// $this->load->view('FrmPDLap_View/FrmUpdatePD_view', $data);
		// $this->load->view('template/footer');

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/FrmPD_Lap_View/FrmUpdatePD_view', $data);
		$this->load->view('templates/footer');

	}

	function CekApprovePD($NoPD){
		$QueryCek = $this->db->query("SELECT NoPD, NoRef, ApprovedByAK, ApprovedByKT, ApprovedByKK, RejectBy, (SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE FROM PdHdr WHERE NoPD='$NoPD' ")->row();

		if ($QueryCek->RejectBy != NULL) {
			return 'disabled';
		}else if ($QueryCek->CountBLE > 0) {
			return 'disabled';
		}else if ($QueryCek->ApprovedByAK != NULL) {
			return 'disabled';
		}else if ($QueryCek->ApprovedByKT != NULL) {
			return 'disabled';
		}else if ($QueryCek->ApprovedByKK != NULL) {
			return 'disabled';
		}else{
			return '';
		}

	}


	function pindah_data_PDEdit(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$NoPD = $this->input->post('NoPD', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$JobNo = $this->input->post('JobNo', TRUE);
		$NoKO = $this->input->post('NoKO', TRUE);
		$VendorNm = $this->input->post('VendorNm', TRUE);

		$this->db->trans_start();

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$Company = $this->Company;

		$where = array(
			'NoPD' => $NoPD,
			'Company' => $Company
		);

		$this->db->query("DELETE FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ");
		$this->db->query("DELETE FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company' ");

		$this->db->query("
			INSERT INTO PdDtl_sementara (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Company)
			SELECT '$NoPD', NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, '$this->Company' FROM PdDtl WHERE NoPD='$NoPD'
			");

		$TotalInvoice = 0;
		$TotalBayar = 0;
		$ArrayInvoice = array();
		$LedgerNo_Invoice_Sebelum_Edit = [];

		$MyQuery = $this->db->query("SELECT *,
			(SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS 'TotalPayment'
			FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO' ");

		foreach($MyQuery->result() as $rowInvoice):

			$QueryPanggil_InvPD = $this->db->query("SELECT * FROM InvPD WHERE JobNo='$rowInvoice->JobNo' AND NoPD='$NoPD' AND NoKO='$rowInvoice->NoKO' AND InvNo='$rowInvoice->InvNo' ");

			if ($QueryPanggil_InvPD->num_rows() > 0 ) {

				$row_InvPD = $QueryPanggil_InvPD->row();

				$TotalBayar += (float) $row_InvPD->PaymentAmount;

				$ArrayInvoice[] = array(
					'LedgerNo' => $rowInvoice->LedgerNo,
					'NoKO' => $rowInvoice->NoKO,
					'VendorNm' => $VendorNm,
					'InvNo' => $rowInvoice->InvNo,
					'InvDate' => $rowInvoice->InvDate,
					'DueDate' => $rowInvoice->DueDate,
					'Total' => (float) $rowInvoice->Total,
					'PPN' => (float) $rowInvoice->PPN,
					'FPNo' => $rowInvoice->FPNo,
					'FPDate' => $rowInvoice->FPDate,
					'PaymentAmount' => (float) $row_InvPD->PaymentAmount,
					'TotalPayment' =>  ($rowInvoice->TotalPayment == NULL) ? 0 : (float) $rowInvoice->TotalPayment,
					'OriginalPayment' => (float) $row_InvPD->PaymentAmount,
					'IsChecked' => 1,
					'NoPD' => $NoPD,
					'JobNo' => $JobNo,
					'Company' => $this->Company,

				); 
			}else{
				$Total1 = ($rowInvoice->Total == NULL) ? 0 : (float) $rowInvoice->Total;
				$TotalPayment1 = ($rowInvoice->TotalPayment == NULL) ? 0 : (float) $rowInvoice->TotalPayment;

				if ($Total1 <= $TotalPayment1 ) {
					continue;
				}

				$TotalInvoice += $Total1;


				$Validasi_PaymentAmount =0;
				if ($rowInvoice->Total == NULL) {
					$Validasi_PaymentAmount = $Total1;
				}else{
					$Validasi_PaymentAmount = $Total1 - $TotalPayment1;
				}

				$ArrayInvoice[] = array(
					'LedgerNo' => $rowInvoice->LedgerNo,
					'NoKO' => $rowInvoice->NoKO,
					'VendorNm' => $VendorNm,
					'InvNo' => $rowInvoice->InvNo,
					'InvDate' => $rowInvoice->InvDate,
					'DueDate' => $rowInvoice->DueDate,
					'Total' => (float) $Total1,
					'PPN' => (float) $rowInvoice->PPN,
					'FPNo' => $rowInvoice->FPNo,
					'FPDate' => $rowInvoice->FPDate,
					'PaymentAmount' => (float) $Validasi_PaymentAmount,
					'TotalPayment' =>  $TotalPayment1,
					'OriginalPayment' => (int) 0,
					'IsChecked' => 0,
					'NoPD' => $NoPD,
					'JobNo' => $JobNo,
					'Company' => $this->Company,

				); 


			}


		endforeach;


		if (substr($NoKO,-4) != '_INT') {
			if (count($ArrayInvoice) > 0) {
				$this->db->insert_batch('NewInvoice',$ArrayInvoice);
			}
		}



		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo 'gagal';
		} else {
			echo 'berhasil';
		}


	}


	function tampilkan_tbl_PdDtl_PDEdit(){
		if (!$this->input->post()) {
			exit;
		}


		$NoPD = $this->input->post('NoPD', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$JobNo = $this->input->post('JobNo', TRUE);

		$NoKO = $this->input->post('NoKO', TRUE);
		$TipeForm = $this->input->post('TipeForm', TRUE);
		$KSO = cek_KSO_Job($JobNo);
		$KSO = ($KSO <> 0 ) ? 1 : 0;

		$TipeForm = $this->input->post('TipeForm', TRUE);
		$VendorNm = $this->input->post('VendorNm', TRUE);

		$data['NoPD'] = $NoPD;
		$data['Alokasi'] = $Alokasi;
		$data['JobNo'] = $JobNo;
		$data['NoKO'] = $NoKO;

		$where = array(
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,
			'NoPD' => $NoPD,
			'NoKO' => $NoKO,
			'TipeForm' => $TipeForm,
		);

		$data['KSO'] = $KSO;

		$data['dataPdDtl'] = $this->db->query("SELECT *, (Vol * HrgSatuan) as JumlahHarga FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ORDER BY NoUrut DESC")->result();

		$data['List_NoPJSebelumnya'] = $this->Panggil_List_NoPJSebelumnya($JobNo,$TipeForm,$KSO);

		$data['TabUraian'] = (!$this->input->post('TabKirim') || $this->input->post('TabKirim') =='' ) ? 'active' : '';
		$data['TabInvoice'] = ($this->input->post('TabKirim') || $this->input->post('TabKirim') !='') ? 'active' : '';

		$MyInvoice = $this->db->query("SELECT * FROM NewInvoice WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND Company='$this->Company' ");

		$data['InvoiceData'] = $MyInvoice->result();

		$data['TotalBayar'] = $this->db->query("SELECT SUM(PaymentAmount) as 'TotalBayar' FROM NewInvoice WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND Company='$this->Company' AND IsChecked<>0 ")->row()->TotalBayar;

		$data['TotalInvoice'] = $this->db->query("SELECT SUM(Total) as 'TotalInvoice' FROM NewInvoice WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND Company='$this->Company' ")->row()->TotalInvoice;



		$queryPdHdr = $this->db->query("SELECT Saldo, TotalPD, NoPJSebelumnya, NoPD FROM PdHdr WHERE NoPD='$NoPD' ")->row();
		$data['NoPJSebelumnya_Asli'] = $queryPdHdr->NoPJSebelumnya;
		$data['TotalPD_Asli'] = $queryPdHdr->TotalPD;



		if ($queryPdHdr->NoPJSebelumnya!='' || $queryPdHdr->NoPJSebelumnya!=NULL) {
			$getSaldoDariNoPJSebelumnya = $this->db->query("SELECT ISNULL(Saldo,0) AS 'SaldoNoPJSebelumnya' FROM PdHdr WHERE NoPJ='$queryPdHdr->NoPJSebelumnya'  ")->row();
			$data['Saldo_Asli'] = $getSaldoDariNoPJSebelumnya->SaldoNoPJSebelumnya;
		}else{
			$data['Saldo_Asli'] = 0;
		}


		$data['StatusPD'] = $this->CekApprovePD($NoPD);

		$KSO_Ubah = NULL;
		if (substr($NoPD,2,3) == 'RKD' ) {
			$KSO_Ubah = 1;
		}else{
			$KSO_Ubah = 0;
		}
		$List_NoPJSebelumnya = $this->Panggil_List_NoPJSebelumnya($JobNo,$TipeForm,$KSO_Ubah);
		$data['List_NoPJSebelumnya'] = $List_NoPJSebelumnya;

		$this->load->view('entry_view/PDPJ_View/FrmPD_Lap_View/tblPdDtl_PDEdit', $data);

	}

	function AksiUpdatePD(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}



		$NoPD = $this->input->post('NoPD');
		$JobNo = $this->input->post('JobNo');
		$TipeForm =  ($this->input->post('TipeForm')=='' || $this->input->post('TipeForm')==NULL) ? NULL : $this->input->post('TipeForm');
		$NoRef = $this->input->post('NoRef');
		$NoKO = $this->input->post('NoKO');
		$TglPD = ($this->input->post('TglPD')=='' || $this->input->post('TglPD')==NULL || $this->input->post('TglPD')=='1900-01-01') ? NULL : $this->input->post('TglPD');
		$Deskripsi =  $this->PengecekanNULL($this->input->post('Deskripsi'));

		if ($NoRef=='' || $NoRef==NULL) {
			echo json_encode(
				array(
					'pesan' => 'noref_belum_diisi',
					'teks' => 'No. BAP/Ref Lapangan	belum diisi.',
					'reload_halaman' => 'no',
				)
			);

			die;
		}

		if ($this->input->post('Alokasi')=='' || $this->input->post('Alokasi')==NULL) {
			echo json_encode(
				array(
					'pesan' => 'alokasi_belum_diisi',
					'teks' => 'Alokasi belum di-pilih.',
					'reload_halaman' => 'no',
				)
			);
			die;
		}

		if ($TipeForm==NULL) {
			echo json_encode(
				array(
					'pesan' => 'tipe_form_kosong',
					'teks' => 'Tipe form belum di-pilih.',
					'reload_halaman' => 'no',
				)
			);
			die;
		}

		if ($TglPD==NULL) {
			echo json_encode(
				array(
					'pesan' => 'tgl_pd_kosong',
					'teks' => 'Tgl.PD belum di-isi',
					'reload_halaman' => 'no',
				)
			);
			die;
		}

		if ($Deskripsi==NULL) {
			echo json_encode(
				array(
					'pesan' => 'Deskripsi_kosong',
					'teks' => 'Deskripsi belum di-isi',
					'reload_halaman' => 'no',
				)
			);
			die;
		}

		if (!$this->input->post('BuktiPendukung') && $TipeForm=='05C') {
			echo json_encode(
				array(
					'pesan' => 'belum_pilih_bukti_pendukung',
					'teks' => 'Belum pilih bukti pendukung.',
					'reload_halaman' => 'no',
				)
			);
			die;
		}

		if (substr($NoKO, 0, 2)=='KO') {

			if (!$this->input->post('BuktiPendukung')) {
				echo json_encode(
					array(
						'pesan' => 'belum_pilih_bukti_pendukung',
						'teks' => 'Belum pilih bukti pendukung.',
						'reload_halaman' => 'no',
					)
				);
				die;
			}
		}

		$QueryInvoice = $this->db->query("SELECT * FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company'");
		if ($QueryInvoice->num_rows() > 0) {

			$CekCheckedInvoice = $this->db->query("SELECT * FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company' AND IsChecked<>0"); 

			if ($CekCheckedInvoice->num_rows() ==0) {
				echo json_encode(
					array(
						'pesan' => 'belum_pilih_invoice',
						'teks' => 'Belum pilih Invoice',
						'reload_halaman' => 'no',
					)
				);
				die;
			}

			$TotalInvoice_Baru = $this->input->post('TotalInvoice');
			$TotalInvoice_Baru = CekDanHapusKoma($TotalInvoice_Baru);
			$TotalInvoice_Baru = (float) $TotalInvoice_Baru;

			$TotalBayar_Baru = $this->input->post('TotalBayar');
			$TotalBayar_Baru = CekDanHapusKoma($TotalBayar_Baru);
			$TotalBayar_Baru = (float) $TotalBayar_Baru;

			if ($TotalBayar_Baru > $TotalInvoice_Baru) {
				echo json_encode(
					array(
						'pesan' => 'total_centang_melebihi_total_invoice',
						'teks' => 'Total  Bayar  melebihi Total Invoice',
						'reload_halaman' => 'no',
					)
				);
				die;
			}

			$TotalPermintaan_baru = $this->input->post('TotalPermintaan');
			$TotalPermintaan_baru = CekDanHapusKoma($TotalPermintaan_baru);
			$TotalPermintaan_baru = (float) $TotalPermintaan_baru;

			if ($TotalPermintaan_baru > $TotalBayar_Baru) {
				echo json_encode(
					array(
						'pesan' => 'total_permintaan_melebihi_total_centang_invoice',
						'teks' => 'Total Permintaan melebihi Total Bayar Invoice',
						'reload_halaman' => 'no',
					)
				);
				die;
			}

		}

		if (substr($NoKO, 0, 2)=='KO') {

			if (!$this->input->post('BuktiPendukung')) {
				echo json_encode(
					array(
						'pesan' => 'belum_pilih_bukti_pendukung',
						'teks' => 'Belum pilih bukti pendukung.',
						'reload_halaman' => 'no',
					)
				);
				die;
			}

			$TotalKO = 0;
			$TotalPD = 0;
			$TotalTerbayar = 0;

			// == dibawah ini query khusus aksi update pd, nomor PD yang saat ini dikecualikan agar tidak terbaca saat menghitung
			$query_Cek_TotalPD = $this->db->query("SELECT * FROM PdHdr WHERE NoPD!='$NoPD' AND NoKO='$NoKO' AND RejectBy IS NULL");
			if ($query_Cek_TotalPD->num_rows()>0) {
				foreach ($query_Cek_TotalPD->result() as $row ) {
					$TotalPD += ($row->TotalPD=='' || $row->TotalPD==NULL) ? 0 : (float) $row->TotalPD;
				}
			}


			// === CEK TOTAL PEMBAYARAN
			$query_BLE = $this->db->query("SELECT ISNULL(SUM(Amount),0) AS Amount FROM BLE WHERE NoKO='$NoKO'");
			if ($query_BLE->num_rows() > 0) {
				$rowBLE = $query_BLE->row();
				$Amount = ($rowBLE->Amount=='' || $rowBLE->Amount==NULL) ? 0 : (float) $rowBLE->Amount;
				$TotalTerbayar += $Amount;
			}

			$TotalPermintaan_baru = $this->input->post('TotalPermintaan');
			$TotalPermintaan_baru = CekDanHapusKoma($TotalPermintaan_baru);
			$TotalPermintaan_baru = (float) $TotalPermintaan_baru;

			$TotalPD += $TotalPermintaan_baru;


			// == PENGECEKAN TOTAL KO
			$query_Cek_TotalKO = $this->db->query("SELECT * FROM KoHdr WHERE NoKO='$NoKO' ");
			if ($query_Cek_TotalKO->num_rows() > 0) {
				$row_KO = $query_Cek_TotalKO->row();

				if ($row_KO->ApprovedBy=='' || $row_KO->ApprovedBy==NULL) {
					echo json_encode(
						array(
							'pesan' => 'ko_belum_approved',
							'teks' => $NoKO. 'belum di-Approved',
							'reload_halaman' => 'no',
						)
					);
					die;
				}

				$SubTotal_KO = ($row_KO->SubTotal=='' || $row_KO->SubTotal==NULL) ? 0 : (float) $row_KO->SubTotal;
				$DiscAmount_KO = ($row_KO->DiscAmount=='' || $row_KO->DiscAmount==NULL) ? 0 : (float) $row_KO->DiscAmount;
				$PPN_KO = ($row_KO->PPN=='' || $row_KO->PPN==NULL) ? 0 : (float) $row_KO->PPN;

				$TotalKO = ($SubTotal_KO - $DiscAmount_KO + $PPN_KO);

				if ($TotalKO < $TotalPD) {

					$PesanKO = "";
					$PesanKO .= "<h3>Total Akumulasi PD melebihi nilai Kontrak/PO. <br />";
					$PesanKO .= "Nilai Kontrak/PO ".$NoKO." : ".number_format($TotalKO)." <br />";
					$PesanKO .= "Total Akumulasi PD termasuk current : ".number_format($TotalPD)." <br />";
					$PesanKO .= "Nilai Kontrak/PO yang sudah dibayar : ".number_format($TotalTerbayar)." <br /></h3>";

					// is_pesan('warning',$PesanKO);
					echo json_encode(
						array(
							'pesan' => 'Total_KO_Lebih_Kecil_Dari_Total_PD',
							'teks' => $PesanKO,
							'reload_halaman' => 'yes',
						)
					);
					die;
				}

				$TotalBayar_Baru = $this->input->post('TotalBayar');
				$TotalBayar_Baru = CekDanHapusKoma($TotalBayar_Baru);
				$TotalBayar_Baru = (float) $TotalBayar_Baru;

				if ($TotalBayar_Baru <> $TotalPermintaan_baru) {
					echo json_encode(
						array(
							'pesan' => 'total_invoice_tidak_sama_dengan_total_permintaan',
							'teks' => 'Total Invoice <> Total Permintaan.',
							'reload_halaman' => 'no',
						)
					);
					die;
				}

			}



		}

		if ($TipeForm=='04B' AND $TipeForm=='05B') {
			if ($CekCheckedInvoice->num_rows() ==0) {
				echo json_encode(
					array(
						'pesan' => 'belum_pilih_invoice',
						'teks' => 'Tipe Form Belum pilih Invoice',
						'reload_halaman' => 'no',
					)
				);
				die;
			}
		}



		// == START VALIDASI BANK ==
		$Bank = ($this->input->post('Bank')=='' || $this->input->post('Bank')==NULL) ? NULL : $this->input->post('Bank');
		if ($Bank=='' || $Bank==NULL) {
			echo json_encode(
				array(
					'pesan' => 'bank_penerima_kosong',
					'teks' => 'Bank rekening penerima belum di-pilih.',
					'reload_halaman' => 'no',
				)
			);
			die;
		}
		// == END VALIDASI BANK ==

		// print_rr($_POST);
		// die;

		$KSO = NULL;
		if (substr($NoPD,2,3) == 'RKD' ) {
			$KSO = 1;
		}else{
			$KSO = 0;
		}

		$Company = $this->Company;

		$CekUraian = $this->db->query("SELECT * FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$Company' ")->num_rows();
		if ($CekUraian < 1) {
			// echo 'uraian_kosong';
			echo json_encode(
				array(
					'pesan' => 'uraian_kosong',
					'teks' => 'Uraian belum di-isi',
					'reload_halaman' => 'no',
				)
			);
			die;
		}




		// $NoRef = NULL;
		$JobNo = $this->input->post('JobNo');
		// $TglPD = $this->input->post('TglPD');
		$Jaminan = ($this->input->post('Jaminan')) ? 1 : 0;

		$PrdAwal = $this->input->post('PrdAwal');
		$PrdAkhir = $this->input->post('PrdAkhir');
		$Minggu = $this->input->post('Minggu');
		$Alokasi = $this->input->post('Alokasi');
		$TipeForm = $this->input->post('TipeForm');
		$BuktiPendukung = ($this->input->post('BuktiPendukung', TRUE)) ?  $this->MembuatMenjadiKoma($this->input->post('BuktiPendukung', TRUE)) : '';
		$Keterangan = $this->input->post('Keterangan');

		$NoKO = $this->input->post('NoKO');
		$NoTagihan = $this->input->post('NoTagihan');
		$Nama = $this->input->post('Nama');
		$Alamat = $this->input->post('Alamat');
		$Telepon = $this->input->post('Telepon');
		$NPWP = $this->input->post('NPWP');
		$NoRek = $this->input->post('NoRek');
		$AtasNama = $this->input->post('AtasNama');
		$Bank = $this->input->post('Bank');

		// $SubTotal = $this->input->post('SubTotal');

		$SubTotal = ($this->input->post('SubTotal') == '' || $this->input->post('SubTotal') == NULL) ? 0 : $this->input->post('SubTotal');
		$SubTotal = CekDanHapusKoma($SubTotal);
		$SubTotal = (float) $SubTotal;

		$Saldo = ($this->input->post('Saldo') == '' || $this->input->post('Saldo') == NULL) ? 0 : $this->input->post('Saldo') ;
		$Saldo = CekDanHapusKoma($Saldo);
		$Saldo = (float) $Saldo;

		$TotalPermintaan = ($this->input->post('TotalPermintaan') == '' || $this->input->post('TotalPermintaan') == NULL) ? 0 : $this->input->post('TotalPermintaan');
		$TotalPermintaan = CekDanHapusKoma($TotalPermintaan);
		$TotalPermintaan = (float) $TotalPermintaan;


		$NoPJSebelumnya = ($this->input->post('NoPJSebelumnya') == '') ? NULL : $this->input->post('NoPJSebelumnya');

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$TotalInvoice = ($this->input->post('TotalInvoice')) ? CekDanHapusKoma($this->input->post('TotalInvoice')) : 0;
		$TotalInvoice = (float) $TotalInvoice;

		$TotalBayar = ($this->input->post('TotalBayar')) ? CekDanHapusKoma($this->input->post('TotalBayar')) : 0;
		$TotalBayar = (float) $TotalBayar;

		// == START FIELD PENAMBAHAN BARU PD PPN, DISURUH PAK MUL TANGGAL 23 NOVEMBER 2023
		$PD_PPN = ($this->input->post('PD_PPN')) ? 1 : 0;
		// == START FIELD PENAMBAHAN BARU PD PPN, DISURUH PAK MUL TANGGAL 23 NOVEMBER 2023

		$dataPdHdr = array(
			'TglPD' => $TglPD,
			'Jaminan' => $Jaminan,
			'Deskripsi' => $Deskripsi,
			'PrdAwal' => $PrdAwal,
			'PrdAkhir' => $PrdAkhir,
			'Minggu' => $Minggu,
			'BuktiPendukung' => $BuktiPendukung,
			'Keterangan' => $Keterangan,
			'NoTagihan' => $NoTagihan,
			'Nama' => $Nama,
			'Alamat' => $Alamat,
			'Telepon' => $Telepon,
			'NPWP' => $NPWP,
			'NoRek' => $NoRek,
			'AtasNama' => $AtasNama,
			'Bank' => $Bank,
			'NoPJSebelumnya' => $NoPJSebelumnya,
			// 'TotalPD' => $TotalPermintaan,
			'TotalPD' => $SubTotal,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'PD_PPN' => $PD_PPN,
		);

		// print_rr($dataPdHdr);
		// die;


		$this->db->trans_begin();

		$this->db->set($dataPdHdr)->where('NoPD',$NoPD)->update('PdHdr');
		$this->db->query("DELETE FROM PdDtl WHERE NoPD='$NoPD'   ");
		$this->db->query("
			INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry) 
			SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry FROM PdDtl_sementara WHERE Company='$Company' AND NoPD='$NoPD'");
		$this->db->query("DELETE FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$Company'  ");

		// == START PEMINDAHAN DATA DARI TABLE NEW INVOICE KE TABLE InvPD
		$this->db->query("DELETE FROM InvPD WHERE NoPD='$NoPD' ");
		$cekInv = $this->db->query("SELECT * FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company' AND NoKO='$NoKO' AND IsChecked!='0' ");
		if ($cekInv->num_rows()>0) {
			$this->db->query("
				INSERT INTO InvPD(JobNo,NoKO,InvNo,NoPD,PaymentAmount,UserEntry,TimeEntry) 
				SELECT JobNo, NoKO, InvNo, NoPD, PaymentAmount, '$UserEntry', '$TimeEntry' FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company' AND NoKO='$NoKO' AND IsChecked!='0' ");

			$this->db->query("DELETE FROM NewInvoice WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Company='$this->Company' AND NoKO='$NoKO' ");
		}
		// == END PEMINDAHAN DATA DARI TABLE NEW INVOICE KE TABLE InvPD

		if ($this->db->trans_status() === FALSE ) {
			$this->db->trans_rollback();
			// echo 'gagal';
			// is_pesan('error','Entry Data Failed');
			echo json_encode(
				array(
					'pesan' => 'gagal',
					'teks' => 'Entry Data Failed',
					'reload_halaman' => 'yes',
				)
			);
			// die;
		}else{
			$this->db->trans_commit();
			// echo 'berhasil';
			is_pesan('success','Updated Success');
			echo json_encode(
				array(
					'pesan' => 'berhasil',
					'teks' => 'Entry Data Sukses',
					'reload_halaman' => 'yes',

				)
			);

			// die;
		}
	}

	function AksiUpdate_Invoice_UntukEdit(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo');
		$StatusChecked = $this->input->post('StatusChecked');
		$InvNo = $this->input->post('InvNo');

		$TotalPaymentID = ($this->input->post('TotalPaymentID')) ? $this->input->post('TotalPaymentID') : 0;
		$TotalPayment = CekDanHapusKoma($TotalPaymentID);

		$Total = ($this->input->post('Total')) ? $this->input->post('Total') : 0;
		$Total = CekDanHapusKoma($Total);

		if ($TotalPayment > $Total) {
			echo 'kelebihan_bayar';
			exit;
		}

		$this->db->trans_begin();

		$this->db->query("UPDATE NewInvoice SET IsChecked='$StatusChecked', PaymentAmount='$TotalPaymentID' WHERE LedgerNo='$LedgerNo' AND InvNo='$InvNo' ");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
		} else {
			$this->db->trans_commit();
			echo 'berhasil';
		}
	}

	// ========= BERAKHIR FORM UNTUK EDIT PD ========


	function AksiPrint(){
		if (!$this->input->post()) {
			exit;
		}

		$NoPD = $this->input->post('NoPD',TRUE);

		$query = $this->db->query("SELECT ISNULL(RejectBy,NULL) AS 'RejectBy' FROM PdHdr WHERE NoPD='$NoPD' ");
		if ($query->num_rows() == 0) {
			echo 'data tidak ditemukan';
			exit;
		}

		$row = $query->row();
		if ($row->RejectBy != NULL || $row->RejectBy !='') {
			echo $NoPD.' sudah di-reject oleh '.$row->RejectBy;
			exit;
		}

		echo 'berhasil';
	}


	// == DITAMBAHKAN TOMY PADA TANGGAL 09 OKTOBER 2023 : PERBAIKAN INVOICE
	function chk_invoice_klik(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}



		$LedgerNo = $this->input->post('LedgerNo');
		$InvNo = $this->input->post('InvNo');
		$NoKO = $this->input->post('NoKO');
		$NoPD = $this->input->post('NoPD');
		$JobNo = $this->input->post('LedgerNo');
		$cek_chk =  $this->input->post('cek_chk');

		$PaymentAmount = $this->input->post('PaymentAmount');
		$PaymentAmount = CekDanHapusKoma($PaymentAmount);
		$PaymentAmount = (float) $PaymentAmount;


		$this->db->query("UPDATE NewInvoice SET PaymentAmount='$PaymentAmount', IsChecked='$cek_chk' WHERE LedgerNo='$LedgerNo'  ");
		if ($this->db->affected_rows()>0) {
			echo true;
		}else{
			echo false;
		}

	}

	function updateInvoice_Amount(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);

		$LedgerNo = $this->input->post('LedgerNo');
		$InvNo = $this->input->post('InvNo');
		$NoKO = $this->input->post('NoKO');
		$NoPD = $this->input->post('NoPD');
		$JobNo = $this->input->post('LedgerNo');
		$cek_chk =  $this->input->post('cek_chk');

		$PaymentAmount = $this->input->post('PaymentAmount');
		$PaymentAmount = CekDanHapusKoma($PaymentAmount);
		$PaymentAmount = (float) $PaymentAmount;

		$data = array(
			'LedgerNo' => $LedgerNo,
			'InvNo' => $InvNo,
			'NoKO' => $NoKO,
			'NoPD' => $NoPD,
			'JobNo' => $JobNo,
			'cek_chk' => $cek_chk,
			'LedgerNo' => $LedgerNo,
			'PaymentAmount' => $PaymentAmount,
		);

		$this->db->query("UPDATE NewInvoice SET PaymentAmount='$PaymentAmount', IsChecked='$cek_chk' WHERE LedgerNo='$LedgerNo'  ");
		if ($this->db->affected_rows()>0) {
			echo true;
		}else{
			echo false;
		}

		// print_rr($data);



	}

}

/* End of file FrmPD_Lap.php */
/* Location: ./application/controllers/Entry/PDPJ/FrmPD_Lap.php */