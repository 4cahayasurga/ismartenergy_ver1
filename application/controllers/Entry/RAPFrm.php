<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once APPPATH . 'third_party/libraryExcel/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';

class RAPFrm extends CI_Controller {

	var $Company;
	var $UrlUntukExportExcel;

	var $JobNo_Global;
	var $Alokasi_Global;
	var $Versi_Global;
	var $TipeRAP_Global;

	public function __construct()
	{
		parent::__construct();
		is_login();
		if ($this->session->userdata('PecahToken')->RAP!=1) {
			echo 'akses di-tolak';
			die;
		}


		$this->Company = $this->config->item('Company');
		PembuatanTable_RAP_Cadangan();

	}

	public function index($JobNo='',$Alokasi='',$Versi='')
	{
		$this->session->unset_userdata('SesiRAP');
		$data['judul'] = 'Entry RAP';
		$data['title'] = 'Entry RAP';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';

		$data['ListJob'] = $this->GetJob();
		$data['ListAlokasi'] = $this->GetAlokasi();
		
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['Versi'] = $Versi;

		$data['js_saya'] = 'RAPFrm_VIEW/js_main_RAP';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('RAPFrm_VIEW/main_RAP', $data);
		$this->load->view('templates/footer');
	}

	function get_ListRAP(){
		$this->session->unset_userdata('SesiRAP');
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$Versi = $this->input->post('Versi');
		$TipeRAP = $this->input->post('TipeRAP');

		$txt_query = "";
		if ($TipeRAP=='RAP') {
			$txt_query = "SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'  ORDER BY NoUrut ASC";
		}else{
			$txt_query = "SELECT * FROM RAPH WHERE JobNo='$JobNo' AND Versi='$Versi' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC";
		}

		$ArrayBuatan = array();
		$query = $this->db->query($txt_query);

		$TtlRAB = 0;
		$TtlRAP = 0;

		$sumFooter_JumlahHargaRAP = 0;
		$sumFooter_JumlahHargaRAB = 0;
		foreach ($query->result() as $row_hitung) {

			if ($row_hitung->Tipe=='Header') {
				continue;
			}

			$Vol 									= ($row_hitung->Vol=='' || $row_hitung->Vol==NULL) ? 0 : (float) $row_hitung->Vol;
			$HrgSatuan 								= ($row_hitung->HrgSatuan=='' || $row_hitung->HrgSatuan==NULL) ? 0 : (float) $row_hitung->HrgSatuan;
			$HrgRAB 								= ($row_hitung->HrgRAB=='' || $row_hitung->HrgRAB==NULL) ? 0 : (float) $row_hitung->HrgRAB;

			$TtlRAP 								= $TtlRAP + ($Vol * $HrgSatuan);
			$TtlRAB 								= $TtlRAB + ($Vol * $HrgRAB);

		}

		foreach ($query->result() as $row) {

			$BobotRAP 							= 0;
			$BobotRAB 							= 0;
			$BobotRAP_DiBagi_BobotRAB 			= 0;

			$JobNo 								= ($row->JobNo=='' || $row->JobNo==NULL) ? NULL : $row->JobNo;
			$KdRAP 								= ($row->KdRAP=='' || $row->KdRAP==NULL) ? NULL : $row->KdRAP;
			$Versi 								= CekNull_Huruf($row->Versi);
			$NoUrut 							= (int) $row->NoUrut;
			$Uraian 							= CekNull_Huruf($row->Uraian);
			$Alokasi 							= CekNull_Huruf($row->Alokasi);
			$Tipe 								= CekNull_Huruf($row->Tipe);
			$Header 							= CekNull_Huruf($row->Header);
			$Uom 								= CekNull_Huruf($row->Uom);
			$Vol 								= ($row->Vol=='' || $row->Vol==NULL) ? 0 : (float) $row->Vol;
			$HrgSatuan 							= ($row->HrgSatuan=='' || $row->HrgSatuan==NULL) ? 0 : (float) $row->HrgSatuan;
			$HrgRAB 							= ($row->HrgRAB=='' || $row->HrgRAB==NULL) ? 0 : (float) $row->HrgRAB;
			$TotalTerserap 						= ($row->TotalTerserap=='' || $row->TotalTerserap==NULL) ? 0 : (float) $row->TotalTerserap;
			$UserEntry 							= CekNull_Huruf($row->UserEntry);
			$TimeEntry 							= ($row->TimeEntry=='' || $row->TimeEntry==NULL) ? NULL : $row->TimeEntry;
			$Tahun 								= CekNull_Huruf($row->Tahun);
			$Jenis 								= CekNull_Huruf($row->Jenis);
			$LedgerNo 							= (int) $row->LedgerNo;
			$PPN 								= ($TipeRAP=='RAP') ? CekNull_Angka_Dan_Bikin_Float($row->PPN) : NULL;
			$PPN_New_Persen 					= ($TipeRAP=='RAP') ? CekNull_Huruf($row->PPN_New_Persen) : NULL;
			$PPN_New_Nominal					= ($TipeRAP=='RAP') ? CekNull_Angka_Dan_Bikin_Float($row->PPN_New_Nominal) : NULL;
			$PPN_New_Checklist 					= ($TipeRAP=='RAP') ? CekNull_Huruf($row->PPN_New_Checklist) : NULL;
			$Jumlah_HrgSatuan_kali_Vol			= ($TipeRAP=='RAP') ? CekNull_Angka_Dan_Bikin_Float($row->Jumlah_HrgSatuan_kali_Vol) : NULL;
			$Total_Jumlah_Tambah_PPN_Nominal	= ($TipeRAP=='RAP') ? CekNull_Angka_Dan_Bikin_Float($row->Total_Jumlah_Tambah_PPN_Nominal) : NULL;

			// -- WILAYAH HARGA SATUAN
			$JumlahHargaRAP					 = $Vol * $HrgSatuan;
			$BobotRAP 							= $this->calculate_percentage_for_display($JumlahHargaRAP,$TtlRAP);

			// -- WILAYAH HARGA RAB
			$JumlahHargaRAB					 	= $Vol * $HrgRAB;
			$BobotRAB							= $this->calculate_percentage_for_display($JumlahHargaRAB,$TtlRAB);


			if ($JumlahHargaRAB==0) {
				$BobotRAP_DiBagi_BobotRAB 		= 0;
			}else{
				$BobotRAP_DiBagi_BobotRAB 		= ($JumlahHargaRAP / $JumlahHargaRAB) * 100;
			}

			$ArrayBuatan[] = (object)  array(
				'JobNo' 							=> $row->JobNo,
				'KdRAP' 							=> $KdRAP,
				'Versi' 							=> $Versi,
				'NoUrut' 							=> $NoUrut,
				'Uraian' 							=> $Uraian,
				'Alokasi' 							=> $Alokasi,
				'Tipe' 								=> $Tipe,
				'Header' 							=> $Header,
				'Uom' 								=> $Uom,


				'TotalTerserap' 					=> $TotalTerserap,
				'UserEntry' 						=> $UserEntry,
				'TimeEntry' 						=> $TimeEntry,
				'Tahun'								=> $Tahun,
				'Jenis' 							=> $Jenis,
				'LedgerNo' 							=> $LedgerNo,
				'PPN' 								=> $PPN,
				'PPN_New_Persen' 					=> $PPN_New_Persen,
				'PPN_New_Nominal' 					=> $PPN_New_Nominal,
				'PPN_New_Checklist' 				=> $PPN_New_Checklist,
				'Jumlah_HrgSatuan_kali_Vol' 		=> $Jumlah_HrgSatuan_kali_Vol,
				'Total_Jumlah_Tambah_PPN_Nominal' 	=> $Total_Jumlah_Tambah_PPN_Nominal,


				'Vol' 								=> $Vol,
				'HrgSatuan' 						=> $HrgSatuan,
				'JumlahHargaRAP'					=> $JumlahHargaRAP,
				'BobotRAP'							=> $BobotRAP,

				'HrgRAB' 							=> $HrgRAB,
				'JumlahHargaRAB'					=> $JumlahHargaRAB,
				'BobotRAB' 							=> $BobotRAB,

				'BobotRAP_DiBagi_BobotRAB' 			=> round($BobotRAP_DiBagi_BobotRAB,2),
			);

		}

		$data['ArrayBuatan'] 						= $ArrayBuatan;
		$data['JobNo'] 								= $JobNo;
		$data['Alokasi'] 							= $Alokasi;
		$data['Versi'] 								= $Versi;
		$data['TipeRAP'] 							= $TipeRAP;

		$data['VersiStrip'] 						= str_replace(".", "-", $Versi);

		$data['VersiTerkini'] = '0.0';
		$get_VersiTerkini = $this->db->query("SELECT TOP 1 Versi FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'");
		if ($get_VersiTerkini->num_rows() > 0) {
			$data['VersiTerkini'] = $get_VersiTerkini->row()->Versi;
		}else{
			$data['VersiTerkini'] = '0.0';
		}

		$data['Url_ExportExcel'] = site_url('Entry/RAPFrm/myexport_excel/'.$JobNo.'/'.$Alokasi.'/'.$data['VersiStrip'].'/'.$TipeRAP);

		$this->load->view('RAPFrm_VIEW/table_RAP', $data);
	}

	function CreateNewRAP($JobNo='',$Alokasi='',$Versi='',$TipeRAP='',$flagAksi='NEW'){
		if($JobNo=='' || $Alokasi=='' || $Versi==''){
			redirect('Entry/RAPFrm/');
		}

		// -- Ubah strip ('-') versi menjadi titik
		$Versi = str_replace("-", ".", $Versi);

		if ($TipeRAP!='RAP') {
			$getVersi = $this->db->query("SELECT TOP 1 Versi FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ")->row()->Versi;
			is_pesan('danger','Hanya bisa tambah data untuk RAP Versi '.$getVersi);
			redirect('Entry/RAPFrm/index/'.$JobNo.'/'.$Alokasi);
			die;
		}

		$data['JobNo'] 		= $JobNo;
		$data['Alokasi'] 	= $Alokasi;
		$data['KeteranganAlokasi'] = $this->dapatkan_Alokasi($Alokasi);
		$data['Versi'] 		= $Versi;
		$data['JobNm'] 		= $this->getJobNm($JobNo);
		$data['TipeRAP'] 	= $TipeRAP;
		$data['flagAksi'] 	= $flagAksi;

		$data['judul'] 		= 'RAP - '.$data['JobNm'];
		$data['title'] 		= 'RAP - '.$data['JobNm'];
		$data['bodyclass'] 	= 'sidebar-collapse skin-blue-light';

		$data['js_saya'] 	= 'RAPFrm_VIEW/js_Form_Create_RAP';

		$data['getRAP_ForHeader'] = $this->getRAP_ForHeader($JobNo,$Alokasi,$Versi,$TipeRAP);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('RAPFrm_VIEW/Form_Create_RAP', $data);
		$this->load->view('templates/footer');
	}

	

	function SaveRAP(){
		if (!$this->input->post()) {
			die;
		}

		$this->session->unset_userdata('LabelBuatan_KdRAP');
		$this->session->unset_userdata('LabelBuatan_Aksi');

		$JobNo 		= ($this->input->post('JobNo')) ? $this->input->post('JobNo') : NULL; 
		$Alokasi 	= ($this->input->post('Alokasi')) ? $this->input->post('Alokasi') : NULL;
		$Versi 		= ($this->input->post('Versi')) ? $this->input->post('Versi') : NULL;

		$Tipe 		= $this->input->post('Tipe');
		$Header 	= $this->input->post('Header');
		$KdRAP 		= ($this->input->post('KdRAP')) ? $this->input->post('KdRAP') : NULL;
		$Uraian 	= ($this->input->post('Uraian')) ? $this->input->post('Uraian') : NULL;
		$Vol 		= ($this->input->post('Vol')) ? CekDanHapusKoma($this->input->post('Vol')) : 0;
		$Uom 		= ($this->input->post('Uom')) ? $this->input->post('Uom') : NULL;
		$HrgSatuan 	= ($this->input->post('HrgSatuan')) ? CekDanHapusKoma($this->input->post('HrgSatuan')) : 0;
		$HrgRAB 	= ($this->input->post('HrgRAB')) ? CekDanHapusKoma($this->input->post('HrgRAB')) : 0;
		$flagAksi 	= ($this->input->post('flagAksi')) ? $this->input->post('flagAksi') : NULL;

		$Vol 		= (float) $Vol;
		$HrgSatuan 	= (float) $HrgSatuan;
		$HrgRAB 	= (float) $HrgRAB;

		$UserEntry 	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry 	= date('Y-m-d H:i:s');

		if ($Tipe==NULL || $Header==NULL) {
			die;
		}

		$KdRAP = trim($KdRAP);
		$KdRAP = HapusKutip_DuaDanSatu($KdRAP);
		$KdRAP = HapusTitikDiAkhirString($KdRAP);


		$KdRAPHeader_Sementara = '';
		$labelBuatanAksi = '';
		$hasil = 1;

		if ($Header!='0' && $flagAksi=='NEW') {


				// start cek kode rap, sudah ada maka tidak bisa
			$queryCek_KdRAP = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND KdRAP='$KdRAP' AND Alokasi='$Alokasi' ");
			if ($queryCek_KdRAP->num_rows() > 0) :
				echo 'Kode RAP '.$KdRAP.' sudah ada.';
				die;
			endif;
				// start cek kode rap, sudah ada maka tidak bisa

			if ($Tipe == 'Detail') {
					// jika tipe detail
				$RsFind = $this->db->query("SELECT TOP 1 KdRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Detail' AND Header='$Header' ORDER BY NoUrut DESC");

				if ($RsFind->num_rows() > 0) {
					$KdRAPHeader_Sementara = $RsFind->row()->KdRAP; 
					$labelBuatanAksi = 'INS1';
					$flagAksi = 'INS1';
				}else{
					$RsFindX = $this->db->query("SELECT KdRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP='$Header' ");
					if ($RsFindX->num_rows() > 0) {
						$KdRAPHeader_Sementara = $RsFindX->row()->KdRAP; 
						$labelBuatanAksi = 'INS1';
						$flagAksi = 'INS1';
					}
				}
			}else{
					// jika tipe bukan detail
				$RsFind = $this->db->query("SELECT TOP 1 KdRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Header='$Header' ORDER BY NoUrut DESC");
				if ($RsFind->num_rows() > 0) {
						// Jika header punya detail maka ambil header terakhir
					$KdRAPHeader_Sementara = $RsFind->row()->KdRAP; 
					$labelBuatanAksi = 'INS1';
					$flagAksi = 'INS1';
				}else{
						// Jika header tidak punya detail maka ambil kdrap 
					$RsFindX = $this->db->query("SELECT KdRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP='$Header'  ");
					if ($RsFindX->num_rows() > 0) {
						$KdRAPHeader_Sementara = $RsFindX->row()->KdRAP; 
						$labelBuatanAksi = 'INS1';
						$flagAksi = 'INS1';
					}
				}

			}

		}

		if ($flagAksi=='NEW') {
			$RsID = $this->db->query("SELECT TOP 1 NoUrut FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut DESC");
			if ($RsID->num_rows() > 0) {
				$hasil = $RsID->row()->NoUrut + 1;
			}
		}else{
			$RsID1 = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP='$KdRAPHeader_Sementara' ");
			if ($RsID1->num_rows() > 0) {
				$row_RsID1 = $RsID1->row();
				$TxtQuery = "";
				if ($flagAksi=='INS') {
					$TxtQuery = "SELECT * FROM  RAP WHERE JobNo='$row_RsID1->JobNo' AND Alokasi='$row_RsID1->Alokasi' AND NoUrut>='$row_RsID1->NoUrut' ORDER BY NoUrut";
				}else if ($flagAksi=='INS1'){
					$TxtQuery = "SELECT * FROM  RAP WHERE JobNo='$row_RsID1->JobNo' AND Alokasi='$row_RsID1->Alokasi' AND NoUrut>'$row_RsID1->NoUrut' ORDER BY NoUrut";
				}


				$query_RsID2 = $this->db->query($TxtQuery);
				if ($query_RsID2->num_rows() > 0) {
					foreach ($query_RsID2->result() as $row_RsID2) {
						$MyNoUrut = (int) $row_RsID2->NoUrut + 1;
						$MyJobNo = $row_RsID2->JobNo;
						$MyAlokasi = $row_RsID2->Alokasi;
						$MyKdRAP = $row_RsID2->KdRAP;

						$set_dataRAP = array(
							'NoUrut' => (int) $row_RsID2->NoUrut + 1,
							'JobNo' => $row_RsID2->JobNo,
							'Alokasi' => $row_RsID2->Alokasi,
							'KdRAP' => $row_RsID2->KdRAP,
						);

						$this->db->set($set_dataRAP)->where(array('JobNo' =>$MyJobNo, 'Alokasi' =>$MyAlokasi, 'KdRAP' =>$MyKdRAP, 'NoUrut' =>$MyNoUrut  ))->update('RAP');

					}
				}

				$hasil = ($flagAksi=='INS1') ? (int) $row_RsID1->NoUrut + 1 : (int) $row_RsID1->NoUrut;
			}
		}

		$data_InsertRAP = array(
			'JobNo' 	=> $JobNo,
			'KdRAP' 	=> $KdRAP,
			'Versi' 	=> $Versi,
			'NoUrut' 	=> $hasil,
			'Uraian' 	=> $Uraian,
			'Tipe' 		=> $Tipe,
			'Header' 	=> $Header,
			'Uom' 		=> strtoupper($Uom),
			'Vol' 		=> ($Tipe=='Header') ? 1 : $Vol,
			'HrgSatuan' => $HrgSatuan,
			'Alokasi' 	=> $Alokasi,
			'UserEntry'	=> $UserEntry,
			'TimeEntry'	=> $TimeEntry,
			'HrgRAB'	=> $HrgRAB,
		);

		// -- START EKSEKUSI KE DATABASE
		try {

			$this->db->insert('RAP',$data_InsertRAP);
			if ($data_InsertRAP['Tipe']=='Detail') {
				$this->HitRecursive($data_InsertRAP['Header'],$data_InsertRAP['JobNo'],$data_InsertRAP['Alokasi']);
			}
			echo 'berhasil';
			$this->db->trans_commit();


		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}


	}


	function UpdateRAP($JobNo='',$Alokasi='',$Versi='',$TipeRAP='',$flagAksi='',$LedgerNo=''){
		if($JobNo=='' || $Alokasi=='' || $Versi==''){
			redirect('Entry/RAPFrm/');
		}

		// -- Ubah strip ('-') versi menjadi titik
		$Versi = str_replace("-", ".", $Versi);

		$data['JobNo'] 		= $JobNo;
		$data['Alokasi'] 	= $Alokasi;
		$data['KeteranganAlokasi'] = $this->dapatkan_Alokasi($Alokasi);
		$data['Versi'] 		= $Versi;
		$data['JobNm'] 		= $this->getJobNm($JobNo);
		$data['TipeRAP'] 	= $TipeRAP;
		$data['flagAksi'] 	= $flagAksi;
		$data['LedgerNo'] 	= $LedgerNo;


		$data['judul'] 		= 'RAP - '.$data['JobNm'];
		$data['title'] 		= 'RAP - '.$data['JobNm'];
		$data['bodyclass'] 	= 'sidebar-collapse skin-blue-light';
		$data['js_saya'] 	= 'RAPFrm_VIEW/js_Form_Update_RAP';

		$data['getRAP_ForHeader'] = $this->getRAP_ForHeader($JobNo,$Alokasi,$Versi,$TipeRAP);

		$TxtQuery = "";
		if ($TipeRAP=='RAP') {
			$TxtQuery = "SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND LedgerNo='$LedgerNo'";
		}else{
			$TxtQuery = "SELECT * FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND LedgerNo='$LedgerNo' AND Versi='$Versi' ";
		}

		$query_DataRAP = $this->db->query($TxtQuery)->row();
		$data['dataRAP'] = $query_DataRAP;

		$data['btn_dimatikan'] = '';
		if ($TipeRAP!='RAP') {
			$data['btn_dimatikan'] = 'disabled';
		}else{
			$data['btn_dimatikan'] = '';
		}

		$data['KdRAP_dimatikan'] = ''; 

		// Disable KdRAP jika sudah dipakai PD
		$query_PD = $this->db->query("SELECT * FROM PdHdr A JOIN PdDtl B ON A.NoPD=B.NoPD WHERE A.JobNo='$JobNo' AND A.Alokasi='$Alokasi' AND A.RejectBy IS NULL AND B.KdRAP='$query_DataRAP->KdRAP' ");
		if ($query_PD->num_rows()>0) {
			$data['KdRAP_dimatikan'] = 'disabled="disabled"'; 
		}

		$query_KO = $this->db->query("SELECT * FROM KoHdr A JOIN KoDtl B ON A.NoKO=B.NoKO WHERE A.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND B.KdRAP='$query_DataRAP->KdRAP'");
		if ($query_KO->num_rows()>0) {
			$data['KdRAP_dimatikan'] = 'disabled="disabled"'; 
		}

		// Disable KdRAP jika sudah dipakai SPR di table PrDtl
		$query_SPR = $this->db->query("SELECT * FROM PrHdr A JOIN PrDtl B ON A.NoSPR=B.NoSPR WHERE A.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND B.KdRAP='$query_DataRAP->KdRAP' ");
		if ($query_SPR->num_rows()>0) {
			$data['KdRAP_dimatikan'] = 'disabled="disabled"'; 
		}

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('RAPFrm_VIEW/Form_Update_RAP', $data);
		$this->load->view('templates/footer');
	}

	function Aksi_UpdateRAP(){
		if (!$this->input->post()) {
			die;
		}

		$this->session->unset_userdata('LabelBuatan_KdRAP');
		$this->session->unset_userdata('LabelBuatan_Aksi');

		// print_rr($_POST);
		// die;

		$JobNo 		= ($this->input->post('JobNo')) ? $this->input->post('JobNo') : NULL; 
		$Alokasi 	= ($this->input->post('Alokasi')) ? $this->input->post('Alokasi') : NULL;
		$Versi 		= ($this->input->post('Versi')) ? $this->input->post('Versi') : NULL;

		$Tipe 		= $this->input->post('Tipe');
		$Header 	= $this->input->post('Header');
		$KdRAP 		= ($this->input->post('KdRAP')) ? $this->input->post('KdRAP') : NULL;
		$Uraian 	= ($this->input->post('Uraian')) ? $this->input->post('Uraian') : NULL;
		$Vol 		= ($this->input->post('Vol')) ? CekDanHapusKoma($this->input->post('Vol')) : 0;
		$Uom 		= ($this->input->post('Uom')) ? $this->input->post('Uom') : NULL;
		$HrgSatuan 	= ($this->input->post('HrgSatuan')) ? CekDanHapusKoma($this->input->post('HrgSatuan')) : 0;
		$HrgRAB 	= ($this->input->post('HrgRAB')) ? CekDanHapusKoma($this->input->post('HrgRAB')) : 0;
		$flagAksi 	= ($this->input->post('flagAksi')) ? $this->input->post('flagAksi') : NULL;

		$Vol 		= (float) $Vol;
		$HrgSatuan 	= (float) $HrgSatuan;
		$HrgRAB 	= (float) $HrgRAB;

		$UserEntry 	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry 	= date('Y-m-d H:i:s');

		$KdRAP_Lama 	= ($this->input->post('KdRAP_Lama')) ? $this->input->post('KdRAP_Lama') : NULL;
		$TipeRAP 		= ($this->input->post('TipeRAP')) ? $this->input->post('TipeRAP') : NULL;

		$queryCek_KdRAP = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND KdRAP='$KdRAP' AND Alokasi='$Alokasi' AND KdRAP!='$KdRAP_Lama' ");

		$KdRAP = trim($KdRAP);
		$KdRAP = HapusKutip_DuaDanSatu($KdRAP);
		$KdRAP = HapusTitikDiAkhirString($KdRAP);


		if ($Tipe==NULL || $Header==NULL) {
			die;
		}

		if ($TipeRAP!='RAP') {
			echo 'hanya bisa update versi '.$Versi;
			die;
		}

		// Disable KdRAP jika sudah dipakai PD
		$query_PD = $this->db->query("SELECT * FROM PdHdr A JOIN PdDtl B ON A.NoPD=B.NoPD WHERE A.JobNo='$JobNo' AND A.Alokasi='$Alokasi' AND A.RejectBy IS NULL AND B.KdRAP='$KdRAP_Lama' AND VersiRAP='$Versi' ");
		if ($query_PD->num_rows()>0) {
			echo 'Tidak bisa update RAP.<br />Sudah dipakai untuk Permintaan Dana.';
			die;
		}

		$query_KO = $this->db->query("SELECT * FROM KoHdr A JOIN KoDtl B ON A.NoKO=B.NoKO WHERE A.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND B.KdRAP='$KdRAP_Lama' AND VersiRAP='$Versi'");
		if ($query_KO->num_rows()>0) {
			echo 'Tidak bisa update RAP.<br />Sudah dipakai untuk Kontrak/PO.';
			die;
		}

		// Disable KdRAP jika sudah dipakai SPR di table PrDtl
		$query_SPR = $this->db->query("SELECT * FROM PrHdr A JOIN PrDtl B ON A.NoSPR=B.NoSPR WHERE A.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND B.KdRAP='$KdRAP_Lama' AND VersiRAP='$Versi' ");
		if ($query_SPR->num_rows()>0) {
			echo 'Tidak bisa update RAP.<br />Sudah dipakai untuk Permintaan Material.';
			die;
		}

		if ($Tipe=='Header') {
			$query_Cek = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Detail' AND Header='$KdRAP_Lama' ");
			if ($query_Cek->num_rows() > 0) {
				echo 'Tidak bisa update RAP Tipe Header. Masih ada detail.';
				die;
			}
		}

		if ($queryCek_KdRAP->num_rows() > 0) :
			echo 'Kode RAP '.$KdRAP.' sudah ada.';
			die;
		endif;




		// -- START EKSEKUSI KE DATABASE
		try {

			if ($Tipe=='Detail') {
				$Set_RAP = array(
					'Uraian'		=> $Uraian,
					'Uom'			=> strtoupper($Uom),
					'Vol'			=> ($Tipe=='Header') ? 1 : $Vol,
					'HrgSatuan'		=> $HrgSatuan,
					'UserEntry'		=> $UserEntry,
					'TimeEntry'		=> $TimeEntry,
					'HrgRAB'		=> $HrgRAB,
					// 'KdRAP'			=> trim($KdRAP),
				);

				$where_RAP = array('JobNo' => $JobNo, 'KdRAP' => $KdRAP_Lama, 'Alokasi' => $Alokasi );

				$this->db->set($Set_RAP)->where($where_RAP)->update('RAP');

				if ($Tipe=='Detail') {
					$this->HitRecursive($Header,$JobNo,$Alokasi);
				}

				echo 'berhasil';
				$this->db->trans_commit();

			}else{
				$Set_RAP = array(
					'Uraian'		=> $Uraian,
					'UserEntry'		=> $UserEntry,
					'TimeEntry'		=> $TimeEntry,
					// 'KdRAP'			=> trim($KdRAP),
				);

				$where_RAP = array('JobNo' => $JobNo, 'KdRAP' => $KdRAP_Lama, 'Alokasi' => $Alokasi );

				$this->db->set($Set_RAP)->where($where_RAP)->update('RAP');
				echo 'berhasil';
				$this->db->trans_commit();
			}

			
			


		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}

	}

	function AksiSimpan_Versi(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);
		// die;

		$PerubahanVersi 	= $this->input->post('PerubahanVersi');
		$VersiTerkini		= $this->input->post('VersiTerkini');
		$RubahVersiMenjadi 	= $this->input->post('RubahVersiMenjadi');
		$JobNo 				= $this->input->post('JobNo');
		$Alokasi 			= $this->input->post('Alokasi');

		// -- Cek di table RAP
		$query_satu = $this->db->query("SELECT TOP 1 Versi FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$RubahVersiMenjadi' ");
		if ($query_satu->num_rows()>0) {
			echo 'Versi '.$query_satu->row()->Versi.' sudah ada.';
			die;
		}

		// -- Cek di table RAPH
		$query_dua = $this->db->query("SELECT TOP 1 Versi FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$RubahVersiMenjadi' ");
		if ($query_dua->num_rows()>0) {
			echo 'Versi '.$query_dua->row()->Versi.' sudah ada.';
			die;
		}

		try {

			$UserEntry = $this->session->userdata('UserEntry');
			$TimeEntry = date('Y-m-d H:i:s');

			// $this->db->query("INSERT INTO RAPH SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'");

			$this->db->query("
				INSERT INTO RAPH (JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,UserEntry,TimeEntry,Tahun,Jenis,PPN,PPN_New_Persen,PPN_New_Nominal,PPN_New_Checklist,Jumlah_HrgSatuan_kali_Vol,Total_Jumlah_Tambah_PPN_Nominal)
				SELECT JobNo,KdRAP,Versi,NoUrut,Uraian,Alokasi,Tipe,Header,Uom,Vol,HrgSatuan,HrgRAB,TotalTerserap,UserEntry,TimeEntry,Tahun,Jenis,PPN,PPN_New_Persen,PPN_New_Nominal,PPN_New_Checklist,Jumlah_HrgSatuan_kali_Vol,Total_Jumlah_Tambah_PPN_Nominal FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ");

			$this->db->query("UPDATE RAP SET Versi='$RubahVersiMenjadi', UserEntry='$UserEntry',TimeEntry='$TimeEntry' WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ");


			// echo 'berhasil';
			is_pesan('success','Updated!');
			$this->db->trans_commit();
		} catch (Exception $e) {
			is_pesan('danger','Failed!');
			// echo 'gagal';
			$this->db->trans_rollback();
		}

	}

	function HitRecursive($KdRAP,$JobNo,$Alokasi){
		try {
			$query_satu = $this->db->query("SELECT COALESCE(SUM(HrgSatuan*Vol),0) AS JumlahHarga_RAP , COALESCE(SUM(HrgRAB*Vol),0) AS JumlahHarga_RAB FROM RAP WHERE JobNo='$JobNo' AND Header='$KdRAP' AND Alokasi='$Alokasi'");

			$Totalv 		= 0;
			$TotalRAB 		= 0;

			if ($query_satu->num_rows() > 0) {
				$row_satu 	= $query_satu->row_array();
				$Totalv 	= (float) $row_satu['JumlahHarga_RAP'];
				$TotalRAB 	= (float) $row_satu['JumlahHarga_RAB'];
			}

			$set_satu = array(
				'HrgSatuan' => $Totalv,
				'HrgRAB' 	=> $TotalRAB,
			);

			$where_satu = array(
				'JobNo' 	=> $JobNo,
				'KdRAP' 	=> $KdRAP,
				'Alokasi' 	=> $Alokasi,
			);

			$query_Update 	= $this->db->set($set_satu)->where($where_satu)->update('RAP');


			// --Pencarian Header
			$Header = '0';
			$query_Header 	= $this->db->query("SELECT TOP 1 Header FROM RAP WHERE JobNo='$JobNo' AND KdRAP='$KdRAP' AND Alokasi='$Alokasi' ");
			if ($query_Header->num_rows() > 0) {
				$row_Header = $query_Header->row();
				$Header 	= $row_Header->Header;
			}

			if ($Header=='0') {
				// echo 'berhasil';
				// is_pesan('success','Tersimpan');
				// die();
			}else{
				$this->HitRecursive($Header,$JobNo,$Alokasi);
			}
			$this->db->trans_commit();
		} catch (Exception $e) {
			$this->db->trans_rollback();

		}


	}

	function dapatkan_Alokasi($Alokasi=''){
		$txt = "";
		if ($Alokasi!='') {
			$txt = " WHERE Alokasi='".$Alokasi."' ";
		}else{
			$txt = "";
		}

		return $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi ".$txt." ")->row()->Keterangan;
	}

	function getRAP_ForHeader($JobNo='',$Alokasi='',$Versi='',$TipeRAP=''){
		$TxtQuery = "";
		if ($TipeRAP=='RAP') {
			$TxtQuery = "SELECT KdRAP,Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header' ORDER BY NoUrut ASC";
		}else{
			$TxtQuery = "SELECT KdRAP,Uraian FROM RAPH WHERE JobNo='$JobNo' AND Versi='$Versi' AND Alokasi='$Alokasi' AND Tipe='Header' ORDER BY NoUrut ASC";
		}

		$MyQuery = $this->db->query($TxtQuery)->result();
		return $MyQuery;
	}

	function getJobNm($JobNo){
		$query = $this->db->query("SELECT ISNULL(JobNm,NULL) AS 'JobNm' FROM Job WHERE JobNo='$JobNo'  ")->row();
		return $query->JobNm;
	}

	function getVersi(){
		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		$myarray = array();

		// == get Versi RAP
		$query = $this->db->query("SELECT TOP 1 Versi FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ");
		if ($query->num_rows()>0) {
			foreach ($query->result() as $row) {
				$myarray[] = array('Versi' => $row->Versi,'TipeRAP' => 'RAP');
			}
		}else{
			$myarray[] = array('Versi' => '0.0','TipeRAP' => 'RAP');
		}


		// == get Versi RAP H
		$queryRAPH = $this->db->query("SELECT DISTINCT Versi FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY Versi DESC");
		if ($queryRAPH->num_rows()>0) {
			foreach ($queryRAPH->result() as $row) {
				$myarray[] = array('Versi' => $row->Versi,'TipeRAP' => 'RAPH');
			}
		}

		echo json_encode($myarray);
	}

	function format_percentage($percentage, $precision = 2) {
		return round($percentage, $precision);
	}

	function calculate_percentage($number, $total) {
		if ($total == 0) {
			return 0;
		}

		return ($number / $total) * 100;
	}

	function calculate_percentage_for_display($number, $total) {
		return $this->format_percentage($this->calculate_percentage($number, $total));
	}

	private function GetAlokasi(){
		$AksesAlokasi = $this->session->userdata('PecahToken')->AksesAlokasi;
		$queryAlokasi = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi ");
		$ArrayAlokasi = array();
		foreach($queryAlokasi->result_array() as $row ):

			if (!preg_match('/\b' . $row['Alokasi'] . '\b/', $AksesAlokasi)) {
				continue;
			}

			$ArrayAlokasi[] = (object) array('Alokasi' => $row['Alokasi'], 'Keterangan' => $row['Keterangan']);
		endforeach;

		return $ArrayAlokasi;
	}

	private function GetJob(){

		$AksesJob = $this->session->userdata('PecahToken')->AksesJob;
		$QueryJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$this->Company' ");
		$ArrayJob = array();
		foreach($QueryJob->result_array() as $myjob ):

			if (!preg_match('/\b' . $myjob['JobNo'] . '\b/', $AksesJob)) {
				continue;
			}
			$ArrayJob[] = (object) array('JobNo' => $myjob['JobNo'], 'JobNm' => $myjob['JobNm']);
		endforeach;

		return $ArrayJob;
	}

	function CekData_RAP(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);
		// die;

		$JobNo 		= $this->input->post('JobNo');
		$Alokasi 	= $this->input->post('Alokasi');
		$KdRAP 		= trim($this->input->post('KdRAP'));
		$LedgerNo 	= $this->input->post('LedgerNo');
		$Versi 		= $this->input->post('Versi');
		$TipeRAP 	= $this->input->post('TipeRAP');
		$Tipe 		= $this->input->post('Tipe');

		// Disable KdRAP jika sudah dipakai PD
		$query_PD = $this->db->query("SELECT * FROM PdHdr A JOIN PdDtl B ON A.NoPD=B.NoPD WHERE A.JobNo='$JobNo' AND A.Alokasi='$Alokasi' AND A.RejectBy IS NULL AND B.KdRAP='$KdRAP' ");

		if ($query_PD->num_rows()>0) {
			echo 'Tidak bisa hapus RAP.<br />Sudah dipakai untuk Permintaan Dana.';
			die;
		}

		$query_KO = $this->db->query("SELECT * FROM KoHdr A JOIN KoDtl B ON A.NoKO=B.NoKO WHERE A.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND B.KdRAP='$KdRAP'");
		if ($query_KO->num_rows()>0) {
			echo 'Tidak bisa hapus RAP.<br />Sudah dipakai untuk Kontrak/PO.';
			die;
		}

		// Disable KdRAP jika sudah dipakai SPR di table PrDtl
		$query_SPR = $this->db->query("SELECT * FROM PrHdr A JOIN PrDtl B ON A.NoSPR=B.NoSPR WHERE A.JobNo='$JobNo' AND B.Alokasi='$Alokasi' AND B.KdRAP='$KdRAP' ");
		if ($query_SPR->num_rows()>0) {
			echo 'Tidak bisa hapus RAP.<br />Sudah dipakai untuk Permintaan Material.';
			die;
		}

		if ($Tipe=='Header') {
			$query_Cek = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Detail' AND Header='$KdRAP' ");
			if ($query_Cek->num_rows() > 0) {
				echo 'Tidak bisa hapus RAP Tipe Header. Masih ada detail.';
				die;
			}
		}

		echo 'lanjut';



	}

	function HitRecursive_Depan($KdRAP,$JobNo,$Alokasi){
		$query1 = $this->db->query("SELECT SUM(HrgSatuan*Vol) as 'Totalv', SUM(HrgRAB*Vol) as 'TotalRAB' FROM RAP WHERE JobNo='$JobNo' AND Header='$KdRAP' AND Alokasi='$Alokasi'");

		$Totalv = 0;
		$TotalRAB = 0;

		if ($query1->num_rows() > 0) {
			$row = $query1->row();
			$Totalv = CekNull_Angka_Dan_Bikin_Float($row->Totalv);
			$TotalRAB = CekNull_Angka_Dan_Bikin_Float($row->TotalRAB);
		}

		$query2 = $this->db->query("UPDATE RAP SET HrgSatuan='$Totalv',HrgRAB='$TotalRAB' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP' AND Alokasi='$Alokasi' ");

		$Header  = '0';
		$query3 = $this->db->query("SELECT TOP 1 ISNULL(Header,NULL) as Header FROM RAP WHERE JobNo='$JobNo' AND KdRAP='$KdRAP' AND Alokasi='$Alokasi' ");
		if ($query3->num_rows() > 0) {
			$row = $query3->row();
			$Header = CekNULL_KhususString_Terbaru($row->Header);
		}

		if ($Header=='0') {
			return true;
		}else{
			$this->HitRecursive_Depan($Header,$JobNo,$Alokasi);
		}
	}

	function AksiHapus_RAP(){
		if (!$this->input->post()) {
			die;
		}


		$JobNo 		= $this->input->post('JobNo');
		$Alokasi 	= $this->input->post('Alokasi');
		$KdRAP 		= trim($this->input->post('KdRAP'));
		$LedgerNo 	= $this->input->post('LedgerNo');
		$Versi 		= $this->input->post('Versi');
		$TipeRAP 	= $this->input->post('TipeRAP');
		$Tipe 		= $this->input->post('Tipe');


		try {

			$KdRAP_Header = '';
			$query_cek = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Detail' AND KdRAP='$KdRAP'");
			if ($query_cek->num_rows() > 0) {
				$row = $query_cek->row();
				$KdRAP_Header = $row->Header;
			}

			$this->db->query("DELETE FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP='$KdRAP' ");

			$query_cek_header = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Detail' AND Header='$KdRAP_Header' ");

			if ($query_cek_header->num_rows() > 0) {
				$this->HitRecursive_Depan($KdRAP_Header,$JobNo,$Alokasi);
			}else{
				$this->db->query("UPDATE RAP SET HrgSatuan=0,HrgRAB=0 WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_Header' AND Alokasi='$Alokasi'");
			}

			echo 'berhasil';
			$this->db->trans_commit();
		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}

	}

	function UpdateNoUrut(){
		if (!$this->input->post()) {
			die;
		}

		$NoUrut_YangDiKlik 	= $this->input->post('NoUrut_YangDiKlik');
		$KdRAP_YangDiKlik 	= $this->input->post('KdRAP_YangDiKlik');
		$Tipe_YangDiKlik 	= $this->input->post('Tipe_YangDiKlik');

		$JobNo 				= $this->input->post('JobNo');
		$TipeButton 		= $this->input->post('TipeButton');

		if ($TipeButton=='btnUp') {
			try {

				$NoUrut_atas 		= (int) $this->input->post('NoUrut_atas');
				$KdRAP_atas 		= $this->input->post('KdRAP_atas');
				$Tipe_atas 			= $this->input->post('Tipe_atas');

				$NoUrut_atas = $NoUrut_atas + 1; 
				$NoUrut_YangDiKlik = $NoUrut_YangDiKlik - 1;

				$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_atas' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_atas' ");
				$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_YangDiKlik' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_YangDiKlik' ");
				echo 'berhasil';
				$this->db->trans_commit();
			} catch (Exception $e) {
				echo 'gagal';
				$this->db->trans_rollback();
			}

		}else{
			try {

				// print_rr($_POST);
				// return false;

				$NoUrut_bawah 		= (int) $this->input->post('NoUrut_bawah');
				$KdRAP_bawah 		= $this->input->post('KdRAP_bawah');
				$Tipe_bawah 		= $this->input->post('Tipe_bawah');

				$NoUrut_bawah = $NoUrut_bawah - 1; 
				$NoUrut_YangDiKlik = $NoUrut_YangDiKlik + 1;

				// echo $KdRAP_YangDiKlik;
				// die;

				$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_bawah' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_bawah' ");
				$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_YangDiKlik' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_YangDiKlik' ");

				echo 'berhasil';
				$this->db->trans_commit();
			} catch (Exception $e) {
				echo 'gagal';
				$this->db->trans_rollback();
			}
		}

		


		// print_rr($_POST);
	}

	function UpdatePreference(){
		if (!$this->input->post()) {
			echo '';
			die;
		}


		$NoUrut 	= $this->input->post('NoUrut');
		$KdRAP 		= $this->input->post('KdRAP');
		$JobNo 		= $this->input->post('JobNo');
		$Alokasi 	= $this->input->post('Alokasi');

		$this->db->set(array('NoUrut' => $NoUrut,))->where(array('JobNo' => $JobNo, 'Alokasi' => $Alokasi, 'KdRAP' => $KdRAP))->update('RAP');

	}

	function UpdatePreference_lama(){
		if (!$this->input->post()) {
			die;
		}

		$FlagBtn = $this->input->post('FlagBtn');
		$JobNo = $this->input->post('JobNo');

		if ($FlagBtn=='btnUp') {

			$KdRAP_sekarang = trim($this->input->post('KdRAP_sekarang'));
			$NoUrut_atasnya = (int) $this->input->post('NoUrut_atasnya');

			$KdRAP_atasnya = trim($this->input->post('KdRAP_atasnya'));
			$NoUrut_sekarang = (int) $this->input->post('NoUrut_sekarang');

			// echo $NoUrut_sekarang;
			// die;

			try {
				$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_sekarang' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_atasnya'");
				$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_atasnya' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_sekarang'");

				echo 'berhasil';
				$this->db->trans_commit();
			} catch (Exception $e) {
				echo 'gagal';
				$this->db->trans_rollback();
			}

			die;

		}else{
			$KdRAP_sekarang = trim($this->input->post('KdRAP_sekarang'));
			$NoUrut_atasnya = (int) $this->input->post('NoUrut_atasnya');

			$KdRAP_atasnya = trim($this->input->post('KdRAP_atasnya'));
			$NoUrut_sekarang = (int) $this->input->post('NoUrut_sekarang');

			try {
				$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_sekarang' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_atasnya'");
				$this->db->query("UPDATE RAP SET NoUrut='$NoUrut_atasnya' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP_sekarang'");

				echo 'berhasil';
				$this->db->trans_commit();
			} catch (Exception $e) {
				echo 'gagal';
				$this->db->trans_rollback();
			}

			die;
		}

		// 
	}

	function import_excel(){

		// $this->load->library('Excel');

		if (isset($_FILES["fileExcel"]["name"])) {

			$JobNo 		= trim($this->input->post('JobNo'));
			$Alokasi 	= trim($this->input->post('Alokasi'));
			$Versi 		= trim($this->input->post('Versi'));
			$TipeRAP	= trim($this->input->post('TipeRAP'));
			$TimeEntry 	= date('Y-m-d H:i:s');
			$UserEntry 	= $this->session->userdata('MIS_LOGGED_NAME');

			$file = $_FILES["fileExcel"]["tmp_name"];
			$objPHPExcel = PHPExcel_IOFactory::load($file); 

			$JobNo_excel 	= trim(explode('-',$objPHPExcel->getActiveSheet()->getCell('B1')->getValue())[0]);
			$Alokasi_excel 	= trim(explode('-',$objPHPExcel->getActiveSheet()->getCell('B2')->getValue())[0]);
			$Versi_excel 	= trim(explode('-',$objPHPExcel->getActiveSheet()->getCell('B3')->getValue())[0]);


			if ($TipeRAP!='RAP') {
				echo 'import hanya dapat digunakan di-versi terbaru';
				exit;
			}

			if ($JobNo_excel!=$JobNo) {
				echo 'job tidak valid';
				exit;
			}

			if ($Alokasi_excel!=$Alokasi) {
				echo 'alokasi tidak valid';
				exit;
			}

			if ($Versi_excel!=$Versi) {
				echo 'versi tidak valid';
				exit;
			}

			$this->db->trans_begin();

			try {

				$this->db->where(array(
					'JobNo' 	=> $JobNo,
					'Alokasi' 	=> $Alokasi,
					'Versi' 	=> $Versi,
				))->delete('RAP');

				$totalrow = $objPHPExcel->getActiveSheet()->getHighestRow();
				$array_saya = array(); 

				$tanda_error = 0;

				for($row=6; $row<=$totalrow; $row++){

					$objPHPExcel->getActiveSheet()->getStyle('I'.$row)->getNumberFormat()->setFormatCode('0');


					$NoUrut 	= $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->getValue();

					$KdRAP 		= $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->getValue();
					$KdRAP 		= trim($KdRAP);
					$KdRAP 		= HapusKutip_DuaDanSatu($KdRAP);
					$KdRAP 		= HapusTitikDiAkhirString($KdRAP);

					$Uraian 	= $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->getValue();
					$Vol 		= $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->getValue();
					$Vol 		= CekDanHapusKoma($Vol);

					$Uom 		= $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->getValue();

					$HrgSatuan 	= $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->getValue();
					$HrgSatuan 	= CekDanHapusKoma($HrgSatuan);


					$HrgRAB 	= $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->getValue();
					$HrgRAB 	= CekDanHapusKoma($HrgRAB);

					$Tipe 		= $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $row)->getValue();
					$Header 	= $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $row)->getFormattedValue();

					if ($Tipe=='Header') {
						if ($HrgSatuan!=0) {
							echo 'Kode RAP : '.$KdRAP.' jika tipe adalah Header, maka Harga Satuan RAP dan Harga satuan RAB harus 0 (Nol) ';

							$this->db->where(array(
								'JobNo' 	=> $JobNo,
								'Alokasi' 	=> $Alokasi,
								'Versi' 	=> $Versi,
							))->delete('RAP');
							die;
						}
					}

					$array_saya = array(
						'JobNo' 	=> $JobNo,
						'Versi' 	=> $Versi,
						'Alokasi' 	=> $Alokasi,
						'NoUrut' 	=> $NoUrut,
						'KdRAP' 	=> $KdRAP,
						'Uraian' 	=> $Uraian,
						'Vol' 		=> $Vol,
						'Uom' 		=> $Uom,
						'HrgSatuan' => $HrgSatuan,
						'HrgRAB' 	=> $HrgRAB,
						'Tipe' 		=> $Tipe,
						'Header' 	=> $Header,
						'TimeEntry' => $TimeEntry,
						'UserEntry' => $UserEntry,
					);

					$this->db->insert('RAP',$array_saya);

					if ($Tipe=='Header' && $Header!='0') {
						$cek_header = $this->db->where(array(
							'JobNo' => $JobNo,
							'Alokasi' => $Alokasi,
							'Versi' => $Versi,
							'KdRAP' => $Header,
						))->get('RAP');

						if ($cek_header->num_rows() < 1) {
							echo 'Header : <b>'.$Header.'</b> pada Kode RAP : <b>'.$KdRAP.'</b> tidak ditemukan, silahkan ulangi import ';

							$this->db->where(array(
								'JobNo' 	=> $JobNo,
								'Alokasi' 	=> $Alokasi,
								'Versi' 	=> $Versi,
							))->delete('RAP');

							die;
						}
					}

					if ($Tipe=='Detail') {

						// -- Cek ulang kode header 
						$cek_header = $this->db->where(array(
							'JobNo' => $JobNo,
							'Alokasi' => $Alokasi,
							'Versi' => $Versi,
							'KdRAP' => $Header,
						))->get('RAP');

						if ($cek_header->num_rows() < 1) {
							echo 'Header : <b>'.$Header.'</b> pada Kode RAP : <b>'.$KdRAP.'</b> tidak ditemukan, silahkan ulangi import ';
							$this->db->where(array(
								'JobNo' 	=> $JobNo,
								'Alokasi' 	=> $Alokasi,
								'Versi' 	=> $Versi,
							))->delete('RAP');
							die;
						}

						$this->HitRecursive($Header,$JobNo,$Alokasi);

					}

				}
				
				$this->db->trans_commit();
				is_pesan('success','berhasil');
				redirect('Entry/RAPFrm/index/'.$JobNo.'/'.$Alokasi);

			} catch (Exception $e) {
				$this->db->trans_rollback();
				is_pesan('danger','terjadi kesalahan');
				redirect('Entry/RAPFrm/index/'.$JobNo.'/'.$Alokasi);
			}

		}else{
			is_pesan('warning','tidak ada file yang masuk');
		}

	}

	function lakukan_download(){			
		$this->load->helper('download');
		if (!file_exists('bahan_import_excel/import_RAP_MDH.xlsx')) {
			echo 'import_RAP_REN.xlsx tidak ditemukan';
			die;
		}

		force_download('bahan_import_excel/import_RAP_MDH.xlsx',NULL);
	}	

	function myexport_excel($JobNo='',$Alokasi='',$Versi='',$TipeRAP=''){


		$Versi= str_replace("-", ".", $Versi);

		$txt_query = "";
		if ($TipeRAP=='RAP') {
			$txt_query = "SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'  ORDER BY NoUrut ASC";
		}else{
			$txt_query = "SELECT * FROM RAPH WHERE JobNo='$JobNo' AND Versi='$Versi' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC";
		}

		$ArrayBuatan = array();
		$query = $this->db->query($txt_query);

		$TtlRAB = 0;
		$TtlRAP = 0;

		$sumFooter_JumlahHargaRAP = 0;
		$sumFooter_JumlahHargaRAB = 0;
		foreach ($query->result() as $row_hitung) {

			if ($row_hitung->Tipe=='Header') {
				continue;
			}

			$Vol 									= ($row_hitung->Vol=='' || $row_hitung->Vol==NULL) ? 0 : (float) $row_hitung->Vol;
			$HrgSatuan 								= ($row_hitung->HrgSatuan=='' || $row_hitung->HrgSatuan==NULL) ? 0 : (float) $row_hitung->HrgSatuan;
			$HrgRAB 								= ($row_hitung->HrgRAB=='' || $row_hitung->HrgRAB==NULL) ? 0 : (float) $row_hitung->HrgRAB;

			$TtlRAP 								= $TtlRAP + ($Vol * $HrgSatuan);
			$TtlRAB 								= $TtlRAB + ($Vol * $HrgRAB);

		}

		foreach ($query->result() as $row) {

			$BobotRAP 							= 0;
			$BobotRAB 							= 0;
			$BobotRAP_DiBagi_BobotRAB 			= 0;

			$JobNo 								= ($row->JobNo=='' || $row->JobNo==NULL) ? NULL : $row->JobNo;
			$KdRAP 								= ($row->KdRAP=='' || $row->KdRAP==NULL) ? NULL : $row->KdRAP;
			$Versi 								= CekNull_Huruf($row->Versi);
			$NoUrut 							= (int) $row->NoUrut;
			$Uraian 							= CekNull_Huruf($row->Uraian);
			$Alokasi 							= CekNull_Huruf($row->Alokasi);
			$Tipe 								= CekNull_Huruf($row->Tipe);
			$Header 							= CekNull_Huruf($row->Header);
			$Uom 								= CekNull_Huruf($row->Uom);
			$Vol 								= ($row->Vol=='' || $row->Vol==NULL) ? 0 : (float) $row->Vol;
			$HrgSatuan 							= ($row->HrgSatuan=='' || $row->HrgSatuan==NULL) ? 0 : (float) $row->HrgSatuan;
			$HrgRAB 							= ($row->HrgRAB=='' || $row->HrgRAB==NULL) ? 0 : (float) $row->HrgRAB;
			$TotalTerserap 						= ($row->TotalTerserap=='' || $row->TotalTerserap==NULL) ? 0 : (float) $row->TotalTerserap;
			$UserEntry 							= CekNull_Huruf($row->UserEntry);
			$TimeEntry 							= ($row->TimeEntry=='' || $row->TimeEntry==NULL) ? NULL : $row->TimeEntry;
			$Tahun 								= CekNull_Huruf($row->Tahun);
			$Jenis 								= CekNull_Huruf($row->Jenis);
			$LedgerNo 							= (int) $row->LedgerNo;
			$PPN 								= ($TipeRAP=='RAP') ? CekNull_Angka_Dan_Bikin_Float($row->PPN) : NULL;
			$PPN_New_Persen 					= ($TipeRAP=='RAP') ? CekNull_Huruf($row->PPN_New_Persen) : NULL;
			$PPN_New_Nominal					= ($TipeRAP=='RAP') ? CekNull_Angka_Dan_Bikin_Float($row->PPN_New_Nominal) : NULL;
			$PPN_New_Checklist 					= ($TipeRAP=='RAP') ? CekNull_Huruf($row->PPN_New_Checklist) : NULL;
			$Jumlah_HrgSatuan_kali_Vol			= ($TipeRAP=='RAP') ? CekNull_Angka_Dan_Bikin_Float($row->Jumlah_HrgSatuan_kali_Vol) : NULL;
			$Total_Jumlah_Tambah_PPN_Nominal	= ($TipeRAP=='RAP') ? CekNull_Angka_Dan_Bikin_Float($row->Total_Jumlah_Tambah_PPN_Nominal) : NULL;

			// -- WILAYAH HARGA SATUAN
			$JumlahHargaRAP					 	= $Vol * $HrgSatuan;
			$BobotRAP 							= $this->calculate_percentage_for_display($JumlahHargaRAP,$TtlRAP);

			// -- WILAYAH HARGA RAB
			$JumlahHargaRAB					 	= $Vol * $HrgRAB;
			$BobotRAB							= $this->calculate_percentage_for_display($JumlahHargaRAB,$TtlRAB);


			if ($JumlahHargaRAB==0) {
				$BobotRAP_DiBagi_BobotRAB 		= 0;
			}else{
				$BobotRAP_DiBagi_BobotRAB 		= ($JumlahHargaRAP / $JumlahHargaRAB) * 100;
			}

			$ArrayBuatan[] = (object)  array(
				'JobNo' 							=> $row->JobNo,
				'KdRAP' 							=> $KdRAP,
				'Versi' 							=> $Versi,
				'NoUrut' 							=> $NoUrut,
				'Uraian' 							=> $Uraian,
				'Alokasi' 							=> $Alokasi,
				'Tipe' 								=> $Tipe,
				'Header' 							=> $Header,
				'Uom' 								=> $Uom,


				'TotalTerserap' 					=> $TotalTerserap,
				'UserEntry' 						=> $UserEntry,
				'TimeEntry' 						=> $TimeEntry,
				'Tahun'								=> $Tahun,
				'Jenis' 							=> $Jenis,
				'LedgerNo' 							=> $LedgerNo,
				'PPN' 								=> $PPN,
				'PPN_New_Persen' 					=> $PPN_New_Persen,
				'PPN_New_Nominal' 					=> $PPN_New_Nominal,
				'PPN_New_Checklist' 				=> $PPN_New_Checklist,
				'Jumlah_HrgSatuan_kali_Vol' 		=> $Jumlah_HrgSatuan_kali_Vol,
				'Total_Jumlah_Tambah_PPN_Nominal' 	=> $Total_Jumlah_Tambah_PPN_Nominal,


				'Vol' 								=> $Vol,
				'HrgSatuan' 						=> $HrgSatuan,
				'JumlahHargaRAP'					=> $JumlahHargaRAP,
				'BobotRAP'							=> $BobotRAP,

				'HrgRAB' 							=> $HrgRAB,
				'JumlahHargaRAB'					=> $JumlahHargaRAB,
				'BobotRAB' 							=> $BobotRAB,

				'BobotRAP_DiBagi_BobotRAB' 			=> round($BobotRAP_DiBagi_BobotRAB,2),
			);

		}

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$style_col = [
			'font'  => [
				'bold'  => true,
				'color' => ['rgb' => '000000'],
				'size'  => 10,
				'name'  => 'Segoe UI',
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'color' => ['rgb' => 'FFFFFFFF'],
			],
		];

		$style_header_kedua = [
			'font'  => [
				'bold'  => true,
				'color' => ['rgb' => 'FFFFFFFF'],
				'size'  => 10,
				'name'  => 'Segoe UI',
			],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				
			],
		];

		

		$sheet->setCellValue('A1',"");
		$sheet->mergeCells('A1:D1');
		$sheet->getStyle('A1:D1')->applyFromArray($style_col);

		$sheet->setCellValue('E1',"RAP");
		$sheet->mergeCells('E1:G1');
		$sheet->getStyle('E1:G1')->applyFromArray($style_col);

		$sheet->setCellValue('H1',"RAB");
		$sheet->mergeCells('H1:J1');
		$sheet->getStyle('H1:J1')->applyFromArray($style_col);

		$sheet->setCellValue('K1',"");
		$sheet->getStyle('K1')->applyFromArray($style_col);


		$sheet->setCellValue('A2', 'Kode RAP');
		$sheet->setCellValue('B2', 'Uraian');
		$sheet->setCellValue('C2', 'Sat');
		$sheet->setCellValue('D2', 'Vol');

		$sheet->setCellValue('E2', 'Hrg Satuan (Rp) ');
		$sheet->setCellValue('F2', 'Jumlah Harga (Rp)');
		$sheet->setCellValue('G2', 'Bobot (%)');

		$sheet->setCellValue('H2', 'Hrg Satuan (Rp)');
		$sheet->setCellValue('I2', 'Jumlah Harga (Rp)');
		$sheet->setCellValue('J2', 'Bobot (%)');

		$sheet->setCellValue('K2', 'RAP / RAB (%)');

		$spreadsheet->getActiveSheet()->getStyle('A2:K2')->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('5D7B9D');

		$sheet->getStyle('A2:K2')->applyFromArray($style_header_kedua);
		
		$rows = 3;
		$sumBobotRAP = 0;
		$sumBobotRAB = 0;
		$sumJumlahHarga_RAP = 0;
		$sumJumlahHarga_RAB = 0;
		$sum_BobotRAP_Dibagi_BobotRAB = 0;

		foreach ($ArrayBuatan as $row){

			if ($row->Tipe != 'Header') {
				$sumBobotRAP += (float) $row->BobotRAP;
				$sumBobotRAB += (float) $row->BobotRAB;
				$sumJumlahHarga_RAP += (float) $row->Vol * $row->HrgSatuan;
				$sumJumlahHarga_RAB += (float) $row->Vol * $row->HrgRAB;
				$sum_BobotRAP_Dibagi_BobotRAB += (float) $row->BobotRAP_DiBagi_BobotRAB;
			}

			$sheet->setCellValue('A' . $rows, htmlspecialchars($row->KdRAP));
			$sheet->setCellValue('B' . $rows, htmlspecialchars($row->Uraian));
			$sheet->setCellValue('C' . $rows, htmlspecialchars($row->Uom));
			$sheet->setCellValue('D' . $rows, VolDecimal_Nol_Tiga($row->Vol));

			$sheet->setCellValue('E' . $rows, VolDecimal_Nol_Dua($row->HrgSatuan));
			$sheet->setCellValue('F' . $rows, VolDecimal_Nol_Dua($row->JumlahHargaRAP));
			$sheet->setCellValue('G' . $rows, htmlspecialchars($row->BobotRAP));

			$sheet->setCellValue('H' . $rows, VolDecimal_Nol_Dua($row->HrgRAB));
			$sheet->setCellValue('I' . $rows, VolDecimal_Nol_Dua($row->JumlahHargaRAB));
			$sheet->setCellValue('J' . $rows, htmlspecialchars($row->BobotRAB));

			$sheet->setCellValue('K' . $rows, htmlspecialchars($row->BobotRAP_DiBagi_BobotRAB));

			if ($row->Tipe=='Header') {
				$sheet->getStyle('A' . $rows.':K' . $rows)->applyFromArray(['font' => ['bold'=>true]]);
			}

			$spreadsheet->getActiveSheet()->getStyle('E' . $rows.':G' . $rows)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A1DCF2');

			$spreadsheet->getActiveSheet()->getStyle('H' . $rows.':J' . $rows)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('99FF99');

			$sheet->getStyle('H1:J1')->applyFromArray($style_col);

			$rows++;

		} 

		$rows_footer = $rows;

		$spreadsheet->getActiveSheet()->getStyle('A'.$rows_footer.':K'.$rows_footer)->getFill()
		->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
		->getStartColor()->setARGB('5D7B9D');
		$sheet->getStyle('A'.$rows_footer.':K'.$rows_footer)->applyFromArray($style_header_kedua);

		$sheet->setCellValue('A' . $rows_footer, '');
		$sheet->setCellValue('B' . $rows_footer, '');
		$sheet->setCellValue('C' . $rows_footer, '');
		$sheet->setCellValue('D' . $rows_footer, '');
		$sheet->setCellValue('E' . $rows_footer, '');
		$sheet->setCellValue('F' . $rows_footer, 'sum='.number_format($sumJumlahHarga_RAP));
		$sheet->setCellValue('G' . $rows_footer, 'sum='.$sumBobotRAP);
		$sheet->setCellValue('H' . $rows_footer, '');
		$sheet->setCellValue('I' . $rows_footer, 'sum='.number_format($sumJumlahHarga_RAB));
		$sheet->setCellValue('J' . $rows_footer, 'sum='.$sumBobotRAB);
		$sheet->setCellValue('K' . $rows_footer, '');

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		// header('Content-Disposition: attachment; filename="");
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');


	}




}

/* End of file RAPFrm.php */
											/* Location: ./application/controllers/Entry/RAPFrm.php */