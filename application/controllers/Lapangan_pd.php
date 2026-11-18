<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Lapangan_pd extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('M_lapangan_pd');
        $this->load->helper('bantu');
    }


    //saat tambah pd

    function table_detail_tanpa_ko($JobNo = '', $NoKO = '', $NoPD = '', $menu_detail = '')
    {
        if (!$this->input->post()) {
            die;
        }

        if ($JobNo == '') {
            $JobNo = $this->input->post('JobNo', TRUE);
        }

        if ($NoKO == '') {
            $NoKO = $this->input->post('NoKO', TRUE);
        }

        if ($NoPD == '') {
            $NoPD = $this->input->post('NoPD', TRUE);
        }

        $Alokasi = $this->input->post('Alokasi', TRUE);
        $data['JobNo'] = $JobNo;
        $data['NoKO'] = $NoKO;
        $data['NoPD'] = $NoPD;
        $data['Alokasi'] = $Alokasi;

        $query = $this->db->query(" SELECT JobNo,JobNm FROM Job WHERE JobNo='$JobNo' ")->row_array();
        $data['data_job'] = $query;
        // $NoPD = $this->input->post('NoPD', TRUE);

        $data['data_detail'] = $this->M_lapangan_pd->dapat_data_detail('sementara', $NoPD);
        $data['menu_detail'] = $menu_detail;

        $invoice = $this->M_lapangan_pd->dapat_invoice($JobNo, $NoKO);
        $data['data_invoice'] = $invoice;



        $this->load->view('lapangan_pd/tampil_uraian_dan_invoice', $data);
    }

    function tampil_uraian_dan_invoice()
    {

        if (!$this->input->post()) {
            die;
        }

        $JobNo = $this->input->post('JobNo', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);

        $menu_detail = $this->input->post('menu_detail', TRUE);

        $type = $this->input->post('type', TRUE);

        $data['JobNo'] = $JobNo;
        $data['NoKO'] = $NoKO;
        $data['NoPD'] = $NoKO;
        $data['Alokasi'] = $Alokasi;
        $cek_NoKO = substr($NoKO, -3);

        $nama_table = '';

        if ($type == 'tambah_pd') {
            $nama_table = 'table_pd_detail_sementara';
        } else if ($type == 'edit_pd') {
            $nama_table = 'PdDtl';
        }

        if ($cek_NoKO !== 'INT') {
            $masukkan_kodtl_ke_pd_sementara = $this->M_lapangan_pd->masukkan_kodtl_ke_pd_sementara($NoPD, $NoKO, $Alokasi);
        }

        $cek_NoKO = substr($NoKO, -3);
        if ($cek_NoKO !== 'INT') {
            $masukkan_kodtl_ke_pd_sementara = $this->M_lapangan_pd->masukkan_kodtl_ke_pd_sementara($NoPD, $NoKO, $Alokasi);
        } else {
            $this->db->where('NoPd', $NoPD)->delete($nama_table);
        }

        $this->table_detail_tanpa_ko($JobNo, $NoKO, $NoPD, $menu_detail);






        // if ($menu_detail == 'pd_detail') {
        //     //jika nomor ko nya bukan INT berarti harus ambil data dulu dr KoDtl

        //     $cek_NoKO = substr($NoKO, -3);
        //     if ($cek_NoKO !== 'INT') {
        //         $masukkan_kodtl_ke_pd_sementara = $this->M_lapangan_pd->masukkan_kodtl_ke_pd_sementara($NoPD, $NoKO, $Alokasi);
        //     }

        //     $this->table_detail_tanpa_ko($JobNo, $NoKO, $NoPD);
        // }

        // if ($menu_detail == 'pd_invoice') {

        //     exit;
        // }

        // print_rr($_POST);
    }

    function hapus_detail_pd()
    {
        if (!$this->input->post()) {
            die;
        }

        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);
        $type = $this->input->post('type', TRUE);

        $where = array(
            'NoUrut' => $NoUrut,
            'NoPD' => $NoPD
        );

        $nama_table = '';

        if ($type == 'tambah_pd') {
            $nama_table = 'table_pd_detail_sementara';
        } else if ($type == 'edit_pd') {
            $nama_table = 'PdDtl';
        }



        $hapus = $this->M_lapangan_pd->hapus_detail_pd($where, $nama_table);
        if ($hapus == TRUE) {
            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;
    }


    function simpan_pd_detail()
    {
        if (!$this->input->post()) {
            die;
        }

        $NoPD = $this->input->post('NoPD', TRUE);
        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);
        $Vol = $this->input->post('Vol', TRUE);
        $Uom = $this->input->post('Uom', TRUE);
        $HrgSatuan = $this->input->post('HrgSatuan', TRUE);


        $nama_table = '';
        $type = $this->input->post('type', TRUE);
        if ($type == 'tambah_pd') {
            $nama_table = 'table_pd_detail_sementara';
        } else {
            $nama_table = 'PdDtl';
        }
        $NoUrut = dapat_nomor_urut_pd_detail($nama_table, $NoPD);

        $data = array(
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut,
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'HrgSatuan' => HapusFormatUang($HrgSatuan),
        );

        $simpan = $this->M_lapangan_pd->simpan_pd_detail($data, $nama_table);
        // $simpan = TRUE;
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;
    }

    function update_pd_detail()
    {
        if (!$this->input->post()) {
            die;
        }

        $NoPD = $this->input->post('NoPD', TRUE);
        $NoUrut = $this->input->post('NoUrut', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);

        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);
        $Vol = $this->input->post('Vol', TRUE);
        $Uom = $this->input->post('Uom', TRUE);
        $HrgSatuan = $this->input->post('HrgSatuan', TRUE);

        $nama_table = '';
        $type = $this->input->post('type', TRUE);
        if ($type == 'tambah_pd') {
            $nama_table = 'table_pd_detail_sementara';
        } else {
            $nama_table = 'PdDtl';
        }

        $data = array(

            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'HrgSatuan' => HapusFormatUang($HrgSatuan),
        );

        $where = array(
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut
        );

        $simpan = $this->M_lapangan_pd->update_pd_detail($data, $where, $nama_table);
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;
    }

    function simpan_PD()
    {
        if (!$this->input->post()) {
            die;
        }

        $NoPD = $this->input->post('NoPD', TRUE);
        // $NoPD = 'PD1328L00001';
        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);

        $NoRef = $this->input->post('NoRef', TRUE);
        $TglPD = $this->input->post('TglPD', TRUE);
        $Jaminan = ($this->input->post('Jaminan')) ? $this->input->post('Jaminan', TRUE) : 0;
        $Deskripsi = $this->input->post('Deskripsi', TRUE);
        $PrdAwal = $this->input->post('PrdAwal', TRUE);
        $PrdAkhir = $this->input->post('PrdAkhir', TRUE);
        $Minggu = $this->input->post('Minggu', TRUE);
        $TipeForm = $this->input->post('TipeForm', TRUE);
        $BuktiPendukung = ($this->input->post('BuktiPendukung', TRUE)) ?  MembuatMenjadiKoma($this->input->post('BuktiPendukung', TRUE)) : '';
        $Keterangan = $this->input->post('Keterangan', TRUE);


        //bayar kepada
        $NoKO = $this->input->post('NoKO', TRUE);
        $NoTagihan = $this->input->post('NoTagihan', TRUE);
        $Nama = $this->input->post('Nama', TRUE);
        $Alamat = $this->input->post('Alamat', TRUE);
        $Telepon = $this->input->post('Telepon', TRUE);
        $NPWP = $this->input->post('NPWP', TRUE);

        //rekening penerima
        $NoRek = $this->input->post('NoRek', TRUE);
        $AtasNama = $this->input->post('AtasNama', TRUE);
        $Bank = $this->input->post('Bank', TRUE);

        //tambahan
        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');
        $KSO = Dapatkan_KSO($JobNo);

        // $dapat_total_PD = $this->M_permintaan_dana_keuangan->dapat_total_PD($NoPD);
        $TotalPD = $this->input->post('TotalPD', TRUE);
        $status_kode = $this->input->post('status_kode', TRUE);

        //cek pddtl sementara, untuk tau sudah di isi belum krn wajib di isi

        // print_rr($_POST);

        $nama_table = '';
        $type = 'tambah_pd';
        if ($type == 'tambah_pd') {
            $nama_table = 'table_pd_detail_sementara';
        } else {
            $nama_table = 'PdDtl';
        }

        if (Cek_Detail_PD($NoPD, $nama_table) == 0) {
            echo 'pd_nol';
            die;
        }

        if (cekNoPD($NoPD) === 'ada') {
            echo 'no_pd_sudah_terpakai';
            // pesan_alert('info', 'No PD sudah terpakai');
            exit;
        }

        $data = array(
            'NoPD' => $NoPD,
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi,


            'NoRef' => $NoRef,
            'TglPD' => $TglPD,
            'Jaminan' => $Jaminan,
            'Deskripsi' => $Deskripsi,
            'PrdAwal' => $PrdAwal,
            'PrdAkhir' => $PrdAkhir,
            'Minggu' => $Minggu,
            'BuktiPendukung' => $BuktiPendukung,
            'Keterangan' => $Keterangan,
            'TipeForm' => $TipeForm,

            //bayar kepada
            'NoKO' => $NoKO,
            'NoTagihan' => $NoTagihan,
            'Nama' => $Nama,
            'Alamat' => $Alamat,
            'Telepon' => $Telepon,
            'NPWP' => $NPWP,

            //rekening penerima
            'NoRek' => $NoRek,
            'AtasNama' => $AtasNama,
            'Bank' => $Bank,

            //tambahan
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
            'KSO' => $KSO,
            'TotalPD' => $TotalPD,

        );

        $simpan_ke_pdhdr = $this->M_lapangan_pd->simpanPD($data, $NoPD, $JobNo, $Alokasi, $status_kode);
        if ($simpan_ke_pdhdr == TRUE) {
            echo 'berhasil';
            pembuatan_sesi_sementara($JobNo, $Alokasi);
            is_pesan('success', 'Berhasil menambahkan data');

            exit;
        }

        echo 'gagal';
        exit;

        // print_rr($data);
    }


    //akhir tambah pd
}

/* End of file Lapangan_pd.php */
