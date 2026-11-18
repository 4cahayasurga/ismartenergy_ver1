<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EntryPDLap_C extends CI_Controller {

	var $Company;
	var $PD_Status;
	var $Url_Batal;

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

		if ($this->session->userdata('PecahToken')->PD!=1) {
			redirect('Welcome');
		}

		$this->PD_Status='Lapangan';



		
		$this->Url_Batal = site_url('Entry/PDPJ/'.$this->router->fetch_class());

		// if ($this->session->userdata('SesiJob_KT')) {
		// 	$JobNo = explode('|', $this->session->userdata('SesiJob_KT'))[0];
		// 	$Alokasi = explode('|', $this->session->userdata('SesiJob_KT'))[1];
		// 	$NamaController = explode('|', $this->session->userdata('SesiJob_KT'))[2];
		// 	$this->Url_Batal = site_url('Entry/PDPJ/'.$NamaController.'/index/'.$JobNo.'/'.$Alokasi);


		// }else if ($this->session->userdata('SesiJob_KK')) {
		// 	$JobNo = explode('|', $this->session->userdata('SesiJob_KK'))[0];
		// 	$Alokasi = explode('|', $this->session->userdata('SesiJob_KK'))[1];
		// 	$NamaController = explode('|', $this->session->userdata('SesiJob_KK'))[2];
		// 	$this->Url_Batal = site_url('Entry/PDPJ/'.$NamaController.'/index/'.$JobNo.'/'.$Alokasi);

		// }

		// $this->session->unset_userdata('SesiJob_KT');
		// $this->session->unset_userdata('SesiJob_KK');

	}

	public function index($JobNo='',$Alokasi='')
	{

		// print_rr()
		$data['title'] = 'Entry Permintaan Dana (Lapangan)';
		$data['judul'] = 'Entry Permintaan Dana (Lapangan)';
		$data['atur_kiri'] = '1';
		$data['ListJob'] = $this->ListJob();
		
		$data['ListAlokasi'] = $this->ListAlokasi(); 
		$data['bodyclass'] = 'sidebar-collapse skin-blue';

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['js_saya'] = 'entry_view/PDPJ_View/EntryPDLap_VIEW/js_main_PDLap';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/EntryPDLap_VIEW/main_PDLap', $data);
		$this->load->view('templates/footer');	
	}

	function tampil_table_PD_Lapangan(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		$query = $this->db->query("SELECT * FROM PdHdr WHERE JobNo='$JobNo' AND (NoRef IS NOT NULL OR NoRef<>'') AND Alokasi='$Alokasi' ORDER BY NoPD DESC ");
		$Ttl = 0;
		foreach ($query->result() as $row) {
			$TotalPD = ($row->TotalPD=='' || $row->TotalPD==NULL) ? 0 : (float) $row->TotalPD;
			$Ttl += $TotalPD;
		}

		$data['Ttl'] = number_format($Ttl);
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['ListPD'] = $query->result();

		$this->load->view('entry_view/PDPJ_View/EntryPDLap_VIEW/tampil_table_PD_Lapangan', $data);
	}

	function CekData($JobNo,$Alokasi,$TipePD=''){
		if ($JobNo=='' || $Alokasi=='') {
			is_pesan('success','Belum pilih Job/Alokasi.');
			redirect('Entry/PDPJ/EntryPDLap_C/');
		}

		if ($TipePD=='AddKSO') {

			$query = $this->db->query("SELECT * FROM Job WHERE JobNo='$JobNo'");
			if ($query->num_rows()>0) {
				$row = $query->row();
				$JobNm = $row->JobNm;

				if ($row->StatusJob == 'FHO') {
					$pesan = "Job: ".$JobNo.'<br /> Status = '.$row->StatusJob;
					is_pesan('warning',$pesan);
					redirect('Entry/PDPJ/EntryPDLap_C/index/'.$JobNo.'/'.$Alokasi);
				}

				if ($row->KSO <> '1' || $row->KSO <> 1) {
					$pesan = "Job : ".$JobNo.' - '.$JobNm.', bukan KSO';
					is_pesan('warning',$pesan);
					redirect('Entry/PDPJ/EntryPDLap_C/index/'.$JobNo.'/'.$Alokasi);
				}

			}
		}else if ($TipePD=='AddMIX') {

			$query = $this->db->query("SELECT * FROM Job WHERE JobNo='$JobNo'");
			if ($query->num_rows()>0) {
				$row = $query->row();
				$JobNm = $row->JobNm;

				if ($row->StatusJob == 'FHO') {
					$pesan = "Job: ".$JobNo.'<br /> Status = '.$row->StatusJob;
					is_pesan('warning',$pesan);
					redirect('Entry/PDPJ/EntryPDLap_C/index/'.$JobNo.'/'.$Alokasi);
				}

				if ($row->KSO <> '1' || $row->KSO <> 1) {
					$pesan = "Job : ".$JobNo.' - '.$JobNm.', bukan KSO';
					is_pesan('warning',$pesan);
					redirect('Entry/PDPJ/EntryPDLap_C/index/'.$JobNo.'/'.$Alokasi);
				}

			}
		}else{
			$query = $this->db->query("SELECT * FROM Job WHERE JobNo='$JobNo'");
			if ($query->num_rows()>0) {
				$row = $query->row();
				$JobNm = $row->JobNm;

				if ($row->TipeManajerial == 'JO Integrated') {
					$pesan = $JobNo.' - '.$JobNm.' merupakan JO Integrated.';
					is_pesan('warning',$pesan);
					redirect('Entry/PDPJ/EntryPDLap_C/index/'.$JobNo.'/'.$Alokasi);
				}

			}
		}


		if ($TipePD=='AddKSO') {
			$TipePD='RKD';
		}else if ($TipePD=='AddMIX') {
			$TipePD='MIX';
		}else{
			$TipePD='';
		}

		$TxtRedirect = 'Entry/PDPJ/EntryPDLap_C/CreatePD/NEW/'.$JobNo.'/'.$Alokasi.'/'.$TipePD;
		redirect($TxtRedirect);

	}

	function CreatePD($Aksi='NEW',$JobNo,$Alokasi,$TipePD=''){
		if ($JobNo=='' || $Alokasi=='') {
			is_pesan('success','Belum pilih Job/Alokasi.');
			redirect('Entry/PDPJ/EntryPDKeu_C/');
		}

		if ($Alokasi=='L') {
			is_pesan('danger','Tidak dapat tambah PD Alokasi L.');
			redirect('Entry/PDPJ/EntryPDKeu_C/');
		}

		$NoPD=NULL;
		$data['NoPD'] = $NoPD;
		$data['Aksi'] = $Aksi;
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['JobNm'] = $this->getJobNm($JobNo);
		$data['TipePD'] = $TipePD;

		$data['ListBank'] = $this->ListBank();
		$data['ListAlokasi'] = $this->getAlokasi();
		$data['ListTipeForm'] = $this->ListTipeForm($Alokasi);

		$TglPD = date('Y-m-d');
		$dapat_hitungan = $this->dapatkan_hari_senin_atau_monday($TglPD);
		$PrdAwal_minggu = $dapat_hitungan['PrdAwal_minggu'];
		$PrdAkhir_minggu = $dapat_hitungan['PrdAkhir_minggu'];
		$HitungMinggu = $this->hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu);

		$data['TglPD'] = $TglPD;
		$data['PrdAkhir'] = $PrdAkhir_minggu;
		$data['PrdAwal'] = $PrdAwal_minggu;
		$data['Minggu'] = $HitungMinggu;

		$data['title'] = 'Entry Permintaan Dana (Lapangan)';
		$data['judul'] = 'Entry Permintaan Dana (Lapangan)';
		$data['atur_kiri'] = '1';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';

		$data['js_saya'] = 'entry_view/PDPJ_View/EntryPDLap_VIEW/js_FormAdd_PDLap';


		$NoRef = NUll;
		if ($Aksi=='NEW') {
			if ($this->PD_Status=='Lapangan') {
				$NoRef 	= $this->Pembuatan_NoRef($JobNo,$Alokasi);
			}else{
				$NoRef 	= NULL;
			}
		}

		$data['NoRef'] = $NoRef;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/EntryPDLap_VIEW/FormAdd_PDLap', $data);
		$this->load->view('templates/footer');

	}

	function Pembuatan_NoRef($JobNo,$Alokasi){
		$LikeNoRef = "L".$JobNo.$Alokasi."%"; 
		$query = $this->db->query("SELECT TOP 1 NoRef FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND NoRef LIKE '$LikeNoRef' ORDER BY NoRef DESC ");
		$Kombinasi = 'L'.$JobNo.$Alokasi;
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$NoUrut = substr($row['NoRef'],-5) + 1;
			$NoUrut = sprintf("%05s", $NoUrut);
			$Kombinasi = $Kombinasi.''.$NoUrut;
		}else{
			$Kombinasi = $Kombinasi.'00001';
		}

		return $Kombinasi;
	}

	function BindKO(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$TipeForm = $this->input->post('TipeForm');
		$Aksi = $this->input->post('Aksi');
		$TipePD = $this->input->post('TipePD');

		$TmpQuery = "";

		if ($TipeForm=='04B') {
			$TmpQuery = " AND KategoriId='KONTRAK' ";
		}

		if ($TipeForm=='05B') {
			$TmpQuery = " AND KategoriId='PO' ";
		}

		if ($this->PD_Status=='Lapangan') {
			$TmpQuery = " AND KategoriId='KONTRAK' ";
		}

		$NoKO_Internal = $JobNo.'_INT';
		$html_opsi = "";
		$html_opsi .= '<option value="">:: Pilih Salah Satu ::</option>';
		$html_opsi .= '<option value="'.$NoKO_Internal.'">'.$NoKO_Internal.'</option>';

		$query = $this->db->query("SELECT * FROM KoHdr WHERE JobNo='".$JobNo."' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ".$TmpQuery." ORDER BY NoKO ASC ")->result_array();

		$TotalTerbayar = 0;

		foreach ($query as $row) {
			
			$NoKO = $row['NoKO'];

			if (substr($Aksi,0,3)=='SEE') {
				$html_opsi = '<option value="'.$NoKO.'">'.$NoKO.'</option>';
			}else{

				if ($row['ClosedBy']!='' || $row['ClosedBy']!=NULL ) {
					continue;
				}

				$TotalTerbayar = 0;

				// -- START CEK BLE
				$query_BLE = $this->db->query("SELECT SUM(Amount) AS Amount FROM BLE WHERE JobNo='$JobNo' AND NoKO='$NoKO' ");
				if ($query_BLE->num_rows() > 0) {
					$rowBLE = $query_BLE->row();
					$TotalTerbayar = ($rowBLE->Amount=='' || $rowBLE->Amount==NULL) ? 0 : (float) $rowBLE->Amount;
				}
				// -- END CEK BLE


				if ($TipeForm=='05B') {

					$Hitung = 0;

					$SubTotal = ($row['SubTotal']=='' || $row['SubTotal']==NULL) ? 0 : (float) $row['SubTotal'];
					$DiscAmount = ($row['DiscAmount']=='' || $row['DiscAmount']==NULL) ? 0 : (float) $row['DiscAmount'];
					$PPN = ($row['PPN']=='' || $row['PPN']==NULL) ? 0 : (float) $row['PPN'];

					$Hitung = ($SubTotal - $DiscAmount) + $PPN;
					if ($Hitung > $TotalTerbayar) {
						$html_opsi .= '<option value="'.$NoKO.'">'.$NoKO.'</option>';
					}
				}else{
					$html_opsi .= '<option value="'.$NoKO.'">'.$NoKO.'</option>';
				}

			}

		}


		echo $html_opsi;

	}


	function NoPJSebelumnya_Berganti(){
		if (!$this->input->post()) {
			die;
		}

		$NoPJSebelumnya = $this->input->post('NoPJSebelumnya');
		$Saldo = 0;
		$query = $this->db->query("SELECT ISNULL(Saldo,0) AS Saldo FROM PdHdr WHERE NoPJ='$NoPJSebelumnya' ");
		if ($query->num_rows() > 0) {
			$row = $query->row();
			$Saldo = (float) $row->Saldo;
		}

		echo number_format($Saldo);
	}

	function GetSaldo(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$TipeForm = $this->input->post('TipeForm');
		$Aksi = $this->input->post('Aksi');
		$TipePD = $this->input->post('TipePD');
		$NoPD = $this->input->post('NoPD');

		if ($Aksi!='NEW') {
			$query = $this->db->query("SELECT NoPJSebelumnya FROM PdHdr WHERE NoPD='".$NoPD."' ");
			if ($query->num_rows() > 0) {
				$row = $query->row_array();
				if ($row['NoPJSebelumnya']!='' ||  $row['NoPJSebelumnya']!=NULL ) {
					echo $row['NoPJSebelumnya'];
				}else{
					echo '';
				}
			}
		}

		// echo 'PJRKD22015C00036';

	}

	function BindPJ(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$TipeForm = $this->input->post('TipeForm');
		$Aksi = $this->input->post('Aksi');
		$TipePD = $this->input->post('TipePD');

		$KSO = ($TipePD=='RKD') ? 1 : 0;
		$NoRef_Validasi = ($this->PD_Status=='Lapangan') ? ' AND NoRef IS NOT NULL' : ' AND NoRef IS NULL';

		$TxtQuery = "WITH TmpQuery AS ( 
			SELECT NoPJ, Saldo,
			(SELECT COUNT(NoPJSebelumnya) FROM PdHdr B WHERE B.NoPJSebelumnya=PdHdr.NoPJ) AS 'AlreadyUsedPJ' 
			FROM PdHdr WHERE
			JobNo='".$JobNo."' AND TipeForm='".$TipeForm."' AND KSO=".$KSO." AND RejectBy IS NULL
			AND NoPJ IS NOT NULL ".$NoRef_Validasi." )
		SELECT * FROM TmpQuery WHERE AlreadyUsedPJ=0 AND Saldo<>0 ORDER BY NoPJ DESC";
		$Eks_Query = $this->db->query($TxtQuery);

		$List_NoPJSebelumnya = '<option value=""></option>';
		if ($Eks_Query->num_rows() > 0) {
			foreach($Eks_Query->result() as $row){
				$List_NoPJSebelumnya .= '<option value="'.$row->NoPJ.'">'.$row->NoPJ.'</option>';
			}
		}

		if ($this->input->post('NoPJSebelumnyaGlobal')) {
			if ($this->input->post('NoPJSebelumnyaGlobal')!='' || $this->input->post('NoPJSebelumnyaGlobal')!=NULL ) {
				$NoPJSebelumnyaGlobal = $this->input->post('NoPJSebelumnyaGlobal');
				$List_NoPJSebelumnya .= '<option value="'.$NoPJSebelumnyaGlobal.'">'.$NoPJSebelumnyaGlobal.'</option>';
			}
		}

		echo $List_NoPJSebelumnya;
	}

	function NoKO_Berganti(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$TipeForm = $this->input->post('TipeForm');
		$Aksi = $this->input->post('Aksi');
		$TipePD = $this->input->post('TipePD');
		$NoKO = $this->input->post('NoKO');
		$NoPD = $this->input->post('NoPD');

		$VendorId = NULL;
		$Nama = NULL;
		$Alamat = NULL;
		$Telepon = NULL;
		$NPWP = NULL;
		$NoRek = NULL;
		$AtasNama = NULL;
		$Bank = NULL;
		$status = NULL;

		if ($NoKO==$JobNo.'_INT') {
			$QueryJob = $this->db->query("SELECT * FROM Job WHERE JobNo='$JobNo'");
			$row = $QueryJob->row();

			$VendorId = NULL;
			$Nama = CekNull_Huruf($row->Nama);
			$Alamat = CekNull_Huruf($row->Alamat);
			$Telepon = CekNull_Huruf($row->Telepon);
			$NPWP = CekNull_Huruf($row->NPWP);
			$NoRek = CekNull_Huruf($row->NoRek);
			$AtasNama = CekNull_Huruf($row->AtasNama);
			$Bank = CekNull_Huruf($row->Bank);


		}else if($NoKO!=''){
			$queryVendor = $this->db->query("SELECT B.* FROM KoHdr A LEFT JOIN Vendor B ON A.VendorId=B.VendorId WHERE NoKO='$NoKO' ");
			if ($queryVendor->num_rows() > 0) {
				$row = $queryVendor->row();

				$VendorId = CekNull_Huruf($row->VendorId);
				$Nama = CekNull_Huruf($row->VendorNm);
				$Alamat = CekNull_Huruf($row->Alamat);
				$Telepon = CekNull_Huruf($row->Telepon1);
				$NPWP = CekNull_Huruf($row->NPWP);
				$NoRek = CekNull_Huruf($row->NoRek);
				$AtasNama = CekNull_Huruf($row->AtasNama);
				$Bank = CekNull_Huruf($row->Bank);
			}

			$this->Pindahkan_DataKoDtl_Ke_PdDtl($Nama);
			
		}

		$myarray = array(
			'VendorId' => $VendorId,
			'Nama' => $Nama,
			'Alamat' => $Alamat,
			'Telepon' => $Telepon,
			'NPWP' => $NPWP,
			'NoRek' => $NoRek,
			'AtasNama' => $AtasNama,
			'Bank' => $Bank,
		);

		echo json_encode($myarray);

		
	}

	function CekNoUrut($JobNo,$NoKO,$NoPD=NULL){
		
		$query_List_PdDtl = $this->db->query("SELECT ISNULL(NoUrut,0) AS NoUrut FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' ORDER BY NoUrut DESC ");

		if ($NoPD!=NULL || $NoPD!='') {
			$query_List_PdDtl = $this->db->query("SELECT ISNULL(NoUrut,0) AS NoUrut FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' AND NoPD='$NoPD' ORDER BY NoUrut DESC ");
		}

		if ($query_List_PdDtl->num_rows()>0) {
			return (int) $query_List_PdDtl->row()->NoUrut + 1;
		}else{
			return 1;
		}
	}

	function Simpan_Uraian_Ke_DB(){
		if (!$this->input->post()) {
			// json_encode(array('pesan','fail'));
			die;
		}

		// print_rr($_POST);
		// die;

		$JobNo = $this->input->post('JobNo');
		$NoKO = $this->input->post('NoKO');
		$Aksi = $this->input->post('Aksi');
		$NoPD = ($Aksi=='NEW') ? NULL : $this->input->post('NoPD');


		$NoUrut = $this->CekNoUrut($JobNo,$NoKO,$NoPD);
		$KdRAP = CekNull_Huruf(explode('|',$this->input->post('KdRAP'))[1]);
		$Tipe = $this->get_Tipe_KdRAP($JobNo,$KdRAP);
		$Uraian = CekNull_Huruf($this->input->post('Uraian'));
		$Uom = CekNull_Huruf($this->input->post('Uom'));
		$Uom = strtoupper($Uom);
		$Vol = ($this->input->post('Vol')=='' || $this->input->post('Vol')==NULL) ? 0 : $this->input->post('Vol');
		$Vol = CekDanHapusKoma($Vol);
		$Vol = (float) $Vol;

		$HrgSatuan = ($this->input->post('HrgSatuan')=='' || $this->input->post('HrgSatuan')==NULL) ? 0 : $this->input->post('HrgSatuan');
		$HrgSatuan = CekDanHapusKoma($HrgSatuan);
		$HrgSatuan = (float) $HrgSatuan;
		

		$this->db->trans_begin();

		try {
			$dataKoDtl = array(
				'NoPD' => $NoPD,
				'NoUrut' => $NoUrut,
				'KdRAP' => $KdRAP,
				'Tipe' => $Tipe,
				'Uraian' => $Uraian,
				'Vol' => $Vol,
				'Uom' => $Uom,
				'HrgSatuan' => $HrgSatuan,
				'NoKO' => $NoKO,
				'JobNo' => $JobNo,
				'Company' => $this->Company,
				'TimeEntry' => date('Y-m-d H:i:s'),
				'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
			);
			$this->db->insert('PdDtl_Temp_Keuangan',$dataKoDtl);
			echo 'berhasil';
			$this->db->trans_commit();
			
		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}
		
	}

	function Update_Uraian_Ke_DB(){
		if (!$this->input->post()) {
			// json_encode(array('pesan','fail'));
			die;
		}

		// print_rr($_POST);
		// die;

		$JobNo = $this->input->post('JobNo');
		$NoKO = $this->input->post('NoKO');
		$Aksi = $this->input->post('Aksi');
		$NoPD = ($Aksi=='NEW') ? NULL : $this->input->post('NoPD');
		$TipeForm = $this->input->post('TipeForm');

		$NoUrut = $this->input->post('NoUrut');
		$KdRAP = CekNull_Huruf(explode('|',$this->input->post('KdRAP'))[1]);
		$Tipe = $this->get_Tipe_KdRAP($JobNo,$KdRAP);
		$Uraian = CekNull_Huruf($this->input->post('Uraian'));
		$Uom = CekNull_Huruf($this->input->post('Uom'));
		$Uom = strtoupper($Uom);
		$Vol = ($this->input->post('Vol')=='' || $this->input->post('Vol')==NULL) ? 0 : $this->input->post('Vol');
		$Vol = CekDanHapusKoma($Vol);
		$Vol = (float) $Vol;

		$HrgSatuan = ($this->input->post('HrgSatuan')=='' || $this->input->post('HrgSatuan')==NULL) ? 0 : $this->input->post('HrgSatuan');
		$HrgSatuan = CekDanHapusKoma($HrgSatuan);
		$HrgSatuan = (float) $HrgSatuan;

		if (substr($NoKO,0,2)=='KO' && $TipeForm=='05B') {
			$MaxQty = 0;
			$TxtQuery = "";

			$query_satu = $this->db->query("SELECT ISNULL(Vol,0) AS Vol FROM KoDtl WHERE NoKO='$NoKO' AND NoUrut='$NoUrut' ");
			// echo $query_satu->num_rows();
			// die;
			if ($query_satu->num_rows() > 0) {
				$row_satu = $query_satu->row();
				$MaxQty = CekNull_Angka_Dan_Bikin_Float($row_satu->Vol);
			}

			if ($MaxQty > 0) {

				if ($Vol > $MaxQty) {
					echo 'Volume melebihi dari volume yg ada pada KO.';
					die;
				}

				if ($Aksi!='NEW') {
					$TxtQuery = " AND PdHdr.NoPD<>'".$NoPD."' ";
				}

				$Eks_Query = $this->db->query("
					SELECT ISNULL(SUM(Vol),0) AS Vol FROM PdDtl,PdHdr 
					WHERE PdDtl.NoPD=PdHdr.NoPD AND 
					PdHdr.NoKO='$NoKO' AND PdHdr.RejectBy IS NULL AND PdDtl.NoUrut='$NoUrut' AND PdHdr.Alokasi='B' ".$TxtQuery." ");

				if ($Eks_Query->num_rows() > 0) {
					$row_2 = $Eks_Query->row_array();
					$Vol_2 = (float) $row_2['Vol'];
					if (($Vol_2 + $Vol) > $MaxQty ) {
						echo 'Volume melebihi dari volume yg ada pada KO.';
						die;
					}

				}



			}

		}

		$this->db->trans_begin();

		try {
			
			$set_KoDtl = array(
				'KdRAP' => $KdRAP,
				'Tipe' => $Tipe,
				'Uraian' => $Uraian,
				'Vol' => $Vol,
				'Uom' => $Uom,
				'HrgSatuan' => $HrgSatuan,
				'TimeEntry' => date('Y-m-d H:i:s'),
				'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
			);

			$where_KoDtl = array(
				'NoUrut' => $NoUrut,
				'NoKO' => $NoKO,
				'JobNo' => $JobNo,
				'Company' => $this->Company,
			);
			if ($Aksi!='NEW') {
				$where_KoDtl['NoPD'] = $NoPD;
			}

			$this->db->set($set_KoDtl)->where($where_KoDtl)->update('PdDtl_Temp_Keuangan');

			echo 'berhasil';
			$this->db->trans_commit();
			
		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}
		
	}

	function get_Invoice_AddPD(){
		if (!$this->input->post()) {
			// json_encode(array('pesan','fail'));
			die;
		}

		// print_rr($_POST);
		$JobNo = $this->input->post('JobNo');
		$TipeForm = $this->input->post('TipeForm');
		$Aksi = $this->input->post('Aksi');
		$NoKO = $this->input->post('NoKO');
		$Nama = CekNull_Huruf($this->input->post('Nama'));

		$TotalInvoice = 0;
		$TotalBayar = 0;
		$TotalCentang = 0;
		
		$Array_InvPD = array();

		$MyQuery = $this->db->query("SELECT *, (SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS 'TotalPayment' FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO' ");

		if ($MyQuery->num_rows() > 0) {
			$NoUrutInvoice = 1;
			foreach ($MyQuery->result() as $row) :



				$TotalBayar = ($row->Total=='' || $row->Total==NULL ) ? 0 : (float) $row->Total;

				$Query_InvPD = $this->db->query("SELECT ISNULL(SUM(PaymentAmount),0) AS 'PaymentAmount' FROM InvPD WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND InvNo='$row->InvNo' ");

				if ($Query_InvPD->num_rows() > 0) :

					$row_InvPD = $Query_InvPD->row_array();

					if ($row_InvPD['PaymentAmount']!='' || $row_InvPD['PaymentAmount']!=NULL) {
						
						if ($row_InvPD['PaymentAmount'] >= $row->Total ) {
							continue;
						}

						$TotalBayar -= $row_InvPD['PaymentAmount'];
					}

				endif;

				$Array_InvPD[] = (object) array(
					'NoUrutInvoice' => $NoUrutInvoice++,
					'NoKO' => $NoKO,
					'VendorNm' => $Nama,
					'InvNo' => htmlspecialchars($row->InvNo),
					'InvDate' => CekNull_Untuk_Tanggal($row->InvDate),
					'DueDate' => CekNull_Untuk_Tanggal($row->DueDate),
					'Total' => CekNull_Angka_Dan_Bikin_Float($row->Total),
					'PPN' => CekNull_Angka_Dan_Bikin_Float($row->PPN),
					'FPNo' => htmlspecialchars($row->FPNo),
					'FPDate' => CekNull_Untuk_Tanggal($row->FPDate),
					'TotalBayar' => CekNull_Angka_Dan_Bikin_Float($TotalBayar),
					'TotalPayment' => CekNull_Angka_Dan_Bikin_Float($row->TotalPayment),
					'OriginalPayment' => 0,
					'IsChecked' => '0',
				);

				$TotalInvoice += CekNull_Angka_Dan_Bikin_Float($row->Total);

			endforeach;
		}

		// print_rr($Array_InvPD);

		$data['TotalInvoice'] = $TotalInvoice;
		$data['TotalCentang'] = 0;
		$data['List_Invoice'] = $Array_InvPD;

		$this->load->view('entry_view/PDPJ_View/EntryPDLap_VIEW/Invoice_AddPD', $data);
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
		// ListCB = If(CBInvoice.Checked = True, "INV", "")
		// ListCB = If(CBSJ.Checked = True, If(ListCB = "", "SJ", ListCB + "," + "SJ"), ListCB)
		// ListCB = If(CBPO.Checked = True, If(ListCB = "", "PO", ListCB + "," + "PO"), ListCB)
		// ListCB = If(CBFP.Checked = True, If(ListCB = "", "FP", ListCB + "," + "FP"), ListCB)
		// ListCB = If(CBBayar.Checked = True, If(ListCB = "", "BAP", ListCB + "," + "BAP"), ListCB)
		// ListCB = If(CBFisik.Checked = True, If(ListCB = "", "BAOP", ListCB + "," + "BAOP"), ListCB)
	}

	function get_tr_UraianPD(){
		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$TipeForm = $this->input->post('TipeForm');
		$Aksi = $this->input->post('Aksi');
		$TipePD = $this->input->post('TipePD');
		$NoKO = $this->input->post('NoKO');
		$NoPD = $this->input->post('NoPD');

		$NoPD_Cek = "";
		if ($Aksi=='NEW') {
			$NoPD_Cek = " AND NoPD IS NULL ";
		}else{
			$NoPD_Cek = " AND NoPD='".$NoPD."' ";
		}

		$TxtQuery = "SELECT *, (ISNULL(Vol,0) * ISNULL(HrgSatuan,0)) AS JumlahHarga FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' ".$NoPD_Cek." ORDER BY NoUrut ASC";

		$query_List_PdDtl = $this->db->query($TxtQuery)->result();
		$data['List_PdDtl'] = $query_List_PdDtl;

		$data['tombol_dimatikan'] = (substr($Aksi,0,3)=='SEE') ? 'disabled' : '';
		
		$this->load->view('entry_view/PDPJ_View/EntryPDLap_VIEW/tr_UraianPD', $data);
	}

	function get_KdRAP(){
		if (!$this->input->post()) {
			// json_encode(array('pesan','fail'));
			die;
		}

		// print_rr($_POST);
		// die;
		$JobNo = $this->input->post('JobNo');
		$TipeForm = $this->input->post('TipeForm');
		$Aksi = $this->input->post('Aksi');
		$TipePD = $this->input->post('TipePD');
		$Alokasi = $this->input->post('Alokasi');
		$NoKO = $this->input->post('NoKO');
		$NoPD = $this->input->post('NoPD');
		$NoUrut = $this->input->post('NoUrut');

		$KdRAP = $this->input->post('KdRAP');


		$NoKO_Internal = $JobNo.'_INT';

		
		$TxtQuery = "";
		$Alokasi_Validasi = "";

		if ($NoKO!=$NoKO_Internal) {

			$query_Kedua = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' AND NoUrut='$NoUrut' ");
			if ($query_Kedua->num_rows()>0) {
				$rowKedua = $query_Kedua->row();
				$Alokasi_Validasi = ($rowKedua->Alokasi=='' || $rowKedua->Alokasi==NULL) ? $Alokasi : $rowKedua->Alokasi;
			}else{	
				$Alokasi_Validasi = $Alokasi;
			}
		}else{
			$Alokasi_Validasi = $Alokasi;
		}

		$TxtQuery = "SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi_Validasi' ORDER BY NoUrut ASC";
		$Eksekusi_Query = $this->db->query($TxtQuery);

		$html ="";
		$html .='<option value="">:: Pilih Salah Satu ::</option>';
		if ($Eksekusi_Query->num_rows()>0) {
			foreach ($Eksekusi_Query->result() as $row) {

				$selected = "";
				if ($KdRAP!='' && ($row->KdRAP==$KdRAP) ) {
					$selected = "selected";
				}
				$html .= '<option value="'.$row->Tipe.'|'.$row->KdRAP.'"  '.$selected.'>'.$row->KdRAP.' - '.$row->Uraian.'</option>';
			}
		}
		echo $html;
	}

	function aksi_hapus_detail_multiple(){

		$this->db->trans_begin();

		try {

			if ($this->input->post('NoUrut')) {

				$NoKO = $this->input->post('NoKO');
				$JobNo = $this->input->post('JobNo');
				$NoUrut = trim($_POST['NoUrut']);	

				$Aksi = $this->input->post('Aksi');
				$NoPD = $this->input->post('NoPD');

				$NoPD_Cek = "";
				if ($Aksi=='NEW') {
					$NoPD_Cek = " AND NoPD IS NULL ";
				}else{
					$NoPD_Cek = " AND NoPD='".$NoPD."' ";
				}

				$sql = "DELETE FROM PdDtl_Temp_Keuangan WHERE NoUrut in ($NoUrut) AND  NoKO='$NoKO' AND Company='$this->Company' ".$NoPD_Cek." ";
				$this->db->query($sql);

			}

			echo 'berhasil';
			$this->db->trans_commit();
			
		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}
	}

	function Pindahkan_DataKoDtl_Ke_PdDtl($VendorNm=''){


		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$TipeForm = $this->input->post('TipeForm');
		$Aksi = $this->input->post('Aksi');
		$TipePD = $this->input->post('TipePD');
		$NoKO = $this->input->post('NoKO');
		$NoPD = $this->input->post('NoPD');

		$this->db->trans_begin();

		try {
			$NoPD_Cek = "";
			if ($Aksi=='NEW') {
				$NoPD_Cek = " AND NoPD IS NULL ";
			}else{
				$NoPD_Cek = " AND NoPD='".$NoPD."' ";
			}

			$delete_PdDtl_Temp_Keuangan = $this->db->query("DELETE FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' ".$NoPD_Cek." ");

			$query_KoDtl = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ")->result();

			$dataKoDtl = array();

			foreach($query_KoDtl AS $row){
				$NoUrut = (int) $row->NoUrut;
				$KdRAP = CekNull_Huruf($row->KdRAP);
				$Tipe = $this->get_Tipe_KdRAP($JobNo,$KdRAP);
				$Uraian = CekNull_Huruf($row->Uraian);
				$Vol = ($TipeForm=='05B') ? 0 : CekNull_Angka_Dan_Bikin_Float($row->Vol);
				$Uom = CekNull_Huruf($row->Uom);
				$HrgSatuan = CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan);

				$NoKO = $NoKO;
				$JobNo = $JobNo;
				$Company = $this->Company;

				$dataKoDtl[] = array(
					'NoUrut' => $NoUrut,
					'KdRAP' => $KdRAP,
					'Tipe' => $Tipe,
					'Uraian' => $Uraian,
					'Vol' => $Vol,
					'Uom' => $Uom,
					'HrgSatuan' => $HrgSatuan,
					'NoKO' => $NoKO,
					'JobNo' => $JobNo,
					'Company' => $Company,
					'TimeEntry' => date('Y-m-d H:i:s'),
					'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
				);

			}

			$this->db->insert_batch('PdDtl_Temp_Keuangan',$dataKoDtl);
			
			$this->db->trans_commit();

		} catch (Exception $e) {
			$this->db->trans_rollback();
		}

	}

	function Simpan_PD_Baru(){
		if (!$this->input->post()) {
			die;
		}



		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');
		
		$JobNo 		= $this->input->post('JobNo');
		$Alokasi 	= $this->input->post('Alokasi');

		// == START PENGECEKAN NOMOR PD
		$NoPD 		= ($this->input->post('NoPD')=='' || $this->input->post('NoPD')==NULL ) ? NULL : $this->input->post('NoPD');
		// if ($this->input->post('Aksi')=='NEW') {
		// 	$NoPD = $this->Pembuatan_NoPD($this->input->post('TipePD'),$JobNo,$Alokasi);
		// }else{
		// 	$NoPD = $this->input->post('NoPD');
		// }
		// == END PENGECEKAN NOMOR PD

		// -- START FORM SEBELAH KIRI
		$NoRef 		= ($this->PD_Status=='Lapangan') ?$this->input->post('NoRef') : NULL;
		// $NoRef 		= ($this->input->post('NoRef')) ? $this->input->post('NoRef') : NULL ;
		$TglPD 		= CekNull_Untuk_Tanggal($this->input->post('TglPD'));
		$Deskripsi 	= CekNull_Huruf($this->input->post('Deskripsi'));
		$PrdAwal 	= CekNull_Untuk_Tanggal($this->input->post('PrdAwal'));
		$PrdAkhir 	= CekNull_Untuk_Tanggal($this->input->post('PrdAkhir'));
		$Minggu 	= CekNull_Huruf($this->input->post('Minggu'));
		$Minggu  	= (int) $Minggu;
		$TipeForm 	= CekNull_Huruf($this->input->post('TipeForm'));
		$BuktiPendukung = $this->CekBuktiPendukung($this->input->post());
		$Keterangan = CekNull_Huruf($this->input->post('Keterangan'));

		$Jaminan 	= ($this->input->post('Jaminan')) ? 1 : 0;
		$PD_PPN 	= ($this->input->post('PD_PPN')) ? 1 : 0;
		// -- START FORM SEBELAH KIRI

		// == START Bayar Kepada 
		$NoKO 		= CekNull_Huruf($this->input->post('NoKO'));
		$NoTagihan 	= CekNull_Huruf($this->input->post('NoTagihan'));
		$NoTagihan 	= substr($NoTagihan,0,100);
		$Nama 		= CekNull_Huruf($this->input->post('Nama'));
		$Alamat 	= CekNull_Huruf($this->input->post('Alamat'));
		$Telepon 	= CekNull_Huruf($this->input->post('Telepon'));
		$NPWP 		= CekNull_Huruf($this->input->post('NPWP'));
		// == END Bayar Kepada

		// == START REKENING PENERIMA
		$NoRek 		= CekNull_Huruf($this->input->post('NoRek'));
		$AtasNama 	= CekNull_Huruf($this->input->post('AtasNama'));
		$Bank 		= CekNull_Huruf($this->input->post('Bank'));
		// == END REKENING PENERIMA

		$SubTotal 	= ($this->input->post('SubTotal')=='' || $this->input->post('SubTotal')==NULL) ? 0 : $this->input->post('SubTotal');
		$SubTotal 	= CekDanHapusKoma($SubTotal);
		$SubTotal 	= $SubTotal;

		$NoPJSebelumnya = CekNull_Huruf($this->input->post('NoPJSebelumnya'));

		$Saldo 	= ($this->input->post('Saldo')=='' || $this->input->post('Saldo')==NULL) ? 0 : $this->input->post('Saldo');
		$Saldo 	= CekDanHapusKoma($Saldo);
		$Saldo 	= (float) $Saldo;

		$TotalPermintaan 	= ($this->input->post('TotalPermintaan')=='' || $this->input->post('TotalPermintaan')==NULL) ? 0 : $this->input->post('TotalPermintaan');
		$TotalPermintaan 	= CekDanHapusKoma($TotalPermintaan);
		$TotalPermintaan 	= (float) $TotalPermintaan;


		$TotalCentang 	= ($this->input->post('TotalCentang')=='' || $this->input->post('TotalCentang')==NULL) ? 0 : $this->input->post('TotalCentang');
		$TotalCentang 	= CekDanHapusKoma($TotalCentang);
		$TotalCentang 	= (float) $TotalCentang;


		if ($this->PD_Status=='Lapangan' && ($NoRef=='' || $NoRef==NULL ) ) {
			echo 'No. BAP/Ref Lapangan	belum diisi.';
			die;
		}

		if ($Alokasi=='' || $Alokasi==NULL ) {
			echo 'Alokasi belum di-pilih.';
			die;
		}

		if ($TipeForm=='' || $TipeForm==NULL ) {
			echo 'Tipe Form belum di-pilih.';
			die;
		}

		if ($NoKO=='' || $NoKO==NULL ) {
			echo 'Belum pilih No Kontrak/PO.';
			die;
		}

		if ($BuktiPendukung=='' && $TipeForm=='05C' ) {
			echo 'Belum pilih bukti pendukung';
			die;
		}


		$TmpPrice = 0;
		// START PENGECEKAN KO
		if (substr($NoKO,0,2)=='KO') {

			if ($BuktiPendukung=='') {
				echo 'Belum pilih bukti pendukung';
				die;
			}

			if (!$this->input->post('checkbox_tercentang')) {
				echo 'belum pilih invoice';
				die;
			}


			// START VALIDASI INVOICE
			if (isset($_POST['checkbox_tercentang'])) {

				foreach($_POST['checkbox_tercentang'] as $key => $val){

					$InvNo = htmlspecialchars($_POST['InvNo'][$val]);
					$TotalBayar = ($_POST['TotalBayar'][$val]=='' || $_POST['TotalBayar'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['TotalBayar'][$val]);
					$TotalPayment = ($_POST['TotalPayment'][$val]=='' || $_POST['TotalPayment'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['TotalPayment'][$val]);

					$OriginalPayment = ($_POST['OriginalPayment'][$val]=='' || $_POST['OriginalPayment'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['OriginalPayment'][$val]);
					$Total = ($_POST['Total'][$val]=='' || $_POST['Total'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['Total'][$val]);

					$TmpPrice = $TotalBayar;
					$TotalPayment = $TotalPayment;
					$OriginalPayment = $OriginalPayment;
					$Total = $Total;

					if ($TmpPrice + ($TotalPayment - $OriginalPayment) > $Total ) {
						echo 'Total bayar invoice '.$InvNo.' melebihi total invoice.';
						die;
					}
				}
			}
			// END VALIDASI INVOICE

			$TotalKO = 0;
			$TotalPD = 0;
			$TotalTerbayar = 0;

			$Txt_query_PdHdr = "";
			if ($this->input->post('Aksi')=='NEW') {
				$Txt_query_PdHdr = "SELECT * FROM PdHdr WHERE NoKO='$NoKO' AND RejectBy IS NULL";
			}else{
				$Txt_query_PdHdr = "SELECT * FROM PdHdr WHERE NoPD!='$NoPD' AND NoKO='$NoKO' AND RejectBy IS NULL";
			}


			// == start nominal yang telah ada di PD
			$Query_PdHdr = $this->db->query($Txt_query_PdHdr);
			if ($Query_PdHdr->num_rows()>0) {
				foreach ($Query_PdHdr->result() as $row_PdHdr) {
					$TotalPD += ($row_PdHdr->TotalPD=='' || $row_PdHdr->TotalPD==NULL) ? 0 : $row_PdHdr->TotalPD;
				}
			}
			// == end nominal yang telah ada di PD


			// == start nominal yang telah di bayar
			$Query_BLE = $this->db->query("SELECT ISNULL(SUM(Amount),0) AS Amount FROM BLE WHERE NoKO='$NoKO' ");
			if ($Query_BLE->num_rows()>0) {
				$row_BLE = $Query_BLE->row_array();
				$TotalTerbayar += $row_BLE['Amount'];

			}
			// == end nominal yang telah di bayar

			$TotalPD += $SubTotal;
			
			// == start mendapatkan nominal Total Ko dari KoHdr
			$query_KoHdr = $this->db->query("SELECT * FROM KoHdr WHERE NoKO='$NoKO' ");
			if ($query_KoHdr->num_rows() > 0) {
				
				$row_KoHdr = $query_KoHdr->row_array();

				// -- Cek NoKO ini sudah di approved atau belum
				if ($row_KoHdr['ApprovedBy']=='' || $row_KoHdr['ApprovedBy']==NULL) {
					echo $NoKO.' belum di-approve';
					die;
				}

				$SubTotal_KoHdr = ($row_KoHdr['SubTotal']=='' || $row_KoHdr['SubTotal']==NULL) ? 0 : $row_KoHdr['SubTotal'];
				$DiscAmount = ($row_KoHdr['DiscAmount']=='' || $row_KoHdr['DiscAmount']==NULL) ? 0 : $row_KoHdr['DiscAmount'];
				$PPN = ($row_KoHdr['PPN']=='' || $row_KoHdr['PPN']==NULL) ? 0 : $row_KoHdr['PPN'];

				$TotalKO = ($SubTotal_KoHdr - $DiscAmount) + $PPN;
				$pesan = "";
				if ($TotalKO<$TotalPD) {
					$pesan = "Total Akumulasi PD melebihi nilai Kontrak/PO.\n";
					$pesan = $pesan." Nilai Kontrak/PO ".$NoKO." : ".number_format($TotalKO)."\n";
					$pesan = $pesan."Total Akumulasi PD termasuk current : ".number_format($TotalPD)."\n";
					$pesan = $pesan."Nilai Kontrak/PO yang sudah dibayar : ".number_format($TotalTerbayar)."\n";
					echo $pesan;
					die;
					
				}

				if ($TotalCentang<>$SubTotal) {
					echo 'Total Invoice <> Total Permintaan.';
					die;
				}


			}
			// == end mendapatkan nominal Total Ko dari KoHdr

		}
		// END PENGECEKAN KO

		if ($Bank=='' || $Bank==NULL ) {
			echo 'Bank rekening penerima belum di-pilih.';
			die;
		}

		// print_rr($_POST);
		// die;

		$this->db->trans_begin();

		try {

			$TipePD = $this->input->post('TipePD');
			$NoPD = ($this->input->post('Aksi')=='NEW') ? $this->AssignNoPD($TipePD,$JobNo,$Alokasi) : $NoPD;

			if ($this->input->post('Aksi')=='NEW') {
				// == AKSI UNTUK TABLE PdHdr
				$dataInsert_To_PdHdr = array(
					'NoPD' 			=> $NoPD,
					'NoRef' 		=> $NoRef,
					'JobNo' 		=> $JobNo,
					'TglPD' 		=> $TglPD,
					'Deskripsi' 	=> $Deskripsi,
					'PrdAwal' 		=> $PrdAwal,
					'PrdAkhir' 		=> $PrdAkhir,
					'Minggu' 		=> $Minggu,
					'Alokasi' 		=> $Alokasi,
					'TipeForm' 		=> $TipeForm,
					'TotalPD' 		=> $SubTotal,
					'BuktiPendukung'=> $BuktiPendukung,
					'NoKO' 			=> $NoKO,
					'NoTagihan' 	=> $NoTagihan,
					'Nama' 			=> $Nama,
					'Alamat' 		=> $Alamat,
					'Telepon' 		=> $Telepon,
					'NPWP' 			=> $NPWP,
					'NoRek' 		=> $NoRek,
					'Bank' 			=> $Bank,
					'AtasNama' 		=> $AtasNama,
					'KSO' 			=> ($TipePD=='RKD') ? 1 : 0,
					'Keterangan' 	=> $Keterangan,
					'NoPJSebelumnya'=> $NoPJSebelumnya,
					'Jaminan' 		=> $Jaminan,
					'PD_PPN' 		=> $PD_PPN,
					'UserEntry' 	=> $UserEntry,
					'TimeEntry' 	=> $TimeEntry,
				);

				$this->db->insert('PdHdr',$dataInsert_To_PdHdr);

			}else{
				// == AKSI UNTUK TABLE PdHdr - PROSES PD UPDATE
				$setUpdate_PdHdr = array(
					'TglPD' 		=> $TglPD,
					'Deskripsi' 	=> $Deskripsi,
					'PrdAwal' 		=> $PrdAwal,
					'PrdAkhir' 		=> $PrdAkhir,
					'Minggu' 		=> $Minggu,
					'TotalPD' 		=> $SubTotal,
					'BuktiPendukung'=> $BuktiPendukung,
					'NoKO' 			=> $NoKO,
					'NoTagihan' 	=> $NoTagihan,
					'Nama' 			=> $Nama,
					'Alamat' 		=> $Alamat,
					'Telepon' 		=> $Telepon,
					'NPWP' 			=> $NPWP,
					'NoRek' 		=> $NoRek,
					'Bank' 			=> $Bank,
					'AtasNama' 		=> $AtasNama,
					'NoRef' 		=> $NoRef,
					'TipeForm' 		=> $TipeForm,
					'Keterangan' 	=> $Keterangan,
					'NoPJSebelumnya'=> $NoPJSebelumnya,
					'Jaminan' 		=> $Jaminan,
					'PD_PPN' 		=> $PD_PPN,
					'UserEntry' 	=> $UserEntry,
					'TimeEntry' 	=> $TimeEntry,
				);

				$whereUpdate_PdHdr = array('NoPD' => $NoPD);
				$this->db->set($setUpdate_PdHdr)->where($whereUpdate_PdHdr)->update('PdHdr');


				// == HAPUS TABLE PdDtl - PROSES PD UPDATE
				$this->db->query("DELETE PdDtl WHERE NoPD='$NoPD' ");

				// == HAPUS TABLE InvPD - PROSES PD UPDATE
				$this->db->query("DELETE InvPD WHERE NoPD='$NoPD' ");

			}

			// == AKSI INSERT INTO KE TABLE PdDtl
			// $this->db->query("INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry)
			// 	SELECT '".$NoPD."',NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,'".$UserEntry."','".$TimeEntry."' FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' ");

			$NoPD_Cek = "";
			if ($this->input->post('Aksi')=='NEW') {
				$NoPD_Cek = " AND NoPD IS NULL ";
			}else{
				$NoPD_Cek = " AND NoPD='".$NoPD."' ";
			}

			$Cek_PdDtl_Temp_Keuangan = $this->db->query("SELECT * FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' ".$NoPD_Cek." ");



			if ($Cek_PdDtl_Temp_Keuangan->num_rows()>0) {
				$Array_PdDtl_Buatan = array();
				$NoUrut_Buatan = 0;
				foreach ($Cek_PdDtl_Temp_Keuangan->result() as $myrow) {
					
					$NoUrut_Buatan++;
					
					$KdRAP = CekNull_Huruf($myrow->KdRAP);
					$Uraian = CekNull_Huruf($myrow->Uraian);
					$Vol = CekNull_Angka_Dan_Bikin_Float($myrow->Vol);
					$Uom = CekNull_Huruf($myrow->Uom);
					$HrgSatuan = CekNull_Angka_Dan_Bikin_Float($myrow->HrgSatuan);

					$VersiRAP = PengecekanVersiRAP($KdRAP,$JobNo,$Alokasi);
					
					$Array_PdDtl_Buatan[] = array(

						'NoPD' => $NoPD,
						'NoUrut' => $NoUrut_Buatan,
						'KdRAP' => $KdRAP,
						'Uraian' => $Uraian,
						'Vol' => $Vol,
						'Uom' => $Uom,
						'HrgSatuan' => $HrgSatuan,
						'UserEntry' => $UserEntry,
						'TimeEntry' => $TimeEntry,
						'VersiRAP' => $VersiRAP,
					);
				}

				$this->db->insert_batch('PdDtl',$Array_PdDtl_Buatan);
			}

			
			// HAPUS DATA DI PdDtl_Temp_Keuangan setelah insert ke PdDtl
			$this->db->query("DELETE PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' ".$NoPD_Cek." ");

			// INSERT INVOICE YANG TELAH DI CENTANG TADI KE TABLE InvPD
			if (isset($_POST['checkbox_tercentang'])) {
				$dataInvoice_Insert = array();
				foreach($_POST['checkbox_tercentang'] as $key => $val){

					$InvNo = htmlspecialchars($_POST['InvNo'][$val]);
					$TotalBayar = ($_POST['TotalBayar'][$val]=='' || $_POST['TotalBayar'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['TotalBayar'][$val]);

					$TotalBayar = (float) $TotalBayar;

					$dataInvoice_Insert[] = array(
						'JobNo' => $JobNo,
						'NoKO' => $NoKO,
						'InvNo' => $InvNo,
						'NoPD' => $NoPD,
						'PaymentAmount' => $TotalBayar,
						'UserEntry' => $UserEntry,
						'TimeEntry' => $TimeEntry,
					);
				}
				$this->db->insert_batch('InvPD',$dataInvoice_Insert);
				
			}
			
			// $this->db->query("DELETE InvPD_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company'")
			// $this->db->query("DELETE FROM Invoice_Temp_Keuangan WHERE NoKO='$NoKO' AND JobNo='$JobNo' AND Company='$this->Company' ");


			is_pesan('success','Sukses, data telah ditambahkan');
			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}

	}

	function AssignNoPD($TipePD,$JobNo,$Alokasi){
		// == START PEMBUATAN NO PD
		$TxtQuery_Counter = "";
		$NoPD_Buatan = NULL;
		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		if ($TipePD=='RKD') {
			$TxtQuery_Counter = "SELECT CounterKSO FROM Counter WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ";
		}else if($TipePD=='MIX'){
			$TxtQuery_Counter = "SELECT CounterPDMIX FROM Counter WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ";
		}else{
			$TxtQuery_Counter = "SELECT CounterPD FROM Counter WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ";
		}

		$eksekusi_QueryCounter = $this->db->query($TxtQuery_Counter);

		if ($eksekusi_QueryCounter->num_rows()>0) {

			$rowCounter = $eksekusi_QueryCounter->row_array();

				// -- START TAMBAH PD TIPE PDRKD
			$NoUrut_Counter = 0;

			if ($TipePD=='RKD') {

				if ($rowCounter['CounterKSO']=='' || $rowCounter['CounterKSO']==NULL) {
					$NoUrut_Counter = 1;
				}else{
					$NoUrut_Counter = (int) $rowCounter['CounterKSO'] + 1;
				}

				$QueryUpdate_Counter = $this->db->query("UPDATE Counter SET CounterKSO='$NoUrut_Counter',UserEntry='$UserEntry',TimeEntry='$TimeEntry' WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ");

			}else if ($TipePD=='MIX') {

				if ($rowCounter['CounterPDMIX']=='' || $rowCounter['CounterPDMIX']==NULL) {
					$NoUrut_Counter = 1;
				}else{
					$NoUrut_Counter = (int) $rowCounter['CounterPDMIX'] + 1;
				}

				$QueryUpdate_Counter = $this->db->query("UPDATE Counter SET CounterPDMIX='$NoUrut_Counter',UserEntry='$UserEntry',TimeEntry='$TimeEntry' WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ");

			}else{

				if ($rowCounter['CounterPD']=='' || $rowCounter['CounterPD']==NULL) {
					$NoUrut_Counter = 1;
				}else{
					$NoUrut_Counter = (int) $rowCounter['CounterPD'] + 1;
				}


				$QueryUpdate_Counter = $this->db->query("UPDATE Counter SET CounterPD='$NoUrut_Counter',UserEntry='$UserEntry',TimeEntry='$TimeEntry' WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ");

			}


			if ($TipePD=='RKD') {
				$NoPD_Buatan = 'PDRKD'.$JobNo.$Alokasi.sprintf("%05s", $NoUrut_Counter);
			}else if ($TipePD=='MIX'){
				$NoPD_Buatan = 'PDMIX'.$JobNo.$Alokasi.sprintf("%05s", $NoUrut_Counter);
			}else{
				$NoPD_Buatan = 'PD'.$JobNo.$Alokasi.sprintf("%05s", $NoUrut_Counter);
			}


		}else{

			if ($TipePD=='RKD') {

				$dataCounter = array(
					'JobNo' => $JobNo,
					'Alokasi' => $Alokasi,
					'CounterKSO' => 1,
					'UserEntry' => $UserEntry,
					'TimeEntry' => $TimeEntry,
				);

				$this->db->insert('Counter',$dataCounter);

			}else if ($TipePD=='MIX'){

				$dataCounter = array(
					'JobNo' => $JobNo,
					'Alokasi' => $Alokasi,
					'CounterPDMIX' => 1,
					'UserEntry' => $UserEntry,
					'TimeEntry' => $TimeEntry,
				);

				$this->db->insert('Counter',$dataCounter);

			}else{

				$dataCounter = array(
					'JobNo' => $JobNo,
					'Alokasi' => $Alokasi,
					'CounterPD' => 1,
					'UserEntry' => $UserEntry,
					'TimeEntry' => $TimeEntry,
				);

				$this->db->insert('Counter',$dataCounter);

			}

		}

		return $NoPD_Buatan;

		// == START PEMBUATAN NO PD
	}

	function get_Tipe_KdRAP($JobNo,$KdRAP){
		$KdRAP = htmlspecialchars($KdRAP);
		$query = $this->db->query("SELECT ISNULL(Tipe,NULL) AS Tipe FROM RAP WHERE JobNo='".$JobNo."' AND KdRAP='".$KdRAP."' ");
		if ($query->num_rows()>0) {
			$row = $query->row();
			return $row->Tipe;
		}else{
			return NULL;
		}

	}

	function dapatkan_hari_senin_atau_monday ($TglPD=''){

		$TglPD_nama_hari = date('l', strtotime($TglPD));
		$TglPD_tanggal = ($TglPD_nama_hari == 'Sunday') ? date('Y-m-d',strtotime('-1 days',strtotime($TglPD))) : date('Y-m-d',strtotime('+1 days',strtotime($TglPD)));
		$PrdAwal_minggu = date('Y-m-d', strtotime('previous monday',strtotime($TglPD_tanggal)));
		$PrdAkhir_minggu = date('Y-m-d',strtotime('+6 days',strtotime($PrdAwal_minggu)));
		return array('PrdAwal_minggu' => $PrdAwal_minggu, 'PrdAkhir_minggu' => $PrdAkhir_minggu);
	}

	function hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu){

		$dapat_hasil = '';
		$query_get_PrdAwal = $this->db->query("SELECT PrdAwal FROM Job WHERE JobNo='$JobNo' ")->row();
		$PrdAwal_minggu = $query_get_PrdAwal->PrdAwal;
		if ($PrdAwal_minggu == NULL || $PrdAwal_minggu == '' ) {
			$dapat_hasil = 0;
			return $dapat_hasil;
		}

		$diff = strtotime($PrdAkhir_minggu, 0) - strtotime($PrdAwal_minggu, 0);
		$diff = floor($diff / 604800) + 1;
		$dapat_hasil = $diff;
		return $dapat_hasil;
	}

	function dapat_data_ketika_TglPD_berubah_js(){
		if (!$this->input->post()) {
			echo json_encode(array('status'=>'gagal'));
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$TglPD = $this->input->post('TglPD');

		$dapat_hitungan = $this->dapatkan_hari_senin_atau_monday($TglPD);
		$PrdAwal_minggu = $dapat_hitungan['PrdAwal_minggu'];
		$PrdAkhir_minggu = $dapat_hitungan['PrdAkhir_minggu'];
		$HitungMinggu = $this->hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu);

		echo json_encode(
			array(
				'status' => 'berhasil',
				'PrdAwal' => $PrdAwal_minggu,
				'PrdAkhir' => $PrdAkhir_minggu,
				'Minggu' => $HitungMinggu
			)
		);

	}

	function getJobNm($JobNo){
		$query = $this->db->query("SELECT ISNULL(JobNm,NULL) AS 'JobNm' FROM Job WHERE JobNo='$JobNo'  ")->row();
		return $query->JobNm;
	}

	function ListBank(){
		return $this->db->get('Bank')->result();
	}

	function getAlokasi(){
		return $this->db->select('Alokasi,Keterangan')->get('Alokasi')->result();
	}

	function ListTipeForm($Alokasi){

		$query = $this->db->query("SELECT ISNULL(TipeForm,NULL) AS TipeForm, ISNULL(Keterangan,NULL) AS Keterangan  FROM TipeForm WHERE Alokasi='$Alokasi'  ");

		$Arr = array();
		if ($query->num_rows() > 0 ) {
			foreach ($query->result() as $row) {
				if ($this->PD_Status=='Lapangan') {
					if ($row->TipeForm=='05B') {
						continue;
					}

					$Arr[] = (object) array(
						'TipeForm' => $row->TipeForm,
						'Keterangan' => $row->Keterangan,
					);


				}
			}
		}

		return $Arr;
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

	function CekStatusApprovePD(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);
		$NoPD = $this->input->post('NoPD');

		$query = $this->db->query("SELECT NoPD, NoRef, ApprovedByKT, ApprovedByKK, RejectBy, (SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE FROM PdHdr WHERE NoPD='$NoPD' ");

		if ($query->num_rows() > 0) {

			$row = $query->row_array();
			if ($row['RejectBy']!='' || $row['RejectBy']!=NULL) {
				echo $NoPD.' sudah di-reject oleh '.$row['RejectBy'];
				die;
			}

			if ($row['CountBLE']>0) {
				echo 'Sudah ada pembayaran untuk '.$NoPD;
				die;
			}

			if ($row['ApprovedByKT']!='' || $row['ApprovedByKT']!=NULL) {
				echo $NoPD.' sudah di-approved oleh Cost Control. ';
				die;
			}

			if ($row['ApprovedByKK']!='' || $row['ApprovedByKK']!=NULL) {
				echo $NoPD.' sudah di-approved oleh Keuangan. ';
				die;
			}

			echo 'lanjut';


		}else{
			echo 'data tidak ditemukan';
		}
		

	}

	function lakukanApprove(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD = $this->input->post('NoPD');
		$TxtApprove = $this->input->post('TxtApprove');

		$this->db->trans_begin();

		try {
			$set = array(
				'ApprovedByAK' => ($TxtApprove=='APPROVE') ? $this->session->userdata('MIS_LOGGED_NAME') : NULL,
				'TimeApprovedAK' => ($TxtApprove=='APPROVE') ? date('Y-m-d H:i:s') : NULL,
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

	function UpdateFormPD($Aksi='UPD',$JobNo,$Alokasi,$NoPD,$TipePD=''){


		if ($NoPD=='') {
			is_pesan('warning','Something Wrong');
			redirect('Entry/PDPJ/EntryPDKeu_C/');
		}

		if ($JobNo=='' || $Alokasi=='') {
			is_pesan('warning','Belum pilih Job/Alokasi.');
			redirect('Entry/PDPJ/EntryPDKeu_C/');
		}

		if ($Alokasi=='L') {
			is_pesan('success','Tidak dapat tambah PD Alokasi L.');
			redirect('Entry/PDPJ/EntryPDKeu_C/');
		}


		if ($this->session->userdata('sesiKembali')) {

			if ($this->session->userdata('sesiKembali')=='FrmApprovalPD_KT') {
				$data["Url_Batal"] =  site_url('Entry/PDPJ/FrmApprovalPD_KT/index/'.$JobNo.'/'.$Alokasi);
			}else if($this->session->userdata('sesiKembali')=='FrmApprovalPD_KK'){
				$data["Url_Batal"] = site_url('Entry/PDPJ/FrmApprovalPD_KT/index/'.$JobNo.'/'.$Alokasi);
			}else{
				$data["Url_Batal"] = site_url('Entry/PDPJ/EntryPDLap_C/index/'.$JobNo.'/'.$Alokasi);
			}	
		}
		else{
			$data["Url_Batal"] = site_url('Entry/PDPJ/EntryPDLap_C/index/'.$JobNo.'/'.$Alokasi);
		}
		// $data["Url_Batal"] = site_url('Entry/PDPJ/EntryPDLap_C/index/'.$JobNo.'/'.$Alokasi);
		// echo $data['Url_Batal'];
		// die;	

		// $data["Url_Batal"] = $this->Url_Batal.'/index/'.$JobNo.'/'.$Alokasi;
		// echo $this->Url_Batal;
		// die;

		$data['NoPD'] = $NoPD;
		$data['Aksi'] = $Aksi;
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['JobNm'] = $this->getJobNm($JobNo);
		$data['TipePD'] = $TipePD;

		$data['ListBank'] = $this->ListBank();
		$data['ListAlokasi'] = $this->getAlokasi();
		$data['ListTipeForm'] = $this->ListTipeForm($Alokasi);

		$data['title'] = 'Update Permintaan Dana (Lapangan)';
		$data['judul'] = 'Update Permintaan Dana (Lapangan)';

		$data['atur_kiri'] = '1';
		$data['bodyclass'] = 'sidebar-collapse skin-red-light';

		$data['js_saya'] = 'entry_view/PDPJ_View/EntryPDLap_VIEW/js_FormUpdate_PDLap';

		$dataUtamaPD = $this->db->query("SELECT * FROM PdHdr WHERE NoPD='$NoPD'")->row();
		$NoKO = $dataUtamaPD->NoKO;
		// $query_KoDtl = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$NoPD' ")->result();
		$data['dataUtamaPD'] = $dataUtamaPD;

		$this->db->trans_begin();
		try {

			$this->db->query("DELETE FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' AND NoPD='$NoPD' ");

			$query_KoDtl = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$NoPD'");

			$dataKoDtl = array();
			if ($query_KoDtl->num_rows() > 0) {
				foreach($query_KoDtl->result() AS $row){
					$NoUrut = (int) $row->NoUrut;
					$KdRAP = CekNull_Huruf($row->KdRAP);
					$Tipe = $this->get_Tipe_KdRAP($JobNo,$KdRAP);
					$Uraian = CekNull_Huruf($row->Uraian);
					$Vol =  CekNull_Angka_Dan_Bikin_Float($row->Vol);
					$Uom = CekNull_Huruf($row->Uom);
					$HrgSatuan = CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan);

					$NoKO = $NoKO;
					$JobNo = $JobNo;
					$Company = $this->Company;

					$dataKoDtl[] = array(
						'NoPD' => $NoPD,
						'NoUrut' => $NoUrut,
						'KdRAP' => $KdRAP,
						'Tipe' => $Tipe,
						'Uraian' => $Uraian,
						'Vol' => $Vol,
						'Uom' => $Uom,
						'HrgSatuan' => $HrgSatuan,
						'NoKO' => $NoKO,
						'JobNo' => $JobNo,
						'Company' => $Company,
						'TimeEntry' => date('Y-m-d H:i:s'),
						'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
					);

				}
				$this->db->insert_batch('PdDtl_Temp_Keuangan',$dataKoDtl);
			}

			

			
			
			$this->db->trans_commit();

		} catch (Exception $e) {
			$this->db->trans_rollback();
		}

		$data['tombol_dimatikan'] = (substr($Aksi,0,3)=='SEE') ? 'disabled' : '';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/EntryPDLap_VIEW/FormUpdate_PDKeu', $data);
		$this->load->view('templates/footer');

	}

	function PerpindahanData_PdDtl_To_PdDtl_Temp($JobNo,$Alokasi,$TipeForm,$Aksi='',$TipePD='',$NoKO,$NoPD){
		$this->db->trans_begin();
		try {

			$this->db->query("DELETE FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' AND NoPD='$NoPD' ");

			$query_KoDtl = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$NoPD'")->result();

			$dataKoDtl = array();

			foreach($query_KoDtl AS $row){
				$NoUrut = (int) $row->NoUrut;
				$KdRAP = CekNull_Huruf($row->KdRAP);
				$Tipe = $this->get_Tipe_KdRAP($JobNo,$KdRAP);
				$Uraian = CekNull_Huruf($row->Uraian);
				$Vol =  CekNull_Angka_Dan_Bikin_Float($row->Vol);
				$Uom = CekNull_Huruf($row->Uom);
				$HrgSatuan = CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan);

				$NoKO = $NoKO;
				$JobNo = $JobNo;
				$Company = $this->Company;

				$dataKoDtl[] = array(
					'NoPD' => $NoPD,
					'NoUrut' => $NoUrut,
					'KdRAP' => $KdRAP,
					'Tipe' => $Tipe,
					'Uraian' => $Uraian,
					'Vol' => $Vol,
					'Uom' => $Uom,
					'HrgSatuan' => $HrgSatuan,
					'NoKO' => $NoKO,
					'JobNo' => $JobNo,
					'Company' => $Company,
					'TimeEntry' => date('Y-m-d H:i:s'),
					'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
				);

			}

			$this->db->insert_batch('PdDtl_Temp_Keuangan',$dataKoDtl);
			
			$this->db->trans_commit();

		} catch (Exception $e) {
			$this->db->trans_rollback();
		}
	}

	function get_Invoice_AddPD_Update(){
		if (!$this->input->post()) {
			// json_encode(array('pesan','fail'));
			die;
		}

		// print_rr($_POST);
		// die;
		$JobNo = $this->input->post('JobNo');
		$TipeForm = $this->input->post('TipeForm');
		$Aksi = $this->input->post('Aksi');
		$NoKO = $this->input->post('NoKO');
		$Nama = CekNull_Huruf($this->input->post('Nama'));
		$NoPD = $this->input->post('NoPD');

		$TotalInvoice=0;
		$TotalBayar=0;


		// $JobNo = '23029';
		// $NoKO = 'KO23029002';
		// $NoPD = 'PD23029B00109';
		// $Nama = 'dddd';

		$TxtQuery = "SELECT *, (SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS TotalPayment FROM Invoice WHERE JobNo='".$JobNo."' AND NoKO='".$NoKO."' ";
		$Eks_TxtQuery = $this->db->query($TxtQuery);

		$Array_InvPD = array();

		$NoUrutInvoice1 = 0;
		$InvNo1 = NULL;
		$InvDate1 = NULL;
		$DueDate1 = NULL;
		$Total1 = 0;
		$PPN1 = 0;
		$FPNo1 = NULL;
		$FPDate1 = NULL;
		$PaymentAmount1 = 0;
		$TotalPayment1 = 0;
		$PaymentAmount2 = 0;
		$IsChecked1 = 0;


		if ($Eks_TxtQuery->num_rows() > 0) {
			$NoUrutInvoice = 1;
			foreach ($Eks_TxtQuery->result_array() as $row) {
				$InvNo = $row['InvNo'];

				$InvPDQuery = $this->db->query("SELECT * FROM InvPD WHERE JobNo='".$JobNo."' AND NoPD='".$NoPD."' AND NoKO='".$NoKO."' AND InvNo='".$InvNo."' ");

				if ($InvPDQuery->num_rows() > 0 ) {

					$TotalInvoice += CekNull_Angka_Dan_Bikin_Float($row['Total']);
					$InvPD_Row = $InvPDQuery->row();
					$TotalBayar += $InvPD_Row->PaymentAmount;

					$Array_InvPD[] = (object) array(
						'NoUrutInvoice' => $NoUrutInvoice++,
						'NoKO' => $row['NoKO'],
						'VendorNm' => $Nama,
						'InvNo' => htmlspecialchars($row['InvNo']),
						'InvDate' => CekNull_Untuk_Tanggal($row['InvDate']),
						'DueDate' => CekNull_Untuk_Tanggal($row['DueDate']),
						'Total' => ($row['Total']==NULL || $row['Total']=='') ? 0 : $row['Total'],
						'PPN' => ($row['PPN']==NULL || $row['PPN']=='') ? 0 : $row['PPN'],
						'FPNo' => htmlspecialchars($row['FPNo']),
						'FPDate' => $row['FPDate'],
						'TotalBayar' => ($InvPD_Row->PaymentAmount==NULL || $InvPD_Row->PaymentAmount=='') ? 0 : $InvPD_Row->PaymentAmount,
						'TotalPayment' => ($row['TotalPayment']==NULL || $row['TotalPayment']=='') ? 0 : $row['TotalPayment'],
						'OriginalPayment' => ($InvPD_Row->PaymentAmount==NULL || $InvPD_Row->PaymentAmount=='') ? 0 : $InvPD_Row->PaymentAmount,
						'IsChecked' => 1,
						'JobNo' => $JobNo,
						'NoPD' => $NoPD,
						'Company' => $this->Company,

					);

					// $NoUrutInvoice1 	= $NoUrutInvoice++;
					// $InvNo1 			= $row['InvNo'];
					// $InvDate1 			= $row['InvDate'];
					// $DueDate1 			= $row['DueDate'];
					// $Total1 			= ($row['Total']==NULL || $row['Total']=='') ? 0 : $row['Total'];
					// $PPN1 				= ($row['PPN']==NULL || $row['PPN']=='') ? 0 : $row['PPN'];
					// $FPNo1 				= $row['FPNo'];
					// $FPDate1 			= $row['FPDate'];
					// $PaymentAmount1 	= ($InvPD_Row->PaymentAmount==NULL || $InvPD_Row->PaymentAmount=='') ? 0 : $InvPD_Row->PaymentAmount;
					// $TotalPayment1 		= ($row['TotalPayment']==NULL || $row['TotalPayment']=='') ? 0 : $row['TotalPayment'];
					// $PaymentAmount2 	= ($InvPD_Row->PaymentAmount==NULL || $InvPD_Row->PaymentAmount=='') ? 0 : $InvPD_Row->PaymentAmount;
					// $IsChecked1 		= 1;

				}else{
					
					if (CekNull_Angka_Dan_Bikin_Float($row['Total']) <= CekNull_Angka_Dan_Bikin_Float($row['TotalPayment'])) {
						continue;
					}

					$TotalInvoice += CekNull_Angka_Dan_Bikin_Float($row['Total']);

					$dapat_PaymentAmount = 0;
					if ($row['TotalPayment']==NULL || $row['TotalPayment']=='') {
						$dapat_PaymentAmount = CekNull_Angka_Dan_Bikin_Float($row['Total']);
					}else{
						$dapat_PaymentAmount = (CekNull_Angka_Dan_Bikin_Float($row['Total']) - CekNull_Angka_Dan_Bikin_Float($row['TotalPayment']));
					}

					$Array_InvPD[] = (object) array(
						'NoUrutInvoice' => $NoUrutInvoice++,
						'NoKO' => $row['NoKO'],
						'VendorNm' => $Nama,
						'InvNo' => htmlspecialchars($row['InvNo']),
						'InvDate' => CekNull_Untuk_Tanggal($row['InvDate']),
						'DueDate' => CekNull_Untuk_Tanggal($row['DueDate']),
						'Total' => ($row['Total']==NULL || $row['Total']=='') ? 0 : $row['Total'],
						'PPN' => ($row['PPN']==NULL || $row['PPN']=='') ? 0 : $row['PPN'],
						'FPNo' => htmlspecialchars($row['FPNo']),
						'FPDate' => CekNull_Untuk_Tanggal($row['FPDate']),
						'TotalBayar' => $dapat_PaymentAmount,
						'TotalPayment' => ($row['TotalPayment']==NULL || $row['TotalPayment']=='') ? 0 : $row['TotalPayment'],
						'OriginalPayment' => 0,
						'IsChecked' => 0,
						'JobNo' => $JobNo,
						'NoPD' => $NoPD,
						'Company' => $this->Company,

					);

					// $NoUrutInvoice1 	= $NoUrutInvoice++;
					// $InvNo1 			= $row['InvNo'];
					// $InvDate1 			= $row['InvDate'];
					// $DueDate1 			= $row['DueDate'];
					// $Total1 			= ($row['Total']==NULL || $row['Total']=='') ? 0 : $row['Total'];
					// $PPN1 				= ($row['PPN']==NULL || $row['PPN']=='') ? 0 : $row['PPN'];
					// $FPNo1 				= $row['FPNo'];
					// $FPDate1 			= $row['FPDate'];
					// $PaymentAmount1 	= $dapat_PaymentAmount;
					// $TotalPayment1 		= ($row['TotalPayment']==NULL || $row['TotalPayment']=='') ? 0 : $row['TotalPayment'];
					// $PaymentAmount2 	= 0;
					// $IsChecked1 		= 0;

				}

			}
		}

		// print_rr($Array_InvPD);

		// $Array_InvPD[] = (object) array(
		// 	'NoUrutInvoice' => $NoUrutInvoice1,
		// 	'NoKO' => $NoKO,
		// 	'VendorNm' => $Nama,
		// 	'InvNo' => htmlspecialchars($InvNo1),
		// 	'InvDate' => CekNull_Untuk_Tanggal($InvDate1),
		// 	'DueDate' => CekNull_Untuk_Tanggal($DueDate1),
		// 	'Total' => $Total1,
		// 	'PPN' => $PPN1,
		// 	'FPNo' => htmlspecialchars($FPNo1),
		// 	'FPDate' => $FPDate1,
		// 	'TotalBayar' => $PaymentAmount1,
		// 	'TotalPayment' => $TotalPayment1,
		// 	'OriginalPayment' => $PaymentAmount2,
		// 	'IsChecked' => (int) $IsChecked1 ,
		// 	'JobNo' => $JobNo,
		// 	'NoPD' => $NoPD,
		// 	'Company' => $this->Company,

		// );

		$data['TotalInvoice'] = $TotalInvoice;
		$data['TotalCentang'] = $TotalBayar;
		$data['List_Invoice'] = $Array_InvPD;
		$data['tombol_dimatikan'] = (substr($Aksi,0,3)=='SEE') ? 'disabled' : '';
		$this->load->view('entry_view/PDPJ_View/EntryPDLap_VIEW/Invoice_AddPD_Update', $data);
	}

	function Lakukan_Update_PermintaanDana(){
		if (!$this->input->post()) {
			die;
		}

		if (substr($this->input->post('Aksi'),0,3)=='SEE') {
			die;
		}

		// print_rr($_POST);
		// die;

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');
		
		$JobNo 		= $this->input->post('JobNo');
		$Alokasi 	= $this->input->post('Alokasi');

		// == START PENGECEKAN NOMOR PD
		$NoPD 		= ($this->input->post('NoPD')=='' || $this->input->post('NoPD')==NULL ) ? NULL : $this->input->post('NoPD');
		// if ($this->input->post('Aksi')=='NEW') {
		// 	$NoPD = $this->Pembuatan_NoPD($this->input->post('TipePD'),$JobNo,$Alokasi);
		// }else{
		// 	$NoPD = $this->input->post('NoPD');
		// }
		// == END PENGECEKAN NOMOR PD

		// -- START FORM SEBELAH KIRI
		$NoRef 		= ($this->PD_Status=='Lapangan') ? $this->input->post('NoRef') : NULL;
		$TglPD 		= CekNull_Untuk_Tanggal($this->input->post('TglPD'));
		$Deskripsi 	= CekNull_Huruf($this->input->post('Deskripsi'));
		$PrdAwal 	= CekNull_Untuk_Tanggal($this->input->post('PrdAwal'));
		$PrdAkhir 	= CekNull_Untuk_Tanggal($this->input->post('PrdAkhir'));
		$Minggu 	= CekNull_Huruf($this->input->post('Minggu'));
		$Minggu  	= (int) $Minggu;
		$TipeForm 	= CekNull_Huruf($this->input->post('TipeForm'));
		$BuktiPendukung = $this->CekBuktiPendukung($this->input->post());
		$Keterangan = CekNull_Huruf($this->input->post('Keterangan'));

		$Jaminan 	= ($this->input->post('Jaminan')) ? 1 : 0;
		$PD_PPN 	= ($this->input->post('PD_PPN')) ? 1 : 0;
		// -- START FORM SEBELAH KIRI

		// == START Bayar Kepada 
		$NoKO 		= CekNull_Huruf($this->input->post('NoKO'));
		$NoTagihan 	= CekNull_Huruf($this->input->post('NoTagihan'));
		$NoTagihan 	= substr($NoTagihan,0,100);
		$Nama 		= CekNull_Huruf($this->input->post('Nama'));
		$Alamat 	= CekNull_Huruf($this->input->post('Alamat'));
		$Telepon 	= CekNull_Huruf($this->input->post('Telepon'));
		$NPWP 		= CekNull_Huruf($this->input->post('NPWP'));
		// == END Bayar Kepada

		// == START REKENING PENERIMA
		$NoRek 		= CekNull_Huruf($this->input->post('NoRek'));
		$AtasNama 	= CekNull_Huruf($this->input->post('AtasNama'));
		$Bank 		= CekNull_Huruf($this->input->post('Bank'));
		// == END REKENING PENERIMA

		$SubTotal 	= ($this->input->post('SubTotal')=='' || $this->input->post('SubTotal')==NULL) ? 0 : $this->input->post('SubTotal');
		$SubTotal 	= CekDanHapusKoma($SubTotal);
		$SubTotal 	= $SubTotal;

		$NoPJSebelumnya = CekNull_Huruf($this->input->post('NoPJSebelumnya'));

		$Saldo 	= ($this->input->post('Saldo')=='' || $this->input->post('Saldo')==NULL) ? 0 : $this->input->post('Saldo');
		$Saldo 	= CekDanHapusKoma($Saldo);
		$Saldo 	= (float) $Saldo;

		$TotalPermintaan 	= ($this->input->post('TotalPermintaan')=='' || $this->input->post('TotalPermintaan')==NULL) ? 0 : $this->input->post('TotalPermintaan');
		$TotalPermintaan 	= CekDanHapusKoma($TotalPermintaan);
		$TotalPermintaan 	= (float) $TotalPermintaan;


		$TotalCentang 	= ($this->input->post('TotalCentang')=='' || $this->input->post('TotalCentang')==NULL) ? 0 : $this->input->post('TotalCentang');
		$TotalCentang 	= CekDanHapusKoma($TotalCentang);
		$TotalCentang 	= (float) $TotalCentang;

		$TmpPrice = 0;
		// START PENGECEKAN KO
		if (substr($NoKO,0,2)=='KO') {

			if ($BuktiPendukung=='') {
				echo 'Belum pilih bukti pendukung';
				die;
			}

			if (!$this->input->post('checkbox_tercentang')) {
				echo 'belum pilih invoice';
				die;
			}


			// START VALIDASI INVOICE
			if (isset($_POST['checkbox_tercentang'])) {

				foreach($_POST['checkbox_tercentang'] as $key => $val){

					$InvNo = htmlspecialchars($_POST['InvNo'][$val]);
					$TotalBayar = ($_POST['TotalBayar'][$val]=='' || $_POST['TotalBayar'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['TotalBayar'][$val]);
					$TotalPayment = ($_POST['TotalPayment'][$val]=='' || $_POST['TotalPayment'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['TotalPayment'][$val]);

					$OriginalPayment = ($_POST['OriginalPayment'][$val]=='' || $_POST['OriginalPayment'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['OriginalPayment'][$val]);
					$Total = ($_POST['Total'][$val]=='' || $_POST['Total'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['Total'][$val]);

					$TmpPrice = $TotalBayar;
					$TotalPayment = $TotalPayment;
					$OriginalPayment = $OriginalPayment;
					$Total = $Total;

					if ($TmpPrice + ($TotalPayment - $OriginalPayment) > $Total ) {
						echo 'Total bayar invoice '.$InvNo.' melebihi total invoice.';
						die;
					}
				}
			}
			// END VALIDASI INVOICE

			$TotalKO = 0;
			$TotalPD = 0;
			$TotalTerbayar = 0;

			$Txt_query_PdHdr = "";
			if ($this->input->post('Aksi')=='NEW') {
				$Txt_query_PdHdr = "SELECT * FROM PdHdr WHERE NoKO='$NoKO' AND RejectBy IS NULL";
			}else{
				$Txt_query_PdHdr = "SELECT * FROM PdHdr WHERE NoPD!='$NoPD' AND NoKO='$NoKO' AND RejectBy IS NULL";
			}


			// == start nominal yang telah ada di PD
			$Query_PdHdr = $this->db->query($Txt_query_PdHdr);
			if ($Query_PdHdr->num_rows()>0) {
				foreach ($Query_PdHdr->result() as $row_PdHdr) {
					$TotalPD += ($row_PdHdr->TotalPD=='' || $row_PdHdr->TotalPD==NULL) ? 0 : $row_PdHdr->TotalPD;
				}
			}
			// == end nominal yang telah ada di PD


			// == start nominal yang telah di bayar
			$Query_BLE = $this->db->query("SELECT ISNULL(SUM(Amount),0) AS Amount FROM BLE WHERE NoKO='$NoKO' ");
			if ($Query_BLE->num_rows()>0) {
				$row_BLE = $Query_BLE->row_array();
				$TotalTerbayar += $row_BLE['Amount'];

			}
			// == end nominal yang telah di bayar

			$TotalPD += $SubTotal;
			
			// == start mendapatkan nominal Total Ko dari KoHdr
			$query_KoHdr = $this->db->query("SELECT * FROM KoHdr WHERE NoKO='$NoKO' ");
			if ($query_KoHdr->num_rows() > 0) {
				
				$row_KoHdr = $query_KoHdr->row_array();

				// -- Cek NoKO ini sudah di approved atau belum
				if ($row_KoHdr['ApprovedBy']=='' || $row_KoHdr['ApprovedBy']==NULL) {
					echo $NoKO.' belum di-approve';
					die;
				}

				$SubTotal_KoHdr = ($row_KoHdr['SubTotal']=='' || $row_KoHdr['SubTotal']==NULL) ? 0 : $row_KoHdr['SubTotal'];
				$DiscAmount = ($row_KoHdr['DiscAmount']=='' || $row_KoHdr['DiscAmount']==NULL) ? 0 : $row_KoHdr['DiscAmount'];
				$PPN = ($row_KoHdr['PPN']=='' || $row_KoHdr['PPN']==NULL) ? 0 : $row_KoHdr['PPN'];

				$TotalKO = ($SubTotal_KoHdr - $DiscAmount) + $PPN;
				$pesan = "";
				if ($TotalKO<$TotalPD) {
					$pesan = "Total Akumulasi PD melebihi nilai Kontrak/PO.\n";
					$pesan = $pesan." Nilai Kontrak/PO ".$NoKO." : ".number_format($TotalKO)."\n";
					$pesan = $pesan."Total Akumulasi PD termasuk current : ".number_format($TotalPD)."\n";
					$pesan = $pesan."Nilai Kontrak/PO yang sudah dibayar : ".number_format($TotalTerbayar)."\n";
					echo $pesan;
					die;
					
				}

				if ($TotalCentang<>$SubTotal) {
					echo 'Total Invoice <> Total Permintaan.';
					die;
				}


			}
			// == end mendapatkan nominal Total Ko dari KoHdr

		}
		// END PENGECEKAN KO

		if ($Bank=='' || $Bank==NULL ) {
			echo 'Bank rekening penerima belum di-pilih.';
			die;
		}


		$this->db->trans_begin();

		try {

			$TipePD = $this->input->post('TipePD');
			$NoPD = ($this->input->post('Aksi')=='NEW') ? $this->AssignNoPD($TipePD,$JobNo,$Alokasi) : $NoPD;

			if ($this->input->post('Aksi')=='NEW') {
				// == AKSI UNTUK TABLE PdHdr
				$dataInsert_To_PdHdr = array(
					'NoPD' 			=> $NoPD,
					'NoRef' 		=> $NoRef,
					'JobNo' 		=> $JobNo,
					'TglPD' 		=> $TglPD,
					'Deskripsi' 	=> $Deskripsi,
					'PrdAwal' 		=> $PrdAwal,
					'PrdAkhir' 		=> $PrdAkhir,
					'Minggu' 		=> $Minggu,
					'Alokasi' 		=> $Alokasi,
					'TipeForm' 		=> $TipeForm,
					'TotalPD' 		=> $SubTotal,
					'BuktiPendukung'=> $BuktiPendukung,
					'NoKO' 			=> $NoKO,
					'NoTagihan' 	=> $NoTagihan,
					'Nama' 			=> $Nama,
					'Alamat' 		=> $Alamat,
					'Telepon' 		=> $Telepon,
					'NPWP' 			=> $NPWP,
					'NoRek' 		=> $NoRek,
					'Bank' 			=> $Bank,
					'AtasNama' 		=> $AtasNama,
					'KSO' 			=> ($TipePD=='RKD') ? 1 : 0,
					'Keterangan' 	=> $Keterangan,
					'NoPJSebelumnya'=> $NoPJSebelumnya,
					'Jaminan' 		=> $Jaminan,
					'PD_PPN' 		=> $PD_PPN,
					'UserEntry' 	=> $UserEntry,
					'TimeEntry' 	=> $TimeEntry,
				);

				$this->db->insert('PdHdr',$dataInsert_To_PdHdr);

			}else{
				// == AKSI UNTUK TABLE PdHdr - PROSES PD UPDATE
				$setUpdate_PdHdr = array(
					'TglPD' 		=> $TglPD,
					'Deskripsi' 	=> $Deskripsi,
					'PrdAwal' 		=> $PrdAwal,
					'PrdAkhir' 		=> $PrdAkhir,
					'Minggu' 		=> $Minggu,
					'TotalPD' 		=> $SubTotal,
					'BuktiPendukung'=> $BuktiPendukung,
					'NoKO' 			=> $NoKO,
					'NoTagihan' 	=> $NoTagihan,
					'Nama' 			=> $Nama,
					'Alamat' 		=> $Alamat,
					'Telepon' 		=> $Telepon,
					'NPWP' 			=> $NPWP,
					'NoRek' 		=> $NoRek,
					'Bank' 			=> $Bank,
					'AtasNama' 		=> $AtasNama,
					'NoRef' 		=> $NoRef,
					'TipeForm' 		=> $TipeForm,
					'Keterangan' 	=> $Keterangan,
					'NoPJSebelumnya'=> $NoPJSebelumnya,
					'Jaminan' 		=> $Jaminan,
					'PD_PPN' 		=> $PD_PPN,
					'UserEntry' 	=> $UserEntry,
					'TimeEntry' 	=> $TimeEntry,
				);

				$whereUpdate_PdHdr = array('NoPD' => $NoPD);
				// echo 'sini';
				$this->db->set($setUpdate_PdHdr)->where($whereUpdate_PdHdr)->update('PdHdr');


				// == HAPUS TABLE PdDtl - PROSES PD UPDATE
				$this->db->query("DELETE PdDtl WHERE NoPD='$NoPD' ");

				// == HAPUS TABLE InvPD - PROSES PD UPDATE
				$this->db->query("DELETE InvPD WHERE NoPD='$NoPD' ");

			}

			// die;

			// == AKSI INSERT INTO KE TABLE PdDtl
			// $this->db->query("INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry)
			// 	SELECT '".$NoPD."',NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,'".$UserEntry."','".$TimeEntry."' FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' ");

			$NoPD_Cek = "";
			if ($this->input->post('Aksi')=='NEW') {

				$NoPD_Cek = " AND NoPD IS NULL ";
			}else{
				$NoPD_Cek = " AND NoPD='".$NoPD."' ";
			}

			$Cek_PdDtl_Temp_Keuangan = $this->db->query("SELECT * FROM PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' ".$NoPD_Cek." ");

			// print_rr($Cek_PdDtl_Temp_Keuangan->result());

			if ($Cek_PdDtl_Temp_Keuangan->num_rows()>0) {
				$Array_PdDtl_Buatan = array();
				$NoUrut_Buatan = 0;
				foreach ($Cek_PdDtl_Temp_Keuangan->result() as $myrow) {

					$NoUrut_Buatan++;

					$KdRAP = CekNull_Huruf($myrow->KdRAP);
					$Uraian = CekNull_Huruf($myrow->Uraian);
					$Vol = CekNull_Angka_Dan_Bikin_Float($myrow->Vol);
					$Uom = CekNull_Huruf($myrow->Uom);
					$HrgSatuan = CekNull_Angka_Dan_Bikin_Float($myrow->HrgSatuan);

					$Array_PdDtl_Buatan[] = array(

						'NoPD' => $NoPD,
						'NoUrut' => $NoUrut_Buatan,
						'KdRAP' => $KdRAP,
						'Uraian' => $Uraian,
						'Vol' => $Vol,
						'Uom' => $Uom,
						'HrgSatuan' => $HrgSatuan,
						'UserEntry' => $UserEntry,
						'TimeEntry' => $TimeEntry,
					);
				}

				$this->db->insert_batch('PdDtl',$Array_PdDtl_Buatan);
			}



			// HAPUS DATA DI PdDtl_Temp_Keuangan setelah insert ke PdDtl
			$this->db->query("DELETE PdDtl_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company' ".$NoPD_Cek."  ");

			// INSERT INVOICE YANG TELAH DI CENTANG TADI KE TABLE InvPD
			if (isset($_POST['checkbox_tercentang'])) {
				$dataInvoice_Insert = array();
				foreach($_POST['checkbox_tercentang'] as $key => $val){

					$InvNo = htmlspecialchars($_POST['InvNo'][$val]);
					$TotalBayar = ($_POST['TotalBayar'][$val]=='' || $_POST['TotalBayar'][$val]==NULL) ? 0 : CekDanHapusKoma($_POST['TotalBayar'][$val]);

					$TotalBayar = (float) $TotalBayar;

					$dataInvoice_Insert[] = array(
						'JobNo' => $JobNo,
						'NoKO' => $NoKO,
						'InvNo' => $InvNo,
						'NoPD' => $NoPD,
						'PaymentAmount' => $TotalBayar,
						'UserEntry' => $UserEntry,
						'TimeEntry' => $TimeEntry,
					);
				}
				$this->db->insert_batch('InvPD',$dataInvoice_Insert);

			}

			// $this->db->query("DELETE InvPD_Temp_Keuangan WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND Company='$this->Company'")
			$this->db->query("DELETE FROM Invoice_Temp_Keuangan WHERE NoKO='$NoKO' AND JobNo='$JobNo' AND Company='$this->Company' ".$NoPD_Cek." ");


			is_pesan('success','Sukses, data telah di-update');
			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();
		}

	}


}

/* End of file EntryPDLap_C.php */
/* Location: ./application/controllers/Entry/PDPJ/EntryPDLap_C.php */