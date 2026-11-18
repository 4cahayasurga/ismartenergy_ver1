<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ko_addendum extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('M_ko_addendum');
        $this->load->helper('bantu');
    }


    public function view($JobNo = '')
    {

        if ($JobNo == '') {
            redirect('Welcome', 'refresh');
            die;
        }

        // $data['JobNo'] = ($JobNo == '') ? '' : $JobNo;
        $data['judul'] = "ADDENDUM KONTRAK/PURCHASE ORDER";
        $data['bodyclass'] = 'sidebar-collapse skin-blue';

        $query = $this->db->query("SELECT JobNo,JobNm,Lokasi FROM Job WHERE JobNo='$JobNo' ")->row_array();
        $data['JobNo'] = $JobNo;
        $data['JobNm'] = $query['JobNm'];
        $data['Lokasi'] = $query['Lokasi'];

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('ko_addendum/view_ko_addendum', $data);
        $this->load->view('templates/footer');
    }

    function request_list_ko_addendum()
    {
        if (!$this->input->post()) {
            die;
        }

        $JobNo = $this->input->post('JobNo', TRUE);
        $data['JobNo'] = $JobNo;
        // echo $JobNo;
        // die;

        $this->load->view('ko_addendum/dapat_list_ko_addendum', $data);
    }

    function dapat_list_ko_addendum()
    {
        if (!$this->input->post('JobNo', TRUE)) {
            die;
        }

        $JobNo = $this->input->post('JobNo', TRUE);

        header('Content-Type: application/json');
        echo $this->M_ko_addendum->dapat_list_ko_addendum($JobNo);
    }

    function frm_addendum()
    {
        if (!$this->input->post()) {
            die;
        }

        $LedgerNo = $this->input->post('LedgerNo', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $AddendumKe = $this->input->post('AddendumKe', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);

        $data['row_ko'] = $this->db->query(" SELECT AddendumKe,AlasanAddendum FROM KoHdr WHERE LedgerNo='$LedgerNo' ")->row_array();
        $data['data_ko'] = $this->db->query("
            SELECT
            a.LedgerNo,
            a.NoKO,
            a.JobNo,
            a.TglKO,
            b.VendorId,
            b.VendorNm,
            a.KategoriId,
            a.AddendumKe,
            a.SubTotal,
            a.ApprovedBy,
            a.TimeApproved,
            a.Keterangan,
            a.TimeEntry  
            FROM KoHdrH a 
            LEFT JOIN Vendor b ON a.VendorId=b.VendorId
            WHERE a.JobNo='$JobNo' AND a.NoKO='$NoKO'
            ORDER BY LedgerNo DESC");

        $data['NoKO'] = $NoKO;
        $data['LedgerNo'] = $LedgerNo;
        $this->load->view('ko_addendum/frm_addendum', $data);
    }

    function aksi_simpan_addendum()
    {
        if (!$this->input->post()) {
            die;
        }
        $AddendumKe = $this->input->post('AddendumKe', TRUE);
        $AlasanAddendum = $this->input->post('AlasanAddendum', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);
        $LedgerNo = $this->input->post('LedgerNo', TRUE);

        $data = array(
            'AddendumKe' => $AddendumKe,
            'AlasanAddendum' => $AlasanAddendum
        );

        $where = array(
            'NoKO' => $NoKO,
            'JobNo' => $JobNo
        );

        $this->db->query(
            "INSERT INTO KoHdrH(
            NoKO, TglKO, JobNo, VendorId, KategoriId,
            SubKategoriId, NoSPR, SubTotal, DiscPercentage, 
            DiscAmount, PPN, TotalTerbayar, AddendumKe,
            ClosedBy, TimeClosed, AlamatKirim, NamaKirim, 
            TeleponKirim,MaterialApproval, RAP, K3, SyaratTeknis, 
            SyaratPembayaran, JadwalPengiriman, Sanksi, 
            JadwalPembayaran, Keterangan, QRCode, ApprovedBy, 
            TimeApproved,UserEntry,TimeEntry,OverridePPN, PDFFile, CanceledBy, TimeCancel, 
            CancelReason, AlasanAddendum, TglPengiriman,PotongKO, BeginningTotal )
            SELECT
            NoKO, TglKO, JobNo, VendorId, KategoriId,
            SubKategoriId, NoSPR, SubTotal, DiscPercentage, 
            DiscAmount, PPN, TotalTerbayar, AddendumKe,
            ClosedBy, TimeClosed, AlamatKirim, NamaKirim, 
            TeleponKirim,MaterialApproval, RAP, K3, SyaratTeknis, 
            SyaratPembayaran, JadwalPengiriman, Sanksi, 
            JadwalPembayaran, Keterangan, QRCode, ApprovedBy, 
            TimeApproved,UserEntry,TimeEntry,OverridePPN, PDFFile, CanceledBy, TimeCancel, 
            CancelReason, AlasanAddendum, TglPengiriman,PotongKO, BeginningTotal 
            FROM KoHdr WHERE JobNo='$JobNo' AND NoKO='$NoKO'"

        );

        $this->db->query(" UPDATE KoHdr SET AddendumKe='$AddendumKe', AlasanAddendum='$AlasanAddendum' WHERE JobNo='$JobNo' AND NoKO='$NoKO' ");
        echo 'berhasil';
        // print_rr($_POST);
        // die;
        // echo 'berhasil';
    }

    function dapat_tampil_table()
    {
        if (!$this->input->post()) {
            die;
        }

        $JobNo = $this->input->post('JobNo', TRUE);
        $data['JobNo'] = $JobNo;
        $this->load->view('ko_addendum/list_table_addendum', $data);
    }
}

/* End of file Ko_addendum.php */
