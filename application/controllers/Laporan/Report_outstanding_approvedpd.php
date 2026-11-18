<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \Mpdf\Mpdf;

class Report_outstanding_approvedpd extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        
        redirect('RptOutstandingPC','refresh');
        is_login();

        $this->load->model('Laporan_model/M_report_outstanding_approvedpd');
    }

    public function index()
    {
        if (!$this->db->table_exists('Tbl_report_outstan_approved_pd')) {
            echo 'tidak terdapat table report outstanding';
            die;
        }

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
        if (!$this->db->table_exists('Tbl_report_outstan_approved_pd')) {
            echo 'tidak terdapat table report outstanding';
            die;
        }

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


        $this->load->view('laporan_view/report_outstanding_approvedpd/table_report_outstanding_approvedpd', $data);
    }

    function dapat_list_outstanding()
    {
        if (!$this->db->table_exists('Tbl_report_outstan_approved_pd')) {
            echo 'tidak terdapat table report outstanding';
            die;
        }

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


        header('Content-Type: application/json');
        echo $this->M_report_outstanding_approvedpd->dapat_list_outstanding_data_json($data);
    }

    function rpt_outstanding_pdf($By_pilihan = '', $Type_pilihan = '', $dari_tgl = '', $sampai_tgl = '', $pilih = '')
    {

        if (!$this->db->table_exists('Tbl_report_outstan_approved_pd')) {
            echo 'tidak terdapat table report outstanding';
            die;
        }

        if ($By_pilihan == '' || $Type_pilihan == '') {
            redirect('Laporan/Report_outstanding_approvedpd', 'refresh');
            die;
        }


        $where = array(
            'By_pilihan' => $By_pilihan,
            'Type_pilihan' => $Type_pilihan,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );

        $ubah = explode('-', $pilih);
        $elements = array();
        foreach ($ubah as $ubah_dua) {
            $elements[] = $ubah_dua;
        }

        $LedgerNo = implode(',', $elements);

        $data['By_pilihan'] = $By_pilihan;
        $data['Type_pilihan'] = $Type_pilihan;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;


        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;


        $data['mydata'] = $this->M_report_outstanding_approvedpd->dapat_data_outstanding_baru($where, $LedgerNo);
        $data['subtotal'] = $this->M_report_outstanding_approvedpd->sub_total_report_baru($LedgerNo);
        // print_rr($data);
        // die;
        $this->load->view('laporan_view/report_outstanding_approvedpd/print/pdf_outstanding_content', $data);
    }

    function test_data()
    {

        $sub_total = $this->M_report_outstanding_approvedpd->sub_total_report();
        $array = array(
            'terbilang' => '*Terbilang : ' . terbilang($sub_total),
            'angka' => $sub_total
        );
        echo json_encode($array);
        // echo $this->M_report_outstanding_approvedpd->sub_total_report();
    }

    function export_excel_rpt_outstanding($By_pilihan = '', $Type_pilihan = '', $dari_tgl = '', $sampai_tgl = '', $pilih = '')
    {

        $where = array(
            'By_pilihan' => $By_pilihan,
            'Type_pilihan' => $Type_pilihan,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );

        $ubah = explode('-', $pilih);
        $elements = array();
        foreach ($ubah as $ubah_dua) {
            $elements[] = $ubah_dua;
        }

        $LedgerNo = implode(',', $elements);

        $query = $this->M_report_outstanding_approvedpd->dapat_data_outstanding_for_excel($where, $LedgerNo);


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

        $sheet->setCellValue('A1', "REPORT OUTSTANDING / APPROVED PD");
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->setCellValue('A2', "By");
        $sheet->setCellValue('B2', $By_pilihan);
        $sheet->mergeCells('B2:C2');
        $sheet->getStyle('A2')->getFont()->setBold(false);

        $sheet->setCellValue('A3', "Type");
        $sheet->setCellValue('B3', $Type_pilihan);
        $sheet->mergeCells('B3:C3');
        $sheet->getStyle('A3')->getFont()->setBold(false);


        $sheet->setCellValue('A4', "Periode");
        $sheet->setCellValue('B4', tgl_baru($dari_tgl) . ' s.d ' . tgl_baru($sampai_tgl));
        $sheet->mergeCells('B4:C4');
        $sheet->getStyle('A4')->getFont()->setBold(false);


        $sheet->setCellValue('A5', "No");
        $sheet->setCellValue('B5', "Job No");
        $sheet->setCellValue('C5', "Job Name");
        $sheet->setCellValue('D5', "No PD");
        $sheet->setCellValue('E5', "Alokasi");
        $sheet->setCellValue('F5', "Tipe Form");
        $sheet->setCellValue('G5', "Atas Nama");
        $sheet->setCellValue('H5', "Rekening Penerima");
        $sheet->setCellValue('I5', "No. KO");
        $sheet->setCellValue('J5', "Total PD (Rp)");
        $sheet->setCellValue('K5', "Tgl PD");
        $sheet->setCellValue('L5', "Jth Tempo");
        $sheet->setCellValue('M5', "Deskripsi");
        $sheet->setCellValue('N5', "Approved By");




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


        $no = 1;
        $numrow = 6;

        $sum_total = 0;
        foreach ($query as $data) :

            $sum_total += $data->TotalPD;

            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $data->NoPD);
            $sheet->setCellValue('C' . $numrow, $data->JobNm);
            $sheet->setCellValue('D' . $numrow, $data->NoPD);
            $sheet->setCellValue('E' . $numrow, $data->Alokasi);
            $sheet->setCellValue('F' . $numrow, $data->TipeForm);
            $sheet->setCellValue('G' . $numrow, $data->AtasNama);
            $sheet->setCellValue('H' . $numrow, $data->NoRek);
            $sheet->setCellValue('I' . $numrow, $data->NoKO);
            $sheet->setCellValue('J' . $numrow, new_decimal($data->TotalPD));
            $sheet->setCellValue('K' . $numrow, tgl_baru($data->TglPD));
            $sheet->setCellValue('L' . $numrow, tgl_baru($data->DueDate));
            $sheet->setCellValue('M' . $numrow, $data->Deskripsi);
            $sheet->setCellValue('N' . $numrow, $data->ApprovedBy);


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

        $sheet->setCellValue('A' . $numrow, "Sub Total");
        $sheet->mergeCells('A' . $numrow . ':I' . $numrow);
        $sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya);

        $sheet->setCellValue('J' . $numrow, new_decimal($sum_total));
        // $sheet->mergeCells('J'.$numrow);
        $sheet->getStyle('J' . $numrow)->applyFromArray($new_gaya);


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

        // Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $sheet->setTitle("Report OutStanding");

        // Proses file excel
        // $tgl_hari_ini = date('ymdHis');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="report_outstanding-' . date('ymdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    function export_coba()
    {
        $query = $this->db->query("SELECT TOP 2 NoPD,JobNo,TglPD,Alokasi,NoKO FROM PdHdr ")->result();
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
            ]
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

        $sheet->setCellValue('A1', "NO");
        $sheet->setCellValue('B1', "NAMA ASSET");
        // $sheet->setCellValue('C1', "KODE ASSET");
        // $sheet->setCellValue('D1', "PERIODE SEWA AWAL");
        // $sheet->setCellValue('E1', "PERIODE SEWA AKHIR");
        // $sheet->setCellValue('F1', "MASA SEWA");
        // $sheet->setCellValue('G1', "SECURITY DEPOSIT");
        // $sheet->setCellValue('H1', "HARGA SEWA");
        // $sheet->setCellValue('I1', "FEE AGEN");
        // $sheet->setCellValue('J1', "BIAYA PERBAIKAN");
        // $sheet->setCellValue('K1', "NOMINAL TRANSFER");
        // $sheet->setCellValue('L1', "TGL TRF");
        // $sheet->setCellValue('M1', "TRANSFER KE");
        // $sheet->setCellValue('N1', "KETERANGAN");

        $sheet->getStyle('A1:B1')->getFont()->setBold(true);

        $sheet->getStyle('A1')->applyFromArray($style_col);
        $sheet->getStyle('B1')->applyFromArray($style_col);
        // $sheet->getStyle('C1')->applyFromArray($style_col);
        // $sheet->getStyle('D1')->applyFromArray($style_col);
        // $sheet->getStyle('E1')->applyFromArray($style_col);
        // $sheet->getStyle('F1')->applyFromArray($style_col);
        // $sheet->getStyle('G1')->applyFromArray($style_col);
        // $sheet->getStyle('H1')->applyFromArray($style_col);
        // $sheet->getStyle('I1')->applyFromArray($style_col);
        // $sheet->getStyle('J1')->applyFromArray($style_col);
        // $sheet->getStyle('K1')->applyFromArray($style_col);
        // $sheet->getStyle('L1')->applyFromArray($style_col);
        // $sheet->getStyle('M1')->applyFromArray($style_col);
        // $sheet->getStyle('N1')->applyFromArray($style_col);


        $no = 1;
        $row = 2;

        foreach ($query as $data) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $data->NoPD);
            // $sheet->setCellValue('C' . $row, $data_asset->JobNo);
            // $sheet->setCellValue('D' . $row, $data_asset->PeriodeSewa1);
            // $sheet->setCellValue('E' . $row, $data_asset->PeriodeSewa2);
            // $sheet->setCellValue('F' . $row, $data_asset->MasaSewa);
            // $sheet->setCellValue('G' . $row, UbahNol($data_asset->SecurityDeposit));
            // $sheet->setCellValue('H' . $row, Currency($data_asset->Currency) . ' ' . UbahNol($data_asset->HargaSewa));
            // $sheet->setCellValue('I' . $row, UbahNol($data_asset->feeAgen));
            // $sheet->setCellValue('J' . $row, UbahNol($data_asset->BiayaPerbaikan));
            // $sheet->setCellValue('K' . $row, UbahNol($data_asset->NominalTransfer));
            // $sheet->setCellValue('L' . $row, $data_asset->TglTransfer);
            // $sheet->setCellValue('M' . $row, $data_asset->NamaBank);
            // $sheet->setCellValue('N' . $row, $data_asset->KeteranganSW);



            $sheet->getStyle('A' . $row)->applyFromArray($style_row);
            $sheet->getStyle('B' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('C' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('D' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('E' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('F' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('G' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('H' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('I' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('J' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('K' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('L' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('M' . $row)->applyFromArray($style_row);
            // $sheet->getStyle('N' . $row)->applyFromArray($style_row);

            $no++;
            $row++;
        }

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        $sheet->setTitle("dddd");

        $tgl_hari_ini = date('ymdHis');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="vvv.xlsx"');

        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    function hapus_pilih($By_pilihan = '', $Type_pilihan = '', $dari_tgl = '', $sampai_tgl = '', $pilih = '')
    {


        if (!$this->db->table_exists('Tbl_report_outstan_approved_pd')) {
            echo 'tidak terdapat table report outstanding';
            die;
        }

        if ($By_pilihan == '' || $Type_pilihan == '') {
            redirect('Laporan/Report_outstanding_approvedpd', 'refresh');
            die;
        }

        $ubah = explode('-', $pilih);
        $elements = array();
        foreach ($ubah as $data) {
            $elements[] = $data;
        }
        $LedgerNo = implode(',', $elements);


        $where = array(
            'By_pilihan' => $By_pilihan,
            'Type_pilihan' => $Type_pilihan,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
            'LedgerNo' => $LedgerNo,
        );

        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $array_baru['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        $array_baru['mydata'] = $this->M_report_outstanding_approvedpd->dapat_data_outstanding_baru($where, $LedgerNo);
        $array_baru['subtotal'] = $this->M_report_outstanding_approvedpd->sub_total_report();
        $this->load->view('laporan_view/report_outstanding_approvedpd/print/pdf_outstanding_content', $array_baru);

        // print_rr($array_baru);
        // die;

        // $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        // $tglSkrg = date('d-M-Y');
        // $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        // $where = array(
        //     'By_pilihan' => $By_pilihan,
        //     'Type_pilihan' => $Type_pilihan,
        //     'dari_tgl' => $dari_tgl,
        //     'sampai_tgl' => $sampai_tgl,
        // );

        // $data['LedgerNo'] = $LedgerNo;


        // $data['mydata'] = $this->M_report_outstanding_approvedpd->dapat_data_outstanding_baru($where,$LedgerNo);


        // $data['subtotal'] = $this->M_report_outstanding_approvedpd->sub_total_report();
        // print_rr($data);
        // $this->load->view('laporan_view/report_outstanding_approvedpd/print/pdf_outstanding_content', $data);


        // echo $pilih;

        // $ubah = explode('-',$pilih);
        // $apa = array();
        // foreach($ubah as $data){
        //     $apa = implode(',',$data);
        // }

        // echo $apa;
        // print_rr($ubah);
        // echo $ubah[1];
        // $ubah_pilih = explode('-',$)
        //  if (!$this->input->post()) {
        //     exit;
        // }
        // $LedgerNo = $this->input->post('ids');
        // $mydata
        // $this->db->where_in('LedgerNo', explode("-", $LedgerNo));

        // echo $LedgerNo;
        // die;
        // die;

        // $By_pilihan = $this->input->post('By_pilihan', true);
        // $Type_pilihan = $this->input->post('Type_pilihan', true);
        // $dari_tgl = $this->input->post('dari_tgl', true);
        // $sampai_tgl = $this->input->post('sampai_tgl', true);

        // $where = array(
        //     'By_pilihan' => $By_pilihan,
        //     'Type_pilihan' => $Type_pilihan,
        //     'dari_tgl' => $dari_tgl,
        //     'sampai_tgl' => $sampai_tgl,
        // );





        // $data['mydata'] = $this->M_report_outstanding_approvedpd->dapat_data_outstanding_baru($where,$LedgerNo);
        // $data['subtotal'] = $this->M_report_outstanding_approvedpd->sub_total_report();
        // $this->load->view('laporan_view/report_outstanding_approvedpd/print/pdf_outstanding_content', $data);

    }
}

/* End of file Report_outstanding_approvedpd.php */
