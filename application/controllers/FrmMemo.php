<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmMemo extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('Model_Memo');
	}

	

	function simpan_memo(){
		if (!$this->input->post()) {
			exit;
		}

		$NIK = $this->input->post('NIK',TRUE);
		$Tipe = 'Internal';
		$TglMemo = $this->input->post('TglMemo',TRUE);
		$ImageCompany = $this->input->post('ImageCompany',TRUE);
		$Pembuat = $this->input->post('Pembuat',TRUE);
		$Kepada = $this->input->post('Kepada',TRUE);
		$Perihal = $this->input->post('Perihal',TRUE);
		$Remark = $this->input->post('Remark',TRUE);
		$Tembusan = $this->input->post('Tembusan',TRUE);

		if(!empty($Tembusan)){
			$myarray = array();
			foreach($Tembusan as $tem){
				$myarray[] = $tem;
			}

			$Tembusan = implode(',',$myarray);
		}
		
		if(empty($Tembusan)){

			$Tembusan = NULL;
		}

		$Company = $this->config->item('Company');
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');
		$Divisi = $this->Model_Memo->DapatKodeMemo($NIK);

		$NoMemo = $this->Model_Memo->MembuatKodeMemo($NIK,$TglMemo);

		if ($NoMemo == 'job_belum_di_set') {
			is_pesan('info','Job position belum di-set kode memo.');
			redirect('Kepegawaian/view_profile','refresh');
		}

		$dataMemoKirim = array();
		if (isset($_POST['Kepada'])) {
			foreach ($_POST['Kepada'] as $key => $value) {


				$dataMemoKirim[] = array(
					'Tipe' => $Tipe,
					'NoMemo' => $NoMemo,
					'Pembuat' => $Pembuat,
					'TglMemo' => $TglMemo,
					'Kepada' => $value,
					'Perihal' => $Perihal,
					'Remark' => $Remark,
					'Tembusan' => $Tembusan,
					'Company' => $Company,
					'UserEntry' => $UserEntry,
					'TimeEntry' => $TimeEntry,
					'Divisi' => $Divisi,
					'ImageCompany' => $ImageCompany,
				);



			}
		}

		$this->db->trans_start();
			

		$query_simpan_memo = $this->db->insert_batch('Memo',$dataMemoKirim);
		// $query_simpan_memo = $this->db->query("
		// 	INSERT INTO Memo (Tipe, NoMemo, Pembuat, TglMemo, Kepada, Perihal, Remark, Tembusan, Company, UserEntry, TimeEntry, Divisi, ImageCompany) 
		// 	VALUES ('$Tipe', '$NoMemo', '$Pembuat', '$TglMemo', '$Kepada', '$Perihal', '$Remark', '$Tembusan', '$Company', '$UserEntry', '$TimeEntry', '$Divisi', '$ImageCompany')   
		// 	");


		// $LedgerNo 		= $this->db->insert_id();	
		// $NIKPembuat 	= $Pembuat;
		// $NamaPembuat 	= $UserEntry;
		// $NIKPenerima 	= $Kepada;
		// $NamaPenerima	= get_nama_karyawan($NIKPenerima);
		// $DiBaca 		= 0;
		// $UserEntry 		= $this->session->userdata("MIS_LOGGED_NAME");
		// $TimeEntry 		= date('Y-m-d H:i:s');
		// $url_tujuan 	= "Kepegawaian/view_profile/Memo";
		// $Keterangan  	= 'Sent No. Memo : '.$NoMemo;

		// $this->db->query(" INSERT INTO MyNotif (LedgerNo, NIKPembuat, NamaPembuat, NIKPenerima, NamaPenerima, DiBaca, DariTable, UserEntry, TimeEntry, UrlTujuan, Keterangan) 
		// 	VALUES ('$LedgerNo', '$NIKPembuat', '$UserEntry', '$Kepada', '$NamaPenerima', '$DiBaca', 'Memo', '$UserEntry', '$TimeEntry', '$url_tujuan', '$Keterangan') ");

		
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			is_pesan('error','gagal saat menambahkan memo');
			redirect('Kepegawaian/view_profile/Memo','refresh');
		}else{
			is_pesan('success','berhasil menambkan memo');
			redirect('Kepegawaian/view_profile/Memo','refresh');
		}

		

	}


	function tampil_form_edit_memo(){
		if (!$this->input->post()) {
			echo '';
			exit;
		}

		$NIK = $this->session->userdata('MIS_LOGGED_NIK');
		$LedgerNo = $this->input->post('LedgerNo',TRUE);

		$view_memo = ($this->input->post('view_memo',TRUE)) ? $this->input->post('view_memo',TRUE) : '';

		$data['data_memo'] = $this->Model_Memo->get_data_memo($LedgerNo);
		$data['NIK'] = $NIK;
		$data['ListKaryawan'] = $this->Model_Memo->get_list_Karyawan($NIK);


		$button_hilang = NULL;
		if ($data['data_memo']->UserApproval != NULL || $data['data_memo']->UserApproval != ''  ) {
			$button_hilang = 'none';
		}
		if ($view_memo !='') {
			$button_hilang = 'none';
		}

		$data['button_hilang'] = $button_hilang;

		$this->load->view('FrmMemo_view/form_edit_memo',$data);


	}

	function update_memo(){
		if (!$this->input->post()) {
			echo '';
			exit;
		}

		$NIK = $this->input->post('NIK',TRUE);
		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$NoMemo = $this->input->post('NoMemo',TRUE);
		$Tipe = 'Internal';

		$TglMemo = $this->input->post('TglMemo',TRUE);
		$ImageCompany = $this->input->post('ImageCompany',TRUE);
		$Pembuat = $this->input->post('Pembuat',TRUE);
		$Kepada = $this->input->post('Kepada',TRUE);
		$Perihal = $this->input->post('Perihal',TRUE);
		$Remark = $this->input->post('Remark',TRUE);
		$Tembusan = $this->input->post('Tembusan',TRUE);


		if(!empty($Tembusan)){
			$myarray = array();
			foreach($Tembusan as $tem){
				$myarray[] = $tem;
			}

			$Tembusan = implode(',',$myarray);
		}
		
		if(empty($Tembusan)){

			$Tembusan = NULL;
		}


		$this->db->trans_start();
		$this->db->query("

			UPDATE Memo SET TglMemo='$TglMemo', ImageCompany='$ImageCompany', Pembuat='$Pembuat', Kepada='$Kepada', Perihal='$Perihal', Remark='$Remark', Tembusan='$Tembusan' WHERE LedgerNo='$LedgerNo'  
			");
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			// echo 'gagal';
			is_pesan('error','Memo '.$NoMemo.' gagal di ubah');
			redirect('Kepegawaian/view_profile','refresh');
		}else{
			// echo 'berhasil';
			is_pesan('success','Memo '.$NoMemo.' berhasil di ubah');
			redirect('Kepegawaian/view_profile','refresh');
		}

	}


	function cek_status_memo(){
		if (!$this->input->post()) {
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo',TRUE);


		$cek_memo = $this->db->query("SELECT * FROM Memo WHERE LedgerNo='$LedgerNo' ");
		$row = $cek_memo->row();

		if ($cek_memo->num_rows() < 1 ) {
			echo 'kosong';
			exit;
		}

		if ( ($row->UserApproval != NULL || $row->UserApproval != '')  || ($row->TimeApproval != NULL || $row->TimeApproval != '')   ) {
			echo 'user_approval_ada';
			exit;
		}


		$this->db->trans_start();
		$this->db->query("DELETE FROM Memo WHERE LedgerNo='$LedgerNo' ");
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			echo 'gagal';
		}else{
			echo 'berhasil';
		}

	}

	function export_MemoPDF($LedgerNo=''){
		if ($LedgerNo == '') {
			redirect('Kepegawaian/view_profile','refresh');
		}


		$this->load->library('Pdfgenerator');

        // title dari pdf
		$this->data['title_pdf'] = 'Laporan Penjualan Toko Kita';


		$file_pdf = "MemoPDF_".$this->session->userdata('MIS_LOGGED_ID').'_'.$LedgerNo;

		$paper = 'A4';

		$orientation = "Landscape";

		$q = $this->db->query("
			SELECT Memo.*, Karyawan.Nama AS 'NmKaryawan' FROM Memo, Karyawan WHERE
			Memo.LedgerNo='$LedgerNo' AND Memo.Kepada=Karyawan.NIK ORDER BY Memo.TglMemo DESC, Memo.NoMemo DESC")->row();
		$this->data['data_memo'] = $q;

		$html = $this->load->view('FrmMemo_view/export_MemoPDF',$this->data, true);

		$this->pdfgenerator->generate($html, $file_pdf,$paper,$orientation);

	}


	function export_MemoPDF_old($LedgerNo=''){
		if ($LedgerNo == '') {
			redirect('Kepegawaian/view_profile','refresh');
		}

		$q = $this->db->query("
			SELECT Memo.*, Karyawan.Nama AS 'NmKaryawan' FROM Memo, Karyawan WHERE
			Memo.LedgerNo='$LedgerNo' AND Memo.Kepada=Karyawan.NIK ORDER BY Memo.TglMemo DESC, Memo.NoMemo DESC")->row();

		$data['data_memo'] = $q;
		$dompdf = new Dompdf\Dompdf();
		$html =  $this->load->view ('FrmMemo_view/export_MemoPDF',$data,true);
		$dompdf->load_html($html);
		$dompdf->set_option("isPhpEnabled", true);
		// $text = "{PAGE_NUM} of {PAGE_COUNT}";
		// $canvas = $text->get_canvas();
		// $dompdf->page_text(0, 0, "{PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
		// add the header
		$canvas = $dompdf->get_canvas();
		// $font = Font_Metrics::get_font("helvetica", "bold");

		// the same call as in my previous example
		$canvas->page_text(0, 0, "footer: {PAGE_NUM} of {PAGE_COUNT}",
			0, 0, array(0,0,0));
		// $dompdf->page_text($text);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'Landscape');

		// Render the HTML as PDF
		$dompdf->render();
		// Get the generated PDF file contents

		$pdf = $dompdf->output();

		// Output the generated PDF to Browser
		$dompdf->stream("MemoPDF_".$this->session->userdata('MIS_LOGGED_ID').'_'.$LedgerNo,array("Attachment"=>0));

		// $data = array(
		// 	"dataku" => array(
		// 		"nama" => "Petani Kode",
		// 		"url" => "http://petanikode.com"
		// 	)
		// );

		// $this->load->library('pdf');

		// $this->pdf->setPaper('A4', 'potrait');
		// $this->pdf->filename = "laporan-petanikode.pdf";
		// $this->pdf->load_view('test_pdf2', $data); 

	}

	function aksi_approve(){
		if (!$this->input->post()) {
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$flag = $this->input->post('flag',TRUE);


		$cek_memo = $this->db->query("SELECT * FROM Memo WHERE LedgerNo='$LedgerNo' ");
		$row = $cek_memo->row();

		if ($cek_memo->num_rows() < 1 ) {
			is_pesan('warning','Memo tidak ditemukan');
			exit;
		}

		$UserApproval = ($flag == 'selesai') ? $this->session->userdata('MIS_LOGGED_NAME') : NULL;
		$TimeApproval = ($flag == 'selesai') ? date('Y-m-d H:i:s') : '';

		$this->db->trans_start();
		$this->db->query("UPDATE Memo SET UserApproval='$UserApproval',TimeApproval='$TimeApproval' WHERE LedgerNo='$LedgerNo' ");
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			is_pesan('error','Gagal');
			exit;
		}else{
			is_pesan('success','Berhasil');
		}

	}




}

/* End of file FrmMemo.php */
/* Location: ./application/controllers/FrmMemo.php */