<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Mpdf\Mpdf;

class RptPenyerapanRAP extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Laporan_model/Model_RptPenyerapanRAP');
    }

    public function index()
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $data['judul'] = 'Penyerapan RAP';
        $data['bodyclass'] = 'sidebar-collapse skin-orange';

        $data['tgl_pertama'] = date('Y-m-01');
        $data['tgl_terakhir'] = date('Y-m-t');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('laporan_view/penyerapan_rap/view_penyerapan_rap', $data);
        $this->load->view('templates/footer');
    }

    function view_penyerapan_rap()
    {
        if (!$this->input->post()) {
            die;
        }

        $JobNo = $this->input->post('JobNo', true);
        $Alokasi = $this->input->post('Alokasi', true);
        $dari_tgl = $this->input->post('dari_tgl', true);
        $sampai_tgl = $this->input->post('sampai_tgl', true);


        $data = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );

        // $data_penyerapan_rap = $this->Model_RptPenyerapanRAP->dapat_penyerapan_rap($data);
        // $data['data_penyerapan_rap'] = $data_penyerapan_rap;

        $data_penyerapan_rap = $this->Model_RptPenyerapanRAP->tes($data);
        $data['data_penyerapan_rap'] = $data_penyerapan_rap;

        // print_rr($_POST);
        // echo "<br>";
        // print_rr($data);
        // die;

        $this->load->view('laporan_view/penyerapan_rap/table_penyerapan_rap', $data);
    }
}

/* End of file Controllername.php */
