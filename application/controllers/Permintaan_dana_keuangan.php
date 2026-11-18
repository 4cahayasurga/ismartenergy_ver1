<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Mpdf\Mpdf;

class Permintaan_dana_keuangan extends CI_Controller {

    var $Company;
    
    function __construct()
    {
        parent::__construct();
        is_login();

        pembuatan_tbl_InvPD_sementara();
        pembuatan_tbl_invoice_sementara();
        tambah_ledgerNo_di_InvPD_sementara();
        tambah_ledgerNo_di_InvPD();
        pembuatan_tbl_PdDtl_sementara();

        $this->load->model('Permintaan_dana_keuangan_model');
        $this->load->helper('bantu');
        $this->Company = $this->config->item('Company');
        
    }

    function view($JobNo='',$Alokasi='',$dari_tgl='',$sampai_tgl=''){
        if($JobNo==''){
            redirect('Welcome');
        }

        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $data['judul'] = 'PERMINTAAN DANA (KEUANGAN)';
        $data['bodyclass'] = 'sidebar-collapse skin-blue-light';
        $data['data_alokasi'] = AksesAlokasi();

        $query = $this->db->select('JobNm,Lokasi')->where('JobNo',$JobNo)->get('Job')->row();
        $JobNm = $query->JobNm;
        $Lokasi = $query->Lokasi;

        $data['JobNo'] = $JobNo;
        $data['JobNm'] = $JobNm;
        $data['Lokasi'] = $Lokasi;
        $data['Alokasi'] = $Alokasi;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('permintaan_dana_keuangan/view', $data);
        $this->load->view('templates/footer');
    }

    function dapat_list_pd()
    {
        if (!$this->input->post('JobNo', TRUE)) {
            exit;
        }

        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $dari_tgl = $this->input->post('dari_tgl', TRUE);
        $sampai_tgl = $this->input->post('sampai_tgl', TRUE);


        $query = $this->db->query(" SELECT KSO, TipeManajerial, JobNm FROM Job WHERE JobNo='$JobNo'  ")->row();
        $data['KSO'] = $query->KSO;
        $data['TipeManajerial'] = $query->TipeManajerial;
        $data['JobNm'] = $query->JobNm;

        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;

        $dari_tgl_back = ($dari_tgl == '') ? '/0' : '/'.$dari_tgl;
        $sampai_tgl_back = ($sampai_tgl == '') ? '/0' : '/'.$sampai_tgl;

        $myurl = site_url('Permintaan_dana_keuangan/tambah_pd').'/'.$JobNo.'/'.$Alokasi;
        $myurl .= $dari_tgl_back;
        $myurl .= $sampai_tgl_back;

        $data['myurl'] = $myurl;
        // echo $myurl;
        // die;
        // $data['myurl']   


        $data['sumTotalPD'] = $this->sumTotalPD($JobNo, $Alokasi, $dari_tgl, $sampai_tgl);
        // exit;


        $this->load->view('permintaan_dana_keuangan/dapat_list_pd', $data);
    }

    private function sumTotalPD($JobNo, $Alokasi, $dari_tgl='', $sampai_tgl=''){

        $ValidasiTgl = "";
        $sumTotalPD = 0;

        if ($dari_tgl !='' && $sampai_tgl !='') {
            $ValidasiTgl = " AND TglPD>='$dari_tgl' AND TglPD<='$sampai_tgl'   ";
        }

        $q = $this->db->query("SELECT SUM(TotalPD) AS 'sumTotalPD'
            FROM PdHdr
            WHERE JobNo = '$JobNo'
            AND Alokasi = '$Alokasi'
            AND (NoRef IS NULL OR NoRef='')
            ".$ValidasiTgl."
            ");

        if ($q->num_rows() > 0 ) {
            $row = $q->row();
            $sumTotalPD = $row->sumTotalPD;
        }else{
            $sumTotalPD = 0;
        }

        return $sumTotalPD;
    }


    function dapat_list_pd_json()
    {
        if (!$this->input->post()) {
            exit;
        }

        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $dari_tgl = ($this->input->post('dari_tgl', TRUE)) ? $this->input->post('dari_tgl', TRUE) : '';
        $sampai_tgl =  ($this->input->post('sampai_tgl', TRUE)) ? $this->input->post('sampai_tgl', TRUE) : '';


        $where = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi
        );

        header('Content-Type: application/json');
        echo $this->Permintaan_dana_keuangan_model->dapat_list_pd_json($where, $dari_tgl, $sampai_tgl);
    }

    private function dapatkan_hari_senin_atau_monday ($TglPD=''){

        $TglPD_nama_hari = date('l', strtotime($TglPD));
        $TglPD_tanggal = ($TglPD_nama_hari == 'Sunday') ? date('Y-m-d',strtotime('-1 days',strtotime($TglPD))) : date('Y-m-d',strtotime('+1 days',strtotime($TglPD)));
        $PrdAwal_minggu = date('Y-m-d', strtotime('previous monday',strtotime($TglPD_tanggal)));
        $PrdAkhir_minggu = date('Y-m-d',strtotime('+6 days',strtotime($PrdAwal_minggu)));
        return array('PrdAwal_minggu' => $PrdAwal_minggu, 'PrdAkhir_minggu' => $PrdAkhir_minggu);
    }

    private function hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu){

        $dapat_hasil = '';
        $query_get_PrdAwal = $this->db->query("SELECT PrdAwal FROM Job WHERE JobNo='$JobNo' ")->row();
        $PrdAwal_minggu = $query_get_PrdAwal->PrdAwal;

        if ($PrdAwal_minggu == NULL || $PrdAwal_minggu == '' ) {
            $dapat_hasil = 0;
            return $dapat_hasil;
        }

        $diff = strtotime($PrdAkhir_minggu, 0) - strtotime($PrdAwal_minggu, 0);
        $diff = floor($diff / 604800) + 1;
        $dapat_hasil = $diff;
        return $dapat_hasil;
    }

    //pd tambah
    function tambah_pd($JobNo = '', $Alokasi = '',$dari_tgl='',$sampai_tgl='', $PD_Dari='')
    {
        if ($JobNo == '' || $Alokasi == '') {
            redirect('Welcome', 'refresh');
        }

        $TglPD = date('Y-m-d');
        $dapat_hitungan = $this->dapatkan_hari_senin_atau_monday($TglPD);

        $PrdAwal_minggu = $dapat_hitungan['PrdAwal_minggu'];
        $PrdAkhir_minggu = $dapat_hitungan['PrdAkhir_minggu'];
        $HitungMinggu = $this->hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu);

        $data['PrdAkhir'] = $PrdAkhir_minggu;
        $data['PrdAwal'] = $PrdAwal_minggu;
        $data['Minggu'] = $HitungMinggu;

        $data['judul'] = 'ENTRY PERMINTAAN DANA (KEUANGAN)';
        $data['bodyclass'] = 'sidebar-collapse skin-purple-light';

        $query = $this->db->select('JobNm,Lokasi')->where('JobNo',$JobNo)->get('Job')->row();
        $JobNm = $query->JobNm;
        $Lokasi = $query->Lokasi;


        $data['dapat_judul'] = $this->Permintaan_dana_keuangan_model->dapatkan_judul($JobNo);
        $data['no_urut_pd'] = KodeUrutPd($JobNo, $Alokasi,$PD_Dari);

        $data['JobNo'] = $JobNo;
        $data['JobNm'] = $JobNm;
        $data['Lokasi'] = $Lokasi;
        $data['Alokasi'] = $Alokasi;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;


        $data['PD_Dari'] = $PD_Dari;



        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('permintaan_dana_keuangan/tambah_pd', $data);
        $this->load->view('templates/footer');
    }

    function dapat_data_ketika_TglPD_berubah_js(){
        if (!$this->input->post()) {
            echo json_encode(array('status'=>'gagal'));
            die;
        }

        $JobNo = $this->input->post('JobNo',TRUE);
        $TglPD = $this->input->post('TglPD',TRUE);

        $dapat_hitungan = $this->dapatkan_hari_senin_atau_monday($TglPD);
        $PrdAwal_minggu = $dapat_hitungan['PrdAwal_minggu'];
        $PrdAkhir_minggu = $dapat_hitungan['PrdAkhir_minggu'];
        $HitungMinggu = $this->hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu);

        echo json_encode(
            array(
                'status' => 'berhasil',
                'PrdAwal' => $PrdAwal_minggu,
                'PrdAkhir' => $PrdAkhir_minggu,
                'Minggu' => $HitungMinggu
            )
        );

    }


    function hapus_uraian()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);


        $hapus = $this->Permintaan_dana_keuangan_model->hapus_uraian_baru($NoUrut,$NoPD);
        if ($hapus == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
    }


    function dapatkan_data_pddtl_tidak_aktif()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);
        $Company = $this->config->item('Company');

        $query = $this->db->query("SELECT TOP 1 *, (Vol * HrgSatuan) as TotalHarga FROM table_pd_detail_sementara WHERE NoPD='$NoPD' AND NoUrut='$NoUrut' AND Company='$Company' ORDER BY NoUrut ASC")->row_array();

        $Vol = PecahAngka($query['Vol']);

        $data = array(
            'KdRAP' => htmlentities($query['KdRAP']),
            'Uraian' => htmlentities($query['Uraian']),
            'Vol' => htmlentities($Vol),
            'Uom' => htmlentities($query['Uom']),
            'HrgSatuan' => htmlentities(number_format($query['HrgSatuan'])),
            'TotalHarga' => htmlentities($query['TotalHarga']),
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut

        );

        echo json_encode($data);
    }

    function aksi_edit_uraian_pd()
    {
        if (!$this->input->post()) {
            exit;
        }


        $Company = $this->config->item('Company');


        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);

        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);
        $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
        $Uom = $this->input->post('Uom', TRUE);
        $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $data = array(
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'HrgSatuan' => $HrgSatuan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
            'Company' => $Company

        );

        $where = array(
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut
        );

        $simpan = $this->Permintaan_dana_keuangan_model->aksi_update_uraian_pd($data, $where);
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

        // print_rr($_POST);
        // print_rr($data);


    }


    function simpan_pdhdr()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoPD = $this->input->post('NoPD', TRUE);
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


        if (cek_pddtl_sementara($NoPD) == 0) {
            echo 'pd_nol';
            die;
        }

        if (cekNoPD($NoPD) === 'ada') {
            echo 'no_pd_sudah_terpakai';
            pesan_alert('info', 'No PD sudah terpakai');

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

        $simpan_ke_pdhdr = $this->Permintaan_dana_keuangan_model->simpan_ke_pdhdr($data, $NoPD, $JobNo, $Alokasi, $status_kode);
        if ($simpan_ke_pdhdr == TRUE) {
            echo 'berhasil';
            pembuatan_sesi_sementara($JobNo, $Alokasi);
            is_pesan('success', 'Berhasil menambahkan data');

            exit;
        }

        echo 'gagal';
        exit;
    }


    //print

    function print_pd_keuangan($LedgerNo, $NoPD, $JobNo, $Alokasi,$Dari="KEUANGAN")
    {

        if ($NoPD == '' || $JobNo == '') {
            echo '';
            exit;
        }


        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;


        $where_saldo = array(
            'NoPD' => $NoPD,
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi
        );

        $data['LedgerNo'] = $LedgerNo;
        $data['NoPD'] = $NoPD;
        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;

        $pd_data = $this->Permintaan_dana_keuangan_model->dapat_data_pd($LedgerNo);

        $data['data'] = $pd_data;
        $data['getTableUraianDetailPD'] = $this->Permintaan_dana_keuangan_model->dapat_pd_detail($NoPD);
        $data['SaldoSebelumnya'] = $this->Permintaan_dana_keuangan_model->cekSaldoSebelumnya($where_saldo);

        $data['SubTotal'] = 0;
        $data['TotalPermintaan'] = 0;

        $dapat_detail_PJ = $this->db->query("SELECT *, (PjVol * PjHrgSatuan) AS 'PjJumlahHarga' FROM PdDtl WHERE NoPD='$NoPD' AND (NoPJ IS NOT NULL OR NoPJ !='' ) ");
        $data['dapat_detail_PJ'] = $dapat_detail_PJ;


        $NoPJSebelumnya_Saya = '';
        $Saldo_Saya = 0;

        $get_DataPD = $this->db->query("SELECT * FROM PdHdr WHERE NoPD='$NoPD' ");

        if ($get_DataPD->num_rows() > 0 ) {

            $row_saya = $get_DataPD->row_array();
            $NoPJSebelumnya = $row_saya['NoPJSebelumnya'];
            $NoPJ = $row_saya['NoPJ'];

            $NoPD = $row_saya['NoPD'];
            $TipeForm = $row_saya['TipeForm'];

            $TglPD = $row_saya['TglPD'];
            $KSO = $row_saya['KSO'];

            $cek = $this->get_Saldo($NoPD,$TipeForm, $TglPD, $KSO, $Alokasi,$NoPJSebelumnya,$NoPJ);

            $NoPJSebelumnya_Saya = $cek['NoPJSebelumnya'];
            $Saldo_Saya = $cek['Saldo'];

        }


        $array_saya = array();
        $query_PD = $this->Permintaan_dana_keuangan_model->dapat_pd_detail($NoPD);
        $SubTotal_PD = 0;
        
        foreach($query_PD as $PD_data ){
            $SubTotal_PD += $PD_data['JumlahHarga'];
            $array_saya[] = array(
                'NoUrut' => $PD_data['NoUrut'],
                'NoPD' => $PD_data['NoPD'],
                'KdRAP' => $PD_data['KdRAP'],
                'Uraian' => $PD_data['Uraian'],
                'Vol' => $PD_data['Vol'],
                'Uom' => $PD_data['Uom'],
                'HrgSatuan' => $PD_data['HrgSatuan'],
                'UserEntry' => $PD_data['UserEntry'],
                'TimeEntry' => $PD_data['TimeEntry'],
                'JumlahHarga' => $PD_data['JumlahHarga'],
            );
        }


        $array_PJ = array();
        $SubTotal_PJ = 0;
        $cek_PJ = $this->db->query("SELECT NoPJ FROM PdHdr WHERE LedgerNo='$LedgerNo' ")->row();
        $NoPJ_cek = 0;
        if ($cek_PJ->NoPJ !=NULL || $cek_PJ->NoPJ !='' ) {
            $NoPJ_cek = 1;

            foreach($query_PD as $PJ_Data ){
                $SubTotal_PJ += ($PJ_Data['PjVol'] * $PJ_Data['PjHrgSatuan']);

                $array_PJ[] = array(

                    'NoUrut' => $PJ_Data['NoUrut'],
                    'NoPJ' => $PJ_Data['NoPJ'],
                    'NoPD' => $PD_data['NoPD'],
                    'Uom' => $PD_data['Uom'],
                    'KdRAP' => $PD_data['KdRAP'],
                    'PjUraian' => $PJ_Data['PjUraian'],
                    'PjVol' => $PJ_Data['PjVol'],
                    'PjHrgSatuan' => $PJ_Data['PjHrgSatuan'],
                    'PjUserEntry' => $PJ_Data['PjUserEntry'],
                    'PjTimeEntry' => $PJ_Data['PjTimeEntry'],
                    'PJJumlahHarga' => ($PJ_Data['PjVol'] * $PJ_Data['PjHrgSatuan'] ),
                );

                
            }

        }


        //PD
        $data['NoPJSebelumnya_Saya'] = $NoPJSebelumnya_Saya;
        $data['Saldo_Saya'] = $Saldo_Saya;
        $data['SubTotal_PD'] = $SubTotal_PD;
        $data['TotalPermintaan'] = $SubTotal_PD - $Saldo_Saya;
        $data['detailPD'] = $array_saya;


        //PJ
        $data['NoPJ_cek'] = $NoPJ_cek;
        $data['SubTotal_PJ'] = $SubTotal_PJ;
        $data['DanaTersediaDiLapangan_PJ'] = $SubTotal_PD;
        $data['Saldo_saya_PJ'] = $SubTotal_PD - $SubTotal_PJ;
        
        $data['array_PJ'] = $array_PJ;

        $tglBayarPJ = '';
        $CaraBayar = '';

        $query_bayar = $this->db->query("
            SELECT TOP 1 *
            FROM 
            BLE WHERE NoPD='$pd_data[NoPD]'  OR 
            NoPD='$pd_data[NoRef]' ORDER BY TimeEntry DESC");
        if ($query_bayar->num_rows() > 0 ) {
            $row_ble = $query_bayar->row();
            $tglBayarPJ = ($row_ble->TglBayar == NULL || $row_ble== '' ) ? '-' : $row_ble->TglBayar;

        }

        $data['tglBayarPJ']= $tglBayarPJ;

        $data['pj_bayar'] = $query_bayar->row_array();
        $data['Dari'] = $Dari;

        $this->load->view('permintaan_dana_lapangan/print/print_pd_lapangan', $data);
    }

    

    private function get_Saldo($NoPD='', $TipeForm ='', $TglPD='', $KSO='', $Alokasi='', $NoPJSebelumnya='', $NoPJ=''){

        $NoPJ_set = '';
        $Saldo_set = 0;

        if (  ($TglPD >= '2019-12-12' || $TglPD >= '12/12/19'  )  || ($Alokasi !='B' AND $Alokasi !='C' )  ) {

            $query_saya = $this->db->query("SELECT NoPJSebelumnya FROM PdHdr WHERE NoPD='$NoPD'");
            if ($query_saya->num_rows() > 0 ) {
                // return 'vvv';

                $row_saya = $query_saya->row_array();

                if ($row_saya['NoPJSebelumnya'] == NULL || $row_saya['NoPJSebelumnya'] == ''  ) {

                    $NoPJ_set = '';
                    $Saldo_set = 0;

                }else{

                    $query = $this->db->query(" SELECT Saldo FROM PdHdr WHERE NoPJ='$row_saya[NoPJSebelumnya]' ");

                    if ($query->num_rows() > 0 ) {

                        $row = $query->row();

                        $NoPJ_set = $row_saya['NoPJSebelumnya'];
                        $Saldo_set = $row->Saldo;

                    }


                }

            }

        }else{

        }

        return array('NoPJSebelumnya'=>$NoPJ_set,'Saldo' => $Saldo_set);

    }


    function aksi_approve(){
        if (!$this->input->post()) {
            exit;
        }

        $LedgerNo = $this->input->post('LedgerNo', TRUE);
        $value_status_pd = $this->input->post('value_status_pd', TRUE);

        //values status pd, kalau 0 itu belum di approve, lalu kalau 1 berarti sudah di approve dan ingin melakukan unapprove

        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');


        $where = array(
            'LedgerNo' => $LedgerNo
        );

        $data['ApprovedByAK'] = NULL;
        $data['TimeApprovedAK'] = NULL;

        if($value_status_pd == 0){
            //ketika melakukan approve
            $data['ApprovedByAK'] = $UserEntry;
            $data['TimeApprovedAK'] = $TimeEntry;
        }

        if($value_status_pd == 1){




            $cek_jika_sudah_KK = $this->db->query(" SELECT NoPD,ApprovedByKK FROM PdHdr WHERE LedgerNo='$LedgerNo' AND ApprovedByKK IS NOT NULL  ");
            if ($cek_jika_sudah_KK->num_rows() > 0 )  {
                echo 'approve_kk_ada';
                exit;
            }

            //ketika melakukan unapprove
            $data['ApprovedByAK'] = NULL;
            $data['TimeApprovedAK'] = NULL;
        }

        $simpan = $this->Permintaan_dana_keuangan_model->aksi_approve($where,$data);
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;


    }


    //detail dan edit
    function detail_pd($LedgerNo = '', $NoPD = '', $JobNo = '', $Alokasi = '')
    {
        if ($LedgerNo == '' || $NoPD == '' || $JobNo == '' || $Alokasi == '') {
            exit;
        }



        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $data['NoPD'] = $NoPD;
        $data['LedgerNo'] = $LedgerNo;


        $data['dapat_judul'] = $this->Permintaan_dana_keuangan_model->dapatkan_judul($JobNo);
        $data['data_pdhdr'] = $this->Permintaan_dana_keuangan_model->dapat_data_pdhdr($LedgerNo);
        $data['judul'] = "UPDATE PERMINTAAN DANA (keuangan)";


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('permintaan_dana_keuangan/detail_atau_edit_keuangan/form_edit_pd_keuangan', $data);
        $this->load->view('templates/footer');
    }

    function dapatkan_pddtl_edit()
    {
        if (!$this->input->post()) {
            exit;
        }


        $NoPD = $this->input->post('NoPD', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);

        $text_button_mati = $this->input->post('text_button_mati', TRUE);

        $where = array(
            'NoPD' => $NoPD,
            'Alokasi' => $Alokasi,
            'JobNo' => $JobNo,
        );

        $SaldoSebelumnya = $this->Permintaan_dana_keuangan_model->cekSaldoSebelumnya($where);

        $query_dapat_pddtl = $this->db->query(" SELECT *, (Vol * HrgSatuan) as Total FROM PdDtl WHERE NoPD='$NoPD' ORDER BY NoUrut DESC");

        $tbody = '';
        $tfoot = '';

        if ($query_dapat_pddtl->num_rows() == 0) {
            $tbody .= '

            <table class="table table_pddtl" id="mytabledetail">
            <thead style="font-size:12px !important;">
            <tr class="bg-info">
            <th>#</th>
            <th>No.</th>
            <th>Kode RAP</th>
            <th>Uraian</th>
            <th>Vol</th>
            <th>Sat</th>
            <th>Harga Satuan (Rp)</th>
            <th>Jumlah Harga (Rp)</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <td>data tidak ditemukan</td>
            </tr>
            </tbody>
            </table>';



            echo $tbody;
            echo $tfoot;
            die;
        } else {

            $no = 1;
            $subtotal = 0;

            $tbody .= '
            <table class="table table_pddtl" id="mytabledetail" >
            <thead>
            <tr class="bg-info">
            <th>#</th>
            <th>No.</th>
            <th>Kode RAP</th>
            <th>Uraian</th>
            <th>Vol</th>
            <th>Sat</th>
            <th>Harga Satuan (Rp)</th>
            <th>Jumlah Harga (Rp)</th>
            </tr>
            </thead>
            <tbody>';

            foreach ($query_dapat_pddtl->result_array() as $data_pddtl) :

                $subtotal += $data_pddtl['Total'];
                $norut = $data_pddtl['NoUrut'];

                $tbody .= '
                <tr>
                <td style="text-align:center;">
                <a href="javascript:void(0)" class="btn btn-xs btn-danger '.$text_button_mati.'" onclick="hapus_uraian_mdl_edit(' . $norut . ')"  ><i class="fa fa-trash"></i> Hapus</a>
                <button type="button" class="btn btn-xs btn-warning" onclick="edit_uraian_mdl_edit(' . $norut . ')"  ><i class="fa fa-edit"></i> Edit</button>
                </td>
                <td style="text-align:center;">' . $no++ . '.</td>
                <td>' . $data_pddtl['KdRAP'] . '</td>
                <td>' . strtoupper($data_pddtl['Uraian']) . '</td>
                <td style="text-align:right;">' . UbahGabunganUang($data_pddtl['Vol']) . '</td>
                <td>' . strtoupper($data_pddtl['Uom']) . '</td>
                <td style="text-align:right;">' . UbahGabunganUang($data_pddtl['HrgSatuan']) . '</td>
                <td style="text-align:right;">' . UbahGabunganUang($data_pddtl['Total']) . '</td>
                </tr>

                ';


            endforeach;
            $tbody .= '
            </tbody>
            </table>';

            $HitungTotalPermintaan =  $subtotal - $SaldoSebelumnya;

            $tfoot .= '
            <table style="width: 100%;" style="font-size:12px !important;">
            <tr>
            <th style="text-align: right;font-size:12px;padding-right:5px;">Sub Total</th>
            <td style="width: 180px !important;font-size:12px;">
            <input type="text" class="form-control form-control-sm uang" style="text-align: right;font-size:12px;" value="' . UbahGabunganUang($subtotal) . '" readonly>
            </td>
            </tr>
            </table>
            ';

            $tfoot .= '
            <table style="width: 100%;" class="text-right">
            <tr>
            <th style="text-align: right;font-size:12px;padding-right:5px;">Saldo sebelumnya dari No</th>

            <td style="width: 180px !important;font-size:12px;padding-right:5px;">
            <input type="text"  class="form-control form-control-sm" style="text-align: right;" value="' . $NoPD . '" disabled>
            </td>
            <td style="width: 180px !important;font-size:12px;">
            <input type="text"  class="form-control form-control-sm" style="text-align: right;font-size:12px;" value="' . UbahGabunganUang($SaldoSebelumnya) . '" disabled>
            </td>
            </tr>
            </table>';

            $tfoot .= '
            <table style="width: 100%;">
            <tr>
            <th style="text-align: right;font-size:12px;padding-right:5px;">Total Permintaan</th>
            <td style="width: 180px !important;font-size:12px;">
            <input type="text"  style="text-align: right;font-size:12px;" class="form-control form-control-sm" value="' . UbahGabunganUang($HitungTotalPermintaan) . '" disabled>
            <input type="hidden"  style="text-align: right;font-size:12px;" name="mytotalpd" id="mytotalpd" class="form-control form-control-sm" value="' . $HitungTotalPermintaan . '">
            </td>
            </tr>
            </table>

            ';
        }

        echo $tbody;
        echo $tfoot;
    }

    function hapus_uraian_mdl_edit()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);

        $where = array(
            'NoUrut' => $NoUrut,
            'NoPD' => $NoPD
        );

        $hapus = $this->Permintaan_dana_keuangan_model->hapus_uraian_mdl_edit($where);
        if ($hapus == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
    }

    function dapatkan_data_pddtl_mdl_edit()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);

        $query = $this->db->query("SELECT TOP 1 *, (Vol * HrgSatuan) as TotalHarga FROM PdDtl WHERE NoPD='$NoPD' AND NoUrut='$NoUrut'  ORDER BY NoUrut DESC")->row_array();

        $Vol = PecahAngka($query['Vol']);

        $data = array(
            'KdRAP' => htmlentities($query['KdRAP']),
            'Uraian' => htmlentities(strtoupper($query['Uraian'])),
            'Vol' => htmlentities($Vol),
            'Uom' => htmlentities($query['Uom']),
            'HrgSatuan' => htmlentities(number_format($query['HrgSatuan'])),
            'TotalHarga' => htmlentities($query['TotalHarga']),
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut,


        );

        echo json_encode($data);
    }

    function aksi_simpan_uraian_pd_mdl_edit()
    {
        if (!$this->input->post()) {
            exit;
        }


        $NoPD = $this->input->post('NoPD', TRUE);
        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);
        $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
        $Uom = $this->input->post('Uom', TRUE);
        $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $query_nourut = $this->db->query(" SELECT TOP 1 NoUrut FROM PdDtl WHERE NoPD='$NoPD' ORDER BY NoUrut DESC  ")->row_array();

        $NoUrut =  (int) $query_nourut['NoUrut'] + 1;

        $data = array(
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut,
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'HrgSatuan' => $HrgSatuan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,

        );

        $simpan = $this->Permintaan_dana_keuangan_model->aksi_simpan_uraian_pd_mdl_edit($data);
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

        // print_rr($_POST);
        // print_rr($data);


    }

    function aksi_edit_uraian_pd_mdl_edit()
    {
        if (!$this->input->post()) {
            exit;
        }

        // echo 'ok';
        // die;

        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);

        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);
        $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
        $Uom = $this->input->post('Uom', TRUE);
        $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $data = array(
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'HrgSatuan' => $HrgSatuan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,

        );

        $where = array(
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut
        );

        $simpan = $this->Permintaan_dana_keuangan_model->aksi_update_uraian_pd_mdl_edit($data, $where);
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

        // print_rr($_POST);
        // print_rr($data);


    }

    function simpan_pdhdr_mdl_edit()
    {
        if (!$this->input->post()) {
            exit;
        }

        // print_rr($_POST);
        // die;


        $LedgerNo = $this->input->post('LedgerNo', TRUE);

        $NoPD = $this->input->post('NoPD', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);

        if (cek_pddtl_sementara_mdl_edit($NoPD) == 0) {
            echo 'pd_nol';
            die;
        }





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
        $TotalPD = $this->input->post('TotalPD', TRUE);

        $my_no_ko = $JobNo . '_INT';



        $data = array(

            'NoRef' => $NoRef,
            'TglPD' => $TglPD,
            'Jaminan' => $Jaminan,
            'Deskripsi' => $Deskripsi,
            'PrdAwal' => $PrdAwal,
            'PrdAkhir' => $PrdAkhir,
            'Minggu' => $Minggu,
            'BuktiPendukung' => $BuktiPendukung,
            'Keterangan' => $Keterangan,

            //bayar kepada
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
            'TotalPD' => $TotalPD,
            'TipeForm' => $TipeForm,
            'NoKO' => $NoKO

        );


        if ($NoKO !== $my_no_ko) {
            unset($data['Nama']);
            unset($data['Alamat']);
            unset($data['Telepon']);
            unset($data['NPWP']);
            unset($data['NoRek']);
            unset($data['AtasNama']);
            unset($data['Bank']);
        }
        


        $edit = $this->Permintaan_dana_keuangan_model->simpan_ke_pdhdr_mdl_edit($data, $LedgerNo);
        if ($edit == TRUE) {
            echo 'berhasil';
            pembuatan_sesi_sementara($JobNo, $Alokasi);
            is_pesan('success', 'Data berhasil di ubah');
            exit;
        }

        echo 'gagal';
        exit;
    }


    //perubahan terbaru

    function dapat_no_ko_dan_vendor()
    {
        if (!$this->input->post()) {
            exit;
        }

        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);

        $type = $this->input->post('type',TRUE);
        $TipeForm = $this->input->post('TipeForm',TRUE);

        $data = $this->Permintaan_dana_keuangan_model->dapatkan_list_no_ko($JobNo,$TipeForm);

        $internal = $JobNo . '_INT';
        $data['internal'] = $internal;
        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $data['TipeForm'] = $TipeForm;
        $this->load->view('permintaan_dana_keuangan/form_no_ko',$data);

    }

    function dapat_vendor()
    {
        if (!$this->input->post()) {
            exit;
        }

        $VendorId = $this->input->post('VendorId', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $query = $this->Permintaan_dana_keuangan_model->dapatkan_vendor($NoKO,$JobNo);
        echo json_encode($query);

    }

    function tampil_table_dari_NoKO(){

        if (!$this->input->post()) {
            exit;
        }


        $NoPD = $this->input->post('NoPD', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);

        $pindah_data = $this->Permintaan_dana_keuangan_model->KoDtl_ke_PdDtl_sementara($JobNo,$Alokasi,$NoPD,$NoKO);
    }

    function dapat_PdDtl_sementara(){
        if (!$this->input->post()) {
            exit;
        }


        $NoPD = $this->input->post('NoPD', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $TipeForm = $this->input->post('TipeForm', TRUE);

        $NoKO = $this->input->post('NoKO', TRUE);

        $KSO = $this->db->query("SELECT TOP 1 KSO FROM Job WHERE JobNo='$JobNo' ")->row()->KSO;
        $KSO = ($KSO <> 0) ? 1 : 0;

        // $TipeForm = $this->db->select('TipeForm')->where('NoPD',$NoPD)->get('PdHdr')->row()->TipeForm;
        // print_rr($KSO);
        // exit;

        $data = array(
            'NoPD' => $NoPD,
            'Alokasi' => $Alokasi,
            'JobNo' => $JobNo,
            'NoKO' => $NoKO,

        );

        $data['query_dapat_pddtl'] = $this->Permintaan_dana_keuangan_model->dapat_PdDtl_sementara($NoPD);
        // $data['dapat_NoPJSebelumnya'] = $this->Permintaan_dana_keuangan_model->dapat_NoPJSebelumnya($NoPD);
        $data['dapat_NoPJSebelumnya'] = $this->db->query("  WITH TmpQuery AS (
            SELECT NoPJ, Saldo, (SELECT COUNT(NoPJSebelumnya) FROM PdHdr B WHERE B.NoPJSebelumnya=PdHdr.NoPJ) AS 'AlreadyUsedPJ'
            FROM PdHdr WHERE JobNo='$JobNo' AND TipeForm='$TipeForm' AND KSO='$KSO' AND RejectBy IS NULL
            AND NoPJ IS NOT NULL AND NoRef IS NULL 
            )
            SELECT * FROM TmpQuery WHERE AlreadyUsedPJ=0 AND Saldo<>0 ORDER BY NoPJ DESC")->result_array();

        // $tes = $this->db->query("WITH TmpQuery AS (
        //     SELECT NoPJ, Saldo, (SELECT COUNT(NoPJSebelumnya) FROM PdHdr B WHERE B.NoPJSebelumnya=PdHdr.NoPJ) AS 'AlreadyUsedPJ'
        //     FROM PdHdr WHERE JobNo='$JobNo' AND TipeForm='01C' AND KSO='$KSO' AND RejectBy IS NULL
        //     AND NoPJ IS NOT NULL AND NoRef IS NULL 
        //     )
        //     SELECT * FROM TmpQuery WHERE AlreadyUsedPJ=0 AND Saldo<>0 ORDER BY NoPJ DESC")->result();
        // print_rr($data);
        // exit;

        $this->load->view('permintaan_dana_keuangan/table_pddtl',$data);
    }

    function dapatkan_Saldo_NoPJSebelumnya(){
       if(!$this->input->post()){
        exit;
    }

        // print_rr($_POST);
        // exit;

    $NoPJSebelumnya = $this->input->post('NoPJSebelumnya');
    $SubTotal = $this->input->post('SubTotal');
    $TotalPermintaan = $this->input->post('TotalPermintaan');

    $JobNo = $this->input->post('JobNo');
    $TipeForm = $this->input->post('TipeForm');

    $Saldo = 0;
    $myquery = $this->db->query("SELECT Saldo FROM PdHdr WHERE NoPJ='$NoPJSebelumnya' ");
    if ($myquery->num_rows() > 0 ) {
        $row = $myquery->row();
        $Saldo = $row->Saldo;
    }

    $TotalPermintaan = 0;
    $TotalPermintaan = $SubTotal - $Saldo;

    $data = array(
        'SubTotal' => $SubTotal,
        'Saldo' => $Saldo,
        'Saldo_view_saja' => uang_koma_genap_decimal_kedua($Saldo),
        'TotalPermintaan' => number_format($TotalPermintaan, 2, '.', ',')
    );
    echo json_encode($data);
}

function aksi_simpan_uraian_pd()
{
    if (!$this->input->post()) {
        exit;
    }



    $NoPD = $this->input->post('NoPD', TRUE);
    $KdRAP = $this->input->post('KdRAP', TRUE);
    $Uraian = $this->input->post('Uraian', TRUE);
        // $Vol = ($this->input->post('Vol', TRUE) == 0 || $this->input->post('Vol', TRUE) == '') ? 1 : $this->input->post('Vol', TRUE);
    $Uom = $this->input->post('Uom', TRUE);
        // $HrgSatuan = $this->input->post('HrgSatuan', TRUE);

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $Company = $this->config->item('Company');

    $Vol = ($this->input->post('Vol',TRUE) == '' || $this->input->post('Vol',TRUE) == NULL ) ? 0 : $this->input->post('Vol',TRUE);
    $HrgSatuan = ($this->input->post('HrgSatuan',TRUE) == '' || $this->input->post('HrgSatuan',TRUE) == NULL ) ? 0 : $this->input->post('HrgSatuan',TRUE);

    $Vol = HapusKomaPHP($Vol);
    $HrgSatuan = HapusKomaPHP($HrgSatuan);


    $NoUrut = $this->Permintaan_dana_keuangan_model->pembuatan_NoUrut('pd_baru',$NoPD);

        // echo $NoUrut;
        // exit;

    $data = array(
        'NoPD' => $NoPD,
        'NoUrut' => $NoUrut,
        'KdRAP' => $KdRAP,
        'Uraian' => $Uraian,
            // 'Vol' => HapusKomaPHP($Vol),
        'Uom' => $Uom,
            // 'HrgSatuan' => HapusKomaPHP($HrgSatuan),
        'Vol' => $Vol,
        'HrgSatuan' => preg_replace('/[,]/', '', $HrgSatuan),
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,
        'Company' => $Company

    );

        // print_rr($_POST);
        // echo "<hr>";
        //  print_rr($data);
        // exit;

    $simpan = $this->Permintaan_dana_keuangan_model->aksi_simpan_uraian_pd($data);
    if ($simpan == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;

        // print_rr($_POST);
        // print_rr($data);


}

function dapatkan_form_edit_uraian(){

    if (!$this->input->post()) {
        exit;
    }

    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoPD = $this->input->post('NoPD', TRUE);
    $Company = $this->config->item('Company');

    $query = $this->db->query("SELECT TOP 1 *, (Vol * HrgSatuan) as TotalHarga FROM PdDtl_sementara WHERE NoPD='$NoPD' AND NoUrut='$NoUrut' AND Company='$Company' ORDER BY NoUrut ASC")->row_array();

    $KdRAP = htmlentities($query['KdRAP']);
    $Uraian = htmlentities($query['Uraian']);
    $Vol = htmlentities($query['Vol']);
    $Uom = htmlentities($query['Uom']);
    $HrgSatuan = htmlentities($query['HrgSatuan']);
    $TotalHarga = htmlentities($query['TotalHarga']);

    $data = array(
        'KdRAP' => $KdRAP,
        'Uraian' =>$Uraian,
        'Vol' => uang_koma_genap_decimal_kedua($Vol),
        'Uom' => $Uom,
        'HrgSatuan' => uang_koma_genap_decimal_kedua($HrgSatuan),
            // 'TotalHarga' => uang_koma_genap_decimal_kedua($TotalHarga),
        'NoPD' => $NoPD,
        'NoUrut' => $NoUrut

    );

    echo json_encode($data);
}


function aksi_edit_uraian_di_tambah_pd()
{
    if (!$this->input->post()) {
        exit;
    }

        // die;

    $JobNo = $this->input->post('JobNo', TRUE);
    $NoPD = $this->input->post('NoPD', TRUE);
    $NoUrut = $this->input->post('NoUrut', TRUE);
    $Alokasi = $this->input->post('Alokasi', TRUE);
    $Company = $this->config->item('Company');

    $KdRAP = $this->input->post('KdRAP', TRUE);
    $Uraian = $this->input->post('Uraian', TRUE);
        // $Vol = ($this->input->post('Vol', TRUE) == 0 || $this->input->post('Vol', TRUE) == '') ? 1 : $this->input->post('Vol', TRUE);
    $Uom = $this->input->post('Uom', TRUE);
        // $HrgSatuan = $this->input->post('HrgSatuan', TRUE);

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $Vol = ($this->input->post('Vol',TRUE) == '' || $this->input->post('Vol',TRUE) == NULL ) ? 0 : $this->input->post('Vol',TRUE);
    $HrgSatuan = ($this->input->post('HrgSatuan',TRUE) == '' || $this->input->post('HrgSatuan',TRUE) == NULL ) ? 0 : $this->input->post('HrgSatuan',TRUE);

    $Vol = HapusKomaPHP($Vol);
    $HrgSatuan = HapusKomaPHP($HrgSatuan);

    $data = array(
        'NoPD' => $NoPD,
        'NoUrut' => $NoUrut,
        'KdRAP' => $KdRAP,
        'Uraian' => $Uraian,
            // 'Vol' => HapusKomaPHP($Vol),
        'Uom' => $Uom,
            // 'HrgSatuan' => HapusKomaPHP($HrgSatuan),
        'Vol' => $Vol,
        'HrgSatuan' => preg_replace('/[,]/', '', $HrgSatuan),
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,
    );

    $where = array(
        'NoPD' => $NoPD,
        'NoUrut' => $NoUrut,
        'Company' => $Company,
    );

    $ubah = $this->Permintaan_dana_keuangan_model->aksi_ubah_data_uraian_di_tambah_pd($data,$where);
    if ($ubah == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;

}

function hapus_multiple_uraian()
{

    if (!$this->input->post()) {
        exit;
    }

    $NoUrut = $this->input->post('NoUrut',TRUE);
    $NoPD = $this->input->post('NoPD',TRUE);

    $NoUrut = implode(",", $NoUrut);
    $hapus = $this->Permintaan_dana_keuangan_model->aksi_hapus_multiple_uraian($NoUrut,$NoPD);
    if ($hapus == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;
}

function dapat_saldo_NoPJSebelumnya(){
    if (!$this->input->post()) {
        exit;
    }
    $NoPJSebelumnya = $this->input->post('NoPJSebelumnya',TRUE);
    $query = $this->db->query("SELECT Saldo FROM PdHdr WHERE NoPJ='$NoPJSebelumnya' ")->row_array();
    $Saldo = ($query['Saldo'] == NULL || $query['Saldo'] == '') ? 0 : new_decimal($query['Saldo']); 
    echo $Saldo;
}

function dapat_saldo_NoPJSebelumnya_terbaru(){
    if (!$this->input->post()) {
        exit;
    }

    $NoPJSebelumnya = $this->input->post('NoPJSebelumnya',TRUE);
    $SubTotal = ($this->input->post('SubTotal',TRUE)) ? $this->input->post('SubTotal',TRUE) : 0;

    $query = $this->db->query("SELECT Saldo FROM PdHdr WHERE NoPJ='$NoPJSebelumnya' ")->row_array();
    $Saldo = ($query['Saldo'] == NULL || $query['Saldo'] == '') ? 0 : $query['Saldo'];

    $TotalPermintaan = 0;
    $TotalPermintaan = $SubTotal - $Saldo;

    $data = array(
        'SubTotal' => uang_koma_genap_decimal_kedua($SubTotal),
        'Saldo' => uang_koma_genap_decimal_kedua($Saldo),
        'TotalPermintaan' => uang_koma_genap_decimal_kedua($TotalPermintaan)
    );

    echo json_encode($data);
}

function Aksi_Simpan_PdHdr_tambah_pd(){
    if (!$this->input->post()) {
        exit;
    }


        // print_rr($_POST);
        // die;


    $NoPD = $this->input->post('NoPD', TRUE);
    $JobNo = $this->input->post('JobNo', TRUE);
    $Alokasi = $this->input->post('Alokasi', TRUE);

    $Cek_Table_PdDtl_sementara = $this->Permintaan_dana_keuangan_model->Cek_Table_PdDtl_sementara($NoPD);
    if ($Cek_Table_PdDtl_sementara == 0) {
        echo 'pd_detail_kosong';
        exit;
    }

    $CekNoPD_ada_atau_tidak = $this->Permintaan_dana_keuangan_model->CekNoPD_ada_atau_tidak($NoPD);
    if ($CekNoPD_ada_atau_tidak > 0) {
        echo 'pd_sudah_ada';
        exit;
    }
        // exit;


    $NoRef = $this->input->post('NoRef', TRUE);
    $TglPD = $this->input->post('TglPD', TRUE);
    $Deskripsi = $this->input->post('Deskripsi', TRUE);
    $PrdAwal = $this->input->post('PrdAwal', TRUE);
    $PrdAkhir = $this->input->post('PrdAkhir', TRUE);
    $Minggu = $this->input->post('Minggu', TRUE);
    $TipeForm = $this->input->post('TipeForm', TRUE);
    $Keterangan = $this->input->post('Keterangan', TRUE);
    $BuktiPendukung = ($this->input->post('BuktiPendukung', TRUE)) ?  MembuatMenjadiKoma($this->input->post('BuktiPendukung', TRUE)) : '';

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


    $status_kode = $this->input->post('status_kode', TRUE);
    $Jaminan = ($this->input->post('Jaminan')) ? $this->input->post('Jaminan', TRUE) : 0;

        // $SubTotal = $this->input->post('SubTotal',TRUE);
        // $Saldo = $this->input->post('Saldo',TRUE);
        // $TotalPermintaan = $this->input->post('TotalPermintaan',TRUE);

    $SubTotal = $this->input->post('SubTotal',TRUE);
    $SubTotal = HapusKomaPHP($SubTotal);
    $SubTotal = preg_replace('/[,]/', '', $SubTotal);

    $Saldo = $this->input->post('Saldo',TRUE);
    $Saldo = HapusKomaPHP($Saldo);
    $Saldo = preg_replace('/[,]/', '', $Saldo);

    $TotalPermintaan = $this->input->post('TotalPermintaan',TRUE);
    $TotalPermintaan = HapusKomaPHP($TotalPermintaan);
    $TotalPermintaan = preg_replace('/[,]/', '', $TotalPermintaan);

    $NoPJSebelumnya = ($this->input->post('NoPJSebelumnya') == '') ? NULL : $this->input->post('NoPJSebelumnya');


    $PD_Dari = $this->input->post('PD_Dari',TRUE);

        // echo 'berhasil';
        // die;

    $data = array(
        'NoPD' => $NoPD,
        'JobNo' => $JobNo,
        'Alokasi' => $Alokasi,


        'NoRef' => NULL,
        'TglPD' => $TglPD,
        'Jaminan' => $Jaminan,
        'Deskripsi' => $Deskripsi,
        'PrdAwal' => $PrdAwal,
        'PrdAkhir' => $PrdAkhir,
        'Minggu' => $Minggu,
        'TipeForm' => $TipeForm,
        'BuktiPendukung' => $BuktiPendukung,
        'Keterangan' => $Keterangan,


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
        'KSO' => dapatkan_KSO($JobNo),
            // 'TotalPD' => HapusKomaPHP($SubTotal),
            // 'Saldo' => HapusKomaPHP($Saldo),
        'TotalPD' => $SubTotal,
        'Saldo' => $Saldo,
        'NoPJSebelumnya' => $NoPJSebelumnya,

    );

    $array_dari_InvPD_sementara = array(
        'JobNo' => $JobNo,
        'NoPD' => $NoPD,
        'NoKO' => $NoKO,
        'Company' => $this->Company
    );

        // print_rr($array_dari_InvPD_sementara);
        // die;

        // print_rr($_POST);
        // echo "<hr>";
        // print_rr($data);
        // return false;

    $simpan_pd = $this->Permintaan_dana_keuangan_model->simpan_data_dari_module_tambah_pd_keuangan($data, $NoPD, $JobNo, $Alokasi, $status_kode, $array_dari_InvPD_sementara,$PD_Dari);

    if ($simpan_pd == TRUE) {

        echo 'berhasil';
        // is_pesan('success', 'Berhasil menambahkan data');
        exit;
    }
    echo 'gagal';
        // is_pesan('error', 'Gagal saat menambahkan data');
    exit;

}

private function cek_approve_PD_per_jabatan($NoPD='',$ApprovedByAK='',$ApprovedByKT='',$ApprovedByKK='',$fromPD='')
{
    if ($NoPD == '') {
        return '';
        die;
    }

    if ($fromPD =='') {
        return 'terjadikesalahan';
    }

    $ApprovedByAK = ($ApprovedByAK == null || $ApprovedByAK =='') ? 'kosongAK' : $ApprovedByAK;
    $ApprovedByKT = ($ApprovedByKT == null || $ApprovedByKT =='') ? 'kosongKT' : $ApprovedByKT;
    $ApprovedByKK = ($ApprovedByKK == null || $ApprovedByKK =='') ? 'kosongKK' : $ApprovedByKK;

    $alert_jadi_satu = '';

    $khusus_inputan = NULL;
    $aktif_or_tidak = 'disabled=""';
    $tombol_btn = '';

    if ($ApprovedByAK !='kosongAK') {

        $alert_jadi_satu .= '
        <div class="col-sm-3">
        <table width="100%" class="bg-red" style="margin-bottom:3px !important;">
        <tr>
        <td>
        <i class="icon fa fa-check"></i> Approved By (<u>'.$ApprovedByAK.'</u>)
        </td>
        </tr>
        </table>
        </div>';

        $khusus_inputan = 'disabled';
        $aktif_or_tidak = 'disabled="disabled"';
        $tombol_btn = 'hidden';
    }

    if ($ApprovedByKT !='kosongKT') {

        $alert_jadi_satu .= '
        <div class="col-sm-3">
        <table width="100%" class="bg-yellow" style="margin-bottom:3px !important;">
        <tr>
        <td>
        <i class="icon fa fa-check"></i> Approved By (<u>'.$ApprovedByKT.'</u>)
        </td>
        </tr>
        </table>

        </div>';

        $khusus_inputan = 'disabled';
        $aktif_or_tidak = 'disabled="disabled"';
        $tombol_btn = 'hidden';
    }

    if ($ApprovedByKK !='kosongKK') {

        $alert_jadi_satu .= '
        <div class="col-sm-3">
        <table width="100%" class="bg-green" style="margin-bottom:3px !important;">
        <tr>
        <td>
        <i class="icon fa fa-check"></i> Approved By (<u>'.$ApprovedByKK.'</u>)
        </td>
        </tr>
        </table>

        </div>';

        $khusus_inputan = 'disabled';
        $aktif_or_tidak = 'disabled="disabled"';
        $tombol_btn = 'hidden';
    }





    $array = array(
        'alert_jadi_satu' => $alert_jadi_satu,
        'khusus_inputan' => $khusus_inputan,
        'aktif_or_tidak' => $aktif_or_tidak,
        'tombol_btn' => $tombol_btn,
    );

    return $array;


}



function ViewPdKeuangan($LedgerNo='', $JobNo='', $Alokasi='', $dari_tgl='', $sampai_tgl='' ){
    if ($LedgerNo == '') {
        redirect('Job','refresh');
        exit;
    }



    $get_DataPD = $this->Permintaan_dana_keuangan_model->dapatDataPD($LedgerNo);
    $NoPD = $get_DataPD['NoPD'];
    $ApprovedByAK = $get_DataPD['ApprovedByAK'];
    $ApprovedByKT = $get_DataPD['ApprovedByKT'];
    $ApprovedByKK = $get_DataPD['ApprovedByKK'];
    $fromPD = 'KEUANGAN';

    $where_for_cek = array(
        'NoPD' => $NoPD,
        'ApprovedByAK' => $ApprovedByAK,
        'ApprovedByKT' => $ApprovedByKT,
        'ApprovedByKK' => $ApprovedByKK,
        'fromPD' => $fromPD,
    );



    $cek = $this->cek_approve_PD_per_jabatan($NoPD, $ApprovedByAK, $ApprovedByKT, $ApprovedByKK, $fromPD);
    $data['alert_jadi_satu'] = $cek['alert_jadi_satu'];
    $data['khusus_inputan'] = $cek['khusus_inputan'];
    $data['aktif_or_tidak'] = $cek['aktif_or_tidak'];
    $data['tombol_btn'] = $cek['tombol_btn'];

        // print_rr($cek);
        // die;/

        // $data['alert_jadi_satu'] = '';
        // $data['alert_jadi_satu'] = $this->cek_approve_PD_per_jabatan($NoPD, $ApprovedByAK, $ApprovedByKT, $ApprovedByKK, $fromPD);


    $user_id = $this->session->userdata('MIS_LOGGED_ID');

    $data['judul'] = 'DETAIL PERMINTAAN DANA (KEUANGAN)';
    $data['bodyclass'] = 'sidebar-collapse skin-blue-light';
    $data['dataPD'] = $get_DataPD;
    $data['LedgerNo'] = $LedgerNo;

    $data['JobNo'] = $JobNo;
    $data['Alokasi'] = $Alokasi;

    $data['dari_tgl'] = $dari_tgl;
    $data['sampai_tgl'] = $sampai_tgl;

        // unset($data['alert_jadi_satu']['aktif_or_tidak']);
        // print_rr($data);
        // die;


    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('permintaan_dana_keuangan/view_PD_keuangan', $data);
    $this->load->view('templates/footer');


}



    //EDIT PD
function PindahData_PdDtl_ke_PdDtl_sementara_EditPD(){

    if (!$this->input->post()) {
        exit;
    }


    $NoPD = $this->input->post('NoPD', TRUE);
    $Alokasi = $this->input->post('Alokasi', TRUE);
    $JobNo = $this->input->post('JobNo', TRUE);
    $LedgerNo = $this->input->post('LedgerNo', TRUE);

        // print_rr($_POST);
        // exit;

    $pindah_data = $this->Permintaan_dana_keuangan_model->PindahData_PdDtl_ke_PdDtl_sementara_EditPD($JobNo,$Alokasi,$NoPD,$LedgerNo);

}

function dapat_PdDtl_sementara_EditPD(){

    if (!$this->input->post()) {
        exit;
    }


    $NoPD = $this->input->post('NoPD', TRUE);
    $Alokasi = $this->input->post('Alokasi', TRUE);
    $JobNo = $this->input->post('JobNo', TRUE);
    $LedgerNo = $this->input->post('LedgerNo', TRUE);
    $KSO = $this->db->query("SELECT TOP 1 KSO FROM Job WHERE JobNo='$JobNo' ")->row()->KSO;
    $KSO = ($KSO <> 0) ? 1 : 0;
    $NoPJSebelumnya = $this->db->select('NoPJSebelumnya')->where('NoPD',$NoPD)->get('PdHdr')->row()->NoPJSebelumnya;
    $TipeForm = $this->db->select('TipeForm')->where('NoPD',$NoPD)->get('PdHdr')->row()->TipeForm;
    $khusus_inputan = ($this->input->post('khusus_inputan', TRUE)) ? $this->input->post('khusus_inputan', TRUE) : '' ;
    // echo $khusus_inputan;
    // die;

    $data = array(
        'NoPD' => $NoPD,
        'Alokasi' => $Alokasi,
        'JobNo' => $JobNo,
        'LedgerNo' => $LedgerNo,

    );
    $data['khusus_inputan'] = $khusus_inputan;

    $data['query_dapat_pddtl'] = $this->Permintaan_dana_keuangan_model->dapat_PdDtl_sementara_EditPD($NoPD);
    // $data['dapat_NoPJSebelumnya'] = $this->Permintaan_dana_keuangan_model->dapat_NoPJSebelumnya_EditPD($NoPD);
    $data['NoPJSebelumnya'] = $NoPJSebelumnya;


    $data['dapat_NoPJSebelumnya'] = $this->db->query("  WITH TmpQuery AS (
        SELECT NoPJ, Saldo, (SELECT COUNT(NoPJSebelumnya) FROM PdHdr B WHERE B.NoPJSebelumnya=PdHdr.NoPJ) AS 'AlreadyUsedPJ'
        FROM PdHdr WHERE JobNo='$JobNo' AND TipeForm='$TipeForm' AND KSO='$KSO' AND RejectBy IS NULL
        AND NoPJ IS NOT NULL AND NoRef IS NULL 
        )
        SELECT * FROM TmpQuery WHERE AlreadyUsedPJ=0 AND Saldo<>0 ORDER BY NoPJ DESC")->result_array();

    // print_rr($data);
    // exit;


    $data['dataUraian'] = $this->db->query("SELECT NoPJSebelumnya, Saldo  FROM PdHdr WHERE LedgerNo='$LedgerNo' ")->row_array();
    $data['TipeForm'] = $TipeForm;
    $this->load->view('permintaan_dana_keuangan/table_pddtl_EditPD',$data);

}


function AksiSimpanPdDtl_EditPD()

{
    if (!$this->input->post()) {
        exit;
    }

    $NoPD = $this->input->post('NoPD', TRUE);
    $KdRAP = $this->input->post('KdRAP', TRUE);
    $Uraian = $this->input->post('Uraian', TRUE);
        // $Vol = ($this->input->post('Vol', TRUE) == 0 || $this->input->post('Vol', TRUE) == '') ? 1 : $this->input->post('Vol', TRUE);
    $Uom = $this->input->post('Uom', TRUE);
        // $HrgSatuan = $this->input->post('HrgSatuan', TRUE);

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $Company = $this->config->item('Company');
    $NoUrut = $this->Permintaan_dana_keuangan_model->pembuatan_NoUrut('pd_baru',$NoPD);


    $Vol = ($this->input->post('Vol',TRUE) == '' || $this->input->post('Vol',TRUE) == NULL ) ? 0 : $this->input->post('Vol',TRUE);
    $HrgSatuan = ($this->input->post('HrgSatuan',TRUE) == '' || $this->input->post('HrgSatuan',TRUE) == NULL ) ? 0 : $this->input->post('HrgSatuan',TRUE);

    $Vol = HapusKomaPHP($Vol);
    $HrgSatuan = HapusKomaPHP($HrgSatuan);


    $data = array(
        'NoPD' => $NoPD,
        'NoUrut' => $NoUrut,
        'KdRAP' => $KdRAP,
        'Uraian' => $Uraian,
            // 'Vol' => HapusKomaPHP($Vol),
        'Uom' => $Uom,
            // 'HrgSatuan' => HapusKomaPHP($HrgSatuan),
        'Vol' => $Vol,
        'HrgSatuan' => preg_replace('/[,]/', '', $HrgSatuan),
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,
        'Company' => $Company

    );
        // print_rr($data);
        // die;
    $simpan = $this->Permintaan_dana_keuangan_model->AksiSimpanPDUraian_EditPD($data);
    if ($simpan == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;

        // print_rr($_POST);
        // print_rr($data);


}


function dapatFormUraian_EditPD(){
    if (!$this->input->post()) {
        exit;
    }

    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoPD = $this->input->post('NoPD', TRUE);
    $Company = $this->config->item('Company');

    $query = $this->db->query("SELECT TOP 1 *, (Vol * HrgSatuan) as TotalHarga FROM PdDtl_sementara WHERE NoPD='$NoPD' AND NoUrut='$NoUrut' AND Company='$Company' ORDER BY NoUrut ASC")->row_array();

    $KdRAP = htmlentities($query['KdRAP']);
    $Uraian = htmlentities($query['Uraian']);
    $Vol = htmlentities($query['Vol']);
    $Uom = htmlentities($query['Uom']);
    $HrgSatuan = htmlentities($query['HrgSatuan']);
    $TotalHarga = htmlentities($query['TotalHarga']);

    $data = array(
        'KdRAP' => $KdRAP,
        'Uraian' =>$Uraian,
        'Vol' => uang_koma_genap_decimal_kedua($Vol),
        'Uom' => $Uom,
        'HrgSatuan' => uang_koma_genap_decimal_kedua($HrgSatuan),
        'TotalHarga' => uang_koma_genap_decimal_kedua($TotalHarga),
        'NoPD' => $NoPD,
        'NoUrut' => $NoUrut

    );

    echo json_encode($data);
}

function AksiEditUraian_EditPD()
{
    if (!$this->input->post()) {
        exit;
    }

    $JobNo = $this->input->post('JobNo', TRUE);
    $NoPD = $this->input->post('NoPD', TRUE);
    $NoUrut = $this->input->post('NoUrut', TRUE);
    $Alokasi = $this->input->post('Alokasi', TRUE);
    $Company = $this->config->item('Company');



    $KdRAP = $this->input->post('KdRAP', TRUE);
    $Uraian = $this->input->post('Uraian', TRUE);
        // $Vol = ($this->input->post('Vol', TRUE) == 0 || $this->input->post('Vol', TRUE) == '') ? 1 : $this->input->post('Vol', TRUE);
    $Uom = $this->input->post('Uom', TRUE);
        // $HrgSatuan = $this->input->post('HrgSatuan', TRUE);

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $Vol = ($this->input->post('Vol',TRUE) == '' || $this->input->post('Vol',TRUE) == NULL ) ? 0 : $this->input->post('Vol',TRUE);
    $HrgSatuan = ($this->input->post('HrgSatuan',TRUE) == '' || $this->input->post('HrgSatuan',TRUE) == NULL ) ? 0 : $this->input->post('HrgSatuan',TRUE);

    $Vol = HapusKomaPHP($Vol);
    $HrgSatuan = HapusKomaPHP($HrgSatuan);

    $data = array(
        'NoPD' => $NoPD,
        'NoUrut' => $NoUrut,
        'KdRAP' => $KdRAP,
        'Uraian' => $Uraian,
            // 'Vol' => HapusKomaPHP($Vol),
        'Uom' => $Uom,
            // 'HrgSatuan' => HapusKomaPHP($HrgSatuan),
        'Vol' => $Vol,
        'HrgSatuan' => preg_replace('/[,]/', '', $HrgSatuan),
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,
    );

    $where = array(
        'NoPD' => $NoPD,
        'NoUrut' => $NoUrut,
        'Company' => $Company,
    );

        // print_rr('ok');
        // exit;

    $ubah = $this->Permintaan_dana_keuangan_model->AksiEditUraian_EditPD($data,$where);
    if ($ubah == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;

}

function HapusBanyakUraian_EditPD()
{

    if (!$this->input->post()) {
        exit;
    }

    $NoUrut = $this->input->post('NoUrut',TRUE);
    $NoPD = $this->input->post('NoPD',TRUE);

    $NoUrut = implode(",", $NoUrut);
    $hapus = $this->Permintaan_dana_keuangan_model->aksi_hapus_multiple_uraian($NoUrut,$NoPD);
    if ($hapus == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;
}

function AksiSimpanUraian_EditPD(){
    if (!$this->input->post()) {
        exit;
    }

        // print_rr($_POST);
        // die;

    $NoPD = $this->input->post('NoPD', TRUE);
    $JobNo = $this->input->post('JobNo', TRUE);
    $Alokasi = $this->input->post('Alokasi', TRUE);
    $LedgerNo = $this->input->post('LedgerNo', TRUE);
    $NoKO = $this->input->post('NoKO',TRUE);



    $Cek_Table_PdDtl_sementara = $this->Permintaan_dana_keuangan_model->Cek_Table_PdDtl_sementara($NoPD);
    if ($Cek_Table_PdDtl_sementara == 0) {
        echo 'pd_detail_kosong';
        exit;
    }

    $NoTagihan = $this->input->post('NoTagihan', TRUE);
        // $SubTotal = $this->input->post('SubTotal',TRUE);
        // $Saldo = $this->input->post('Saldo',TRUE);
        // $TotalPermintaan = $this->input->post('TotalPermintaan',TRUE);


    $SubTotal = $this->input->post('SubTotal',TRUE);
    $SubTotal = HapusKomaPHP($SubTotal);
    $SubTotal = preg_replace('/[,]/', '', $SubTotal);

    $Saldo = $this->input->post('Saldo',TRUE);
    $Saldo = HapusKomaPHP($Saldo);
    $Saldo = preg_replace('/[,]/', '', $Saldo);

    $TotalPermintaan = $this->input->post('TotalPermintaan',TRUE);
    $TotalPermintaan = HapusKomaPHP($TotalPermintaan);
    $TotalPermintaan = preg_replace('/[,]/', '', $TotalPermintaan);

    $data = array(
        'NoTagihan' => $NoTagihan,
            // 'TotalPD' => HapusKomaPHP($SubTotal),
            // 'Saldo' => HapusKomaPHP($Saldo),
        'TotalPD' => $SubTotal,
        'Saldo' => $Saldo,
    );

        // new
    $TglPD = $this->input->post('TglPD', TRUE);
    $Deskripsi = $this->input->post('Deskripsi', TRUE);
    $PrdAwal = $this->input->post('PrdAwal', TRUE);
    $PrdAkhir = $this->input->post('PrdAkhir', TRUE);
    $Minggu = $this->input->post('Minggu', TRUE);
    $TipeForm = $this->input->post('TipeForm', TRUE);
    $Keterangan = $this->input->post('Keterangan', TRUE);
    $BuktiPendukung = ($this->input->post('BuktiPendukung', TRUE)) ?  MembuatMenjadiKoma($this->input->post('BuktiPendukung', TRUE)) : '';

    $Nama = $this->input->post('Nama', TRUE);
    $Alamat = $this->input->post('Alamat', TRUE);
    $Telepon = $this->input->post('Telepon', TRUE);
    $NPWP = $this->input->post('NPWP', TRUE);
    $NoRek = $this->input->post('NoRek', TRUE);
    $AtasNama = $this->input->post('AtasNama', TRUE);
    $Bank = $this->input->post('Bank', TRUE);

    $NoPJSebelumnya = $this->input->post('NoPJSebelumnya',TRUE);




    $data['TglPD'] = $TglPD;
    $data['Deskripsi'] = $Deskripsi;
    $data['PrdAwal'] = $PrdAwal;
    $data['PrdAkhir'] = $PrdAkhir;
    $data['Minggu'] = $Minggu;
    $data['Keterangan'] = $Keterangan;
    $data['BuktiPendukung'] = $BuktiPendukung;

    $data['Nama'] = $Nama;
    $data['Alamat'] = $Alamat;
    $data['Telepon'] = $Telepon;
    $data['NPWP'] = $NPWP;
    $data['NoRek'] = $NoRek;
    $data['AtasNama'] = $AtasNama;
    $data['Bank'] = $Bank;

    $data['NoPJSebelumnya'] = $NoPJSebelumnya;

    // print_rr($_POST);
        // echo "==============================";
        // print_rr($data);
        // exit;

    $where_invoice = array(
        'JobNo' => $JobNo,
        'NoPD' => $NoPD,
        'NoKO' => $NoKO
    );

    $ubahPD = $this->Permintaan_dana_keuangan_model->AksiUbahPD_EditPD($data, $NoPD, $JobNo, $Alokasi, $LedgerNo, $where_invoice);

    if ($ubahPD == TRUE) {

        echo 'berhasil';
            // is_pesan('success', 'Berhasil mengubah data ');
        exit;
    }

    echo 'gagal';
        // is_pesan('error', 'Gagal saat mengubah data');
    exit;

}

function aksi_Approve_PDKeuangan(){
    if (!$this->input->post()) {
        exit;
    }

    $LedgerNo = $this->input->post('LedgerNo', TRUE);
    $NoPD = $this->input->post('NoPD', TRUE);
    $aksi_ApprovedByAK = $this->input->post('ApprovedByAK', TRUE);
        // $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        // $TimeEntry = date('Y-m-d H:i:s');

    $ApprovedByAK =NULL;
    $TimeApprovedAK = NULL;

    if ($aksi_ApprovedByAK == 'APPROVE') {
        $ApprovedByAK = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeApprovedAK = date('Y-m-d H:i:s');
    }else{
        $ApprovedByAK =NULL;
        $TimeApprovedAK = NULL;
    }

    $mydata['ApprovedByAK'] = $ApprovedByAK;
    $mydata['TimeApprovedAK'] = $TimeApprovedAK;


    $this->db->trans_start();

    $this->db->set($mydata)->where('LedgerNo',$LedgerNo)->update('PdHdr');

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE)
    {
            // echo '';
        echo json_encode(array(
            'status' => 'gagal',
            'pesan' => 'gagal '.$aksi_ApprovedByAK.' PD '.$NoPD.' ',
            'warna' => 'error', 
        ));
    }else{
        echo json_encode(array(
            'status' => 'berhasil',
            'pesan' => 'berhasil '.$aksi_ApprovedByAK.' PD '.$NoPD.' ',
            'warna' => 'success',
        ));
    }

        // $query_cek = $this->db->query(" SELECT ApprovedByAK,ApprovedByKT,ApprovedByKK FROM PdHdr WHERE LedgerNo='$LedgerNo' ")->row_array();
        // echo $ApprovedByAK;
        // echo $cek->ApprovedByKK;

        // if ($ApprovedByAK == 'UNAPPROVE') {
        //     if ($cek->ApprovedByKK !=null || $cek->ApprovedByKK !='' ) {
        //         echo 'berhasil';
        //     }
        // }

        // print_rr($_POST);
        // $query_cek = $this->db->query(" SELECT ApprovedByAK,ApprovedByKT,ApprovedByKK FROM PdHdr WHERE LedgerNo='$LedgerNo' ")->row_array();

        // $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        // $TimeEntry = date('Y-m-d H:i:s');


        // $data = array();
        // if ($query_cek['ApprovedByAK'] == NULL || $query_cek['ApprovedByAK'] == NULL ) {
        //     $data = array(
        //         'ApprovedByAK' => $UserEntry,
        //         'TimeApprovedAK' => $TimeEntry 
        //     );    
        // }else{

        //     if ($query_cek['ApprovedByKT'] <> NULL || $query_cek['ApprovedByKT'] <> '' ) {
        //         echo 'KT_ada';
        //         exit;
        //     }

        //     if ($query_cek['ApprovedByKK'] <> NULL || $query_cek['ApprovedByKK'] <> '' ) {
        //         echo 'KK_ada';
        //         exit;
        //     }

        //     $data = array(
        //         'ApprovedByAK' => NULL,
        //         'TimeApprovedAK' => NULL 
        //     );
        // }

        // $ubah_approve = $this->Permintaan_dana_keuangan_model->Aksi_Ubah_Approved($data,$LedgerNo);
        // if ($ubah_approve == TRUE) {
        //     echo 'berhasil';
        //     exit;
        // }

        // echo 'gagal';
        // exit;
}

function aksi_Approve_PDKeuangan_old(){
    if (!$this->input->post()) {
        exit;
    }

    $LedgerNo = $this->input->post('LedgerNo', TRUE);
    $query_cek = $this->db->query(" SELECT ApprovedByAK,ApprovedByKT,ApprovedByKK FROM PdHdr WHERE LedgerNo='$LedgerNo' ")->row_array();

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');


    $data = array();
    if ($query_cek['ApprovedByAK'] == NULL || $query_cek['ApprovedByAK'] == NULL ) {
        $data = array(
            'ApprovedByAK' => $UserEntry,
            'TimeApprovedAK' => $TimeEntry 
        );    
    }else{

        if ($query_cek['ApprovedByKT'] <> NULL || $query_cek['ApprovedByKT'] <> '' ) {
            echo 'KT_ada';
            exit;
        }

        if ($query_cek['ApprovedByKK'] <> NULL || $query_cek['ApprovedByKK'] <> '' ) {
            echo 'KK_ada';
            exit;
        }

        $data = array(
            'ApprovedByAK' => NULL,
            'TimeApprovedAK' => NULL 
        );
    }

    $ubah_approve = $this->Permintaan_dana_keuangan_model->Aksi_Ubah_Approved($data,$LedgerNo);
    if ($ubah_approve == TRUE) {
        echo 'berhasil';
        exit;
    }

    echo 'gagal';
    exit;
}


   //lihat invoice saat tambah permintaan dana lapangan
function lihat_invoice(){
    if(!$this->input->post('JobNo',TRUE)){
        echo 'error';
        exit;
    }

    $JobNo = $this->input->post('JobNo', TRUE);
    $Alokasi = $this->input->post('Alokasi', TRUE);

    $NoPD = $this->input->post('NoPD', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);
    $TipeForm = $this->input->post('TipeForm', TRUE);



    $data_mentah_Inv['JobNo'] = $JobNo;
    $data_mentah_Inv['Alokasi'] = $Alokasi;
    $data_mentah_Inv['NoPD'] = $NoPD;
    $data_mentah_Inv['NoKO'] = $NoKO;
    $data_mentah_Inv['TipeForm'] = $TipeForm;

    $pembuatan_invoice = $this->pembuatan_invoice($data_mentah_Inv);

            // print_rr($pembuatan_invoice);
            // die;

    $data['mydataInv'] = $pembuatan_invoice['array_buatan'];
    $data['TotalInvoice'] = $pembuatan_invoice['TotalInvoice'];
    $data['TotalBayar'] = $pembuatan_invoice['TotalBayar'];
    $data['jumlah_centang'] = $pembuatan_invoice['jumlah_centang'];

    $data['JobNo'] = $JobNo;
    $data['Alokasi'] = $Alokasi;
    $data['NoPD'] = $NoPD;
    $data['NoKO'] = $NoKO;
    $data['TipeForm'] = $TipeForm;

    $this->load->view('permintaan_dana_keuangan/lihat_inv_invoice', $data);
}

private function pembuatan_invoice($data_mentah_Inv){

    $JobNo = $data_mentah_Inv['JobNo'];
    $Alokasi = $data_mentah_Inv['Alokasi'];
    $NoPD = $data_mentah_Inv['NoPD'];
    $NoKO = $data_mentah_Inv['NoKO'];
    $TipeForm = $data_mentah_Inv['TipeForm'];

    $Company = $this->Company;


    $query_saya = $this->db->query("SELECT *,
        (SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS 'TotalPayment' 
        FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO'");

    $nomor_Inv = 1;

    $TotalInvoice = 0;
    $TotalBayar = 0;
    $TotalPayment= 0;
    $TotalPayment_cek = 0;

    $status_centang = 0;
    $array_buatan = array();

    $jumlah_centang=0;

    foreach($query_saya->result_array() as $row){

        $JobNo = $row['JobNo'];
        $NoKO = $row['NoKO'];
        $InvNo = $row['InvNo'];

        $TotalPayment_cek = (is_null($row['TotalPayment'])) ? 0 : $row['TotalPayment'];

        $myquery = $this->db->query("SELECT * FROM InvPD WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$NoKO' AND InvNo='$InvNo'  ");

        $cek_di_InvPD_sementara = $this->db->query("SELECT * FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$NoKO' AND InvNo='$InvNo' AND Company='$Company' ");

        if ($myquery->num_rows() > 0 ) {
            $invrow = $myquery->row();
            $TotalBayar += $invrow->PaymentAmount;
            $status_centang = 1;

        }else{

            if ($row['Total'] <= $TotalPayment_cek) {
                continue;   
            }

            $TotalInvoice += $row['Total'];
            $status_centang = 0;
        }

        if ($cek_di_InvPD_sementara->num_rows() > 0 ) {
            $status_centang = 1;
        }

        $jumlah_centang += $status_centang;

        $array_buatan[] = array(
            'JobNo' => $JobNo,
            'NoKO' => $NoKO, 
            'InvNo' => $InvNo,
            'NoPD' => $NoPD,
            'Total' => $row['Total'],
            'TotalPayment' => $row['TotalPayment'],
            'UserEntry' => $row['UserEntry'],
            'status_centang' => $status_centang,
            'LedgerNo' => $row['LedgerNo']
        );


    }

    return array('array_buatan'=>$array_buatan,'TotalInvoice' => $TotalInvoice,'TotalBayar' => $TotalBayar,'jumlah_centang' => $jumlah_centang);
}

function simpan_inv_per_row(){
    if (!$this->input->post('LedgerNo',TRUE)) {
        die;
    }

    $LedgerNo = $this->input->post('LedgerNo',TRUE);

    $JobNo = $this->input->post('JobNo',TRUE);
    $NoKO = $this->input->post('NoKO',TRUE);
    $InvNo = $this->input->post('InvNo',TRUE);
    $NoPD = $this->input->post('NoPD',TRUE);
    $TotalPayment = $this->input->post('TotalPayment',TRUE);

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $status = $this->input->post('status',TRUE);


    $data_send = array(
        'LedgerNo' => $LedgerNo,
        'JobNo' => $JobNo,
        'NoKO' => $NoKO,
        'InvNo' => $InvNo,
        'NoPD' => $NoPD,
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,
        'Company' => $this->Company,
        'PaymentAmount' => $TotalPayment
    );



    $this->db->trans_begin();

    if ($status == 1) {
        $this->db->insert('InvPD_sementara',$data_send);
    }else{
        $this->db->where('LedgerNo',$LedgerNo)->delete('InvPD_sementara');
    }

    if ($this->db->trans_status() === FALSE) {
        $this->db->trans_rollback();
        echo json_encode(array('kondisi' => 'gagal', 'status_centang' => ($status == 1) ? 'simpan' : 'hapus', 
            'jumlah_berhasil' => $this->db->affected_rows()  ));
    } else {
        $this->db->trans_commit();
        echo json_encode(array('kondisi' => 'berhasil', 'status_centang' => ($status == 1) ? 'disimpan' : 'dihapus', 
            'jumlah_berhasil' => $this->db->affected_rows()   ));

    }

}



// function print_pd_keuangan($LedgerNo,$NoPD, $JobNo, $Alokasi)
// {

//     if ($NoPD == '' || $JobNo == '') {
//         echo '';
//         exit;
//     }
//     $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
//     $tglSkrg = date('d-M-Y');
//     $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;


//     $where_saldo = array(
//         'NoPD' => $NoPD,
//         'JobNo' => $JobNo,
//         'Alokasi' => $Alokasi
//     );

//     $data['LedgerNo'] = $LedgerNo;
//     $data['NoPD'] = $NoPD;
//     $data['JobNo'] = $JobNo;
//     $data['Alokasi'] = $Alokasi;


//     $data['data'] = $this->Permintaan_dana_keuangan_model->dapat_data_pd($LedgerNo);
//     $data['getTableUraianDetailPD'] = $this->Permintaan_dana_keuangan_model->dapat_pd_detail($NoPD);
//     $data['SaldoSebelumnya'] = $this->Permintaan_dana_keuangan_model->cekSaldoSebelumnya($where_saldo);

//     $data['SubTotal'] = 0;
//     $data['TotalPermintaan'] = 0;



//     $this->load->view('permintaan_dana_keuangan/print/print_pd_keuangan', $data);
// }



}

/* End of file Permintaan_dana_keuangan.php */
    /* Location: ./application/controllers/Permintaan_dana_keuangan.php */