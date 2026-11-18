<?php

defined('BASEPATH') or exit('No direct script access allowed');
error_reporting(E_ALL);
use \Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Rkp_pembayaran extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Laporan_model/Model_rkp_pembayaran');
    }

    public function index()
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $data['judul'] = 'Rekap Pembayaran';
        $data['bodyclass'] = 'sidebar-collapse skin-blue';

        $data['tgl_pertama'] = date('Y-m-01');
        $data['tgl_terakhir'] = date('Y-m-t');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('laporan_view/rkp_pembayaran/view_rkp_pembayaran', $data);
        $this->load->view('templates/footer');
    }

    function view_table_rkp_pembayaran()
    {
        if (!$this->input->post()) {
            die;
        }

        $JobNo = $this->input->post('JobNo', true);
        $dari_tgl = $this->input->post('dari_tgl', true);
        $sampai_tgl = $this->input->post('sampai_tgl', true);


        $data = array(
            'JobNo' => $JobNo,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );

        $data_rkp = $this->Model_rkp_pembayaran->dapat_data_rkp_pembayaran($data);
        $data['data_rkp'] = $data_rkp['myarray'];
        $data['Ttl_bayar'] = $data_rkp['TtlBayar'];

        // print_rr($data_rkp);
        // die;



        $this->load->view('laporan_view/rkp_pembayaran/table_rkp_pembayaran', $data);
    }

    function print_rkp_pembayaran($JobNo = '',  $dari_tgl = '', $sampai_tgl = '')
    {
        if ($JobNo == '') {
            redirect('Laporan/Rkp_pembayaran', 'refresh');
            die;
        }

        $dari_tgl = ($dari_tgl == 0) ? '' : $dari_tgl;
        $sampai_tgl = ($dari_tgl == 0) ? '' : $sampai_tgl;

        $query_job = $this->db->query("select JobNm from Job where JobNo='$JobNo'")->row_array();
        $JobNm = $query_job['JobNm'];

        $data = array(
            'JobNo' => $JobNo,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );

        $dataJob = '';
        if ($JobNo == 0) {
            $dataJob = 'Semua Job';
        } else {
            $dataJob = $JobNo . ' - ' . $JobNm;
        }

        $data['dataJob'] = $dataJob;

        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        $data_rkp = $this->Model_rkp_pembayaran->dapat_data_rkp_pembayaran($data);
        $data['data_rkp'] = $data_rkp['myarray'];
        $data['Ttl_bayar'] = $data_rkp['TtlBayar'];

        $this->load->view('laporan_view/rkp_pembayaran/print/print_rkp_pembayaran_content', $data);
    }


   function myexcel_RkpPembayaran($JobNo = '',  $dari_tgl = '', $sampai_tgl = '')
    {
        if ($JobNo == '') {
            die;
        }

        $dari_tgl = ($dari_tgl == 0) ? '' : $dari_tgl;
        $sampai_tgl = ($dari_tgl == 0) ? '' : $sampai_tgl;

        $query_job = $this->db->query("select JobNm from Job where JobNo='$JobNo'")->row_array();
        $JobNm = $query_job['JobNm'];

        $data = array(
            'JobNo' => $JobNo,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );

        $dataJob = '';
        if ($JobNo == 0) {
            $dataJob = 'Semua Job';
        } else {
            $dataJob = $JobNo . ' - ' . $JobNm;
        }

        $data['dataJob'] = $dataJob;

        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $gabung = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        $data_rkp = $this->Model_rkp_pembayaran->dapat_data_rkp_pembayaran($data);
        $data_rkp_arr = $data_rkp['myarray'];
        $Ttl_bayar = $data_rkp['TtlBayar'];


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

        $sheet->setCellValue('A1', "REPORT - REKAP PEMBAYARAN");
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->setCellValue('A2', "Job No");
        $sheet->setCellValue('B2', $dataJob);
        $sheet->mergeCells('B2:E2');
        $sheet->getStyle('A2')->getFont()->setBold(false);

        $sheet->setCellValue('A3', "Tgl. Pembayaran");
        $sheet->setCellValue('B3', date('d-M-Y', strtotime($dari_tgl)) . ' s.d ' . date('d-M-Y', strtotime($sampai_tgl)));
        $sheet->mergeCells('B3:C3');
        $sheet->getStyle('A3')->getFont()->setBold(false);

        $sheet->setCellValue('D3', uang_koma_genap_decimal($Ttl_bayar));
        $sheet->mergeCells('D3:E3');
        $sheet->getStyle('D3')->getFont()->setBold(true);




        $sheet->setCellValue('A5', "No");
        $sheet->setCellValue('B5', "Tanggal");
        $sheet->setCellValue('C5', "Tipe Form");
        $sheet->setCellValue('D5', "No PD");
        $sheet->setCellValue('E5', "Job Name");
        $sheet->setCellValue('F5', "Nama Penerima");
        $sheet->setCellValue('G5', "Rekening Penerima");
        $sheet->setCellValue('H5', "Bank Penerima");
        $sheet->setCellValue('I5', "Nilai Transfer");
        $sheet->setCellValue('J5', "Bank Pengirim");
        $sheet->setCellValue('K5', "No Cek / BG");
        $sheet->setCellValue('L5', "Rekening Pengirim");
        $sheet->setCellValue('M5', "No. Kontrak");
        $sheet->setCellValue('N5', "Keterangan");




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

        foreach ($data_rkp_arr as $mydata) :


            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, date('d-M', strtotime($mydata['TglBayar'])));
            $sheet->setCellValue('C' . $numrow, $mydata['TipeForm']);
            $sheet->setCellValue('D' . $numrow, $mydata['NoPD']);
            $sheet->setCellValue('E' . $numrow, $mydata['JobNm']);
            $sheet->setCellValue('F' . $numrow, $mydata['NamaTerima']);
            $sheet->setCellValue('G' . $numrow, $mydata['NoRekTerima']);
            $sheet->setCellValue('H' . $numrow, $mydata['BankTerima']);
            $sheet->setCellValue('I' . $numrow, uang_koma_genap_decimal($mydata['Amount']));
            $sheet->setCellValue('J' . $numrow, $mydata['BankKirim']);
            $sheet->setCellValue('K' . $numrow, $mydata['JenisTrf']);
            $sheet->setCellValue('L' . $numrow, $mydata['NoRekKirim']);
            $sheet->setCellValue('M' . $numrow, $mydata['NoKO']);
            $sheet->setCellValue('N' . $numrow, $mydata['Keterangan']);


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



        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(20); 
        $sheet->getColumnDimension('E')->setWidth(30); 
        $sheet->getColumnDimension('F')->setWidth(30); 
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30); 
        $sheet->getColumnDimension('I')->setWidth(30); 
        $sheet->getColumnDimension('J')->setWidth(30); 
        $sheet->getColumnDimension('K')->setWidth(30); 
        $sheet->getColumnDimension('L')->setWidth(30); 
        $sheet->getColumnDimension('M')->setWidth(30); 
        $sheet->getColumnDimension('N')->setWidth(30); 


        $sheet->getDefaultRowDimension()->setRowHeight(-1);


        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


        $sheet->setTitle("REKAP PEMBAYARAN");


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="RekapPembayaran-' . date('ymdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }


}

/* End of file Controllername.php */
