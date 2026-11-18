<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmUpdatePO_C extends CI_Controller {

	var $Company;
	var $LblAction;
	var $LblJobNo;
	var $LblNoKO;
	var $LblTglKO;
	var $LblMix;


	public function __construct()
	{
		parent::__construct();
		Create_FieldBaru_TotalNominalPPN();
		if ($this->session->userdata('UpdateSesi_PURCHASE_ORDER')) {
			$explode_DataSesi_KO = explode('|',$this->session->userdata('UpdateSesi_PURCHASE_ORDER'));
			$this->LblAction = $explode_DataSesi_KO[0];
			$this->LblJobNo = $explode_DataSesi_KO[1];
			$this->LblNoKO = $explode_DataSesi_KO[2];
			// $this->LblTglKO = $explode_DataSesi_KO[3];
			// $this->LblMIX = $explode_DataSesi_KO[4];
			if (!$this->db->field_exists('PPN_Check', 'KoDtl'))
			{
				$this->db->query("ALTER TABLE KoDtl ADD PPN_Check char(1) NULL DEFAULT((0))");
			}

			if (!$this->db->field_exists('PPN_Nominal', 'KoDtl'))
			{
				$this->db->query("ALTER TABLE KoDtl ADD PPN_Nominal money NULL");
			}
		}
	}

	public function index()
	{
		// echo $this->LblAction;
		// die;

		$data['judul'] = 'Purchase Order';
		$data['title'] = 'Purchase Order';
		$data['bodyclass'] = 'skin-blue-light';

		$data['dataVendor'] = $this->ListVendor();
		$data['JobNm'] = $this->get_JobNm();
		$data['ListRAP'] = $this->get_List_RAP();
		$data['ListSPR'] = $this->get_ListSPR();
		$data['ListPotongKO'] = $this->getPotong_KO();
		$data['ListAlokasi'] = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi WHERE Alokasi='B' OR Alokasi='C' OR Alokasi='L' OR Alokasi='E'")->result();

		$data['JobNo_redirect'] = $this->LblJobNo;

		
		$query_data_KO = $this->db->query("SELECT * FROM KoHdr WHERE NoKO='$this->LblNoKO'");
		$Array_KO_Buatan = array();
		if ($query_data_KO->num_rows() > 0) {
			foreach ($query_data_KO->result() as $row) {


				// $AlamatVendor = "";
				// $TeleponVendor = "";
				// $NPWPVendor = "";
				// $UpVendor = "";
				// $LblPPNVendor = "";
				// $KategoriVendor = "";
				// $UsahaVendor = "";


				// if ($row->VendorId!='' || $row->VendorId!=NULL) {
				// 	$getDataVendor = $this->db->query("SELECT * FROM Vendor WHERE VendorId='$row->VendorId'  ");
				// 	if ($getDataVendor->num_rows() > 0) {
				// 		$rowVendor = $getDataVendor->row_array();
				// 		$AlamatVendor = $rowVendor['Alamat'];
				// 		$TeleponVendor = $rowVendor['Telepon1'];
				// 		$NPWPVendor = $rowVendor['NPWP'];
				// 		$UpVendor = $rowVendor['ContactPerson'];
				// 		$LblPPNVendor = ($rowVendor['PKP']=='' || $rowVendor['PKP']==NULL || $rowVendor['PKP']==0) ? '' : 'PPN';
				// 		$KategoriVendor = $rowVendor['Kategori'];
				// 		$UsahaVendor = $rowVendor['BidangUsaha'];
				// 	}
				// }


				

				$Array_KO_Buatan = (object) array(
					'NoKO' => $row->NoKO,
					'TglKO' => $row->TglKO,
					'VendorId' => $row->VendorId,
					'NoSPR' => $row->NoSPR,
					'MaterialApproval' => $row->MaterialApproval,
					'RAP' => $row->RAP,
					'SyaratPembayaran' => $row->SyaratPembayaran,
					'AlamatKirim' => $row->AlamatKirim,
					'NamaKirim' => $row->NamaKirim,
					'TeleponKirim' => $row->TeleponKirim,
					'K3' => $row->K3,
					'SyaratTeknis' => $row->SyaratTeknis,
					'JadwalPengiriman' => $row->JadwalPengiriman,
					'JadwalPembayaran' => $row->JadwalPembayaran,
					'Sanksi' => $row->Sanksi,
					'Keterangan' => $row->Keterangan,
					'DiscPercentage' => $row->DiscPercentage,
					'DiscAmount' => $row->DiscAmount,
					'PPN' => $row->PPN,
					'OverridePPN' => $row->OverridePPN,
					'TglPengiriman' => $row->TglPengiriman,
					'PotongKO' => $row->PotongKO,
					'KategoriVendor' => $row->KategoriVendor,
					'SistemPembayaran' => $row->SystemPembayaran,
					// 'AlamatVendor' => $AlamatVendor,
					// 'TeleponVendor' => $TeleponVendor,
					// 'NPWPVendor' => $NPWPVendor,
					// 'UpVendor' => $UpVendor,
					// 'LblPPNVendor' => $LblPPNVendor,
					// 'KategoriVendor' => $KategoriVendor,
					// 'UsahaVendor' => $UsahaVendor,


				);
			}
		}

		// print_rr($Array_KO_Buatan);
		// exit;

		$data['rowKO'] = $Array_KO_Buatan;

		// print_rr($data);
		// die;

		$Txt_disabled='disabled="disabled"';
		if ($this->LblAction=='SEE_KO') {
			$Txt_disabled='disabled="disabled"';
		}else if($this->LblAction=='UPD'){
			$Txt_disabled='';
		}
		$data['Txt_disabled'] = $Txt_disabled;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/procurement/FrmUpdatePO_VIEW/form_update_po_view', $data);
		$this->load->view('templates/footer');
	}

	function ListVendor(){
		$query = $this->db->query("SELECT VendorId,VendorNm FROM Vendor");
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

	function get_List_RAP(){
		$query = $this->db->query("SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$this->LblJobNo' AND Alokasi='B' ORDER BY NoUrut ASC");
		return $query->result();
	}

	function getPotong_KO(){
		$query = $this->db->query("SELECT NoKO FROM KoHdr WHERE JobNo='$this->LblJobNo' AND KategoriId='KONTRAK' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ORDER BY NoKO")->result();
		return $query;
	}

	function get_ListSPR(){
		$query = $this->db->query("SELECT NoSPR, UtkPekerjaan FROM PRHdr WHERE JobNo='$this->LblJobNo' AND ApprovedBy IS NOT NULL")->result();
		return $query;
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

	function get_KoDtl(){
		$NoKO = $this->input->post('NoKO');
		$Array_KoDtl = array();
		$SubTotal = 0;
		$Sum_PPN_Nominal = 0;
		$Sum_TotalNominalPPN = 0;

		$query = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut ASC ");
		if ($query->num_rows() > 0) {

			foreach ($query->result() as $row) {
				$Vol = ($row->Vol==NULL || $row->Vol=='') ? 0 : (float) $row->Vol;
				$HrgSatuan = ($row->HrgSatuan==NULL || $row->HrgSatuan=='') ? 0: (float) $row->HrgSatuan;
				$PPN_Nominal = ($row->PPN_Nominal==NULL || $row->PPN_Nominal=='') ? 0: (float) $row->PPN_Nominal;
				$TotalNominalPPN = ($row->TotalNominalPPN==NULL || $row->TotalNominalPPN=='') ? 0: (float) $row->TotalNominalPPN;

				$Sum_PPN_Nominal += $PPN_Nominal;
				$Sum_TotalNominalPPN += $TotalNominalPPN;

				$JumlahHarga = $Vol * $HrgSatuan;
				$SubTotal += $JumlahHarga;

				$Array_KoDtl[] =  array(
					'NoUrut' => $row->NoUrut,
					'Alokasi' => $row->Alokasi,
					'KdRAP' => $row->KdRAP,
					'Uraian' => $row->Uraian,
					'Vol' => number_format(numberPrecision($Vol,3),3),
					'Uom' => $row->Uom,
					'HrgSatuan' => number_format(numberPrecision($HrgSatuan,2),2),
					'JumlahHarga' =>  number_format(numberPrecision($JumlahHarga,3),3),
					'PPNCheck' => $row->PPN_Check,
					'PPN_Nominal' => number_format(numberPrecision($PPN_Nominal,3),3),

					'PeriodePengiriman1' => Validasi_Tgl($row->PeriodePengiriman1),
					'PeriodePengiriman2' => Validasi_Tgl($row->PeriodePengiriman2),
					'PeriodePembayaran1' => Validasi_Tgl($row->PeriodePembayaran1),
					'PeriodePembayaran2' => Validasi_Tgl($row->PeriodePembayaran1),
					'TotalNominalPPN' => number_format(numberPrecision($TotalNominalPPN,3),3),
				);
			}
		}
		
		

		$data['data_KoDtl'] = $Array_KoDtl;
		$data['SubTotal'] = $SubTotal;
		$data['Sum_PPN_Nominal'] = $Sum_PPN_Nominal;
		$data['Sum_TotalNominalPPN'] = $Sum_TotalNominalPPN;

		$data['ListPotongKO_SPR'] = $this->getPotong_KO();
		$data['ListAlokasi_SPR'] = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi WHERE Alokasi='B' OR Alokasi='C' OR Alokasi='L' OR Alokasi='E'")->result();

		$query_data_KO = $this->db->query("SELECT ISNULL(DiscPercentage,0) AS DiscPercentage, ISNULL(DiscAmount,0) AS DiscAmount, ISNULL(PotongKO,NULL) AS PotongKO, ISNULL(PPN,0) AS PPN, OverridePPN FROM KoHdr WHERE NoKO='$this->LblNoKO'");

		$TxtDiscPersen = 0;
		$TxtDiscNominal = 0;
		$TxtPPN = 0;
		$TxtPotongKO = NULL;

		$Hitung_GrandTotal = 0;
		if ($query_data_KO->num_rows() > 0) {
			$row = $query_data_KO->row();

			$TxtDiscPersen = (float) $row->DiscPercentage;
			$TxtDiscNominal = (float) $row->DiscAmount;
			$TxtPPN = (float) $row->PPN;

			$Hitung_GrandTotal = ($SubTotal - $TxtDiscNominal) + $TxtPPN;


			$TxtDiscPersen = number_format($TxtDiscPersen, 2);
			$TxtDiscNominal = number_format(numberPrecision($TxtDiscNominal,3),3);

			$TxtPotongKO = $row->PotongKO;

			$OvveridePPN = $row->OverridePPN;

		}

		$data['TxtDiscPersen'] = $TxtDiscPersen;
		$data['TxtDiscNominal'] = $TxtDiscNominal;
		$data['TxtPPN'] = $TxtPPN;
		$data['TxtPotongKO'] = $TxtPotongKO;
		$data['OverridePPN'] = $OvveridePPN;
		// echo $OvveridePPN;
		// die;

		$Txt_disabled='disabled="disabled"';
		if ($this->LblAction=='SEE_KO') {
			$Txt_disabled='disabled="disabled"';
		}else if($this->LblAction=='UPD'){
			$Txt_disabled='';
		}
		$data['Txt_disabled'] = $Txt_disabled;
		$data['Hitung_GrandTotal'] = $Hitung_GrandTotal;

		
		// echo $data['TxtPPN'];
		// die;
		// print_rr($data);
		// die;

		
		// $data['Hitung_GrandTotal'] = ((float)$SubTotal - (float)$TxtDiscNominal ) + $TxtPPN;

		$this->load->view('entry_view/procurement/FrmUpdatePO_VIEW/table_KoDtl',$data);

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
			$this->load->view('entry_view/procurement/FrmUpdatePO_VIEW/table_Order_SPR',$data);
		}else{
			$this->load->view('entry_view/procurement/FrmUpdatePO_VIEW/table_Order_NON_SPR',$data);
		}


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

	function Aksi_UpdatePO(){

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

		if ($this->input->post('SistemPembayaran') == '') {
			echo json_encode(array('pesan' => 'Belum pilih Sistem Pembayaran.', 'redirect_ke' => ''));
			// echo 'Belum pilih syarat pembayaran.';
			die;
		}

		$NoKO = $this->LblNoKO;

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
			return false;
		}



		$VendorId = $this->input->post('VendorId');
		$TglKO = CekNULL_KhususString_Terbaru($this->input->post('TxtTglKO'));
		$JobNo = $this->LblJobNo;
		$SubTotal = CekDanHapusKoma($this->input->post('SubTotal'));
		$DiscPercentage = CekDanHapusKoma($this->input->post('TxtDiscPersen'));

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

		// $QRCode = $this->generateRandomString(6);
		$OverridePPN = ($this->input->post('CbOverride')) ? 1 : 0;
		$NoSPR = CekNULL_KhususString_Terbaru($this->input->post('NoSPR'));
		$TglPengiriman = CekNULL_KhususString_Terbaru($this->input->post('TxtTglKirim'));
		$PotongKO = CekNULL_KhususString_Terbaru($this->input->post('TxtPotongKO'));
		$KategoriVendor = $this->input->post('KategoriVendor');
		$SistemPembayaran = $this->input->post('SistemPembayaran');


		$set_KoHdr = array(
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

			'SyaratTeknis' => $SyaratTeknis,
			'JadwalPengiriman' => $JadwalPengiriman,
			'JadwalPembayaran' => $JadwalPembayaran,
			'SyaratPembayaran' => $SyaratPembayaran,
			'Sanksi' => $Sanksi,
			'Keterangan' => $Keterangan,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'KategoriId' => $KategoriId,
			// 'QRCode' => $QRCode,
			'OverridePPN' => $OverridePPN,
			'NoSPR' => $NoSPR,
			'TglPengiriman' => $TglPengiriman,
			'PotongKO' => $PotongKO,
			'KategoriVendor' => $KategoriVendor,
			'SystemPembayaran' => $SistemPembayaran,
		);

		// print_rr($set_KoHdr);
		// die;



		// print_rr($_POST);
		// echo '<hr>';
		// echo '<hr>';
		// echo '<hr>';
		// echo '<hr>';
		// print_rr($set_KoHdr);
		// if ($this->input->post('NoUrut_SPR')) {
		// 	echo 'ok';
		// }
		// die;


		$this->db->trans_begin();

		// $this->db->insert('KoHdr',$data_Insert_KoHdr);
		$this->db->set($set_KoHdr)->where('NoKO',$this->LblNoKO)->update('KoHdr');

		$this->db->query("DELETE FROM KoDtl WHERE NoKO='$this->LblNoKO' ");


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

			// == START 30 OKTOBER 2023
			$PeriodePengiriman1 = $this->input->post('PeriodePengiriman1');
			$PeriodePengiriman2 = $this->input->post('PeriodePengiriman2');
			$PeriodePembayaran1 = $this->input->post('PeriodePembayaran1');
			$PeriodePembayaran2 = $this->input->post('PeriodePembayaran2');
			// END 30 OKTOBER 2023

			$TotalNominalPPN = CekDanHapusKoma($this->input->post('TotalNominalPPN'));

			$data_KoDtl = array();        
			$index = 0;
			$Counter = 0;
			foreach($NoUrut_SPR as $NoUrut_SPR):
				$Counter++;
				array_push($data_KoDtl, 

					array(
						'NoKO' => $NoKO,
						'NoUrut'=>$Counter,
						'KdRAP'=>$KdRAP_SPR[$index],
						'Uraian'=>$Uraian[$index],
						'Vol'=> (float) $Vol[$index],
						'Uom'=>$Uom[$index],
						'HrgSatuan'=>(float) $HrgSatuan[$index],
						'UserEntry'=>$UserEntry,
						'TimeEntry'=> $TimeEntry,
						'Alokasi'=>$Alokasi_SPR[$index],
						'PPN_Check'=>$PPNCheck_Dipilih[$index],
						'PPN_Nominal'=>(float) $PPN_Nominal[$index],

						'PeriodePengiriman1' => Validasi_Tgl($PeriodePengiriman1[$index]),
						'PeriodePengiriman2' => Validasi_Tgl($PeriodePengiriman2[$index]),
						'PeriodePembayaran1'=> Validasi_Tgl($PeriodePembayaran1[$index]),
						'PeriodePembayaran2'=> Validasi_Tgl($PeriodePembayaran2[$index]),

						'TotalNominalPPN'=>(float) $TotalNominalPPN[$index],
					));

				$index++;
			endforeach;

			$this->db->insert_batch('KoDtl',$data_KoDtl);
			// echo 'masuk_ko_dtl';
		}

		if ($this->db->trans_status()===FALSE) {
			echo json_encode(array('pesan'=>'gagal','redirect_ke'=>''));
			// echo 'gagal';
			$this->db->trans_rollback();
		}else{
			is_pesan('success', 'Saved!');

			echo json_encode(array('pesan'=>'berhasil','redirect_ke'=>''));
			// echo 'berhasil';
			$this->db->trans_commit();
			$this->session->unset_userdata('UpdateSesi_PURCHASE_ORDER');
		}
	}


}

/* End of file FrmUpdatePO.php */
/* Location: ./application/controllers/Entry/Procurement/FrmUpdatePO.php */
