<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Basic_Data extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('MIS_LOGGED_NAME') == '') {
            $this->session->sess_destroy();
            redirect(base_url('auth'));
        }
    }

    public function index()
    {
        $data['judul'] = 'Basic Data Quota';
        $data['bodyclass'] = 'sidebar-collapse skin-black';

         $data['Quota'] = $this->db->get('Data_Quota')->result(); // Contoh pengambilan data untuk menampilkan di tabel

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('Ist/Basic_Data/Basic_Data', $data);
        $this->load->view('templates/footer');
    }

    public function getDataTable()
    {
        $list = $this->db->get('basic_data')->result();
        $data = [];
        foreach ($list as $item) {
            $row = [];
            $row[] = $item->id;
            $row[] = $item->name;
            $row[] = '<button class="btn btn-sm btn-primary">Edit</button>';
            $data[] = $row;
        }
        echo json_encode(['data' => $data]);
    }

    public function Add_Basic_Data()
    {
        $data['judul'] = 'Tambah Data Quota SPBB';
        $data['bodyclass'] = 'sidebar-collapse skin-black';

        $data['no_spbb'] = $this->db->get('spbb')->result(); // Contoh pengambilan data untuk dropdown No SPBB
        $data['quota_edit'] = NULL;
        $data['form_action'] = site_url('Ist/Basic_Data/Basic_Data/Save_Data_Quota');
       



        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('Ist/Basic_Data/Add_Basic_Data', $data);
        $this->load->view('templates/footer');
        
    }

    public function Save_Data_Quota()
    {
        $no_spbb = $this->input->post('no_spbb');
        // Ambil data lain dari form jika ada

        // Simpan data ke database
        $data = [
            'No_SPBB' => $no_spbb,
            'Area' => $this->input->post('Area'),
            'No_SK' => $this->input->post('No_SK_Pertamina'),
            'Tgl_SK' => $this->input->post('Tanggal_SK'),
            'ThnAnggaran' => $this->input->post('Tahun_Anggaran'),
            'Vol_Quota' => $this->input->post('Volume_Quota'),
            'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
            'TimeEntry' => date('Y-m-d H:i:s')


            // Tambahkan field lain sesuai kebutuhan
        ];

        $this->db->insert('Data_Quota', $data);
        redirect(site_url('Ist/Basic_Data/Basic_Data'));
    }

    public function Edit_Basic_Data($ledger_no)
    {
        $data['judul'] = 'Edit Data Quota SPBB';
        $data['bodyclass'] = 'sidebar-collapse skin-black';
        $data['no_spbb'] = $this->db->get('spbb')->result();
        $data['quota_edit'] = $this->db->where('LedgerNo', $ledger_no)->get('Data_Quota')->row();
        $data['form_action'] = site_url('Ist/Basic_Data/Basic_Data/Update_Data_Quota/' . $ledger_no);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('Ist/Basic_Data/Add_Basic_Data', $data);
        $this->load->view('templates/footer');
    }

    public function Update_Data_Quota($ledger_no)
    {
        $data = [
            'No_SPBB' => $this->input->post('no_spbb'),
            'Area' => $this->input->post('Area'),
            'No_SK' => $this->input->post('No_SK_Pertamina'),
            'Tgl_SK' => $this->input->post('Tanggal_SK'),
            'ThnAnggaran' => $this->input->post('Tahun_Anggaran'),
            'Vol_Quota' => $this->input->post('Volume_Quota'),
            'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
            'TimeEntry' => date('Y-m-d H:i:s')
        ];

        $this->db->where('LedgerNo', $ledger_no)->update('Data_Quota', $data);
        redirect(site_url('Ist/Basic_Data/Basic_Data'));
    }
}
