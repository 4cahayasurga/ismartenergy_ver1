<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AnalisaDelta extends CI_Controller
{
    public function index()
    {
        $data['judul'] = 'Analisa Delta RAP vs Realisasi RAP';
        $data['bodyclass'] = 'sidebar-collapse skin-red';
        $data['DetailJob'] = $this->get_ListJob();
        $data['ListAlokasi'] = $this->GetAlokasi();
        // print_rr($data['ListAlokasi']);
        // die;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('report_view/procurement/analisa_delta/halaman_utama', $data);
        $this->load->view('templates/footer');
    }

    public function showTable()
    {
        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $this->load->view('report_view/procurement/analisa_delta/tableData', $data);
    }

    public function getListData()
    {
        if (!$this->input->post()) {
            exit;
        }
        $this->session->unset_userdata('sessionForExcel');
        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        if ($Alokasi !== 'All') {
            $whereTambahan = "AND t1.Alokasi='$Alokasi'";
        } else {
            $whereTambahan = '';
        }
        $array = [];
        $queryRAP = $this->db->query("SELECT
            t1.KdRAP, 
            t1.JobNo,
            t1.Uraian, 
            t1.Versi,
            t1.Alokasi,
            t1.NoUrut,
            t1.Tipe,
            SUM(t1.Vol) AS Vol_RAP, 
            SUM(t1.HrgSatuan) AS HrgSatuan_RAP
            FROM RAP t1
            WHERE t1.JobNo = '$JobNo' AND t1.Tipe != 'Header' $whereTambahan
            GROUP BY t1.KdRAP, t1.JobNo, t1.Uraian, t1.Versi, t1.ALokasi, t1.NoUrut, t1.Tipe, t1.NoUrut
            ORDER BY t1.NoUrut ASC")->result();
        foreach ($queryRAP as $row) {
            if ($row->Tipe == 'Header') {
                continue;
            }
            if ($this->db->get_where('RAPH', ['JobNo' => $JobNo])->num_rows() < 1) {
                $queryRAPH = $this->db->query(
                    "SELECT TOP 1
                    t1.KdRAP,
                    t1.HrgSatuan AS HrgSatuan_RAPH, 
                    t1.Vol AS Vol_RAPH, 
                    t1.Tipe,
                    t1.Versi,
                    t1.LedgerNo,
                    t1.NoUrut,
                    (SELECT SUM(Vol) from PRDtl WHERE KdRAP='$row->KdRAP' AND NoSPR LIKE '%$JobNo%') AS 'SumVolPRDtl',
                    (SELECT SUM(Vol) from KoDtl WHERE KdRAP='$row->KdRAP' AND NoKO LIKE '%$JobNo%') AS 'SumVolKoDtl'
                FROM RAP AS t1
                WHERE t1.JobNo = '$JobNo' AND t1.KdRAP = '$row->KdRAP' $whereTambahan
                GROUP BY t1.KdRAP, t1.Vol, t1.HrgSatuan, t1.JobNo, t1.KdRAP, t1.Tipe, t1.Versi, t1.LedgerNo, t1.NoUrut
                ORDER BY t1.LedgerNo ASC, t1.NoUrut ASC"
                )->result();
            } else {
                $queryRAPH = $this->db->query(
                    "SELECT TOP 1
                        t1.KdRAP,
                        t1.HrgSatuan AS HrgSatuan_RAPH, 
                        t1.Vol AS Vol_RAPH, 
                        t1.Tipe,
                        t1.Versi,
                        t1.LedgerNo,
                        t1.NoUrut,
                        (SELECT SUM(Vol) from PRDtl WHERE KdRAP='$row->KdRAP' AND NoSPR LIKE '%$JobNo%') AS 'SumVolPRDtl',
                        (SELECT SUM(Vol) from KoDtl WHERE KdRAP='$row->KdRAP' AND NoKO LIKE '%$JobNo%') AS 'SumVolKoDtl'
                    FROM RAPH AS t1
                    WHERE t1.JobNo = '$JobNo' AND t1.KdRAP = '$row->KdRAP' $whereTambahan
                    GROUP BY t1.KdRAP, t1.Vol, t1.HrgSatuan, t1.JobNo, t1.KdRAP, t1.Tipe, t1.Versi, t1.LedgerNo, t1.NoUrut
                    ORDER BY t1.LedgerNo ASC, t1.NoUrut ASC"
                )->result();
            }
            foreach ($queryRAPH as $row2) {

                $SumVolPRDtl = ($row2->SumVolPRDtl == null ? 0 : $row2->SumVolPRDtl);
                $SumVolKoDtl = ($row2->SumVolKoDtl == null ? 0 : $row2->SumVolKoDtl);
                $RAP = $row->HrgSatuan_RAP * $row->Vol_RAP;
                $SPR = $SumVolPRDtl * $row->HrgSatuan_RAP;
                $RealisasiPO = $RAP * $SumVolKoDtl;
                $array[] = [
                    "KdRAP" => $row->KdRAP,
                    "NoUrut" => $row->NoUrut,
                    "LedgerNo" => $row2->LedgerNo,
                    "Alokasi" => $row->Alokasi,
                    "Uraian" => $row->Uraian,
                    "Versi" => $row->Versi,
                    "Vol" => UbahDecimal_Nol_Tiga($row2->Vol_RAPH),
                    "HrgSatuan" => UbahDecimal_Nol_Tiga($row2->HrgSatuan_RAPH),
                    "VolRAP" => UbahDecimal_Nol_Tiga($row->Vol_RAP),
                    "HrgSatuanRAP" => UbahDecimal_Nol_Tiga($row->HrgSatuan_RAP),
                    "VolSPR" => UbahDecimal_Nol_Tiga($row2->SumVolPRDtl),
                    "SPR" => UbahDecimal_Nol_Tiga($SPR),
                    "VolPO" => UbahDecimal_Nol_Tiga($SumVolKoDtl),
                    "Realisasi_PO" => UbahDecimal_Nol_Tiga($RealisasiPO),
                    "Delta1" => UbahDecimal_Nol_Tiga($RAP - $SPR),
                    "Delta2" => UbahDecimal_Nol_Tiga($RAP - $RealisasiPO),
                    "Delta3" => UbahDecimal_Nol_Tiga($SPR - $RealisasiPO),
                ];
            }
        }
        $this->session->set_userdata('sessionForExcel', ['data' => $array, 'title' => $JobNo]);
        header('Content-Type: application/json');
        echo json_encode(['data' => $array]);
    }

    public function exportExcel()
    {
        $dataSession = $this->session->userdata('sessionForExcel');
        $this->db->select('JobNm');
        $getJobName = $this->db->get_where('Job', ['JobNo' => $dataSession['title']])->row_array();
        $JobName = $getJobName['JobNm'];

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

        $sheet->mergeCells('A1:O1');
        $sheet->setCellValue('A1', 'Analisa Delta RAP vs Realisasi RAP');
        $sheet->getStyle('A1')->applyFromArray($styleTitle);
        $sheet->mergeCells('A2:O2');
        $sheet->setCellValue('A2', $dataSession['title'] . '-' . $JobName);
        $sheet->getStyle('A2')->applyFromArray($styleTitle);
        $sheet->getStyle('A1')->getFont()->setSize(13);
        $sheet->getStyle('A2')->getFont()->setSize(13);

        $sheet->mergeCells('A4:A5');
        $sheet->mergeCells('B4:B5');
        $sheet->mergeCells('C4:C5');
        $sheet->mergeCells('D4:F4');
        $sheet->mergeCells('G4:H4');
        $sheet->mergeCells('I4:J4');
        $sheet->mergeCells('K4:L4');
        $sheet->mergeCells('M4:M5');
        $sheet->mergeCells('N4:N5');
        $sheet->mergeCells('O4:O5');

        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Kode RAP');
        $sheet->setCellValue('C4', 'Uraian');
        $sheet->setCellValue('D4', 'RAP Version');
        $sheet->setCellValue('D5', 'Versi');
        $sheet->setCellValue('E5', 'Volume');
        $sheet->setCellValue('F5', 'Harga Satuan (Rp)');
        $sheet->setCellValue('G4', 'RAP Berjalan');
        $sheet->setCellValue('G5', 'Volume');
        $sheet->setCellValue('H5', 'Harga Satuan (Rp)');
        $sheet->setCellValue('I4', 'SPR');
        $sheet->setCellValue('I5', 'Volume');
        $sheet->setCellValue('J5', 'Nilai SPR (Rp)');
        $sheet->setCellValue('K4', 'Realisasi PO');
        $sheet->setCellValue('K5', 'Volume');
        $sheet->setCellValue('L5', 'Nilai PO (Rp)');
        $sheet->setCellValue('M4', '∆ RAP - SPR');
        $sheet->setCellValue('N4', '∆ RAP - PO');
        $sheet->setCellValue('O4', '∆ SPR - PO');

        $sheet->getStyle('A4:A5')->applyFromArray($styleHeader);
        $sheet->getStyle('B4:B5')->applyFromArray($styleHeader);
        $sheet->getStyle('C4:C5')->applyFromArray($styleHeader);
        $sheet->getStyle('D4:F4')->applyFromArray($styleHeader);
        $sheet->getStyle('D5')->applyFromArray($styleHeader);
        $sheet->getStyle('E5')->applyFromArray($styleHeader);
        $sheet->getStyle('F5')->applyFromArray($styleHeader);
        $sheet->getStyle('G4:H4')->applyFromArray($styleHeader);
        $sheet->getStyle('G5')->applyFromArray($styleHeader);
        $sheet->getStyle('H5')->applyFromArray($styleHeader);
        $sheet->getStyle('I4:J4')->applyFromArray($styleHeader);
        $sheet->getStyle('I5')->applyFromArray($styleHeader);
        $sheet->getStyle('J5')->applyFromArray($styleHeader);
        $sheet->getStyle('K4:L4')->applyFromArray($styleHeader);
        $sheet->getStyle('K5')->applyFromArray($styleHeader);
        $sheet->getStyle('L5')->applyFromArray($styleHeader);
        $sheet->getStyle('M4:M5')->applyFromArray($styleHeader);
        $sheet->getStyle('N4:N5')->applyFromArray($styleHeader);
        $sheet->getStyle('O4:O5')->applyFromArray($styleHeader);

        $sheet->getStyle('A4:O5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('ccd4db');
        $sheet->getStyle('D5:F5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('3399FF');
        $sheet->getStyle('G5:H5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('33CC33');
        $sheet->getStyle('I5:J5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('ffd43b');
        $sheet->getStyle('K5:L5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('845ef7');

        $no = 1;
        $numrow = 6;
        foreach ($dataSession['data'] as $row) {
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $row['KdRAP']);
            $sheet->setCellValue('C' . $numrow, $row['Uraian']);
            $sheet->setCellValue('D' . $numrow, $row['Versi']);
            $sheet->setCellValue('E' . $numrow, $row['Vol']);
            $sheet->setCellValue('F' . $numrow, $row['HrgSatuan']);
            $sheet->setCellValue('G' . $numrow, $row['VolRAP']);
            $sheet->setCellValue('H' . $numrow, $row['HrgSatuanRAP']);
            $sheet->setCellValue('I' . $numrow, $row['VolSPR']);
            $sheet->setCellValue('J' . $numrow, $row['SPR']);
            $sheet->setCellValue('K' . $numrow, $row['VolPO']);
            $sheet->setCellValue('L' . $numrow, $row['Realisasi_PO']);
            $sheet->setCellValue('M' . $numrow, $row['Delta1']);
            $sheet->setCellValue('N' . $numrow, $row['Delta2']);
            $sheet->setCellValue('O' . $numrow, $row['Delta3']);
            $sheet->getStyle('A' . $numrow . ':B' . $numrow)->applyFromArray($styleHeader);
            $sheet->getStyle('C' . $numrow)->applyFromArray($styleLeft);
            $sheet->getStyle('D' . $numrow)->applyFromArray($styleHeader);
            $sheet->getStyle('E' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('F' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('G' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('H' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('I' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('J' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('K' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('L' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('M' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('N' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('O' . $numrow)->applyFromArray($styleRight);
            $sheet->getStyle('D' . $numrow . ':F' . $numrow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('80ceff');
            $sheet->getStyle('G' . $numrow . ':H' . $numrow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('80ff9e');
            $sheet->getStyle('I' . $numrow . ':J' . $numrow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('fff68f');
            $sheet->getStyle('I' . $numrow . ':J' . $numrow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('fff68f');
            $sheet->getStyle('K' . $numrow . ':L' . $numrow)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('b197fc');
            $no++;
            $numrow++;
        }

        $sheet->getColumnDimension('A')->setWidth(4);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getColumnDimension('E')->setWidth(11);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(11);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(11);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(21);
        $sheet->getColumnDimension('M')->setWidth(20);
        $sheet->getColumnDimension('N')->setWidth(20);
        $sheet->getColumnDimension('O')->setWidth(20);
        $sheet->getRowDimension(4)->setRowHeight(24);
        $sheet->getRowDimension(5)->setRowHeight(24);
        $sheet->getStyle('E:L')->getNumberFormat()->setFormatCode('#,##0.00');


        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        $sheet->setTitle("Analaisa Delta");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="analisa_delta.xlsx"');
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

    private function GetAlokasi()
    {
        $AksesAlokasi = $this->session->userdata('PecahToken')->AksesAlokasi;
        // $AksesAlokasi = $this->db->query("SELECT AksesAlokasi FROM Login WHERE UserID='liasw'")->row()->AksesAlokasi;
        $queryAlokasi = $this->db->query("SELECT Alokasi,Keterangan FROM Alokasi WHERE Alokasi<>'A' ");
        $ArrayAlokasi = array();
        foreach ($queryAlokasi->result_array() as $row) :

            if (!preg_match('/\b' . $row['Alokasi'] . '\b/', $AksesAlokasi)) {
                continue;
            }

            $ArrayAlokasi[] = array('Alokasi' => $row['Alokasi'], 'Keterangan' => $row['Keterangan']);
        endforeach;

        return $ArrayAlokasi;
    }

    public function getVersi()
    {
        $JobNo = $this->input->post('JobNo');
        $Alokasi = $this->input->post('Alokasi');

        $myarray = array();

        // == get Versi RAP
        $query = $this->db->query("SELECT TOP 1 Versi FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ");
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $myarray[] = array('Versi' => $row->Versi, 'TipeRAP' => 'RAP');
            }
        } else {
            $myarray[] = array('Versi' => '0.0', 'TipeRAP' => 'RAP');
        }


        // == get Versi RAP H
        $queryRAPH = $this->db->query("SELECT DISTINCT Versi FROM RAPH WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY Versi DESC");
        if ($queryRAPH->num_rows() > 0) {
            foreach ($queryRAPH->result() as $row) {
                $myarray[] = array('Versi' => $row->Versi, 'TipeRAP' => 'RAPH');
            }
        }

        echo json_encode($myarray);
    }
}
