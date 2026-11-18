<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rap extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Rap_model');
	}

	public function index()
	{
	}

	function getTable()
	{
		if ($this->input->post()) {
			$JobNo = $this->input->post('JobNo');
			$alokasi = $this->input->post('alokasi');
			$Versi_rap = $this->input->post('Versi_rap');
			$data['alokasi'] = $alokasi;
			$data['Versi_rap'] = $Versi_rap;
			$data['JobNo'] = $JobNo;
			$this->load->view('rap/table-rap', $data);
		}
	}

    public function getTableRap()
    {
        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            $alokasi_rap = $this->input->post('alokasi_rap');
            $versi_rap = $this->input->post('versi_rap');
            $Par = '2';

            $data['alokasi_rap'] = $alokasi_rap;
            $data['versi_rap'] = $versi_rap;
            $data['JobNo'] = $JobNo;

			$data['result'] = $this->db->query("RAPSumda_Load N'{$JobNo}', N'{$alokasi_rap}', N'{$versi_rap}', N'{$Par}'")->result();

            $this->load->view('rap/table-rap', $data);
        }
    }

	function getForm()
	{
		if ($this->input->post()) {
			$JobNo = $this->input->post('JobNo');
			$alokasi = $this->input->post('alokasi');
			$Versi_rap = $this->input->post('Versi_rap');

			$data['header'] = $this->Rap_model->getHeader($JobNo, $alokasi, $Versi_rap)->result();
			$data['Alokasi'] = $alokasi;
			$data['Versi'] = $Versi_rap;
			$data['JobNo'] = $JobNo;
			// print_r($data);
			// exit;
			$this->load->view('rap/form-tambah-rap', $data);
		}
	}

	function simpanRAP()
	{
		if ($this->input->post()) {
			$JobNo = $this->input->post('JobNo');
			$Header = $this->input->post('Header');
			$Alokasi = $this->input->post('Alokasi');
			$Versi = $this->input->post('Versi');
			$Tipe = $this->input->post('Tipe');
			$KdRAP = $this->input->post('KdRAP');
			$Uraian = $this->input->post('Uraian');
			$Vol = $this->input->post('Vol');
			$Uom = $this->input->post('Uom');
			$HrgRAB = ($this->input->post('HrgRAB')) ?  $this->input->post('HrgRAB') : NULL;

			$TimeEntry = date('Y-m-d H:i:s');
			$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
			// $HrgSatuan = $this->input->post('HrgSatuan');

			$data = array(
				'JobNo' => $JobNo,
				'KdRAP' => $KdRAP,
				'Versi' => $Versi,
				'Uraian' => $Uraian,
				'Alokasi' => $Alokasi,
				'Tipe' => $Tipe,
				'Header' => $Header,
				'Uom' => $Uom,
				'Vol' => $Vol,
				'HrgRAB' => HapusFormatUang($HrgRAB),
				'UserEntry' => $UserEntry,
				'TimeEntry' => $TimeEntry,
				// 'HrgSatuan' => HapusFormatUang($HrgSatuan),
				// 'NoUrut' => $NoUrut,
			);
			// print_rr($data);
			// exit;
			$simpan = $this->Rap_model->simpanRAP($data);
			if ($simpan === TRUE) {
				// print_rr($data);
				echo 'success';
				exit;
			}
			echo 'gagal';
		}
	}

	function simpanRAP_old()
	{
		if ($this->input->post()) {
			$JobNo = $this->input->post('JobNo');
			$Header = $this->input->post('Header');
			$Alokasi = $this->input->post('Alokasi');
			$Versi = $this->input->post('Versi');
			$Tipe = $this->input->post('Tipe');
			$KdRAP = $this->input->post('KdRAP');
			$Uraian = $this->input->post('Uraian');
			$Vol = $this->input->post('Vol');
			$Uom = $this->input->post('Uom');
			$HrgSatuan = $this->input->post('HrgSatuan');
			$HrgRAB = $this->input->post('HrgRAB');

			$TimeEntry = date('Y-m-d H:i:s');
			$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');

			$data = array(
				'JobNo' => $JobNo,
				'KdRAP' => $KdRAP,
				'Versi' => $Versi,
				// 'NoUrut' => $NoUrut,
				'Uraian' => $Uraian,
				'Alokasi' => $Alokasi,
				'Tipe' => $Tipe,
				'Header' => $Header,
				'Uom' => $Uom,
				'Vol' => $Vol,
				'HrgSatuan' => HapusFormatUang($HrgSatuan),
				'HrgRAB' => HapusFormatUang($HrgRAB),
				'UserEntry' => $UserEntry,
				'TimeEntry' => $TimeEntry,
			);
			if ($this->input->post('Detail')) {
				$data['Header'] = $this->input->post('Detail');
			}
			if ($this->input->post('DetailSumda')) {
				$data['Header'] = $this->input->post('DetailSumda');
			}
			// print_rr($data);
			// echo 'success';
			// exit;

			$simpan = $this->Rap_model->simpanRAP($data);
			if ($simpan === TRUE) {
				// print_rr($data);
				echo 'success';
				exit;
			}
			echo 'gagal';
		}
	}

	function getDetail()
	{
		if ($this->input->post()) {
			$JobNo = $this->input->post('JobNo');
			$Alokasi = $this->input->post('Alokasi');
			$Versi = $this->input->post('Versi');
			$Tipe = 'Detail';
			$Header = $this->input->post('Header');

			$data = array(

				'JobNo' => $JobNo,
				'Alokasi' => $Alokasi,
				'Tipe' => $Tipe,
				'Header' => $Header

			);
			$data['Tipe'] = $Tipe;
			$data['Alokasi'] = $Alokasi;
			$data['Versi'] = $Versi;
			$data['mydata'] = $this->db->get_where('RAP', $data)->result();
			$this->load->view('Rap/form_sumda', $data);
		}
	}

	function getDetailSumda()
	{
		if ($this->input->post()) {
			$JobNo = $this->input->post('JobNo');
			$Alokasi = $this->input->post('Alokasi');
			$Versi = $this->input->post('Versi');
			$Tipe = 'Sumda';
			$Header = $this->input->post('Header');

			$data = array(

				'JobNo' => $JobNo,
				'Alokasi' => $Alokasi,
				'Tipe' => $Tipe,
				'Header' => $Header

			);
			// $data['Tipe'] = $Tipe;
			// $data['Alokasi'] = $Alokasi;
			// $data['Versi'] = $Versi;
			$data['mydatamy'] = $this->db->get_where('RAP', $data)->result();

			$this->load->view('Rap/form_detail_sumda', $data);
		}
	}

	function getFormRap()
	{
		if ($this->input->post()) {
			$JobNo = $this->input->post('JobNo');
			$alokasi = $this->input->post('alokasi');
			$Versi_rap = $this->input->post('Versi_rap');

			$data['header'] = $this->Rap_model->getHeader($JobNo, $alokasi, $Versi_rap)->result();
			$data['Alokasi'] = $alokasi;
			$data['Versi'] = $Versi_rap;
			$data['JobNo'] = $JobNo;
			// print_r($data);
			// exit;
			$this->load->view('rap/form-tambah-rap', $data);
		}
	}
}

/* End of file Rap.php */
/* Location: ./application/controllers/Rap.php */
