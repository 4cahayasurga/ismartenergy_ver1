<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting(E_ALL);
class PJLap_C extends CI_Controller {

	var $Company;
	var $PJ_Type;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->PJ_Type = 'Lapangan';
		
	}

	public function index($JobNo='',$Alokasi='')
	{
		$data['title'] = 'Pertanggungjawaban Dana (Lapangan)';
		$data['judul'] = 'Pertanggungjawaban Dana (Lapangan)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';
		$data['atur_kiri'] = '1';
		$data['ListJob'] = ListJob();
		$data['ListAlokasi'] = ListAlokasi(); 

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/PJLap_View/halaman_utama', $data);
		$this->load->view('templates/footer');
	}

	function tablePJLapangan(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$data['ListPJ'] = $this->db->query("SELECT *, (SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) 'CountBLE' FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND NoRef IS NOT NULL AND NoRef<>'' AND RejectBy IS NULL ORDER BY NoPD DESC")->result();
		// print_rr($data);
		// exit;
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['PJ_Type_Untuk_PRINT_PD'] = ($this->PJ_Type=='Lapangan') ? '' : 'KEU';


		$this->load->view('entry_view/PDPJ_View/PJLap_View/tablePJLapangan', $data);
	}

	function AksiApprove($NoPD='',$JobNo=''	,$Alokasi=''){
		if ($NoPD=='' || $JobNo=='' || $Alokasi=='') {
			is_pesan('error','terjadi kesalahan');
			redirect('Entry/PDPJ/PJLap_C','refresh');
			die;
		}

		$query = $this->db->query("SELECT * FROM PdHdr WHERE NoPD='$NoPD' ");
		$jumlah = $query->num_rows();
		$data = $query->row_array();

		if ($jumlah ==0) :
			is_pesan('warning',$NoPD.' tidak ditemukan');
			redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		endif;

		if ($data['RejectBy']!=''||$data['RejectBy']!=NULL) :
			is_pesan('warning',$NoPD.' sudah di-reject oleh '.$data['RejectBy']);
			redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		endif;

		if ($data['NoPJ']==''||$data['NoPJ']==NULL) :
			is_pesan('warning',$NoPD.' belum entry uraian PJ.');
			redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		endif;

		if ($data['PjApprovedByKT']!=''||$data['PjApprovedByKT']!=NULL) :
			is_pesan('warning',$data['NoPJ'].' sudah di-Approved oleh Cost Control.');
			redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		endif;

		// $PjApprovedByAK = ($data['PjApprovedByAK']=='' || $data['PjApprovedByAK']==NULL) ? 'APPROVE' : 'UNAPPROVE';

		$PjApprovedByAK = ($data['PjApprovedByAK']=='' || $data['PjApprovedByAK']==NULL) ? $this->session->userdata('MIS_LOGGED_NAME') : NULL;
		$PjTimeApprovedAK = ($data['PjApprovedByAK']=='' || $data['PjApprovedByAK']==NULL) ? date('Y-m-d H:i:s') : NULL;

		// echo $PjApprovedByAK.' - '.$PjTimeApprovedAK;
		// die;
		$this->db->query("UPDATE PdHdr SET PjApprovedByAK='$PjApprovedByAK', PjTimeApprovedAK='$PjTimeApprovedAK' WHERE NoPD='$NoPD' ");
		if ($this->db->affected_rows()>0) {
			is_pesan('success','Update Berhasil');
			redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		}else{
			is_pesan('error','Update Gagal, Silahkan Ulangi Proses');
			redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		}

	}

	function FrmUpdatePJLap($NoPD='',$JobNo='',$Alokasi='')
	{
		if ($NoPD=='' || $JobNo=='' || $Alokasi=='') {
			is_pesan('error','terjadi kesalahan');
			redirect('Entry/PDPJ/PJLap_C','refresh');
			die;
		}

		$Query = $this->db->query("SELECT *, (SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE FROM PdHdr WHERE NoPD='$NoPD'");
		$jumlah = $Query->num_rows();
		$dataCek = $Query->row_array();

		if ($jumlah ==0) :
			is_pesan('warning',$NoPD.' tidak ditemukan');
			redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		endif;

		if ($dataCek['RejectBy']!=''||$dataCek['RejectBy']!=NULL) :
			is_pesan('warning',$NoPD.' sudah di-reject oleh '.$dataCek['RejectBy']);
			redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		endif;

		if ($dataCek['CountBLE']==0) :
			is_pesan('warning','Belum ada pembayaran untuk No '.$NoPD);
			redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		endif;

		$data['title'] = 'Entry Pertanggungjawaban Dana (Lapangan)';
		$data['judul'] = 'Entry Pertanggungjawaban Dana (Lapangan)';
		$data['atur_kiri'] = '1';

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['NoPD'] = $NoPD;

		$QueryPD = $this->db->query("SELECT *, (SELECT ISNULL(JobNm,NULL) FROM Job WHERE JobNo=PdHdr.JobNo) AS 'JobNm', (SELECT ISNULL(Keterangan,NULL) FROM Alokasi WHERE Alokasi=PdHdr.Alokasi) AS 'KeteranganAlokasi', (SELECT ISNULL(Keterangan,NULL) FROM TipeForm WHERE TipeForm=PdHdr.TipeForm AND Alokasi=PdHdr.Alokasi) AS 'KeteranganTipeForm' FROM PdHdr WHERE NoPD='$NoPD'")->row_array();


		$data['dataPD'] = $QueryPD;

		$data['btnTambahUraian'] = ($Alokasi=='C') ? 'disabled' : 'disabled'; 

		$cekApprove = $this->db->query("SELECT NoPD FROM PdHdr WHERE NoPD='$NoPD' AND PjApprovedByAK IS NOT NULL   ")->num_rows();
		$data['cekApprove'] = ($cekApprove==1) ? 'style="display:none;"' : '';
		$data['matikan_input'] = ($cekApprove==1) ? 'disabled' : '';
		// echo $cekApprove;
		// exit;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/PJLap_View/FrmUpdatePJLap', $data);
		$this->load->view('templates/footer');
	}

	function PindahData_PdDtl_To_PdDtl_temp(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);

		$this->db->trans_begin();

		$cek = $this->db->query("SELECT * FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ")->num_rows();
		if ($cek==0) {

			// $this->db->query("
			// 	INSERT INTO PdDtl_sementara (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Company)
			// 	SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,'".$this->Company."' FROM PdDtl WHERE NoPD='".$NoPD."'
			// 	");

			$this->db->query("INSERT INTO PdDtl_sementara (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Company, NoPJ, PjUraian, PjVol, PjHrgSatuan, PjUserEntry, PjTimeEntry)
				SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,'$this->Company', NoPJ, PjUraian, PjVol, PjHrgSatuan,PjUserEntry, PjTimeEntry FROM PdDtl WHERE NoPD='$NoPD'
				");

		}
		

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
		}
		
	}

	function tableEntryUraian(){
		if (!$this->input->post()) {
			die;
		}


		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);

		$queryPdDtl_sementara = $this->db->query("SELECT *, (SELECT CONCAT_WS(' - ',KdRAP,Uraian) FROM RAP WHERE JobNo='$JobNo' AND KdRAP=PdDtl_sementara.KdRAP) AS 'KdRAP_Lengkap' FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ")->result();

		$ArrayPdDtl = array();

		foreach ($queryPdDtl_sementara as $row) :
			$ArrayPdDtl[] = array(
				'NoPD' => showKodeAman($row->NoPD),
				'NoUrut' => $row->NoUrut,
				'KdRAP' => $row->KdRAP,
				'Uraian' => $row->Uraian,
				'Vol' => floatval($row->Vol),
				'Uom' => $row->Uom,
				'HrgSatuan' =>floatval($row->HrgSatuan),
				'PjUraian' => $row->PjUraian,
				'PjVol' => floatval($row->PjVol),
				'PjHrgSatuan' =>floatval($row->PjHrgSatuan),
				'KdRAP_Lengkap' => showKodeAman($row->KdRAP_Lengkap),
				'NoPJ' => showKodeAman($row->NoPJ),
			);
		endforeach;

		

		$get_TotalPD = $this->db->query("SELECT coalesce(sum(Vol * HrgSatuan),0) AS 'sumTotalPD' FROM PdDtl WHERE NoPD='$NoPD' ")->row_array();
		$get_TotalPJ = $this->db->query("SELECT coalesce(sum(PjVol * PjHrgSatuan),0) AS 'sumTotalPJ' FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ")->row_array();

		$sumTotalPD = ($get_TotalPD['sumTotalPD'] == 0) ? floatval(0) : floatval($get_TotalPD['sumTotalPD']);
		$sumTotalPJ = ($get_TotalPJ['sumTotalPJ'] == 0) ? floatval(0) : floatval($get_TotalPJ['sumTotalPJ']);
		$getSaldo = floatval($sumTotalPD - $sumTotalPJ);

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['NoPD'] = $NoPD;
		$data['NoKO'] = $NoKO;
		$data['ListPdDtl'] = $ArrayPdDtl;


		$data['sumTotalPD'] = $sumTotalPD;
		$data['sumTotalPJ'] = $sumTotalPJ;
		$data['Saldo'] = $getSaldo;


		// print_rr($data);
		// exit;

		$this->load->view('entry_view/PDPJ_View/PJLap_View/tableUraianPJLap', $data);

		// print_rr($data);
	}

	function btnUpdateUraian(){
		if (!$this->input->post()) {
			die;
		}

		// exit;
		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);

		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		// $queryPdDtl = $this->db->query("SELECT * FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ")->result();

		$this->db->trans_begin();
		// $this->db->query("DELETE FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ");
		// $this->db->query("
		// 	INSERT INTO PdDtl_sementara (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Company, NoPJ, PjUraian, PjVol, PjHrgSatuan, PjUserEntry, PjTimeEntry)
		// 	SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,'$this->Company', NoPJ, PjUraian, PjVol, PjHrgSatuan, '$UserEntry', '$TimeEntry' FROM PdDtl WHERE NoPD='$NoPD'
		// 	");
		$this->db->query("UPDATE t 
			SET t.PjUraian = (SELECT TOP 1 t2.Uraian FROM dbo.PdDtl_sementara t2 WHERE t2.NoPD=t.NoPD AND t2.NoUrut = t.NoUrut AND t2.Company=t.Company),
			t.PjVol = (SELECT TOP 1 t2.Vol FROM dbo.PdDtl_sementara t2 WHERE t2.NoPD=t.NoPD AND t2.NoUrut = t.NoUrut AND t2.Company=t.Company),
			t.PjHrgSatuan = (SELECT TOP 1 t2.HrgSatuan FROM dbo.PdDtl_sementara t2 WHERE t2.NoPD=t.NoPD AND t2.NoUrut = t.NoUrut AND t2.Company=t.Company)
			FROM PdDtl_sementara t
			WHERE t.NoPD = '$NoPD' AND t.Company='$this->Company'");
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
		}else{
			$this->db->trans_commit();
		}
		die;
		


		// $ArrayPdDtl = array();
		// foreach ($queryPdDtl as $row) :

		// 	$NoUrut = (int) $row->NoUrut;
		// 	$ArrayPdDtl[] = array(
		// 		'Uraian' => showKodeAman($row->Uraian),
		// 		'Vol' => floatval($row->Vol),
		// 		'HrgSatuan' =>floatval($row->HrgSatuan),
		// 		'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
		// 		'TimeEntry' => date('Y-m-d H:i:s'),
		// 	);

		// endforeach;

	}

	function Aksi_UpdateUraian(){
		if (!$this->input->post()) {
			die;
		}

		$NoUrut = $this->input->post('NoUrut',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);

		$PjUraian = $this->input->post('PjUraian',TRUE);
		$PjVol = $this->input->post('PjVol',TRUE);
		$PjHrgSatuan = $this->input->post('PjHrgSatuan',TRUE);

		$PjVol = CekDanHapusKoma($PjVol);
		$PjHrgSatuan = CekDanHapusKoma($PjHrgSatuan);

		$where=array(
			'NoUrut' => $NoUrut,
			'NoPD' => $NoPD,
			'Company' => $this->Company,
		);

		$set = array(
			'PjUraian' => $PjUraian,
			'PjVol' => $PjVol,
			'PjHrgSatuan' => $PjHrgSatuan,
		);


		$this->db->trans_begin();
		
		$this->db->set($set)->where($where)->update('PdDtl_sementara');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
		}else{
			$this->db->trans_commit();
			echo 'berhasil';
		}
		die;
	}

	function Aksi_TambahUraian(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD = $this->input->post('NoPD',TRUE);

		$KdRAP = $this->input->post('KdRAP_Tambah',TRUE);
		$PjUraian = $this->input->post('Uraian_Tambah',TRUE);
		$PjVol = $this->input->post('Vol_Tambah',TRUE);
		$Uom = $this->input->post('Uom_Tambah',TRUE);
		$Uom = strtoupper($Uom);
		$PjHrgSatuan = $this->input->post('HrgSatuan_Tambah',TRUE);
		
		$PjVol = CekDanHapusKoma($PjVol);
		$PjHrgSatuan = CekDanHapusKoma($PjHrgSatuan);

		$query = $this->db->query("SELECT * FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ")->num_rows();
		if ($query==0) {
			$NoUrut = (int) 1;
		}else{
			$NoUrut = (int) $query + 1;
		}

		// print_rr($_POST);
		// echo $NoUrut;
		// exit;


		$data = array(
			'NoPD' => $NoPD,
			'NoUrut' => $NoUrut,
			'KdRAP' => $KdRAP,
			'PjUraian' => $PjUraian,
			'PjVol' => $PjVol,
			'Uom' => $Uom,
			'PjHrgSatuan' => $PjHrgSatuan,
			'PjUserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
			'PjTimeEntry' => date('Y-m-d H:i:s'),
			'Company' => $this->Company,

		);

		// print_rr($data);
		// exit;

		$this->db->trans_begin();
		
		$this->db->insert('PdDtl_sementara',$data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
		}else{
			$this->db->trans_commit();
			echo 'berhasil';
		}
		die;
	}

	function SimpanPJ_Aksi(){
		if (!$this->input->post()) {
			echo 'error';
		}

		$BuktiPendukung = ($this->input->post('BuktiPendukung')) ?  MembuatMenjadiKoma($this->input->post('BuktiPendukung')) : '';
		$Keterangan = $this->input->post('Keterangan');
		$TglPJ = $this->input->post('TglPJ');

		$NoPD = $this->input->post('NoPD');
		$JobNo = $this->input->post('JobNo');
		$Alokasi = $this->input->post('Alokasi');

		$sumTotalPJ = ($this->input->post('sumTotalPJ')) ? $this->input->post('sumTotalPJ') : 0;
		$sumTotalPJ = CekDanHapusKoma($sumTotalPJ);

		$Saldo = ($this->input->post('Saldo')) ? $this->input->post('Saldo') : 0;
		$Saldo = CekDanHapusKoma($Saldo);

		$NoPJ = NULL;
		$NoPJ = "PJ".substr($NoPD,2);

		if ($NoPJ=='' || $NoPJ==NULL) {
			echo 'PJkosong';
			exit;
		}

		$SET = array(
			'NoPJ' => $NoPJ,
			'TglPJ' => $TglPJ,
			'TotalPJ' => $sumTotalPJ,
			'BuktiPendukung' => $BuktiPendukung,
			'Saldo' => $Saldo,
			'PjUserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
			'PjTimeEntry' => date('Y-m-d H:i:s'),
		);

		$WHERE = array(
			'NoPD' => $NoPD
		);


		// print_rr($SET);
		// exit;

		$this->db->trans_begin();

		$this->db->set($SET)->where($WHERE)->update('PdHdr');
		$this->db->query("DELETE FROM PdDtl WHERE NoPD='$NoPD' ");
		$this->db->query("INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,NoPJ,PjUraian,PjVol,PjHrgSatuan,PjUserEntry,PjTimeEntry)
			SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,NoPJ,PjUraian,PjVol,PjHrgSatuan,PjUserEntry,PjTimeEntry FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company'");

		$this->db->query("DELETE FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$this->Company' ");

		if ($this->db->trans_status()===FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
			is_pesan('warning',$NoPD.' gagal di-update');
			// redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		}else{
			$this->db->trans_commit();
			echo 'berhasil';
			is_pesan('success',$NoPD.' berhasil di-update');
			// redirect('Entry/PDPJ/PJLap_C/index/'.$JobNo.'/'.$Alokasi);
			die;
		}

	}

}

/* End of file PJLap_C.php */
/* Location: ./application/controllers/Entry/PDPJ/PJLap_C.php */