<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Mpdf\Mpdf;
error_reporting(E_ALL);

class Permintaan_dana_lapangan extends CI_Controller
{

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
        $this->load->model('Permintaan_dana_lapangan_model');
        $this->load->helper('bantu');

        $this->Company = $this->config->item('Company');

    }

    public function view($JobNo='',$Alokasi='',$dari_tgl='',$sampai_tgl='')
    {

        if($JobNo==''){
            exit;
        }

        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $this->load->model('m_job');
        $pdpj = $this->m_job->pdpj($JobNo);
        $data['pdpj'] = $pdpj;
        $data['judul'] = 'PERMINTAAN DANA (LAPANGAN)';
        $data['data_alokasi'] = AksesAlokasi();

        $data['Alokasi'] = $Alokasi;
        $data['Alokasi_back'] = $Alokasi;
        $data['bodyclass'] = 'sidebar-collapse skin-green-light';


        

        // print_rr($data);
        // die;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('permintaan_dana_lapangan/view', $data);
        $this->load->view('templates/footer');
    }

    function dapat_list_pd()
    {
        if (!$this->input->post('JobNo', TRUE)) {
            exit;
        }

        $JobNo =  $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $dari_tgl = $this->input->post('dari_tgl', TRUE);
        $sampai_tgl = $this->input->post('sampai_tgl', TRUE);


        $JobNo_back =  $this->input->post('JobNo', TRUE);
        $Alokasi_back = $this->input->post('Alokasi', TRUE);
        $dari_tgl_back = $this->input->post('dari_tgl', TRUE);
        $sampai_tgl_back = $this->input->post('sampai_tgl', TRUE);

        $JobNo_back = ($this->input->post('JobNo', TRUE)) ? $this->input->post('JobNo', TRUE) : '';
        $Alokasi_back = ($this->input->post('Alokasi', TRUE)) ? $this->input->post('Alokasi', TRUE) : '';
        $dari_tgl_back = ($this->input->post('dari_tgl', TRUE)) ? $this->input->post('dari_tgl', TRUE) : '';
        $sampai_tgl_back = ($this->input->post('sampai_tgl', TRUE)) ? $this->input->post('sampai_tgl', TRUE) : '';

        $UrlPengenal = ($this->input->post('UrlPengenal', TRUE)) ? $this->input->post('UrlPengenal', TRUE) : '';



        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;

        $data['JobNo_back'] = $JobNo_back;
        $data['Alokasi_back'] = $Alokasi_back;
        $data['dari_tgl_back'] = $dari_tgl_back;
        $data['sampai_tgl_back'] = $sampai_tgl_back;
        $data['UrlPengenal'] = $UrlPengenal;

        $cek_job = $this->db->query("SELECT TipeManajerial, KSO, JobNo, JobNm FROM Job WHERE JobNo='$JobNo' ")->row();
        $data['TipeManajerial'] = $cek_job->TipeManajerial;
        $data['KSO'] = $cek_job->KSO;
        $data['JobNo'] = $cek_job->JobNo;
        $data['JobNm'] = $cek_job->JobNm;

        $atur_url = site_url($UrlPengenal.'/tambah_pd');
        $atur_url .= ($JobNo_back =='') ? '' : '/'.$JobNo_back;
        $atur_url .= ($Alokasi_back =='') ? '' : '/'.$Alokasi_back;
        $atur_url .= ($dari_tgl_back == '' AND $sampai_tgl_back == ''  ) ? '/0/0' : '/'.$dari_tgl_back.'/'.$sampai_tgl_back;
        $data['atur_url'] = $atur_url;

        $data['sumTotalPD'] = $this->sumTotalPD($JobNo, $Alokasi, $dari_tgl, $sampai_tgl);

        $this->load->view('permintaan_dana_lapangan/dapat_list_pd', $data);
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
            AND (NoRef IS NOT NULL OR NoRef<>'')
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

        $UrlPengenal = ($this->input->post('UrlPengenal', TRUE)) ? $this->input->post('UrlPengenal', TRUE) : '';


        $where = array(
            'JobNo' => $JobNo,
            'Alokasi' => $Alokasi
        );

        header('Content-Type: application/json');
        echo $this->Permintaan_dana_lapangan_model->dapat_list_pd_json($where, $dari_tgl, $sampai_tgl,$UrlPengenal);
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

    function tambah_pd($JobNo = '', $Alokasi = '', $dari_tgl='',$sampai_tgl='',$status_tambah='')
    {
        if ($JobNo == '' || $Alokasi == '') {
            redirect('Permintaan_dana_lapangan', 'refresh');
        }
        $TglPD = date('Y-m-d');
        $dapat_hitungan = $this->dapatkan_hari_senin_atau_monday($TglPD);

        $PrdAwal_minggu = $dapat_hitungan['PrdAwal_minggu'];
        $PrdAkhir_minggu = $dapat_hitungan['PrdAkhir_minggu'];
        $HitungMinggu = $this->hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu);



        $data['bodyclass'] = 'sidebar-collapse skin-red-light';

        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $data['dapat_judul'] = $this->Permintaan_dana_lapangan_model->dapatkan_judul($JobNo);

        $data['no_urut_pd'] = '';

        if ($status_tambah == 'RKD') {
            $data['no_urut_pd'] = NoPD_untuk_PDRKD($JobNo, $Alokasi);
        }else if($status_tambah == 'MIX'){
            $data['no_urut_pd'] = '';
        }else{
            $data['no_urut_pd'] = KodeUrutPd($JobNo, $Alokasi);
        }

        // $data['no_urut_pd'] = ($status_tambah == 'RKD') ? NoPD_untuk_PDRKD($JobNo, $Alokasi) :  KodeUrutPd($JobNo, $Alokasi);

        $data['PrdAkhir'] = $PrdAkhir_minggu;
        $data['PrdAwal'] = $PrdAwal_minggu;
        $data['Minggu'] = $HitungMinggu;
        // $data['NoRef'] = $this->Permintaan_dana_lapangan_model->Pembuatan_NoRef($JobNo,$Alokasi);
        $data['NoRef'] = BuatNoRef($JobNo,$Alokasi);

        $data['dataTipeForm'] = $this->db->query("SELECT TipeForm,Keterangan FROM TipeForm  WHERE Alokasi='$Alokasi'")->result_array();
        // return $query;

        $data['judul'] = "TAMBAH PERMINTAAN DANA (Lapangan)";


        $atur_url_back_tambah_pd = site_url('Permintaan_dana_lapangan/view');
        $atur_url_back_tambah_pd .= ($JobNo =='') ? '' : '/'.$JobNo;
        $atur_url_back_tambah_pd .= ($Alokasi =='') ? '' : '/'.$Alokasi;
        $atur_url_back_tambah_pd .= ($dari_tgl == '' AND $sampai_tgl == ''  ) ? '' : '/'.$dari_tgl.'/'.$sampai_tgl;
        $data['atur_url_back_tambah_pd'] = $atur_url_back_tambah_pd;

        $data['status_tambah'] = $status_tambah;


        // print_rr($data);
        // die;
        
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('permintaan_dana_lapangan/tambah_pd', $data);
        $this->load->view('templates/footer');
    }

    function dapat_no_ko_dan_vendor()
    {
        if (!$this->input->post()) {
            exit;
        }

        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);

        $type = $this->input->post('type',TRUE);
        $TipeForm = $this->input->post('TipeForm',TRUE);



        $data = $this->Permintaan_dana_lapangan_model->dapatkan_list_no_ko($JobNo,$TipeForm);


        $internal = $JobNo . '_INT';
        $html = '';
        $html .= '
        <select name="NoKO" id="NoKO" class="form-control form-control-sm select2" onchange="dapat_vendor()"  required>
        <option value="' . $internal . '" data-vendor="" data-JobNo="' . $JobNo . '" data-Alokasi="' . $Alokasi . '" selected>' . $internal . '</option>';
        foreach ($data as $mydata) {
            $html .= '<option value="' . $mydata['NoKO'] . '" data-vendor="' . $mydata['VendorId'] . '" data-JobNo="' . $JobNo . '" data-Alokasi="' . $Alokasi . '" >' . $mydata['NoKO'] . '</option>';
        }

        $html .= '</select>';

        $html .= "<script>
        $(document).ready(function() {
            dapat_vendor();
            $('.select2').select2({'theme': 'classic','width': '100%' });
            });
            </script>";
            echo $html;
        }


        function dapat_vendor()
        {
            if (!$this->input->post()) {
                exit;
            }

            $VendorId = $this->input->post('VendorId', TRUE);
            $NoKO = $this->input->post('NoKO', TRUE);
            $JobNo = $this->input->post('JobNo', TRUE);
            $query = $this->Permintaan_dana_lapangan_model->dapatkan_vendor($NoKO,$JobNo);
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

        $pindah_data = $this->Permintaan_dana_lapangan_model->KoDtl_ke_PdDtl_sementara($JobNo,$Alokasi,$NoPD,$NoKO);

    }

    function dapat_PdDtl_sementara(){
        if (!$this->input->post()) {
            exit;
        }

        // echo 'aa';
        // exit;

        $NoPD = $this->input->post('NoPD', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);

        $NoKO = $this->input->post('NoKO', TRUE);
        $TipeForm = $this->input->post('TipeForm', TRUE);
        $KSO = cek_KSO_Job($JobNo);
        $KSO = ($KSO <> 0 ) ? 1 : 0;
        // echo $KSO;
        // exit;
        // exit;

        $data = array(
            'NoPD' => $NoPD,
            'Alokasi' => $Alokasi,
            'JobNo' => $JobNo,
            'NoKO' => $NoKO,

        );

        $where['JobNo'] = $JobNo;
        $where['Alokasi'] = $Alokasi;
        $where['NoPD'] = $NoPD;
        $where['NoKO'] = $NoKO;
        $where['TipeForm'] = $TipeForm;

        // $getUang_PdDtl = $this->HitungPdDtl($NoPD);
        // print_rr($getUang_PdDtl);
        // exit;

            // print_rr($_POST);
            // exit;



        $data['query_dapat_pddtl'] = $this->Permintaan_dana_lapangan_model->dapat_PdDtl_sementara($NoPD);

        
        // $data['dapat_NoPJSebelumnya'] = $this->Permintaan_dana_lapangan_model->dapat_NoPJSebelumnya($NoPD);


        $data['dapat_NoPJSebelumnya'] = $this->db->query("  WITH TmpQuery AS (
            SELECT NoPJ, Saldo, (SELECT COUNT(NoPJSebelumnya) FROM PdHdr B WHERE B.NoPJSebelumnya=PdHdr.NoPJ) AS 'AlreadyUsedPJ'
            FROM PdHdr WHERE JobNo='$JobNo' AND TipeForm='$TipeForm' AND KSO='$KSO' AND RejectBy IS NULL
            AND NoPJ IS NOT NULL AND NoRef IS NOT NULL 
            )
            SELECT * FROM TmpQuery WHERE AlreadyUsedPJ=0 AND Saldo<>0 ORDER BY NoPJ DESC")->result_array();
        // print_rr($data);
        // exit;


        // $SubTotal_PDDetail = $this->db->query("SELECT SUM((Vol * HrgSatuan)) as SubTotal_PDDetail FROM PdDtl_sementara WHERE NoPD='PD21640B00463'   ");

        $get_data_invoice = $this->Permintaan_dana_lapangan_model->get_data_invoice($where);
        $data['myinv'] = $get_data_invoice;
        $data['TipeForm'] = $TipeForm;

        $this->load->view('permintaan_dana_lapangan/table_pddtl',$data);
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
        // $data = array(
        //     'SubTotal' => $SubTotal,
        //     'Saldo' => uang_koma_genap_decimal_kedua($Saldo),
        //     'Saldo_view_saja' => uang_koma_genap_decimal_kedua($Saldo_view_saja),
        //     'TotalPermintaan' => uang_koma_genap_decimal_kedua($TotalPermintaan)
        // );
        echo json_encode($data);

        // print_rr($data);
        // exit;


        // $data = array(
        //     'SubTotal' => $SubTotal,
        //     'Saldo' => number_format($Saldo),
        //     'TotalPermintaan' => $TotalPermintaan
        // );

        

        // $A = json_encode($data);

        // PRINT_RR(json_decode($A));

        // print_rr($data);



        // $query = $this->db->query("SELECT TOP 1 * FROM PdHdr WHERE JobNo='$JobNo' AND TipeForm='$TipeForm' AND KSO='0' AND RejectBy IS NULL ORDER BY NoPD DESC");

        // $array_buatan = array();
        // if ($query->num_rows() > 0) {
        //     foreach($query->result() as $mydata ){
        //         $array_buatan = array('SubTotal' => $SubTotal);
        //     }
        // }else{
        //     $array_buatan = array('SubTotal' => 0);
        // }

        // print_rr($array_buatan);
    }

    private function HitungPdDtl($NoPD){
        $query = $this->db->query(" SELECT *, (Vol * HrgSatuan) as Total FROM PdDtl_sementara WHERE NoPD='$NoPD' ORDER BY NoUrut ASC");
        $TotalPD = 0;
        $Saldo = 0;
        $SubTotalPD = 0;
        $array_buatan = array();
        if ($query->num_rows() > 0 ) {
            foreach($query->result() as $mydata ){
                $TotalPD += $mydata->Total;
                $array_buatan = array('TotalPD' => $TotalPD);
            }
        }else{
            $array_buatan = array('TotalPD' => $TotalPD);
        }
        return $array_buatan;

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

        // echo $Saldo;
    }


    function aksi_simpan_uraian_pd()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoPD = $this->input->post('NoPD', TRUE);
        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);
        $Uom = $this->input->post('Uom', TRUE);
        // $Vol = ($this->input->post('Vol', TRUE) == 0 || $this->input->post('Vol', TRUE) == '') ? 1 : $this->input->post('Vol', TRUE);
        // $HrgSatuan = $this->input->post('HrgSatuan', TRUE);
        $Vol = ($this->input->post('Vol',TRUE) == '' || $this->input->post('Vol',TRUE) == NULL ) ? 0 : $this->input->post('Vol',TRUE);
        $HrgSatuan = ($this->input->post('HrgSatuan',TRUE) == '' || $this->input->post('HrgSatuan',TRUE) == NULL ) ? 0 : $this->input->post('HrgSatuan',TRUE);
        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');
        $Company = $this->config->item('Company');
        $NoUrut = $this->Permintaan_dana_lapangan_model->pembuatan_NoUrut('pd_baru',$NoPD);

        $Vol = HapusKomaPHP($Vol);
        $HrgSatuan = HapusKomaPHP($HrgSatuan);
        
        $data = array(
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut,
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            // 'Vol' => HapusKomaPHP($Vol),
            // 'HrgSatuan' => HapusKomaPHP($HrgSatuan),
            'Vol' => $Vol,
            'HrgSatuan' => preg_replace('/[,]/', '', $HrgSatuan),
            'Uom' => $Uom,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
            'Company' => $Company,
        );

        // print_rr($_POST);
        // echo '<hr>';
        // print_rr($data);
        // die;


        $simpan = $this->Permintaan_dana_lapangan_model->aksi_simpan_uraian_pd($data);
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;
    }


    function hapus_uraian()
    {
        if (!$this->input->post()) {
            exit;
        }


        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);


        $hapus = $this->Permintaan_dana_lapangan_model->hapus_uraian_baru($NoUrut,$NoPD);
        if ($hapus == TRUE) {
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
        $hapus = $this->Permintaan_dana_lapangan_model->aksi_hapus_multiple_uraian($NoUrut,$NoPD);
        if ($hapus == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
    }


    function dapatkan_form_edit_uraian()
    {
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
            'Uom' => $Uom,
            // 'Vol' => new_decimal($Vol),
            // 'HrgSatuan' => new_decimal($HrgSatuan),
            'Vol' => uang_koma_genap_decimal_kedua($Vol),
            'HrgSatuan' => uang_koma_genap_decimal_kedua($HrgSatuan),
            // 'TotalHarga' => new_decimal($TotalHarga),
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

        $JobNo = $this->input->post('JobNo', TRUE);
        $NoPD = $this->input->post('NoPD', TRUE);
        $NoUrut = $this->input->post('NoUrut', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $Company = $this->config->item('Company');



        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);

        // $Vol = ($this->input->post('Vol', TRUE) == 0 || $this->input->post('Vol', TRUE) == '') ? 1 : $this->input->post('Vol', TRUE);
        // $HrgSatuan = $this->input->post('HrgSatuan', TRUE);
        $Vol = ($this->input->post('Vol',TRUE) == '' || $this->input->post('Vol',TRUE) == NULL ) ? 0 : $this->input->post('Vol',TRUE);
        $HrgSatuan = ($this->input->post('HrgSatuan',TRUE) == '' || $this->input->post('HrgSatuan',TRUE) == NULL ) ? 0 : $this->input->post('HrgSatuan',TRUE);

        $Uom = $this->input->post('Uom', TRUE);
        

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $Vol = HapusKomaPHP($Vol);
        $HrgSatuan = HapusKomaPHP($HrgSatuan);

        $data = array(
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut,
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            // 'Vol' => HapusKomaPHP($Vol),
            // 'HrgSatuan' => HapusKomaPHP($HrgSatuan),
            'Vol' => $Vol,
            'HrgSatuan' => preg_replace('/[,]/', '', $HrgSatuan),
            'Uom' => $Uom,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
        );

        // print_rr($data);
        // die;

        $where = array(
            'NoPD' => $NoPD,
            'NoUrut' => $NoUrut,
            'Company' => $Company,
        );

        $ubah = $this->Permintaan_dana_lapangan_model->aksi_ubah_data_uraian_di_tambah_pd($data,$where);
        if ($ubah == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

    }

    function Aksi_Simpan_PdHdr_tambah_pd(){

        if (!$this->input->post()) {
            exit;
        }




        $NoPD = $this->input->post('NoPD', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);

        $Cek_Table_PdDtl_sementara = $this->Permintaan_dana_lapangan_model->Cek_Table_PdDtl_sementara($NoPD);
        if ($Cek_Table_PdDtl_sementara == 0) {
            echo 'pd_detail_kosong';
            exit;
        }

        $CekNoPD_ada_atau_tidak = $this->Permintaan_dana_lapangan_model->CekNoPD_ada_atau_tidak($NoPD);
        if ($CekNoPD_ada_atau_tidak > 0) {
            echo 'pd_sudah_ada';
            exit;
        }



        $NoRef = $this->input->post('NoRef', TRUE);
        $TglPD = $this->input->post('TglPD', TRUE);
        $Deskripsi = $this->input->post('Deskripsi', TRUE);
        $PrdAwal = $this->input->post('PrdAwal', TRUE);
        $PrdAkhir = $this->input->post('PrdAkhir', TRUE);
        $Minggu = $this->input->post('Minggu', TRUE);
        $TipeForm = $this->input->post('TipeForm', TRUE);
        $Keterangan = $this->input->post('Keterangan', TRUE);
        $BuktiPendukung = ($this->input->post('BuktiPendukung', TRUE)) ?  MembuatMenjadiKoma($this->input->post('BuktiPendukung', TRUE)) : '';


        $NoKO = $this->input->post('NoKO', TRUE);
        $NoTagihan = $this->input->post('NoTagihan', TRUE);
        $Nama = $this->input->post('Nama', TRUE);
        $Alamat = $this->input->post('Alamat', TRUE);
        $Telepon = $this->input->post('Telepon', TRUE);
        $NPWP = $this->input->post('NPWP', TRUE);


        $NoRek = $this->input->post('NoRek', TRUE);
        $AtasNama = $this->input->post('AtasNama', TRUE);
        $Bank = $this->input->post('Bank', TRUE);


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


        // $TotalPermintaan = ($SubTotal - $Saldo);



        $status_tambah = $this->input->post('status_tambah',TRUE);

        $NoPJSebelumnya = ($this->input->post('NoPJSebelumnya') == '') ? NULL : $this->input->post('NoPJSebelumnya');

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
            'TotalPD' => $SubTotal,
            'Saldo' => $Saldo,
            'NoPJSebelumnya' => $NoPJSebelumnya,
            // 'TotalPermintaan' => ($SubTotal - $Saldo),

            // 'TotalPD' => HapusKomaPHP($SubTotal),
            // 'Saldo' => HapusKomaPHP($Saldo),

        );

        // print_rr($_POST);
        // echo "===============================================";
        // print_rr($data);    
        // exit;

        $array_dari_InvPD_sementara = array(
            'JobNo' => $JobNo,
            'NoPD' => $NoPD,
            'NoKO' => $NoKO,
            'Company' => $this->Company
        );
        // echo json_encode($myarr);
        // die;

        // $this->db->trans_start();

        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE ) {
        //     echo 'gagal';
        // }else{
        //     echo 'berhasil';

        //     $Company = $this->config->item('Company');

        //     OtomatisUbahCounter($JobNo, $Alokasi, $status_kode,$status_tambah);


        // }


        $simpan_pd = $this->Permintaan_dana_lapangan_model->simpan_data_dari_module_tambah_pd_lapangan($data, $NoPD, $JobNo, $Alokasi, $status_kode,$array_dari_InvPD_sementara,$status_tambah);
            // echo $simpan_pd;
            // die;

        if ($simpan_pd == TRUE) {

            echo 'berhasil';
            is_pesan('success', 'Berhasil menambahkan data');
            exit;
        }
        echo 'gagal';
        is_pesan('error', 'Gagal saat menambahkan data');
        exit;

    }


    //batas edit PD lapamgan
    function ViewPdLapangan($LedgerNo='',$JobNo_back='',$Alokasi_back='',$dari_tgl_back='', $sampai_tgl_back='',$UrlPengenal=''){

        if ($LedgerNo == '') {
            redirect('Job','refresh');
            exit;
        }

        $get_DataPD = $this->Permintaan_dana_lapangan_model->dapatDataPD($LedgerNo);
        $user_id = $this->session->userdata('MIS_LOGGED_ID');

        $data['judul'] = 'DETAIL PERMINTAAN DANA (Lapangan)';
        $data['bodyclass'] = 'sidebar-collapse skin-blue';
        $data['dataPD'] = $get_DataPD;
        $data['LedgerNo'] = $LedgerNo;

        // ---------------------------------

        $data['alert_jadi_satu'] = '';
        $data['khusus_inputan'] = NULL;
        $data['aktif_or_tidak'] = 'disabled=""';
        $data['tombol_btn'] = '';

        $NoPD = $get_DataPD['NoPD'];
        $JobNo = $get_DataPD['JobNo'];
        $Alokasi = $get_DataPD['Alokasi'];


        if ($get_DataPD['ApprovedByAK'] !=NULL || $get_DataPD['ApprovedByAK'] !='' ) {

            $data['alert_jadi_satu'] .= '
            <div class="col-sm-3">
            <div class="alert alert-danger">
            <small style="font-size:14px;color:black;"><i class="icon fa fa-check"></i> Approved By ('.$get_DataPD['ApprovedByAK'].') </small>
            </div>
            </div>';

            $data['khusus_inputan'] = 'disabled';
            $data['aktif_or_tidak'] = 'disabled="disabled"';
            $data['tombol_btn'] = 'hidden';

        }

        if ($get_DataPD['ApprovedByKT'] !=NULL || $get_DataPD['ApprovedByKT'] !='' ) {

            $data['alert_jadi_satu'] .= '
            <div class="col-sm-3">
            <div class="alert alert-info">
            <small style="font-size:14px;color:black;"><i class="icon fa fa-check"></i> Approved By ('.$get_DataPD['ApprovedByKT'].') </small>
            </div>
            </div>';

            $data['khusus_inputan'] = 'disabled';
            $data['aktif_or_tidak'] = 'disabled="disabled"';
            $data['tombol_btn'] = 'hidden';

        }

        // if ($get_DataPD['NoRef'] != NULL || $get_DataPD['NoRef'] != ''  ) {
        //     if ($get_DataPD['ApprovedByKT'] !=NULL || $get_DataPD['ApprovedByKT'] !='' ) {

        //         $data['alert_jadi_satu'] .= '
        //         <div class="col-sm-3">
        //         <div class="alert alert-info">
        //         <small style="font-size:14px;color:black;"><i class="icon fa fa-check"></i> Approved By ('.$get_DataPD['ApprovedByKT'].') </small>
        //         </div>
        //         </div>';

        //         $data['khusus_inputan'] = 'disabled';
        //         $data['aktif_or_tidak'] = 'disabled="disabled"';
        //         $data['tombol_btn'] = 'hidden';

        //     }
        // }


        if ($get_DataPD['ApprovedByKK'] !=NULL || $get_DataPD['ApprovedByKK'] !='' ) {

            $data['alert_jadi_satu'] .= '
            <div class="col-sm-3">
            <div class="alert alert-success">
            <small style="font-size:14px;color:black;"><i class="icon fa fa-check"></i> Approved By ('.$get_DataPD['ApprovedByKK'].') </small>
            </div>
            </div>';

            $data['khusus_inputan'] = 'disabled';
            $data['aktif_or_tidak'] = 'disabled="disabled"';
            $data['tombol_btn'] = 'hidden';

        }


        $cek_pembayaran = $this->db->query(" SELECT TOP 1 * FROM BLE WHERE NoPD='$NoPD' AND JobNo='$JobNo' AND Alokasi='$Alokasi' ");

        if ($cek_pembayaran->num_rows() > 0 ) {

            $data['alert_jadi_satu'] .= '
            <div class="col-sm-3">
            <div class="alert alert-warning">
            <small style="font-size:14px;color:black;"><i class="icon fa fa-check"></i> Terbayar </small>
            </div>
            </div>';

            $data['khusus_inputan'] = 'disabled';
            $data['aktif_or_tidak'] = 'disabled="disabled"';
            $data['tombol_btn'] = 'hidden';

        }


        $data['JobNo_back'] = $JobNo_back;
        $data['Alokasi_back'] = $Alokasi_back;
        $data['dari_tgl_back'] = $dari_tgl_back;
        $data['sampai_tgl_back'] = $sampai_tgl_back;
        $data['UrlPengenal'] = $UrlPengenal;


        $UrlPengenal = ($UrlPengenal == '1') ? 'Permintaan_dana_lapangan/view' : 'Approve_permintaan_dana_lapangan/view';
        $atur_url = site_url($UrlPengenal.'/'.$JobNo_back.'/'.$Alokasi_back);
        $atur_url .= ($dari_tgl_back == 0) ? '' : '/'.$dari_tgl_back;
        $atur_url .= ($sampai_tgl_back == 0) ? '' : '/'.$sampai_tgl_back;
        $data['atur_url'] = $atur_url;  
        // echo $atur_url;
        // die;
        // print_rr($data);
        // die;


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('permintaan_dana_lapangan/view_PD_lapangan', $data);
        $this->load->view('templates/footer');
    }




    function PindahData_PdDtl_ke_PdDtl_sementara_EditPD(){

        if (!$this->input->post()) {
            exit;
        }


        $NoPD = $this->input->post('NoPD', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $LedgerNo = $this->input->post('LedgerNo', TRUE);


        $pindah_data = $this->Permintaan_dana_lapangan_model->PindahData_PdDtl_ke_PdDtl_sementara_EditPD($JobNo,$Alokasi,$NoPD,$LedgerNo);

    }

    function dapat_PdDtl_sementara_EditPD(){

        if (!$this->input->post()) {
            exit;
        }


        $NoPD = $this->input->post('NoPD', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $KSO = $this->db->query("SELECT TOP 1 KSO FROM Job WHERE JobNo='$JobNo' ")->row()->KSO;
        $KSO = ($KSO <> 0) ? 1 : 0;

        $TipeForm = $this->db->select('TipeForm')->where('NoPD',$NoPD)->get('PdHdr')->row()->TipeForm; 
        $NoPJSebelumnya = $this->db->select('NoPJSebelumnya')->where('NoPD',$NoPD)->get('PdHdr')->row()->NoPJSebelumnya;
        $LedgerNo = $this->input->post('LedgerNo', TRUE);
        $khusus_inputan = ($this->input->post('khusus_inputan', TRUE)) ? 'disabled' : '' ;


        $data = array(
            'NoPD' => $NoPD,
            'Alokasi' => $Alokasi,
            'JobNo' => $JobNo,
            'LedgerNo' => $LedgerNo,

        );
        $data['NoPJSebelumnya'] = $NoPJSebelumnya;
        $data['khusus_inputan'] = $khusus_inputan;

        $data['query_dapat_pddtl'] = $this->Permintaan_dana_lapangan_model->dapat_PdDtl_sementara_EditPD($NoPD);
        // $data['dapat_NoPJSebelumnya'] = $this->Permintaan_dana_lapangan_model->dapat_NoPJSebelumnya_EditPD($NoPD);



        $data['dapat_NoPJSebelumnya'] = $this->db->query("  WITH TmpQuery AS (
            SELECT NoPJ, Saldo, (SELECT COUNT(NoPJSebelumnya) FROM PdHdr B WHERE B.NoPJSebelumnya=PdHdr.NoPJ) AS 'AlreadyUsedPJ'
            FROM PdHdr WHERE JobNo='$JobNo' AND TipeForm='$TipeForm' AND KSO='$KSO' AND RejectBy IS NULL
            AND NoPJ IS NOT NULL AND NoRef IS NOT NULL 
            )
            SELECT * FROM TmpQuery WHERE AlreadyUsedPJ=0 AND Saldo<>0 ORDER BY NoPJ DESC")->result_array();

        // print_rr($data['NoPJSebelumnya']);
        // exit;

        $data['dataUraian'] = $this->db->query("SELECT NoPJSebelumnya, Saldo  FROM PdHdr WHERE LedgerNo='$LedgerNo' ")->row_array();

        $data['TipeForm'] = $TipeForm;
        $this->load->view('permintaan_dana_lapangan/table_pddtl_EditPD',$data);

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


        $NoUrut = $this->Permintaan_dana_lapangan_model->pembuatan_NoUrut('pd_baru',$NoPD);

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

        $simpan = $this->Permintaan_dana_lapangan_model->AksiSimpanPDUraian_EditPD($data);
        if ($simpan == TRUE) {
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
        $hapus = $this->Permintaan_dana_lapangan_model->aksi_hapus_multiple_uraian($NoUrut,$NoPD);
        if ($hapus == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
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
            'Uom' => $Uom,
            // 'HrgSatuan' => new_decimal($HrgSatuan),
            // 'TotalHarga' => new_decimal($TotalHarga),
            // 'Vol' => new_decimal($Vol),
            'Vol' => uang_koma_genap_decimal_kedua($Vol),
            'HrgSatuan' => uang_koma_genap_decimal_kedua($HrgSatuan),
            // 'TotalHarga' => $TotalHarga,
            // 'TotalHarga' => uang_koma_genap_decimal_kedua($TotalHarga),
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

        $Vol = ($this->input->post('Vol',TRUE) == '' || $this->input->post('Vol',TRUE) == NULL ) ? 0 : $this->input->post('Vol',TRUE);
        $HrgSatuan = ($this->input->post('HrgSatuan',TRUE) == '' || $this->input->post('HrgSatuan',TRUE) == NULL ) ? 0 : $this->input->post('HrgSatuan',TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

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


        $ubah = $this->Permintaan_dana_lapangan_model->AksiEditUraian_EditPD($data,$where);
        if ($ubah == TRUE) {
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
        // exit;



        $NoPD = $this->input->post('NoPD', TRUE);
        $JobNo = $this->input->post('JobNo', TRUE);
        $Alokasi = $this->input->post('Alokasi', TRUE);
        $LedgerNo = $this->input->post('LedgerNo', TRUE);
        $NoKO = $this->input->post('NoKO',TRUE);

        $Cek_Table_PdDtl_sementara = $this->Permintaan_dana_lapangan_model->Cek_Table_PdDtl_sementara($NoPD);
        if ($Cek_Table_PdDtl_sementara == 0) {
            echo 'pd_detail_kosong';
            exit;
        }


        $NoTagihan = $this->input->post('NoTagihan', TRUE);


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

        $where_invoice = array(
            'JobNo' => $JobNo,
            'NoPD' => $NoPD,
            'NoKO' => $NoKO
        );


        // print_rr($_POST);
        // echo "==============================";
        // print_rr($data);
        // exit;


        $ubahPD = $this->Permintaan_dana_lapangan_model->AksiUbahPD_EditPD($data, $NoPD, $JobNo, $Alokasi, $LedgerNo, $where_invoice);

        if ($ubahPD == TRUE) {

            echo 'berhasil';
            is_pesan('success', 'Berhasil mengubah data ');
            exit;
        }

        echo 'gagal';
        is_pesan('error', 'Gagal saat mengubah data');
        exit;

    }



    function aksi_Approve_PDKeuangan(){
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

        $ubah_approve = $this->Permintaan_dana_lapangan_model->Aksi_Ubah_Approved($data,$LedgerNo);
        if ($ubah_approve == TRUE) {
            echo 'berhasil';
            exit;
        }

        echo 'gagal';
        exit;
    }


    function lihat_invoice(){
        if(!$this->input->post('JobNo',TRUE)){
            // echo 'error';
            // exit;
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

        // print_rr($data);
        // die;

        $this->load->view('permintaan_dana_lapangan/lihat_inv_invoice', $data);
    }



    private function pembuatan_invoice($data_mentah_Inv){

        $JobNo = $data_mentah_Inv['JobNo'];
        $Alokasi = $data_mentah_Inv['Alokasi'];
        $NoPD = $data_mentah_Inv['NoPD'];
        $NoKO = $data_mentah_Inv['NoKO'];
        $TipeForm = $data_mentah_Inv['TipeForm'];

        $Company = $this->Company;


        $query_saya = $this->db->query("
            SELECT *, 
            (SELECT B.VendorNm FROM KoHdr A 
                LEFT JOIN Vendor B ON A.VendorId=B.VendorId 
                WHERE NoKO=Invoice.NoKO) AS 'VendorNm', 
            (SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS 'TotalPayment' FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO' ");

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
                // $myquery = $this->db->query("
                //     SELECT a.*,  
                //     (SELECT COUNT(InvNo) FROM InvPD_sementara WHERE JobNo=a.JobNo AND NoKO=a.NoKO AND InvNo=a.InvNo AND NoPD=a.NoPD AND Company='MDH' ) as 'StatusFrom_InvPD_sementara' 
                //     FROM InvPD a  WHERE a.JobNo='$JobNo' AND a.NoKO='$NoKO' AND a.NoPD='$NoPD' AND a.InvNo='$InvNo'

                // ");

            $cek_di_InvPD_sementara = $this->db->query("SELECT * FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$NoKO' AND InvNo='$InvNo' AND Company='$Company' ");

            if ($myquery->num_rows() > 0 ) {
                $invrow = $myquery->row();
                $TotalBayar += $invrow->PaymentAmount;
                $status_centang = 1;
                    // $status_centang = ($invrow->StatusFrom_InvPD_sementara === 1 ) ? 1 : 0 ;

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
                'InvDate' => $row['InvDate'],
                'DueDate' => $row['DueDate'],
                'PPN' => $row['PPN'],
                'FPNo' => $row['FPNo'],
                'FPDate' => $row['FPDate'],
                'Total' => $row['Total'],
                'TotalPayment' => $row['TotalPayment'],
                'UserEntry' => $row['UserEntry'],
                'status_centang' => $status_centang,
                'LedgerNo' => $row['LedgerNo'],
                'VendorNm' => $row['VendorNm'],
            );


        }
            // return json_encode($array_buatan);

        return array('array_buatan'=>$array_buatan,'TotalInvoice' => $TotalInvoice,'TotalBayar' => $TotalBayar,'jumlah_centang' => $jumlah_centang);
    }

    function simpan_inv_per_row(){
        if (!$this->input->post('LedgerNo',TRUE)) {
            // die;
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

    function print_pd_lapangan($LedgerNo, $NoPD, $JobNo, $Alokas,$Dari='')
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

        $pd_data = $this->Permintaan_dana_lapangan_model->dapat_data_pd($LedgerNo);

        $data['data'] = $pd_data;
        $data['getTableUraianDetailPD'] = $this->Permintaan_dana_lapangan_model->dapat_pd_detail($NoPD);
        $data['SaldoSebelumnya'] = $this->Permintaan_dana_lapangan_model->cekSaldoSebelumnya($where_saldo);

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
        $query_PD = $this->Permintaan_dana_lapangan_model->dapat_pd_detail($NoPD);
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
        // $tglBayarPJ = '';
        // $tglBayarPJ = '';
        // $tglBayarPJ = '';
        // $tglBayarPJ = '';

        $query_bayar = $this->db->query("
            SELECT TOP 1 *
            FROM 
            BLE WHERE NoPD='$pd_data[NoPD]'  OR 
            NoPD='$pd_data[NoRef]' ORDER BY TimeEntry DESC");

        $cek_bayar_ble = 0;
        if ($query_bayar->num_rows() > 0 ) {
            $row_ble = $query_bayar->row();
            $tglBayarPJ = ($row_ble->TglBayar == NULL || $row_ble== '' ) ? '-' : $row_ble->TglBayar;
            $cek_bayar_ble = 1;
            // echo $row_ble->TglBayar;

        }else{
            $cek_bayar_ble = 0;
        }

        $data['tglBayarPJ']= $tglBayarPJ;

        $data['pj_bayar'] = $query_bayar->row_array();
        $data['Dari'] = ($Dari == '') ? 'LAPANGAN' : $Dari;
        $data['cek_bayar_ble'] = $cek_bayar_ble;

        // print_rr($query_bayar);
        // die;




        // echo count($data['array_PJ']);
        // die;

        $this->load->view('permintaan_dana_lapangan/print/print_pd_lapangan', $data);
    }

    private function cek_pj($LedgerNo='', $NoPD,$TipeForm, $TglPD, $KSO, $Alokasi,$NoPJSebelumnya,$NoPJ){

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
        $query_PD = $this->Permintaan_dana_lapangan_model->dapat_pd_detail($NoPD);
        foreach($query_PD as $PD_data ){
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
                'NoPJ' => $PD_data['NoPJ'],
            );
        }
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

    private function get_PdDtl($NoPD=''){

    }


    // function print_PD(){

    // }
}





/* End of file Permintaan_dana_lapangan.php */
/* Location: ./application/controllers/Permintaan_dana_lapangan.php */
