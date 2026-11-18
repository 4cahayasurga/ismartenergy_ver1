<?php

defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PembelianMaterial extends CI_Controller
{
    public function index()
    {
        $data['judul'] = 'Daftar Pembelian Material';
        $data['Job'] = $this->get_ListJob();
        $data['bodyclass'] = 'sidebar-collapse skin-blue-light';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('report_view/procurement/pembelian_material/halaman_utama');
        $this->load->view('templates/footer');
    }

    public function getDataMaterial()
    {
        $this->session->unset_userdata('session_dftarMaterial');
        $JobNo = $this->input->post('JobNo');
        $StartDate = $this->input->post('StartDate');
        $EndDate = $this->input->post('EndDate');
        $data = $this->db->query("
            DECLARE @JobNo NVARCHAR(5)
            DECLARE @tglMulai DATE
            DECLARE @tglAkhir DATE

            SET @JobNo = '$JobNo'
            SET @tglMulai = '$StartDate'
            SET @tglAkhir = '$EndDate'

            SELECT 
                t1.Uraian, 
                t1.HrgSatuan, 
                t1.Vol, 
                t1.Vol * t1.HrgSatuan AS Total,
                t3.TglKO,
                t2.KdBarang,
                t4.VendorNm
            FROM 
                KoDtl t1 
            JOIN 
                tbl_DaftarMaterial t2 ON t1.Uraian = t2.NmBarang 
            JOIN 
                KoHdr t3 ON t1.NoKO = t3.NoKO
            JOIN 
                Vendor t4 ON t3.VendorId = t4.VendorId
            WHERE 
                --t2.KdBarang IS NOT NULL AND
                t3.JobNo = @JobNo AND
                t3.TglKO >= @tglMulai AND
                t3.TglKO <= @tglAkhir;
        ")->result_array();

        $queryForChart = $this->db->query("
            DECLARE @JobNo NVARCHAR(5)
            DECLARE @tglMulai DATE
            DECLARE @tglAkhir DATE

            SET @JobNo = '$JobNo'
            SET @tglMulai = '$StartDate'
            SET @tglAkhir = '$EndDate'

            SELECT TOP 10
                t1.Uraian, 
                t1.Vol * t1.HrgSatuan AS Total,
                t3.TglKO
            FROM 
                KoDtl t1 
            JOIN 
                tbl_DaftarMaterial t2 ON t1.Uraian = t2.NmBarang 
            JOIN 
                KoHdr t3 ON t1.NoKO = t3.NoKO
            JOIN 
                Vendor t4 ON t3.VendorId = t4.VendorId
            WHERE 
                --t2.KdBarang IS NOT NULL AND
                t3.JobNo = @JobNo AND
                t3.TglKO >= @tglMulai AND
                t3.TglKO <= @tglAkhir
            ORDER BY 
                Total DESC;
        ")->result_array();

        $sumTotal = 0;
        foreach ($data as $row) {
            $get = $row['Total'] != null ? $row['Total'] : 0;
            $sumTotal += $get;
        }

        $dataSesion = [
            'JobNo' => $JobNo,
            'StartDate' => $StartDate,
            'EndDate' => $EndDate,
            'data' => $data,
            'sumTotal' => $sumTotal,
            'dataForChart' => $queryForChart
        ];
        $this->session->set_userdata('session_dftarMaterial', $dataSesion);
        echo json_encode(["data" => $data, "sumTotal" => $sumTotal]);
    }

    public function getTopdata()
    {
        $data = $this->session->userdata('session_dftarMaterial');
        echo json_encode($data['dataForChart']);
    }

    public function search()
    {
        $data['JobNo'] = $this->input->post('JobNo');
        $data['PrdAwal'] = $this->input->post('StartDate');
        $data['PrdAkhir'] = $this->input->post('EndDate');

        $this->load->view('report_view/procurement/pembelian_material/tableData', $data);
    }

    public function exportExcel()
    {
        $Company = $this->config->item('Company');
        $dataSession = $this->session->userdata('session_dftarMaterial');
        $query = $dataSession['data'];
        $JobNo = $dataSession["JobNo"];
        $getJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$Company' AND JobNo='$JobNo'")->row_array();

        $title = 'Daftar Pembelian Barang';
        $JobDetail = $getJob['JobNo'] . ' - ' . $getJob['JobNm'];
        $periode = 'Periode ' . $dataSession['StartDate'] . ' s/d ' . $dataSession['EndDate'];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $styleHeader = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $styleHeaderTable = [
            'font' => [
                'bold' => true,
            ],
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
        $styleRow = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'indent' => 1
            ],
            'borders' => [
                'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ],
        ];


        // start title text and style
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');

        $sheet->setCellValue('A1', strtoupper($title));
        $sheet->setCellValue('A2', strtoupper($JobDetail));
        $sheet->setCellValue('A3', $periode);

        $sheet->getStyle('A1')->applyFromArray($styleHeader);
        $sheet->getStyle('A2')->applyFromArray($styleHeader);
        $sheet->getStyle('A1')->getFont()->setSize(12);
        $sheet->getStyle('A2')->getFont()->setSize(12);
        $sheet->getStyle('A2')->applyFromArray($styleHeader);
        $sheet->getStyle('A3')->applyFromArray($styleHeader);
        $sheet->getStyle('A3')->getFont()->setBold(false);
        // end title text and style

        //start header table txt
        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'Kode Barang');
        $sheet->setCellValue('C5', 'Nama Barang');
        $sheet->setCellValue('D5', 'Nama Vendor');
        $sheet->setCellValue('E5', 'Tgl Pembelian');
        $sheet->setCellValue('F5', 'Vol');
        $sheet->setCellValue('G5', 'Hrg Satuan');
        $sheet->setCellValue('H5', 'Total');
        //end header table txt

        // start header table style
        $sheet->getStyle('A5')->applyFromArray($styleHeaderTable);
        $sheet->getStyle('B5')->applyFromArray($styleHeaderTable);
        $sheet->getStyle('C5')->applyFromArray($styleHeaderTable);
        $sheet->getStyle('D5')->applyFromArray($styleHeaderTable);
        $sheet->getStyle('E5')->applyFromArray($styleHeaderTable);
        $sheet->getStyle('F5')->applyFromArray($styleHeaderTable);
        $sheet->getStyle('G5')->applyFromArray($styleHeaderTable);
        $sheet->getStyle('H5')->applyFromArray($styleHeaderTable);
        $sheet->getStyle('A5:H5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('e6e6e6');
        // end header table style

        // start row
        $numrow = 6;
        $no = 1;
        foreach ($query as $row) {
            // start row text
            $sheet->setCellValue('A' . $numrow, $no);
            $sheet->setCellValue('B' . $numrow, $row['KdBarang']);
            $sheet->setCellValue('C' . $numrow, $row['Uraian']);
            $sheet->setCellValue('D' . $numrow, $row['VendorNm']);
            $sheet->setCellValue('E' . $numrow, $row['TglKO']);
            $sheet->setCellValue('F' . $numrow, $row['Vol']);
            $sheet->setCellValue('G' . $numrow, $row['HrgSatuan']);
            $sheet->setCellValue('H' . $numrow, $row['Total']);
            // end row text

            // start row style
            $sheet->getStyle('A' . $numrow)->applyFromArray($styleRow);
            $sheet->getStyle('B' . $numrow)->applyFromArray($styleRow);
            $sheet->getStyle('C' . $numrow)->applyFromArray($styleRow);
            $sheet->getStyle('D' . $numrow)->applyFromArray($styleRow);
            $sheet->getStyle('E' . $numrow)->applyFromArray($styleRow);
            $sheet->getStyle('F' . $numrow)->applyFromArray($styleRow);
            $sheet->getStyle('G' . $numrow)->applyFromArray($styleRow);
            $sheet->getStyle('H' . $numrow)->applyFromArray($styleRow);

            $sheet->getStyle('C' . $numrow)->getAlignment()->setWrapText(true);
            $sheet->getStyle('D' . $numrow)->getAlignment()->setWrapText(true);

            $cellValueG = $sheet->getCell('F' . $numrow)->getValue();
            if ($cellValueG != 0) {
                $sheet->getStyle('F' . $numrow)->getNumberFormat()->setFormatCode('#,##0.000');
            }
            $cellValueG = $sheet->getCell('G' . $numrow)->getValue();
            if ($cellValueG != 0) {
                $sheet->getStyle('G' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
            }
            $cellValueH = $sheet->getCell('H' . $numrow)->getValue();
            if ($cellValueH != 0) {
                $sheet->getStyle('H' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
            }
            // end row style

            $numrow++;
            $no++;
        }
        // end row

        // set width row
        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(60);
        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('E')->setWidth(13);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        // set width row

        // start sum footer
        $sheet->mergeCells('A' . $numrow . ':G' . $numrow);
        $sheet->setCellValue('A' . $numrow, 'SUM');
        $sheet->setCellValue('H' . $numrow, $dataSession['sumTotal']);
        $sheet->getStyle('A' . $numrow)->applyFromArray($styleHeaderTable);
        $sheet->getStyle('B' . $numrow)->applyFromArray($styleHeaderTable);
        $sheet->getStyle('C' . $numrow)->applyFromArray($styleHeaderTable);
        $sheet->getStyle('D' . $numrow)->applyFromArray($styleHeaderTable);
        $sheet->getStyle('E' . $numrow)->applyFromArray($styleHeaderTable);
        $sheet->getStyle('F' . $numrow)->applyFromArray($styleHeaderTable);
        $sheet->getStyle('G' . $numrow)->applyFromArray($styleHeaderTable);
        $sheet->getStyle('H' . $numrow)->applyFromArray($styleHeaderTable);
        $sheet->getStyle('H' . $numrow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A' . $numrow . ':H' . $numrow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('e6e6e6');
        // end sum footer


        // Set orientasi kertas jadi LANDSCAPE
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

        // Set judul file excel nya
        $sheet->setTitle("Report Pembelian Barang");

        // Proses file excel
        // $tgl_hari_ini = date('ymdHis');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="report_pembelian_barang-' . date('ymdHis') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function exportPDF()
    {
        $this->data['user'] = $this->session->userdata('MIS_LOGGED_ID');
        $this->data['date'] = date('d-M-Y');

        $this->load->library('pdfgenerator');

        $Company = $this->config->item('Company');
        $dataSession = $this->session->userdata('session_dftarMaterial');
        $this->data['query'] = $dataSession['data'];
        $this->data['sumTotal'] = $dataSession['sumTotal'];
        $JobNo = $dataSession["JobNo"];
        $getJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$Company' AND JobNo='$JobNo'")->row_array();

        $this->data['title'] = 'DAFTAR PEMBELIAN MATERIAL';
        $this->data['JobDetail'] = $getJob['JobNo'] . ' - ' . $getJob['JobNm'];
        $this->data['periode'] = 'Periode ' . $dataSession['StartDate'] . ' s/d ' . $dataSession['EndDate'];

        $file_pdf = 'LaporanDaftarPembelianMaterial';
        $paper = 'A4';
        $orientation = "landscape";
        $html = $this->load->view('report_view/procurement/pembelian_material/print', $this->data, true);
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }

    private function get_ListJob()
    {
        $Company = $this->config->item('Company');
        $query = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$Company' ");
        return $query->result_array();
    }
}
