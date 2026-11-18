<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FrmReqKaryawan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Model_PelamarPosting');
    }


    public function index()
    {
        $data['judul'] = 'Form Permintaan Personel / Karyawan';
        $data['bodyclass'] = 'sidebar-collapse skin-blue';
        $data['allData'] = $this->db->get('PermintaanKaryawan')->result_array();
        // print_rr($data['allData']);
        // die;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('FrmRequestKaryawan/halaman_utama', $data);
        $this->load->view('templates/footer', $data);
    }

    public function create_request()
    {
        $posisi = $this->input->post('Posisi');
        $jumlah = $this->input->post('JumlahPermintaan');
        $penempatan = $this->input->post('Penempatan');
        $waktuBergabung = $this->input->post('WaktuBergabung');
        $durasiKontrak = $this->input->post('DurasiKontrak');
        $kualifikasi = $this->input->post('Kualifikasi');
        $idReq = $this->generateIDRequest();

        $data = [
            "IDRequest" => $idReq,
            "Posisi" => $posisi,
            "Jumlah" => $jumlah,
            "Penempatan" => $penempatan,
            "TglSiapKerja" => $waktuBergabung,
            "DurasiKontrak" => $durasiKontrak,
            "Kualifikasi" => $kualifikasi,
            "Status" => "request",
        ];

        $insert = $this->db->insert("PermintaanKaryawan", $data);
        if ($insert) {
            $this->session->set_flashdata("message", "Data berhasil ditambah");
            redirect("FrmReqKaryawan");
        }
    }

    private function generateIDRequest()
    {
        $query = $this->db->query("SELECT TOP 1 IDRequest FROM PermintaanKaryawan ORDER BY IDRequest DESC")->row();
        if ($query == NULL) {
            $ID = 'REQ0001';
        } else {
            $getID = $query->IDRequest;
            $trimID = substr($getID, 4);
            $addID =  str_pad($trimID + 1, 4, '0', STR_PAD_LEFT);
            $ID = 'REQ' . $addID;
        }

        return $ID;
    }
}
