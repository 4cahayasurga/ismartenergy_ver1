<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EntryPJKeu extends CI_Controller {

	var $Company;
	var $PJ_Status;
	var $Url_Batal;

	var $JobNo_Saya;
	var $Alokasi_Saya;

	var $Action_Global;
	var $JobNo_Global;
	var $NoPD_Global;
	var $Alokasi_Global;
	var $UrlDari_Global;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		PembuatanInvoiceSementara(); 
		tbl_NewInvoice();
		PembuatanTable_PdDtl_Temp_Keuangan();
		PembuatanTable_Invoice_Temp_Keuangan();

		// == START ADD PD_PPN,23 Nov 2023.
		PenambahanField_PD_PPN();
		// PenambahanField_InputDari();
		// == END ADD PD_PPN,23 Nov 2023.

		// if ($this->session->userdata('PecahToken')->PJ!=1) {
		// 	redirect('Welcome');
		// }

		$this->PJ_Status='Keuangan';

		// $this->Url_Batal = site_url('Entry/PDPJ/'.$this->router->fetch_class());

		if ($this->session->userdata('SesiPJKeuangan')) {
			// $this->JobNo_Saya 	= explode('|', $this->session->userdata('SesiPJKeuangan'))[0];
			// $this->Alokasi_Saya	= explode('|', $this->session->userdata('SesiPJKeuangan'))[1];

			$this->Action_Global 	= explode('|', $this->session->userdata('SesiPJKeuangan'))[0];
			$this->JobNo_Global	= explode('|', $this->session->userdata('SesiPJKeuangan'))[1];
			$this->NoPD_Global 	= explode('|', $this->session->userdata('SesiPJKeuangan'))[2];
			$this->Alokasi_Global = explode('|', $this->session->userdata('SesiPJKeuangan'))[3];
			$this->UrlDari_Global = explode('|', $this->session->userdata('SesiPJKeuangan'))[4];

		}

		// -- DITAMBAHKAN TANGGAL : 13 DES 2023
		PembuatanTable_PJCadangan();

	}

	private function ListJob(){

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

	private function ListAlokasi(){
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

	function index($JobNo='',$Alokasi=''){

		if ($this->session->userdata('PecahToken')->PJFinance!=1) {
			echo 'akses di tolak';
			die;
		}

		if ($JobNo=='') {
			$JobNo 		= $this->JobNo_Global;
			$Alokasi 	= $this->Alokasi_Global;
		}

		// $this->session->unset_userdata('SesiPJKeuangan');

		// echo $_SESSION['SesiPJKeuangan'];

		$data['title'] 			= 'Entry Pertanggungjawaban Dana (Keuangan)';
		$data['judul'] 			= 'Entry Pertanggungjawaban Dana (Keuangan)';
		$data['atur_kiri'] 		= '1';
		$data['bodyclass'] 		= 'sidebar-collapse skin-blue-light';
		$data['js_saya'] 		= 'entry_view/PDPJ_View/EntryPJKeu_VIEW/js_halaman_utama_PjKeu';

		$data['JobNo'] 			= $JobNo;
		$data['Alokasi'] 		= $Alokasi;

		$data['ListJob'] = $this->ListJob();
		$data['ListAlokasi'] = $this->ListAlokasi(); 

		// print_rr($data);



		// echo $this->BindJob();
		// die;

		// $data['Url_Batal'] 		= "";

		// if ($this->UrlDari_Global=='FrmPJLap') {
		// 	$data['Url_Batal'] 	= "FrmPJLap/view/".$this->JobNo_Global.'/'.$this->Alokasi_Global;
		// }

		// $data['rowPD'] 			= $this->getPdHdr();
		// $this->PerpindahanData_PdDtl_to_PJCadangan();

		// $data['btn_dimatikan'] = ($this->Action_Global=='SEE') ? 'disabled' : '';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/EntryPJKeu_VIEW/halaman_utama_PjKeu', $data);
		$this->load->view('templates/footer');
	}

	function BindJob(){
		$UserID  = $this->session->userdata('MIS_LOGGED_ID');
		
		$AksesJob = "";
		$query = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='$UserID' ");
		if ($query->num_rows() > 0) {
			$AksesJob = $query->row()->AksesJob;
		}

		return $AksesJob;
	}

	function tampil_table_PJ_Keuangan(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		$SesiPJKeuangan = '|'.$JobNo.'||'.$Alokasi.'|';
		$this->session->set_userdata('SesiPJKeuangan',$SesiPJKeuangan);


		$TxtQuery = "SELECT * FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND (NoRef IS NULL OR NoRef='') AND RejectBy IS NULL ORDER BY NoPD DESC "; 

		$query = $this->db->query($TxtQuery);

		$Ttl = 0;
		foreach ($query->result() as $row) {
			$TotalPD = ($row->TotalPD=='' || $row->TotalPD==NULL) ? 0 : (float) $row->TotalPD;
			$Ttl += $TotalPD;
		}

		$data['Ttl'] = number_format($Ttl);
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['mydata'] = $query->result();

		$this->load->view('entry_view/PDPJ_View/EntryPJKeu_VIEW/tampil_table_PJ_Keuangan', $data);

	}

	function CekPdHdr(){
		if (!$this->input->post('LedgerNo')) {
			echo 'gagal';
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo');
		$cekPD = $this->db->where('LedgerNo',$LedgerNo)->get('PdHdr');

		if ($cekPD->num_rows() < 1) {
			echo 'PD_kosong';
			exit;
		}

		$row = $cekPD->row();
		if ($row->RejectBy !='' || $row->RejectBy !=NULL ) {
			echo 'sudah_reject';
			exit;
		}

		if ($row->NoPJ == '' || $row->NoPJ == NULL) {
			echo 'NoPJ_kosong';
			exit;
		}

		echo 'berhasil';


	}

	function CekPD($JobNo='',$Alokasi='',$NoPD=''){
		if ($JobNo=='' || $Alokasi=='') {
			redirect('Entry/PDPJ/EntryPJKeu/');
			die;
		}

		$query_BLE = $this->db->query("SELECT *, (SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE  FROM PdHdr WHERE NoPD='$NoPD' ");

		if ($query_BLE->num_rows() < 1) {
			is_pesan('danger',$NoPD.' tidak ditemukan');
			redirect('Entry/PDPJ/EntryPJKeu/index/'.$JobNo.'/'.$Alokasi);
		}else{
			$row = $query_BLE->row();
			if ($row->RejectBy!='' || $row->RejectBy!=NULL) {
				is_pesan('danger','sudah di-reject oleh '.$row->RejectBy);
				redirect('Entry/PDPJ/EntryPJKeu/index/'.$JobNo.'/'.$Alokasi);
			}

			if ($row->CountBLE==0) {
				is_pesan('danger','Belum ada pembayaran untuk No '.$NoPD);
				redirect('Entry/PDPJ/EntryPJKeu/index/'.$JobNo.'/'.$Alokasi);
			}

			$this->session->unset_userdata('SesiPJKeuangan');

			$SesiPJKeuangan = "";
			if ($row->PjApprovedByAK=='' || $row->PjApprovedByAK==NULL) {
				$SesiPJKeuangan = "UPD|".$JobNo."|".$NoPD."|".$Alokasi."|EntryPJKeu";
			}else{
				$SesiPJKeuangan = "SEE|".$JobNo."|".$NoPD."|".$Alokasi."|EntryPJKeu";
			}

			$this->session->set_userdata('SesiPJKeuangan',$SesiPJKeuangan);
			redirect('Entry/PDPJ/EntryPJKeu/FrmPJUpdate');
		}

	}

	function CekStatusApprovePD(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);
		$NoPD = $this->input->post('NoPD');
		$NoPJ = $this->input->post('NoPJ');

		$query = $this->db->query("SELECT * FROM PdHdr WHERE NoPD='$NoPD' ");

		if ($query->num_rows() > 0) {

			$row = $query->row_array();
			if ($row['RejectBy']!='' || $row['RejectBy']!=NULL) {
				echo $NoPD.' sudah di-reject oleh '.$row['RejectBy'];
				die;
			}else if ($row['NoPJ']=='' || $row['NoPJ']==NULL) {
				echo $NoPD.' belum entry uraian PJ.';
			}else if ($row['PjApprovedByKT']!='' || $row['PjApprovedByKT']!=NULL) {
				echo $NoPD.' sudah di-approved oleh Cost Control. ';
				die;
			}else{
				echo 'lanjut';
			}


		}else{
			echo 'data tidak ditemukan';
		}
		

	}

	function lakukanApprove(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD = $this->input->post('NoPD');
		$NoPJ = $this->input->post('NoPJ');
		$TxtApprove = $this->input->post('TxtApprove');

		$this->db->trans_begin();

		try {
			$set = array(
				'PjApprovedByAK' => ($TxtApprove=='APPROVE') ? $this->session->userdata('MIS_LOGGED_NAME') : NULL,
				'PjTimeApprovedAK' => ($TxtApprove=='APPROVE') ? date('Y-m-d H:i:s') : NULL,
			);
			$where = array('NoPD' => $NoPD);
			$this->db->set($set)->where($where)->update('PdHdr');
			
			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}

	}


	function FrmPJUpdate(){

		$data['title'] 			= 'Entry Pertanggungjawaban Dana (Keuangan)';
		$data['judul'] 			= 'Entry Pertanggungjawaban Dana (Keuangan)';
		$data['atur_kiri'] 		= '1';
		$data['bodyclass'] 		= 'sidebar-collapse skin-blue-light';
		$data['js_saya'] 		= 'entry_view/PDPJ_View/EntryPJKeu_VIEW/js_form_entry_PJKeu';

		$data['JobNo'] 			= $this->JobNo_Global;
		$data['Alokasi'] 		= $this->Alokasi_Global;
		$data['NoPD'] 			= $this->NoPD_Global;

		$data['Url_Batal'] 		= "";

		if ($this->UrlDari_Global=='EntryPJKeu') {
			$data['Url_Batal'] 	= "Entry/PDPJ/EntryPJKeu/index/".$this->JobNo_Global.'/'.$this->Alokasi_Global;
		}

		$data['rowPD'] 			= $this->getPdHdr();
		$this->PerpindahanData_PdDtl_to_PJCadangan();

		$data['btn_dimatikan'] = ($this->Action_Global=='SEE') ? 'disabled' : '';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/EntryPJKeu_VIEW/form_entry_PJKeu', $data);
		$this->load->view('templates/footer');
	}

	function getPdHdr(){
		$getDataPD = $this->db->query("SELECT *, (SELECT ISNULL(JobNm,NULL) FROM Job WHERE JobNo='$this->JobNo_Global') AS 'JobNm', (SELECT ISNULL(Keterangan,NULL) FROM Alokasi WHERE Alokasi='$this->Alokasi_Global') AS 'KeteranganAlokasi', (SELECT ISNULL(Keterangan,NULL) FROM TipeForm WHERE Alokasi='$this->Alokasi_Global' AND TipeForm=PdHdr.TipeForm) AS 'KeteranganTipeForm' FROM PdHdr WHERE NoPD='$this->NoPD_Global' ")->row();

		return $getDataPD;
	}

	function PerpindahanData_PdDtl_to_PJCadangan(){
		$this->db->trans_begin();
		try {
			
			$this->db->query("DELETE FROM PJCadangan WHERE NoPD='$this->NoPD_Global'  ");

			$myquery = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$this->NoPD_Global' ");
			if ($myquery->num_rows() > 0) {
				$myarray = array();
				foreach ($myquery->result() as $row) {
					$myarray[] = array(
						'NoPD' => CekNull_Huruf($row->NoPD),
						'NoUrut' => (int) $row->NoUrut,
						'KdRAP' => CekNull_Huruf($row->KdRAP),
						'Uraian' => CekNull_Huruf($row->Uraian),
						'Vol' => CekNull_Angka_Dan_Bikin_Float($row->Vol),
						'Uom' => CekNull_Huruf($row->Uom),
						'HrgSatuan' => CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan),
						'UserEntry' => $row->UserEntry,
						'TimeEntry' => $row->TimeEntry,
						'NoPJ' => CekNull_Huruf($row->NoPJ),
						'PjUraian' => CekNull_Huruf($row->PjUraian),
						'PjVol' => CekNull_Angka_Dan_Bikin_Float($row->PjVol),
						'PjHrgSatuan' => CekNull_Angka_Dan_Bikin_Float($row->PjHrgSatuan),
						'PjUserEntry' => $row->PjUserEntry,
						'PjTimeEntry' => $row->PjTimeEntry,
						'Company' => $this->Company,
						'NewRecord' => 'No',
					);
				}
				$this->db->insert_batch('PJCadangan',$myarray);
			}

			$this->db->trans_commit();

		} catch (Exception $e) {
			$this->db->trans_rollback();

		}
	}

	function HurufKosong_Validasi($dataparam,$flag=''){
		if ($flag=='tanggal') {
			return ($dataparam=='' || $dataparam==NULL || $dataparam=='1900-01-01' ) ? '' : $dataparam;
		}else{
			return ($dataparam=='' || $dataparam==NULL) ? '' : $dataparam;
		}
		
	}

	function getPdDtl(){
		$getPdDtl = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$this->NoPD_Global' ORDER BY NoUrut")->result();
		return $getPdDtl;
		// $MyArray = array();
		// if ($getPdDtl->num_rows() > 0) {

		// 	foreach ($getPdDtl->result() as $row) {

		// 		$MyArray[] = (object) array();
		// 	}
		// }
	}

	function tampilkan_tablePJKeu(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD = $this->input->post('NoPD');

		$data['NoPD'] = $NoPD;
		$ArrayBuatan = array();

		$ListPdDtl = $this->db->query("SELECT *,(ISNULL(PJVol,0) * ISNULL(PjHrgSatuan,0)) AS JumlahHarga FROM PJCadangan WHERE NoPD='$NoPD' ORDER BY NoUrut ASC  ")->result();
		$data['ListPdDtl'] = $ListPdDtl;

		$sumTotalPJ = 0;
		$query = $this->db->query("SELECT COALESCE(sum(PJVol * PjHrgSatuan),0) AS 'TotalPJ' FROM PJCadangan WHERE NoPD='$NoPD' GROUP BY NoPD  ");
		if ($query->num_rows()>0) {
			$sumTotalPJ = $query->row()->TotalPJ;
		}else{
			$sumTotalPJ = 0;
		}

		$data['sumTotalPJ'] = number_format($sumTotalPJ);

		$this->load->view('entry_view/PDPJ_View/EntryPJKeu_VIEW/tablePJKeu', $data);
	}

	function AddNewUraian(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD 			= $this->input->post('NoPD');
		$NoUrut 		= (int) $this->input->post('NoUrut');
		$KdRAP 			= CekNull_Huruf($this->input->post('KdRAP'));
		$PjUraian 		= CekNull_Huruf($this->input->post('Uraian'));
		$PjVol 			= CekDanHapusKoma($this->input->post('Vol'));
		$Uom 			= CekNull_Huruf($this->input->post('Uom'));
		$PjHrgSatuan 	= CekNull_Huruf($this->input->post('HrgSatuan'));	

		
		$this->db->trans_begin();
		try {
			
			$data = array(
				'NoPD' 			=> $NoPD,
				'NoUrut' 		=> $NoUrut, 
				'KdRAP' 		=> $KdRAP,
				'PjUraian' 		=> $PjUraian,
				'PjVol' 		=> $PjVol,
				'Uom' 			=> strtoupper($Uom),
				'PjHrgSatuan' 	=> $PjHrgSatuan,
				'PjUserEntry' 	=> $this->session->userdata('MIS_LOGGED_NAME'),
				'PjTimeEntry' 	=> date('Y-m-d H:i:s'),
				'NewRecord' 	=> 'Yes',
			);
			$this->db->insert('PJCadangan',$data);

			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}

	}

	function UpdateUraian(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);
		// die;

		$NoPD 			= $this->input->post('NoPD');
		$NoUrut 		= (int) $this->input->post('NoUrut');

		$PjUraian 		= CekNull_Huruf($this->input->post('PjUraian'));
		$PjVol 			= CekDanHapusKoma($this->input->post('PjVol'));
		$PjHrgSatuan 	= CekDanHapusKoma($this->input->post('PjHrgSatuan'));

		
		$this->db->trans_begin();
		try {

			$set = array(
				'PjUraian' 		=> $PjUraian,
				'PjVol' 		=> $PjVol,
				'PjHrgSatuan' 	=> $PjHrgSatuan,
				'PjUserEntry' 	=> $this->session->userdata('MIS_LOGGED_NAME'),
				'PjTimeEntry' 	=> date('Y-m-d H:i:s'),
			);
			
			$where = array(
				'NoPD' 			=> $NoPD,
				'NoUrut' 		=> $NoUrut, 
			);

			// print_rr($where);

			$this->db->set($set)->where($where)->update('PJCadangan');

			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}

	}
	function Aksi_UraianSamaDenganPD(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD 			= $this->input->post('NoPD');
		
		$this->db->trans_begin();
		try {

			$query = $this->db->query("SELECT * FROM PJCadangan WHERE NoPD='$NoPD' ");
			if ($query->num_rows() > 0) {

				$myarray = array();

				foreach ($query->result() as $row) {
					$Uraian = CekNull_Huruf($row->Uraian);
					$Vol = CekNull_Angka_Dan_Bikin_Float($row->Vol);
					$HrgSatuan = CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan);
					$NoUrut = (int) $row->NoUrut;

					$set = array(
						'PjUraian' => $Uraian,
						'PjVol' => $Vol,
						'PjHrgSatuan' => $HrgSatuan,
					);

					$where = array('NoPD' => $NoPD, 'NoUrut' => $NoUrut);

					$this->db->set($set)->where($where)->update('PJCadangan');
				}
			}

			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}

	}

	function CekBuktiPendukung($param_BuktiPendukung=""){
		$ListBuktiPendukung = "";
		
		$ListBuktiPendukung = ($this->input->post('INV')) ? "INV" : "";
		$ListBuktiPendukung = ($this->input->post('SJ')) ? ($ListBuktiPendukung=="") ? "SJ" : $ListBuktiPendukung.",SJ" : $ListBuktiPendukung;
		$ListBuktiPendukung = ($this->input->post('PO')) ? ($ListBuktiPendukung=="") ? "PO" : $ListBuktiPendukung.",PO" : $ListBuktiPendukung;
		$ListBuktiPendukung = ($this->input->post('FP')) ? ($ListBuktiPendukung=="") ? "FP" : $ListBuktiPendukung.",FP" : $ListBuktiPendukung;
		$ListBuktiPendukung = ($this->input->post('BAP')) ? ($ListBuktiPendukung=="") ? "BAP" : $ListBuktiPendukung.",BAP" : $ListBuktiPendukung;
		$ListBuktiPendukung = ($this->input->post('BAOP')) ? ($ListBuktiPendukung=="") ? "BAOP" : $ListBuktiPendukung.",BAOP" : $ListBuktiPendukung;

		return $ListBuktiPendukung;
	}

	function UpdatePD_To_Database(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD 			= $this->input->post('NoPD');
		$NoPJ 			= $this->input->post('NoPJ');
		$TglPJ 			= $this->input->post('TglPJ');
		$TotalPJ 		= CekDanHapusKoma($this->input->post('TotalPJ'));
		$BuktiPendukung = $this->CekBuktiPendukung($this->input->post());
		$Saldo 			= CekDanHapusKoma($this->input->post('Saldo'));
		$PjUserEntry 	= $this->session->userdata('MIS_LOGGED_NAME');
		$PjTimeEntry 	= date('Y-m-d H:i:s');


		$this->db->trans_begin();
		try {


			// -- Update table di PdHdr
			$set_PdHdr = array(
				'NoPJ' => $NoPJ,
				'TglPJ' => $TglPJ,
				'TotalPJ' => $TotalPJ,
				'BuktiPendukung' => $BuktiPendukung,
				'Saldo' => $Saldo,
				'PjUserEntry' => $PjUserEntry,
				'PjTimeEntry' => $PjTimeEntry,
			);
			$this->db->set($set_PdHdr)->where(array('NoPD' => $NoPD))->update('PdHdr');


				// -- update dan insert pada table PdDtl
			$myarray = array();
			foreach($this->input->post('NoUrut_table') as $key => $val) :
				$NoUrut 		= (int) $val;
				$KdRAP 			= $this->input->post('KdRAP_table')[$key];
				$PjUraian 		= $this->input->post('PjUraian_table')[$key];
				$PjVol 			= $this->input->post('PjVol_table')[$key];
				$Uom 			= $this->input->post('Uom_table')[$key];
				$PjHrgSatuan 	= $this->input->post('PjHrgSatuan_table')[$key];
				$NewRecord 		= $this->input->post('NewRecord_table')[$key];

				$PjVol 			= CekDanHapusKoma($PjVol);
				$PjHrgSatuan 	= CekDanHapusKoma($PjHrgSatuan);

				if ($NewRecord=='Yes') {

					$array_simpan = array(
						'NoPD' 			=> $NoPD,
						'NoUrut' 		=> $NoUrut,
						'KdRAP' 		=> $KdRAP, 
						'NoPJ' 			=> $NoPJ, 
						'PjUraian' 		=> $PjUraian, 
						'PjVol' 		=> $PjVol, 
						'Uom' 			=> $Uom, 
						'PjHrgSatuan' 	=> $PjHrgSatuan, 
						'PjUserEntry' 	=> $PjUserEntry,
						'PjTimeEntry' 	=> $PjTimeEntry,
					);

					$this->db->insert('PdDtl',$array_simpan);

				}else{

					$array_set = array(
						'NoPJ' 			=> $NoPJ,
						'PjUraian'		=> $PjUraian,
						'PjVol' 		=> $PjVol,
						'PjHrgSatuan' 	=> $PjHrgSatuan,
						'PjUserEntry' 	=> $PjUserEntry,
						'PjTimeEntry' 	=> $PjTimeEntry,
					);

					$array_where = array(
						'NoPD' 			=> $NoPD,
						'NoUrut'		=> $NoUrut,
					);

					$this->db->set($array_set)->where($array_where)->update('PdDtl');

				}

			endforeach;

			$this->db->query("DELETE PJCadangan WHERE NoPD='$NoPD' ");

			is_pesan('success','Sukses, data telah di-Update');
			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}

		// print_rr($myarray);
	}

}

/* End of file EntryPJKeu.php */
/* Location: ./application/controllers/Entry/PDPJ/EntryPJKeu.php */