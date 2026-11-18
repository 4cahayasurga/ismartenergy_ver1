<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DaftarMaterial extends CI_Controller
{
    var $Company;

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->Company = $this->config->item('Company');

        if ($this->session->userdata('PecahToken')->DaftarMaterial != 1) {
            redirect('Welcome');
        }
    }

    public function index()
    {
        $data['judul'] = 'Daftar Barang/Material';
        $data['DetailJob'] = $this->get_ListJob();
        $data['dftrMaterial'] = $this->db->query("SELECT * FROM tbl_DaftarMaterial ORDER BY ID ASC")->result_array();
        $data['kdBarang'] = $this->db->query("SELECT DISTINCT(KdBarang) FROM tbl_DaftarMaterial WHERE KdBarang IS NOT NULL ORDER BY KdBarang ASC")->result_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('report_view/Procurement/daftar_material/halaman_utama');
        $this->load->view('templates/footer');
    }

    public function getData()
    {
        $query = $this->db->query("SELECT * FROM tbl_DaftarMaterial")->result_array();
        echo json_encode(['data' => $query]);
    }

    public function save()
    {
        $kategori = $this->input->post('kategori');
        $subKategori = $this->input->post('subKategori');
        $namaBarang = $this->input->post('namaBarang');
        $kodeBarang = $this->input->post('kodeBarang');


        if ($kategori == '' || $subKategori == '' || $namaBarang == '' || $kodeBarang == '') {
            $data = [
                'status' => 'error',
                'message' => 'Form tidak valid',
            ];
            echo json_encode($data);
            die;
        } else {
            $timeEntry = date('Y-m-d H:i:s');

            $sql = "INSERT INTO tbl_DaftarMaterial (KdBarang, NmBarang, Kategori, SubKategori, TimeEntry) VALUES (?, ?, ?, ?, ?)";
            $this->db->query($sql, [$kodeBarang, $namaBarang, $kategori, $subKategori, $timeEntry]);

            $data = [
                'status' => 'success',
                'message' => 'Form valid',
            ];
            echo json_encode($data);
            die;
        }
    }

    public function update()
    {
        $kodeBarang = $this->input->post('kodeBarang');
        $kodeBarangLama = $this->input->post('kdBarangLama');
        $kategori = $this->input->post('kategori');
        $subKategori = $this->input->post('subKategori');
        $namaBarang = $this->input->post('namaBarang');
        $namaBarangLama = $this->input->post('nmBarangLama');
        $type = $this->input->post('type');

        if ($type == 'first') {
            $data = [
                'KdBarang' => $kodeBarang,
            ];
            $this->db->where('NmBarang', $namaBarangLama);
            $this->db->update('tbl_DaftarMaterial', $data);
            $data = [
                'KdBarang' => $kodeBarang,
                'NmBarang' => $namaBarang,
                'Kategori' => $kategori,
                'SubKategori' => $subKategori,
                'TimeEntry' => date('Y-m-d H:i:s')
            ];
            $this->db->where('KdBarang', $kodeBarang);
            $this->db->update('tbl_DaftarMaterial', $data);
        } else {
            $data = [
                'KdBarang' => $kodeBarang,
                'NmBarang' => $namaBarang,
                'Kategori' => $kategori,
                'SubKategori' => $subKategori,
                'TimeEntry' => date('Y-m-d H:i:s')
            ];
            $this->db->where('KdBarang', $kodeBarangLama);
            $this->db->update('tbl_DaftarMaterial', $data);
        }
        $this->session->set_flashdata('message', 'Data berhasil di update');
        redirect('MyReport/Procurement/DaftarMaterial');
    }

    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $styleTitle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        ];

        $styleHeader = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'indent' => 1,
            ],
            'borders' => [
                'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ];

        $styleLeft = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'indent' => 1,
            ],
            'borders' => [
                'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
        ];

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', 'Daftar Barang/Material');
        $sheet->getStyle('A2')->applyFromArray($styleTitle);
        $sheet->getStyle('A2')->getFont()->setSize(14);

        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Kode Barang');
        $sheet->setCellValue('C4', 'Nama Barang');
        $sheet->setCellValue('D4', 'Kategori');
        $sheet->setCellValue('E4', 'Sub Kategori');

        $sheet->getStyle('A4')->applyFromArray($styleHeader);
        $sheet->getStyle('B4')->applyFromArray($styleHeader);
        $sheet->getStyle('C4')->applyFromArray($styleHeader);
        $sheet->getStyle('D4')->applyFromArray($styleHeader);
        $sheet->getStyle('E4')->applyFromArray($styleHeader);

        $sheet->getStyle('A4:E4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('adb5bd');

        $query = $this->db->query("SELECT * FROM tbl_DaftarMaterial")->result_array();

        $no = 1;
        $numrow = 5;
        foreach ($query as $row) {
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $row['KdBarang']);
            $sheet->setCellValue('C' . $numrow, $row['NmBarang']);
            $sheet->setCellValue('D' . $numrow, $row['Kategori']);
            $sheet->setCellValue('E' . $numrow, $row['SubKategori']);
            $sheet->getStyle('A' . $numrow)->applyFromArray($styleLeft);
            $sheet->getStyle('B' . $numrow)->applyFromArray($styleLeft);
            $sheet->getStyle('C' . $numrow)->applyFromArray($styleLeft);
            $sheet->getStyle('D' . $numrow)->applyFromArray($styleLeft);
            $sheet->getStyle('E' . $numrow)->applyFromArray($styleLeft);

            $no++;
            $numrow++;
        }

        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);


        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        $sheet->setTitle("Analaisa Delta");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="daftarBarangMaterial.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    private function get_ListJob()
    {
        $Company = $this->config->item('Company');
        $query = $this->db->query(" SELECT JobNo,JobNm FROM Job WHERE Company='$Company' ");
        return $query->result_array();
    }

    public function getNmBarang()
    {
        $term = $this->input->post('term');
        $query = $this->db->query("SELECT NmBarang FROM tbl_DaftarMaterial WHERE NmBarang LIKE '%$term%'")->result_array();
        $data = array();
        foreach ($query as $row) {
            $data[] = $row['NmBarang'];
        }
        echo json_encode($data);
    }
}
