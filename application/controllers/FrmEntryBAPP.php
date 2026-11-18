<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmEntryBAPP extends CI_Controller {

	var $Company;
	var $UserID;
	var $UserName;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->UserID = $this->session->userdata('MIS_LOGGED_ID');
		$this->UserName= $this->session->userdata('MIS_LOGGED_NAME');


		if (!$this->db->table_exists('BAPP_tbl')){
			$this->db->query("CREATE TABLE [dbo].[BAPP_tbl](
				[JobNo] [nvarchar](10) NOT NULL,
				[KdRAP] [nvarchar](15) NOT NULL,
				[Versi] [nvarchar](10) NULL,
				[NoUrut] [int] DEFAULT ((0)) NULL,
				[Uraian] [nvarchar](200) NULL,
				[Alokasi] [nvarchar](1) NOT NULL,
				[Tipe] [nvarchar](100) NULL,
				[Header] [nvarchar](100) DEFAULT ((0)) NULL,
				[Uom] [nvarchar](15) NULL,
				[Vol] [decimal](12, 3) DEFAULT ((0)) NULL,
				[HrgSatuan] [money] DEFAULT ((0)) NULL,
				[HrgRAB] [money] DEFAULT ((0)) NULL,
				[TotalTerserap] [money] DEFAULT ((0)) NULL,
				[UserEntry] [nvarchar](30) NULL,
				[TimeEntry] [datetime] NULL,
				[Tahun] [nvarchar](4) NULL,
				[Jenis] [nvarchar](15) NULL,
				[LedgerNo] [bigint] IDENTITY(1,1) NOT NULL,
				[PPN] [money] NULL,
				[VolumeInputan] [decimal](12, 3) NULL,
				[Jumlah] [money] NULL,
				CONSTRAINT [PK_BAPP_tbl] PRIMARY KEY CLUSTERED 
				(
					[LedgerNo] ASC
				)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
			) ON [PRIMARY]") ;

			redirect('FrmEntryBAPP');
		}


		

	}

	public function index($JobNo='',$Alokasi='')
	{
		$data['judul'] = 'Form Entry BAPP';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['ListJob'] = $this->GetJob();
		$data['JobNo'] = $JobNo;

		$data['ListAlokasi'] = $this->GetAlokasi();
		$data['Alokasi'] = $Alokasi;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmEntryBAPP_View/halaman_utama_entry_BAPP', $data);
		$this->load->view('templates/footer');
	}
	function TampilkanTable_RAP(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$JobNm = $this->input->post('JobNm',TRUE);

		$Alokasi = $this->input->post('Alokasi',TRUE);
		$KeteranganAlokasi = $this->input->post('Keterangan',TRUE);

		$query = $this->db->query("
			SELECT A.*, (A.Vol * A.HrgRAB) as 'TotalHargaKontrak',
			ISNULL((SELECT TOP 1 VolumeInputan FROM BAPP_tbl WHERE JobNo=A.JobNo AND Alokasi=A.Alokasi AND KdRAP=A.KdRAP ORDER BY LedgerNo DESC),0) AS 'VolSekarang',
			ISNULL((SELECT VolumeInputan FROM BAPP_tbl  WHERE JobNo=A.JobNo AND Alokasi=A.Alokasi AND KdRAP=A.KdRAP ORDER BY LedgerNo DESC OFFSET 1 ROWS  FETCH NEXT 1 ROWS ONLY),0) AS 'VolLalu'
			FROM RAP A WHERE A.JobNo='$JobNo' AND A.Alokasi='$Alokasi' ORDER BY A.NoUrut ASC  ");

		// print_rr($query->result());
		// exit;
		$sum_Volume = 0;

		


		$ArrayBuatan = array();

		foreach ($query->result_array() as $row) {

			$VolLalu = 0;
			$VolLalu = ($row['VolLalu'] == NULL || $row['VolLalu'] == '' || $row['VolLalu'] == 0) ? 0 : $row['VolLalu'];

			$HrgRAB = 0;
			$HrgRAB = ($row['HrgRAB'] == NULL || $row['HrgRAB'] == '' || $row['HrgRAB'] == 0) ? 0 : $row['HrgRAB'];

			$JumlahLalu = 0;
			$JumlahLalu = ($VolLalu * $HrgRAB);

			$VolSekarang = 0;
			$VolSekarang = ($row['VolSekarang'] == NULL || $row['VolSekarang'] == '' || $row['VolSekarang'] == 0) ? 0 : $row['VolSekarang'];

			$JumlahSekarang = 0;
			$JumlahSekarang = ($VolSekarang * $HrgRAB);

			$VolSampaiDenganSekarang = 0;
			$JumlahSampaiDenganSekarang = 0;
			$VolSampaiDenganSekarang = ($VolLalu + $VolSekarang); 
			$JumlahSampaiDenganSekarang = ($JumlahLalu + $JumlahSekarang); 

			$sum_Volume += $row['Vol'];
			

			$ArrayBuatan[] = array(
				'NoUrut' => $row['NoUrut'],
				'JobNo' => $row['JobNo'],
				'KdRAP' => $row['KdRAP'],
				'Versi' => $row['Versi'],
				'NoUrut' => $row['NoUrut'],
				'Uraian' => $row['Uraian'],
				'Alokasi' => $row['Alokasi'],
				'Tipe' => $row['Tipe'],
				'Header' => $row['Header'],
				'Uom' => $row['Uom'],
				'Vol' => $row['Vol'],
				'HrgSatuan' => $row['HrgSatuan'],
				'HrgRAB' => $row['HrgRAB'],
				'TotalHargaKontrak' => $row['TotalHargaKontrak'],
				'VolLalu' => $VolLalu,
				'VolSekarang' => $VolSekarang,

				'JumlahLalu' => $JumlahLalu,
				'JumlahSekarang' => $JumlahSekarang,

				'VolSampaiDenganSekarang' => $VolSampaiDenganSekarang,
				'JumlahSampaiDenganSekarang' => $JumlahSampaiDenganSekarang,


			);

			// $sum_Volume = 0;
		}
		// print_rr($ArrayBuatan);
		// exit;


		$data['dataRAP'] = $ArrayBuatan;
		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $JobNm;
		$data['Alokasi'] = $Alokasi;
		$data['KeteranganAlokasi'] = $KeteranganAlokasi;
		$data['sum_Volume'] = $sum_Volume;

		// $data['ListRAP'] = $query->result_array();

		// print_rr($data);
		// exit;
		$this->load->view('FrmEntryBAPP_View/tableRAP', $data);

	}

	function HitungTotalHarga(){
		if (!$this->input->post()) {
			exit;
		}

		$HrgRAB = $this->input->post('HrgRAB',TRUE);
		$VolumeInputan = $this->input->post('VolumeInputan',TRUE);
		$Hitung = 0;

		$searchForValue = ',';

		if( strpos($HrgRAB, $searchForValue) !== false ) {
			$HrgRAB = str_replace(",", "", $HrgRAB);
		}

		if( strpos($VolumeInputan, $searchForValue) !== false ) {
			$VolumeInputan = str_replace(",", "", $VolumeInputan);
		}

		$Hitung = $HrgRAB * $VolumeInputan;
		echo UbahDecimal_Nol_Dua($Hitung);
	}

	function SimpanBAPP(){
		if (!$this->input->post()) {
			exit;
		}

		// print_rr($_POST);

		$KdRAP = $this->input->post('KdRAP',TRUE);
		$Uraian = $this->input->post('Uraian',TRUE);
		$Uom = $this->input->post('Uom',TRUE);
		$Vol = $this->input->post('Vol',TRUE);
		$VolumeInputan = $this->input->post('VolumeInputan',TRUE);
		$TotalHarga = $this->input->post('TotalHarga',TRUE);
		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$Header = $this->input->post('Header',TRUE);
		$Tipe = $this->input->post('Tipe',TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$searchForValue = ',';

		if( strpos($Vol, $searchForValue) !== false ) {
			$Vol = str_replace(",", "", $Vol);
		}

		if( strpos($VolumeInputan, $searchForValue) !== false ) {
			$VolumeInputan = str_replace(",", "", $VolumeInputan);
		}

		if( strpos($TotalHarga, $searchForValue) !== false ) {
			$TotalHarga = str_replace(",", "", $TotalHarga);
		}

		$data = array(
			'JobNo' => $JobNo,
			'KdRAP' => $KdRAP,
			'Alokasi' => $Alokasi,
			'Uraian' => $Uraian,
			'Uom' => $Uom,
			'Vol' => $Vol,
			'VolumeInputan' => $VolumeInputan,
			'Jumlah' => $TotalHarga,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'Header' => $Header,
			'Tipe' => $Tipe,
		);

		$this->db->trans_begin();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
		}else{
			$this->db->trans_commit();
			echo 'berhasil';
			is_pesan('success','Saved!');
		}

		$this->db->insert('BAPP_tbl',$data);

	}


	private function GetAlokasi(){
		$AksesAlokasi = $this->session->userdata('PecahToken')->AksesAlokasi;
		// $AksesAlokasi = $this->db->query("SELECT AksesAlokasi FROM Login WHERE UserID='liasw'")->row()->AksesAlokasi;
		$queryAlokasi = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi WHERE Alokasi<>'A' ");
		$ArrayAlokasi = array();
		foreach($queryAlokasi->result_array() as $row ):

			if (!preg_match('/\b' . $row['Alokasi'] . '\b/', $AksesAlokasi)) {
				continue;
			}

			$ArrayAlokasi[] = array('Alokasi' => $row['Alokasi'], 'Keterangan' => $row['Keterangan']);
		endforeach;

		return $ArrayAlokasi;
	}

	private function GetJob(){

		$AksesJob = $this->session->userdata('PecahToken')->AksesJob;
		$QueryJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$this->Company' ");
		$ArrayJob = array();
		foreach($QueryJob->result_array() as $myjob ):

			if (!preg_match('/\b' . $myjob['JobNo'] . '\b/', $AksesJob)) {
				continue;
			}
			$ArrayJob[] = array('JobNo' => $myjob['JobNo'], 'JobNm' => $myjob['JobNm']);
		endforeach;

		return $ArrayJob;
	}





}

/* End of file FrmEntryBAPP.php */
/* Location: ./application/controllers/FrmEntryBAPP.php */