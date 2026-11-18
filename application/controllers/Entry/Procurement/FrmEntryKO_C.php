<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL);
class FrmEntryKO_C extends CI_Controller {

	var $Company;
	var $LblAction;
	var $LblJobNo;
	var $LblNoKO;
	var $LblTglKO;

	public function __construct()
	{
		parent::__construct();
		if ($this->session->userdata('KO_SESI')) {
			$explode_DataSesi_KO = explode('|',$this->session->userdata('KO_SESI'));
			$this->LblAction = $explode_DataSesi_KO[0];
			$this->LblJobNo = $explode_DataSesi_KO[1];
			$this->LblNoKO = $explode_DataSesi_KO[2];
			$this->LblTglKO = $explode_DataSesi_KO[3];
		}

		if ($this->LblAction=='') {
			redirect('Kontrak_po/index');
		}

		Add_Column_PPN_Persen();
		
		// -- ADD DATE : 24 JANUARI 2024
		AddField_VersiRAP_ToTable_KoDtl();
		AddField_VersiRAP_ToTable_PRDtl();
		AddField_VersiRAP_ToTable_PdDtl();
	}

	public function index()
	{
		if ($this->LblAction!='NEW') {
			redirect('Kontrak_po/index/'.$this->LblJobNo);
		}

		// redirect('Entry/Procurement/FrmEntryKO_C/tes_kontrak_baru/'.$this->LblJobNo);

		$data['judul'] = 'Summary Kontrak';
		$data['title'] = 'Summary Kontrak';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['dataVendor'] = $this->ListVendor();

		$data['JobNm'] = $this->get_JobNm();
		$data['ListRAP'] = $this->get_List_RAP();

		$data['JobNo_redirect'] = $this->LblJobNo;

		$data['js_saya'] = 'entry_view/procurement/FrmEntryKO_VIEW/js_add_KO';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/procurement/FrmEntryKO_VIEW/AddNew_KO', $data);
		$this->load->view('templates/footer');
	}

	function tes_kontrak_baru($JobNo=''){

		if ($JobNo=='') {
			redirect($_SERVER['HTTP_REFERER']);
		}
		// -- jika kategoriid adalah kontrak maka alokasi di hardcode jadi B
		$Alokasi='B';

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['JobNo_redirect'] = $JobNo;
		$data['BindRAP'] = $this->BindRAP($JobNo,$Alokasi);
		$data['dataVendor'] = $this->ListVendor();

		$data['judul'] = 'Summary Kontrak';
		$data['title'] = 'Summary Kontrak';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';
		$data['JobNm'] = $this->get_JobNm($JobNo);
		$data['TxtPotongKO'] = '';
		$data['TxtTtlPengurang'] = '0';
		$data['TxtTotal'] = '0';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		// $this->load->view('entry_view/procurement/FrmEntryKO_VIEW/tes_kontrak_baru_view', $data);
		$this->load->view('entry_view/procurement/FrmEntryKO_VIEW/AddNew_KO_terbaru', $data);
		$this->load->view('templates/footer');
	}

	function CreateNewKO_To_Database(){


		$this->db->trans_begin();

		$arrayInduk = array();
		$arrayRincianPekerjaan = array();
		$arraySubRow_RincianPekerjaan = array();

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME'); 
		$TimeEntry = date('Y-m-d H:i:s');
		$QRCode = $this->generateRandomString(6);
		$Alokasi = 'B';
		

		if ($this->input->post('mydata')) {
			$data 	= $this->input->post('mydata')[0];
			$JobNo 	= $data['JobNo'];

			$arrayInduk = array(
				// 'NoKO' 				=> $NoKO,
				'VendorId' 			=> $data['VendorId'],
				'TglKO' 			=> ($data['TglKO']=='' || $data['TglKO']==='1900-01-01') ? NULL :  $data['TglKO'],
				'JobNo' 			=> $data['JobNo'],
				'SubTotal' 			=> $data['SubTotal'],
				'PPN' 				=> $data['PPN'],
				'K3' 				=> $data['K3'],
				'SyaratTeknis' 		=> $data['SyaratTeknis'],
				'JadwalPengiriman' 	=> $data['JadwalPengiriman'],
				'JadwalPembayaran' 	=> $data['JadwalPembayaran'],
				'SyaratPembayaran' 	=> $data['SyaratPembayaran'],
				'Sanksi' 			=> $data['Sanksi'],
				'Keterangan' 		=> $data['Keterangan'],
				'KategoriId' 		=> strtoupper($data['KategoriId']),
				'BeginningTotal' 	=> $data['BeginningTotal'],
				'KategoriVendor' 	=> $data['KategoriVendor'],
				'SystemPembayaran' 	=> $data['SystemPembayaran'],
				'UserEntry' 		=> $UserEntry,
				'TimeEntry' 		=> $TimeEntry,
				'QRCode' 			=> $QRCode,
			);

			if (!empty($data['RincianPekerjaan'])) {
				// echo 'aa';	
				foreach ($data['RincianPekerjaan'] as $RincianPekerjaan) {

					$PeriodePengiriman1 = (!empty($RincianPekerjaan['subrows'])) ? NULL : $RincianPekerjaan['PeriodePengiriman1_Induk'];
					$PeriodePengiriman2 = (!empty($RincianPekerjaan['subrows'])) ? NULL : $RincianPekerjaan['PeriodePengiriman2_Induk'];
					$PeriodePembayaran1 = (!empty($RincianPekerjaan['subrows'])) ? NULL : $RincianPekerjaan['PeriodePembayaran1_Induk'];
					$PeriodePembayaran2 = (!empty($RincianPekerjaan['subrows'])) ? NULL : $RincianPekerjaan['PeriodePembayaran2_Induk'];


					$arrayRincianPekerjaan[] = array(
						// 'NoKO'					=> $NoKO,
						'NoUrut' 				=> $RincianPekerjaan['nomor'],
						'Alokasi' 				=> $Alokasi,
						'KdRAP' 				=> $RincianPekerjaan['KdRAP'],
						'Uraian' 				=> $RincianPekerjaan['Uraian'],
						'Vol' 					=> CekDanHapusKoma($RincianPekerjaan['Vol']),
						'Uom' 					=> $RincianPekerjaan['Uom'],
						'HrgSatuan' 			=> $RincianPekerjaan['HrgSatuan'],
						'UserEntry' 			=> $UserEntry,
						'TimeEntry' 			=> $TimeEntry,
						'JobNo'	 				=> $JobNo,
						'PPN_Persen' 			=> $RincianPekerjaan['PPNPersen'],
						'PPN_Nominal' 			=> $RincianPekerjaan['PPNNominal'],
						'TotalNominalPPN' 		=> $RincianPekerjaan['TotalPPNNominal'],
						'PeriodePengiriman1' 	=> $PeriodePengiriman1,
						'PeriodePengiriman2' 	=> $PeriodePengiriman2,
						'PeriodePembayaran1' 	=> $PeriodePembayaran1,
						'PeriodePembayaran2' 	=> $PeriodePembayaran2,
						'VersiRAP'  => PengecekanVersiRAP($RincianPekerjaan['KdRAP'],$JobNo,$Alokasi),
					);

					$nomorUrut_subrow = 1;
					$lastKdRAP = null;

					if (!empty($RincianPekerjaan['subrows'])) {

						foreach ($RincianPekerjaan['subrows'] as $subrow) {
							$nomorUrut_subrow++;
							$expTglPengiriman = explode(' to ',$subrow['TglPengiriman_subrow']);
							$expTglPembayaran = explode(' to ',$subrow['TglPembayaran_subrow']);

							if ($RincianPekerjaan['KdRAP'] !== $lastKdRAP) {
								$nomorUrut_subrow = 1;
								$lastKdRAP = $RincianPekerjaan['KdRAP'];
							}


							$arraySubRow_RincianPekerjaan[] = array(
								// 'NoKO' 					=> $NoKO,
								'NoUrut_subrow'  		=> $nomorUrut_subrow,
								'KdRAP_subrow' 			=> $subrow['KdRAP_subrow'],
								'NoUrut_KdRAP' 			=> $RincianPekerjaan['nomor'],
								'Uom_subrow' 			=> $subrow['Uom_subrow'],
								'Vol_subrow' 			=> $subrow['Vol_subrow'],
								'HrgSatuan_subrow' 		=> $subrow['HrgSatuan_subrow'],
								'JumlahHarga_subrow' 	=> $subrow['JumlahHarga_subrow'],
								'PeriodePengiriman1' 	=> $expTglPengiriman[0],
								'PeriodePengiriman2' 	=> $expTglPengiriman[1],
								'PeriodePembayaran1' 	=> $expTglPembayaran[0],
								'PeriodePembayaran2' 	=> $expTglPembayaran[0],
								'UserEntry' 			=> $UserEntry,
								'TimeEntry' 			=> $TimeEntry,
								'Company'				=> $this->config->item('Company'),

							);



							
						}
					}

				}
			}

		}

		// print_rr($arrayInduk);
		// echo '<hr>';
		// print_rr($arrayRincianPekerjaan);
		// echo '<hr>';
		// print_rr($arraySubRow_RincianPekerjaan);
		// die;
		// echo (!empty($arrayRincianPekerjaan)) ? 1 : 0;
		// print_rr($arrayRincianPekerjaan);

		$NoKO = $this->AssignNoKO();

		$arrayInduk['NoKO'] = $NoKO;

		$addNoKO = function($item) use ($NoKO) {
			$item['NoKO'] = $NoKO;
			return $item;
		};

		$arrayRincianPekerjaan_new = array_map($addNoKO, $arrayRincianPekerjaan);
		$arraySubRow_RincianPekerjaan_new = array_map($addNoKO, $arraySubRow_RincianPekerjaan);

		$this->db->insert('KoHdr',$arrayInduk);

		if (!empty($arrayRincianPekerjaan_new)) {
			$this->db->insert_batch('KoDtl',$arrayRincianPekerjaan_new);
		}
		
		if (!empty($arraySubRow_RincianPekerjaan_new)) {
			$this->db->insert_batch('KoDtl_subrow',$arraySubRow_RincianPekerjaan_new);
		}

		if ($this->db->trans_status()===FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('pesan'=>'gagal','redirect_ke'=>''));
		}else{
			$this->db->trans_commit();
			is_pesan('success', 'Saved!');
			echo json_encode(array('pesan'=>'berhasil','redirect_ke'=>''));
			$this->session->unset_userdata('KO_SESI');
		}


	}

	function ContractFrmUpd($JobNo,$NoKO){
		if ($JobNo=='') {
			redirect($_SERVER['HTTP_REFERER']);
		}

		$arrayInduk = array();
		$arrayRincianPekerjaan = array();

		$query = $this->db->query("SELECT * FROM KoHdr WHERE NoKO='$NoKO' ");
		$query2 = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ");

		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {

				$getVendorData = $this->Vendor_Berganti('1',$row->VendorId);
				$JobNm = $this->get_JobNm($row->JobNo);

				// print_rr($getVendorData);
				$arrayInduk = array(
					'VendorId' => $row->VendorId,
					'TxtNama' => $getVendorData['TxtNama'],
					'TxtAlamat' => $getVendorData['TxtAlamat'],
					'TxtTelepon' => $getVendorData['TxtTelepon'],
					'TxtNPWP' => $getVendorData['TxtNPWP'],
					'TxtKategori' => $getVendorData['TxtKategori'],
					'TxtUsaha' => $getVendorData['TxtUsaha'],
					'TglKO' => $row->TglKO,
					'NoKO' => $row->NoKO,
					'JobNo' => $row->JobNo,
					'JobNm' => $JobNm,
					'SystemPembayaran' => $row->SystemPembayaran,
					'KategoriVendor' => $row->KategoriVendor,
					'K3' => $row->K3,
					'SyaratTeknis' => $row->SyaratTeknis,
					'SyaratPembayaran' => $row->SyaratPembayaran,
					'JadwalPengiriman' => $row->JadwalPengiriman,
					'Sanksi' => $row->Sanksi,
					'Keterangan' => $row->Keterangan,
					'ApprovedBy' => $row->ApprovedBy,
					'PotongKO' => $row->PotongKO,
				);
			}
		}

		if ($query2->num_rows() > 0) {
			// code...
		}

		print_rr($arrayInduk);
		die;

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['JobNo_redirect'] = $JobNo;
		$data['BindRAP'] = $this->BindRAP($JobNo,$Alokasi);
		$data['dataVendor'] = $this->ListVendor();

		$data['judul'] = 'Summary Kontrak';
		$data['title'] = 'Summary Kontrak';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';
		$data['JobNm'] = $this->get_JobNm($JobNo);
		$data['TxtPotongKO'] = '';
		$data['TxtTtlPengurang'] = '0';
		$data['TxtTotal'] = '0';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		// $this->load->view('entry_view/procurement/FrmEntryKO_VIEW/tes_kontrak_baru_view', $data);
		$this->load->view('entry_view/procurement/FrmEntryKO_VIEW/AddNew_KO_terbaru', $data);
		$this->load->view('templates/footer');
	}

	function BindRAP($JobNo='',$Alokasi='') {
        // Query untuk mendapatkan data RAP berdasarkan JobNo dan Alokasi

		$JobNo = ($this->input->get('JobNo')) ? $this->input->get('JobNo') : $JobNo;
		$Alokasi = ($this->input->get('Alokasi')) ? $this->input->get('Alokasi') : $Alokasi;

		$query = $this->db->select('*')
		->from('RAP')
		->where('JobNo', $JobNo)
		->where('Alokasi', $Alokasi)
		->order_by('NoUrut', 'ASC')
		// ->limit(5)
		->get();

		$option_values = '<option value="">:: Pilih Salah Satu ::</option>';
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row):

				$KdRAP = htmlentities($row->KdRAP);
				$Tipe = htmlentities($row->Tipe);
				$Uraian = htmlentities($row->Uraian);
				$Uom = htmlentities($row->Uom);
				$Vol = htmlentities($row->Vol);
				$HrgSatuan = htmlentities($row->HrgSatuan);
				$HrgSatuan = round($HrgSatuan);

				$JumlahHarga = 0;
				$JumlahHarga = $Vol * $HrgSatuan;
				$JumlahHarga = round($JumlahHarga);

				$Vol = VolDecimal_Nol_Tiga($Vol);
				$HrgSatuan = number_format($HrgSatuan);
				$JumlahHarga = number_format($JumlahHarga);

				$disabled = ($Tipe == "Header") ? 'disabled' : '';

				$option_values .= '<option value="'.$KdRAP.'" data-Tipe="'.$Tipe.'"   data-Uraian="'.$Uraian.'" data-Uom="'.$Uom.'"  data-Vol="'.$Vol.'" data-HrgSatuan="'.$HrgSatuan.'" data-JumlahHarga="'.$JumlahHarga.'" '.$disabled.'>'.$KdRAP.' - '.$Uraian.'</option>';
			endforeach;
		}else{
			$option_values = '<option value="">:: Pilih Salah Satu ::</option>';
		}

		return $option_values;
	}

	public function get_total_and_list_potong_ko($PotongKO='') {
		$this->db->select('NoKO, (SubTotal - DiscAmount + PPN) AS TtlKO');
		$this->db->from('KoHdr');
		$this->db->where('PotongKO', $PotongKO);
		$this->db->where('ApprovedBy IS NOT NULL');
		$this->db->where('CanceledBy IS NULL');
		$this->db->where('ClosedBy IS NULL');
		$query = $this->db->get();

		$listPotongKO = '';
		$ttlKO = 0;

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$listPotongKO .= ($listPotongKO == '' ? $row->NoKO : ', ' . $row->NoKO);
				$ttlKO += $row->TtlKO;
			}
		}

		return array('listPotongKO' => $listPotongKO, 'ttlKO' => $ttlKO);
	}

	

	

	function get_List_RAP(){
		$query = $this->db->query("SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$this->LblJobNo' AND Alokasi='B' ORDER BY NoUrut ASC");
		return $query->result();
	}

	function get_JobNm($JobNo=''){
		$TxtQuery = "";
		if ($JobNo!='') {
			$TxtQuery = "WHERE JobNo='".$JobNo."'  ";
		}else{
			$TxtQuery = "WHERE JobNo='".$this->LblJobNo."'  ";
		}
		$query = $this->db->query("SELECT ISNULL(JobNm,NULL) AS JobNm FROM Job ".$TxtQuery." ");
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			return $row['JobNm'];
		}else{
			return NULL;
		}
	}

	private function ListVendor(){
		$query = $this->db->query("SELECT VendorId,VendorNm FROM Vendor");
		return $query->result();
	}

	function Vendor_Berganti($callFrom='',$VendorId=''){
		// if (!$this->input->post()) {
		// 	echo 'error';
		// 	die;
		// }

		if ($callFrom=='') {
			$VendorId = $this->input->post('VendorId');
		}

		
		$query = $this->db->query("SELECT * FROM Vendor WHERE VendorId='$VendorId' ");

		$TxtNama = "";
		$TxtAlamat = "";
		$TxtTelepon = "";
		$TxtNPWP = "";

		if ($query->num_rows() > 0) {
			$row = $query->row();


			$TxtNama = CekNULL_KhususString($row->VendorNm);
			$TxtAlamat = CekNULL_KhususString($row->Alamat);
			$TxtTelepon = CekNULL_KhususString($row->Telepon1);
			$TxtNPWP = CekNULL_KhususString($row->NPWP);

			$TxtKategori = CekNULL_KhususString($row->Kategori);
			$TxtUsaha = CekNULL_KhususString($row->BidangUsaha);

			$array = array(
				'TxtNama' 		=> $TxtNama,
				'TxtAlamat' 	=> $TxtAlamat,
				'TxtTelepon' 	=> $TxtTelepon,
				'TxtNPWP' 		=> $TxtNPWP,
				'TxtKategori' 	=> $TxtKategori,
				'TxtUsaha' 		=> $TxtUsaha,
			);

			if ($callFrom!='') {
				return $array;
			}else{
				echo json_encode($array);
			}
			

		}else{
			$array = array(
				'TxtNama' 		=> NULL,
				'TxtAlamat' 	=> NULL,
				'TxtTelepon' 	=> NULL,
				'TxtNPWP' 		=> NULL,
				'TxtKategori'	=> NULL,
				'TxtUsaha' 		=> NULL,
			);

			if ($callFrom!='') {
				return $array;
			}else{
				echo json_encode($array);
			}

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

	function AssignNoKO(){

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		$query = $this->db->query("SELECT CounterKO FROM Counter WHERE JobNo='$this->LblJobNo' AND Alokasi='B' ");
		if ($query->num_rows() > 0) {

			$row = $query->row();
			$CounterKO = ($row->CounterKO==NULL || $row->CounterKO=='') ? 1 : (int) $row->CounterKO + 1;


			$this->db->trans_begin();

			$set = array(
				'CounterKO' => $CounterKO,
				'UserEntry' => $UserEntry,
				'TimeEntry' => $TimeEntry,
			);

			$where = array(
				'JobNo' => $this->LblJobNo,
				'Alokasi' => 'B'
			);

			$this->db->set($set)->where($where)->update('Counter');

			if ($this->db->trans_status()===FALSE) {
				$this->db->trans_rollback();
				return 'error';
			}else{
				$this->db->trans_commit();
				return 'KO'.$this->LblJobNo.sprintf('%03s', $CounterKO);
			}


		}else{

			$this->db->trans_begin();
			$mydata = array(
				'JobNo' => $this->LblJobNo,
				'Alokasi' => 'B',
				'CounterKO' => 1,
				'UserEntry' => $UserEntry,
				'TimeEntry' => $TimeEntry,
			);

			$this->db->insert('Counter',$mydata);

			if ($this->db->trans_status()===FALSE) {
				$this->db->trans_rollback();
				return 'error';
			}else{
				$this->db->trans_commit();
				return 'KO'.$this->LblJobNo.'001';
			}

		}

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

	function SimpanKO(){

		if (!$this->input->post()) {
			echo json_encode(array('pesan'=>'error','redirect_ke'=>''));
			die;
		}

		if ($this->input->post('VendorId')=='') {
			echo json_encode(array('pesan'=>'Belum pilih ID.','redirect_ke'=>''));
			die;
		}

		if ($this->Validasi_SyaratPembayaran()=='') {
			echo json_encode(array('pesan'=>'Belum pilih syarat pembayaran.','redirect_ke'=>''));
			die;
		}

		$NoKO = $this->AssignNoKO();

		if ($NoKO=='error') {
			echo json_encode(array('pesan'=>'Error while generate No KO.','redirect_ke'=>''));
			die;
		}

		$TglKO = CekNULL_KhususString_Terbaru($this->input->post('TglKO'));
		$JobNo = $this->LblJobNo;
		$VendorId = $this->input->post('VendorId');
		$SubTotal = CekDanHapusKoma($this->input->post('SubTotal'));
		$PPN =  $this->input->post('TxtPPN');
		$K3 = ($this->input->post('CheckBoxK3')) ? 1 : 0;
		$SyaratTeknis = CekNULL_KhususString_Terbaru($this->input->post('TxtSyaratTeknis'));
		$JadwalPengiriman = $this->input->post('TxtJadwalPengiriman');
		$JadwalPembayaran = CekNULL_KhususString_Terbaru($this->input->post('TxtJadwalPembayaran'));
		$SyaratPembayaran = $this->Validasi_SyaratPembayaran();
		$Sanksi = CekNULL_KhususString_Terbaru($this->input->post('TxtSanksi'));
		$Keterangan = CekNULL_KhususString_Terbaru($this->input->post('TxtKeterangan'));
		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME'); 
		$TimeEntry = date('Y-m-d H:i:s');
		$KategoriId = 'KONTRAK';
		$QRCode = $this->generateRandomString(6);
		$BeginningTotal = CekDanHapusKoma($this->input->post('SubTotal'));
		$KategoriVendor = $this->input->post('KatVen');
		$SistemPembayaran = $this->input->post('SistemPembayaran');

		$data_saya = array(
			'NoKO' => $NoKO,
			'TglKO' => $TglKO,
			'JobNo' => $JobNo,
			'VendorId' => $VendorId,
			'SubTotal' => $SubTotal,
			'PPN'  => $PPN,
			'K3' => $K3,
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
			'BeginningTotal' => $BeginningTotal,
			'KategoriVendor' => $KategoriVendor,
			'SystemPembayaran' => $SistemPembayaran,
		);


		$data_KoDtl = array(); 
		if ($this->input->post('NoUrut_tr')) {

			$NoUrut_tr = $this->input->post('NoUrut_tr');
			$KdRAP_tr = $this->input->post('KdRAP_tr');
			$Uraian_tr = $this->input->post('Uraian_tr');
			$Vol_tr = CekDanHapusKoma($this->input->post('Vol_tr'));
			$Uom_tr = $this->input->post('Uom_tr');
			$HrgSatuan_tr = CekDanHapusKoma($this->input->post('HrgSatuan_tr'));

			$PPN_Nominal_tr = CekDanHapusKoma($this->input->post('PPN_Nominal_tr'));
			$T_PPN_Nominal_tr = CekDanHapusKoma($this->input->post('T_PPN_Nominal_tr'));

			$PeriodePengiriman1 = Validasi_Tgl($this->input->post('DrTgl_Pelaksanaan'));
			$PeriodePengiriman2 = Validasi_Tgl($this->input->post('SpTgl_Pelaksanaan'));

			$PeriodePembayaran1 = Validasi_Tgl($this->input->post('DrTgl_Pembayaran'));
			$PeriodePembayaran2 = Validasi_Tgl($this->input->post('SpTgl_Pembayaran'));

			$PPN_Persen_tr = CekDanHapusKoma($this->input->post('PPN_Persen_tr'));

			$Counter = 0;
			$index = 0;
			foreach($NoUrut_tr as $NoUrut_tr):
				$Counter++;
				array_push($data_KoDtl, 

					array(
						'NoKO' => $NoKO,
						'NoUrut'=>$Counter,
						'KdRAP'=>$KdRAP_tr[$index],
						'Uraian'=>$Uraian_tr[$index],
						'Vol'=> (float) $Vol_tr[$index],
						'Uom'=>$Uom_tr[$index],
						'HrgSatuan'=>(float) $HrgSatuan_tr[$index],
						'UserEntry'=> $this->session->userdata('MIS_LOGGED_NAME'),
						'TimeEntry'=> date('Y-m-d H:i:s'),
						'Alokasi'=>'B',
						// 'PPN_Check'=>$PPNCheck_Dipilih[$index],
						'PPN_Nominal'=>(float) $PPN_Nominal_tr[$index],
						'PPN_Persen' => (float) $PPN_Persen_tr[$index],
						'TotalNominalPPN' =>(float) $T_PPN_Nominal_tr[$index],

						'PeriodePengiriman1' => $PeriodePengiriman1[$index],
						'PeriodePengiriman2' => $PeriodePengiriman2[$index],
						'PeriodePembayaran1'=> $PeriodePembayaran1[$index],
						'PeriodePembayaran2'=> $PeriodePembayaran2[$index],
						'VersiRAP'  => PengecekanVersiRAP($KdRAP_tr[$index],$JobNo,'B'),
					));

				$index++;
			endforeach;
		}

		$this->db->trans_begin();

		try {

			$this->db->insert('KoHdr',$data_saya);

			if (count($this->input->post('NoUrut_tr'))>0) {
				$this->db->insert_batch('KoDtl',$data_KoDtl);
			}

			$this->db->trans_commit();
			is_pesan('success', 'Saved!');
			echo json_encode(array('pesan'=>'berhasil','redirect_ke'=>''));
			$this->session->unset_userdata('KO_SESI');


		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo json_encode(array('pesan'=>'gagal','redirect_ke'=>''));
		}




		// if (count($this->input->post('NoUrut_tr'))>0) {
		// 	$this->db->insert_batch('KoDtl',$data_KoDtl);
		// }

		// if ($this->db->trans_status()===FALSE) {
		// 	$this->db->trans_rollback();
		// 	echo json_encode(array('pesan'=>'gagal','redirect_ke'=>''));
		// }else{
		// 	$this->db->trans_commit();
		// 	is_pesan('success', 'Saved!');
		// 	echo json_encode(array('pesan'=>'berhasil','redirect_ke'=>''));
		// 	$this->session->unset_userdata('KO_SESI');
		// }
	}

	function get_KoDtl(){
		$NoKO = $this->LblNoKO;
		$JobNo = $this->LblJobNo;
		$TglKO = $this->LblTglKO;

		if ($this->LblAction=='SEE_KOADDENDUMH') {
			$query = $this->db->query("SELECT * FROM KoDtlH WHERE NoKO='$NoKO' AND TglKO='$TglKO' ORDER BY NoUrut");
		}else{
			$query = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut");
		}

		$Array_Me = array();
		$SubTotal = 0;

		if ($query->num_rows()>0) :

			foreach ($query->result() as $row) :

				$Vol = ($row->Vol==NULL || $row->Vol=='') ? 0 : $row->Vol;				

				$HrgSatuan = ($row->HrgSatuan==NULL || $row->HrgSatuan=='') ? 0:  (float) $row->HrgSatuan;
				$HrgSatuan = round($HrgSatuan,0);

				$PPN_Nominal = ($row->PPN_Nominal==NULL || $row->PPN_Nominal=='') ? 0:  (float) $row->PPN_Nominal;
				$PPN_Nominal = round($PPN_Nominal,0);

				$T_PPN_Nominal = ($row->TotalNominalPPN==NULL || $row->TotalNominalPPN=='') ? 0:  (float) $row->TotalNominalPPN;
				$T_PPN_Nominal = round($T_PPN_Nominal,0);

				$JumlahHarga = 0;
				$JumlahHarga = $Vol * $HrgSatuan;
				$JumlahHarga = (float) $JumlahHarga;
				$JumlahHarga = round($JumlahHarga);

				$PPN_Persen = ($row->PPN_Persen==NULL || $row->PPN_Persen=='') ? 0 : $row->PPN_Persen;

				$SubTotal += $JumlahHarga;

				$Array_Me[] = (object) array(
					'NoUrut' => (int) $row->NoUrut,
					'Alokasi' => $row->Alokasi,
					'KdRAP' => $row->KdRAP,
					'Uraian' => $row->Uraian,
					'Vol' => UbahDecimal_Nol_Tiga($Vol),
					'Uom' => $row->Uom,
					'HrgSatuan' => number_format($HrgSatuan),
					'JumlahHarga' =>  number_format($JumlahHarga),
					'PPN_Persen' => number_format((float)$PPN_Persen, 2, '.', ''),
					'PPN_Nominal' => number_format($PPN_Nominal),
					'TotalNominalPPN' => number_format($T_PPN_Nominal),

					'PeriodePengiriman1' => Validasi_Tgl($row->PeriodePengiriman1),
					'PeriodePengiriman2' => Validasi_Tgl($row->PeriodePengiriman2),

					'PeriodePembayaran1' => Validasi_Tgl($row->PeriodePembayaran1),
					'PeriodePembayaran2' => Validasi_Tgl($row->PeriodePembayaran2),
				);
			endforeach;

		endif;

		return array('Array_Me' => $Array_Me, 'SubTotal' => $SubTotal );
	}

	public function UpdateForm()
	{

		// redirect('Entry/Procurement/FrmEntryKO_C/ContractFrmUpd/'.$this->LblJobNo.'/'.$this->LblNoKO);
		// die;
		if ($this->LblAction=='NEW') {
			redirect('Kontrak_po/index/'.$this->LblJobNo);
		}

		if ($this->LblAction=='SEE_KOADDENDUMH') {
			$MyQuery = $this->db->query("SELECT * FROM KoHdrH WHERE NoKO='$this->LblNoKO' AND TglKO='$this->LblTglKO'");
		}else{
			$MyQuery = $this->db->query("SELECT * FROM KoHdr WHERE NoKO='$this->LblNoKO' ");
		}

		$arrayBuatan_KoHdr = array();
		if ($MyQuery->num_rows()>0) {
			foreach($MyQuery->result() as $row_KO):
				$arrayBuatan_KoHdr = (object) array(
					'Txt_NoKO' => $row_KO->NoKO,
					'Txt_JobNo' => $row_KO->JobNo,
					'Txt_TglKO' => $row_KO->TglKO,
					'Txt_VendorId' => $row_KO->VendorId,
					'Txt_SyaratPembayaran' => $row_KO->SyaratPembayaran,
					'Txt_K3' => $row_KO->K3,
					'Txt_SyaratTeknis' => $row_KO->SyaratTeknis,
					'Txt_JadwalPengiriman' => $row_KO->JadwalPengiriman,
					'Txt_JadwalPembayaran' => $row_KO->JadwalPembayaran,
					'Txt_Sanksi' => $row_KO->Sanksi,
					'Txt_Keterangan' => $row_KO->Keterangan,
					'KategoriVendor' => $row_KO->KategoriVendor,
					'SistemPembayaran' => $row_KO->SystemPembayaran,

				);
			endforeach;
		}

		$data['data_KoHdr'] = $arrayBuatan_KoHdr;

		// print_rr($data['data_KoHdr']);
		// exit;

		$Get_KoDtl = $this->get_KoDtl();
		// $data['ListKoDtl'] = json_encode($Get_KoDtl['Array_Me']);
		$data['ListKoDtl'] = $Get_KoDtl['Array_Me'];


		$data['judul'] = 'Detail Summary Kontrak';
		$data['title'] = 'Detail Summary Kontrak';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['dataVendor'] = $this->ListVendor();
		$data['JobNm'] = $this->get_JobNm();
		$data['ListRAP'] = $this->get_List_RAP();
		$data['JobNo_redirect'] = $this->LblJobNo;
		$data['js_saya'] = 'entry_view/procurement/FrmEntryKO_VIEW/js_Update_KO';


		$Txt_disabled='disabled="disabled"';
		if ($this->LblAction=='SEE_KO') {
			$Txt_disabled='disabled="disabled"';
		}else if($this->LblAction=='UPD'){
			$Txt_disabled='';
		}
		$data['Txt_disabled'] = $Txt_disabled;

		// echo $this->LblAction;
		// die; 

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/procurement/FrmEntryKO_VIEW/Update_KO', $data);
		$this->load->view('templates/footer');
	}

	function Update_Kontrak(){
		if (!$this->input->post()) {
			echo json_encode(array('pesan'=>'error','redirect_ke'=>''));
			die;
		}

		if ($this->input->post('VendorId')=='') {
			echo json_encode(array('pesan'=>'Belum pilih ID.','redirect_ke'=>''));
			die;
		}

		if ($this->Validasi_SyaratPembayaran()=='') {
			echo json_encode(array('pesan'=>'Belum pilih syarat pembayaran.','redirect_ke'=>''));
			die;
		}


		$TglKO = CekNULL_KhususString_Terbaru($this->input->post('TglKO'));
		$JobNo = $this->LblJobNo;
		$VendorId = $this->input->post('VendorId');
		$SubTotal = CekDanHapusKoma($this->input->post('SubTotal'));
		$PPN = $this->input->post('TxtPPN');
		$K3 = ($this->input->post('CheckBoxK3')) ? 1 : 0;
		$SyaratTeknis = CekNULL_KhususString_Terbaru($this->input->post('TxtSyaratTeknis'));
		$JadwalPengiriman = $this->input->post('TxtJadwalPengiriman');
		$JadwalPembayaran = CekNULL_KhususString_Terbaru($this->input->post('TxtJadwalPembayaran'));
		$SyaratPembayaran = $this->Validasi_SyaratPembayaran();
		$Sanksi = CekNULL_KhususString_Terbaru($this->input->post('TxtSanksi'));
		$Keterangan = CekNULL_KhususString_Terbaru($this->input->post('TxtKeterangan'));
		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME'); 
		$TimeEntry = date('Y-m-d H:i:s');
		$KategoriId = 'KONTRAK';
		$QRCode = $this->generateRandomString(6);
		$BeginningTotal = CekDanHapusKoma($this->input->post('SubTotal'));
		$KategoriVendor = $this->input->post('KategoriVendor');
		$SistemPembayaran = $this->input->post('SistemPembayaran');

		// print_rr($_POST);

		// echo $KategoriVendor;
		// die;


		$set_KoHdr = array(
			'SubTotal' => $SubTotal,
			'PPN' => $PPN,
			'K3' => $K3,
			'SyaratTeknis' => $SyaratTeknis,
			'JadwalPengiriman' => $JadwalPengiriman,
			'JadwalPembayaran' => $JadwalPembayaran,
			'SyaratPembayaran' => $SyaratPembayaran,
			'Sanksi' => $Sanksi,
			'Keterangan' => $Keterangan,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'TglKO' => $TglKO,
			'VendorId' => $VendorId,
			'KategoriVendor' => $KategoriVendor,
			'SystemPembayaran' => $SistemPembayaran,
		);


		$TotalNominalPPN_New = 0;

		$where_NoKO = array('NoKO' => $this->LblNoKO);

		$data_KoDtl = array(); 
		if ($this->input->post('NoUrut_tr')) {

			$NoUrut_tr = $this->input->post('NoUrut_tr');
			$KdRAP_tr = $this->input->post('KdRAP_tr');
			$Uraian_tr = $this->input->post('Uraian_tr');
			$Vol_tr = CekDanHapusKoma($this->input->post('Vol_tr'));
			$Uom_tr = $this->input->post('Uom_tr');
			$HrgSatuan_tr = CekDanHapusKoma($this->input->post('HrgSatuan_tr'));

			$PPN_Nominal_tr = CekDanHapusKoma($this->input->post('PPN_Nominal_tr'));
			$T_PPN_Nominal_tr = CekDanHapusKoma($this->input->post('T_PPN_Nominal_tr'));

			$PeriodePengiriman1 = Validasi_Tgl($this->input->post('DrTgl_Pelaksanaan'));
			$PeriodePengiriman2 = Validasi_Tgl($this->input->post('SpTgl_Pelaksanaan'));

			$PeriodePembayaran1 = Validasi_Tgl($this->input->post('DrTgl_Pembayaran'));
			$PeriodePembayaran2 = Validasi_Tgl($this->input->post('SpTgl_Pembayaran'));


			$PPN_Persen_tr = CekDanHapusKoma($this->input->post('PPN_Persen_tr'));

			$Counter = 0;
			$index = 0;
			foreach($NoUrut_tr as $NoUrut_tr):
				$Counter++;

				$TotalNominalPPN_New += (float) $PPN_Nominal_tr[$index];

				array_push($data_KoDtl, 

					array(
						'NoKO' => $this->LblNoKO,
						'NoUrut'=>$Counter,
						'KdRAP'=>$KdRAP_tr[$index],
						'Uraian'=>$Uraian_tr[$index],
						'Vol'=> (float) $Vol_tr[$index],
						'Uom'=>$Uom_tr[$index],
						'HrgSatuan'=>(float) $HrgSatuan_tr[$index],
						'UserEntry'=> $this->session->userdata('MIS_LOGGED_NAME'),
						'TimeEntry'=> date('Y-m-d H:i:s'),
						'Alokasi'=>'B',
						// 'PPN_Check'=>$PPNCheck_Dipilih[$index],
						'PPN_Nominal'=>(float) $PPN_Nominal_tr[$index],
						'TotalNominalPPN' =>(float) $T_PPN_Nominal_tr[$index],
						'PPN_Persen' => (float) $PPN_Persen_tr[$index],

						'PeriodePengiriman1' => $PeriodePengiriman1[$index],
						'PeriodePengiriman2' => $PeriodePengiriman2[$index],
						'PeriodePembayaran1'=> $PeriodePembayaran1[$index],
						'PeriodePembayaran2'=> $PeriodePembayaran2[$index],
						'VersiRAP'  => PengecekanVersiRAP($KdRAP_tr[$index],$JobNo,'B'),
					));

				$index++;
			endforeach;
		}

		$set_KoHdr['PPN'] = $TotalNominalPPN_New;

		// print_rr($set_KoHdr);
		// die;
		// print_rr($data_KoDtl);
		// die;

		$this->db->trans_begin();

		//UPDATE 
		$this->db->set($set_KoHdr)->where($where_NoKO)->update('KoHdr');

		if (count($this->input->post('NoUrut_tr'))>0) {
			$this->db->where($where_NoKO)->delete('KoDtl');
			$this->db->insert_batch('KoDtl',$data_KoDtl);
		}

		if ($this->db->trans_status()===FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('pesan'=>'Update Failed','redirect_ke'=>''));
		}else{
			$this->db->trans_commit();
			is_pesan('success', 'Update Success');
			echo json_encode(array('pesan'=>'berhasil','redirect_ke'=>''));
			$this->session->unset_userdata('KO_SESI');
		}



		// print_rr($_POST);
		// echo '<br><hr>';
		// print_rr($data_KoDtl);
	}

}

/* End of file FrmEntryKO_C.php */
/* Location: ./application/controllers/Entry/Procurement/FrmEntryKO_C.php */
