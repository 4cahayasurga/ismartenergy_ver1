<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmPJLap extends CI_Controller {


	public function __construct()
	{
		parent::__construct();
		is_login();
	}

	public function view($JobNo='',$Alokasi='', $flag=''){

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Entry Pertanggungjawaban Dana (Lapangan)';
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
		$this->load->view('FrmPJLap_view/main_pagePJ', $data);
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

	function tablePDLapangan(){
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
				END) AS 'PjApprovedByAKText' FROM PdHdr
			

			WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND NoRef IS NOT NULL AND NoRef<>'' AND RejectBy IS NULL ORDER BY NoPD DESC ")->result();


		$data['mydata'] = $myquery;

		// print_rr($data);
		// exit;

		$this->load->view('FrmPJLap_view/tablePDLapangan',$data);

	}


	function ApprovePD(){
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


		// $query = $this->db->where('NoPD',$NoPD)->get('PdHdr');
		// $jumlah = $query->num_rows();
		// $row = $query->row();


		// if ($jumlah == 0) {
		// 	echo 'PD_kosong';
		// 	exit;
		// }else if ($row->RejectBy !='' || $row->RejectBy != NULL ) {
		// 	echo 'sudah_reject';
		// 	exit;
		// }else if ($row->NoPJ =='' || $row->NoPJ == NULL ) {
		// 	echo 'NoPJ_kosong';
		// 	exit;
		// }else if ($row->PjApprovedByKT !='' || $row->PjApprovedByKT != NULL ) {
		// 	echo 'sudah_approve_cost_control';
		// 	exit;
		// }

		// exit;

		



		// $UserApprove = '';
		// $TimeUserApprove = '';
		// if ($ApproveText == 'Approve') {
		// 	$UserApprove = $this->session->userdata('MIS_LOGGED_ID');
		// 	$TimeUserApprove = date('Y-m-d H:i:s');
		// 	$this->db
		// 	->set(array('PjApprovedByAK' => $UserApprove, 'PjTimeApprovedAK' => $TimeUserApprove))
		// 	->where('NoPD',$NoPD)
		// 	->update('PdHdr');
		// }else{

		// 	$this->db
		// 	->set(array('PjApprovedByAK' => NULL, 'PjTimeApprovedAK' => NULL))
		// 	->where('NoPD',$NoPD)
		// 	->update('PdHdr');
		// }



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

	function EntryPJ($NoPD='', $LedgerNo=''){

		if ($LedgerNo == '') {
			exit;
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Entry Pertanggungjawaban Dana';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';
		$data['NoPD'] = $NoPD;

		$MyQuery = $this->db->query("SELECT * FROM PdHdr WHERE LedgerNo='$LedgerNo' AND NoPD='$NoPD' ")->row();
		$data['dataPD'] = $MyQuery;


		$disabledText = '';
		$disabledButton = '';
		if ($MyQuery->NoPJ !='' || $MyQuery->NoPJ !=NULL) {

			if ($MyQuery->PjApprovedByAK !='' || $MyQuery->PjApprovedByAK !=NULL  ) {
				$disabledText = 'disabled="disabled';
				$disabledButton = 'disabled';
			}

			
		}

		$data['disabledText'] = $disabledText;
		$data['disabledButton'] = $disabledButton;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmPJLap_view/EntryPJForm', $data); 
		$this->load->view('templates/footer');

	}

	private function PindahData($NoPD='',$flag=''){

		$this->db->trans_begin();

		$CekPD = $this->db->query("SELECT * FROM PdDtl_cadangan WHERE NoPD='$NoPD'  ");

		if ($flag == 'HalamanMulai') {

			if ($CekPD->num_rows() > 0 ) {
				$hapus = $this->db->query("DELETE FROM PdDtl_cadangan WHERE NoPD='$NoPD'  ");

			}

			//cek table PdDtl Asli
			$CekPdDtl_Asli = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$NoPD'  ");
			if ($CekPdDtl_Asli->num_rows() > 0) {

				foreach($CekPdDtl_Asli->result() as $mydata){
					$array[] = array(
						'NoPD' => $mydata->NoPD,
						'NoUrut' => $mydata->NoUrut,
						'KdRAP' => $mydata->KdRAP,
						'Uraian' => $mydata->Uraian,
						'Vol' => ($mydata->Vol==NULL || $mydata->Vol=='') ? 0 : (float) $mydata->Vol,
						'Uom' => $mydata->Uom,
						'HrgSatuan' => ($mydata->HrgSatuan==NULL || $mydata->HrgSatuan=='') ? 0 : (float) $mydata->HrgSatuan,
						'UserEntry' => $mydata->UserEntry,
						'TimeEntry' => $mydata->TimeEntry,
						'NoPJ' => $mydata->NoPJ,
						'PjUraian' => $mydata->PjUraian,
						'PjVol' => ($mydata->PjVol==NULL || $mydata->PjVol=='') ? 0 : (float) $mydata->PjVol,
						'PjHrgSatuan' => ($mydata->PjHrgSatuan==NULL || $mydata->PjHrgSatuan=='') ? 0 : (float) $mydata->PjHrgSatuan,
						'PjUserEntry' => $mydata->PjUserEntry,
						'PjTimeEntry' => $mydata->PjTimeEntry,
						// 'Company' => $Company
					);
					// $this->db->insert('PdDtl_cadangan',$array);
				}
				$this->db->insert_batch('PdDtl_cadangan',$array);

			}
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return 'gagal';
		}
		else
		{
			$this->db->trans_commit();
			return 'berhasil';
		}
		
	}

	function TampilkanTableUraian(){
		if (!$this->input->post()) {
			exit;
		}

		$NoPD = $this->input->post('NoPD');
		$Alokasi = $this->db->query("SELECT Alokasi FROM PdHdr WHERE NoPD='$NoPD' ")->row();
		$Alokasi = $Alokasi->Alokasi;
		$JobNo = $this->input->post('JobNo');
		// $KdRAP = $this->input->post('KdRAP');
		$flag = $this->input->post('flag');

		// if ($this->PindahData($NoPD,$flag) == 'gagal') {
		// 	echo 'gagal';
		// 	exit;
		// }


		$data['dataUraian'] = $this->db->query("SELECT a.*, 
			(SELECT Uraian FROM RAP WHERE JobNo='$JobNo' AND KdRAP=a.KdRAP AND Alokasi='$Alokasi' ) AS 'UraianKdRAP' FROM PdDtl a WHERE a.NoPD='$NoPD' ")->result();

		$PanggilData = $this->db->query("SELECT TotalPD, Saldo, RemarkOverrideSaldo, OverrideSaldo FROM PdHdr WHERE  NoPD='$NoPD' ")->row();
		$data['TotalPD'] = $PanggilData->TotalPD;
		$data['Saldo'] = $PanggilData->Saldo;
		$data['RemarkOverrideSaldo'] = $PanggilData->RemarkOverrideSaldo;
		$data['OverrideSaldo'] = $PanggilData->OverrideSaldo;

		// print_rr($data);

		$this->load->view('FrmPJLap_view/table_uraian',$data);	
	}

	function FormUraian(){
		if (!$this->input->post()) {
			echo 'gagal';
			exit;
		}

		print_rr($_POST);

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
			// echo '';
		}else{
			// echo '';
			// exit;
		}

		// print_rr($_POST);
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


		// UPDATE PdHdr SET NoPJ=@P1,TglPJ=@P2,TotalPJ=@P3,BuktiPendukung=@P4,Saldo=@P5,PjUserEntry=@P6,PjTimeEntry=@P7 WHERE NoPD=@P8

		$PotongPD = substr($NoPD,2);
		$NoPJ = "PJ".$PotongPD;
		// echo $NoPJ;

		$set = array(
			'NoPJ' => $NoPJ,
			'TglPJ' => $TglPJ,
			'TotalPJ' => ($TotalPJ==NULL||$TotalPJ=='') ? 0 : (float) $TotalPJ,
			'BuktiPendukung' => $BuktiPendukung,
			'Saldo' => ($Saldo==NULL||$Saldo=='') ? 0 : (float) $Saldo,
			'RemarkOverrideSaldo' => $RemarkOverrideSaldo,
			'PjUserEntry' => $PjUserEntry,
			'PjTimeEntry' => $PjTimeEntry,
			'Keterangan' => $Keterangan,
			
		);

		$where = array(
			'NoPD' => $NoPD
		);

		// print_rr($_POST);
		// echo "<hr>";
		// print_rr($set);
		// echo "<hr>";
		// print_rr($where);
		// exit;

		

		

		$this->db->trans_start();

		$this->db->set($set)->where($where)->update('PdHdr');
		$this->db->query("UPDATE PdDtl SET NoPJ='$NoPJ', PjUserEntry='$PjUserEntry', PjTimeEntry='$PjTimeEntry' WHERE NoPD='$NoPD'  ");
		// $this->db->where($where)->delete('PdDtl');

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE ) {
			is_pesan('danger','gagal di update');
			echo 'gagal';
		}else{
			is_pesan('success','berhasil di update');
			echo 'berhasil';

		}

		// $update_PdHdr = 
		// if ($this->db->affected_rows() > 0 ) {
		// 	$this->db->where($where)->delete('PdDtl_cadangan');
		// }
		// echo $this->db->affected_rows();

		// print_rr($set);
		// exit;
		// print_rr($_POST);



		// $JobNo = $this->input->pos

		// print_rr($_POST);
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
			'PjVol' => ($PjVol==NULL||$PjVol=='') ? 0 : (float) $PjVol,
			'PjHrgSatuan' => ($PjHrgSatuan==NULL||$PjHrgSatuan=='') ? 0 : (float) $PjHrgSatuan,
			'PjUserEntry' => $UserEntry,
			'PjTimeEntry' => $TimeEntry
		);

		$where = array(
			'NoUrut' => $NoUrut,
			'NoPD' => $NoPD,
		);

		// print_rr($_POST);
		// exit;

		// $this->db->set($set)->where($where)->update('PdDtl_cadangan');
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


	// -- CREATE DATE : 12 DESEMBER 2023
	function CekPD($JobNo='',$Alokasi='',$NoPD=''){
		if ($JobNo=='' || $Alokasi=='') {
			redirect('FrmPJLap');
			die;
		}

		$query_BLE = $this->db->query("SELECT *, (SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE  FROM PdHdr WHERE NoPD='$NoPD' ");

		if ($query_BLE->num_rows() < 1) {
			is_pesan('danger',$NoPD.' tidak ditemukan');
			redirect('FrmPJLap/view/'.$JobNo.'/'.$Alokasi);
		}else{
			$row = $query_BLE->row();
			if ($row->RejectBy!='' || $row->RejectBy!=NULL) {
				is_pesan('danger','sudah di-reject oleh '.$row->RejectBy);
				redirect('FrmPJLap/view/'.$JobNo.'/'.$Alokasi);
			}

			if ($row->CountBLE==0) {
				is_pesan('danger','Belum ada pembayaran untuk No '.$NoPD);
				redirect('FrmPJLap/view/'.$JobNo.'/'.$Alokasi);
			}

			$TxtSesiEntryPJ = "";
			if ($row->PjApprovedByAK=='' || $row->PjApprovedByAK==NULL) {
				$TxtSesiEntryPJ = "UPD|".$JobNo."|".$NoPD."|".$Alokasi."|FrmPJLap";
			}else{
				$TxtSesiEntryPJ = "SEE|".$JobNo."|".$NoPD."|".$Alokasi."|FrmPJLap";
			}

			// echo $TxtSesiEntryPJ;
			// die;

			$this->session->set_userdata('SesiEntryPJ',$TxtSesiEntryPJ);
			redirect('Entry/PDPJ/EntryPJLap');
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
			}

			if ($row['NoPJ']=='' || $row['NoPJ']==NULL) {
				echo $NoPD.' belum entry uraian PJ.';
			}

			if ($row['PjApprovedByKT']!='' || $row['PjApprovedByKT']!=NULL) {
				echo $NoPD.' sudah di-approved oleh Cost Control. ';
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




}