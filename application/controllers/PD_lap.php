<?php

defined('BASEPATH') or exit('No direct script access allowed');

use \Mpdf\Mpdf;

class PD_lap extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('PD_lap_model');
    }

    public function view($JobNo)
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');


        $this->load->model('m_job');
        $pdpj = $this->m_job->pdpj($JobNo);
        $data['pdpj'] = $pdpj;
        $data['judul'] = 'PD & PJ';

        $data['data_alokasi'] = AksesAlokasi();
        $data['dari_tgl'] = date('Y-m-d', strtotime('-7 days'));
        $data['sampai_tgl'] = date('Y-m-d');
        // is_pesan('success', 'Berhasil mengubah data');

        // print_rr($data);
        // exit;


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('PD_lap/pd_lap_view', $data);
        $this->load->view('templates/footer');
    }

    function tampil_table_pd_lapangan()
    {
        if (!$this->input->post()) {
            exit;
        }

        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $Pencarian = $this->input->post('Pencarian', TRUE);

        $dari_tgl = $this->input->post('dari_tgl', TRUE);
        $sampai_tgl = $this->input->post('sampai_tgl', TRUE);

        $where = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi,
            'Pencarian' => $Pencarian,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );

        // print_rr($_POST);
        // exit;

        $data['mydata'] = $where;
        $this->load->view('PD_lap/tampil_table_pd_lapangan', $data);
    }

    function json_table_pd_lapangan()
    {
        if (!$this->input->post()) {
            exit;
        }



        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $dari_tgl = $this->input->post('dari_tgl', TRUE);
        $sampai_tgl = $this->input->post('sampai_tgl', TRUE);

        $Pencarian = $this->input->post('Pencarian', TRUE);


        $where = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi,

        );

        header('Content-Type: application/json');
        echo $this->PD_lap_model->json_table_pd_lapangan($where, $dari_tgl, $sampai_tgl, $Pencarian);
    }

    function print_pd_lap($LedgerNo = '')
    {
        if ($LedgerNo == '') {
            exit;
        }

        $nama_user = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $nama_user;

        $dapat_pd = $this->PD_lap_model->dapat_data_pd($LedgerNo)->row_array();

        $JobNo = $dapat_pd['JobNo'];
        $NoPD = $dapat_pd['NoPD'];
        $Alokasi = $dapat_pd['Alokasi'];

        $where = array(
            'NoPD' => $NoPD,
            'JobNo' => $JobNo,
        );
        $data['NoPD'] = $NoPD;


        $data['data'] = $this->PD_lap_model->dapat_data_pd($LedgerNo)->row_array();
        $data['detail_pd'] = $this->PD_lap_model->dapat_pd_detail($NoPD);
        $data['SaldoSebelumnya'] = $this->PD_lap_model->cekSaldoSebelumnya($where);

        $this->load->view('PD_lap/print_pd_lap', $data);
    }
    // function print_pd_lap_modal()
    // {
    //     if (!$this->input->post()) {
    //         exit;
    //     }
    //     $LedgerNo = $this->input->post('LedgerNo', TRUE);
    //     echo $LedgerNo;
    // }

    function pd_lap_detail($LedgerNo = '', $JobNo = '')
    {
        if ($LedgerNo == '') {
            redirect('PD_lap', 'refresh');
            exit;
        }
        // is_pesan_saya('success', 'Berhasil');
        // $this->session->set_flashdata('pesan', '<h1>ddddddd</h1>');

        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        // $data['alokasi_lain'] = getAlokasi($user_id);
        $data['data_alokasi'] = AksesAlokasi();
        // $data['mytipeform'] = dapatkan_tipeform();

        $data['detail'] = $this->db->select('JobNo,JobNm,Lokasi')->from('Job')->where('JobNo', $JobNo)->get()->row_array();
        $data['data_pd'] = $this->db->where('LedgerNo', $LedgerNo)->get('PdHdr')->row_array();
        $data['judul'] = 'PD & PJ';


        $data['LedgerNo'] = $LedgerNo;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('PD_lap/pd_lap_detail', $data);
        $this->load->view('templates/footer');
    }

    function tampil_uraian_atau_invoice()
    {
        if (!$this->input->post()) {
            exit;
        }

        $type = $this->input->post('type', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $LedgerNo = $this->input->post('LedgerNo', TRUE);
        // print_rr($_POST);
        // die;

        if ($type == 'uraian_pd') {
            $data['NoPD'] = $NoPD;
            $data['JobNo'] = $JobNo;
            $data['Alokasi'] = $Alokasi;
            $data['LedgerNo'] = $LedgerNo;

            $where['NoPD'] = $NoPD;
            $where['JobNo'] = $JobNo;
            $where['Alokasi'] = $Alokasi;

            $data['mydatauraian'] = $this->PD_lap_model->dapatkan_detail_uraian_pd($NoPD);
            $data['SaldoSebelumnya'] = $this->PD_lap_model->cekSaldoSebelumnya($where);
            // print_rr($data);
            // die;


            $this->load->view('PD_lap/uraian_pd_lap', $data);
        } else if ($type == 'invoice_pd') {
            echo 'invoice';
        } else {
            echo '';
        }
    }
    function tambah_uraian_pd_saat_edit_pd()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoPD = $this->input->post('NoPD', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $LedgerNo = $this->input->post('LedgerNo', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);

        $data['NoPD'] = $NoPD;
        $data['Alokasi'] = $Alokasi;
        $data['LedgerNo'] = $LedgerNo;
        $data['JobNo'] = $JobNo;
        $data['data_rap'] = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe != 'Header'")->result_array();
        // print_rr($data);
        // die;
        // $judul = $this->db->select('JobNo')

        $this->load->view('PD_lap/form_tambah_uraian_ketika_edit_pd', $data);
    }

    function simpan_tambah_uraian_di_pd_edit()
    {
        if (!$this->input->post()) {
            exit;
        }
        $NoPD = $this->input->post('NoPD', TRUE);
        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);
        $Vol = $this->input->post('Vol', TRUE);
        $Uom = $this->input->post('Uom', TRUE);
        $HrgSatuan = $this->input->post('HrgSatuan', TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $data = array(
            'NoUrut' => dapat_no_urut_table_pdhdr($NoPD),
            'NoPD' => $NoPD,
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'HrgSatuan' => $HrgSatuan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
        );

        $simpan = $this->PD_lap_model->simpan_uraian_pd_ketika_edit_pd($data);

        if ($simpan == TRUE) {
            is_pesan('success', 'berhasil menambahkan uraian');
            exit;
        }
        is_pesan('error', 'Gagal');
        exit;



        // print_rr($data);
        // die;
    }

    function hapus_uraian()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);

        $where = array(
            'NoUrut' => $NoUrut,
            'NoPD' => $NoPD,
        );


        $hapus_uraian = $this->PD_lap_model->hapus_uraian_dtl($where);
        if ($hapus_uraian == TRUE) {
            is_pesan('success', 'berhasil dihapus');

            exit;
        }
        is_pesan('error', 'gagal dihapus');
        exit;
    }

    function edit_uraian_pd_saat_edit_pd()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoPD = $this->input->post('NoPD', TRUE);
        $NoUrut = $this->input->post('NoUrut', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);

        $data['NoPD'] = $NoPD;
        $data['Alokasi'] = $Alokasi;
        $data['JobNo'] = $JobNo;
        $data['NoUrut'] = $NoUrut;

        $data['data_rap'] = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe != 'Header'")->result_array();

        $data['data_dtl'] = $this->db->where(array('NoUrut' => $NoUrut, 'NoPD' => $NoPD))->get('PdDtl')->row_array();
        // print_rr($data);
        // die;
        // $judul = $this->db->select('JobNo')

        $this->load->view('PD_lap/form_edit_uraian_ketika_edit_pd', $data);
    }

    function simpan_edit_uraian_di_pd_edit()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoPD = $this->input->post('NoPD', TRUE);
        $NoUrut = $this->input->post('NoUrut', TRUE);


        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);
        $Vol = $this->input->post('Vol', TRUE);
        $Uom = $this->input->post('Uom', TRUE);
        $HrgSatuan = $this->input->post('HrgSatuan', TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $where = array(
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut

        );

        $data = array(
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'HrgSatuan' => $HrgSatuan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
        );

        $simpan = $this->PD_lap_model->simpan_edit_uraian_pd_ketika_edit_pd($where, $data);

        if ($simpan == TRUE) {
            is_pesan('success', 'berhasil mengubah uraian');
            exit;
        }
        is_pesan('error', 'Gagal');
        exit;



        // print_rr($data);
        // die;
    }

    function edit_pdhdr()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoRef = $this->input->post('NoRef', true);
        $TglPD = $this->input->post('TglPD', true);
        $Jaminan = ($this->input->post('Jaminan')) ? $this->input->post('Jaminan') : 0;
        $Deskripsi = $this->input->post('Deskripsi', true);
        $PrdAwal = $this->input->post('PrdAwal', true);
        $PrdAkhir = $this->input->post('PrdAkhir', true);
        $Minggu = $this->input->post('Minggu', true);
        $BuktiPendukung = ($this->input->post('BuktiPendukung')) ?  MembuatMenjadiKoma($this->input->post('BuktiPendukung')) : '';
        $NoTagihan = $this->input->post('NoTagihan', true);
        $Keterangan = $this->input->post('Keterangan', true);
        $TotalPD = $this->input->post('TotalPD', true);


        $NoPD = $this->input->post('NoPD');
        $JobNo = $this->input->post('JobNo', true);
        $Alokasi = $this->input->post('Alokasi', true);
        $LedgerNo = $this->input->post('LedgerNo', true);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $cek_data_uraian = $this->PD_lap_model->cek_data_uraian(array('NoPD' => $NoPD));
        if ($cek_data_uraian == 'belum_ada') {

            $array = array(
                'JobNo' => $JobNo,
                'pesan' => 'uraian_kosong',
            );
            echo json_encode($array);
            exit;
        }

        $data = array(
            'NoRef' => $NoRef,
            'TglPD' => $TglPD,
            'Jaminan' => $Jaminan,
            'Deskripsi' => $Deskripsi,
            'PrdAwal' => $PrdAwal,
            'PrdAkhir' => $PrdAkhir,
            'Minggu' => $Minggu,
            'BuktiPendukung' => $BuktiPendukung,
            'Keterangan' => $Keterangan,
            'NoTagihan' => $NoTagihan,
            'TotalPD' => $TotalPD,
            'TimeEntry' => $TimeEntry,
            'UserEntry' => $UserEntry

        );

        $where = array(
            'LedgerNo' => $LedgerNo,
        );


        $simpan = $this->PD_lap_model->simpan_edit_pdhdr($where, $data);

        if ($simpan == TRUE) {

            $array = array(
                'JobNo' => $JobNo,
                'pesan' => 'berhasil',
            );
            echo json_encode($array);
            is_pesan('success', 'Berhasil mengubah data');
            exit;
        }
        $array = array(
            'JobNo' => $JobNo,
            'pesan' => 'gagal',
        );
        echo json_encode($array);
        is_pesan('error', 'Gagal mengubah data');
        exit;
    }

    function lakukan_approve()
    {
        if (!$this->input->post()) {
            exit;
        }



        $status_app = $this->input->post('status_app', true);
        $LedgerNo = $this->input->post('LedgerNo', true);


        $NoPD = $this->db->select('NoPD')->where('LedgerNo', $LedgerNo)->get('PdHdr')->row_array()['NoPD'];

        $cek_pd_sudah_sampai_kt = cek_pd_sudah_sampai_kt($NoPD);

        if ($cek_pd_sudah_sampai_kt == 'sudah_app_kt' && $status_app == 'sudahapprove') {
            echo 'sudah_app_kt';
            die;
        }
        // echo $cek_pd_sudah_sampai_kt;
        // die;

        // $cek_pd_sudah_sampai_kk = cek_pd_sudah_sampai_kk($NoPD);
        // if ($cek_pd_sudah_sampai_kk == 'sudah_app_kk' && $status_app == 'sudahapprove') {
        //     echo 'sudah_app_kk';
        //     die;
        // }

        // $cek_pd_sudah_sampai_kasir_belum = cek_pd_sudah_sampai_kasir_belum($NoPD);
        // if ($cek_pd_sudah_sampai_kasir_belum == 'sudah_pembayaran' && $status_app == 'sudahapprove') {
        //     echo 'sudah_pembayaran';
        //     die;
        // }





        // $data = array();


        if ($status_app == 'sudahapprove') {

            $data = array(
                'ApprovedByAK' => NULL,
                'TimeApprovedAK' => NULL
            );
        } else {
            $data = array(
                'ApprovedByAK' => $this->session->userdata("MIS_LOGGED_NAME"),
                'TimeApprovedAK' => date('Y-m-d H:i:s')
            );
        }

        $where['LedgerNo'] = $LedgerNo;

        $simpan = $this->PD_lap_model->update_approve($where, $data);
        if ($simpan == TRUE) {

            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;
    }
}

/* End of file PD_con.php */
