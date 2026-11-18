<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Mpdf\Mpdf;

class Vendor_new extends CI_Controller
{
    var $Company;
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->Company = $this->config->item('Company');
        $this->load->model('M_vendor');
        $this->load->helper('bantu');
    }

    public function index()
    {

        $data['judul'] = "LIST DATA VENDOR";

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('procurement/vendor/view_vendor', $data);
        $this->load->view('templates/footer');
    }

    function dapat_list_data_vendor()
    {
        header('Content-Type: application/json');
        echo $this->M_vendor->dapat_list_data_vendor();
    }

    function aksi_simpan_vendor()
    {
        if (!$this->input->post()) {

            exit;
        }

        $VendorNm = $this->input->post('VendorNm', TRUE);
        $Alamat = $this->input->post('Alamat', TRUE);
        $Kota = $this->input->post('Kota', TRUE);
        $Propinsi = $this->input->post('Propinsi', TRUE);
        $Fax = $this->input->post('Fax', TRUE);
        $NPWP = $this->input->post('NPWP', TRUE);
        $ContactPerson = $this->input->post('ContactPerson', TRUE);
        $Kategori = $this->input->post('Kategori', TRUE);
        $Telepon1 = $this->input->post('Telepon1', TRUE);
        $Telepon2 = $this->input->post('Telepon2', TRUE);
        $Telepon3 = $this->input->post('Telepon3', TRUE);
        $Email1 = $this->input->post('Email1', TRUE);
        $Email2 = $this->input->post('Email2', TRUE);
        $Email3 = $this->input->post('Email3', TRUE);
        $BidangUsaha = $this->input->post('BidangUsaha', TRUE);

        $PKP = ($this->input->post('PKP', TRUE)) ? $this->input->post('PKP', TRUE) : 0;

        //data bank
        $NoRek = $this->input->post('NoRek', TRUE);
        $AtasNama = $this->input->post('AtasNama', TRUE);
        $Bank = $this->input->post('Bank', TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $VendorId = KodeVendor();


        $data = array(

            'VendorId' => $VendorId,
            'VendorNm' => $VendorNm,
            'Alamat' => $Alamat,
            'Kota' => $Kota,
            'Propinsi' => $Propinsi,
            'Fax' => $Fax,
            'NPWP' => $NPWP,
            'ContactPerson' => $ContactPerson,
            'Kategori' => $Kategori,
            'Telepon1' => $Telepon1,
            'Telepon2' => $Telepon2,
            'Telepon3' => $Telepon3,
            'Email1' => $Email1,
            'Email2' => $Email2,
            'Email3' => $Email3,
            'BidangUsaha' => $BidangUsaha,
            'PKP' => $PKP,

            //data bank
            'AtasNama' => $AtasNama,
            'NoRek' => $NoRek,
            'Bank' => $Bank,

            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry

        );

        $simpan = $this->M_vendor->simpan_vendor($data);
        if ($simpan == TRUE) {
            echo 'berhasil';
            exit;
        }
        echo 'gagal';
        exit;
    }

    function dapat_data_vendor()
    {
        if (!$this->input->post()) {

            exit;
        }

        $VendorId = $this->input->post('VendorId', TRUE);

        $query = $this->db->query(" SELECT * FROM Vendor WHERE VendorId='$VendorId' ");
        if ($query->num_rows() == 0) {
            echo 'gagal';
            exit;
        }
        echo json_encode($query->row_array());
    }

    function aksi_edit_vendor()
    {
        if (!$this->input->post()) {

            exit;
        }


        $VendorId = $this->input->post('VendorId', TRUE);

        $VendorNm = $this->input->post('VendorNm', TRUE);
        $Alamat = $this->input->post('Alamat', TRUE);
        $Kota = $this->input->post('Kota', TRUE);
        $Propinsi = $this->input->post('Propinsi', TRUE);
        $Fax = $this->input->post('Fax', TRUE);
        $NPWP = $this->input->post('NPWP', TRUE);
        $ContactPerson = $this->input->post('ContactPerson', TRUE);
        $Kategori = $this->input->post('Kategori', TRUE);
        $Telepon1 = $this->input->post('Telepon1', TRUE);
        $Telepon2 = $this->input->post('Telepon2', TRUE);
        $Telepon3 = $this->input->post('Telepon3', TRUE);
        $Email1 = $this->input->post('Email1', TRUE);
        $Email2 = $this->input->post('Email2', TRUE);
        $Email3 = $this->input->post('Email3', TRUE);
        $BidangUsaha = $this->input->post('BidangUsaha', TRUE);

        $PKP = ($this->input->post('PKP', TRUE)) ? $this->input->post('PKP', TRUE) : 0;

        //data bank
        $NoRek = $this->input->post('NoRek', TRUE);
        $AtasNama = $this->input->post('AtasNama', TRUE);
        $Bank = $this->input->post('Bank', TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');



        $data = array(


            'VendorNm' => $VendorNm,
            'Alamat' => $Alamat,
            'Kota' => $Kota,
            'Propinsi' => $Propinsi,
            'Fax' => $Fax,
            'NPWP' => $NPWP,
            'ContactPerson' => $ContactPerson,
            'Kategori' => $Kategori,
            'Telepon1' => $Telepon1,
            'Telepon2' => $Telepon2,
            'Telepon3' => $Telepon3,
            'Email1' => $Email1,
            'Email2' => $Email2,
            'Email3' => $Email3,
            'BidangUsaha' => $BidangUsaha,
            'PKP' => $PKP,

            //data bank
            'AtasNama' => $AtasNama,
            'NoRek' => $NoRek,
            'Bank' => $Bank,

            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry

        );

        $simpan = $this->M_vendor->edit_vendor($data, $VendorId);
        if ($simpan == TRUE) {
            echo 'berhasil';
            exit;
        }
        echo 'gagal';
        exit;
    }
}
	
	/* End of file Permintaan_dana_lapangan.php */
	/* Location: ./application/controllers/Permintaan_dana_lapangan.php */