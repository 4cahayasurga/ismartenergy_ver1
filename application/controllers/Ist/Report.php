<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;


class Report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('MIS_LOGGED_NAME') == '') {
            $this->session->sess_destroy();
            redirect(base_url('auth'));
        }
        $this->load->model('Spr_model');
        $this->load->model('SalesRealization_model', 'sr');
        $this->load->model('Spbb_model');
        $role = $this->Spbb_model->get_role($this->session->userdata('MIS_LOGGED_ID'));
        if (!in_array($role['role'], array('PEMILIK_SPBB', 'SUPERADMIN')) &&
            strtolower($this->session->userdata('MIS_LOGGED_ID')) !== 'admin') {
            show_error('Akses ditolak', 403);
        }
    }

    public function SalesRealization()
    {
        $data['title'] = "Sales Realization Report";
        $data['judul'] = "Sales Realization Report";
        $data['bodyclass'] = 'sidebar-collapse skin-black';

        $data['sales_data'] = $this->db->get('SalesRealization')->result();


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('Ist/Report/SalesRealization', $data);
        $this->load->view('templates/footer');
    }

    public function import()
    {
        if (empty($_FILES['file']['name'])) {
            $this->session->set_flashdata('error', 'File belum dipilih.');
            redirect('Ist/Report/SalesRealization');
        }

        $file = $_FILES['file']['tmp_name'];

        $spreadsheet = IOFactory::load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $dataInsert = [];

        for ($i = 2; $i <= count($sheetData); $i++) {

            $row = $sheetData[$i];

            // ===============================
            // KONVERSI TANGGAL EXCEL (PENTING)
            // ===============================
            $transactionDate = null;

            if (!empty($row['B'])) {

                // Jika format numeric (Excel date)
                if (is_numeric($row['B'])) {
                    $transactionDate = Date::excelToDateTimeObject($row['B'])
                        ->format('Y-m-d');
                } 
                // Jika format text
                else {
                    $transactionDate = date('Y-m-d', strtotime($row['B']));
                }
            }

            $dataInsert[] = [
                'Material'        => trim($row['A']),
                'TransactionDate' => $transactionDate,
                'TBBM'            => trim($row['C']),
                'Regional'        => trim($row['D']),
                'SalesDistrict'   => trim($row['E']),
                'NoSPBU'          => trim($row['F']),
                'CustomerGroup'   => trim($row['G']),
                'Kuota'           => (int)$row['H'],
                'Volume'          => (int)$row['I'],
            ];
        }

        if (!empty($dataInsert)) {
            $this->sr->insert_batch($dataInsert);
            $this->session->set_flashdata(
                'success',
                'Import berhasil, total data: ' . count($dataInsert)
            );
        } else {
            $this->session->set_flashdata('error', 'Data kosong atau format tidak sesuai.');
        }

        redirect('Ist/Report/SalesRealization');
    }

    public function update()
	{
		$id = $this->input->post('LedgerNo');

		$data = [
			'Material'        => $this->input->post('Material'),
			'TransactionDate' => $this->input->post('TransactionDate'),
			'TBBM'            => $this->input->post('TBBM'),
			'Regional'        => $this->input->post('Regional'),
			'SalesDistrict'   => $this->input->post('SalesDistrict'),
			'NoSPBU'          => $this->input->post('NoSPBU'),
			'CustomerGroup'   => $this->input->post('CustomerGroup'),
			'Kuota'           => $this->input->post('Kuota'),
			'Volume'          => $this->input->post('Volume'),
		];

		$this->db->where('LedgerNo', $id);
		$this->db->update('SalesRealization', $data);

		$this->session->set_flashdata('success', 'Data berhasil diupdate');
		redirect('Ist/Report/SalesRealization');
	}



    // public function import()
    // {
    //     // Jika tidak ada file
    //     if (empty($_FILES['file']['name'])) {
    //         $this->session->set_flashdata('error', 'File belum dipilih.');
    //         redirect('Ist/Report/SalesRealization');
    //     }

    //     $file = $_FILES['file']['tmp_name'];

    //     // Baca file Excel
    //     $spreadsheet = IOFactory::load($file);
    //     $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    //     $dataInsert = [];

    //     // Mulai dari baris 2 (karena baris 1 biasanya header)
    //     for ($i = 2; $i <= count($sheetData); $i++) {

    //         $row = $sheetData[$i];

    //         // Mapping kolom Excel ke database
    //         $dataInsert[] = [
    //             'Material'              =>$row['A'],       //=> date('Y-m-d', strtotime($row['A'])),
    //             'TransactionDate'       => $row['B'],
    //             'TBBM'                  => $row['C'],
    //             'Regional'              => $row['D'],
    //             'SalesDistrict'         => $row['E'],
    //             'NoSPBU'                => $row['F'],
    //             'CustomerGroup'         => $row['G'],
    //             'Kuota'                 => $row['H'],
    //             'Volume'                => $row['I'],
    //         ];
    //     }

    //     // Insert ke database
    //     if (!empty($dataInsert)) {
    //         $this->sr->insert_batch($dataInsert);
    //         $this->session->set_flashdata('success', 'Import berhasil, total data: ' . count($dataInsert));
    //     } else {
    //         $this->session->set_flashdata('error', 'Data kosong atau format tidak sesuai.');
    //     }

    //     redirect('Ist/Report/SalesRealization');
    // }

    


   
}

/* End of file Controllername.php */
