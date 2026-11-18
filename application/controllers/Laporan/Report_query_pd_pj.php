<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \Mpdf\Mpdf;

class Report_query_pd_pj extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        is_login();
        redirect('RptQuery_PDPJ','refresh');
        $this->load->model('Laporan_model/M_report_querypdpj');
        
    }

    function index()
    {
        // echo $this->M_report_querypdpj->insert_ke_tbl_querypdpj('21570', 'B');

        // die;

        if (!$this->db->table_exists('Tbl_report_querypdpj')) {
            echo 'tidak terdapat table report query pd/pj';
            die;
        }

        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $data['judul'] = 'Report Query PD / PJ';
        $data['bodyclass'] = 'sidebar-collapse skin-default';

        $data['tgl_pertama'] = date('Y-m-01');
        $data['tgl_terakhir'] = date('Y-m-t');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('laporan_view/report_query_pdpj/view_report_querypdpj', $data);
        $this->load->view('templates/footer');
    }

    function dapat_data_querypdpj()
    {
        if (!$this->input->post()) {
            die;
        }

        $JobNo = $this->input->post('JobNo', true);
        $Alokasi = $this->input->post('Alokasi', true);

        $data = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi
        );
        // echo 'ok';

        $this->load->view('laporan_view/report_query_pdpj/table_querypdpj', $data);
    }

    function get_data_query_pdpj()
    {
        if (!$this->input->post()) {
            die;
        }
        $JobNo = $this->input->post('JobNo', true);
        $Alokasi = $this->input->post('Alokasi', true);

        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;

        header('Content-Type: application/json');
        echo $this->M_report_querypdpj->get_data_query_pdpj_json($JobNo, $Alokasi);
    }

    function rpt_query_pdpj_pdf($JobNo = '', $Alokasi = '')
    {
        if (!$this->db->table_exists('Tbl_report_querypdpj')) {
            echo 'tidak terdapat table report outstanding';
            die;
        }

        if ($JobNo == '' || $Alokasi == '') {
            redirect('Laporan/Report_query_pd_pj', 'refresh');
        }

        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;


        $dapat_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'  ")->row_array();
        $dapat_alokasi = $this->db->query("SELECT Keterangan FROM Alokasi WHERE Alokasi='$Alokasi'  ")->row_array();
        $data['JobNm'] = $dapat_job['JobNm'];
        $data['Keterangan'] = $dapat_alokasi['Keterangan'];
        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;

        $data['data_query_pdpj'] = $this->M_report_querypdpj->get_pdf_query_pdpj($JobNo, $Alokasi);

        $this->load->view('laporan_view/report_query_pdpj/print/pdf_querypdpj_content', $data);
    }

    function print_rpt_querypdpj($JobNo = '', $Alokasi = '', $pilih = '')
    {
        if (!$this->db->table_exists('Tbl_report_querypdpj')) {
            echo 'tidak terdapat table report outstanding';
            die;
        }

        if ($JobNo == '' || $Alokasi == '') {
            redirect('Laporan/Report_query_pd_pj', 'refresh');
        }


        $ubah = explode('-', $pilih);
        $elements = array();
        foreach ($ubah as $ubah_dua) {
            $elements[] = $ubah_dua;
        }
        $LedgerNo = implode(',', $elements);

        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;


        $dapat_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'  ")->row_array();
        $dapat_alokasi = $this->db->query("SELECT Keterangan FROM Alokasi WHERE Alokasi='$Alokasi'  ")->row_array();
        $data['JobNm'] = $dapat_job['JobNm'];
        $data['Keterangan'] = $dapat_alokasi['Keterangan'];
        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $data['data_query_pdpj'] = $this->M_report_querypdpj->get_data_untuk_pdf($JobNo, $Alokasi, $LedgerNo);


        $this->load->view('laporan_view/report_query_pdpj/print/pdf_querypdpj_content', $data);
    }

    function exp_excel_rpt_querypdpj($JobNo = '', $Alokasi = '', $pilih = '')
    {
        if (!$this->db->table_exists('Tbl_report_querypdpj')) {
            echo 'tidak terdapat table report outstanding';
            die;
        }

        if ($JobNo == '' || $Alokasi == '') {
            redirect('Laporan/Report_query_pd_pj', 'refresh');
        }


        $ubah = explode('-', $pilih);
        $elements = array();
        foreach ($ubah as $ubah_dua) {
            $elements[] = $ubah_dua;
        }
        $LedgerNo = implode(',', $elements);

        $dapat_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'  ")->row_array();
        $dapat_alokasi = $this->db->query("SELECT Keterangan FROM Alokasi WHERE Alokasi='$Alokasi'  ")->row_array();
        $JobNm = $dapat_job['JobNm'];
        $Keterangan = $dapat_alokasi['Keterangan'];

        $data_query_pdpj = $this->M_report_querypdpj->get_data_untuk_pdf($JobNo, $Alokasi, $LedgerNo);

        // print_rr($data_query_pdpj);
        // exit;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $style_col = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
        ];

        $style_row = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];

        $spreadsheet->getActiveSheet()->getStyle('A5:N5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('7f8c8d');

        $sheet->setCellValue('A1', "REPORT QUERY PD/PJ");
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->setCellValue('A2', "Job No");
        $sheet->setCellValue('B2', $JobNo . ' - ' . $JobNm);
        $sheet->mergeCells('B2:C2');
        $sheet->getStyle('A2')->getFont()->setBold(false);

        $sheet->setCellValue('A3', "Alokasi");
        $sheet->setCellValue('B3', $Alokasi . ' - ' . $Keterangan);
        $sheet->mergeCells('B3:C3');
        $sheet->getStyle('A3')->getFont()->setBold(false);


        $sheet->setCellValue('A5', "No");
        $sheet->setCellValue('B5', "Job No");
        $sheet->setCellValue('C5', "No PD");
        $sheet->setCellValue('D5', "Tgl PD");
        $sheet->setCellValue('E5', "No Ref Lap");
        $sheet->setCellValue('F5', "Alokasi");
        $sheet->setCellValue('G5', "Tipe Form");
        $sheet->setCellValue('H5', "Deskripsi");
        $sheet->setCellValue('I5', "No. KO");
        $sheet->setCellValue('J5', "Total KO (Rp)");
        $sheet->setCellValue('K5', "No Tagihan");
        $sheet->setCellValue('L5', "Rekening Penerima");
        $sheet->setCellValue('M5', "Atas Nama");
        $sheet->setCellValue('N5', "Total PD (Rp)");
        $sheet->setCellValue('O5', "Tgl Bayar");
        $sheet->setCellValue('P5', "Via");
        $sheet->setCellValue('Q5', "Total Pembayaran");
        $sheet->setCellValue('R5', "Rekening Pengirim");
        $sheet->setCellValue('S5', "No PJ");
        $sheet->setCellValue('T5', "Tgl PJ");
        $sheet->setCellValue('U5', "Total PJ");
        $sheet->setCellValue('V5', "Saldo (Rp)");

        $sheet->getStyle('A5')->applyFromArray($style_col);
        $sheet->getStyle('B5')->applyFromArray($style_col);
        $sheet->getStyle('C5')->applyFromArray($style_col);
        $sheet->getStyle('D5')->applyFromArray($style_col);
        $sheet->getStyle('E5')->applyFromArray($style_col);
        $sheet->getStyle('F5')->applyFromArray($style_col);
        $sheet->getStyle('G5')->applyFromArray($style_col);
        $sheet->getStyle('H5')->applyFromArray($style_col);
        $sheet->getStyle('I5')->applyFromArray($style_col);
        $sheet->getStyle('J5')->applyFromArray($style_col);
        $sheet->getStyle('K5')->applyFromArray($style_col);
        $sheet->getStyle('L5')->applyFromArray($style_col);
        $sheet->getStyle('M5')->applyFromArray($style_col);
        $sheet->getStyle('N5')->applyFromArray($style_col);
        $sheet->getStyle('O5')->applyFromArray($style_col);
        $sheet->getStyle('P5')->applyFromArray($style_col);
        $sheet->getStyle('Q5')->applyFromArray($style_col);
        $sheet->getStyle('R5')->applyFromArray($style_col);
        $sheet->getStyle('S5')->applyFromArray($style_col);
        $sheet->getStyle('T5')->applyFromArray($style_col);
        $sheet->getStyle('U5')->applyFromArray($style_col);
        $sheet->getStyle('V5')->applyFromArray($style_col);

        $no = 1;
        $numrow = 6;

        $sum_total_pd = 0;
        $sum_total_pembayaran = 0;
        $sum_total_pj = 0;


        foreach ($data_query_pdpj as $data) :

            $sum_total_pd += $data['TotalPD'];
            $sum_total_pembayaran += $data['BLEAmount'];
            $sum_total_pj += $data['TotalPJ'];

            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data['JobNo']);
            $sheet->setCellValue('C' . $numrow, $data['NoPD']);
            $sheet->setCellValue('D' . $numrow, tgl_baru($data['TglPD']));
            $sheet->setCellValue('E' . $numrow, $data['NoRef']);
            $sheet->setCellValue('F' . $numrow, $data['Alokasi']);
            $sheet->setCellValue('G' . $numrow, $data['TipeForm']);
            $sheet->setCellValue('H' . $numrow, $data['Deskripsi']);
            $sheet->setCellValue('I' . $numrow, $data['NoKO']);
            $sheet->setCellValue('J' . $numrow, 0);
            $sheet->setCellValue('K' . $numrow, $data['NoTagihan']);
            $sheet->setCellValue('L' . $numrow, $data['NoRek']);
            $sheet->setCellValue('M' . $numrow, $data['AtasNama']);
            $sheet->setCellValue('N' . $numrow, number_format($data['TotalPD']));
            $sheet->setCellValue('O' . $numrow, tgl_baru($data['TglBayar']));
            $sheet->setCellValue('P' . $numrow, $data['JenisTrf']);
            $sheet->setCellValue('Q' . $numrow, number_format($data['BLEAmount']));
            $sheet->setCellValue('R' . $numrow, $data['RekId']);
            $sheet->setCellValue('S' . $numrow, $data['NoPJ']);
            $sheet->setCellValue('T' . $numrow, tgl_baru($data['TglPJ']));
            $sheet->setCellValue('U' . $numrow, number_format($data['TotalPJ']));
            $sheet->setCellValue('V' . $numrow, 0);

            $sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('H' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('I' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('J' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('K' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('L' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('M' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('N' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('O' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('P' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('Q' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('R' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('S' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('T' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('U' . $numrow)->applyFromArray($style_row);
            $sheet->getStyle('V' . $numrow)->applyFromArray($style_row);

            $no++;
            $numrow++;


        endforeach;

        $new_gaya = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        ];


        $sheet->setCellValue('A' . $numrow, "Sub Total: " . new_decimal($sum_total_pd));
        $sheet->mergeCells('A' . $numrow . ':N' . $numrow);
        $sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya);

        $sheet->setCellValue('O' . $numrow, "Sub Total: " . new_decimal($sum_total_pembayaran));
        $sheet->mergeCells('O' . $numrow . ':Q' . $numrow);
        $sheet->getStyle('O' . $numrow)->applyFromArray($new_gaya);

        $sheet->setCellValue('R' . $numrow, "Sub Total: " . new_decimal($sum_total_pj));
        $sheet->mergeCells('R' . $numrow . ':U' . $numrow);
        $sheet->getStyle('R' . $numrow)->applyFromArray($new_gaya);

        // $sheet->setCellValue('J' . $numrow, new_decimal($sum_total_pd));
        // $sheet->getStyle('J' . $numrow)->applyFromArray($new_gaya);




        $sheet->getColumnDimension('A')->setWidth(5); // Set width kolom A
        $sheet->getColumnDimension('B')->setWidth(15); // Set width kolom B
        $sheet->getColumnDimension('C')->setWidth(25); // Set width kolom C
        $sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D
        $sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('F')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('G')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('H')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('I')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('J')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('K')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('L')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('M')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('N')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('O')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('P')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('Q')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('R')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('S')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('T')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('U')->setWidth(30); // Set width kolom E
        $sheet->getColumnDimension('V')->setWidth(30); // Set width kolom E


        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        $sheet->setTitle("Rpt-QUERY-PDPJ");

        // Proses file excel
        // $tgl_hari_ini = date('ymdHis');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="report_query_pdpj-' . date('ymdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    // function rpt_querypdpj_pdf($JobNo = '', $Alokasi = '')
    // {
    //     if ($JobNo == '' || $Alokasi == '') {
    //         redirect('Laporan/Report_query_pdpj', 'refresh');
    //         die;
    //     }

    //     $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
    //     $tglSkrg = date('d-M-Y');
    //     $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;


    //     $data['JobGabung'] = 'All JOB';
    //     $data['AlokasiGabung'] = 'ALL ALOKASI';
    //     $data['data_querypdpj'] = $this->M_report_querypdpj->get_data_pdpj($JobNo, $Alokasi);

    //     $this->load->view('laporan_view/report_query_pdpj/print/querypdpj_content', $data);
    // }


}

/* End of file Controllername.php */
