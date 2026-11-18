<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmEntryPO_C extends CI_Controller {



	var $Company;
	var $LblAction;
	var $LblJobNo;
	var $LblNoKO;
	var $LblTglKO;
	var $LblMix;


	public function __construct()
	{
		parent::__construct();
		
		is_login();
		KoDtl_Field_Pembayaran();
		Create_FieldBaru_TotalNominalPPN();

		if ($this->session->userdata('DataSesi_PURCHASE_ORDER')) {
			$explode_DataSesi_KO = explode('|',$this->session->userdata('DataSesi_PURCHASE_ORDER'));
			$this->LblAction = $explode_DataSesi_KO[0];
			$this->LblJobNo = $explode_DataSesi_KO[1];
			$this->LblNoKO = $explode_DataSesi_KO[2];
			$this->LblTglKO = $explode_DataSesi_KO[3];
			$this->LblMIX = $explode_DataSesi_KO[4];


			if (!$this->db->field_exists('PPN_Check', 'KoDtl'))
			{
				$this->db->query("ALTER TABLE KoDtl ADD PPN_Check char(1) NULL DEFAULT((0))");
			}

			if (!$this->db->field_exists('PPN_Nominal', 'KoDtl'))
			{
				$this->db->query("ALTER TABLE KoDtl ADD PPN_Nominal money NULL");
			}
		}

		// -- ADD DATE : 24 JANUARI 2024
		AddField_VersiRAP_ToTable_KoDtl();
		AddField_VersiRAP_ToTable_PRDtl();
		AddField_VersiRAP_ToTable_PdDtl();
	}

	public function index()
	{

		$data['judul'] = 'Add New Purchase Order';
		$data['title'] = 'Add New Purchase Order';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['dataVendor'] = $this->ListVendor();
		
		$data['JobNm'] = $this->get_JobNm();
		$data['ListRAP'] = $this->get_List_RAP();
		$data['ListSPR'] = $this->get_ListSPR();
		$data['ListPotongKO'] = $this->getPotong_KO();
		$data['ListAlokasi'] = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi WHERE Alokasi='B' OR Alokasi='C' OR Alokasi='L' OR Alokasi='E'")->result();

		$data['JobNo_redirect'] = $this->LblJobNo;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/procurement/FrmEntry_PO_VIEW/AddNew_PO', $data);
		$this->load->view('templates/footer');
	}

	function getPotong_KO(){
		$query = $this->db->query("SELECT NoKO FROM KoHdr WHERE JobNo='$this->LblJobNo' AND KategoriId='KONTRAK' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ORDER BY NoKO")->result();
		return $query;
	}

	function get_ListSPR(){
		$query = $this->db->query("SELECT NoSPR, UtkPekerjaan FROM PRHdr WHERE JobNo='$this->LblJobNo' AND ApprovedBy IS NOT NULL")->result();
		return $query;
	}

	function get_List_RAP(){
		$query = $this->db->query("SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$this->LblJobNo' AND Alokasi='B' ORDER BY NoUrut ASC");
		return $query->result();
	}

	function get_JobNm(){
		$query = $this->db->query("SELECT ISNULL(JobNm,NULL) AS JobNm FROM Job WHERE JobNo='$this->LblJobNo' ");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			return $row['JobNm'];
		}else{
			return NULL;
		}
	}

	function ListVendor(){
		$query = $this->db->query("SELECT VendorId,VendorNm FROM Vendor");
		return $query->result();
	}

	function Vendor_Berganti(){
		if (!$this->input->post()) {
			die;
		}

		$VendorId = $this->input->post('VendorId');
		$query = $this->db->query("SELECT * FROM Vendor WHERE VendorId='$VendorId'");
		if ($query->num_rows() > 0) {
			$row = $query->row();
			echo json_encode(array(
				'TxtNama' => $row->VendorNm,
				'TxtAlamat' => $row->Alamat,
				'TxtTelepon' => $row->Telepon1,
				'TxtNPWP' => $row->NPWP,
				'TxtUP' => $row->ContactPerson,
				'PKP' =>  ($row->PKP=='' || $row->PKP==NULL) ? 0 : $row->PKP,
				'TxtKategori' => $row->Kategori,
				'TxtUsaha' => $row->BidangUsaha,
			));
		}else{
			echo json_encode(array(
				'TxtNama' => NULL,
				'TxtAlamat' => NULL,
				'TxtTelepon' => NULL,
				'TxtNPWP' => NULL,
				'TxtUP' => NULL,
				'PKP' =>  0,
				'TxtKategori' => NULL,
				'TxtUsaha' => NULL,
			));
		}
	}

	function NoSPR_Berganti(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}

		$NoSPR = $this->input->post('NoSPR');
		$Array_PRDtl = array();
		$SubTotal = 0;

		if ($NoSPR!='' || $NoSPR!=0) {
			$query = $this->db->query("SELECT * FROM PRDtl WHERE NoSPR='$NoSPR' ORDER BY NoUrut ASC ");
			if ($query->num_rows() > 0) {

				foreach ($query->result() as $row) {
					$Vol = ($row->Vol==NULL || $row->Vol=='') ? 0 : (float) $row->Vol;
					$HrgSatuan = ($row->HrgSatuan==NULL || $row->HrgSatuan=='') ? 0: (float) 0;
					$JumlahHarga = $Vol * $HrgSatuan;
					$SubTotal += $JumlahHarga;

					$Array_PRDtl[] =  array(
						'NoUrut' => $row->NoUrut,
						// 'Alokasi' => tampilkan_select_Alokasi(''.$row->Alokasi.''),
						// 'KdRAP' => tampilkan_select_KdRAP(''.$row->Alokasi.'',''.$this->LblJobNo.''),
						'Alokasi' => $row->Alokasi,
						'KdRAP' => $row->KdRAP,
						'Uraian' => $row->Uraian,
						'Vol' => number_format($Vol),
						'Uom' => $row->Uom,
						'HrgSatuan' => number_format($HrgSatuan),
						'JumlahHarga' => number_format($JumlahHarga),
						'PPNCheck' => 0,
						'PPN_Nominal' => 0,
						'PeriodePengiriman1' =>NULL,
						'PeriodePengiriman2' => NULL,
						'PeriodePembayaran1' => NULL,
						'PeriodePembayaran2' =>NULL,
						'TotalNominalPPN' => 0,
					);
				}
			}
		}

		$data['dataPRDtl'] = $Array_PRDtl;
		$data['SubTotal'] = $SubTotal;
		$data['ListPotongKO_SPR'] = $this->getPotong_KO();
		$data['ListAlokasi_SPR'] = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi WHERE Alokasi='B' OR Alokasi='C' OR Alokasi='L' OR Alokasi='E'")->result();

		if ($NoSPR!='' || $NoSPR!=0) {
			$this->load->view('entry_view/procurement/FrmEntry_PO_VIEW/table_Order_SPR',$data);
		}else{
			$this->load->view('entry_view/procurement/FrmEntry_PO_VIEW/table_Order_NON_SPR',$data);
		}


	}

	function get_NPWP(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}
		$TxtPPN = $this->input->post('TxtPPN');
		$TxtPPN = CekDanHapusKoma($TxtPPN);
		$TxtPPN = (float) $TxtPPN;
		$JobNo = $this->LblJobNo;

		$TxtKeterangan="";
		if ($TxtPPN!=0) {
			$query = $this->db->query("SELECT NPWPName,NPWPAddress,NPWPCompany FROM Job WHERE JobNo='$JobNo' ");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$TxtKeterangan .= ($row->NPWPCompany!=NULL || $row->NPWPCompany!='') ? 'NPWP : '.$row->NPWPCompany : '';
				$TxtKeterangan .= ($row->NPWPName!=NULL || $row->NPWPName!='') ? ' - : '.$row->NPWPName : '';
				$TxtKeterangan .= ($row->NPWPAddress!=NULL || $row->NPWPAddress!='') ? ' - : '.$row->NPWPAddress : '';
			}
		}else{
			$TxtKeterangan = '';
		}

		echo $TxtKeterangan;
	}

	function get_DataPengiriman(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}
		$NoSPR = $this->input->post('NoSPR');
		$query = $this->db->query("SELECT * FROM PRHdr WHERE NoSPR='$NoSPR' ");

		if ($query->num_rows()>0) {
			$row = $query->row();

			$TxtTglKirim = CekNULL_KhususString_Terbaru($row->TglKirim);
			echo json_encode(array(
				'TxtNamaKirim' => $row->NamaKirim,
				'TxtAlamatKirim' => $row->AlamatKirim,
				'TxtTeleponKirim' => $row->TeleponKirim,
				'TxtJadwalPengiriman' => tgl_baru($TxtTglKirim),

			));
		}else{
			echo json_encode(array(
				'TxtNamaKirim' => '',
				'TxtAlamatKirim' => '',
				'TxtTeleponKirim' => '',
				'TxtJadwalPengiriman' => '',

			));
		}
	}

	function get_KdRAP(){
		$Alokasi = $this->input->post('Alokasi');

		$query = $this->db->query("SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$this->LblJobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC");
		$output = "";
		foreach($query->result() as $row){
			$output .='<option value="'.$row->KdRAP.'">'.$row->KdRAP.' - '.$row->Uraian.'</option>';
		}
		echo $output;
	}

	function Validasi_SyaratPembayaran(){
		$data_CB = "";

		$data_CB = ($this->input->post('INV')) ? ($data_CB=="") ? "INV" : $data_CB.","."INV" : $data_CB;
		$data_CB = ($this->input->post('SJ')) ? ($data_CB=="") ? "SJ" : $data_CB.","."SJ" : $data_CB;
		$data_CB = ($this->input->post('FP')) ? ($data_CB=="") ? "FP" : $data_CB.","."FP" : $data_CB;
		$data_CB = ($this->input->post('BAP')) ? ($data_CB=="") ? "BAP" : $data_CB.","."BAP" : $data_CB;
		$data_CB = ($this->input->post('PO')) ? ($data_CB=="") ? "PO" : $data_CB.","."PO" : $data_CB;
		$data_CB = ($this->input->post('BAOP')) ? ($data_CB=="") ? "BAOP" : $data_CB.","."BAOP" : $data_CB;

		return $data_CB;
	}

	function generateRandomString($length = 10) {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}

		$query = $this->db->query("SELECT QRCode FROM KOHdr WHERE QRCode='$randomString' ");
		if ($query->num_rows() > 0 ) {
			$this->generateRandomString(6);
		}else{
			return $randomString;
		}
	}

	function AssignNoKO(){

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		if ($this->LblMIX=='MIX') {
			$query = $this->db->query("SELECT CounterKOMIX FROM Counter WHERE JobNo='$this->LblJobNo' AND Alokasi='B' ");

			if ($query->num_rows() > 0) {
				$row = $query->row();
				$CounterKOMIX = ($row->CounterKOMIX==NULL || $row->CounterKOMIX=='' || $row->CounterKOMIX=='0' || $row->CounterKOMIX==0) ? 1 : $row->CounterKOMIX + 1;
				$query_Update = $this->db->query("UPDATE Counter SET CounterKOMIX='$CounterKOMIX',UserEntry='$UserEntry',TimeEntry='$TimeEntry' WHERE JobNo='$this->LblJobNo' AND Alokasi='B' ");
				return 'KO'.$this->LblJobNo.'MIX'.sprintf('%03s', $CounterKOMIX);
			}else{
				$mydata = array(
					'JobNo' => $this->LblJobNo,
					'Alokasi' => 'B',
					'CounterKOMIX' => 1,
					'UserEntry' => $UserEntry,
					'TimeEntry' => $TimeEntry,
				);
				$this->db->insert('Counter',$mydata);
				return 'KO'.$this->LblJobNo.'MIX001';
			}

		}else{

			$query = $this->db->query("SELECT CounterKO FROM Counter WHERE JobNo='$this->LblJobNo' AND Alokasi='B' ");
			if ($query->num_rows() > 0) {
				$row = $query->row();
				$CounterKO = ($row->CounterKO==NULL || $row->CounterKO=='') ? 1 : (int) $row->CounterKO + 1;
				$query_Update = $this->db->query("UPDATE Counter SET CounterKO='$CounterKO',UserEntry='$UserEntry',TimeEntry='$TimeEntry' WHERE JobNo='$this->LblJobNo' AND Alokasi='B' ");
				return 'KO'.$this->LblJobNo.sprintf('%03s', $CounterKO);
			}else{
				$mydata = array(
					'JobNo' => $this->LblJobNo,
					'Alokasi' => 'B',
					'CounterKO' => 1,
					'UserEntry' => $UserEntry,
					'TimeEntry' => $TimeEntry,
				);
				$this->db->insert('Counter',$mydata);
				return 'KO'.$this->LblJobNo.'001';
			}

		}

	}

	function SimpanPO(){
		
		if (!$this->input->post()) {

			echo json_encode(array('pesan'=>'error','redirect_ke'=>''));
			// echo 'error';
			die;
		}

		if ($this->input->post('VendorId')=='') {
			// echo 'Belum pilih ID.';
			echo json_encode(array('pesan'=>'Belum pilih ID.','redirect_ke'=>''));
			die;
		}

		if ($this->input->post('TxtAlamatKirim')=='' || $this->input->post('TxtNamaKirim')=='' || $this->input->post('TxtTeleponKirim')=='') {
			echo json_encode(array('pesan'=>'Harap lengkapi lokasi pengiriman.','redirect_ke'=>''));
			// echo 'Harap lengkapi lokasi pengiriman.';
			die;
		}

		if ($this->input->post('SistemPembayaran') == '' || $this->input->post('SistemPembayaran') == '' ) {
			echo json_encode(array('pesan' => 'Harap lengkapi Sistem Pembayaran.', 'redirect_ke' => ''));
			// echo 'Harap lengkapi lokasi pengiriman.';
			die;
		}

		if (!$this->input->post('CheckBoxRAP')) {
			echo json_encode(array('pesan'=>'RAP belum dicentang.','redirect_ke'=>''));
			// echo 'RAP belum dicentang.';
			die;
		}

		if ($this->Validasi_SyaratPembayaran()=='') {
			echo json_encode(array('pesan'=>'Belum pilih syarat pembayaran.','redirect_ke'=>''));
			// echo 'Belum pilih syarat pembayaran.';
			die;
		}

		$this->db->trans_begin();

		$NoKO = $this->AssignNoKO();

		$queryInvoice_Cek = $this->db->query("SELECT ISNULL(SUM(Total),0) AS Total FROM Invoice WHERE JobNo='$this->LblJobNo' AND NoKO='$NoKO' ");
		if ($queryInvoice_Cek->num_rows() > 0) {
			$Total = $queryInvoice_Cek->row()->Total;
			$Total = (float) $Total;

			$TxtTotal = CekDanHapusKoma($this->input->post('TxtTotal'));
			$TxtTotal = (float) $TxtTotal;

			if ($Total>$TxtTotal) {
				echo json_encode(array('pesan'=>'Total invoice existing melebihi total '.$NoKO,'redirect_ke'=>''));
				// echo 'Total invoice existing melebihi total '.$NoKO;
				die;
			}
		}

		if (count($this->input->post('NoUrut_SPR'))<1) {
			echo json_encode(array('pesan'=>'Belum input order list.','redirect_ke'=>''));
			// echo 'Belum input order list.';
			return false;
		}



		$VendorId = $this->input->post('VendorId');
		$TglKO = CekNULL_KhususString_Terbaru($this->input->post('TxtTglKO'));
		$JobNo = $this->LblJobNo;
		$SubTotal = CekDanHapusKoma($this->input->post('SubTotal'));
		$DiscPercentage = CekDanHapusKoma($this->input->post('TxtDiscPersen'));
		$KategoriVendor = $this->input->post('KatVen');
		$SistemPembayaran = $this->input->post('SistemPembayaran');

		$DiscAmount = CekDanHapusKoma($this->input->post('TxtDiscNominal'));
		$PPN = CekDanHapusKoma($this->input->post('TxtPPN'));
		$AlamatKirim = $this->input->post('TxtAlamatKirim');
		$NamaKirim = $this->input->post('TxtNamaKirim');
		$TeleponKirim = $this->input->post('TxtTeleponKirim');
		$MaterialApproval = ($this->input->post('CheckBoxMA')) ? 1 : 0;
		$RAP = ($this->input->post('CheckBoxRAP')) ? 1 : 0;
		$K3 = ($this->input->post('CheckBoxK3')) ? 1 : 0;

		$SyaratTeknis = CekNULL_KhususString_Terbaru($this->input->post('TxtSyaratTeknis'));
		
		// $JadwalPengiriman = ($this->input->post('TxtJadwalPengiriman')=='') ? NULL :  date("Y-m-d", strtotime($this->input->post('TxtJadwalPengiriman')));
		$JadwalPengiriman = $this->input->post('TxtJadwalPengiriman');

		$JadwalPembayaran = CekNULL_KhususString_Terbaru($this->input->post('TxtJadwalPembayaran'));
		$SyaratPembayaran = $this->Validasi_SyaratPembayaran();
		$Sanksi = CekNULL_KhususString_Terbaru($this->input->post('TxtSanksi'));
		$Keterangan = CekNULL_KhususString_Terbaru($this->input->post('TxtKeterangan'));
		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME'); 
		$TimeEntry = date('Y-m-d H:i:s');
		$KategoriId = 'PO';

		$QRCode = $this->generateRandomString(6);
		$OverridePPN = ($this->input->post('CbOverride')) ? 1 : 0;
		$NoSPR = CekNULL_KhususString_Terbaru($this->input->post('NoSPR'));
		$TglPengiriman = CekNULL_KhususString_Terbaru($this->input->post('TxtTglKirim'));
		$PotongKO = CekNULL_KhususString_Terbaru($this->input->post('TxtPotongKO'));

		$data_Insert_KoHdr = array(
			'NoKO' => $NoKO,
			'VendorId' => $VendorId,		
			'TglKO' => $TglKO,
			'JobNo' => $JobNo,
			'SubTotal' => $SubTotal,
			'DiscPercentage' => $DiscPercentage,
			'DiscAmount' => $DiscAmount,
			'PPN' => $PPN,
			'AlamatKirim' => $AlamatKirim,
			'NamaKirim' => $NamaKirim,
			'TeleponKirim' => $TeleponKirim,
			'MaterialApproval' => $MaterialApproval,
			'RAP' => $RAP,
			'K3' => $K3,
			'SyaratTeknis' => $SyaratTeknis,
			'KategoriVendor' => $KategoriVendor,
			'SystemPembayaran' => $SistemPembayaran,

			'SyaratTeknis' => $SyaratTeknis,
			'JadwalPengiriman' => $JadwalPengiriman,
			'JadwalPembayaran' => $JadwalPembayaran,
			'SyaratPembayaran' => $SyaratPembayaran,
			'Sanksi' => $Sanksi,
			'Keterangan' => $Keterangan,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'KategoriId' => $KategoriId,
			'QRCode' => $QRCode,
			'OverridePPN' => $OverridePPN,
			'NoSPR' => $NoSPR,
			'TglPengiriman' => $TglPengiriman,
			'PotongKO' => $PotongKO,
		);

		// print_rr($data_Insert_KoHdr);
		// die;

		$this->db->insert('KoHdr',$data_Insert_KoHdr);

		if ($this->input->post('NoUrut_SPR')) {

			$NoUrut_SPR = $this->input->post('NoUrut_SPR');
			$Alokasi_SPR = $this->input->post('Alokasi_SPR');
			$KdRAP_SPR = $this->input->post('KdRAP_SPR');
			$Uraian = $this->input->post('Uraian');
			$Vol = CekDanHapusKoma($this->input->post('Vol'));
			$Uom = $this->input->post('Uom');
			$HrgSatuan = CekDanHapusKoma($this->input->post('HrgSatuan'));
			$JumlahHarga = CekDanHapusKoma($this->input->post('JumlahHarga'));
			$PPNCheck_Dipilih = $this->input->post('PPNCheck_Dipilih');
			$PPN_Nominal = CekDanHapusKoma($this->input->post('PPN_Nominal'));
			$TotalNominalPPN = CekDanHapusKoma($this->input->post('TotalNominalPPN'));
			$PeriodePengiriman1 = $this->input->post('PeriodePengiriman1');
			$PeriodePengiriman2 = $this->input->post('PeriodePengiriman2');
			$PeriodePembayaran1 = $this->input->post('PeriodePembayaran1');
			$PeriodePembayaran2 = $this->input->post('PeriodePembayaran2');

			
			$data_KoDtl = array();        
			$index = 0;
			$Counter = 0;
			foreach($NoUrut_SPR as $NoUrut_SPR):
				$Counter++;

				array_push($data_KoDtl, 

					array(
						'NoKO' 					=> $NoKO,
						'NoUrut'				=>$Counter,
						'KdRAP'					=>$KdRAP_SPR[$index],
						'Uraian'				=>$Uraian[$index],
						'Vol'					=> (float) $Vol[$index],
						'Uom'					=>$Uom[$index],
						'HrgSatuan'				=>(float) $HrgSatuan[$index],
						'UserEntry'				=>$UserEntry,
						'TimeEntry'				=> $TimeEntry,
						'Alokasi'				=>$Alokasi_SPR[$index],
						'PPN_Check'				=>$PPNCheck_Dipilih[$index],
						'PPN_Nominal'			=>(float) $PPN_Nominal[$index],
						'TotalNominalPPN'		=>(float) $TotalNominalPPN[$index],
						'PeriodePengiriman1'	=> Validasi_Tgl($PeriodePengiriman1[$index]),
						'PeriodePengiriman2' 	=> Validasi_Tgl($PeriodePengiriman2[$index]),
						'PeriodePembayaran1'	=> Validasi_Tgl($PeriodePembayaran1[$index]),
						'PeriodePembayaran2'	=> Validasi_Tgl($PeriodePembayaran2[$index]),
						'VersiRAP' 				=> PengecekanVersiRAP($KdRAP_SPR[$index],$JobNo,$Alokasi_SPR[$index]),

					));

				$index++;
			endforeach;

			$this->db->insert_batch('KoDtl',$data_KoDtl);
			// print_rr($data_KoDtl);
		}
		// die;

		if ($this->db->trans_status()===FALSE) {
			echo json_encode(array('pesan'=>'gagal','redirect_ke'=>''));
			// echo 'gagal';
			$this->db->trans_rollback();
		}else{
			is_pesan('success', 'Saved!');

			echo json_encode(array('pesan'=>'berhasil','redirect_ke'=>''));
			// echo 'berhasil';
			$this->db->trans_commit();
			$this->session->unset_userdata('DataSesi_PURCHASE_ORDER');
		}

	}

	function PODetail($flag='',$JobNo='',$NoKO=''){
		$this->session->unset_userdata('UpdateSesi_PURCHASE_ORDER');

		$UpdateSesi_PURCHASE_ORDER = $flag.'|'.$JobNo.'|'.$NoKO;

		$this->session->set_userdata('UpdateSesi_PURCHASE_ORDER',$UpdateSesi_PURCHASE_ORDER);
		if ($this->session->userdata('UpdateSesi_PURCHASE_ORDER') ) {
			redirect('Entry/Procurement/FrmUpdatePO_C');
		}
	}



}

/* End of file FrmEntryPO_C.php */
/* Location: ./application/controllers/Entry/Procurement/FrmEntryPO_C.php */
