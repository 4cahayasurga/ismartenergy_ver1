
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class QueryPd extends CI_Controller
{

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('M_QueryPd');
	}


	public function index()
	{

		$Tbl1 = $this->M_QueryPd->GetTbl1();
		$data['query'] = $Tbl1;

		$UserId = $this->session->userdata('MIS_LOGGED_ID');
		// $json = json_decode($UserId, true);
		// $DataSesiUser = $json['UserID'];
		$GetAksesJob = $this->db->query("SELECT AksesJob FROM Login WHERE UserID='$UserId' ")->row();
		$explode = explode(",", $GetAksesJob->AksesJob);
		$implode = "'" . implode("','",
			$explode
		) . "'";

		$data['Job'] = $this->db->query("SELECT * From Job Where TipeJob='Project' and Company='$this->Company' and StatusJob IN ('Pelaksanaan','Pemeliharaan') and JobNo IN($implode) Order by JobNo DESC")->result();

		$GetAlokasi = $this->M_QueryPd->GetAlokasi();
		$data['AksesAlokasi'] = $GetAlokasi;

		
		$this->parseData['query1'] = $this->M_QueryPd->GetTbl1();
		$this->parseData['UraianPD'] = $this->M_QueryPd->uraianPD();
		$this->parseData['InvoicePD'] = $this->M_QueryPd->InvoicePD();
		$this->parseData['PembayaranPD'] = $this->M_QueryPd->PembayaranPD();

		$data['judul'] = 'Query PD/PJ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/reports/PdPj/querypd", $data);
		$this->load->view('templates/footer');
	}

	function dapatkan_table()
	{
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$dari_tgl = ($this->input->post('dari_tgl', TRUE) !== '') ? $this->input->post('dari_tgl', TRUE) : '';
		$sampai_tgl = ($this->input->post('sampai_tgl', TRUE) !== '') ? $this->input->post('sampai_tgl', TRUE) : '';

		$jumlah_data = $this->input->post('jumlah_data', TRUE);
		// $cek_type_data = is_numeric($jumlah_data);
		// if ($cek_type_data == FALSE) {
		// 	echo  HapusFormatUang(1);
		// 	exit;
		// }
		// exit;
		// if ($cek_type_data == TRUE) {
		// 	echo 'gagal';
		// 	exit;
		// }

		$where_array['JobNo'] = $JobNo;
		$where_array['Alokasi'] = $Alokasi;
		$where_array['dari_tgl'] = $dari_tgl;
		$where_array['sampai_tgl'] = $sampai_tgl;
		$where_array['jumlah_data'] = $jumlah_data;

		$data['query1'] = $this->M_QueryPd->GetTbl_new($where_array);

		$this->load->view('content/reports/PdPj/table_rekappdjaminan', $data);
	}
}
