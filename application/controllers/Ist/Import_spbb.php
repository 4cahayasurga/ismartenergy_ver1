<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;   

class Import_spbb extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Spbb_model');
        $role = $this->Spbb_model->get_role($this->session->userdata('MIS_LOGGED_ID'));
        if (!in_array($role['role'], array('PEMILIK_SPBB', 'SUPERADMIN')) &&
            strtolower($this->session->userdata('MIS_LOGGED_ID')) !== 'admin') {
            show_error('Akses ditolak', 403);
        }
    }

    public function index()
    {
        redirect('Ist/Import_spbb/DataDelivery');
    }

    public function upload()
    {
        $file = $this->validatedSpreadsheet('file_excel');
        if (!$file) {
            echo 'File Excel tidak valid. Gunakan XLS/XLSX maksimal 10 MB.';
            return;
        }

        try {
            $spreadsheet = IOFactory::load($file);
        } catch (Exception $e) {
            log_message('error', 'Gagal membaca Data Supply Excel: ' . $e->getMessage());
            echo 'File Excel rusak atau format tidak didukung.';
            return;
        }
        $sheet = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

        // HEADER
        $nama_spbb   = trim(explode(':',$sheet[1]['B'])[1]);
        $periode     = trim(explode(':',$sheet[2]['B'])[1]);
        $jenis_bbm   = trim(explode(':',$sheet[3]['B'])[1]);

        list($awal,$akhir) = explode(' s.d ',$periode);

        $this->db->insert('laporan_spbb',[
            'nama_spbb' => $nama_spbb,
            'periode_awal' => $awal,
            'periode_akhir'=> $akhir,
            'jenis_bbm' => $jenis_bbm
        ]);

        $laporan_id = $this->db->insert_id();

        // DATA START ROW (sesuaikan, biasanya mulai baris 7)
        for($i=7;$i<=count($sheet);$i++){
            if(empty($sheet[$i]['A'])) continue;

            $this->db->insert('transaksi_spbb',[
                'laporan_id' => $laporan_id,
                'no_transaksi' => $sheet[$i]['A'],
                'no_quotation' => $sheet[$i]['B'],
                'nama_kapal' => $sheet[$i]['C'],
                'jenis_kapal' => $sheet[$i]['D'],
                'tanggal_pemesanan' => date('Y-m-d',strtotime($sheet[$i]['E'])),
                'waktu_pemesanan' => $sheet[$i]['F'],
                'dipesan_liter' => $sheet[$i]['G'],
                'tanggal_pengiriman' => !empty($sheet[$i]['H']) ? date('Y-m-d',strtotime($sheet[$i]['H'])) : NULL,
                'waktu_pengiriman' => $sheet[$i]['I'],
                'realisasi_liter' => $sheet[$i]['J'],
                'jenis_bbm' => $sheet[$i]['K'],
                'titik_serah' => $sheet[$i]['L'],
                'status' => $sheet[$i]['M'],
                'sp' => $sheet[$i]['N'],
                'logbook' => $sheet[$i]['O'],
                'rpt_ri' => $sheet[$i]['P']
            ]);
        }

        echo "IMPORT BERHASIL";

        redirect('Ist/Import_spbb');
    }

    public function DataSupply()
    {
        // echo "HALAMAN DATA SUPPLY SPBB";
        // exit;

        $data['judul'] = 'Data Supply SPBB';
        $data['bodyclass'] = 'sidebar-collapse skin-black';

        $data['data_supply'] = $this->db->query("SELECT * FROM DataSupply  ORDER BY Tgl_Pengisian DESC")->result_array();




        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('Ist/Transaksi/data_supply', $data);
        $this->load->view('templates/footer');

    }
    
    

    public function uploadDataSuply()
    {
        if (!isset($_FILES['file_excel'])) {
            echo "File tidak ditemukan!";
            return;
        }

        if ($_FILES['file_excel']['error'] != 0) {
            echo "Upload gagal!";
            return;
        }

        $file = $this->validatedSpreadsheet('file_excel');
        if (!$file) {
            echo 'File Excel tidak valid. Gunakan XLS/XLSX maksimal 10 MB.';
            return;
        }

        try {
            $spreadsheet = IOFactory::load($file);
        } catch (Exception $e) {
            log_message('error', 'Gagal membaca Data Delivery Excel: ' . $e->getMessage());
            echo 'File Excel rusak atau format tidak didukung.';
            return;
        }
        $sheet = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

        $insert = [];
        $seen = [];
        $skipped = 0;
        foreach ($sheet as $i => $row) {

            if ($i < 2) continue;
            if (empty($row['A'])) continue;

            $do_number = trim($row['C']);
            $no_spbb = trim($row['L']);
            $key = strtoupper($no_spbb . '|' . $do_number);
            if (!$do_number || !$no_spbb || isset($seen[$key]) ||
                $this->db->where('DO_Number', $do_number)->where('No_SPBB', $no_spbb)->count_all_results('DataSupply')) {
                $skipped++;
                continue;
            }
            $seen[$key] = true;

            // ================= TANGGAL =================
            $tgl = null;
            if (!empty($row['E'])) {
                if (is_numeric($row['E'])) {
                    $tgl = Date::excelToDateTimeObject($row['E'])->format('Y-m-d');
                } else {
                    $tgl = date('Y-m-d', strtotime($row['E']));
                }
            }

            // ================= JAM =================
            $jam = !empty($row['F']) ? date('H:i:s', strtotime($row['F'])) : null;

            // ================= NUMERIC =================
            $do_vol        = (int)$row['P'];
            $delivery_vol  = (int)$row['Q'];
            $sales_vol     = $delivery_vol; // bisa disesuaikan
            $diff          = $delivery_vol - $do_vol;
            $diff_percent  = $do_vol != 0 ? ($diff / $do_vol) * 100 : 0;

            $insert[] = [
                'Tgl_Pengisian' => $tgl,
                'Jam' => $jam,
                'DO_Number' => $do_number,
                'Nokendaraan' => $row['D'],
                'Nm_Driver' => $row['G'],
                'Supplier' => $row['I'],
                'Tank' => $row['J'],
                'Supply_Point_Name' => $row['K'],
                'No_SPBB' => $no_spbb,
                'Grade' => $row['M'],
                'Stock_Awal' => $row['N'],
                'Stock_Akhir' => $row['O'],
                'DO_Vol' => $do_vol,
                'Delivery_Vol' => $delivery_vol,
                'Sales_Vol' => $row['R'], // bisa disesuaikan
                'Diff' => $diff, // bisa disesuaikan
                'Diff_Percent' => $diff_percent,
                'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
                'TimeEntry' => date('Y-m-d H:i:s')
            ];
        }

        // ================= INSERT BATCH =================
        if (!empty($insert)) {
            $this->db->insert_batch('DataSupply', $insert);
        }

        echo "IMPORT BERHASIL. " . count($insert) . " baris ditambahkan, " . $skipped . " baris duplikat/tidak valid dilewati.";
        redirect('Ist/Import_spbb/DataSupply');
    }


    public function DataDelivery()
    {
        // echo "HALAMAN DATA SUPPLY SPBB";
        // exit;

        $data['judul'] = 'Data Penjualan SPBB / Stock Keluar';
        $data['bodyclass'] = 'sidebar-collapse skin-black';

        $data['data_delivery'] = $this->db->query("SELECT * FROM DataDelivery  ORDER BY Tgl_Pemesanan DESC")->result_array();
        $role = $this->Spbb_model->get_role($this->session->userdata('MIS_LOGGED_ID'));
        $data['manual_spbb_list'] = $this->Spbb_model->spbb_list_by_ids($role['spbb_ids']);
        $data['manual_fuel_list'] = $this->Spbb_model->fuel_list($role['spbb_ids']);




        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('Ist/Transaksi/data_Delivery', $data);
        $this->load->view('templates/footer');

    }

    public function saveManualDelivery()
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $role = $this->Spbb_model->get_role($user_id);
        if ($role['role'] !== 'PEMILIK_SPBB') {
            show_error('Akses ditolak', 403);
        }

        $spbb_id = (int) $this->input->post('SpbbID', true);
        $fuel_id = (int) $this->input->post('FuelID', true);
        $spbb = $this->Spbb_model->get_spbb_by_id($spbb_id);
        $fuel = $this->Spbb_model->get_fuel($fuel_id);
        $qty = (float) $this->input->post('RealizedQuantity', true);
        $ordered_qty = (float) $this->input->post('OrderedQuantity', true);
        $transaction_no = trim($this->input->post('TransactionNo', true));
        $customer_name = trim($this->input->post('CustomerName', true));
        $customer_group = trim($this->input->post('CustomerGroup', true));

        if (!$spbb || !$fuel || !in_array($spbb_id, $role['spbb_ids']) || (int) $fuel['SpbbID'] !== $spbb_id) {
            $result = array(false, 'SPBB atau material tidak sesuai dengan akses user');
        } elseif (!$transaction_no || !$customer_name || !$customer_group || $ordered_qty <= 0 || $qty <= 0 ||
            !$this->input->post('OrderDate', true) || !$this->input->post('RealizationDate', true)) {
            $result = array(false, 'Nomor transaksi, customer, tanggal, dan volume wajib diisi dengan benar');
        } else {
            $result = $this->Spbb_model->create_manual_sale($user_id, array(
                'SourceOrderID' => null,
                'EntrySource' => 'MANUAL',
                'TransactionNo' => $transaction_no,
                'QuotationNo' => trim($this->input->post('QuotationNo', true)),
                'CustomerName' => $customer_name,
                'CustomerGroup' => $customer_group,
                'CustomerType' => trim($this->input->post('CustomerType', true)),
                'SpbbID' => $spbb_id,
                'NoSPBB' => $spbb['No_SPBB'],
                'Area' => $spbb['Area'],
                'FuelID' => $fuel_id,
                'FuelName' => $fuel['FuelName'],
                'OrderDate' => $this->input->post('OrderDate', true),
                'OrderTime' => $this->input->post('OrderTime', true),
                'RealizationDate' => $this->input->post('RealizationDate', true),
                'RealizationTime' => $this->input->post('RealizationTime', true),
                'OrderedQuantity' => $ordered_qty,
                'RealizedQuantity' => $qty,
                'Status' => 'Completed',
                'SPP' => trim($this->input->post('SPP', true)),
                'SPB' => trim($this->input->post('SPB', true)) ?: $spbb['No_SPBB'],
                'Logbook' => trim($this->input->post('Logbook', true)),
                'ReportReference' => trim($this->input->post('ReportReference', true)),
                'CreatedBy' => $user_id,
            ));
        }

        $this->session->set_flashdata($result[0] ? 'Berhasil' : 'Gagal', $result[1]);
        redirect('Ist/Import_spbb/DataDelivery');
    }
    
    

    public function uploadDataDelivery()
    {
        if (!isset($_FILES['file_excel'])) {
            echo "File tidak ditemukan!";
            return;
        }

        if ($_FILES['file_excel']['error'] != 0) {
            echo "Upload gagal!";
            return;
        }

        $file = $_FILES['file_excel']['tmp_name'];

        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

        $insert = [];
        $seen = [];
        $skipped = 0;
        $active_spbb = [];
        foreach ($this->db->select('No_SPBB')->where('Status', 'Aktif')->get('SPBB')->result_array() as $spbb) {
            $active_spbb[strtoupper(trim($spbb['No_SPBB']))] = trim($spbb['No_SPBB']);
        }

        foreach ($sheet as $i => $row) {

            if ($i < 2) continue;
            if (empty($row['A'])) continue;

            $transaction_no = trim($row['D']);
            $no_spbb = '';
            foreach (['O', 'P', 'Q', 'R', 'S', 'T'] as $column) {
                $candidate = trim((string) $row[$column]);
                $candidate_key = strtoupper($candidate);
                if (isset($active_spbb[$candidate_key])) {
                    $no_spbb = $active_spbb[$candidate_key];
                    break;
                }
            }
            $key = strtoupper($no_spbb . '|' . $transaction_no);
            if (!$transaction_no || !$no_spbb || isset($seen[$key]) ||
                $this->db->where('DO_Transaksi', $transaction_no)->where('No_SPBB', $no_spbb)->count_all_results('DataDelivery')) {
                $skipped++;
                continue;
            }
            $seen[$key] = true;

            $insert[] = [
                'Year' => $row['B'],
                'Month' => $row['C'],
                'DO_Transaksi' => $transaction_no,
                'NoQuotation' => $row['E'],
                'Nm_Kapal' => $row['F'],
                'JenisKapal' => $row['G'],
                'Tgl_Pemesanan' => $row['H'],
                'Waktu_Pemesanan' => $row['I'],
                'Vol_Dipesan' => $row['J'],
                'Tgl_Realisasi' => $row['K'],
                'Waktu_Realisasi' => $row['L'],
                'Vol_Realisasi' => $row['M'],
                'Grade' => $row['N'],
                'No_SPBB' => $no_spbb,
                'Status' => $row['P'],
                'SPP' => $row['Q'], 
                'SPB' => $row['R'],
                'Logbook' => $row['S'],
                'RPT_RI' => $row['T'],
                'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
                'TimeEntry' => date('Y-m-d H:i:s'),
                'EntrySource' => 'EXCEL'
            ];
        }

        // ================= INSERT BATCH =================
        if (!empty($insert)) {
            $this->db->insert_batch('DataDelivery', $insert);
            $this->Spbb_model->sync_delivery_to_sales_realization();
        }

        echo "IMPORT BERHASIL. " . count($insert) . " baris ditambahkan, " . $skipped . " baris duplikat/tidak valid dilewati.";
        redirect('Ist/Import_spbb/DataDelivery');
    }

    private function validatedSpreadsheet($field)
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK ||
            $_FILES[$field]['size'] <= 0 || $_FILES[$field]['size'] > 10 * 1024 * 1024) {
            return false;
        }

        $extension = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, array('xls', 'xlsx'), true)) {
            return false;
        }

        $allowed_mime = array(
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip',
            'application/octet-stream',
        );
        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : false;
        $mime = $finfo ? finfo_file($finfo, $_FILES[$field]['tmp_name']) : 'application/octet-stream';
        if ($finfo) {
            finfo_close($finfo);
        }
        return in_array($mime, $allowed_mime, true) ? $_FILES[$field]['tmp_name'] : false;
    }

}
