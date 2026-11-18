
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kontrak_po extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('M_kontrak_po');
        $this->load->helper('bantu');

        if (!$this->db->field_exists('JobNo', 'KoDtl_sementara'))
        {
            $this->db->query("ALTER TABLE KoDtl_sementara ADD JobNo nvarchar(15) NULL");
        }

        if (!$this->db->field_exists('JobNo', 'KoDtl'))
        {
            $this->db->query("ALTER TABLE KoDtl ADD JobNo nvarchar(15) NULL");
        }
    }

    public function index($JobNo = '')
    {

        $this->session->unset_userdata('DataSesi_PURCHASE_ORDER');
        $this->session->unset_userdata('KO_SESI');

        $data['JobNo'] = ($JobNo == '') ? '' : $JobNo;

        $data['judul'] = "DAFTAR KONTRAK / PURCHASE ORDER ";
        $data['bodyclass'] = 'sidebar-collapse skin-blue-light';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('procurement/kontrak_po/view_kontrak_po', $data);
        $this->load->view('templates/footer');
    }

    

    function dapat_tampil_table()
    {
        if (!$this->input->post()) {
            die;
        }

        $this->session->unset_userdata('DataSesi_PURCHASE_ORDER');
        $this->session->unset_userdata('KO_SESI');

        $JobNo = $this->input->post('JobNo', TRUE);

        $myquery = $this->db->query("SELECT A.LedgerNo, ISNULL(A.NoKO,NULL) AS NoKO, 
            ISNULL(A.TglKO,NULL) AS TglKO,
            ISNULL(B.VendorNm,NULL) AS VendorNm,
            ISNULL(A.KategoriId,NULL) AS KategoriId,
            ISNULL(A.AddendumKe,NULL) AS AddendumKe,
            ISNULL(A.SubTotal,0) AS SubTotal,
            ISNULL(A.DiscAmount,0) AS DiscAmount,
            ISNULL(A.PPN,0) AS PPN,
            ISNULL(A.ApprovedBy,NULL) AS ApprovedBy,
            ISNULL(A.TimeApproved,NULL) AS TimeApproved,
            ISNULL(A.CanceledBy,NULL) AS CanceledBy,
            ISNULL(A.ClosedBy,NULL) AS ClosedBy,
            ISNULL(A.KategoriVendor,NULL) AS KategoriVendor,
            (CASE
                WHEN A.KategoriId='KONTRAK' THEN (SELECT SUM(ISNULL(TotalNominalPPN,0)) FROM KoDtl WHERE NoKO=A.NoKO GROUP BY NoKO) 
                ELSE 0
                END) AS TotalPPN
            
            FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId WHERE A.JobNo='$JobNo' ORDER BY A.NoKO DESC")->result();
        // print_rr($myquery);
        // die;/
        $myarray = array();
        $Sum_TotalKO = 0;
        $Sum_TotalKO_IncludePPN = 0;
        foreach($myquery as $row):
            $TotalPPN = 0;
            $TotalKO_IncludePPN = 0;

            $TotalPPN =  ($row->TotalPPN=='' || $row->TotalPPN==NULL) ? 0 : (float) $TotalPPN;
            $TotalKO = (float) ($row->SubTotal - $row->DiscAmount) + $row->PPN;
            

            // $SubTotal = (float) $row->SubTotal;
            // $DiscAmount = (float) $row->DiscAmount;
            // $PPN = (float) $row->PPN;

            // $TotalKO = (float) ($SubTotal - $DiscAmount) + $PPN;
            



            // if ($row->KategoriId=='KONTRAK') {
            //     $TotalKO_IncludePPN = $row->SubTotal + $TotalPPN;
            // }

            // $Sum_TotalKO_IncludePPN 

            $TotalKO_IncludePPN = 0;
            if ($row->KategoriId=='KONTRAK') {
                $TotalKO_IncludePPN = $row->SubTotal + $row->TotalPPN;
            }else{
                $TotalKO_IncludePPN = (float) ($row->SubTotal - $row->DiscAmount) + $row->PPN;
            }

            $Sum_TotalKO += $row->SubTotal;
            $Sum_TotalKO_IncludePPN += $TotalKO_IncludePPN;

            

            $myarray[] = (object) array(
                'LedgerNo' => $row->LedgerNo,
                'NoKO' => $row->NoKO,
                'TglKO' => $row->TglKO,
                'VendorNm' => $row->VendorNm,
                'KategoriId' => $row->KategoriId,
                'AddendumKe' => $row->AddendumKe,
                'SubTotal' => $row->SubTotal,
                'DiscAmount' => $row->DiscAmount,
                'PPN' => $row->PPN,
                'ApprovedBy' => $row->ApprovedBy,
                'TimeApproved' => $row->TimeApproved,
                'TotalKO' => $row->SubTotal,
                'CanceledBy' => $row->CanceledBy,
                'ClosedBy' => $row->ClosedBy,
                'TotalPPN' =>  $TotalKO_IncludePPN,
                'KategoriVendor' => $row->KategoriVendor,

            );

            // $TotalPPN = 0;
            // $TotalKO = 0;
        endforeach;

        $data['JobNo'] = $JobNo;
        $data['dataKO'] = $myarray;
        $data['Sum_TotalKO'] = number_format($Sum_TotalKO);
        $data['Sum_TotalKO_IncludePPN'] = number_format($Sum_TotalKO_IncludePPN);
        $this->load->view('procurement/kontrak_po/table_kontrak_po', $data);
    }

    function dapat_data_json_kontrak_po()
    {
        $JobNo = $this->input->post('JobNo', TRUE);

        header('Content-Type: application/json');
        echo $this->M_kontrak_po->dapat_data_json_kontrak_po($JobNo);
    }

    function tambah_kontrak($JobNo = '',$myflag='')
    {
        if ($JobNo == '') {
            redirect('Kontrak_po', 'refresh');
            die;
        }

        $this->session->unset_userdata('KO_SESI');
        $this->session->unset_userdata('DataSesi_PURCHASE_ORDER');
        $this->session->unset_userdata('DataSesi_KO');

        $KO_SESI='NEW|'.$JobNo.'|||';

        $this->session->set_userdata('KO_SESI',$KO_SESI);
        if ($this->session->userdata('KO_SESI') ) {

            if ($myflag!='') {
                if ($this->session->userdata('MIS_LOGGED_ID')=='admin') {
                    redirect('Entry/Procurement/FrmEntryKO_C/tes_kontrak_baru/'.$JobNo);
                }
            }else{
                redirect('Entry/Procurement/FrmEntryKO_C');
            }

            
        }
        die;

        if (!$this->db->table_exists('KoDtl_sementara')) {
            is_pesan('info', 'tidak ada table ko sementara');
            redirect('Kontrak_po', 'refresh');
            die;
        }

        $Alokasi = 'B';
        $data['NoKO'] = no_ko_buatan($JobNo, $Alokasi);
        // echo $data['NoKO']; 
        // exit;

        $data['judul'] = "TAMBAH KONTRAK ";
        $data['bodyclass'] = 'sidebar-collapse skin-blue';

        $query = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE JobNo='$JobNo' ")->row_array();
        $data['JobNo'] = $JobNo;
        $data['JobNm'] = $query['JobNm'];


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('procurement/kontrak_po/tambah_kontrak', $data);
        $this->load->view('templates/footer');
    }

    function dapat_data_vendor()
    {
        if (!$this->input->post()) {
            die;
        }

        $VendorId = $this->input->post('VendorId', TRUE);

        $query = $this->db->query("SELECT * FROM Vendor WHERE VendorId='$VendorId' ")->row_array();
        echo json_encode($query);
    }

    function tampil_rincian_pekerjaan()
    {
        if (!$this->input->post()) {
            die;
        }

        $JobNo = $this->input->post('JobNo', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);

        $query  = $this->db->query(" SELECT *, (Vol * HrgSatuan) as JumlahHarga  FROM KoDtl_sementara WHERE NoKO='$NoKO' ORDER BY NoUrut DESC ");
        $data['jumlah_row'] = $query->num_rows();
        $data['data_detail'] = $query->result_array();
        $data['JobNo'] = $JobNo;
        $data['NoKO'] = $NoKO;



        // $data['dapat_potong_ko'] = $this->db->query(" SELECT PotongKO FROM KoHdr WHERE NoKO='$NoKO' AND JobNo='$JobNo ");


        // print_rr($data);
        // die;
        $this->load->view('procurement/kontrak_po/table_rincian_pekerjaan', $data);
    }

    function aksi_simpan_uraian_ko()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoKO = $this->input->post('NoKO', TRUE);
        $KdRAP = $this->input->post('KdRAP', TRUE);
        $Uraian = $this->input->post('Uraian', TRUE);
        $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
        $Uom = $this->input->post('Uom', TRUE);
        $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $query_nourut = $this->db->query(" SELECT TOP 1 NoUrut FROM KoDtl_sementara WHERE NoKO='$NoKO' ORDER BY NoUrut DESC  ")->row_array();

        $NoUrut =  (int) $query_nourut['NoUrut'] + 1;

        $data = array(
            'NoKO' => $NoKO,
            'NoUrut' => $NoUrut,
            'Alokasi' => 'B',
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'HrgSatuan' => $HrgSatuan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,

        );

        $simpan = $this->M_kontrak_po->aksi_simpan_uraian_ko($data);
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

        // print_rr($_POST);
        // print_rr($data);


    }

    function hapus_uraian_kontrak()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);

        $where = array(
            'NoUrut' => $NoUrut,
            'NoKO' => $NoKO
        );

        $hapus = $this->M_kontrak_po->hapus_uraian_kontrak($where);
        if ($hapus == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
    }

    function dapatkan_data_kodtl_sementara()
    {
        if (!$this->input->post()) {
            exit;
        }

        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);

        $query = $this->db->query("SELECT TOP 1 *, (Vol * HrgSatuan) as TotalHarga FROM KoDtl_sementara WHERE NoKO='$NoKO' AND NoUrut='$NoUrut'  ORDER BY NoUrut DESC")->row_array();

        $Vol = PecahAngka($query['Vol']);

        $data = array(
            'KdRAP' => htmlentities($query['KdRAP']),
            'Uraian' => htmlentities($query['Uraian']),
            'Vol' => htmlentities($Vol),
            'Uom' => htmlentities($query['Uom']),
            'HrgSatuan' => htmlentities(number_format($query['HrgSatuan'])),
            'TotalHarga' => htmlentities($query['TotalHarga']),
            'NoKO' => $NoKO,
            'NoUrut' => $NoUrut

        );

        echo json_encode($data);
    }

    function aksi_edit_uraian_ko()
    {
        if (!$this->input->post()) {
            exit;
        }


        $NoUrut = $this->input->post('NoUrut', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);

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
            'NoKO' => $NoKO,
            'NoUrut' => $NoUrut
        );

        $simpan = $this->M_kontrak_po->aksi_update_uraian_ko($data, $where);
        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

        // print_rr($_POST);
        // print_rr($data);


    }

    function simpan_kohdr_kontrak()
    {
        if (!$this->input->post()) {
            exit;
        }

        //penerima kontrak
        $VendorId = $this->input->post('VendorId', TRUE);
        $TglKO = $this->input->post('TglKO', TRUE);


        //PERSYARATAN DAN SANKSI
        $K3 = (isset($_POST['K3'])) ? $this->input->post('K3', TRUE) : 0;

        $SyaratTeknis = $this->input->post('SyaratTeknis', TRUE);
        $JadwalPengiriman = $this->input->post('JadwalPengiriman', TRUE);
        $JadwalPembayaran = $this->input->post('JadwalPembayaran', TRUE);
        $SyaratPembayaran = ($this->input->post('SyaratPembayaran', TRUE)) ?  MembuatMenjadiKoma($this->input->post('SyaratPembayaran', TRUE)) : '';
        $Sanksi = $this->input->post('Sanksi', TRUE);
        $Keterangan = $this->input->post('Keterangan', TRUE);


        //variabel utama
        $JobNo = $this->input->post('JobNo', TRUE);
        $NoKO = $this->input->post('NoKO', TRUE);

        //rincial pekerjaan (uang)

        $SubTotal = $this->input->post('SubTotal', TRUE);
        $PotongKO = $this->input->post('PotongKO', TRUE);
        $GrandTotal = $this->input->post('GrandTotal', TRUE);


        //tambahan untuk history
        $UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
        $TimeEntry = date('Y-m-d h:i:s');






        $cek_nomor_ko = $this->db->query(" SELECT NoKO FROM KoHdr WHERE NoKO='$NoKO' ");

        if ($cek_nomor_ko->num_rows() > 0) {
            echo json_encode(array('JobNo' => $JobNo, 'pesan' => 'ada_no_ko'));
            die;
        }

        $cek_data_kodtl_sementara = $this->db->query(" SELECT NoKO FROM KoDtl_sementara WHERE NoKO='$NoKO' ");
        if ($cek_data_kodtl_sementara->num_rows() == 0) {
            echo json_encode(array('JobNo' => $JobNo, 'pesan' => 'kodtl_kosong'));
            die;
        }


        $data = array(

            //penerima kontrak
            'VendorId' => $VendorId,
            'TglKO' => $TglKO,

            //PERSYARATAN DAN SANKSI
            'K3' => $K3,
            'SyaratTeknis' => $SyaratTeknis,
            'SyaratPembayaran' => $SyaratPembayaran,
            'JadwalPengiriman' => $JadwalPengiriman,
            'JadwalPembayaran' => $JadwalPembayaran,
            'Sanksi' => $Sanksi,
            'Keterangan' => $Keterangan,

            //variabel utama
            'JobNo' => $JobNo,
            'NoKO' => $NoKO,

            //rincian pekerjaan (uang)
            'SubTotal' => HapusFormatUang($SubTotal),
            'PotongKO' => $PotongKO,
            'GrandTotal' => HapusFormatUang($GrandTotal),

            //tambahan untuk history
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,

            'KategoriId' => 'KONTRAK',
            // 'QRCode' => randomString(12),
            'QRCode' => NULL,

        );

        // print_rr($_POST);
        // echo "<br>";
        // echo "<hr>";
        // print_rr($data);
        // die;

        $simpan = $this->M_kontrak_po->simpan_kohdr_kontrak($data, $JobNo);
        if ($simpan == TRUE) {
            is_pesan('success', 'data telah ditambahkan');
            echo json_encode(
                array(
                    'JobNo' => $JobNo,
                    'pesan' => 'berhasil'
                )
            );
            die;
        }
        echo json_encode(
            array(
                'JobNo' => $JobNo,
                'pesan' => 'gagal'
            )
        );
        die;
    }


    //print
    function myprint_ko($LedgerNo = '')
    {
        if ($LedgerNo == '') {
            die;
        }

        $data['LedgerNo'] = $LedgerNo;
        $data['data_ko'] = $this->M_kontrak_po->dapat_data_ko($LedgerNo);
        $NoKO = $data['data_ko']['NoKO'];
        $data_detail =  $this->db->query(" SELECT *, (Vol * HrgSatuan) as JumlahHarga FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC ");
        $data['data_detail']  = $data_detail->result_array();

        $sum_TotalNominalPPN = 0;
        $Subtotal =0;
        $Total =0;
        $PPN =0;
        if ($data_detail->num_rows() > 0) {
         foreach($data_detail->result() as $row){
            $TotalNominalPPN = 0;
            $TotalNominalPPN = ($row->TotalNominalPPN=='' || $row->TotalNominalPPN==NULL ) ? 0 : (float) $row->TotalNominalPPN;

            $sum_TotalNominalPPN +=$TotalNominalPPN;
        }
    }

    $data['sum_TotalNominalPPN'] = number_format($sum_TotalNominalPPN);

     // -- Cari Addendum, ADD DATE : 18 MARET 2024
    $AddendumKe = NULL;
    $query_GetAddendum = $this->db->query("SELECT A.NoKO,A.TglKO,A.KategoriId,A.AddendumKe,B.VendorNm,A.SubTotal,A.ApprovedBy,A.TimeApproved FROM KoHdr A LEFT OUTER JOIN Vendor B ON A.VendorId = B.VendorId WHERE A.NoKO='$NoKO' ");
    if ($query_GetAddendum->num_rows() > 0) {
        $rowAddendum = $query_GetAddendum->row_array();
        if ($rowAddendum['AddendumKe']!=NULL || $rowAddendum['AddendumKe']!='' ) {
            if ($rowAddendum['AddendumKe']!=0) {
                $AddendumKe = ' (Add '.$rowAddendum['AddendumKe'].')';
            }
        }
    }

    $data['AddendumKe'] = $AddendumKe;

    $this->load->view('procurement/kontrak_po/print/print_content', $data);
}

private function dapat_qrcode($data_QRCode)
{
    return '';
    exit;
    $this->load->library('ciqrcode');

    $config['cacheable']    = true;
    $config['cachedir']     = './assets/';
    $config['errorlog']     = './assets/';
    $config['imagedir']     = './assets/';
    $config['quality']      = true;
    $config['size']         = '1024';
    $config['black']        = array(224, 255, 255);
    $config['white']        = array(70, 130, 180);
    $this->ciqrcode->initialize($config);

    $image_name = $data_QRCode . '.png';

    if (file_exists('./assets/' . $image_name)) {
        unlink('./assets/' . $image_name);
    }

    $params['data'] = $data_QRCode;
    $params['level'] = 'H';
    $params['size'] = 10;
    $params['savename'] = FCPATH . $config['imagedir'] . $image_name;
    $this->ciqrcode->generate($params);
    return $image_name;
}


    ///detail atau edit

function detail_ko($LedgerNo = '', $KategoriId = '', $JobNo = '', $app_ko = '')
{

    if ($LedgerNo == '') {
        die;
    }

    $this->session->unset_userdata('KO_SESI');
    $this->session->unset_userdata('DataSesi_PURCHASE_ORDER');
    $this->session->unset_userdata('DataSesi_KO');
    $KO_SESI='';

    $CekNoKO = $this->db->query("SELECT NoKO, ISNULL(NULL,ApprovedBy) as 'ApprovedBy' FROM KoHdr WHERE LedgerNo='$LedgerNo' ");

    if ($CekNoKO->num_rows() > 0) {

        $row = $CekNoKO->row();
        $NoKO = $row->NoKO;

        if($row->ApprovedBy==NULL || $row->ApprovedBy==''){
            $KO_SESI='UPD|'.$JobNo.'|'.$NoKO.'|';
        }else{
            $KO_SESI='SEE_KO|'.$JobNo.'|'.$NoKO.'|';
        }

        $this->session->set_userdata('KO_SESI',$KO_SESI);
        redirect('Entry/Procurement/FrmEntryKO_C/UpdateForm');
            // print_rr($this->session->userdata('KO_SESI'));

    }else{
        redirect('Kontrak_po/index/'.$JobNo,'refresh');
    }

    die;


    $CekNoKO = $this->db->query("SELECT NoKO FROM KoHdr WHERE LedgerNo='$LedgerNo' ");
    if ($CekNoKO->num_rows() > 0) {
        $row = $CekNoKO->row();
        $query = $this->db->query("SELECT CanceledBy FROM KoHdr WHERE NoKO='$row->NoKO'   ")->row();
        if ($query->CanceledBy!='' || $query->CanceledBy!=NULL) {
            is_pesan('error',$row->NoKO.' sudah di-batalkan');
            redirect('Kontrak_po/index/'.$JobNo,'refresh');
            die;
        }
    }

        // echo 'no';
        // die;


    if ($app_ko == '') {
        $data['app_ko'] = 0;
    } else {
        $data['app_ko'] = 1;
    }


    $Alokasi = 'B';

    if ($KategoriId == 'PO') {
        redirect('Kontrak_po/detail_po/' . $LedgerNo . '/' . $KategoriId . '/' . $JobNo . '/' . $app_ko, 'refresh');
        die;
    }

    $data['judul'] = "DETAIL KONTRAK ";
    $data['bodyclass'] = 'sidebar-collapse skin-blue';


    $data['LedgerNo'] = $LedgerNo;
    $data['data_ko'] = $this->M_kontrak_po->dapat_data_ko($LedgerNo);

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('procurement/kontrak_po/detail_atau_edit_ko/detail_kontrak', $data);
    $this->load->view('templates/footer');
}

function tampil_rincian_pekerjaan_aksi_detail()
{
    if (!$this->input->post()) {
        die;
    }


    $button_app_ko = ($this->input->post('button_app_ko', TRUE)) ? $this->input->post('button_app_ko', TRUE) : '';
    $JobNo = $this->input->post('JobNo', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

    $query  = $this->db->query(" SELECT *, (Vol * HrgSatuan) as JumlahHarga  FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC ");
    $data['jumlah_row'] = $query->num_rows();
    $data['data_detail'] = $query->result_array();
    $data['JobNo'] = $JobNo;
    $data['NoKO'] = $NoKO;
    $data['button_app_ko'] = $button_app_ko;


        // print_rr($_POST);
        // die;
    $this->load->view('procurement/kontrak_po/detail_atau_edit_ko/detail_table_rincian_pekerjaan', $data);
}

function aksi_simpan_uraian_ko_aksi_detail()
{
    if (!$this->input->post()) {
        exit;
    }

    $NoKO = $this->input->post('NoKO', TRUE);
    $KdRAP = $this->input->post('KdRAP', TRUE);
    $Uraian = $this->input->post('Uraian', TRUE);
    $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
    $Uom = $this->input->post('Uom', TRUE);
    $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $query_nourut = $this->db->query(" SELECT TOP 1 NoUrut FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC  ")->row_array();

    $NoUrut =  (int) $query_nourut['NoUrut'] + 1;

    $data = array(
        'NoKO' => $NoKO,
        'NoUrut' => $NoUrut,
        'Alokasi' => 'B',
        'KdRAP' => $KdRAP,
        'Uraian' => $Uraian,
        'Vol' => $Vol,
        'Uom' => $Uom,
        'HrgSatuan' => $HrgSatuan,
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,

    );

        // print_rr($_POST);
        // print_rr($data);
        // die;

    $simpan = $this->M_kontrak_po->aksi_simpan_uraian_ko_aksi_detail($data);
    if ($simpan == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;

        // print_rr($_POST);
        // print_rr($data);


}

function hapus_uraian_kontrak_aksi_detail()
{
    if (!$this->input->post()) {
        exit;
    }

    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

    $where = array(
        'NoUrut' => $NoUrut,
        'NoKO' => $NoKO
    );

    $hapus = $this->M_kontrak_po->hapus_uraian_kontrak_aksi_detail($where);
    if ($hapus == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;
}

function dapatkan_data_kodtl_aksi_detail()
{
    if (!$this->input->post()) {
        exit;
    }

    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

    $query = $this->db->query("SELECT TOP 1 *, (Vol * HrgSatuan) as TotalHarga FROM KoDtl WHERE NoKO='$NoKO' AND NoUrut='$NoUrut'  ORDER BY NoUrut DESC")->row_array();

    $Vol = PecahAngka($query['Vol']);

    $data = array(
        'KdRAP' => htmlentities($query['KdRAP']),
        'Uraian' => htmlentities($query['Uraian']),
        'Vol' => htmlentities($Vol),
        'Uom' => htmlentities($query['Uom']),
        'HrgSatuan' => htmlentities(number_format($query['HrgSatuan'])),
        'TotalHarga' => htmlentities($query['TotalHarga']),
        'NoKO' => $NoKO,
        'NoUrut' => $NoUrut

    );

    echo json_encode($data);
}

function aksi_edit_uraian_ko_aksi_detail()
{
    if (!$this->input->post()) {
        exit;
    }


    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

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
        'NoKO' => $NoKO,
        'NoUrut' => $NoUrut
    );

    $simpan = $this->M_kontrak_po->aksi_update_uraian_ko_aksi_detail($data, $where);
    if ($simpan == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;

        // print_rr($_POST);
        // print_rr($data);


}

function simpan_kohdr_kontrak_aksi_detail()
{
    if (!$this->input->post()) {
        exit;
    }

        // variabel wajib
    $LedgerNo = $this->input->post('LedgerNo', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);
    $JobNo = $this->input->post('JobNo', TRUE);


        //Penerima Kontrak
    $VendorId = $this->input->post('VendorId', TRUE);
    $TglKO = $this->input->post('TglKO', TRUE);

        //persyaratan dan sanksi
    $K3 = (isset($_POST['K3'])) ? $this->input->post('K3', TRUE) : 0;
    $SyaratTeknis = $this->input->post('SyaratTeknis', TRUE);
    $JadwalPengiriman = $this->input->post('JadwalPengiriman', TRUE);
    $JadwalPembayaran = $this->input->post('JadwalPembayaran', TRUE);
    $SyaratPembayaran = ($this->input->post('SyaratPembayaran', TRUE)) ?  MembuatMenjadiKoma($this->input->post('SyaratPembayaran', TRUE)) : '';
    $Sanksi = $this->input->post('Sanksi', TRUE);
    $Keterangan = $this->input->post('Keterangan', TRUE);


        //rincian pekerjaan
    $SubTotal = $this->input->post('SubTotal', TRUE);
    $PotongKO = $this->input->post('PotongKO', TRUE);
    $GrandTotal = $this->input->post('GrandTotal', TRUE);

    $UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
    $TimeEntry = date('Y-m-d h:i:s');


    $data = array(
            //Penerima Kontrak
        'VendorId' => $VendorId,
        'TglKO' => $TglKO,

            //persyaratan dan sanksi
        'K3' => $K3,
        'SyaratTeknis' => $SyaratTeknis,
        'JadwalPengiriman' => $JadwalPengiriman,
        'JadwalPembayaran' => $JadwalPembayaran,
        'SyaratPembayaran' => $SyaratPembayaran,
        'Sanksi' => $Sanksi,
        'Keterangan' => $Keterangan,

            //rincian pekerjaan
        'SubTotal' => HapusFormatUang($SubTotal),
        'PotongKO' => $PotongKO,
        'GrandTotal' => HapusFormatUang($GrandTotal),

            //tambahan
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,
    );


    $simpan = $this->M_kontrak_po->update_data_kontrak($data, $LedgerNo);
    if ($simpan == TRUE) {
        is_pesan('success', 'berhasil mengubah data');
        echo json_encode(
            array(
                'JobNo' => $JobNo,
                'pesan' => 'berhasil'
            )
        );
        die;
    }
    echo json_encode(
        array(
            'JobNo' => $JobNo,
            'pesan' => 'gagal'
        )
    );
    die;
}



    //akhir detail dan edit


    // codingan PO 


function tambah_po($JobNo = '',$flag='')
{

    $this->session->unset_userdata('KO_SESI');
    $this->session->unset_userdata('DataSesi_PURCHASE_ORDER');

    $DataSesi_PURCHASE_ORDER='';

    if ($flag=='MIX') {
        $DataSesi_PURCHASE_ORDER = 'NEW|'.$JobNo.'|||MIX';
    }else{
        $DataSesi_PURCHASE_ORDER = 'NEW|'.$JobNo.'|||';
    }

    $this->session->set_userdata('DataSesi_PURCHASE_ORDER',$DataSesi_PURCHASE_ORDER);
    if ($this->session->userdata('DataSesi_PURCHASE_ORDER') ) {
        redirect('Entry/Procurement/FrmEntryPO_C');
    }


    if ($JobNo == '') {
        redirect('Kontrak_po', 'refresh');
        die;
    }

    if (!$this->db->table_exists('KoDtl_sementara')) {
        is_pesan('info', 'tidak ada table ko sementara');
        redirect('Kontrak_po', 'refresh');
        die;
    }

    $data['judul'] = "Add Purchase Order";
    $data['title'] = "Add Purchase Order";
    $data['bodyclass'] = 'skin-blue-light';
    $data['JobNo'] = $JobNo;
    $query = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE JobNo='$JobNo' ")->row_array();
    $data['JobNm'] = $query['JobNm'];
    $data['list_no_spr'] = $this->db->query("SELECT NoSPR, UtkPekerjaan FROM PRHdr WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL ")->result_array();



        // $Alokasi = 'B';

        // $data['judul'] = "TAMBAH PURCHASE ORDER ";
        // $data['bodyclass'] = 'sidebar-collapse skin-yellow';

        // $query = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE JobNo='$JobNo' ")->row_array();
        // $data['JobNo'] = $JobNo;
        // 
        // 
        // $data['list_no_spr'] = $this->db->query("SELECT NoSPR, UtkPekerjaan FROM PRHdr WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL")->result_array();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('procurement/kontrak_po/purchase_order/form_tambah_po', $data);
    $this->load->view('templates/footer');
}

function get_ListVendor(){
    $query = $this->db->query("SELECT VendorId,VendorNm FROM Vendor")->result();
    return $query;
}

function dapat_KdRAP()
{
    if (!$this->input->post()) {
        die;
    }

    $JobNo = $this->input->post('JobNo', TRUE);
    $Alokasi = $this->input->post('Alokasi', TRUE);

    $data = '';

    $data .= '<option value="">-- Pilih Salah Satu--</option>';

    foreach (list_kode_rap($JobNo, $Alokasi) as $data_rap) :
        $data .= '<option value="' . $data_rap['KdRAP'] . '">' . $data_rap['KdRAP'] . ' - ' . $data_rap['Uraian'] . '</option>';
    endforeach;
    echo $data;
}

function tampil_order_list()
{
    if (!$this->input->post()) {
        die;
    }

    $JobNo = $this->input->post('JobNo', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

        // $data['mydata'] = $this->db->query(" SELECT DiscPercentage,DiscAmount,PPN,OverridePPN ");

    $query  = $this->db->query(" SELECT *, (Vol * HrgSatuan) as JumlahHarga  FROM KoDtl_sementara WHERE NoKO='$NoKO' ORDER BY NoUrut DESC ");
    $data['jumlah_row'] = $query->num_rows();
    $data['data_detail'] = $query->result_array();
    $data['JobNo'] = $JobNo;
    $data['NoKO'] = $NoKO;


        // print_rr($_POST);
        // die;
    $this->load->view('procurement/kontrak_po/purchase_order/tampil_order_list', $data);
}

function aksi_simpan_order_list()
{
    if (!$this->input->post()) {
        exit;
    }

    $Alokasi = $this->input->post('Alokasi', TRUE);

    $NoKO = $this->input->post('NoKO', TRUE);
    $KdRAP = $this->input->post('KdRAP', TRUE);
    $Uraian = $this->input->post('Uraian', TRUE);
    $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
    $Uom = $this->input->post('Uom', TRUE);
    $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $query_nourut = $this->db->query(" SELECT TOP 1 NoUrut FROM KoDtl_sementara WHERE NoKO='$NoKO' ORDER BY NoUrut DESC  ")->row_array();

    $NoUrut =  (int) $query_nourut['NoUrut'] + 1;

    $data = array(
        'NoKO' => $NoKO,
        'NoUrut' => $NoUrut,
        'Alokasi' => $Alokasi,
        'KdRAP' => $KdRAP,
        'Uraian' => $Uraian,
        'Vol' => $Vol,
        'Uom' => $Uom,
        'HrgSatuan' => $HrgSatuan,
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,

    );

    $simpan = $this->M_kontrak_po->aksi_simpan_order_list($data);
    if ($simpan == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;

        // print_rr($_POST);
        // print_rr($data);


}

function hapus_order()
{
    if (!$this->input->post()) {
        exit;
    }

    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

    $where = array(
        'NoUrut' => $NoUrut,
        'NoKO' => $NoKO
    );

    $hapus = $this->M_kontrak_po->hapus_order($where);
    if ($hapus == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;
}

function dapat_data_order()
{
    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);
    $JobNo = $this->input->post('JobNo', TRUE);

    $query = $this->db->query("
       SELECT 
       TOP 1 a.*, (a.Vol * a.HrgSatuan) as TotalHarga,
       (SELECT CONCAT_WS(' - ',Alokasi,Keterangan) FROM Alokasi WHERE Alokasi=a.Alokasi ) as alokasi_detail
       FROM KoDtl_sementara a
       WHERE a.NoKO='$NoKO' AND a.NoUrut='$NoUrut'
       ORDER BY NoUrut DESC")->row_array();



    $Vol = PecahAngka($query['Vol']);

    $data = array(
        'Alokasi' => htmlentities($query['alokasi_detail']),
        'kdrap_detail' => htmlentities(list_kode_rap_bukan_perulangan($JobNo, $query['Alokasi'])),
        'KdRAP' => htmlentities($query['KdRAP']),
        'Uraian' => htmlentities($query['Uraian']),
        'Vol' => htmlentities($Vol),
        'Uom' => htmlentities($query['Uom']),
        'HrgSatuan' => htmlentities(number_format($query['HrgSatuan'])),
        'TotalHarga' => htmlentities($query['TotalHarga']),
        'NoKO' => $NoKO,
        'NoUrut' => $NoUrut

    );

    echo json_encode($data);
}

function aksi_update_order_list()
{
    if (!$this->input->post()) {
        exit;
    }


    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

    $KdRAP = $this->input->post('KdRAP', TRUE);
    $Uraian = $this->input->post('Uraian', TRUE);
    $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
    $Uom = $this->input->post('Uom', TRUE);
    $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $data = array(
        'Uraian' => $Uraian,
        'Vol' => $Vol,
        'Uom' => $Uom,
        'HrgSatuan' => $HrgSatuan,
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,

    );

    $where = array(
        'NoKO' => $NoKO,
        'NoUrut' => $NoUrut
    );

    $simpan = $this->M_kontrak_po->aksi_update_order_list($data, $where);
    if ($simpan == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;


    print_rr($_POST);
    echo 'ok';
}

function simpan_po()
{
    if (!$this->input->post()) {
        die;
    }

        // print_rr($_POST);
        // exit;

        //penerima po
    $VendorId = $this->input->post('VendorId', TRUE);


        //detail
    $TglKO = $this->input->post('TglKO', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);
    $JobNo = $this->input->post('JobNo', TRUE);


        //lokasi pengiriman
    $NamaKirim = $this->input->post('NamaKirim', TRUE);
    $AlamatKirim = $this->input->post('AlamatKirim', TRUE);
    $TeleponKirim = $this->input->post('TeleponKirim', TRUE);
    $TglPengiriman = $this->input->post('TglPengiriman', TRUE);
    $MaterialApproval = ($this->input->post('MaterialApproval')) ? $this->input->post('MaterialApproval', TRUE) : 0;
    $RAP = ($this->input->post('RAP')) ? $this->input->post('RAP', TRUE) : 0;


        //ORDER LIST
    $SubTotal = $this->input->post('SubTotal', TRUE);
    $DiscPercentage = $this->input->post('DiscPercentage', TRUE);
    $DiscAmount = $this->input->post('DiscAmount', TRUE);
    $PPN = $this->input->post('PPN', TRUE);
    $GrandTotal = $this->input->post('GrandTotal', TRUE);
    $OverridePPN = ($this->input->post('OverridePPN')) ? $this->input->post('OverridePPN', TRUE) : 0;


        //persyaratan dan sanksi
    $K3 = $this->input->post('K3', TRUE);
    $SyaratTeknis = $this->input->post('SyaratTeknis', TRUE);
    $JadwalPengiriman = $this->input->post('JadwalPengiriman', TRUE);
    $JadwalPembayaran = $this->input->post('JadwalPembayaran', TRUE);
    $SyaratPembayaran = ($this->input->post('SyaratPembayaran', TRUE)) ?  MembuatMenjadiKoma($this->input->post('SyaratPembayaran', TRUE)) : '';
    $Sanksi = $this->input->post('Sanksi', TRUE);
    $Keterangan = $this->input->post('Keterangan', TRUE);


        //tambahan
    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $NoSPR = $this->input->post('NoSPR',TRUE);


    $cek_nomor_ko = $this->db->query(" SELECT NoKO FROM KoHdr WHERE NoKO='$NoKO' ");

    if ($cek_nomor_ko->num_rows() > 0) {
        echo json_encode(array('JobNo' => $JobNo, 'pesan' => 'ada_no_ko'));
        die;
    }

    $cek_data_kodtl_sementara = $this->db->query(" SELECT NoKO FROM KoDtl_sementara WHERE NoKO='$NoKO' ");
    if ($cek_data_kodtl_sementara->num_rows() == 0) {
        echo json_encode(array('JobNo' => $JobNo, 'pesan' => 'kodtl_kosong'));
        die;
    }


    $data = array(
            //penerima po
        'VendorId' => $VendorId,

            //detail
        'TglKO' => $TglKO,

            //lokasi pengiriman
        'NamaKirim' => $NamaKirim,
        'AlamatKirim' => $AlamatKirim,
        'TeleponKirim' => $TeleponKirim,
        'TglPengiriman' => $TglPengiriman,
        'MaterialApproval' => $MaterialApproval,
        'RAP' => $RAP,

            //order list
        'SubTotal' => HapusFormatUang($SubTotal),
        'DiscPercentage' => $DiscPercentage,
        'DiscAmount' => HapusFormatUang($DiscAmount),
        'PPN' => HapusFormatUang($PPN),
        'GrandTotal' => HapusFormatUang($GrandTotal),
        'OverridePPN' => $OverridePPN,


            //persyaratan dan sanksi
        'K3' => $K3,
        'SyaratTeknis' => $SyaratTeknis,
        'JadwalPengiriman' => $JadwalPengiriman,
        'JadwalPembayaran' => $JadwalPembayaran,
        'SyaratPembayaran' => $SyaratPembayaran,
        'Sanksi' => $Sanksi,
        'Keterangan' => $Keterangan,

            //tambahan
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,
        'KategoriId' => 'PO',
            // 'QRCode' => randomString(12),
        'QRCode' => NULL,

        'NoKO' => $NoKO,
        'JobNo' => $JobNo,

        'NoSPR' => $NoSPR,

    );

    $simpan_ke_kohdr = $this->M_kontrak_po->simpan_po($data);
    if ($simpan_ke_kohdr == TRUE) {
        is_pesan('success', 'Berhasil menambahkan data');
        echo json_encode(
            array(
                'JobNo' => $JobNo,
                'pesan' => 'berhasil'
            )
        );
        die;
    }

    echo json_encode(
        array(
            'JobNo' => $JobNo,
            'pesan' => 'gaga'
        )
    );
    die;


        // echo 'apa';
        // die;

        // print_rr($data);
}

function detail_po($LedgerNo = '', $KategoriId = '', $JobNo = '', $app_ko = '')
{

    if ($JobNo == '') {
        redirect('Kontrak_po', 'refresh');
        die;
    }

    if (!$this->db->table_exists('KoDtl_sementara')) {
        is_pesan('info', 'tidak ada table ko sementara');
        redirect('Kontrak_po', 'refresh');
        die;
    }

    $Alokasi = 'B';
    $data['data_po'] = $this->M_kontrak_po->dapat_data_ko($LedgerNo);

    $data['judul'] = "DETAIL PURCHASE ORDER ";
    $data['bodyclass'] = 'skin-blue-light';
    $data['app_ko'] = $app_ko;

    $data['list_no_spr'] = $this->db->query("SELECT NoSPR, UtkPekerjaan FROM PRHdr WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL")->result_array();

    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('procurement/kontrak_po/purchase_order/form_edit_po', $data);
    $this->load->view('templates/footer');
}




    //akhir codingan PO



    //PO
    //bagian edit atau detail po
function tampil_order_list_aksi_detail()
{
    if (!$this->input->post()) {
        die;
    }

    $button_app_ko = ($this->input->post('button_app_ko', TRUE)) ? $this->input->post('button_app_ko', TRUE) : '';
    $JobNo = $this->input->post('JobNo', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

        // $data['mydata'] = $this->db->query(" SELECT DiscPercentage,DiscAmount,PPN,OverridePPN ");

    $query  = $this->db->query(" SELECT *, (Vol * HrgSatuan) as JumlahHarga  FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC ");
    $data['jumlah_row'] = $query->num_rows();
    $data['data_detail'] = $query->result_array();
    $data['JobNo'] = $JobNo;
    $data['NoKO'] = $NoKO;
    $data['button_app_ko'] = $button_app_ko;


        // print_rr($_POST);
        // die;-
    $this->load->view('procurement/kontrak_po/purchase_order/tampil_order_list_edit_atau_detail', $data);
}

function aksi_simpan_order_list_aksi_detail()
{
    if (!$this->input->post()) {
        exit;
    }

    $Alokasi = $this->input->post('Alokasi', TRUE);

    $NoKO = $this->input->post('NoKO', TRUE);
    $KdRAP = $this->input->post('KdRAP', TRUE);
    $Uraian = $this->input->post('Uraian', TRUE);
    $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
    $Uom = $this->input->post('Uom', TRUE);
    $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $query_nourut = $this->db->query(" SELECT TOP 1 NoUrut FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC  ")->row_array();

    $NoUrut =  (int) $query_nourut['NoUrut'] + 1;

    $data = array(
        'NoKO' => $NoKO,
        'NoUrut' => $NoUrut,
        'Alokasi' => $Alokasi,
        'KdRAP' => $KdRAP,
        'Uraian' => $Uraian,
        'Vol' => $Vol,
        'Uom' => $Uom,
        'HrgSatuan' => $HrgSatuan,
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,

    );
    $simpan = $this->M_kontrak_po->aksi_simpan_order_list_aksi_detail($data);
    if ($simpan == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;
}

function hapus_order_aksi_detail()
{
    if (!$this->input->post()) {
        exit;
    }

    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

    $where = array(
        'NoUrut' => $NoUrut,
        'NoKO' => $NoKO
    );

    $hapus = $this->M_kontrak_po->hapus_order_aksi_detail($where);
    if ($hapus == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;
}

function dapat_data_order_aksi_detail()
{
    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);
    $JobNo = $this->input->post('JobNo', TRUE);

    $query = $this->db->query("
       SELECT 
       TOP 1 a.*, (a.Vol * a.HrgSatuan) as TotalHarga,
       (SELECT CONCAT_WS(' - ',Alokasi,Keterangan) FROM Alokasi WHERE Alokasi=a.Alokasi ) as alokasi_detail
       FROM KoDtl a
       WHERE a.NoKO='$NoKO' AND a.NoUrut='$NoUrut'
       ORDER BY NoUrut DESC")->row_array();



    $Vol = PecahAngka($query['Vol']);

    $data = array(
        'Alokasi' => htmlentities($query['alokasi_detail']),
        'kdrap_detail' => htmlentities(list_kode_rap_bukan_perulangan($JobNo, $query['Alokasi'])),
        'KdRAP' => htmlentities($query['KdRAP']),
        'Uraian' => htmlentities($query['Uraian']),
        'Vol' => htmlentities($Vol),
        'Uom' => htmlentities($query['Uom']),
        'HrgSatuan' => htmlentities(number_format($query['HrgSatuan'])),
        'TotalHarga' => htmlentities($query['TotalHarga']),
        'NoKO' => $NoKO,
        'NoUrut' => $NoUrut

    );

    echo json_encode($data);
}

function aksi_update_order_list_aksi_detail()
{
    if (!$this->input->post()) {
        exit;
    }


    $NoUrut = $this->input->post('NoUrut', TRUE);
    $NoKO = $this->input->post('NoKO', TRUE);

    $KdRAP = $this->input->post('KdRAP', TRUE);
    $Uraian = $this->input->post('Uraian', TRUE);
    $Vol = HapusFormatUang($this->input->post('Vol', TRUE));
    $Uom = $this->input->post('Uom', TRUE);
    $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan', TRUE));

    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $data = array(
        'Uraian' => $Uraian,
        'Vol' => $Vol,
        'Uom' => $Uom,
        'HrgSatuan' => $HrgSatuan,
        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,

    );

    $where = array(
        'NoKO' => $NoKO,
        'NoUrut' => $NoUrut
    );

    $simpan = $this->M_kontrak_po->aksi_update_order_list_aksi_detail($data, $where);
    if ($simpan == TRUE) {
        echo 'berhasil';
        die;
    }

    echo 'gagal';
    die;


        // print_rr($_POST);
        // echo 'ok';
}


function simpan_po_aksi_detail()
{
    if (!$this->input->post()) {
        die;
    }


        //penerima po
    $VendorId = $this->input->post('VendorId', TRUE);


        //detail
    $TglKO = $this->input->post('TglKO', TRUE);



        //lokasi pengiriman
    $NamaKirim = $this->input->post('NamaKirim', TRUE);
    $AlamatKirim = $this->input->post('AlamatKirim', TRUE);
    $TeleponKirim = $this->input->post('TeleponKirim', TRUE);
    $TglPengiriman = $this->input->post('TglPengiriman', TRUE);
    $MaterialApproval = ($this->input->post('MaterialApproval')) ? $this->input->post('MaterialApproval', TRUE) : 0;
    $RAP = ($this->input->post('RAP')) ? $this->input->post('RAP', TRUE) : 0;


        //ORDER LIST
    $SubTotal = HapusFormatUang($this->input->post('SubTotal', TRUE));
    $DiscPercentage = $this->input->post('DiscPercentage', TRUE);
    $DiscAmount = HapusFormatUang($this->input->post('DiscAmount', TRUE));
    $PPN = HapusFormatUang($this->input->post('PPN', TRUE));
    $GrandTotal = HapusFormatUang($this->input->post('GrandTotal', TRUE));
    $OverridePPN = ($this->input->post('OverridePPN')) ? $this->input->post('OverridePPN', TRUE) : 0;


        //persyaratan dan sanksi
    $K3 = $this->input->post('K3', TRUE);
    $SyaratTeknis = $this->input->post('SyaratTeknis', TRUE);
    $JadwalPengiriman = $this->input->post('JadwalPengiriman', TRUE);
    $JadwalPembayaran = $this->input->post('JadwalPembayaran', TRUE);
    $SyaratPembayaran = ($this->input->post('SyaratPembayaran', TRUE)) ?  MembuatMenjadiKoma($this->input->post('SyaratPembayaran', TRUE)) : '';
    $Sanksi = $this->input->post('Sanksi', TRUE);
    $Keterangan = $this->input->post('Keterangan', TRUE);


        //tambahan
    $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
    $TimeEntry = date('Y-m-d H:i:s');

    $NoKO = $this->input->post('NoKO', TRUE);
    $JobNo = $this->input->post('JobNo', TRUE);
    $LedgerNo = $this->input->post('LedgerNo', TRUE);

    $NoSPR = $this->input->post('NoSPR',TRUE);


        // print_rr($_POST);
        // die;





    $cek_data_kodtl = $this->db->query(" SELECT NoKO FROM KoDtl WHERE NoKO='$NoKO' ");
    if ($cek_data_kodtl->num_rows() == 0) {
        echo json_encode(array('JobNo' => $JobNo, 'pesan' => 'kodtl_kosong'));
        die;
    }

    $data = array(
            //penerima po
        'VendorId' => $VendorId,
            //detail
        'TglKO' => $TglKO,

            //lokasi pengiriman
        'NamaKirim' => $NamaKirim,
        'AlamatKirim' => $AlamatKirim,
        'TeleponKirim' => $TeleponKirim,
        'TglPengiriman' => $TglPengiriman,
        'MaterialApproval' => $MaterialApproval,
        'RAP' => $RAP,

            // order list
        'SubTotal' => $SubTotal,
        'DiscPercentage' => $DiscPercentage,
        'DiscAmount' => $DiscAmount,
        'PPN' => $PPN,
        'OverridePPN' => $OverridePPN,
        'GrandTotal' => $GrandTotal,

            //persyaratan dan sanksi
        'K3' => $K3,
        'SyaratTeknis' => $SyaratTeknis,
        'JadwalPengiriman' => $JadwalPengiriman,
        'JadwalPembayaran' => $JadwalPembayaran,
        'SyaratPembayaran' => $SyaratPembayaran,
        'Sanksi' => $Sanksi,
        'Keterangan' => $Keterangan,

        'UserEntry' => $UserEntry,
        'TimeEntry' => $TimeEntry,
        'NoSPR' => $NoSPR

    );



    $where = array(
            // 'NoKO' => $NoKO,
            // 'JobNo' => $JobNo,
        'LedgerNo' => $LedgerNo
    );

        // print_rr($where);
        // echo "<br>";
        // echo "<hr>";
        // print_rr($data);
        // die;

    $update_KoHdr = $this->M_kontrak_po->simpan_po_aksi_detail($data, $where);
    if ($update_KoHdr == TRUE) {
        is_pesan('success', 'Berhasil mengubah data');
        echo json_encode(
            array(
                'JobNo' => $JobNo,
                'pesan' => 'berhasil'
            )
        );
        die;
    }

    echo json_encode(
        array(
            'JobNo' => $JobNo,
            'pesan' => 'gagal mengubah data'
        )
    );
    die;


        // echo 'apa';
        // die;

        // print_rr($data);
}


function po_print($LedgerNo = '')
{
    if ($LedgerNo == '') {
        die;
    }



    $data['data_po'] = $this->M_kontrak_po->dapat_data_ko($LedgerNo);
        // $data['QRCode'] = 'assets/' . $this->dapat_qrcode($data['data_po']['QRCode']);
    $NoKO = $data['data_po']['NoKO'];
    $JobNo = $data['data_po']['JobNo'];
    $data['NoKO'] = $NoKO;
    $data['JobNo'] = $JobNo;
    $data['detail']  = $this->db->query(" SELECT *, (Vol * HrgSatuan) as JumlahHarga  FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut DESC ")->result_array();


    $TxtSubTotal = 0;
    $query = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut ASC ");
    if ($query->num_rows() > 0) {

        foreach ($query->result() as $row) {
            $Vol = ($row->Vol==NULL || $row->Vol=='') ? 0 : (float) $row->Vol;
            $HrgSatuan = ($row->HrgSatuan==NULL || $row->HrgSatuan=='') ? 0: (float) $row->HrgSatuan;

            $JumlahHarga = $Vol * $HrgSatuan;
            $TxtSubTotal += $JumlahHarga;

        }
    }


    $query_data_KO = $this->db->query("SELECT ISNULL(DiscPercentage,0) AS DiscPercentage, ISNULL(DiscAmount,0) AS DiscAmount, ISNULL(PotongKO,NULL) AS PotongKO, ISNULL(PPN,0) AS PPN FROM KoHdr WHERE NoKO='$NoKO' ");

    $TxtDiscPersen = 0;
    $TxtDiscNominal = 0;
    $TxtPPN = 0;
    $TxtPotongKO = NULL;
    $Hitung_GrandTotal = 0;

    if ($query_data_KO->num_rows() > 0) {
        $row = $query_data_KO->row();

        $TxtDiscPersen = (float) $row->DiscPercentage;
        $TxtDiscNominal = (float) $row->DiscAmount;
        $TxtPPN = (float) $row->PPN;

        $Hitung_GrandTotal = ($TxtSubTotal - $TxtDiscNominal) + $TxtPPN;
        $TxtPotongKO = $row->PotongKO;

    }

    $data['TxtDiscPersen'] = $TxtDiscPersen;
    $data['TxtDiscNominal'] = $TxtDiscNominal;
    $data['TxtPPN'] = $TxtPPN;
    $data['Hitung_GrandTotal'] = $Hitung_GrandTotal;
    $data['TxtDiscPersen'] = $TxtDiscPersen;
    $data['TxtSubTotal'] = $TxtSubTotal;


    // -- Cari Addendum, ADD DATE : 18 MARET 2024
    $AddendumKe = NULL;
    $query_GetAddendum = $this->db->query("SELECT A.NoKO,A.TglKO,A.KategoriId,A.AddendumKe,B.VendorNm,A.SubTotal,A.ApprovedBy,A.TimeApproved FROM KoHdr A LEFT OUTER JOIN Vendor B ON A.VendorId = B.VendorId WHERE A.NoKO='$NoKO' ");
    if ($query_GetAddendum->num_rows() > 0) {
        $rowAddendum = $query_GetAddendum->row_array();
        if ($rowAddendum['AddendumKe']!=NULL || $rowAddendum['AddendumKe']!='' ) {
            if ($rowAddendum['AddendumKe']!=0) {
                $AddendumKe = ' (Add '.$rowAddendum['AddendumKe'].')';
            }
        }
    }

    $data['AddendumKe'] = $AddendumKe;

    $this->load->view('procurement/kontrak_po/print/print_po/po_content', $data);
}

    // pengerjaan SPR ketika dipilih

function pilih_SPR(){
    if(!$this->input->post()){
        die;
    }

    $JobNo = $this->input->post('JobNo',TRUE);
    $NoSPR = $this->input->post('NoSPR',TRUE);
    $NoKO = $this->input->post('NoKO',TRUE);

    if ($NoSPR == '' || $NoSPR == NULL) {
        $this->db->query("TRUNCATE TABLE KoDtl_sementara ");
        exit;
    }


    $pindah_dari_spr_ke_kodtl_sementara = $this->M_kontrak_po->pindah_dari_spr_ke_kodtl_sementara($NoSPR,$NoKO,$JobNo);
    echo $pindah_dari_spr_ke_kodtl_sementara;
}

function pilih_SPR_edit_po(){
    if(!$this->input->post()){
        die;
    }

    $JobNo = $this->input->post('JobNo',TRUE);
    $NoSPR = $this->input->post('NoSPR',TRUE);
    $NoKO = $this->input->post('NoKO',TRUE);

    $pindah_dari_spr_ke_kodtl = $this->M_kontrak_po->pindah_dari_spr_ke_kodtl($NoSPR,$NoKO,$JobNo);
    echo $pindah_dari_spr_ke_kodtl;
}


    // == DITAMBHKAN PADA TANGGAL : 19 SEPTEMBER 2023 ==

function EntryKO_H($NoKO='',$TglKO=''){
    if ($NoKO=='') {
        is_pesan('error','No. KO tidak ditemukan');
        redirect('Entry/Procurement/KOAddendumRevisi_C');
        die;
    }

    $data['judul'] = 'SUMMARY KONTRAK ';
    $data['bodyclass'] = 'skin-blue-light';

    $row = $this->db->query("SELECT A.*, (SELECT ISNULL(JobNm,NULL) FROM Job WHERE JobNo=A.JobNo  ) AS JobNm FROM KoHdrH A WHERE A.NoKO='$NoKO' AND A.TglKO='$TglKO' ")->row();
    $QueryVendor = $this->db->query("SELECT * FROM Vendor WHERE VendorId='$row->VendorId'  ")->row();

    $data['JobNo'] = $row->JobNo;
    $data['dataKO'] = $row;
        // print_rr($data['dataKO']);
        // die;
    $data['dataVendor'] = $QueryVendor;


    $Query_KoDtl = $this->db->query("SELECT * FROM KoDtlH WHERE NoKO='$NoKO' AND TglKO='$TglKO' ORDER BY NoUrut")->result();
    $SubTotal = 0;
    $ArrayBuatan = array();
    foreach($Query_KoDtl as $myrow):

        $Vol = 0;
        $HrgSatuan = 0;
        $JumlahHarga = 0;

        $Vol = (float) $myrow->Vol;
        $HrgSatuan = (float) $myrow->HrgSatuan;
        $JumlahHarga = $Vol * $HrgSatuan;
        $SubTotal += (float) $JumlahHarga;

        $ArrayBuatan[] = (object) array(
            'NoUrut' => $myrow->NoUrut,
            'KdRAP' => $myrow->KdRAP,
            'Uraian' => $myrow->Uraian,
            'Vol' => $Vol,
            'Uom' => $myrow->Uom,
            'HrgSatuan' => $HrgSatuan,
            'JumlahHarga' => $JumlahHarga,
        );
    endforeach;

    $ListPotongKO = '';
    $TtlKO = 0;
    $TxtTtlPengurang = 0;
    $GrandTotal = 0;

    $Query_Cek = $this->db->query("SELECT NoKO, SubTotal-DiscAmount+PPN AS 'TtlKO' FROM KoHdr WHERE PotongKO='$NoKO' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL")->result_array();
    foreach ($Query_Cek as $mydata) {
        $ListPotongKO = ($ListPotongKO=='') ? $mydata['NoKO'] : implode(",", $mydata['NoKO']);
        $TtlKO += (float) $mydata['TtlKO'];
    }

    $PotongKO = $ListPotongKO;
    $TxtTtlPengurang = (float) $TtlKO;
    $GrandTotal = $SubTotal - $TxtTtlPengurang;

    $data['SubTotal'] = $SubTotal;
    $data['PotongKO'] = $PotongKO;
    $data['TxtTtlPengurang'] = $TxtTtlPengurang;
    $data['GrandTotal'] = $GrandTotal;

    $data['dataKoDtl'] = $ArrayBuatan;



    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('procurement/kontrak_po/EntryKO', $data);
    $this->load->view('templates/footer');

}

function EntryPO_H($NoKO='',$TglKO=''){
    if ($NoKO=='') {
        is_pesan('error','No. KO tidak ditemukan');
        redirect('Entry/Procurement/KOAddendumRevisi_C');
        die;
    }

    $data['judul'] = 'PURCHASE ORDER ';
    $data['bodyclass'] = 'skin-blue-light';

    $rowPO = $this->db->query("SELECT * FROM KoHdrH WHERE NoKO='$NoKO' AND TglKO='$TglKO'  ")->row();

    $rowVendor = $this->db->query("SELECT * FROM Vendor WHERE VendorId='$rowPO->VendorId' ")->row();
    $data['dataPO'] = $rowPO;
    $data['dataVendor'] = $rowVendor;


    $Query_KoDtl = $this->db->query("SELECT * FROM KoDtl WHERE NoKO='$NoKO' ORDER BY NoUrut")->result();
    $SubTotal = 0;
    $ArrayBuatan = array();
    foreach($Query_KoDtl as $myrow):

        $Vol = 0;
        $HrgSatuan = 0;
        $JumlahHarga = 0;

        $Vol = (float) $myrow->Vol;
        $HrgSatuan = (float) $myrow->HrgSatuan;
        $JumlahHarga = $Vol * $HrgSatuan;
        $SubTotal += (float) $JumlahHarga;

        $ArrayBuatan[] = (object) array(
            'NoUrut' => $myrow->NoUrut,
            'Alokasi' => $myrow->Alokasi,
            'KdRAP' => $myrow->KdRAP,
            'Uraian' => $myrow->Uraian,
            'Vol' => $Vol,
            'Uom' => $myrow->Uom,
            'HrgSatuan' => $HrgSatuan,
            'JumlahHarga' => $JumlahHarga,
        );
    endforeach;

    $PPN = 0;
    $PPN = ($rowPO->OverridePPN==1) ? (float) $rowPO->PPN : 0;
    $DiscPersen = 0;
    $DiscNominal = 0;
    $GrandTotal = 0;

    $DiscPersen = ($rowPO->DiscPercentage==NULL || $rowPO->DiscPercentage=='') ? 0  : (float) $rowPO->DiscPercentage;
    $DiscNominal = $SubTotal * ($DiscPersen / 100);

    $GrandTotal = ($SubTotal - $DiscNominal) + $PPN;

    $data['DiscPersen'] = new_decimal($DiscPersen);
    $data['DiscNominal'] = number_format($DiscNominal);
    $data['PPN'] = number_format($PPN);
    $data['GrandTotal'] = number_format($GrandTotal);

        // echo $DiscNominal;
        // die;

        // echo $SubTotal;
        // die;
    $data['dataKoDtl'] = $ArrayBuatan;
    $data['SubTotal'] = $SubTotal;
        // print_rr($ArrayBuatan);
        // exit;
    $this->load->view('templates/header', $data);
    $this->load->view('templates/sidebar');
    $this->load->view('procurement/kontrak_po/EntryPO_H_View', $data);
    $this->load->view('templates/footer');

}



}

/* End of file Kontrak_po.php */
/* Location: ./application/controllers/Kontrak_po.php */
