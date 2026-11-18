<?php

defined('BASEPATH') or exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RptPenyerapanRAP extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Laporan_model/Model_RptPenyerapanRAP');
    }

    public function index()
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $data['judul'] = 'REPORT PENYERAPAN RAP';
        $data['bodyclass'] = 'sidebar-collapse skin-blue-light';

        $data['tgl_pertama'] = date('Y-m-01');
        $data['tgl_terakhir'] = date('Y-m-t');

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('laporan_view/penyerapan_rap/view_penyerapan_rap', $data);
        $this->load->view('templates/footer');
    }

    function view_penyerapan_rap()
    {
        if (!$this->input->post()) {
            die;
        }

        $this->session->unset_userdata('session_queryPenyerapanRAP');

        $JobNo = $this->input->post('JobNo', true);
        $Alokasi = $this->input->post('Alokasi', true);
        $dari_tgl = $this->input->post('dari_tgl', true);
        $sampai_tgl = $this->input->post('sampai_tgl', true);
        $isChecked = $this->input->post('isChecked', true);

        $query = $this->db->query("SELECT
                r.KdRAP,
                r.Uraian,
                r.Uom,
                r.Vol,
                r.HrgSatuan,
                r.Tipe,
                r.Vol * r.HrgSatuan AS Total,
                COALESCE(SUM(t1.PjHrgSatuan),0) AS SudahTerserap
            FROM
                RAP r
            JOIN
                PdDtl t1 ON r.KdRAP = t1.KdRAP
            JOIN
                PdHdr t3 ON t1.NoPD = t3.NoPD
            WHERE
                r.JobNo = '$JobNo' AND r.Alokasi = '$Alokasi'
                AND t1.NoPD LIKE '%$JobNo%' AND t1.KdRAP = r.KdRAP
                AND t3.TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl'
                AND t3.JobNo = '$JobNo'
            GROUP BY
                r.KdRAP, r.Uraian, r.Uom, r.Vol, r.HrgSatuan, r.Tipe, r.Vol * r.HrgSatuan, r.NoUrut
            ORDER BY
                r.NoUrut
        ")->result();

        $no = 1;
        $array = [];
        foreach ($query as $row) {
            $item = [
                "No" => $no++,
                "KdRAP" => $row->KdRAP,
                "Uraian" => $row->Uraian,
                "Uom" => $row->Uom,
                "Vol" => $row->Vol,
                "Tipe" => $row->Tipe,
                "HrgSatuan" => $row->HrgSatuan,
                "Total" => $row->Total,
                "SudahTerserap" => $row->SudahTerserap,
                "detailBarang" => [],
            ];

            $getDetail = $this->db->query("SELECT * FROM PdDtl WHERE KdRAP='$row->KdRAP' AND NoPD LIKE '%$JobNo%'")->result();

            if (count($getDetail) >= 1) {
                foreach ($getDetail as $row2) {
                    $item['detailBarang'][] = [
                        "KdRAP" => $row2->KdRAP,
                        "Uraian" => $row2->Uraian,
                        "NoPJ" => $row2->NoPJ,
                        "PjHrgSatuan" => $row2->PjHrgSatuan
                    ];
                }
            }
            $array[] = $item;
        }


        /**
         * crated at 05-April-2024
         * tes query baru
         */

        // $query = $this->db->query(
        //     "SELECT KdRAP, Uraian, Tipe, Uom, Vol, HrgSatuan
        //     FROM RAP
        //     WHERE JobNo = '$JobNo'
        //     ORDER BY KdRAP"
        // )->result();

        // $no = 1;
        // $array = [];
        // foreach ($query as $row) {
        //     $item = [
        //         "No" => $no++,
        //         "KdRAP" => $row->KdRAP,
        //         "Uraian" => $row->Uraian,
        //         "Uom" => $row->Uom,
        //         "Vol" => $row->Vol,
        //         "Tipe" => $row->Tipe,
        //         "HrgSatuan" => $row->HrgSatuan,
        //         "detailBarang" => [],
        //     ];

        //     $getDetail = $this->db->query("SELECT * FROM PdDtl t1
        //         JOIN PdHdr t2 ON t1.NoPD = t2.NoPD
        //         WHERE 
        //         KdRAP='$row->KdRAP' AND t1.NoPD LIKE '$JobNo'
        //         AND t2.TglPD BETWEEN '$dari_tgl' AND '$sampai_tgl'
        //         AND t2.JobNo = '$JobNo'")->result();

        //     // if(count($getDetail) >= 1){
        //     //     foreach($getDetail as $row2){
        //     //         $item['detailBarang'] = [

        //     //         ]
        //     //     }
        //     // }



        //     $array[] = $item;
        // }

        /**
         * crated at 05-April-2024
         * tes query baru
         */

        $data = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl
        );

        $this->session->set_userdata('session_queryPenyerapanRAP', $array);

        $data['dataRAP'] = $array;
        $data['checked'] = $isChecked;
        $this->load->view('laporan_view/penyerapan_rap/table_penyerapan_rap', $data);
    }

    function pdf_rap($JobNo = '', $Alokasi = '', $dari_tgl = '', $sampai_tgl = '')
    {

        if ($JobNo == '' or $Alokasi == '' or $dari_tgl == '' or $sampai_tgl == '') {
            redirect('Report/MenuRptPDPJ', 'refresh');
        }
        // $query_RAP = $this->db->query("SELECT * FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut")->result_array();
        $query_RAP = $this->session->userdata('session_queryPenyerapanRAP');

        $data = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi,
            'dari_tgl' => $dari_tgl,
            'sampai_tgl' => $sampai_tgl,
        );



        $query_job = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo'")->row_array();
        $JobNm = $query_job['JobNm'];

        $query_alo = $this->db->query("select  Alokasi,Keterangan from Alokasi where Alokasi='$Alokasi'")->row_array();
        $alo_gabung = $query_alo['Alokasi'] . ' - ' . $query_alo['Keterangan'];

        $data['JobNo'] = $JobNo;
        $data['JobNm'] = $JobNm;
        $data['Alokasi'] = $Alokasi;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;
        $data['alo_gabung'] = $alo_gabung;
        $data['dataRAP'] = $query_RAP;

        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        $this->load->view('laporan_view/penyerapan_rap/pdf_rap/pdf_rap_tengah', $data);

        // print_rr($data);


    }

    public function exportExcel($JobNo = '', $Alokasi = '', $dari_tgl = '', $sampai_tgl = '', $isChecked = '')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $dataRAP = $this->session->userdata('session_queryPenyerapanRAP');

        $styleHeader = [
            'font' => ['bold' => true],
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

        $styleCenter = [
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
        $styleRight = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
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

        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'REPORT PENYERAPAN RAP');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        ]);
        $sheet->getStyle('A1')->getFont()->setSize('14');
        $sheet->mergeCells('A2:B2');
        $sheet->setCellValue('A2', 'Job No');
        $sheet->setCellValue('C2', ': 21570 - DI GUMBASA');
        $sheet->getStyle('C2')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        ]);
        $sheet->mergeCells('A3:B3');
        $sheet->setCellValue('A3', 'Alokasi');
        $sheet->setCellValue('C3', ': B - FISIK');
        $sheet->getStyle('C3')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        ]);
        $sheet->mergeCells('A4:B4');
        $sheet->setCellValue('A4', 'Periode');
        $sheet->setCellValue('C4', ': 01-April-2023 s/d 01-April-2024');
        $sheet->getStyle('C4')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        ]);


        // set header table
        $sheet->setCellValue('A7', 'No');
        $sheet->setCellValue('B7', 'Kode RAP');
        $sheet->setCellValue('C7', 'Uraian');
        $sheet->setCellValue('D7', 'Uom');
        $sheet->setCellValue('E7', 'Vol');
        $sheet->setCellValue('F7', 'Harga Satuan');
        $sheet->setCellValue('G7', 'Total');
        $sheet->setCellValue('H7', 'Sudah Terserap');
        $sheet->setCellValue('I7', '%');
        $sheet->setCellValue('J7', 'Sisa RAP');


        $sheet->getStyle('A7')->applyFromArray($styleHeader);
        $sheet->getStyle('B7')->applyFromArray($styleHeader);
        $sheet->getStyle('C7')->applyFromArray($styleHeader);
        $sheet->getStyle('D7')->applyFromArray($styleHeader);
        $sheet->getStyle('E7')->applyFromArray($styleHeader);
        $sheet->getStyle('F7')->applyFromArray($styleHeader);
        $sheet->getStyle('G7')->applyFromArray($styleHeader);
        $sheet->getStyle('H7')->applyFromArray($styleHeader);
        $sheet->getStyle('I7')->applyFromArray($styleHeader);
        $sheet->getStyle('J7')->applyFromArray($styleHeader);

        $sheet->getStyle('A7:J7')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('74c0fc');


        $no = 1;
        $numrow = 8;
        $sumHrgSatuan = 0;
        $sumTotal = 0;
        $sumSdhTerserap = 0;
        $sumSisaRAP = 0;
        foreach ($dataRAP as $row) {
            $percentage = ($row['Total'] != 0) ? ($row['SudahTerserap'] / $row['Total']) * 100 : 0;
            $sisaRAP = $row['Total'] - $row['SudahTerserap'];

            $sumHrgSatuan += $row['HrgSatuan'];
            $sumTotal += $row['Total'];
            $sumSdhTerserap += $row['SudahTerserap'];
            $sumSisaRAP += $sisaRAP;

            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $row['KdRAP']);
            $sheet->setCellValue('C' . $numrow, $row['Uraian']);
            $sheet->setCellValue('D' . $numrow, $row['Uom']);
            $sheet->setCellValue('E' . $numrow, $row['Vol']);
            $sheet->setCellValue('F' . $numrow, ($row['HrgSatuan']));
            $sheet->setCellValue('G' . $numrow, ($row['Total']));
            $sheet->setCellValue('H' . $numrow, ($row['SudahTerserap']));
            $sheet->setCellValue('I' . $numrow, round($percentage, 3));
            $sheet->setCellValue('J' . $numrow, ($sisaRAP));

            $sheet->getStyle('F' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('G' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('H' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('J' . $numrow)->getNumberFormat()->setFormatCode('#,##0');

            $sheet->getStyle('A' . $numrow)->applyFromArray($styleCenter);
            $sheet->getStyle('B' . $numrow)->applyFromArray($styleLeft);
            $sheet->getStyle('C' . $numrow)->applyFromArray($styleLeft);
            $sheet->getStyle('D' . $numrow)->applyFromArray($styleCenter);
            $sheet->getStyle('E' . $numrow)->applyFromArray($styleCenter);
            $sheet->getStyle('F' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('G' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('H' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('I' . $numrow)->applyFromArray($styleCenter);
            $sheet->getStyle('J' . $numrow)->applyFromArray($styleRight);

            // style detail ketika check = true
            if ($isChecked == 'true') {
                // $sheet->getStyle('A'.$numrow.':J'.$numrow)->applyFromArray();
            }

            if ($isChecked == 'true') {
                $noDetail = 1;
                $numrow += 2;
                $sheet->mergeCells('C' . $numrow . ':E' . $numrow);
                $sheet->setCellValue('B' . $numrow, 'No');
                $sheet->setCellValue('C' . $numrow, 'Uraian');
                $sheet->setCellValue('F' . $numrow, 'No PJ');
                $sheet->setCellValue('G' . $numrow, 'PJ Harga Satuan');

                $sheet->getStyle('B' . $numrow)->applyFromArray($styleHeader);
                $sheet->getStyle('C' . $numrow . ':E' . $numrow)->applyFromArray($styleHeader);
                $sheet->getStyle('F' . $numrow)->applyFromArray($styleHeader);
                $sheet->getStyle('G' . $numrow)->applyFromArray($styleHeader);

                $sheet->getStyle('B' . $numrow . ':G' . $numrow)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('ced4da');
                $sheet->getStyle('J' . $numrow)->applyFromArray([
                    'borders' => [
                        'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                $numrow += 1;
                foreach ($row['detailBarang'] as $row2) {
                    $sheet->mergeCells('C' . $numrow . ':E' . $numrow);
                    $sheet->setCellValue('B' . $numrow, $noDetail);
                    $sheet->setCellValue('C' . $numrow, $row2['Uraian']);
                    $sheet->setCellValue('F' . $numrow, $row2['NoPJ']);
                    $sheet->setCellValue('G' . $numrow, $row2['PjHrgSatuan']);

                    $sheet->getStyle('B' . $numrow)->applyFromArray($styleCenter);
                    $sheet->getStyle('C' . $numrow . ':E' . $numrow)->applyFromArray($styleLeft);
                    $sheet->getStyle('F' . $numrow)->applyFromArray($styleLeft);
                    $sheet->getStyle('G' . $numrow)->applyFromArray($styleRight);

                    $sheet->getStyle('J' . $numrow)->applyFromArray([
                        'borders' => [
                            'right' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        ],
                    ]);

                    $noDetail++;
                    $numrow++;
                }
            }


            $no++;
            $numrow++;
        }

        $sheet->mergeCells('A' . $numrow . ':E' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'SUM');
        $sheet->getStyle('A' . $numrow . ':E' . $numrow)->applyFromArray($styleHeader);
        $sheet->setCellValue('F' . $numrow, UangBiasa($sumHrgSatuan));
        $sheet->setCellValue('G' . $numrow, UangBiasa($sumTotal));
        $sheet->setCellValue('H' . $numrow, UangBiasa($sumSdhTerserap));
        $sheet->setCellValue('J' . $numrow, UangBiasa($sumSisaRAP));
        $sheet->getStyle('F' . $numrow . ':J' . $numrow)->applyFromArray($styleHeader);
        $sheet->getStyle('A' . $numrow . ':J' . $numrow)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('fff85b');


        // set width
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(100);
        $sheet->getColumnDimension('D')->setWidth(8);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(21);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(9);
        $sheet->getColumnDimension('J')->setWidth(18);



        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        $sheet->setTitle("Repoprt RAP");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="ReportRAP' . time() . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}

/* End of file Controllername.php */
