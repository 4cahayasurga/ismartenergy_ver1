<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApprovePd extends CI_Controller
{
	private $akses;
	public function __construct()
	{
		parent::__construct();
		$this->akses = array(
			'UserID' => GetAksesUser('UserID')

		);
		$this->akses = GetAksesUser('UserID');
		$this->load->model('ApprovePd_model');
	}

	public function view($JobNo = '')
	{

		if ($JobNo == '') {

			redirect('Job/index', 'refresh');
			exit;
		}


		$data['judul'] = 'Approve PD';
		$data['Job'] = GetJob($JobNo)->row();
		$data['alokasi'] = GetAlokasi($this->akses);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('content/approve_pd/approve_pd_view', $data);
		$this->load->view('templates/footer');
	}

	function get_json_pd($JobNo = '', $alokasi = '', $cari = '')
	{
		if ($JobNo != '') {
			header('Content-Type: application/json');
			echo $this->ApprovePd_model->get_json_pd($JobNo, $alokasi, $cari);
		}
	}

	function show_table()
	{
		if ($this->input->post()) {
			$JobNo = $this->input->post('JobNo');
			$alokasi = $this->input->post('alokasi');
			$cari = $this->input->post('cari');

			$data['JobNo'] = $JobNo;
			$data['alokasi'] = $alokasi;
			$data['cari'] = $cari;
			$this->load->view('content/approve_pd/tbl_app_pd', $data);
		}
	}

	function status()
	{
		if ($this->input->post()) {
			$ApprovedByAK = $this->input->post('ApprovedByAK');
			$JobNo = $this->input->post('JobNo');
			$NoPD = $this->input->post('NoPD');

			if ($ApprovedByAK == '') {
				$data = array(
					'ApprovedByAK' => $this->session->userdata('MIS_LOGGED_NAME'),
					'TimeApprovedAK' => date("Y-m-d H:i:s")
				);
			} else {
				$data = array(
					'ApprovedByAK' => NULL,
					'TimeApprovedAK' => NULL
				);
			}
			$where = array(
				'NoPD' => $NoPD,
				'JobNo' => $JobNo
			);
			$simpan = $this->ApprovePd_model->ApproveUnapprove($where, $data);
			if ($simpan === TRUE) {

				if ($ApprovedByAK == '') {
					echo json_encode(array(
						'status' => 'success',
						'approve' => 'ya'
					));
					exit;
				} else {
					echo json_encode(array(
						'status' => 'success',
						'approve' => 'no'
					));
					exit;
				}
			}
			echo json_encode(array(
				'status' => 'no'
			));
			exit;
		}
	}

	function prn($JobNo = '', $alokasi, $cari = '')
	{
		if ($JobNo != '' && $alokasi != '') {

			$data['pd']  = $this->ApprovePd_model->getDataApprovePd($JobNo, $alokasi, $cari)->result();
			$data['row']  = $this->db->select('JobNo,JobNm')->get_where('Job', array('JobNo' => $JobNo))->row();

			$data['alokasi'] = '';
			if ($alokasi != '') {
				$get = $this->db->select("CONCAT_WS(' - ',Alokasi,Keterangan) as gabung")->where('Alokasi', $alokasi)->get('Alokasi')->row();
				$data['alokasi'] = "<span class='judul'>Alokasi : " . $get->gabung . "</span><br>";
			}

			$data['cari'] = '';
			if ($cari != '') {

				$data['cari'] = "<span class='judul'>Pencarian : " . $cari . "</span><br>";
			}

			$this->load->library('pdf');
			$this->pdf->setPaper('A3', 'Landscape');
			$this->pdf->filename = "Rpt-ApprovePd-" . date('Y-m-d H:i:s') . "-.pdf";
			$this->pdf->load_view('content/approve_pd/RptApprovePd', $data);
		}
	}
}

/* End of file Controllername.php */
