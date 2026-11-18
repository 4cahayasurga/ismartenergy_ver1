<?php
defined('BASEPATH') or exit('no direct script access allowed');

class Huba extends CI_Controller
{
	var $Company;

	function __construct()
	{
		parent::__construct();
		$this->load->model('Users');
		$this->Company = $this->config->item('Company');

		if (!$this->db->field_exists('PPN', 'Huba'))
		{
			$this->db->query("ALTER TABLE Huba ADD PPN char(1) NULL DEFAULT((0))");
			$this->db->query("ALTER TABLE Huba ADD PPN_Nominal money NULL DEFAULT((0))");
			$this->db->query("ALTER TABLE Huba ADD Total money NULL DEFAULT((0))");

		}
		
	}

	public function Listhuba($JobNo = null, $KdHuba = null, $AlokasiHeader = null)
	{

		// $num = 3.00;
		// if (fmod($num, 1) !== 0.0) {
		// 	echo '$num is a decimal number';
		// } else {
		// 	echo '$num is not a decimal number';
		// }
		// exit;

		$this->load->model('m_job');
		$datadipa = $this->m_job->dipa($JobNo);
		$data['dipa'] = $datadipa;


		$Huba = $this->db->query("SELECT * FROM Huba where JobNo='$JobNo' Order by KdHuba ASC");
		$data['datahuba'] = $Huba->result();

		$editHuba = $this->db->query("SELECT * FROM Huba WHERE JobNo='$JobNo' AND KdHuba='$KdHuba'");
		$data['DataHubaEdit'] = $editHuba->result();

		$dataPaket = $this->db->where('JobNo', $JobNo)->get('Job')->row_object();
		$data['datapaket'] = $dataPaket;

		$Header = $this->db->query("SELECT * FROM Huba where JobNo='$JobNo' and Tipe='Header' Order by NoUrut");
		$data['Header'] = $Header->result();


		$data['bodyclass'] = 'sidebar-collapse skin-blue';
		$data['judul']  = 'Harga Upah Barang dan Alat';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('content/Huba/ListHuba', $data);
		$this->load->view('templates/footer');


	}

	public function print($JobNo = '')
	{
		if ($JobNo != '') {
			$data['huba']  = $this->db->query("SELECT * FROM Huba where JobNo='$JobNo' Order by KdHuba ASC")->result();
			// $data['row']  = $this->db->select('JobNo,JobNm')->get_where('Job', array('JobNo' => $JobNo))->row();
			// $this->load->library('pdf');
			// $this->pdf->setPaper('A4', 'Potrait');
			// $this->pdf->filename = "Rpt-Pbb-" . date('Y-m-d H:i:s') . "-.pdf";
			// $this->pdf->load_view('pbb/RptPbb', $data);
		}
	}

	function dapatkan_TemplateExcel()
	{
		$this->load->helper('download');
		$file = 'bahan_import_excel/Template_Excel_HUBA.xlsx';
		force_download($file, NULL);
	}

	function edithuba($LedgerNo = null, $KdHuba = null)
	{
		$LedgerNo = $this->input->post('LedgerNoEd');
		$JobNo 	= $this->input->post('JobNoEd');
		$Tipe	= $this->input->post('typeed');
		$Header	= $this->input->post('Headered');
		$AlokasiHeader	= $this->input->post('KdAlokasiHeadered');
		$KdHuba	= $this->input->post('Kdhubaed');
		$Uraian	= $this->input->post('Uraianed');
		$Uom		= $this->input->post('Uomed');
		$Harga	= $this->input->post('hargaed');
		$Keterangan	= $this->input->post('Keteranganed');

		$data = array(
			// 'LedgerNo' =>$LedgerNo,
			'JobNo' 	=> $JobNo,
			'Tipe'	=> $Tipe,
			'Header'	=> $Header,
			'AlokasiHeader' => $AlokasiHeader,
			'KdHuba'	=> $KdHuba,
			'Uraian'	=> $Uraian,
			'Uom'	=> $Uom,
			'Harga'	=> $Harga,
			'Keterangan'	=> $Keterangan
		);

		$Where = array(
			'LedgerNo' => $LedgerNo
		);

		// print_rr($data, $Where);
		// exit;

		$this->db->update('Huba', $data, $Where);
		redirect('Huba/ListHuba/' . ($JobNo));
	}

	private function dapat_NoUrut($JobNo, $AlokasiHeader)
	{
		$query = $this->db->query("SELECT TOP 1 NoUrut From Huba WHERE JobNo='$JobNo' AND AlokasiHeader='$AlokasiHeader' ORDER BY NoUrut DESC")->row_array();
		//nomor urut tambah satu
		$tambah_satu = $query['NoUrut'] + 1;
		return $tambah_satu;
	}

	function hubasimpan()
	{
		$JobNo 		= $this->input->post('JobNo');
		$Tipe 	 	= $this->input->post('type');
		$Header 		= $this->input->post('Header');
		$AlokasiHeader = $this->input->post('KdAlokasiHeader');
		$KdHuba 		= $this->input->post('Kdhuba');
		$Uraian 		= $this->input->post('Uraian');
		$Uom  		= $this->input->post('Uom');
		$Harga 		= $this->input->post('harga');
		$Keterangan 	= $this->input->post('Keterangan');
		$NoUrut  		= $this->dapat_NoUrut($JobNo, $AlokasiHeader);
		$UserEntry     = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry     = date('Y-m-d H:i:s');

		$cbPPN = ($this->input->post('cbPPN')) ? 1 : 0;

		$GetPPN_Nominal_db 	= $this->input->post('GetPPN_Nominal_db');
		$GetTotal_db 	= $this->input->post('GetTotal_db');

		$searchForValue = ',';

		if( strpos($Harga, $searchForValue) !== false ) {
			$Harga = str_replace(",", "", $Harga);
		}

		if (is_numeric($Harga) !=1) {
			$Harga = 0;
		}
		


		$data = array(
			'JobNo'  		 => $JobNo,
			'Tipe'   		 => $Tipe,
			'Header' 		 => $Header,
			'AlokasiHeader' => $AlokasiHeader,
			'KdHuba' 		 => $KdHuba,
			'Uraian' 		 => $Uraian,
			'Uom'		 => $Uom,
			'Harga' 		 => $Harga,
			'Keterangan' 	 => $Keterangan,
			'NoUrut' 		 => $NoUrut,
			'UserEntry'	 => $UserEntry,
			'TimeEntry' 	 => $TimeEntry,

			'PPN' => $cbPPN,
			'PPN_Nominal' => $GetPPN_Nominal_db,
			'Total' => $GetTotal_db,
		);

		// print_rr($_POST);
		// exit;

		// print_rr($data);
		// exit;

		$this->db->insert('Huba', $data);
		redirect('Huba/ListHuba/' . ($JobNo));
	}






}
