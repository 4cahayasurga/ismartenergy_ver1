<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmPJKeu extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function view($JobNo='',$Alokasi='', $flag=''){

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Entry Pertanggungjawaban Dana (Keuangan) ';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = '';
		$JobNm = '';
		if ($JobNo !='') {
			$data['JobNm'] = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
		}
		$data['Alokasi'] = $Alokasi;
		$data['flag'] = $flag;

		$data['ListJob'] = $this->dapat_list_job();
		$data['ListAlokasi'] = list_job_alokasi();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmPJKeu_view/main_pagePJ_Keu', $data);
		$this->load->view('templates/footer');
	}

	function dapat_list_job($pengurutan='')
	{

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
			on bx.JobNo = ax.JobNo WHERE bx.Company='$Company' group by ax.JobNo, bx.JobNm ".$var_pengurutan."

			");

		return $query->result();
	}

	function getJobNo(){
		$Company = $this->config->item('Company');
		$query = $this->db->query("SELECT JobNo, JobNm FROM Job WHERE Company='$Company' ")->result();
		return $query;
	}

	function pembuatan_koma_implode_JobNo(){
		$comma_separated = "";
		$AksesJob = AksesJob_HanyaJobNumber();
		$arrayJob = array();

		foreach($AksesJob as $JobAkses){
			$arrayJob[] = $JobAkses->JobNo;
		}

		$comma_separated = implode("','", $arrayJob);
		$comma_separated = "'".$comma_separated."'";

		return $comma_separated;
	}

	function tablePDKeuangan(){
		if (!$this->input->post('JobNo')) {
			echo 'error';
			exit;
		}


		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		$myquery = $this->db->query("
			SELECT *, 
			(CASE 
			WHEN PjApprovedByAK IS NULL THEN 'kosong'
			ELSE 'ada'
			END) AS 'PjApprovedByAKText'
			FROM PdHdr 
			WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND (NoRef IS NULL OR NoRef='') AND RejectBy IS NULL ORDER BY NoPD DESC ")->result();
		
		// $myquery = $this->db->query("
		// 	SELECT *, 
		// 	(CASE 
		// 	WHEN PjApprovedByAK IS NULL THEN 'kosong'
		// 	ELSE 'ada'
		// 	END) AS 'PjApprovedByAKText' FROM PdHdr


		// 	WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND NoRef IS NOT NULL AND NoRef<>'' AND RejectBy IS NULL ORDER BY NoPD DESC ")->result();

		// SELECT * FROM PdHdr WHERE JobNo=@P1 AND Alokasi=@P2 AND (NoRef IS NULL OR NoRef='') AND RejectBy IS NULL


		$data['mydata'] = $myquery;

		// print_rr($data);
		// exit;

		$this->load->view('FrmPJKeu_view/tablePDKeuangan',$data);

	}

	function CekSelectPD(){
		if (!$this->input->post()) {
			exit;
		}

		$NoPD = $this->input->post('NoPD');
		$MyQuery = $this->db->query("
			SELECT *, 
			(SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE
			FROM PdHdr WHERE NoPD='$NoPD' 
			");
		$jumlah = $MyQuery->num_rows();
		$row = $MyQuery->row();

		if ($jumlah == 0) {
			echo 'data_kosong';
			exit;
		}

		if ($row->RejectBy <>'' || $row->RejectBy <> NULL ) {
			echo 'sudah_reject';
			exit;
		}

		if ($row->CountBLE == 0) {
			echo 'belum_bayar_kasir';
			exit;
		}

	}

	function EntryPJKeu($NoPD='', $LedgerNo=''){

		if ($LedgerNo == '') {
			exit;
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Entry Pertanggungjawaban Dana (Keuangan)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';
		$data['NoPD'] = $NoPD;

		$MyQuery = $this->db->query("SELECT * FROM PdHdr WHERE LedgerNo='$LedgerNo' AND NoPD='$NoPD' ")->row();
		$data['dataPD'] = $MyQuery;


		$disabledText = '';
		$disabledButton = '';
		if ($MyQuery->NoPJ !='' || $MyQuery->NoPJ !=NULL) {
			$disabledText = 'disabled="disabled';
			$disabledButton = 'disabled';
		}

		$data['disabledText'] = $disabledText;
		$data['disabledButton'] = $disabledButton;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmPJKeu_view/EntryPJFormKeu', $data);
		$this->load->view('templates/footer');

	}

	function TampilkanTableUraian(){
		if (!$this->input->post()) {
			exit;
		}

		$NoPD = $this->input->post('NoPD');
		$Alokasi = $this->db->query("SELECT Alokasi FROM PdHdr WHERE NoPD='$NoPD' ")->row();
		$Alokasi = $Alokasi->Alokasi;
		$JobNo = $this->input->post('JobNo');

		$flag = $this->input->post('flag');


		$data['dataUraian'] = $this->db->query("SELECT a.*, 
			(SELECT Uraian FROM RAP WHERE JobNo='$JobNo' AND KdRAP=a.KdRAP AND Alokasi='$Alokasi' ) AS 'UraianKdRAP' FROM PdDtl a WHERE a.NoPD='$NoPD' ORDER BY NoUrut DESC ")->result();

		$PanggilData = $this->db->query("SELECT TotalPD, Saldo, RemarkOverrideSaldo, OverrideSaldo FROM PdHdr WHERE  NoPD='$NoPD' ")->row();
		$data['TotalPD'] = $PanggilData->TotalPD;
		$data['Saldo'] = $PanggilData->Saldo;
		$data['RemarkOverrideSaldo'] = $PanggilData->RemarkOverrideSaldo;
		$data['OverrideSaldo'] = $PanggilData->OverrideSaldo;

		// print_rr($data);

		$this->load->view('FrmPJKeu_view/table_uraianKeu',$data);	
	}

	function Update_UraianPJ(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$NoUrut = $this->input->post('NoUrut');
		$NoPD = $this->input->post('NoPD');

		$PjUraian = $this->input->post('PjUraian');
		$PjVol = $this->input->post('PjVol');
		$PjHrgSatuan = $this->input->post('PjHrgSatuan');

		$PjVol = CekDanHapusKoma($PjVol);
		$PjHrgSatuan = CekDanHapusKoma($PjHrgSatuan);
		
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$this->db->trans_begin();

		$set = array(
			'PjUraian' => $PjUraian,
			'PjVol' => ($PjVol==NULL || $PjVol=='') ? 0 : (float) $PjVol,
			'PjHrgSatuan' => ($PjHrgSatuan==NULL || $PjHrgSatuan=='') ? 0 : (float) $PjHrgSatuan,
			'PjUserEntry' => $UserEntry,
			'PjTimeEntry' => $TimeEntry
		);

		$where = array(
			'NoUrut' => $NoUrut,
			'NoPD' => $NoPD,
		);

		$this->db->set($set)->where($where)->update('PdDtl');

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			echo 'gagal';
		}
		else
		{
			$this->db->trans_commit();
			echo 'berhasil';
		}


	}

	function UraianSamaDenganPD(){
		if (!$this->input->post()) {
			echo 'gagal';
			exit;
		}

		$NoPD = $this->input->post('NoPD');

		$PjUserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$PjTimeEntry = date('Y-m-d H:i:s');

		$query = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$NoPD'  ");

		if ($query->num_rows() > 0 ) {
			foreach ($query->result() as $key) {
				$Vol = ($key->Vol==NULL || $key->Vol=='') ? 0 : (float) $key->Vol;
				$Uraian = $key->Uraian;
				$HrgSatuan = ($key->HrgSatuan==NULL || $key->HrgSatuan=='') ? 0 : (float) $key->HrgSatuan;
				$NoUrut = $key->NoUrut;

				$this->db->query("UPDATE PdDtl SET PjUraian='$Uraian', PjVol='$Vol', PjHrgSatuan='$HrgSatuan', PjUserEntry='$PjUserEntry', PjTimeEntry='$PjTimeEntry'  WHERE NoPD='$NoPD' AND NoUrut='$NoUrut' ");
			}
			echo '';
		}else{
			echo '';
		}

	}

	function SimpanPJ(){
		if (!$this->input->post()) {
			exit;
		}

		$NoPD = $this->input->post('NoPD');
		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');
		$TipeForm = $this->input->post('TipeForm');
		$Keterangan = $this->input->post('Keterangan');
		$TglPD = $this->input->post('TglPD');
		$TglPJ = $this->input->post('TglPJ');
		$BuktiPendukung = ($this->input->post('BuktiPendukung')) ?  MembuatMenjadiKoma($this->input->post('BuktiPendukung')) : '';


		$TotalPJ = $this->input->post('TotalPJ');

		$PjUserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$PjTimeEntry = date('Y-m-d H:i:s');
		$Saldo = $this->input->post('Saldo');
		$RemarkOverrideSaldo = $this->input->post('RemarkOverrideSaldo');

		$PotongPD = substr($NoPD,2);
		$NoPJ = "PJ".$PotongPD;

		$set = array(
			'NoPJ' => $NoPJ,
			'TglPJ' => $TglPJ,
			'TotalPJ' => ($TotalPJ==NULL || $TotalPJ=='') ? 0 : (float) $TotalPJ,
			'BuktiPendukung' => $BuktiPendukung,
			'Saldo' => ($Saldo==NULL || $Saldo=='') ? 0 : (float) $Saldo,
			'RemarkOverrideSaldo' => $RemarkOverrideSaldo,
			'PjUserEntry' => $PjUserEntry,
			'PjTimeEntry' => $PjTimeEntry,
			'Keterangan' => $Keterangan,
			
		);

		$where = array(
			'NoPD' => $NoPD
		);	

		$this->db->trans_start();

		$this->db->set($set)->where($where)->update('PdHdr');
		$this->db->query("UPDATE PdDtl SET NoPJ='$NoPJ', PjUserEntry='$PjUserEntry', PjTimeEntry='$PjTimeEntry' WHERE NoPD='$NoPD'  ");


		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE ) {
			is_pesan('error','gagal di update');
			echo 'gagal';
		}else{
			is_pesan('success','berhasil di update');
			echo 'berhasil';

		}

	}

	function Aksi_TambahUraianPJ(){
		if (!$this->input->post()) {
			exit;
		}

		$NoPD = $this->input->post('NoPD');
		$KdRAP= $this->input->post('KdRAP_Uraian');
		$Uraian= $this->input->post('Uraian_Tambah');
		$Vol = $this->input->post('Vol_Uraian');
		$Uom = $this->input->post('Uom_Uraian');
		$HrgSatuan = $this->input->post('HrgSatuan_Uraian');
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$NoUrut=0;

		$cekPdDtl = $this->db->query("SELECT TOP 1 NoUrut FROM PdDtl WHERE NoPD='$NoPD' ORDER BY NoUrut DESC ");
		if ($cekPdDtl->num_rows() > 0 ) {
			$row = $cekPdDtl->row();
			$NoUrut = $row->NoUrut + 1;
		}else{
			$NoUrut = 1;
		}

		
		$Vol = CekDanHapusKoma($Vol);
		$HrgSatuan = CekDanHapusKoma($HrgSatuan);

		$data = array(
			'NoPD' => $NoPD,
			'NoUrut' => $NoUrut,
			'KdRAP' => $KdRAP,
			'Uraian' => $Uraian,
			'Vol' => ($Vol==NULL || $Vol=='') ? 0 : (float) $Vol,
			'Uom' => $Uom,
			'HrgSatuan' => ($HrgSatuan==NULL || $HrgSatuan=='') ? 0 : (float) $HrgSatuan,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry
		);

		$this->db->trans_start();

		$this->db->insert('PdDtl',$data);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE ) {
			echo 'gagal';
		}else{
			echo 'berhasil';
		}
	}

	function ApprovePD()
	{

		if (!$this->input->post()) {
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo');
		$NoPD = $this->input->post('NoPD');
		$ApproveTxt = $this->input->post('ApproveTxt');

		$query = $this->db->query("SELECT * FROM PdHdr WHERE LedgerNo='$LedgerNo' AND NoPD='$NoPD' ");
		if ($query->num_rows() > 0 ) {
			
			$row = $query->row();

			if ($row->RejectBy !='' || $row->RejectBy != NULL ) {
				echo 'sudah_reject';
				exit;
			}else if ($row->NoPJ =='' || $row->NoPJ == NULL ) {
				echo 'NoPJ_kosong';
				exit;
			}else if ($row->PjApprovedByKT !='' || $row->PjApprovedByKT != NULL ) {
				echo 'sudah_approve_cost_control';
				exit;
			}else{

				$this->db->trans_begin();

				$UserApprove = $this->session->userdata('MIS_LOGGED_NAME');
				$TimeUserApprove = date('Y-m-d H:i:s');

				if($ApproveTxt == 'Approve'){
					$this->db->query("UPDATE PdHdr SET PjApprovedByAK='$UserApprove', PjTimeApprovedAK='$TimeUserApprove' WHERE LedgerNo='$LedgerNo' AND NoPD='$NoPD'  ");
				}else{
					$this->db->query("UPDATE PdHdr SET PjApprovedByAK=NULL, PjTimeApprovedAK=NULL WHERE LedgerNo='$LedgerNo' AND NoPD='$NoPD'  ");
				}

				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					echo 'gagal';

				}
				else
				{
					$this->db->trans_commit();
					echo 'berhasil';
				}
			}



		}else{
			echo 'PD_kosong';
		}



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
	

}