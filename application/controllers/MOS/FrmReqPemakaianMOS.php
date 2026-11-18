<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmReqPemakaianMOS extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
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

	private function KodeOtomatis_MK($JobNo){
		$Kode = 'MK'.$JobNo;
		$query = $this->db->query("SELECT TOP 1 NoMK FROM tblPemakaian_MOS WHERE JobNo='$JobNo' ORDER BY LedgerNo DESC");
		$row = $query->row();
		if ($query->num_rows() > 0 ) {
			$Lima_HurufBelakang = substr($row->NoMK,-5);
			$Lima_HurufBelakang = sprintf("%05s", $Lima_HurufBelakang + 1);

			$Kode = $Kode.$Lima_HurufBelakang;
		}else{
			$Kode = $Kode.'00001';
		}

		return $Kode;
	}

	public function view($JobNo='',$flag='')
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$dataHeader['judul'] = 'Request Pemakaian MOS (Material On Site)';
		$dataHeader['bodyclass'] = 'sidebar-collapse skin-red-light';

		$data['judul'] = 'Add New Request Pemakaian MOS (Material On Site)';


		$data['JobNo'] = $JobNo;
		$data['JobNm'] = '';

		$JobNm = '';
		if ($JobNo !='') {
			$data['JobNm'] = $this->db->SELECT('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
		}

		$data['flag'] = $flag;

		$data['ListJob'] = $this->dapat_list_job();
		$data['NoMK'] = $this->KodeOtomatis_MK($JobNo);


		$this->load->view('templates/header', $dataHeader);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmReqPemakaianMOS_View/halaman_utama_req', $data);
		$this->load->view('templates/footer');
	}

	function get_ListKO(){
		if(!$this->input->post('JobNo')){
			echo 'error';
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$query = $this->db->query("SELECT * FROM tblBarangMasuk_Header WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL ")->result();
		
		$html ='';
		$html .='<option value="" >-- Pilih --</option>';
		foreach ($query as $value) {
			$html .='<option value="'.$value->NoKO.'">'.$value->NoKO.'</option>';
		}
		echo $html;
	}

	function table_request_pemakaian(){
		if (!$this->input->post('NoKO')) {
			echo 'error';
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$NoKO = $this->input->post('NoKO');
		$NoMK = $this->input->post('NoMK');

		$queryKO = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ")->result();
		$arrayKO = array();

		foreach($queryKO as $row):
			
			$VolMasuk = 0;
			$VolTerpakai = 0;
			$VolSisa = 0;

			$CekVolMasuk = $this->db->query("SELECT VolBrgMasuk FROM tblBarangMasuk_Detail WHERE NoKO='$row->NoKO' AND NoUrut='$row->NoUrut' AND ApprovedBy iS NOT NULL ");
			
			foreach($CekVolMasuk->result() as $rowVolMasuk){
				$VolMasuk += $rowVolMasuk->VolBrgMasuk;
			}

			$cekVolTerpakai = $this->db->query("SELECT JumlahDipakai FROM tblPemakaian_MOS WHERE NoKO='$row->NoKO' AND NoUrut='$row->NoUrut'  ");
			foreach($cekVolTerpakai->result() as $rowVolTerpakai){
				$VolTerpakai += $rowVolTerpakai->JumlahDipakai;
			}

			$VolSisa = $VolMasuk - $VolTerpakai;

			$arrayKO[] = (object) array(
				'NoKO' => $row->NoKO,
				'NoUrut' => $row->NoUrut,
				'Alokasi' => $row->Alokasi,
				'KdRAP' => $row->KdRAP,
				'Uraian' => $row->Uraian,
				'Uom' => $row->Uom,
				'HrgSatuan' => $row->HrgSatuan,	
				'VolKO' => $row->Vol,
				'VolMasuk' => $VolMasuk,
				'VolTerpakai' => $VolTerpakai,
				'VolSisa' => $VolSisa,
			);
		endforeach;
		

		$data['JobNo'] = $JobNo;
		$data['NoKO'] = $NoKO;
		$data['arrayKO'] = $arrayKO;
		$data['NoMK'] = $NoMK;

		// print_rr($data);

		$this->load->view('FrmReqPemakaianMOS_View/table_request_pemakaian', $data);
	}

	function SimpanDetailPemakaian(){
		if (!$this->input->post()) {
			echo 'error';
			die;
		}

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$MyArray = [];
		if ($this->input->post('Uraian')) {
			$LedgerNo = $this->input->post('Uraian');
			$VolBarangMasuk = $this->input->post('VolBarangMasuk');

			foreach ($LedgerNo as $key => $value) {
				$myquery = $this->db->query("SELECT * FROM tblBarangMasuk_Detail WHERE LedgerNo='$value' ")->row();
				$JumlahDiPakai = 0;
				$JumlahDiPakai = str_replace(",", "", $VolBarangMasuk[$key]);

				$NoSJ = $myquery->NoSJ;
				$NoUrut = $myquery->NoUrut;
				$Alokasi = $myquery->Alokasi;
				$KdRAP = $myquery->KdRAP;
				$Uraian = $myquery->Uraian;
				$Uom = $myquery->Uom;
				$HrgSatuan = $myquery->HrgSatuan;
				$JumlahBarang = $myquery->JumlahBarang;
				// $UserEntry = $myquery->UserEntry;
				// $TimeEntry = $myquery->TimeEntry;
				$JobNo = $myquery->JobNo;
				$VolKO = $myquery->VolKO;
				$VolBrgMasuk = $myquery->VolBrgMasuk;
				$Keterangan = $myquery->Keterangan;
				$ApprovedBy = $myquery->ApprovedBy;
				$TimeApproved = $myquery->TimeApproved;

				$MyArray[] = (object) array(
					'LedgerNo' => $value,
					'NoSJ' => $NoSJ,
					'NoUrut' => $NoUrut,
					'Alokasi' => $Alokasi,
					'KdRAP' => $KdRAP,
					'Uraian' => $Uraian,
					
					'Uom' => $Uom,
					'HrgSatuan' => $HrgSatuan,
					'JumlahBarang' => $JumlahBarang,
					'UserEntry' => $UserEntry,
					'TimeEntry' => $TimeEntry,
					'UserEntry' => $UserEntry,
					'JobNo' => $JobNo,
					'VolKO' => $VolKO,
					'VolBrgMasuk' => $VolBrgMasuk,
					'Keterangan' => $Keterangan,
					'ApprovedBy' => $ApprovedBy,
					'TimeApproved' => $TimeApproved,
					'JumlahDiPakai' => $JumlahDiPakai

				);
			}

		}

		$this->db->trans_start();

		$this->db->insert_batch('tblPemakaian_MOS',$MyArray);
		
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			echo 'gagal';
		}else{
			echo 'berhasil';
			is_pesan('success','berhasil disimpan');
		}
		

		// print_rr($MyArray);
	}

	function AksiUpdatePemakaian_perkolom(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		// print_rr($_POST);
		// exit;


		$JobNo = $this->input->post('JobNo');
		$NoMK = $this->input->post('NoMK');
		$NoKO = $this->input->post('NoKO');
		$NoUrut = $this->input->post('NoUrut');
		$Alokasi = $this->input->post('Alokasi');
		$KdRAP = $this->input->post('KdRAP');
		$Uraian = $this->input->post('Uraian');
		$Uom = $this->input->post('Uom');
		$HrgSatuan = $this->input->post('HrgSatuan');
		$VolKO = $this->input->post('VolKO');

		$VolInput = $this->input->post('VolInput');

		$JumlahDipakai = str_replace(",", "", $VolInput);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$data = array(
			'NoMK' => $NoMK,
			'NoKO' => $NoKO,
			'NoUrut' => $NoUrut,
			'Alokasi' => $Alokasi,
			'KdRAP' => $KdRAP,
			'Uraian' => $Uraian,
			'Uom' => $Uom,
			'HrgSatuan' => $HrgSatuan,
			'JobNo' => $JobNo,
			'VolKO' => $VolKO,
			'JumlahDipakai' => $JumlahDipakai,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,

		);

		// print_rr($data);
		// exit;

		$this->db->insert('tblPemakaian_MOS',$data);
		if ($this->db->affected_rows() > 0) {
			echo 'berhasil';
		}else{
			echo 'gagal';
		}

		// print_rr($_POST);

	}

	function UpdateSemua(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}
		
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');
		$NoMK = $this->input->post('NoMK');
		$JobNo = $this->input->post('JobNo');
		$NoKO = $this->input->post('NoKO');

		$ArraySaya = array();
		if ($this->input->post('JumlahDipakai')) {

			$NoUrut = $this->input->post('NoUrutText');
			$Alokasi = $this->input->post('AlokasiText');
			$VolInput = $this->input->post('JumlahDipakai');
			$KdRAP = $this->input->post('KdRAPText');
			$Uraian = $this->input->post('UraianText');
			$Uom = $this->input->post('UomText');
			$HrgSatuan = $this->input->post('HrgSatuanText');
			$VolKO = $this->input->post('VolKOText');

			foreach($VolInput as $key => $value) :

				$JumlahDipakai = str_replace(",", "", $value);

				if ($JumlahDipakai == 0) {
					continue;
				}

				$ArraySaya[] = array(
					'NoMK' => $NoMK,
					'NoUrut' => $NoUrut[$key],
					'Alokasi' => $Alokasi[$key],
					'KdRAP' => $KdRAP[$key],
					'Uraian' => $Uraian[$key],
					'Uom' => $Uom[$key],
					'HrgSatuan' => $HrgSatuan[$key],
					'JobNo' => $JobNo,
					'NoKO' => $NoKO,
					'VolKO' => $VolKO[$key],
					'JumlahDipakai' => $JumlahDipakai,
					'UserEntry' => $UserEntry,
					'TimeEntry' => $TimeEntry,


				);
			endforeach;

			$this->db->trans_start();

			$this->db->insert_batch('tblPemakaian_MOS',$ArraySaya);

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				echo 'gagal';
			}else{
				echo 'berhasil';
			}

			// print_rr($VolInput);
			// foreach($VolInput as $key => $value) :
			// 	$ArraySaya = array(
			// 		'NoMK' => $NoMK[$key],
			// 		'NoUrut' => $NoUrut[$key],
			// 		'NoKO' => $NoKO[$key],
			// 		'JobNo' => $JobNo[$key],
			// 	);
			// 	 $this->db->insert('tblPemakaian_MOS',$ArraySaya);
			// endforeach;
			// print_rr($ArraySaya);
			// $this->db->insert_batch('tblPemakaian_MOS',$ArraySaya);
		}

	}

}

/* End of file FrmReqPemakaianMOS.php */
/* Location: ./application/controllers/MOS/FrmReqPemakaianMOS.php */