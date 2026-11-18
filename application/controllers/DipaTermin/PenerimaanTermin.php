<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenerimaanTermin extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();

		$this->Company = $this->config->item('Company');
		$this->load->model('Penerimaan_termin_model/M_penerimaan_termin');

		if (!$this->db->field_exists('PPN_persen', 'TerminInduk'))
		{
			echo "Field <b>PPN_persen</b> tidak ditemukan, silahkan di masukkan dalam database";
			exit;
		}

		if (!$this->db->field_exists('PPH_persen', 'TerminInduk'))
		{
			echo "Field <b>PPH_persen</b> tidak ditemukan, silahkan di masukkan dalam database";
			exit;
		}

		if (!$this->db->field_exists('PPN_nominal', 'TerminInduk'))
		{
			echo "Field <b>PPN_nominal</b> tidak ditemukan, silahkan di masukkan dalam database";
			exit;
		}

		if (!$this->db->field_exists('PPH_nominal', 'TerminInduk'))
		{
			echo "Field <b>PPH_nominal</b> tidak ditemukan, silahkan di masukkan dalam database";
			exit;
		}

		if (!$this->db->field_exists('NettoBOQ', 'TerminInduk'))
		{
			echo "Field <b>NettoBOQ</b> tidak ditemukan, silahkan di masukkan dalam database";
			exit;
		}

	}

	private function GetJob_For_PenerimaanTermin(){

		$AksesJob = $this->session->userdata('PecahToken')->AksesJob;
		$QueryJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE (StatusJob='Pelaksanaan' OR StatusJob='Pemeliharaan') AND TipeJob='Project' AND Company='$this->Company' ");
		$ArrayJob = array();
		foreach($QueryJob->result_array() as $myjob ):

			if (!preg_match('/\b' . $myjob['JobNo'] . '\b/', $AksesJob)) {
				continue;
			}
			$ArrayJob[] = (object) array('JobNo' => $myjob['JobNo'], 'JobNm' => $myjob['JobNm']);
		endforeach;

		return $ArrayJob;

	}

	function list_termin($JobNo=''){

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
		}

		if ($this->input->post('JobNo')) {
			$JobNo = $this->input->post('JobNo');
			redirect('DipaTermin/PenerimaanTermin/list_termin/'.$JobNo,'refresh');
			die;
		}

		$JobNm = "";
		$Lokasi = "";
		if ($JobNo!='') {
			$queryJob = $this->db->query("SELECT JobNm, Lokasi FROM Job WHERE JobNo='$JobNo' ");
			if ($queryJob->num_rows()>0) {
				$rowJob = $queryJob->row();
				$JobNm = $rowJob->JobNm;
				$Lokasi = $rowJob->Lokasi;
			}
		}
		$data['JobNm'] = $JobNm;
		$data['Lokasi'] = $Lokasi;
		$data['JobNo'] = $JobNo;
		$data['ListJob'] = $this->GetJob_For_PenerimaanTermin();

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'PENERIMAAN TERMIN';
		$data['bodyclass'] = 'skin-blue-light';

		$tgl_saat_ini = date('Y-m-d');

		$data['tgl_pertama'] = date('Y-m-d', strtotime('-7 days'));
		$data['tgl_terakhir'] = $tgl_saat_ini;

		$data['list_termin_induk'] = $this->M_penerimaan_termin->dapat_list_termin_induk($JobNo);

		

		

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('dipa_termin/penerimaan_termin/view_penerimaan_termin', $data);
		$this->load->view('templates/footer');


	}

	function tampil_table_penerimaan_induk(){
		if (!$this->input->post()) {
			exit;
		}

		// print_rr($_POST);

	}

	function dapat_form_tambah_termin_induk()
	{
		if(!$this->input->post()){
			redirect('welcome','refresh');
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$dapat_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'  ")->row_array();
		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $dapat_job['JobNm'];

		$this->load->view('dipa_termin/penerimaan_termin/form_tambah_termin_induk',$data);

		// print_rr($_POST);

	}

	function aksi_tambah_termin_induk(){
		
		if(!$this->input->post()){
			redirect('welcome','refresh');
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}


		$JenisTermin_termin_induk = $this->input->post('JenisTermin_termin_induk',TRUE);
		$TglCair_termin_induk = $this->input->post('TglCair_termin_induk',TRUE);
		$NoBAP_termin_induk = $this->input->post('NoBAP_termin_induk',TRUE);
		$Uraian_termin_induk = $this->input->post('Uraian_termin_induk',TRUE);
		$BrutoBOQ_termin_induk = $this->input->post('BrutoBOQ_termin_induk',TRUE);

		$PotonganUangMuka_termin_induk = $this->input->post('PotonganUangMuka_termin_induk',TRUE);
		$PotonganRetensi_termin_induk = $this->input->post('PotonganRetensi_termin_induk',TRUE);

		$NettoBOQ_termin_induk = $this->input->post('NettoBOQ_termin_induk',TRUE);


		$PPN_termin_induk = $this->input->post('PPN_termin_induk',TRUE);
		$PPH_termin_induk = $this->input->post('PPH_termin_induk',TRUE);
		$PPN_nominal = $this->input->post('PPN_nominal',TRUE);
		$PPH_nominal = $this->input->post('PPH_nominal',TRUE);

		$NettoPenerimaan_termin_induk = $this->input->post('NettoPenerimaan_termin_induk',TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		// upload pdf 
		$nameFileDB = NULL;
		$path = './images/termin_induk/';
		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
		$file_ext = pathinfo($_FILES['file_termin_induk']['name'], PATHINFO_EXTENSION);
		$file_name =  'PDF_'.$JobNo.'_'.date('Y-m-d').'_'.time().'.'.$file_ext;
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'pdf'; 
		$config['max_size'] = 20048;
		$config['file_name'] = $file_name;
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file_termin_induk')) {
			$error = $this->upload->display_errors();
			// echo json_encode(['status' => 'error', 'message' => $error]);
			echo 'gagal upload file';
			// exit;
		} else {
			$data = $this->upload->data();
			$nameFileDB = $file_name;
			// echo json_encode(['status' => 'success', 'data' => $data]);
		}
		// end pload pff


		$data = array(
			'JobNo' => $JobNo,
			'Jenis' => $JenisTermin_termin_induk,
			'TglCair' => $TglCair_termin_induk,
			'NoBAP' => $NoBAP_termin_induk,
			'Uraian' => $Uraian_termin_induk,
			'TerminInduk' => $NettoPenerimaan_termin_induk,
			'BrutoBOQ' => $BrutoBOQ_termin_induk,
			'UM' => $PotonganUangMuka_termin_induk,
			'Retensi' => $PotonganRetensi_termin_induk,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'PDFFile' => $nameFileDB,
			'PPN_persen' => $PPN_termin_induk,
			'PPH_persen' => $PPH_termin_induk,
			'PPN_nominal' => $PPN_nominal,
			'PPH_nominal' => $PPH_nominal,

			'NettoBOQ' => $NettoBOQ_termin_induk,
		);

		// $this->db->insert('TerminInduk',$data);
		// print_rr($_POST);
		// echo "<p></p>";
		// echo "<hr>";
		// print_rr($data);
		// exit;

		$simpan = $this->M_penerimaan_termin->simpan_termin_induk_to_database($data);
		if ($simpan == TRUE) {
			is_pesan('success','berhasil menambahkan data');
			echo 'berhasil';
			exit;
		}
		is_pesan('error','gagal saat menambahkan data');
		echo "gagal";
		exit;

		// print_rr($_POST);
		// print_rr($data);
		// exit;

		// $tables = $this->db->list_fields("TerminInduk");

		// print_rr($tables);
	}

	function dapat_form_edit_termin_induk(){

		if(!$this->input->post()){
			redirect('welcome','refresh');
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo',TRUE);

		$data['dataTerminInduk'] = $this->M_penerimaan_termin->dapat_list_termin_induk($JobNo,$LedgerNo)->row_array();
		$data['JobNo'] = $JobNo;
		$data['dataJob'] = dapat_data_job($JobNo);

		// print_rr($data['dataTerminInduk']);
		// exit;
		$this->load->view('dipa_termin/penerimaan_termin/form_edit_termin_induk',$data);
	}

	function update_termin_induk(){

		if(!$this->input->post()){
			redirect('welcome','refresh');
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo',TRUE);

		$cek_approval = $this->db
		->where('LedgerNo',$LedgerNo)
		->where('UserApproval IS NOT NULL',NULL,FALSE)
		->get('TerminInduk');

		if ($cek_approval->num_rows() > 0) {
			is_pesan('warning','Data sudah di Approve, tidak dapat di ubah');
			exit;				
		}
		

		$JenisTermin_termin_induk = $this->input->post('JenisTermin_termin_induk',TRUE);
		$TglCair_termin_induk = $this->input->post('TglCair_termin_induk',TRUE);
		$NoBAP_termin_induk = $this->input->post('NoBAP_termin_induk',TRUE);
		$Uraian_termin_induk = $this->input->post('Uraian_termin_induk',TRUE);
		$BrutoBOQ_termin_induk = $this->input->post('BrutoBOQ_termin_induk',TRUE);
		$NettoBOQ_termin_induk = $this->input->post('NettoBOQ_termin_induk',TRUE);


		$PPN_termin_induk = $this->input->post('PPN_termin_induk',TRUE);
		$PPH_termin_induk = $this->input->post('PPH_termin_induk',TRUE);
		$PPN_nominal = $this->input->post('PPN_nominal',TRUE);
		$PPH_nominal = $this->input->post('PPH_nominal',TRUE);



		$PotonganUangMuka_termin_induk = $this->input->post('PotonganUangMuka_termin_induk',TRUE);
		$PotonganRetensi_termin_induk = $this->input->post('PotonganRetensi_termin_induk',TRUE);

		$NettoPenerimaan_termin_induk = $this->input->post('NettoPenerimaan_termin_induk',TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		// update pdf 
		$nameFileDB = NULL;
		$path = './images/termin_induk/';
		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
		$file_ext = pathinfo($_FILES['file_termin_induk']['name'], PATHINFO_EXTENSION);
		$file_name =  'PDF_'.$JobNo.'_'.date('Y-m-d').'_'.time().'.'.$file_ext;
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'pdf'; 
		$config['max_size'] = 20048;
		$config['file_name'] = $file_name;
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file_termin_induk')) {
			// $error = $this->upload->display_errors();
			// echo json_encode(['status' => 'error', 'message' => $error]);
			echo 'file tidak di ubah';
			// exit;
		} else {
			// cek file sudah ada atau belum
			$cek = $cek_approval->row_array();
			if($cek['PDFFile'] != NULL){
				$getFileName = $cek['PDFFile'];
				$pathOfFile = $path.$getFileName;
				if(file_exists($pathOfFile)){
					unlink($pathOfFile);
				}
			}
			$data = $this->upload->data();
			$nameFileDB = $file_name;
			// echo json_encode(['status' => 'success', 'data' => $data]);
		}
		// end update pdf

		$data = array(
			'Jenis' => $JenisTermin_termin_induk,
			'TglCair' => $TglCair_termin_induk,
			'NoBAP' => $NoBAP_termin_induk,
			'Uraian' => $Uraian_termin_induk,
			'TerminInduk' => $NettoPenerimaan_termin_induk,
			'BrutoBOQ' => $BrutoBOQ_termin_induk,
			'UM' => $PotonganUangMuka_termin_induk,
			'Retensi' => $PotonganRetensi_termin_induk,
			'NettoBOQ' => $NettoBOQ_termin_induk,
			'PDFFile' => $nameFileDB,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'PPN_persen' => $PPN_termin_induk,
			'PPH_persen' => $PPH_termin_induk,
			'PPN_nominal' => $PPN_nominal,
			'PPH_nominal' => $PPH_nominal,
		);

		// print_rr($_POST);
		// echo "<hr>";
		// print_rr($data);
		// exit;



		$update_aksi = $this->M_penerimaan_termin->update_termin_induk_to_database($data,$LedgerNo);
		if ($update_aksi == TRUE) {
			is_pesan('info','berhasil mengubah data');
			echo 'berhasil';
			exit;
		}
		is_pesan('error','gagal saat mengubah data');
		echo "gagal";
		exit;


	}


	function Pilihan_termin(){

		if(!$this->input->post()){
			redirect('welcome','refresh');
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
			
		}


		$PilihTermin = $this->input->post('PilihTermin',TRUE);

		if ($PilihTermin == 'TerminInduk') {
			$data['JobNo'] = $JobNo;
			$data['list_termin_induk'] = $this->M_penerimaan_termin->dapat_list_termin_induk($JobNo);
			$this->load->view('dipa_termin/penerimaan_termin/list_termin_induk', $data);
		}

		if ($PilihTermin == 'TerminMember') {
			$data['JobNo'] = $JobNo;
			$data['dapat_member_dari_job'] = $this->M_penerimaan_termin->dapat_member_dari_job($JobNo);
			$data['dataTerminMember'] = $this->M_penerimaan_termin->termin_member_list_data($JobNo)->result_array();

			$this->load->view('dipa_termin/penerimaan_termin/list_termin_member', $data);
		}


	}


	//AWAL TERMIN MEMBER

	function dapat_form_tambah_termin_member()
	{
		if(!$this->input->post()){
			redirect('welcome','refresh');
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$dapat_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'  ")->row_array();

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $dapat_job['JobNm'];
		$data['dapat_member_dari_job'] = $this->M_penerimaan_termin->dapat_member_dari_job($JobNo);

		$this->load->view('dipa_termin/penerimaan_termin/form_tambah_termin_member',$data);

	}

	function simpan_termin_member(){

		if(!$this->input->post()){
			redirect('welcome','refresh');
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		$JenisTermin_termin_member = $this->input->post('JenisTermin_termin_member',TRUE);

		$TglCair_termin_member = $this->input->post('TglCair_termin_member',TRUE);
		$TglSetor_termin_member = $this->input->post('TglSetor_termin_member',TRUE);

		$NoBAP_termin_member = $this->input->post('NoBAP_termin_member',TRUE);
		$Uraian_termin_member = $this->input->post('Uraian_termin_member',TRUE);

		$NettoShareMember1 = $this->input->post('NettoShareMember1',TRUE);
		$NettoShareMember2 = $this->input->post('NettoShareMember2',TRUE);


		$CadanganKSOMember1 = $this->input->post('CadanganKSOMember1',TRUE);
		$CadanganKSOMember2 = $this->input->post('CadanganKSOMember2',TRUE);


		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		// upload pdf 
		$nameFileDB = NULL;
		$path = './images/termin_member/';
		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
		$file_ext = pathinfo($_FILES['file_termin_member']['name'], PATHINFO_EXTENSION);
		$file_name =  'PDF_'.$JobNo.'_'.date('Y-m-d').'_'.time().'.'.$file_ext;
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'pdf'; 
		$config['max_size'] = 20048;
		$config['file_name'] = $file_name;
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file_termin_member')) {
			$error = $this->upload->display_errors();
			// echo json_encode(['status' => 'error', 'message' => $error]);
			echo 'gagal upload file';
			// exit;
		} else {
			$data = $this->upload->data();
			$nameFileDB = $file_name;
			// echo json_encode(['status' => 'success', 'data' => $data]);
		}
		// end pload pff

		$data = array(
			'JobNo' => $JobNo,
			'Jenis' => $JenisTermin_termin_member,
			'TglCair' => $TglCair_termin_member,
			'NoBAP' => $NoBAP_termin_member,
			'TglSetor' => $TglSetor_termin_member,
			'Uraian' => $Uraian_termin_member,
			'TerminMember1' => $NettoShareMember1,
			'TerminMember2' => $NettoShareMember2,
			'CadanganKSO' => $CadanganKSOMember1,
			'CadanganKSOMember1' => $CadanganKSOMember2,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'PDFFile' => $nameFileDB,
		);

		// print_rr($data);
		// echo "<br>";
		// print_rr($_POST);
		// exit;

		$simpan_data = $this->M_penerimaan_termin->simpan_termin_member($data);
		if ($simpan_data == TRUE) {
			is_pesan('success','berhasil menambahkan data');
			echo 'berhasil';
			exit;
		}
		is_pesan('error','gagal saat menambahkan data');
		echo "gagal";
		exit;
	}


	function form_edit_termin_member(){
		

		if(!$this->input->post()){
			redirect('welcome','refresh');
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}


		$LedgerNo = $this->input->post('LedgerNo',TRUE);

		$data['dataTerminMember'] = $this->M_penerimaan_termin->termin_member_list_data($JobNo,$LedgerNo)->row_array();
		$data['JobNo'] = $JobNo;
		$data['LedgerNo'] = $LedgerNo;
		// $data['dataJob'] = dapat_data_job($JobNo);
		$data['dapat_member_dari_job'] = $this->M_penerimaan_termin->dapat_member_dari_job($JobNo);

		// print_rr($data['dataTerminInduk']);
		// exit;
		$this->load->view('dipa_termin/penerimaan_termin/form_edit_termin_member',$data);
	}

	function update_termin_member(){
		
		if(!$this->input->post()){
			redirect('welcome','refresh');
		}
		
		$JobNo = $this->input->post('JobNo', TRUE);

		if($this->session->userdata('PecahToken')->Termin == 0){
			is_pesan('error','Akses tidak diterima');
			redirect('Job/sub_job/'.$JobNo,'refresh');
			exit;
		}

		// $cek_approval = $this->db
		// ->where('LedgerNo',$LedgerNo)
		// ->where('UserApproval IS NOT NULL',NULL,FALSE)
		// ->get('TerminInduk')->num_rows();

		// if ($cek_approval > 0) {
		// 	is_pesan('warning','Data sudah di Approve, tidak dapat di ubah');
		// 	exit;				
		// }
		
		$LedgerNo = $this->input->post('LedgerNo',TRUE);

		$cek_approval = $this->db
		->where('LedgerNo',$LedgerNo)
		->where('UserApproval IS NOT NULL',NULL,FALSE)
		->get('TerminMember');

		if ($cek_approval->num_rows() > 0) {
			is_pesan('warning','Data sudah di Approve, tidak dapat di ubah');
			exit;				
		}
		


		$JenisTermin_termin_member = $this->input->post('JenisTermin_termin_member',TRUE);

		$TglCair_termin_member = $this->input->post('TglCair_termin_member',TRUE);
		$TglSetor_termin_member = $this->input->post('TglSetor_termin_member',TRUE);

		$NoBAP_termin_member = $this->input->post('NoBAP_termin_member',TRUE);
		$Uraian_termin_member = $this->input->post('Uraian_termin_member',TRUE);

		$NettoShareMember1 = $this->input->post('NettoShareMember1',TRUE);
		$NettoShareMember2 = $this->input->post('NettoShareMember2',TRUE);


		$CadanganKSOMember1 = $this->input->post('CadanganKSOMember1',TRUE);
		$CadanganKSOMember2 = $this->input->post('CadanganKSOMember2',TRUE);


		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		// update pdf 
		$nameFileDB = NULL;
		$path = './images/termin_member/';
		if(!is_dir($path)){
			mkdir($path, 0777, true);
		}
		$file_ext = pathinfo($_FILES['file_termin_member']['name'], PATHINFO_EXTENSION);
		$file_name =  'PDF_'.$JobNo.'_'.date('Y-m-d').'_'.time().'.'.$file_ext;
		$config['upload_path'] = $path;
		$config['allowed_types'] = 'pdf'; 
		$config['max_size'] = 20048;
		$config['file_name'] = $file_name;
		$this->upload->initialize($config);
		if (!$this->upload->do_upload('file_termin_member')) {
			// $error = $this->upload->display_errors();
			// echo json_encode(['status' => 'error', 'message' => $error]);
			echo 'file tidak di ubah';
			// exit;
		} else {
			// cek file sudah ada atau belum
			$cek = $cek_approval->row_array();
			if($cek['PDFFile'] != NULL){
				$getFileName = $cek['PDFFile'];
				$pathOfFile = $path.$getFileName;
				if(file_exists($pathOfFile)){
					unlink($pathOfFile);
				}
			}
			$data = $this->upload->data();
			$nameFileDB = $file_name;
			// echo json_encode(['status' => 'success', 'data' => $data]);
		}
		// end update pdf

		$data = array(
			'Jenis' => $JenisTermin_termin_member,
			'TglCair' => $TglCair_termin_member,
			'NoBAP' => $NoBAP_termin_member,
			'TglSetor' => $TglSetor_termin_member,
			'Uraian' => $Uraian_termin_member,
			'TerminMember1' => $NettoShareMember1,
			'TerminMember2' => $NettoShareMember2,
			'CadanganKSO' => $CadanganKSOMember1,
			'CadanganKSOMember1' => $CadanganKSOMember2,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'PDFFile' => $nameFileDB
		);

		$where = array(
			'JobNo' => $JobNo,
			'LedgerNo' => $LedgerNo
		);

		// print_rr($data);
		// exit;



		$update_data = $this->M_penerimaan_termin->update_termin_member($data,$where);
		if ($update_data == TRUE) {
			is_pesan('success','berhasil mengubah data');
			echo 'berhasil';
			exit;
		}
		is_pesan('error','gagal saat mengubah data');
		echo "gagal";
		exit;
	}

	//AKHIR TERMIN MEMBER


	// == START EXPORT EXCEL 11 SEPTEMBER 2023 ==

	function ExportExcel_TerminInduk($JobNo=''){
		if ($JobNo=='') {
			echo 'job tidak ditemukan';
			die;
		}

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', "No");
		$sheet->setCellValue('B1', "Tgl Cair");
		$sheet->setCellValue('C1', "No.BAP");
		$sheet->setCellValue('D1', "Uraian");
		$sheet->setCellValue('E1', "Bruto BOQ (Rp)");
		$sheet->setCellValue('F1', "Potongan Uang Muka (Rp) ");
		$sheet->setCellValue('G1', "Potongan Retensi (Rp)");
		$sheet->setCellValue('H1', "PPN (%)");
		$sheet->setCellValue('I1', "PPN (Rp)");
		$sheet->setCellValue('J1', "PPH (%)");
		$sheet->setCellValue('K1', "PPH (Rp)");
		$sheet->setCellValue('L1', "Netto Penerimaan (Rp)");

		$no = 1;
		$numrow = 2;

		$list_termin_induk = $this->M_penerimaan_termin->dapat_list_termin_induk($JobNo);

		$sumBrutoBOQ=0;
		$sumUM=0;
		$sumRetensi=0;
		$sumPPN_RP=0;
		$sumPPH_RP=0;
		$sumNettoPenerimaan=0;

		foreach ($list_termin_induk->result_array() as $data_termin_induk) :

			$PPN_persen = ($data_termin_induk['PPN_persen'] != NULL) ? (float) $data_termin_induk['PPN_persen'] : 0;
			$PPH_persen = ($data_termin_induk['PPH_persen'] != NULL) ? (float) $data_termin_induk['PPH_persen'] : 0;

			$sumBrutoBOQ += (float) $data_termin_induk['BrutoBOQ'];
			$sumUM += (float) $data_termin_induk['UM'];
			$sumRetensi += (float) $data_termin_induk['Retensi'];
			$sumPPN_RP += (float) $data_termin_induk['PPN_nominal'];
			$sumPPH_RP += (float) $data_termin_induk['PPH_nominal'];
			$sumNettoPenerimaan += (float) $data_termin_induk['TerminInduk'];

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, tgl_baru_tahun_pendek($data_termin_induk['TglCair']));
			$sheet->setCellValue('C' . $numrow, $data_termin_induk['NoBAP']);
			$sheet->setCellValue('D' . $numrow, $data_termin_induk['Uraian'])	;
			$sheet->setCellValue('E' . $numrow, new_decimal($data_termin_induk['BrutoBOQ']));
			$sheet->setCellValue('F' . $numrow, new_decimal($data_termin_induk['UM']));
			$sheet->setCellValue('G' . $numrow, new_decimal($data_termin_induk['Retensi']));
			$sheet->setCellValue('H' . $numrow, $PPN_persen.'%');
			$sheet->setCellValue('I' . $numrow, new_decimal($data_termin_induk['PPN_nominal']));
			$sheet->setCellValue('J' . $numrow, $PPH_persen.'%');
			$sheet->setCellValue('K' . $numrow, new_decimal($data_termin_induk['PPH_nominal']));
			$sheet->setCellValue('L' . $numrow, new_decimal($data_termin_induk['TerminInduk']));

			$no++;
			$numrow++;
		endforeach;

		$new_gaya = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
		];

		$new_gaya1 = [
			'font' => ['bold' => true],
		];

		$sheet->setCellValue('A' . $numrow, "TOTAL");
		$sheet->mergeCells('A' . $numrow . ':D' . $numrow);
		$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya1);

		$sheet->setCellValue('E' . $numrow, number_format($sumBrutoBOQ));
		$sheet->getStyle('E' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('F' . $numrow, number_format($sumUM));
		$sheet->getStyle('F' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('G' . $numrow, number_format($sumRetensi));
		$sheet->getStyle('G' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('H' . $numrow, '');
		$sheet->getStyle('H' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('I' . $numrow, number_format($sumPPN_RP));
		$sheet->getStyle('I' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('J' . $numrow, '');
		$sheet->getStyle('J' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('K' . $numrow,  number_format($sumPPH_RP));
		$sheet->getStyle('K' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('L' . $numrow, number_format($sumNettoPenerimaan));
		$sheet->getStyle('L' . $numrow)->applyFromArray($new_gaya);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->setTitle("Termin Induk");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="TerminInduk.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');

	}

	function ExportExcel_TerminMember($JobNo=''){
		if ($JobNo=='') {
			echo 'job tidak ditemukan';
			die;
		}

		$dapat_member_dari_job = $this->M_penerimaan_termin->dapat_member_dari_job($JobNo);
		

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', "No");
		$sheet->setCellValue('B1', "Job");
		$sheet->setCellValue('C1', "Tgl Cair");
		$sheet->setCellValue('D1', "Tgl Setor");
		$sheet->setCellValue('E1', "No.BAP");
		$sheet->setCellValue('F1', "Uraian");
		$sheet->setCellValue('G1', "Netto Share Termin ".$dapat_member_dari_job['Member1']."  (Rp) (Member)");
		$sheet->setCellValue('H1', "Netto Share Termin ".$dapat_member_dari_job['Member2']." (Rp) (Member)");
		$sheet->setCellValue('I1', "Netto Cadangan KSO ".$dapat_member_dari_job['Member1']." (Rp)");
		$sheet->setCellValue('J1', "Netto Cadangan KSO ".$dapat_member_dari_job['Member2']." (Rp)");

		$no = 1;
		$numrow = 2;

		$sum_NettoShare_Leader=0;
		$sum_NettoShare_Member=0;
		$sum_NettoCadanganKSO_1=0;
		$sum_NettoCadanganKSO_2=0;

		$dataTerminMember = $this->M_penerimaan_termin->termin_member_list_data($JobNo)->result_array();
		foreach ($dataTerminMember as $dataMember) :

			$sum_NettoShare_Leader += (float) $dataMember['TerminMember1'];
			$sum_NettoShare_Member += (float) $dataMember['TerminMember2'];
			$sum_NettoCadanganKSO_1 += (float) $dataMember['CadanganKSO'];
			$sum_NettoCadanganKSO_2 += (float) $dataMember['CadanganKSOMember1'];

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $dataMember['JobNo'].' - '.$dapat_member_dari_job['JobNm']);
			$sheet->setCellValue('C' . $numrow, tgl_baru_tahun_pendek($dataMember['TglCair']));
			$sheet->setCellValue('D' . $numrow, tgl_baru_tahun_pendek($dataMember['TglSetor']));
			$sheet->setCellValue('E' . $numrow, $dataMember['NoBAP']);
			$sheet->setCellValue('F' . $numrow, $dataMember['Uraian']);
			$sheet->setCellValue('G' . $numrow, new_decimal($dataMember['TerminMember1']));
			$sheet->setCellValue('H' . $numrow, new_decimal($dataMember['TerminMember2']));
			$sheet->setCellValue('I' . $numrow, new_decimal($dataMember['CadanganKSO']));
			$sheet->setCellValue('J' . $numrow, new_decimal($dataMember['CadanganKSOMember1']));

			$no++;
			$numrow++;
		endforeach;

		$new_gaya = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
		];

		$new_gaya1 = [
			'font' => ['bold' => true],
		];

		$sheet->setCellValue('A' . $numrow, "TOTAL");
		$sheet->mergeCells('A' . $numrow . ':F' . $numrow);
		$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya1);

		$sheet->setCellValue('G' . $numrow, number_format($sum_NettoShare_Leader));
		$sheet->getStyle('G' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('H' . $numrow, number_format($sum_NettoShare_Member));
		$sheet->getStyle('H' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('I' . $numrow, number_format($sum_NettoCadanganKSO_1));
		$sheet->getStyle('I' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('J' . $numrow, number_format($sum_NettoCadanganKSO_2));
		$sheet->getStyle('J' . $numrow)->applyFromArray($new_gaya);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->setTitle("Termin Induk");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="TerminInduk.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');

	}

	// == END EXPORT EXCEL 11 SEPTEMBER 2023 ==



}

/* End of file PenerimaanTermin.php */
/* Location: ./application/controllers/DipaTermin/PenerimaanTermin.php */