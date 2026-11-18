<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmEntryMOS extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		Pembuatan_tbl_FileMOS();

		tblBarangMasuk_Header_Pembuatan();
		tblBarangMasuk_Detail_Pembuatan();
		tblPemakaian_MOS_Pembuatan();
	}


	private function dapat_list_job($pengurutan='')
	{

		$CI = &get_instance();
		$user_id = $CI->session->userdata('MIS_LOGGED_ID');
		$Company = $CI->config->item('Company');

		$var_pengurutan = '';
		if($pengurutan !=''){
			$var_pengurutan = 'ORDER BY ax.JobNo DESC';
		}

		$query = $CI->db->query("

			SELECT ax.JobNo, bx.JobNm FROM
			(SELECT item as JobNo
				FROM
				dbo.SplitString ((SELECT top 1 a.AksesJob FROM (SELECT * FROM Login) as a
					left outer join
					(SELECT * FROM Job) as b
					on b.JobNo = a.AksesJob
					Where a.UserID= '$user_id'), ',')) as ax
			left outer join 
			Job as bx
			on bx.JobNo = ax.JobNo WHERE bx.Company='$Company' group by ax.JobNo, bx.JobNm ".$var_pengurutan."

			");

		return $query->result();
	}

	public function view($JobNo='',$flag='')
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'MOS (Material On Site)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = '';

		$JobNm = '';
		if ($JobNo !='') {
			$data['JobNm'] = $this->db->SELECT('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
		}

		$data['flag'] = $flag;

		$data['ListJob'] = $this->dapat_list_job();


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmEntryMOS_view/halaman_utama', $data);
		$this->load->view('templates/footer');
	}

	function TampilTableMOS(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$query = $this->db->query("SELECT * FROM tblBarangMasuk_Header WHERE JobNo='$JobNo' ORDER BY NoSJ DESC ")->result();
		$data['mydata'] = $query;

		$data['JobNo'] = $JobNo;
		$this->load->view('FrmEntryMOS_view/halaman_tbl_mos', $data);
	}

	private function KodeOtomatis_MOS($JobNo){

		$Kode = 'SJ'.$JobNo;
		$query = $this->db->query("SELECT TOP 1 NoSJ FROM tblBarangMasuk_Header WHERE JobNo='$JobNo' ORDER BY NoSJ DESC");
		$row = $query->row();
		if ($query->num_rows() > 0 ) {
			$Lima_HurufBelakang = substr($row->NoSJ,-5);
			$Lima_HurufBelakang = sprintf("%05s", $Lima_HurufBelakang + 1);

			$Kode = $Kode.$Lima_HurufBelakang;
		}else{
			$Kode = $Kode.'00001';
		}

		return $Kode;
	}

	function addNewMOS($JobNo='',$LedgerNo=''){
		if ($JobNo=='') {
			redirect('MOS/FrmEntryMOS/view','refresh');
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Form Tambah MOS (Material On Site)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = '';

		$JobNm = '';
		if ($JobNo !='') {
			$data['JobNm'] = $this->db->SELECT('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
		}

		$data['NoSJ'] = $this->KodeOtomatis_MOS($JobNo);

		$dataKO = $this->db->query("
			SELECT a.NoKO, a.TglKO, a.VendorId, a.NoSPR, 
			(SELECT VendorNm FROM Vendor  WHERE VendorId=a.VendorId) AS 'VendorNm',
			(SELECT COUNT(NoKO) FROM SjHdr_Cadangan WHERE NoKO=a.NoKO) AS 'JumlahKO'
			FROM KoHdr a
	
			WHERE a.JobNo='$JobNo' AND  
			a.ApprovedBy IS NOT NULL AND a.ClosedBy IS NULL AND 
			a.CanceledBy IS NULL ORDER BY NoKO ASC" )->result();
		$data['dataKO'] = $dataKO;

		$data['NoKOAda'] = '';
		if ($LedgerNo !='') {
			$query_panggil = $this->db->query("SELECT * FROM tblBarangMasuk_Header WHERE LedgerNo='$LedgerNo' ")->row();
			$data['NoKOAda'] = $query_panggil->NoKO;
		}

		$data['LedgerNo'] = $LedgerNo;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmEntryMOS_view/form_add_mos', $data);
		$this->load->view('templates/footer');
	}


	function tbl_order_list(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$JobNo = $this->input->post('JobNo',true);
		$NoKO = $this->input->post('NoKO',true);

		$CekKO = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ");

		$view = ($this->input->post('view')) ? $this->input->post('view') : '';

		$ArrayBuatan = array();
		if ($CekKO->num_rows() > 0) {

			foreach ($CekKO->result() as $row) :

				$VolSudahMasuk = 0;
				$CekVolYangSudahMasuk = $this->db->query("SELECT VolBrgMasuk FROM tblBarangMasuk_Detail WHERE NoUrut='$row->NoUrut' AND JobNo='$JobNo' AND NoKO='$row->NoKO'  ");
				if ($CekVolYangSudahMasuk->num_rows() > 0) {
					// $rowVolSudahMasuk = $CekVolYangSudahMasuk->row();
					foreach($CekVolYangSudahMasuk->result() as $mydata  ){
						$VolSudahMasuk += $mydata->VolBrgMasuk;
					}
					
				}else{
					$VolSudahMasuk = 0;
				}

				$ArrayBuatan[] = (object) array(
					'NoKO' => $row->NoKO,
					'NoUrut' => $row->NoUrut,
					'Alokasi' => $row->Alokasi,
					'KdRAP' => $row->KdRAP,
					'Uraian' => $row->Uraian,
					'Vol' => $row->Vol,
					'Uom' => $row->Uom,
					'HrgSatuan' => $row->HrgSatuan,
					'VolSudahMasuk' => $VolSudahMasuk,
					// 'Keterangan' => $row->Keterangan,
				);

			endforeach;

		}

		// print_rr($ArrayBuatan);
		// exit;

		$data['dataKO'] = $ArrayBuatan;

		if ($view !='') {
			$data['btn_disabled'] = 'disabled="disabled"';
		}else{
			$data['btn_disabled'] = '';
		}

		$this->load->view('FrmEntryMOS_view/tbl_order_list', $data);
	}

	function SimpanDataMOS(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$NoSJ = $this->input->post('NoSJ');
		$JobNo = $this->input->post('JobNo');
		$TglSJ = $this->input->post('TglSJ');
		$NoSJVendor = $this->input->post('NoSJVendor');
		$NoKO = $this->input->post('NoKO');
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$NamaFile = '';

		if (!empty($_FILES['BuktiSuratJalan']['name'])) {

			$this->load->library('upload');
			$config['upload_path']          =  'assets/MosFile';
			$config['allowed_types']        = '*';
			$config['max_size']             = 2048;
			$config['file_name'] = 'MOS-'.date('ymdHis');


			$this->upload->initialize($config);

			if ($this->upload->data('file_size') > 2048 ) {
				echo 'file_besar';
				exit;
			}

			if (!$this->upload->do_upload('BuktiSuratJalan')) {
				$NamaFile = 'gagal';

			} else {

				$NamaFile = $this->upload->data('file_name');

			}

		} 

		$DataHeader = array(
			'NoSJ' => $NoSJ,
			'TglSJ' => $TglSJ,
			'NoKO' => $NoKO,
			'JobNo' => $JobNo,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'NoSJVendor' => $NoSJVendor,
		);

		$this->db->trans_start();

		$this->db->insert('tblBarangMasuk_Header',$DataHeader);

		if ($this->input->post('VolMasuk')) {
			
			$NoUrut = $this->input->post('NoUrut');
			$VolMasuk = ($this->input->post('VolMasuk') == '' || $this->input->post('VolMasuk') == 0) ? 0 : $this->input->post('VolMasuk');
			$VolKO = ($this->input->post('VolKO') == '' || $this->input->post('VolKO') == 0) ? 0 : $this->input->post('VolKO');

			$Alokasi = $this->input->post('Alokasi');
			$KdRAP = $this->input->post('KdRAP');
			$Uraian = $this->input->post('Uraian');
			$Uom = $this->input->post('Uom');
			$HrgSatuan = $this->input->post('HrgSatuan');
			$Keterangan = $this->input->post('Keterangan');


			foreach($VolMasuk as $key => $value) :

				$VolBrgMasuk = str_replace(",", "", $value);

				if ($VolBrgMasuk == 0) {
					continue;
				}

				$dataSjDtl  = array(
					'NoSJ' => $NoSJ,
					'NoUrut' => $NoUrut[$key],
					'Alokasi' => $Alokasi[$key],
					'KdRAP' => $KdRAP[$key],
					'Uraian' => $Uraian[$key],
					'Uom' => $Uom[$key],
					'HrgSatuan' => $HrgSatuan[$key],
					'UserEntry' => $UserEntry,
					'TimeEntry' => $TimeEntry,
					'JobNo' => $JobNo,
					'VolKO' => $VolKO[$key],
					'VolBrgMasuk' => $VolBrgMasuk,
					'Keterangan' => $Keterangan[$key],
					'JumlahBarang' => $VolBrgMasuk,
					'NoKO' => $NoKO,

				);

				$this->db->insert('tblBarangMasuk_Detail',$dataSjDtl);


			endforeach;

		}

		if ($NamaFile !='') {
			$dataFile = array(
				'NoSJ' => $NoSJ,
				'NamaFile' => $NamaFile,
				'UserEntry' => $UserEntry,
				'TimeEntry' => $TimeEntry,
			);
			$this->db->insert('tbl_FileMOS',$dataFile);
		}

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			is_pesan('error','gagal menyimpan data');
			echo 'gagal';
		}else{
			echo 'berhasil';
			is_pesan('success','data berhasil disimpan');
		}
	}

	function AksiSimpanApprove(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$LedgerNo = $this->input->post('LedgerNo');
		$TxtApprove = $this->input->post('TxtApprove');
		$NoSJ = $this->input->post('NoSJ');
		$NoKO = $this->input->post('NoKO');

		$ApprovedBy = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeApproved = date('Y-m-d H:i:s');

		$this->db->trans_start();

		$data = array();
		if ($TxtApprove == 'Approve') {
			$data = array(
				'ApprovedBy' => $ApprovedBy,
				'TimeApproved' => $TimeApproved
			);
		}else{

			$CekNoSJ = $this->db->query("SELECT * FROM tblPemakaian_MOS WHERE NoKO='$NoKO' ")->num_rows();
			if ($CekNoSJ > 0) {
				echo 'sudah_ada';
				die;
			}

			$data = array(
				'ApprovedBy' => NULL,
				'TimeApproved' => NULL
			);
		}

		$this->db->set($data)->where('LedgerNo',$LedgerNo)->update('tblBarangMasuk_Header');
		$this->db->set($data)->where('NoSJ',$NoSJ)->update('tblBarangMasuk_Detail');

		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			echo 'gagal';
		}else{
			echo 'berhasil';
			// is_pesan('success','data berhasil diupdate');
		}
	}

	function sj_detail($JobNo='',$NoSJ=''){
		if ($JobNo=='' || $NoSJ =='') {
			redirect('MOS/FrmEntryMOS/view/','refresh');
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'Detail MOS (Material On Site)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = '';

		$JobNm = '';
		if ($JobNo !='') {
			$data['JobNm'] = $this->db->SELECT('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
		}

		$data['NoSJ'] = $NoSJ;

		$query = $this->db->query("SELECT * FROM tblBarangMasuk_Header WHERE NoSJ='$NoSJ' ")->row();
		$data['dataHeader'] = $query;

		$dataKO = $this->db->query("
			SELECT a.NoKO, a.TglKO, a.VendorId, a.NoSPR, 
			(SELECT VendorNm FROM Vendor  WHERE VendorId=a.VendorId) AS 'VendorNm',
			(SELECT COUNT(NoKO) FROM SjHdr_Cadangan WHERE NoKO=a.NoKO) AS 'JumlahKO'
			FROM KoHdr a

			WHERE a.JobNo='$JobNo' AND 
			a.ApprovedBy IS NOT NULL AND a.ClosedBy IS NULL AND 
			a.CanceledBy IS NULL ORDER BY NoKO ASC" )->result();
		$data['dataKO'] = $dataKO;

		$dataDetail = $this->db->query("SELECT * FROM tblBarangMasuk_Detail WHERE NoSJ='$NoSJ' ")->result();
		$data['dataDetail'] = $dataDetail;

		if ($query->ApprovedBy !='' || $query->ApprovedBy !=NULL  ) {
			$data['matikan_data'] = 'disabled="disabled"';
			$data['none'] = 'none';
		}else{
			$data['matikan_data'] = '';
			$data['none'] = '';
		}
		// print_rr($query);
		// exit;
		


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmEntryMOS_view/detail_sj', $data);
		$this->load->view('templates/footer');

	}

	function tbl_order_list_update(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$JobNo = $this->input->post('JobNo',true);
		$NoKO = $this->input->post('NoKO',true);
		$NoSJ = $this->input->post('NoSJ',true);

		$CekKO = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ");

		$view = ($this->input->post('view')) ? $this->input->post('view') : '';

		$ArrayBuatan = array();
		$getData_NoSJ = $this->db->query("SELECT * FROM tblBarangMasuk_Detail A WHERE A.NoSJ='$NoSJ' AND JobNo='$JobNo' ")->result_array();
		$Array_DataSJ = array();
		foreach ($getData_NoSJ as $row) {
			
			$NoUrut = $row['NoUrut'];


			$Query = $this->db->query("SELECT COALESCE(JumlahBarang,0) AS 'Total' FROM tblBarangMasuk_Detail WHERE NoUrut='$row[NoUrut]' AND NoKO='$row[NoKO]' AND JobNo='$row[JobNo]'   ")->row();

			$Array_DataSJ[] = array(
				'NoUrut' => showKodeAman($row['NoUrut']),
				'NoSJ' => showKodeAman($row['NoSJ']),
				'KdRAP' => showKodeAman($row['KdRAP']),
				'Uraian' => showKodeAman($row['Uraian']),
				'Uom' => $row['Uom'],
				'VolKO' => UbahDecimal_Nol_Tiga($row['VolKO']),
				'JumlahBarang' => showKodeAman($row['JumlahBarang']),
				'Total' => UbahDecimal_Nol_Tiga($Query->Total),
				'Keterangan' => showKodeAman($row['Keterangan']),
				'ApprovedBy' => showKodeAman($row['ApprovedBy']),
				'matikan_data' => ($row['ApprovedBy'] == NULL || $row['ApprovedBy'] == '') ? '' : 'disabled="disabled"', 
			);
		}
		$data['dataSJ'] = $Array_DataSJ;
		// print_rr($data);
		$this->load->view('FrmEntryMOS_view/tbl_order_list_update', $data);
	}

	function UpdateMOS(){
		if (!$this->input->post()) {
			exit;
		}
		// print_rr($_POST);
		// exit;
		$NoSJ = $this->input->post('NoSJ');
		$JobNo = $this->input->post('JobNo');
		$TglSJ = $this->input->post('TglSJ');
		$NoSJVendor = $this->input->post('NoSJVendor');
		$NoKO = $this->input->post('NoKO');
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$NamaFile = '';
		$flag_File = '';

		if (!empty($_FILES['BuktiSuratJalan']['name'])) {

			$this->load->library('upload');
			$config['upload_path']          =  'assets/MosFile';
			$config['allowed_types']        = '*';
			$config['max_size']             = 2048;
			$config['file_name'] = 'MOS-'.date('ymdHis');


			$this->upload->initialize($config);

			if ($this->upload->data('file_size') > 2048 ) {
				echo 'file_besar';
				exit;
			}

			if (!$this->upload->do_upload('BuktiSuratJalan')) {
				$NamaFile = 'gagal';

			} else {
				$flag_File = 'update';

				$NamaFile = $this->upload->data('file_name');

			}

		} 

		

		$this->db->trans_begin();

		// == UPDATE HEADER ==
		$this->db->query("UPDATE tblBarangMasuk_Header SET TglSJ='$TglSJ', NoSJVendor='$NoSJVendor', UserEntry='$UserEntry', TimeEntry='$TimeEntry' WHERE NoSJ='$NoSJ' AND JobNo='$JobNo' AND NoKO='$NoKO'  ");

		// ==UPDATE FILE MOS==
		if ($flag_File == 'update') {
			$cekFile = $this->db->query("SELECT * FROM tbl_FileMOS WHERE NoSJ='$NoSJ' ");
			$NamaFile_Lama = $cekFile->row()->NamaFile;
			if (file_exists('assets/MosFile/'.$NamaFile_Lama)) {
				unlink('assets/MosFile/'.$NamaFile_Lama);
			}

			$this->db->query("UPDATE tbl_FileMOS SET NamaFile='$NamaFile', UserEntry='$UserEntry', TimeEntry='$TimeEntry' WHERE NoSJ='$NoSJ' ");
		}


		// ==UPDATE JUMLAH BARANG==
		if ($this->input->post('JumlahBarang')) {
			
			$NoUrut = $this->input->post('NoUrut');
			$Keterangan = $this->input->post('Keterangan');
			$JumlahBarang = ($this->input->post('JumlahBarang') == '' || $this->input->post('JumlahBarang') == 0) ? 0 : $this->input->post('JumlahBarang');

			foreach($JumlahBarang as $key => $value) :

				$JumlahBarang = str_replace(",", "", $value);
				$NoUrut = $NoUrut[$key];
				$Keterangan = $Keterangan[$key];

				// if ($JumlahBarang == 0) {
				// 	continue;
				// }

				if ($JumlahBarang < 0) {
					continue;
				}

				$this->db->query("UPDATE tblBarangMasuk_Detail SET JumlahBarang='$JumlahBarang', VolBrgMasuk='$JumlahBarang', Keterangan='$Keterangan', UserEntry='$UserEntry', TimeEntry='$TimeEntry' WHERE NoSJ='$NoSJ' AND NoUrut='$NoUrut' AND JobNo='$JobNo' AND NoKO='$NoKO'   ");

			endforeach;

		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			is_pesan('error','gagal menyimpan data');
			echo 'gagal_update';
		}else{
			$this->db->trans_commit();
			is_pesan('success','data berhasil di-update');
			echo 'berhasil_update';
		}

	}



	




}

/* End of file FrmEntryMOS.php */
							/* Location: ./application/controllers/MOS/FrmEntryMOS.php */
