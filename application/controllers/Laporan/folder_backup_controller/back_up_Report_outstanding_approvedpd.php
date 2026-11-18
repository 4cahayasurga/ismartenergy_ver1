<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Report_outstanding_approvedpd extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Laporan_model/M_report_outstanding_approvedpd');
    }


    public function index()
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $data['judul'] = 'Report Outstanding / Approved PD';
        $data['bodyclass'] = 'sidebar-collapse skin-warning';

        $tgl_saat_ini = date('Y-m-d');

        $data['tgl_pertama'] = date('Y-m-d', strtotime('-7 days'));
        $data['tgl_terakhir'] = $tgl_saat_ini;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('laporan_view/report_outstanding_approvedpd/view_report_outstanding_approvedpd', $data);
        $this->load->view('templates/footer');
    }

    function tampil_table_outstanding()
    {
        if (!$this->input->post()) {
            die;
        }

        $By_pilihan = $this->input->post('By_pilihan', true);
        $Type_pilihan = $this->input->post('Type_pilihan', true);
        $dari_tgl = $this->input->post('dari_tgl', true);
        $sampai_tgl = $this->input->post('sampai_tgl', true);

        $data = array(
            'By_pilihan' => $By_pilihan,
            'Type_pilihan' => $Type_pilihan,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );

        // $total_pd = $this->db->query("select SUM(TotalPD) as SubTotalPD from #TempOutstanding")->row_array();
        // $data['TotalPD'] = $total_pd['SubTotalPD'];
        // die;

        // $data['mydata'] = $this->M_report_outstanding_approvedpd->dapat_data_json_outstanding($data);
        // print_rr($data);
        // header('Content-Type: application/json');
        // echo $data['mydata'];
        // die;

        $this->load->view('laporan_view/report_outstanding_approvedpd/table_report_outstanding_approvedpd', $data);
    }

    function dapat_list_outstanding()
    {
        if (!$this->input->post()) {
            exit;
        }

        $By_pilihan = $this->input->post('By_pilihan', true);
        $Type_pilihan = $this->input->post('Type_pilihan', true);
        $dari_tgl = $this->input->post('dari_tgl', true);
        $sampai_tgl = $this->input->post('sampai_tgl', true);

        $data = array(
            'By_pilihan' => $By_pilihan,
            'Type_pilihan' => $Type_pilihan,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );

        // print_rr($_POST);

        header('Content-Type: application/json');
        echo $this->M_report_outstanding_approvedpd->dapat_list_outstanding_data_json($data);
    }

    function rpt_outstanding_pdf($By_pilihan = '', $Type_pilihan = '', $dari_tgl = '', $sampai_tgl = '')
    {

        if ($By_pilihan == '' || $Type_pilihan == '') {
            redirect('Laporan/Report_outstanding_approvedpd', 'refresh');
            die;
        }

        $data['By_pilihan'] = $By_pilihan;
        $data['Type_pilihan'] = $Type_pilihan;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;

        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        $where = array(
            'By_pilihan' => $By_pilihan,
            'Type_pilihan' => $Type_pilihan,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );


        $data['mydata'] = $this->M_report_outstanding_approvedpd->dapat_data_outstanding($where);
        // print_rr($data);

        $this->load->view('laporan_view/report_outstanding_approvedpd/print/pdf_outstanding_content', $data);
    }

    // function dapat_total_pd()
    // {
    //     if (!$this->input->post()) {
    //         exit;
    //     }

    //     $SubTotalPD = 0;
    //     $query =  $this->db->query("select SUM(TotalPD) as SubTotalPD from #TempOutstanding")->row_array();
    //     if (count($query) > 0) {
    //         $SubTotalPD .= $query['SubTotalPD'];
    //     }

    //     echo $query['SubTotalPD'];
    // }

    // function get_data($data)
    // {

    // if (!$this->input->post()) {
    //     die;
    // }

    // $By_pilihan = $this->input->post('By_pilihan', true);
    // $Type_pilihan = $this->input->post('Type_pilihan', true);
    // $dari_tgl = $this->input->post('dari_tgl', true);
    // $sampai_tgl = $this->input->post('sampai_tgl', true);

    // $data = array(
    //     'By_pilihan' => $By_pilihan,
    //     'Type_pilihan' => $Type_pilihan,
    //     'dari_tgl' => $dari_tgl,
    //     'sampai_tgl' => $sampai_tgl,
    // );


    // header('Content-Type: application/json');
    // echo
    // echo $this->M_report_outstanding_approvedpd->dapat_data_json_outstanding($data);
    // }
}

/* End of file Report_outstanding_approvedpd.php */
