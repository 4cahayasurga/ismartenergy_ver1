<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmCuti extends CI_Controller {


	function __construct()
	{
		parent::__construct();
		is_login();

		// pembuatan_tbl_notif();
		$this->load->model('Model_FrmCuti');
	}

	function index($id_tabs='')
	{


		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$NIK = $this->session->userdata('MIS_LOGGED_NIK');

		$data['judul'] = 'Request Cuti / Kehadiran';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['tgl_pertama'] = date('Y-m-01');
		$data['tgl_terakhir'] = date('Y-m-t');
		$data['NIK'] = $NIK;
		$sisa_cuti = $this->Model_FrmCuti->cek_sisa_cuti($NIK);
		$data['sisa_cuti'] = $sisa_cuti;

		$data['NIK'] = $NIK;

		$data['dataKaryawanPengganti'] = $this->Model_FrmCuti->getKaryawanPengganti();

		$get_cuti = $this->Model_FrmCuti->q_table_cuti($NIK);

		$data['data_cuti'] = $get_cuti;


		// $data['data_approve_cuti'] = $this->db->query("SELECT 
		// 	A.*, (SELECT Nama FROM Karyawan WHERE NIK=A.NIK ) AS 'NamaRequest',
		// 	(SELECT DiBaca FROM MyNotif WHERE LedgerNo=A.LedgerNo ) AS 'DiBaca' 
		// 	FROM RequestADO A 
		// 	WHERE A.Kepada='$NIK' AND 
		// 	(A.SDMAction IS NULL OR A.SDMAction='Pending' )  
		// 	AND (A.HeadAction IS NULL OR A.HeadAction='Pending') 
		// 	ORDER BY A.DrTgl DESC   ")->result();

		// $data['approved_cuti'] = $this->db->query("
		// 	SELECT 
		// 	A.*, (SELECT Nama FROM Karyawan WHERE NIK=A.NIK ) AS 'NamaRequest',
		// 	(SELECT DiBaca FROM MyNotif WHERE LedgerNo=A.LedgerNo ) AS 'DiBaca' 
		// 	FROM RequestADO A 
		// 	WHERE A.NIK='$NIK'
		// 	AND (A.HeadAction IS NULL OR A.HeadAction='Approve') 
		// 	ORDER BY A.DrTgl DESC   ")->result();

		// $data['reject_cuti'] = $this->db->query("
		// 	SELECT 
		// 	A.*, (SELECT Nama FROM Karyawan WHERE NIK=A.NIK ) AS 'NamaRequest',
		// 	(SELECT DiBaca FROM MyNotif WHERE LedgerNo=A.LedgerNo ) AS 'DiBaca' 
		// 	FROM RequestADO A 
		// 	WHERE A.NIK='$NIK'
		// 	AND (A.HeadAction IS NULL OR A.HeadAction='Reject') 
		// 	ORDER BY A.DrTgl DESC   ")->result();


		// $data['approved_SDM'] = $this->db->query("
		// 	SELECT 
		// 	A.*, (SELECT Nama FROM Karyawan WHERE NIK=A.NIK ) AS 'NamaRequest',
		// 	(SELECT DiBaca FROM MyNotif WHERE LedgerNo=A.LedgerNo ) AS 'DiBaca' 
		// 	FROM RequestADO A 
		// 	WHERE A.NIK='$NIK' AND A.SDMAction='Approve'
		// 	ORDER BY A.DrTgl DESC   ")->result();

		// $data['rejected_SDM'] = $this->db->query("
		// 	SELECT 
		// 	A.*, (SELECT Nama FROM Karyawan WHERE NIK=A.NIK ) AS 'NamaRequest',
		// 	(SELECT DiBaca FROM MyNotif WHERE LedgerNo=A.LedgerNo ) AS 'DiBaca' 
		// 	FROM RequestADO A 
		// 	WHERE A.NIK='$NIK' AND A.SDMAction='Reject'
		// 	ORDER BY A.DrTgl DESC   ")->result();

		// print_rr($data['approved_SDM']);
		// exit;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmCuti_view/cuti_page_main', $data);
		$this->load->view('templates/footer');
	}

	function table_cuti(){
		if (!$this->input->post()) {
			exit;
		}

		$NIK = $this->input->post('NIK');
		$get_cuti = $this->Model_FrmCuti->q_table_cuti($NIK);

		
		$data['data_cuti'] = $get_cuti;
		$data['NIK'] = $NIK;




		$this->load->view('FrmCuti_view/table_cuti',$data);
	}

	function form_entry_cuti(){
		if (!$this->input->post()) {
			exit;
		}
		$NIK = $this->input->post('NIK',true);
		
		$data['NIK'] = $NIK;

		$data['dataKaryawanPengganti'] = $this->Model_FrmCuti->getKaryawanPengganti();

		$this->load->view('FrmCuti_view/form_entry_cuti',$data);

		
	}

	function aksi_entry_cuti(){
		if (!$this->input->post()) {
			exit;
		}

		// echo 'aaaa';
		// exit;
		// if ($this->session->userdata('MIS_LOGGED_NIK') == '' || $this->session->userdata('MIS_LOGGED_NIK') == NULL ) {
		// 	redirect('Auth/logout');
		// }
		// $NIK = $this->session->userdata('MIS_LOGGED_NIK');
		$NIK = $this->input->post('NIK',TRUE);
		$DrTgl = $this->input->post('DrTgl',TRUE);
		$SpTgl = $this->input->post('SpTgl',TRUE);
		$Status = $this->input->post('TipeRequest',TRUE);
		$JamMasuk = '00:00:00.0000000';
		$JamKeluar = '00:00:00.0000000';
		$Remark = $this->input->post('Deskripsi',TRUE);
		$Kepada = $this->Model_FrmCuti->TemukanAtasan($NIK);
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');
		$HeadAction = 'Pending';
		$SDMAction = 'Pending';
		$KarPeng = atur_null($this->input->post('KaryawanPengganti',TRUE)); 

		$sisa_cuti = $this->Model_FrmCuti->cek_sisa_cuti($NIK);

		if ($Status == 'Cuti' || $Status == 'Cuti 1/2 Hari' ) {
			if ($sisa_cuti < 1) {
				echo 'cuti_abis';
				exit;
			}
		}
		

		print_rr($_POST);
		exit;

		$data = array(
			'NIK' => $NIK,
			'DrTgl' => $DrTgl,
			'SpTgl' => $SpTgl,
			'Status' => $Status,
			'JamMasuk' => $JamMasuk,
			'JamKeluar' => $JamKeluar,
			'Remark' => $Remark,
			'Kepada' => $Kepada,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'HeadAction' => $HeadAction,
			'SDMAction' => $SDMAction,
			'KarPeng' => $KarPeng,

		);
		$table = 'RequestADO';

		$save = $this->Model_FrmCuti->simpan_cuti($table,$data);
		if ($save == TRUE) {
			echo 'berhasil';
		}else{
			echo 'gagal';
		}

	}

	function tampilkan_form_edit_cuti(){
		if (!$this->input->post()) {
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo');
		$q = $this->Model_FrmCuti->dapat_data_cuti($LedgerNo);
		$data['row_cuti'] = $q;
		$data['LedgerNo'] = $LedgerNo;
		$data['dataKaryawanPengganti'] = $this->Model_FrmCuti->getKaryawanPengganti();
		$this->load->view('FrmCuti_view/form_edit_cuti',$data);
	}

	function aksi_edit_cuti(){
		if (!$this->input->post()) {
			exit;
		}
		

		$LedgerNo = $this->input->post('LedgerNo');
		$table = 'RequestADO';
		$cek_pending = $this->Model_FrmCuti->cek_status_cuti($LedgerNo,$table);
		if ($cek_pending->HeadAction != 'Pending' || $cek_pending->SDMAction != 'Pending' ) {
			echo 'tidak_pending';
			exit;
		}

		$DrTgl = $this->input->post('DrTgl');
		$SpTgl = $this->input->post('SpTgl');
		$Status = $this->input->post('TipeRequest');
		$JamMasuk = '00:00:00.0000000';
		$JamKeluar = '00:00:00.0000000';
		$Remark = $this->input->post('Deskripsi');

		$KarPeng = atur_null($this->input->post('KaryawanPengganti')); 

		$set = array(

			'DrTgl' => $DrTgl,
			'SpTgl' => $SpTgl,
			'Status' => $Status,
			'JamMasuk' => $JamMasuk,
			'JamKeluar' => $JamKeluar,
			'Remark' => $Remark,
			'KarPeng' => $KarPeng,

		);

		$this->db->trans_start();

		$this->db->query("UPDATE RequestADO SET DrTgl='$DrTgl', SpTgl='$SpTgl', Status='$Status', JamMasuk='$JamMasuk', JamKeluar='$JamKeluar', Remark='$Remark', KarPeng='$KarPeng' WHERE LedgerNo='$LedgerNo' ");

		// $this->db->query("UPDATE MyNotif SET Status='$Status' WHERE LedgerNo='$LedgerNo' ");

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			echo 'gagal';

		}
		else
		{
			echo 'berhasil';
		}

		// $table = 'RequestADO';
		// $where = array('LedgerNo' => $LedgerNo);

		// $save = $this->Model_FrmCuti->update_cuti($table,$set,$where);
		// if ($save == TRUE) {
		// 	echo 'berhasil';
		// }else{
		// 	echo 'gagal';
		// }

	}

	function hapus_cuti_request(){
		if (!$this->input->post()) {
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo');
		$table = 'RequestADO';
		$cek_pending = $this->Model_FrmCuti->cek_status_cuti($LedgerNo,$table);

		if ($cek_pending->HeadAction != 'Pending'  ) {
			ecHo json_encode(array('flag'=>'atasan', 'pesan' => 'Tidak bisa hapus, request sudah di-'.$cek_pending->HeadAction.'  oleh atasan.' ));
			exit;
		}

		if ($cek_pending->SDMAction != 'Pending' ) {
			ecHo json_encode(array('flag'=>'sdm','pesan' => 'Tidak bisa hapus, request sudah di-'.$cek_pending->SDMAction.'  oleh SDM.' ));
			exit;
		}

		$table = 'RequestADO';
		$where = array('LedgerNo' => $LedgerNo);

		$hapus = $this->Model_FrmCuti->hapus_cuti($table,$where);
		if ($hapus == TRUE) {
			ecHo json_encode(array('flag'=>'berhasil'));
			exit;
		}else{
			ecHo json_encode(array('flag'=>'gagal'));
			exit;
		}



		// print_rr($_POST);
        // exit;
	}

	function add_cuti_profile(){
		if (!$this->input->post()) {
			exit;
		}

		$NIK = $this->input->post('NIK',TRUE);

		if ($NIK == '' || $NIK == NULL ) {
			is_pesan_saya('error','NIK tidak terdaftar!');
			redirect('Auth/logout');
		}

		$DrTgl = $this->input->post('DrTgl',TRUE);
		$SpTgl = $this->input->post('SpTgl',TRUE);
		$Status = $this->input->post('TipeRequest',TRUE);
		$JamMasuk = '00:00:00.0000000';
		$JamKeluar = '00:00:00.0000000';
		$Remark = $this->input->post('Deskripsi',TRUE);
		$Kepada = $this->Model_FrmCuti->TemukanAtasan($NIK);
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');
		$HeadAction = 'Pending';
		$SDMAction = 'Pending';
		$KarPeng = atur_null($this->input->post('KaryawanPengganti',TRUE)); 

		$sisa_cuti = $this->Model_FrmCuti->cek_sisa_cuti($NIK);

		if ($Status == 'Cuti' || $Status == 'Cuti 1/2 Hari' ) {
			if ($sisa_cuti < 1) {
				// echo 'cuti_habis';
				is_pesan('info','Sisa cuti telah habis');
				redirect('FrmCuti','refresh');
				exit;
			}
		}

		

		$Atasan_NIK = $Kepada;
		$Company = $this->config->item('Company');

		$this->db->trans_start();

		$this->db->query("INSERT INTO RequestADO (NIK, DrTgl, SpTgl, Status, JamMasuk,JamKeluar, Remark, Kepada, UserEntry, TimeEntry, HeadAction, SDMAction, KarPeng) VALUES ('$NIK', '$DrTgl', '$SpTgl', '$Status', '$JamMasuk', '$JamKeluar', '$Remark', '$Kepada', '$UserEntry', '$TimeEntry', '$HeadAction', '$SDMAction', '$KarPeng') ");

		//mendapatkan id yang baru saja di input
		// $LedgerNo 		= $this->db->insert_id();	
		// $NIKPembuat 	= $NIK;
		// $NamaPembuat 	= $UserEntry;
		// $NIKPenerima 	= $Kepada;
		// $NamaPenerima	= get_nama_karyawan($NIKPenerima);
		// $DiBaca 		= 0;
		// $DariTable		= "RequestADO";
		// $UserEntry 		= $this->session->userdata("MIS_LOGGED_NAME");
		// $TimeEntry 		= date('Y-m-d H:i:s');
		// $url_tujuan 	= "Kepegawaian/view_profile/Cuti";
		// $Keterangan  	= "Pengajuan Cuti (".$NamaPembuat.")";

		// $this->db->query(" INSERT INTO MyNotif (LedgerNo, NIKPembuat, NamaPembuat, NIKPenerima, NamaPenerima, DiBaca, DariTable, UserEntry, TimeEntry, UrlTujuan, Keterangan) 
		// 	VALUES ('$LedgerNo', '$NIKPembuat', '$UserEntry', '$Kepada', '$NamaPenerima', '$DiBaca', '$DariTable', '$UserEntry', '$TimeEntry', '$url_tujuan', '$Keterangan') ");

		
		// $this->db->query("INSERT INTO MyNotif (LedgerNo, NIK, UserEntry, TimeEntry, DiBaca, Status, TujuanCompany, DariCompany, Atasan_NIK, Atasan_Status_Aksi, SDM_Status_Aksi) VALUES ('$LedgerNo', '$NIK', '$UserEntry', '$TimeEntry', 0, '$Status', 'HRIS', '$Company','$Atasan_NIK','Pending','Pending')  ");

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			is_pesan('error','Error');

		}
		else
		{
			is_pesan('success','berhasil, data telah ditambahkan');
		}

		redirect('Kepegawaian/view_profile/Cuti');
	}

	function add_cuti(){
		if (!$this->input->post()) {
			exit;
		}



		$NIK = $this->input->post('NIK',TRUE);

		if ($NIK == '' || $NIK == NULL ) {
			is_pesan_saya('error','NIK tidak terdaftar!');
			redirect('Auth/logout');
		}

		$DrTgl = $this->input->post('DrTgl',TRUE);
		$SpTgl = $this->input->post('SpTgl',TRUE);
		$Status = $this->input->post('TipeRequest',TRUE);
		$JamMasuk = '00:00:00.0000000';
		$JamKeluar = '00:00:00.0000000';
		$Remark = $this->input->post('Deskripsi',TRUE);
		$Kepada = $this->Model_FrmCuti->TemukanAtasan($NIK);
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');
		$HeadAction = 'Pending';
		$SDMAction = 'Pending';
		$KarPeng = atur_null($this->input->post('KaryawanPengganti',TRUE)); 


		$sisa_cuti = $this->Model_FrmCuti->cek_sisa_cuti($NIK);

		if ($Status == 'Cuti' || $Status == 'Cuti 1/2 Hari' ) {
			if ($sisa_cuti < 1) {

				is_pesan('info','Sisa cuti telah habis');
				redirect('FrmCuti','refresh');
				exit;
			}
		}

		

		$Atasan_NIK = $Kepada;
		$Company = $this->config->item('Company');

		$this->db->trans_begin();

		$this->db->query("INSERT INTO RequestADO (NIK, DrTgl, SpTgl, Status, JamMasuk,JamKeluar, Remark, Kepada, UserEntry, TimeEntry, HeadAction, SDMAction, KarPeng) VALUES ('$NIK', '$DrTgl', '$SpTgl', '$Status', '$JamMasuk', '$JamKeluar', '$Remark', '$Kepada', '$UserEntry', '$TimeEntry', '$HeadAction', '$SDMAction', '$KarPeng') ");

		//mendapatkan id yang baru saja di input
		$LedgerNo = $this->db->insert_id();

		
		// $this->db->query("INSERT INTO MyNotif (LedgerNo, NIK, UserEntry, TimeEntry, DiBaca, Status, TujuanCompany, DariCompany, Atasan_NIK, Atasan_Status_Aksi, SDM_Status_Aksi) VALUES ('$LedgerNo', '$NIK', '$UserEntry', '$TimeEntry', 0, '$Status', 'HRIS', '$Company','$Atasan_NIK','Pending','Pending')  ");


		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			is_pesan('error','Error');

		}
		else
		{
			$this->db->trans_commit();
			is_pesan('success','berhasil, data telah ditambahkan');
		}

		redirect('FrmCuti');

		// print_rr($data);
		// exit;
	}

	function ApproveAtasan(){
		if (!$this->input->post()) {
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$flag = $this->input->post('flag',TRUE);


		$dapat_data = $this->db->query("SELECT * FROM RequestADO WHERE LedgerNo='$LedgerNo' ");
		$row = $dapat_data->row();

		$HeadUserAction = $this->session->userdata("MIS_LOGGED_NAME");
		$HeadTimeAction = date('Y-m-d H:i:s');

		if ($dapat_data->num_rows() > 0) {
			
			if ($row->HeadAction !='' || $row->HeadAction != NULL ) {
				
				if ($row->HeadAction == 'Approve') {
					echo 'sudah_approve';
					exit;
				}

				if ($row->HeadAction == 'Reject') {
					echo 'sudah_reject';
					exit;
				}

				if ($row->HeadAction == 'Pending') {

					
					$this->db->trans_start();

					$this->db->query(" UPDATE RequestADO SET HeadAction='$flag', HeadUserAction='$HeadUserAction', HeadTimeAction='$HeadTimeAction'WHERE LedgerNo='$LedgerNo'   ");

					// $this->db->query(" UPDATE MyNotif SET Atasan_Status_Aksi='$flag', Atasan_Entry_Aksi='$HeadUserAction', Atasan_Time_Entry_Aksi='$HeadTimeAction', DiBaca='1' WHERE LedgerNo='$LedgerNo'   ");



					$this->db->trans_complete();

					if ($this->db->trans_status() === FALSE)
					{
						echo 'gagal';
					}else{
						echo 'berhasil';
					}

				}



			}

		}else{
			echo 'error';
			exit;
		}

		// print_rr($_POST);
	}



	




}

/* End of file FrmCuti.php */
/* Location: ./application/controllers/FrmCuti.php */