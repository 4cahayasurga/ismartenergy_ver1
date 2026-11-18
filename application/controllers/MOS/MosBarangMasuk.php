<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MosBarangMasuk extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		Pembuatan_table_SjHdr_Cadangan();
		Pembuatan_table_SjDtl_Cadangan();
		Pembuatan_table_KoDtl_Cadangan();
		Pembuatan_tbl_FileMOS();
		membuatFolderMosFile();
		Pembuatan_Table_Pakai_MOS();
		Pembuatan_table_SjDtl_Cadangan_History();
		Pembuatan_table_SjHdr_Cadangan_HISTORY();



	}

	public function view($JobNo='',$flag='')
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'MOS (Material On Site)';
		$data['bodyclass'] = 'sidebar-collapse skin-red-light';

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
		$this->load->view('MOS_view/MosBarangMasuk_view/main_page', $data);
		$this->load->view('templates/footer');
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

	function tableMOSBarangMasuk(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo');


		$new_query = $this->db->query("
			SELECT A.*, 
			(SELECT Vendor.VendorNm FROM KoHdr INNER JOIN Vendor ON KoHdr.VendorId=Vendor.VendorId WHERE NoKO=a.NoKO) as 'VendorNm',
			ISNULL(NULL,(SELECT SUM(Vol) FROM KoDtl WHERE NoKO=a.NoKO GROUP BY NoKO )) AS 'Total_Volume_PO',
			ISNULL(NULL,(SELECT SUM(VolTerima) FROM SjDtl_Cadangan WHERE NoSJ=a.NoSJ )) AS 'Total_Volume_MOS'
			FROM SjHdr_Cadangan a WHERE JobNo='$JobNo'
			")->result();
		// print_rr($new_query);
		$data['mydata'] = $new_query; 		
		
		$data['JobNo'] = $JobNo;

		$this->load->view('MOS_view/MosBarangMasuk_view/tableMOSBarangMasuk',$data);

	}

	private function KodeOtomatis_MOS($JobNo){
		// return $JobNo;
		$Kode = 'MOS'.$JobNo;
		$query = $this->db->query("SELECT TOP 1 NoSJ FROM SjHdr_Cadangan WHERE JobNo='$JobNo' ORDER BY NoSJ DESC");
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

	function FrmEntryMOS($JobNo='',$NoSJ='',$NoKO='',$see=''){
		if ($JobNo =='') {
			redirect('MOS/MosBarangMasuk/view');
		}

		if ($see !='') {
			$data['see'] = 'see';
		}else{
			$data['see'] = '';
		}


		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$JobNm = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo' ")->row()->JobNm;

		$data['judul'] = 'ENTRY MOS (Material On Site)';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $JobNm;

		$data['NoSJ'] = $this->KodeOtomatis_MOS($JobNo);


		if ($NoKO == '') {
			$data['AdaKO'] = '';
		}else{
			$data['AdaKO'] = $NoKO;
		}

		$dataKO = $this->db->query("
			SELECT a.NoKO, a.TglKO, a.VendorId, a.NoSPR, 
			(SELECT VendorNm FROM Vendor  WHERE VendorId=a.VendorId) AS 'VendorNm',
			(SELECT COUNT(NoKO) FROM SjHdr_Cadangan WHERE NoKO=a.NoKO) AS 'JumlahKO'
			FROM KoHdr a
			
			WHERE a.JobNo='$JobNo' AND a.KategoriId='PO' AND 
			a.ApprovedBy IS NOT NULL AND a.ClosedBy IS NULL AND 
			a.CanceledBy IS NULL ORDER BY NoKO ASC" )->result();
		$data['dataKO'] = $dataKO;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('MOS_view/MosBarangMasuk_view/entryMOS', $data);
		$this->load->view('templates/footer');

	}

	function Tampilkan_Table_Detail(){
		if (!$this->input->post('NoKO')) {
			exit;
		}

		$NoKO = $this->input->post('NoKO');
		$JobNo = $this->input->post('JobNo');

		$CekKO = $this->db->query("SELECT * FROM SjHdr_Cadangan WHERE NoKO='$NoKO' AND JobNo='$JobNo'  ");
		$ArrayKO = array();


		$CekNoSJSebelumnya = 0;

		if ($CekKO->num_rows() > 0) {

			$QueryKO = $this->db->query("SELECT * FROM SjDtl_Cadangan WHERE NoKO='$NoKO' ")->result();
			foreach($QueryKO as $rowKO) :
				$ArrayKO[] = (object) array(
					'NoKO' => $rowKO->NoKO,
					'NoUrut' => $rowKO->NoUrut,
					'Alokasi' => $rowKO->Alokasi,
					'KdRAP' => $rowKO->KdRAP,
					'Uraian' => $rowKO->Uraian,
					'Uom' => $rowKO->Uom,
					'HrgSatuan' => $rowKO->HrgSatuan,
					'VolKO' => $rowKO->VolKO,
					'VolTerima' => $rowKO->VolTerima,
				);
			endforeach;

			$CekNoSJSebelumnya = 1;
			
		}else{
			$QueryKO = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ")->result();
			foreach($QueryKO as $rowKO) :
				$ArrayKO[] = (object) array(
					'NoKO' => $rowKO->NoKO,
					'NoUrut' => $rowKO->NoUrut,
					'Alokasi' => $rowKO->Alokasi,
					'KdRAP' => $rowKO->KdRAP,
					'Uraian' => $rowKO->Uraian,
					'Uom' => $rowKO->Uom,
					'HrgSatuan' => $rowKO->HrgSatuan,
					'VolKO' => $rowKO->Vol,
					'VolTerima' => 0,
				);
			endforeach;

		}

		if ($CekNoSJSebelumnya == 1) {
			$CekData = $this->db->query("SELECT TOP 1 NoSJ FROM SjHdr_Cadangan WHERE NoKO='$NoKO' AND JobNo='$JobNo' ORDER BY LedgerNo DESC  ")->row();

			$data['NoSJSebelumnya'] = $CekData->NoSJ;
		}else{
			$data['NoSJSebelumnya'] = '';
		}

		// print_rr($data);
		// exit;

		$data['ArrayKO'] = $ArrayKO;
		$data['NoKO'] = $NoKO;
		$data['JobNo'] = $JobNo;

		$this->load->view('MOS_view/MosBarangMasuk_view/table_detail_stok', $data);
	}

	function SaveMOS(){
		if (!$this->input->post()) {
			die;
		}

		if ($this->input->post('see')) {
			echo 'see';
			die;
		}

		// print_rr($_POST);
		// exit;

		$NoSJ = $this->input->post('NoSJ');
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

		$JobNo = $this->input->post('JobNo');

		$TglSJ = $this->input->post('TglTerima');
		$NoSJVendor = $this->input->post('NoSJVendor');
		$NoKO = $this->input->post('NoKO');
		$NoSPR = $this->input->post('NoSPR');

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$NoSJSebelumnya = ($this->input->post('NoSJSebelumnya')) ? $this->input->post('NoSJSebelumnya') : '';


		$dataSjHdr = array(
			'NoSJ' => $NoSJ,
			'TglSJ' => $TglSJ,
			'NoKO' => $NoKO,
			'JobNo' => $JobNo,
			'NoSJVendor' => $NoSJVendor,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
		);

		
		
		$this->db->trans_start();

		if ($NoSJSebelumnya =='') {
			$this->db->insert('SjHdr_Cadangan',$dataSjHdr);
		}

		$this->db->insert('SjHdr_Cadangan_History',$dataSjHdr);	

		// print_rr($_POST);
		// exit;

		if ($this->input->post('VolTerima')) {


			$NoUrut = $this->input->post('NoUrut');
			$VolTerima = ($this->input->post('VolTerima') == '' || $this->input->post('VolTerima') == 0) ? 0 : $this->input->post('VolTerima');
			$VolKO = ($this->input->post('VolKO') == '' || $this->input->post('VolKO') == 0) ? 0 : $this->input->post('VolKO');

			$VolYangSudahDiterima = ($this->input->post('VolYangSudahDiterima') == '' || $this->input->post('VolYangSudahDiterima') == 0) ? 0 : $this->input->post('VolYangSudahDiterima');
			
			$Alokasi = $this->input->post('Alokasi');
			$KdRAP = $this->input->post('KdRAP');
			$Uraian = $this->input->post('Uraian');
			$Uom = $this->input->post('Uom');
			$HrgSatuan = $this->input->post('HrgSatuan');

			foreach($VolTerima as $key => $value) {

				$VolTotal = str_replace(",", "", $VolYangSudahDiterima[$key]);
				$value = str_replace(",", "", $value);

				$MyVolTotal = $VolTotal + $value;

				$dataSjDtl  = array(
					'NoSJ' => $NoSJ,
					'NoUrut' => $NoUrut[$key],
					'Alokasi' => $Alokasi[$key],
					'KdRAP' => $KdRAP[$key],
					'Uraian' => $Uraian[$key],
					'Uom' => $Uom[$key],
					'HrgSatuan' => $HrgSatuan[$key],
					'JobNo' => $JobNo,
					'NoKO' => $NoKO,
					'VolKO' => $VolKO[$key],
					'VolTerima' => $value,

				);

				if ($NoSJSebelumnya == '') {
					$this->db->insert('SjDtl_Cadangan',$dataSjDtl);
				}

				if ($NoSJSebelumnya != '') {
					
					if ($value == 0) {
						continue;
					}
					$this->db->query("UPDATE SjDtl_Cadangan SET VolTerima='$MyVolTotal' WHERE NoUrut='$NoUrut[$key]' AND NoSJ='$NoSJ'  ");
				}

				$this->db->insert('SjDtl_Cadangan_History',$dataSjDtl);

			}
		}
		
		if ($NamaFile !='') {
			$this->db->query("INSERT INTO tbl_FileMOS (NoSJ, NamaFile, UserEntry, TimeEntry) VALUES ('$NoSJ', '$NamaFile', '$UserEntry', '$TimeEntry') ");
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

	function lakukan_approve(){
		if (!$this->input->post('NoSJ')) {
			echo 'gagal';
			die;
		}

		$NoSJ = $this->input->post('NoSJ');
		$ApprovedTxt = $this->input->post('ApprovedTxt');

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');


		$set = array(
			'ApprovedBy' => ($ApprovedTxt == 'Approve') ? $UserEntry : NULL,
			'TimeApproved' => ($ApprovedTxt == 'Approve') ? $TimeEntry : NULL,
		);
		$where = array('NoSJ' => $NoSJ);

		$this->db->trans_start();

		$this->db->set($set)->where($where)->update('SjHdr_Cadangan');

		$this->db->trans_complete();
		if ($this->db->trans_start() === FALSE) {
			echo 'gagal';
		}else{
			echo 'berhasil';
		}


	}

}

/* End of file MosBarangMasuk.php */
/* Location: ./application/controllers/MOS/MosBarangMasuk.php */