<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approve_permintaan_dana_keuangan extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Approve_permintaan_dana_keuangan_model');
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
        $data['judul'] = 'PD & PJ';

        $data['data_alokasi'] = AksesAlokasi();
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;

        $data['judul'] = 'APPROVE PERMINTAAN DANA (KEUANGAN)';
        $data['bodyclass'] = 'sidebar-collapse skin-yellow-light';
        $data['Alokasi'] = $Alokasi;
        $data['Alokasi_back'] = $Alokasi;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('approve_permintaan_dana_keuangan/view', $data);
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

        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;

        $this->load->view('approve_permintaan_dana_keuangan/dapat_list_pd', $data);
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




        $where = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi
        );

        header('Content-Type: application/json');
        echo $this->Approve_permintaan_dana_keuangan_model->dapat_list_pd_json($where, $dari_tgl, $sampai_tgl);
    }

    function aksi_approve_keuangan(){
        if (!$this->input->post()) {
            exit;
        }

        $LedgerNo = $this->input->post('LedgerNo', TRUE);
        $value_status_pd = $this->input->post('value_status_pd', TRUE);

        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);
        
        //values status pd, kalau 0 itu belum di approve, lalu kalau 1 berarti sudah di approve dan ingin melakukan unapprove

        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');


        $where = array(
            'LedgerNo' => $LedgerNo
        );

        $data['ApprovedByKK'] = NULL;
        $data['TimeApprovedKK'] = NULL;

        if($value_status_pd == 0){
            //ketika melakukan approve

            $data['ApprovedByKK'] = $UserEntry;
            $data['TimeApprovedKK'] = $TimeEntry;
        }

        if($value_status_pd == 1){

            $cek_kasir = $this->db->query(" SELECT TOP 1 * FROM BLE WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY LedgerNo DESC ");

            if ($cek_kasir->num_rows() > 0 ) {
                echo 'ada_pembayaran';
                die;
            }


            //ketika melakukan unapprove
            $data['ApprovedByKK'] = NULL;
            $data['TimeApprovedKK'] = NULL;
        }


        $simpan = $this->Approve_permintaan_dana_keuangan_model->aksi_approve_keuangan($where,$data);
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;


    }

}

/* End of file Approve_permintaan_dana_keuangan.php */
/* Location: ./application/controllers/Approve_permintaan_dana_keuangan.php */