<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_keuangan_entry_controller extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('PD_PJ_model/M_pertanggung_jawaban_entry_keuangan');
		if (!$this->db->table_exists('PdDtl_cadangan'))
		{
			echo 'Table PdDtl_cadangan tidak ditemukan';
			exit;
		}
	}

	public function pj_keuangan_list($JobNo='')
	{
		if ($JobNo == '') {
			redirect('Welcome/index');
			exit;
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'ENTRY PERTANGGUNG JAWABAN (KEUANGAN) ';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';

		$tgl_saat_ini = date('Y-m-d');

		$data['tgl_pertama'] = date('Y-m-d', strtotime('-7 days'));
		$data['tgl_terakhir'] = $tgl_saat_ini;
		$data['JobNo'] = $JobNo;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('PDPJ_Entry/PJ_keuangan_entry_view/view_pj_keuangan', $data);
		$this->load->view('templates/footer');

	}

	function dapat_list_pj_keuangan(){
		if (!$this->input->post('id')) {
			exit;
		}

		// echo '<h1>ddd</h1>';
		// exit;

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;

		$dapat_TotalPD_dan_TotalPJ = $this->db->query("SELECT SUM(TotalPD) as TotalPD,
			SUM(TotalPJ) as TotalPJ FROM PdHdr 
			WHERE JobNo = '21640' AND 
			Alokasi = 'B' AND  (NoRef IS NULL OR NoRef = '' ) AND RejectBy IS NULL")->row_array();

		$data['TotalPJ'] = $dapat_TotalPD_dan_TotalPJ['TotalPJ'];
		$data['TotalPD'] = $dapat_TotalPD_dan_TotalPJ['TotalPD'];






		$this->load->view('PDPJ_Entry/PJ_keuangan_entry_view/table_list_pj_keuangan',$data);
	}

	function data_json_pj_keuangan(){
		if (!$this->input->post('flag')) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$where = array(
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi
		);

		header('Content-Type: application/json');
		echo $this->M_pertanggung_jawaban_entry_keuangan->data_json_pj_keuangan($where);

	}

	function aksi_approve_pj_keuangan(){
		if (!$this->input->post()) {
			exit;
		}
		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);
		$flag_status_approve = ($this->input->post('flag_status_approve',TRUE) == 0) ? 'APPROVE' : 'UNAPPROVE';

		$dapat_data = $this->db->query("SELECT *,(SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE FROM PdHdr WHERE NoPD='$NoPD'");

		$jumlah_data = $dapat_data->num_rows();
		$row_pd = $dapat_data->row_array();


		//cek ada data atau tidak
		if ($jumlah_data == 0) {
			$myreturn = array(
				'status' => 'data_tidak_ada', 
				'pesan' => 'Data tidak ditemukan', 
				'NoPD' => $NoPD,
			);
			echo json_encode($myreturn);
			exit;
		}

		//cek reject
		if ($row_pd['RejectBy'] !=NULL || $row_pd['RejectBy'] !='' ) {
			$RejectBy = $row_pd['RejectBy'];
			$myreturn = array(
				'status' => 'reject', 
				'pesan' => 'Data sudah di reject oleh '.$RejectBy.'', 
				'NoPD' => $NoPD,
			);
			echo json_encode($myreturn);
			exit;
		}

		//cek pembayaran
		if ($row_pd['CountBLE'] == 0 ) {
			$myreturn = array(
				'status' => 'belum_bayar', 
				'pesan' => 'Belum ada pembayaran untuk No '.$NoPD.' ', 
				'NoPD' => $NoPD,
			);
			echo json_encode($myreturn);
			exit;
		}

		if ($flag_status_approve == 'APPROVE') {

			if ($row_pd['NoPJ'] == NULL || $row_pd['NoPJ'] == '' ) {

				$myreturn = array(
					'status' => 'pj_masih_kosong', 
					'pesan' => 'No PJ masih kosong ', 
					'NoPD' => $NoPD,
				);
				echo json_encode($myreturn);
				exit;
			}

			$aksi_update_pj = $this->M_pertanggung_jawaban_entry_keuangan->aksi_update_pj($flag_status_approve,$NoPD);
			if ($aksi_update_pj == TRUE) {
				echo json_encode(array('status' => 'berhasil','pesan' => 'berhasil mengubah data'));
				exit;
			}
			echo json_encode(array('status' => 'gagal','pesan' => 'gagal mengubah data' ));
			exit;
		}

		if ($flag_status_approve == 'UNAPPROVE') {

			// if ($row_pd['PjApprovedByKT'] != NULL || $row_pd['PjApprovedByKT'] != '' ) {

			// 	$myreturn = array(
			// 		'status' => 'sudah_approvedKT', 
			// 		'pesan' => 'Sudah di-approved oleh Cost Control.', 
			// 		'NoPD' => $NoPD,
			// 	);
			// 	echo json_encode($myreturn);
			// 	exit;
			// }

			if ($row_pd['PjApprovedByKK'] != NULL || $row_pd['PjApprovedByKK'] != '' ) {

				$myreturn = array(
					'status' => 'sudah_approvedKK', 
					'pesan' => 'Sudah di-approved oleh Keuangan.',  
					'NoPD' => $NoPD,
				);
				echo json_encode($myreturn);
				exit;
			}

			$aksi_update_pj = $this->M_pertanggung_jawaban_entry_keuangan->aksi_update_pj($flag_status_approve,$NoPD);

			if ($aksi_update_pj == TRUE) {
				echo json_encode(array('status' => 'berhasil','pesan' => 'berhasil mengubah data'));
				exit;
			}
			echo json_encode(array('status' => 'gagal','pesan' => 'gagal mengubah data' ));
			exit;
			
		}


	}

	function detail_pj_keuangan($LedgerNo='',$myflag='',$NoPD){
		if ($LedgerNo == '') {
			redirect('Welcome/index');
			exit;
		}

		$dapatkan_data_pd = $this->M_pertanggung_jawaban_entry_keuangan->get_data_pj_keuangan($LedgerNo,$NoPD);
		$dataPD = $dapatkan_data_pd->row_array();



		if ($dapatkan_data_pd->num_rows() == 0 ) {
			is_pesan('error','data tidak ditemukan');
			redirect('PDPJ/PJ_keuangan_entry_controller/pj_keuangan_list/'.$dataPD['JobNo'],'refresh');
			exit;
		}

		if ($dataPD['CountBLE'] == 0 ) {
			is_pesan('error','belum ada pembayaran untuk No '.$NoPD.' ');
			redirect('PDPJ/PJ_keuangan_entry_controller/pj_keuangan_list/'.$dataPD['JobNo'],'refresh');
			exit;
		}

		//pemidahan data PdDtl Ke Table sementara bernama PdDtl_cadangan
		// $pindah_data_PdDtl = $this->M_pertanggung_jawaban_entry_keuangan->pindah_data_PdDtl($NoPD);

		$btn_dimatikan = '';
		$status_PjApprovedByAK='';
		$status_NoPJ = '';

		if ($dataPD['NoPJ'] !=NULL || $dataPD['NoPJ'] !='') {
			$btn_dimatikan = 'disabled="disabled"';
			$status_NoPJ = '<div class="row"><div class="col-sm-6"><div class="alert alert-success">
			<small style="font-size:14px;color:black;"><i class="icon fa fa-check"></i> No PJ ('.$dataPD['NoPJ'].') </small>
			</div>
			</div>
			</div>';
		}
		
		if ($dataPD['PjApprovedByAK'] !=NULL || $dataPD['PjApprovedByAK'] !='') {
			$btn_dimatikan = 'disabled="disabled"';
			$status_PjApprovedByAK = '<div class="row"><div class="col-sm-6"><div class="alert alert-info">
			<small style="font-size:14px;color:black;"><i class="icon fa fa-check"></i> Approved By AK ('.$dataPD['PjApprovedByAK'].') </small>
			</div>
			</div>
			</div>';
		}


		$status_PjApprovedByKT='';
		// if ($dataPD['PjApprovedByKT'] !=NULL || $dataPD['PjApprovedByKT'] !='') {
		// 	$btn_dimatikan = 'disabled="disabled"';
		// 	$status_PjApprovedByKT = '<div class="row"><div class="col-sm-6"><div class="alert alert-warning">
		// 	<small style="font-size:14px;color:black;"><i class="icon fa fa-check"></i> Approved By KT ('.$dataPD['PjApprovedByKT'].') </small>
		// 	</div>
		// 	</div>
		// 	</div>';
		// }

		$status_PjApprovedByKK='';
		if ($dataPD['PjApprovedByKK'] !=NULL || $dataPD['PjApprovedByKK'] !='') {
			$btn_dimatikan = 'disabled="disabled"';
			$status_PjApprovedByKK = '<div class="row"><div class="col-sm-6"><div class="alert alert-danger">
			<small style="font-size:14px;color:black;"><i class="icon fa fa-check"></i> Approved By KK ('.$dataPD['PjApprovedByKK'].') </small>
			</div>
			</div>
			</div>';
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'ENTRY PERTANGGUNG JAWABAN (KEUANGAN) ';
		$data['bodyclass'] = 'sidebar-collapse skin-warnign';
		$data['dataPD'] = $dataPD;
		$data['myflag'] = $myflag;
		$data['btn_dimatikan'] = $btn_dimatikan;

		$data['status_PjApprovedByAK'] = $status_PjApprovedByAK;
		$data['status_PjApprovedByKT'] = $status_PjApprovedByKT;
		$data['status_PjApprovedByKK'] = $status_PjApprovedByKK;
		$data['status_NoPJ'] = $status_NoPJ;

		// print_rr($data['btn_dimatikan']);
		// exit;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('PDPJ_entry/PJ_keuangan_entry_view/detail_pj_keuangan',$data);
		$this->load->view('templates/footer');
	}

	function tampil_uraian_pj(){
		if (!$this->input->post('myflag')) {
			exit;
		}

		$myflag = $this->input->post('myflag',TRUE);
		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);

		$cek_NoPJ = $this->db->query("SELECT NoPJ FROM PdHdr WHERE LedgerNo='$LedgerNo' AND NoPD='$NoPD' ")->row_array();
		$NoPJ = $cek_NoPJ['NoPJ'];
		$status_detail_PdDtl = ($NoPJ == NULL || $NoPJ == '') ? 'PdDtl_cadangan' : 'PdDtl';

		$data_detail = $this->M_pertanggung_jawaban_entry_keuangan->dapat_data_detail_PdDtl($LedgerNo,$NoPD,$NoPJ,$status_detail_PdDtl);

		$data['data_detail'] = $data_detail;

		$data['myflag'] = $myflag;
		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['status_detail_PdDtl'] = $status_detail_PdDtl;
		$data['status_detail_PdDtl_ubah'] = ($status_detail_PdDtl == 'PdDtl_cadangan') ? 1 : 0;


		$dapat_AmountBLE = $this->db->query("SELECT SUM(Amount) as AmountBLE FROM BLE WHERE NoPD='$NoPD' GROUP BY NoPD")->row_array();
		$AmountBLE = $dapat_AmountBLE['AmountBLE'];

		$dapatTotalPJ = $this->db->query("SELECT TotalPJ, TotalPD FROM PdHdr WHERE NoPD='$NoPD'")->row_array();
		$my_TotalPJ = $dapatTotalPJ['TotalPJ'];
		$DanaTersediaDiLapangan = $AmountBLE - $my_TotalPJ;
		$MySaldo = $DanaTersediaDiLapangan - $my_TotalPJ;


		$data['DanaTersediaDiLapangan'] = $DanaTersediaDiLapangan;
		$data['MySaldo'] = ($DanaTersediaDiLapangan == 0) ? $my_TotalPJ : $MySaldo;

		// echo $my_TotalPJ;
		// exit;

		$this->load->view('PDPJ_Entry/PJ_keuangan_entry_view/table_uraian_pj_keuangan',$data);

	}

	function dapat_uraian_per_row(){
		if (!$this->input->post('NoPD')) {
			exit;
		}

		$NoUrut = $this->input->post('NoUrut',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);
		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);
		$status_detail_PdDtl = $this->input->post('status_detail_PdDtl',TRUE);

		$btn_dimatikan = ($this->input->post('btn_dimatikan') == 1) ? 'disabled="disabled"' : '';

		$dapat_PdDtl = $this->M_pertanggung_jawaban_entry_keuangan->dapat_PdDtl($NoUrut,$NoPD,$JobNo,$Alokasi,$status_detail_PdDtl);
		$data['uraian_data'] = $dapat_PdDtl;
		$data['btn_dimatikan_uraian'] = $btn_dimatikan;
		$data['status_detail_PdDtl'] = $status_detail_PdDtl;

		$this->load->view('PDPJ_Entry/PJ_keuangan_entry_view/form_uraian_pj',$data);
		
	}

	function simpan_uraian_pj(){
		if (!$this->input->post()) {
			exit;
		}

		$NoUrut = $this->input->post('NoUrut',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);

		$PjUraian = $this->input->post('PjUraian',TRUE);
		$PjVol = $this->input->post('PjVol',TRUE);
		$PjHrgSatuan = HapusFormatUang($this->input->post('PjHrgSatuan',TRUE));

		$PjUserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$PjTimeEntry = date('Y-m-d H:i:s');

		$status_detail_PdDtl = $this->input->post('status_detail_PdDtl',TRUE);

		$data = array(
			'PjUraian'		=> $PjUraian,
			'PjVol'			=> $PjVol,
			'PjHrgSatuan'	=> $PjHrgSatuan,
			'PjUserEntry'	=> $PjUserEntry,
			'PjTimeEntry'	=> $PjTimeEntry,
		);

		$where = array(
			'NoUrut' => $NoUrut,
			'NoPD' => $NoPD,
		);
		$simpan_data = $this->M_pertanggung_jawaban_entry_keuangan->simpan_uraian_pj($data,$where,$status_detail_PdDtl);
		if ($simpan_data == TRUE) {
			echo 'berhasil';
			exit;
		}
		echo 'gagal';
		exit;
	}

	function simpan_pj_entry_keuangan_detail(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);
		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);

		$BuktiPendukung = ($this->input->post('BuktiPendukung', TRUE)) ?  MembuatMenjadiKoma($this->input->post('BuktiPendukung', TRUE)) : '';
		$Keterangan = $this->input->post('Keterangan',TRUE);
		$TglPJ = $this->input->post('TglPJ',TRUE);
		$TotalPJ = HapusFormatUang($this->input->post('TotalPJ',TRUE));

		$NoPJ = $this->Pembuatan_NoPJ($NoPD);

		$PjUserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$PjTimeEntry = date('Y-m-d H:i:s');

		$data_set = array(
			'NoPJ' => $NoPJ,
			'BuktiPendukung' => $BuktiPendukung,
			'Keterangan' => $Keterangan,
			'TglPj' => $TglPJ,
			'TotalPJ' => $TotalPJ,
			'PjUserEntry' => $PjUserEntry,
			'PjTimeEntry' => $PjTimeEntry
		);

		$update_pdhdr_pembuatan_NoPJ = $this->M_pertanggung_jawaban_entry_keuangan->update_pdhdr_pembuatan_NoPJ($data_set,$NoPD,$LedgerNo);
		if ($update_pdhdr_pembuatan_NoPJ == TRUE) {
			is_pesan('success','Berhasil mengubah data');
			echo 'berhasil';
			exit;
		}
		is_pesan('error','Gagal! terjadi kesalahan saat mengubah data');
		echo 'gagal';
		exit;

		// print_rr($my_set);
		// echo "<hr>";
		// print_rr($_POST);
	}

	function aksi_Isi_Uraian_Apabila_PD(){
		if (!$this->input->post()) {
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);


		$this->db->trans_begin();

		$PjUserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$PjTimeEntry = date('Y-m-d H:i:s');


		$query = $this->db->select('NoPD,NoUrut,Uraian,Vol,HrgSatuan')->where('NoPD',$NoPD)->get('PdDtl_cadangan')->result_array();
		foreach($query as $mydata){

			$NoUrut = $mydata['NoUrut'];
			$NoPD = $mydata['NoPD'];

			$PjUraian = $mydata['Uraian'];
			$PjVol = $mydata['Vol'];
			$PjHrgSatuan = $mydata['HrgSatuan'];

			$this->db->query(" UPDATE PdDtl_cadangan SET PjUraian='$PjUraian', PjVol='$PjVol', PjHrgSatuan='$PjHrgSatuan', PjUserEntry='$PjUserEntry', PjTimeEntry='$PjTimeEntry' WHERE NoUrut='$NoUrut' AND NoPD='$NoPD' ");

		}
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
		} else {
			$this->db->trans_commit();
			echo 'berhasil';
		}

		// echo 'berhasil';
	}

	private function Pembuatan_NoPJ($NoPD){
		$NoPJ = substr($NoPD,2);
		return 'PJ'.$NoPJ;
	}


}

/* End of file PJ_keuangan_entry_controller.php */
/* Location: ./application/controllers/PDPJ/PJ_keuangan_entry_controller.php */