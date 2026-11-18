<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Approve_PD_CostControl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('Approve_permintaan_dana_lapangan_model');
		$this->load->helper('bantu');
	}
	public function view($JobNo='',$Alokasi='',$dari_tgl='',$sampai_tgl='')
	{

		if($JobNo==''){
			exit;
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$this->load->model('m_job');
		$pdpj = $this->m_job->pdpj($JobNo);
		$data['pdpj'] = $pdpj;
		$data['judul'] = 'APPROVE PERMINTAAN DANA (LAPANGAN)';
		$data['data_alokasi'] = AksesAlokasi();

		$data['Alokasi'] = $Alokasi;
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';


		$data['JobNo_back'] = $JobNo;
		$data['Alokasi_back'] = $Alokasi;
		$data['dari_tgl_back'] = $dari_tgl;
		$data['sampai_tgl_back'] = $sampai_tgl;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('approve_permintaan_dana_lapangan/view', $data);
		$this->load->view('templates/footer');
	}



	function dapat_list_pd()
	{
		if (!$this->input->post('JobNo', TRUE)) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$dari_tgl = $this->input->post('dari_tgl', TRUE);
		$sampai_tgl = $this->input->post('sampai_tgl', TRUE);

		$UrlPengenal = ($this->input->post('UrlPengenal', TRUE)) ? $this->input->post('UrlPengenal', TRUE) : '';



		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['dari_tgl'] = $dari_tgl;
		$data['sampai_tgl'] = $sampai_tgl;
		$data['UrlPengenal'] = $UrlPengenal;



		$this->load->view('approve_permintaan_dana_lapangan/dapat_list_pd', $data);
	}


	function dapat_list_pd_json()
	{
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$dari_tgl = ($this->input->post('dari_tgl', TRUE)) ? $this->input->post('dari_tgl', TRUE) : '';
		$sampai_tgl =  ($this->input->post('sampai_tgl', TRUE)) ? $this->input->post('sampai_tgl', TRUE) : '';

		$UrlPengenal = ($this->input->post('UrlPengenal', TRUE)) ? $this->input->post('UrlPengenal', TRUE) : '';




		$where = array(
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi
		);

		header('Content-Type: application/json');
		echo $this->Approve_permintaan_dana_lapangan_model->dapat_list_pd_json($where, $dari_tgl, $sampai_tgl,$UrlPengenal);
	}

	function aksi_approve_lapangan(){
		if (!$this->input->post()) {
			exit;
		}
        // print_rr($_POST);
        // die;
		$LedgerNo = $this->input->post('LedgerNo', TRUE);
		$value_status_pd = $this->input->post('value_status_pd', TRUE);
		$ApprovedByKK = $this->input->post('ApprovedByKK', TRUE);


		

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);
		$NoPD = $this->input->post('NoPD', TRUE);
		
        //values status pd, kalau 0 itu belum di approve, lalu kalau 1 berarti sudah di approve dan ingin melakukan unapprove

		$user_id = $this->session->userdata('MIS_LOGGED_ID');
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");

        // echo $ApprovedByKK;
        // die;
		$TimeEntry = date('Y-m-d H:i:s');


		$where = array(
			'LedgerNo' => $LedgerNo
		);

		$data['ApprovedByKT'] = NULL;
		$data['TimeApprovedKT'] = NULL;


		$cek_hasil = '';
		if($value_status_pd == 0){
            // echo 'sini';
            //ketika melakukan approve
			$data['ApprovedByKT'] = $UserEntry;
			$data['TimeApprovedKT'] = $TimeEntry;
		}else if($value_status_pd == 1){

            // echo 'sana';

			if ($ApprovedByKK == 'sudah_KK') {
                // echo 'sudah_KK';
                // die;
				$cek_hasil = 'sudah_KK';
			}

			$cek_kasir = $this->db->query(" SELECT TOP 1 * FROM BLE WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY LedgerNo DESC ");

			if ($cek_kasir->num_rows() > 0 ) {
				$cek_hasil = 'ada_pembayaran';
                // echo 'ada_pembayaran';
                // die;
			}

            //ketika melakukan unapprove
			$data['ApprovedByKT'] = NULL;
			$data['TimeApprovedKT'] = NULL;
		}

		if ($cek_hasil == 'sudah_KK') {
			echo $cek_hasil;
			exit;
		} else if ($cek_hasil == 'ada_pembayaran') {
			echo $cek_hasil;
			exit;
		}

        // echo $cek_hasil;
        // exit;


		$simpan = $this->Approve_permintaan_dana_lapangan_model->aksi_approve_lapangan($where,$data);
		if ($simpan == TRUE) {
			echo 'berhasil';
			die;
		}
		echo 'gagal';
		die;


	}

}

/* End of file Approve_PD_CostControl.php */
/* Location: ./application/controllers/Approve_PD_CostControl.php */