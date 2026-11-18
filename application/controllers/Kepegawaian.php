<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kepegawaian extends CI_Controller
{

    var $NIK=NULL;

    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('M_profile');
        $this->NIK = $this->session->userdata('MIS_LOGGED_NIK');


        if (!$this->db->table_exists('LokasiKerja')){
            $this->db->query("
                CREATE TABLE [dbo].[LokasiKerja](
                    [LedgerNo] [bigint] IDENTITY(1,1) NOT NULL,
                    [NamaLokasi] [nvarchar](100) NULL
                    ) ON [PRIMARY]

                SET IDENTITY_INSERT [dbo].[LokasiKerja] ON 

                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (1, N'Pusat')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (2, N'PT Minarta Dutahutama (Makasar)')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (3, N'PT. Rekabangun Energi Nusantara')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (4, N'PT. Kelman Sumber Bahari')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (5, N'Aceh')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (6, N'Sumatera Utara')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (7, N'Sumatera Barat')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (8, N'Riau')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (9, N'Jambi')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (10, N'Sumatera Selatan')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (11, N'Bangka Belitung')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (12, N'Bengkulu')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (13, N'Lampung')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (14, N'Jawa Barat')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (15, N'Jawa Tengah')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (16, N'Yogyakarta')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (17, N'Jawa Timur')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (18, N'Bali')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (19, N'NTB')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (20, N'NTT')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (21, N'Kalimantan Utara')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (22, N'Kalimantan Barat')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (23, N'Kalimantan Tengah')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (24, N'Kalimantan Selatan')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (25, N'Kalimantan Timur')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (26, N'Sulawesi Utara')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (27, N'Sulawesi Barat')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (28, N'Sulawesi Tengah')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (29, N'Sulawesi Tenggara')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (30, N'Sulawesi Selatan')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (31, N'Gorontalo')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (32, N'Maluku')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (33, N'Maluku Utara')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (34, N'Papua Barat')
                INSERT [dbo].[LokasiKerja] ([LedgerNo], [NamaLokasi]) VALUES (35, N'Papua')
                SET IDENTITY_INSERT [dbo].[LokasiKerja] OFF 
                ");
        }

        if (!$this->db->table_exists('tbl_golongan')){

            $this->db->query("

                CREATE TABLE [dbo].[tbl_golongan](
                    [LedgerNo] [bigint] IDENTITY(1,1) NOT NULL,
                    [IdGolongan] [nvarchar](50) NULL,
                    [NamaGolongan] [nvarchar](100) NULL
                    ) ON [PRIMARY]
                SET IDENTITY_INSERT [dbo].[tbl_golongan] ON 

                INSERT [dbo].[tbl_golongan] ([LedgerNo], [IdGolongan], [NamaGolongan]) VALUES (1, N'1', N'Non Staff')
                INSERT [dbo].[tbl_golongan] ([LedgerNo], [IdGolongan], [NamaGolongan]) VALUES (2, N'2', N'Staff')
                INSERT [dbo].[tbl_golongan] ([LedgerNo], [IdGolongan], [NamaGolongan]) VALUES (3, N'3', N'Manager')
                INSERT [dbo].[tbl_golongan] ([LedgerNo], [IdGolongan], [NamaGolongan]) VALUES (4, N'4', N'General Manager')
                INSERT [dbo].[tbl_golongan] ([LedgerNo], [IdGolongan], [NamaGolongan]) VALUES (5, N'5', N'Director')
                INSERT [dbo].[tbl_golongan] ([LedgerNo], [IdGolongan], [NamaGolongan]) VALUES (6, N'6', N'Komisaris')
                SET IDENTITY_INSERT [dbo].[tbl_golongan] OFF



                ");
            
        }

        if (!$this->db->table_exists('tbl_grade')){
            $this->db->query("
                CREATE TABLE [dbo].[tbl_grade](
                    [LedgerNo] [bigint] IDENTITY(1,1) NOT NULL,
                    [IdGrade] [nvarchar](50) NULL,
                    [NamaGrade] [nvarchar](100) NULL
                    ) ON [PRIMARY]
                SET IDENTITY_INSERT [dbo].[tbl_grade] ON 

                INSERT [dbo].[tbl_grade] ([LedgerNo], [IdGrade], [NamaGrade]) VALUES (1, N'1', N'Junior Staff')
                INSERT [dbo].[tbl_grade] ([LedgerNo], [IdGrade], [NamaGrade]) VALUES (2, N'2', N'Staff')
                INSERT [dbo].[tbl_grade] ([LedgerNo], [IdGrade], [NamaGrade]) VALUES (3, N'3', N'Senior Staff')
                SET IDENTITY_INSERT [dbo].[tbl_grade] OFF
                ");
        }

        if (!$this->db->field_exists('FotoKaryawan', 'Karyawan')) {
            $this->db->query("ALTER TABLE Karyawan ADD FotoKaryawan nvarchar(3000) NULL");
        }
        if (!$this->db->field_exists('StatusFoto','tbl_dokumen_pendukung')) {
            $this->db->query("ALTER TABLE tbl_dokumen_pendukung ADD StatusFoto nvarchar(3000) NULL");
        }

    }

    function view_profile($pilihan_menu_redirect=''){

        $data['pilihan_menu_redirect'] = ($pilihan_menu_redirect == '') ? '' : $pilihan_menu_redirect;
        

        $data['judul'] = 'PROFILE';
        $data['bodyclass'] = 'sidebar-collapse skin-purple';

        $getDataKaryawan = $this->M_profile->getDataKaryawan();

        $fields = $this->db->list_fields('Karyawan');
        $mydata = array();

        foreach ($fields as $field)
        {


            if($getDataKaryawan->num_rows() == 0) {
                $mydata[$field] = '';
            }else{

                foreach($getDataKaryawan->result() as $dataKaryawan){
                    $mydata[$field] = $dataKaryawan->$field;
                }

            }


        }

        $data['mydata'] = $mydata;

        $this->load->view('templates/header',$data);
        $this->load->view('templates/sidebar');
        $this->load->view('profile/detailKaryawan',$data);  
        $this->load->view('templates/footer');  
    }

    function pilihan_menu(){
        if (!$this->input->post('flag')) {
            die;
        }

        $myradio = $this->input->post('myradio',TRUE);
        $getDataKaryawan = $this->M_profile->getDataKaryawan()->result_array();

        $NIK = $this->NIK;
        $data['myradio'] = $myradio;
        $dataKaryawan['karyawan'] = $getDataKaryawan;

        if ($myradio == 'identitas_personal') {
            $data['NIK'] = $NIK;
            $KTP = NULL;
            $PrdAwal_KTP = NULL;
            $PrdAkhir_KTP = NULL;
            $DiterbitkanOleh_KTP = NULL;

            $query_KTP = $this->M_profile->getData('KTP');

            if ($query_KTP->num_rows() > 0 ) {

                $dataKTP = $query_KTP->row_array();

                $KTP = $dataKTP['NoID'];
                $PrdAwal_KTP = $dataKTP['PrdAwal'];
                $PrdAkhir_KTP = $dataKTP['PrdAkhir'];
                $DiterbitkanOleh_KTP = $dataKTP['DiterbitkanOleh'];

            }

            $data['KTP'] = $KTP;
            $data['PrdAwal_KTP'] = $PrdAwal_KTP;
            $data['PrdAkhir_KTP'] = $PrdAkhir_KTP;
            $data['DiterbitkanOleh_KTP'] = $DiterbitkanOleh_KTP;

            // -------


            $PASSPOR = NULL;
            $PrdAwal_PASSPOR = NULL;
            $PrdAkhir_PASSPOR = NULL;
            $DiterbitkanOleh_PASSPOR = NULL;

            $query_PASSPOR = $this->M_profile->getData('PASSPOR');



            if ($query_PASSPOR->num_rows() > 0 ) {

                $dataPASSPOR = $query_PASSPOR->row_array();

                $PASSPOR = $dataPASSPOR['NoID'];
                $PrdAwal_PASSPOR = $dataPASSPOR['PrdAwal'];
                $PrdAkhir_PASSPOR = $dataPASSPOR['PrdAkhir'];
                $DiterbitkanOleh_PASSPOR = $dataPASSPOR['DiterbitkanOleh'];

            }

            $data['PASSPOR'] = $PASSPOR;
            $data['PrdAwal_PASSPOR'] = $PrdAwal_PASSPOR;
            $data['PrdAkhir_PASSPOR'] = $PrdAkhir_PASSPOR;
            $data['DiterbitkanOleh_PASSPOR'] = $DiterbitkanOleh_PASSPOR;

            /// -----------

            $NPWP = NULL;
            $PrdAwal_NPWP = NULL;
            $PrdAkhir_NPWP = NULL;
            $DiterbitkanOleh_NPWP = NULL;

            $query_NPWP = $this->M_profile->getData('NPWP');

            if ($query_NPWP->num_rows() > 0 ) {

                $dataNPWP = $query_NPWP->row_array();

                $NPWP = $dataNPWP['NoID'];
                $PrdAwal_NPWP = $dataNPWP['PrdAwal'];
                $PrdAkhir_NPWP = $dataNPWP['PrdAkhir'];
                $DiterbitkanOleh_NPWP = $dataNPWP['DiterbitkanOleh'];

            }

            $data['NPWP'] = $NPWP;
            $data['PrdAwal_NPWP'] = $PrdAwal_NPWP;
            $data['PrdAkhir_NPWP'] = $PrdAkhir_NPWP;
            $data['DiterbitkanOleh_NPWP'] = $DiterbitkanOleh_NPWP;

            // -----------

            $KK = NULL;
            $PrdAwal_KK = NULL;
            $PrdAkhir_KK = NULL;
            $DiterbitkanOleh_KK = NULL;

            $query_KK = $this->M_profile->getData('KK');

            if ($query_KK->num_rows() > 0 ) {

                $dataKK = $query_KK->row_array();

                $KK = $dataKK['NoID'];
                $PrdAwal_KK = $dataKK['PrdAwal'];
                $PrdAkhir_KK = $dataKK['PrdAkhir'];
                $DiterbitkanOleh_KK = $dataKK['DiterbitkanOleh'];

            }

            $data['KK'] = $KK;
            $data['PrdAwal_KK'] = $PrdAwal_KK;
            $data['PrdAkhir_KK'] = $PrdAkhir_KK;
            $data['DiterbitkanOleh_KK'] = $DiterbitkanOleh_KK;

            // ----------


            $SIM_A = NULL;
            $PrdAwal_SIM_A = NULL;
            $PrdAkhir_SIM_A = NULL;
            $DiterbitkanOleh_SIM_A = NULL;

            $query_SIM_A = $this->M_profile->getData('SIM_A');

            if ($query_SIM_A->num_rows() > 0 ) {

                $dataSIM_A = $query_SIM_A->row_array();

                $SIM_A = $dataSIM_A['NoID'];
                $PrdAwal_SIM_A = $dataSIM_A['PrdAwal'];
                $PrdAkhir_SIM_A = $dataSIM_A['PrdAkhir'];
                $DiterbitkanOleh_SIM_A = $dataSIM_A['DiterbitkanOleh'];

            }

            $data['SIM_A'] = $SIM_A;
            $data['PrdAwal_SIM_A'] = $PrdAwal_SIM_A;
            $data['PrdAkhir_SIM_A'] = $PrdAkhir_SIM_A;
            $data['DiterbitkanOleh_SIM_A'] = $DiterbitkanOleh_SIM_A;

            // --------------


            $SIM_B = NULL;
            $PrdAwal_SIM_B = NULL;
            $PrdAkhir_SIM_B = NULL;
            $DiterbitkanOleh_SIM_B = NULL;

            $query_SIM_B = $this->M_profile->getData('SIM_B');

            if ($query_SIM_B->num_rows() > 0 ) {

                $dataSIM_B = $query_SIM_B->row_array();

                $SIM_B = $dataSIM_B['NoID'];
                $PrdAwal_SIM_B = $dataSIM_B['PrdAwal'];
                $PrdAkhir_SIM_B = $dataSIM_B['PrdAkhir'];
                $DiterbitkanOleh_SIM_B = $dataSIM_B['DiterbitkanOleh'];

            }

            $data['SIM_B'] = $SIM_B;
            $data['PrdAwal_SIM_B'] = $PrdAwal_SIM_B;
            $data['PrdAkhir_SIM_B'] = $PrdAkhir_SIM_B;
            $data['DiterbitkanOleh_SIM_B'] = $DiterbitkanOleh_SIM_B;

            // -----------


            $SIM_C = NULL;
            $PrdAwal_SIM_C = NULL;
            $PrdAkhir_SIM_C = NULL;
            $DiterbitkanOleh_SIM_C = NULL;

            $query_SIM_C = $this->M_profile->getData('SIM_C');

            if ($query_SIM_C->num_rows() > 0 ) {

                $dataSIM_C = $query_SIM_C->row_array();

                $SIM_C = $dataSIM_C['NoID'];
                $PrdAwal_SIM_C = $dataSIM_C['PrdAwal'];
                $PrdAkhir_SIM_C = $dataSIM_C['PrdAkhir'];
                $DiterbitkanOleh_SIM_C = $dataSIM_C['DiterbitkanOleh'];

            }

            $data['SIM_C'] = $SIM_C;
            $data['PrdAwal_SIM_C'] = $PrdAwal_SIM_C;
            $data['PrdAkhir_SIM_C'] = $PrdAkhir_SIM_C;
            $data['DiterbitkanOleh_SIM_C'] = $DiterbitkanOleh_SIM_C;

            $this->load->view('profile/form_identitas_personal',$data);

        }

        if ($myradio == 'data_pribadi') {

            $getDataKaryawan = $this->M_profile->getDataKaryawan();

            $fields = $this->db->list_fields('Karyawan');
            $mydata = array();

            foreach ($fields as $field)
            {


                if($getDataKaryawan->num_rows() == 0) {
                    $mydata[$field] = '';
                }else{

                    foreach($getDataKaryawan->result() as $dataKaryawan){
                        $mydata[$field] = $dataKaryawan->$field;
                    }

                }


            }

            $data['mydata'] = $mydata;
            $this->load->view('profile/form_data_pribadi',$data);
        }


        if ($myradio == 'data_keluarga') {


            $data['NIK'] = $NIK;
            $data['dataKeluarga'] = $this->M_profile->getKeluarga();
            $this->load->view('profile/list_data_keluarga',$data);
        }

        if ($myradio == 'data_emergency_contact') {

            $data['NIK'] = $NIK;
            $data['dataEmergencyContact'] = $this->M_profile->dapatData_EmergencyContact($NIK);
            $this->load->view('profile/view_EmergencyContact',$data);

        }

        if ($myradio == 'dataPendidikan') {

            $data['NIK'] = $NIK;
            $data['dataPendidikan'] = $this->M_profile->dapat_dataPendidikan($NIK);
            $this->load->view('profile/view_dataPendidikan',$data);

        }

        if ($myradio == 'data_ketrampilan') {

            $data['NIK'] = $NIK;
            $data['data_ketrampilan'] = $this->M_profile->dapat_data_ketrampilan($NIK);
            $this->load->view('profile/view_data_ketrampilan',$data);

        }

        if ($myradio == 'data_riwayat_pekerjaan') {

            $data['NIK'] = $NIK;
            $data['data_riwayat_pekerjaan'] = $this->M_profile->dapat_data_riwayat_pekerjaan($NIK);
            $this->load->view('profile/view_data_riwayat_pekerjaan',$data);

        }

        if ($myradio == 'data_posisi_pekerjaan') {

            $this->load->helper('minarta');


            $dapat_company = $this->M_profile->get_company($NIK);
            $dapat_list_jabatan = $this->M_profile->get_list_jabatan($dapat_company);
            $dapat_data_karyawan = $this->M_profile->dapat_data_karyawan($NIK);

            $data_posisi_kerjaan = $this->M_profile->dapat_data_posisi_pekerjaan($NIK);

            $data['CompanyKaryawan'] = $dapat_company;
            $data['list_jabatan'] = $dapat_list_jabatan;
            $data['NIK'] = $NIK;
            $data['data_posisi_pekerjaan'] = $data_posisi_kerjaan;
            $data['data_karyawan'] = $dapat_data_karyawan;

            // print_rr($data);
            // exit;

            $this->load->view('profile/view_data_posisi_pekerjaan',$data);

        }

        if ($myradio == 'data_dokumen_pendukung') {

            // $get_list_file = $this->db->where('NIK',$NIK)->get('tbl_dokumen_pendukung')->result_array(); 
            $get_list_file = $this->db->query("SELECT * FROM tbl_dokumen_pendukung WHERE NIK='$NIK' AND StatusFoto IS NULL ")->result_array();

            $data['NIK'] = $this->NIK;
            $data['data_file'] = $get_list_file;
            $this->load->view('profile/view_data_pendukung',$data);
        }

        if ($myradio == 'data_payroll') {

            // print_rr($this->session->all_userdata());
            // die;

            $data['NIK'] = $NIK;

            $this->load->view('profile/view_payroll',$data);

        }

        if ($myradio == 'Cuti') {

            $this->load->model('Model_FrmCuti');
            $data['NIK'] = $NIK;
            $data['grid_cuti_karyawan'] = $this->Model_FrmCuti->grid_cuti_karyawan($NIK);
            $data['dataKaryawanPengganti'] = $this->Model_FrmCuti->getKaryawanPengganti();
            $this->load->view('profile/cuti_main',$data);

            /*$data['NIK'] = $NIK;

            $data['data_approve_cuti'] = $this->Model_FrmCuti->get_approve_cuti($NIK);

            $sisa_cuti = $this->Model_FrmCuti->cek_sisa_cuti($NIK);
            $data['sisa_cuti'] = $sisa_cuti;

            $get_cuti = $this->Model_FrmCuti->q_table_cuti($NIK);

            $data['data_cuti'] = $get_cuti;

            $data['dataKaryawanPengganti'] = $this->Model_FrmCuti->getKaryawanPengganti();*/


            // $cek_notifikasi_cuti = $this->db->query("SELECT * FROM MyNotif WHERE NIKPenerima='$NIK' AND (DiBaca=0 OR DiBaca='0') AND DariTable = 'RequestADO'   ");
            // $TotalNotif = $cek_notif->num_rows();

            
        }

        if ($myradio == 'Memo') {

            $this->load->model('Model_Memo');

            $data['NIK'] = $NIK;
            $data['MemoSent'] = $this->Model_Memo->get_data_for_Sent($NIK);
            $data['ListKaryawan'] = $this->Model_Memo->get_list_Karyawan();

            $data['MemoInbox'] = $this->Model_Memo->dapat_data_MemoInbox($NIK);

            

            $this->load->view('FrmMemo_view/memo_main',$data);
            // print_rr($_POST);
            // echo '<hr>';
            // print_rr($data);
			// $get_list_file = $this->db->where('NIK',$NIK)->get('tbl_dokumen_pendukung')->result_array(); 
			// $get_list_file = $this->db->query("SELECT * FROM RequestADO WHERE NIK=$NIK ORDER BY DrTgl DESC")->result_array();

			// $data['NIK'] = $this->NIK;
			// $data['data_file'] = $get_list_file;
			// $this->load->view('profile/view_data_pendukung', $data);
        }

    }

    function aksi_ubah_karyawan(){
        if (!$this->input->post()) {
            redirect('Welcome','refresh');
            die;
        }

        // $NIK = $this->session->userdata('MIS_LOGGED_NIK');
        // $NIK = '160168';

        // print_rr($_POST);
        // echo $this->NIK;
        // die;

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $NIK = $this->input->post('NIK',TRUE);

        //KTP
        $KTP = $this->input->post('KTP',TRUE);
        $PrdAwal_KTP = $this->input->post('PrdAwal_KTP',TRUE);
        $PrdAkhir_KTP = $this->input->post('PrdAkhir_KTP',TRUE);
        $DiterbitkanOleh_KTP = $this->input->post('DiterbitkanOleh_KTP',TRUE);

        $data_KTP = array(
            'JenisID' => 'KTP',
            'NoID' => $KTP,
            'PrdAwal' => ($PrdAwal_KTP == '') ? NULL : $PrdAwal_KTP,
            'PrdAkhir' => ($PrdAkhir_KTP == '') ? NULL : $PrdAkhir_KTP ,
            'DiterbitkanOleh' => $DiterbitkanOleh_KTP,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );

        //PASSPOR
        $PASSPOR = $this->input->post('PASSPOR',TRUE);
        $PrdAwal_PASSPOR = $this->input->post('PrdAwal_PASSPOR',TRUE);
        $PrdAkhir_PASSPOR = $this->input->post('PrdAkhir_PASSPOR',TRUE);
        $DiterbitkanOleh_PASSPOR = $this->input->post('DiterbitkanOleh_PASSPOR',TRUE);


        $data_PASSPOR = array(
            'JenisID' => 'PASSPOR',
            'NoID' => $PASSPOR,
            'PrdAwal' => ($PrdAwal_PASSPOR == '') ? NULL : $PrdAwal_PASSPOR,
            'PrdAkhir' => ($PrdAkhir_PASSPOR == '') ? NULL : $PrdAkhir_PASSPOR ,
            'DiterbitkanOleh' => $DiterbitkanOleh_PASSPOR,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );



        //NPWP
        $NPWP = $this->input->post('NPWP',TRUE);
        $PrdAwal_NPWP = $this->input->post('PrdAwal_NPWP',TRUE);
        $PrdAkhir_NPWP = $this->input->post('PrdAkhir_NPWP',TRUE);
        $DiterbitkanOleh_NPWP = $this->input->post('DiterbitkanOleh_NPWP',TRUE);


        $data_NPWP = array(
            'JenisID' => 'NPWP',
            'NoID' => $NPWP,
            'PrdAwal' => ($PrdAwal_NPWP == '') ? NULL : $PrdAwal_NPWP,
            'PrdAkhir' => ($PrdAkhir_NPWP == '') ? NULL : $PrdAkhir_NPWP ,
            'DiterbitkanOleh' => $DiterbitkanOleh_NPWP,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );


        //KK
        $KK = $this->input->post('KK',TRUE);
        $PrdAwal_KK = $this->input->post('PrdAwal_KK',TRUE);
        $PrdAkhir_KK = $this->input->post('PrdAkhir_KK',TRUE);
        $DiterbitkanOleh_KK = $this->input->post('DiterbitkanOleh_KK',TRUE);

        $data_KK = array(
            'JenisID' => 'KK',
            'NoID' => $KK,
            'PrdAwal' => ($PrdAwal_KK == '') ? NULL : $PrdAwal_KK,
            'PrdAkhir' => ($PrdAkhir_KK == '') ? NULL : $PrdAkhir_KK ,
            'DiterbitkanOleh' => $DiterbitkanOleh_KK,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );


        $SIM_A = $this->input->post('SIM_A',TRUE);
        $PrdAwal_SIM_A = $this->input->post('PrdAwal_SIM_A',TRUE);
        $PrdAkhir_SIM_A = $this->input->post('PrdAkhir_SIM_A',TRUE);
        $DiterbitkanOleh_SIM_A = $this->input->post('DiterbitkanOleh_SIM_A',TRUE);

        $data_SIM_A = array(
            'JenisID' => 'SIM_A',
            'NoID' => $SIM_A,
            'PrdAwal' => ($PrdAwal_SIM_A == '') ? NULL : $PrdAwal_SIM_A,
            'PrdAkhir' => ($PrdAkhir_SIM_A == '') ? NULL : $PrdAkhir_SIM_A ,
            'DiterbitkanOleh' => $DiterbitkanOleh_SIM_A,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );

        $SIM_B = $this->input->post('SIM_B',TRUE);
        $PrdAwal_SIM_B = $this->input->post('PrdAwal_SIM_B',TRUE);
        $PrdAkhir_SIM_B = $this->input->post('PrdAkhir_SIM_B',TRUE);
        $DiterbitkanOleh_SIM_B = $this->input->post('DiterbitkanOleh_SIM_B',TRUE);

        $data_SIM_B = array(
            'JenisID' => 'SIM_B',
            'NoID' => $SIM_B,
            'PrdAwal' => ($PrdAwal_SIM_B == '') ? NULL : $PrdAwal_SIM_B,
            'PrdAkhir' => ($PrdAkhir_SIM_B == '') ? NULL : $PrdAkhir_SIM_B ,
            'DiterbitkanOleh' => $DiterbitkanOleh_SIM_B,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );

        $SIM_C = $this->input->post('SIM_C',TRUE);
        $PrdAwal_SIM_C = $this->input->post('PrdAwal_SIM_C',TRUE);
        $PrdAkhir_SIM_C = $this->input->post('PrdAkhir_SIM_C',TRUE);
        $DiterbitkanOleh_SIM_C = $this->input->post('DiterbitkanOleh_SIM_C',TRUE);

        $data_SIM_C = array(
            'JenisID' => 'SIM_C',
            'NoID' => $SIM_C,
            'PrdAwal' => ($PrdAwal_SIM_C == '') ? NULL : $PrdAwal_SIM_C,
            'PrdAkhir' => ($PrdAkhir_SIM_C == '') ? NULL : $PrdAkhir_SIM_C ,
            'DiterbitkanOleh' => $DiterbitkanOleh_SIM_C,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );

        // print_rr($data_PASSPOR);
        // exit;

        // $this->db->trans_start();

        // $this->db->trans_complete();
        // if ($this->db->trans_status() === FALSE){
        //     echo 'gagal';
        // }else{
        //     echo 'berhasil';
        // }

        $simpan = $this->M_profile->updateKaryawan($data_KTP, $data_PASSPOR, $data_NPWP, $data_KK,$data_SIM_A, $data_SIM_B, $data_SIM_C,$NIK);
        if ($simpan == TRUE) {
            is_pesan('success','berhasil mengubah data');
            redirect('Kepegawaian/view_profile');
            die;
        }

        is_pesan('error','gagal mengubah data');
        redirect('Kepegawaian/view_profile');
        die;

        
    }

    private function upload_foto_karyawan_ke_sistem_lain($Nama=''){

        $NamaFile = '';
        if (!empty($_FILES['FotoKaryawan']['name'])) {

            $this->load->library('upload');
            $config['upload_path']          =  url_utk_document_root_dan_nama_folder('local');
            $config['allowed_types']        = '*';
            $config['max_size']             = 2048;
            $config['file_name'] = trim($Nama).' '.date('ymdHis').' Foto';


            $this->upload->initialize($config);

            if ($this->upload->data('file_size') > 2048 ) {
                $NamaFile = 'file_besar';
            }

            if (!$this->upload->do_upload('FotoKaryawan')) {
                $NamaFile = 'gagal';

            } else {

                $NamaFile = $this->upload->data('file_name');
            }
        }

        return $NamaFile;
    }

    //awal data pribadi
    function aksi_ubah_data_pribadi(){
        if (!$this->input->post()) {
            redirect('Kepegawaian/view_profile','refresh');
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);

        $NamaFile = '';
        $NamaFile = $this->upload_foto_karyawan_ke_sistem_lain($this->input->post('Nama',TRUE));
        if ($NamaFile == 'file_besar' ) {
            is_pesan('info','Ukuran file maximal 2MB');
            redirect('Kepegawaian/view_profile'.'/'.$NIK);

        }else if ($NamaFile == 'gagal'){
            is_pesan('error','Terjadi Kesalahan, gagal mengupload file');
            redirect('Kepegawaian/view_profile'.'/'.$NIK);
        }


        $Nama = ($this->input->post('Nama',TRUE)) ? $this->input->post('Nama',TRUE) : NULL;
        $Kelamin = $this->input->post('Kelamin',TRUE);
        $TmpLahir = $this->input->post('TmpLahir',TRUE);
        $TglLahir = ($this->input->post('TglLahir',TRUE)) ? $this->input->post('TglLahir',TRUE) : NULL;
        $WN = $this->input->post('WN',TRUE);
        $StsNikah = $this->input->post('StsNikah',TRUE);
        $Agama = $this->input->post('Agama',TRUE);
        $Alamat = $this->input->post('Alamat',TRUE);
        $Provinsi = $this->input->post('Provinsi',TRUE);
        $Kota = $this->input->post('Kota',TRUE);
        $AlamatSurat = $this->input->post('AlamatSurat',TRUE);
        $Email = $this->input->post('Email',TRUE);
        $NoTelp = $this->input->post('NoTelp',TRUE);


        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');


        $data = array(
            'Nama' => $Nama,
            'Kelamin' => $Kelamin,
            'TmpLahir' => $TmpLahir,
            'TglLahir' => $TglLahir,
            'WN' => $WN,
            'StsNikah' => $StsNikah,
            'Agama' => $Agama,
            'Alamat' => $Alamat,
            'Provinsi' => $Provinsi,
            'Kota' => $Kota,
            'AlamatSurat' => $AlamatSurat,
            'Email' => $Email,
            'NoTelp' => $NoTelp,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
        );

        $aksi_ubah = $this->M_profile->aksi_update_data_pribadi($NIK, $data,$NamaFile);

        if ($aksi_ubah == TRUE) {
            is_pesan('success','Data pribadi berhasil di ubah');
            redirect('Kepegawaian/view_profile');
            die;
        }

        is_pesan('error','Data pribadi gagal di ubah');
        redirect('Kepegawaian/view_profile');
        die;

    }

    //akhir data pribadi

    //awal data keluarga
    function FormAddDataKeluarga(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);
        $data['NIK'] = $NIK;
        $this->load->view('profile/FormAddDataKeluarga',$data);

        // print_rr($_POST);

    }

    function simpanDataKeluarga(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);

        $Hubungan = $this->input->post('Hubungan',TRUE);
        $Nama = $this->input->post('Nama',TRUE);
        $Kelamin = $this->input->post('Kelamin',TRUE);
        $TglLahir = ($this->input->post('TglLahir',TRUE)) ? $this->input->post('TglLahir',TRUE) : NULL;
        $Pekerjaan = $this->input->post('Pekerjaan',TRUE);
        $Perusahaan = $this->input->post('Perusahaan',TRUE);

        $NoUrutKeluarga = $this->cekDataKeluarga($NIK);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');


        $data = array(
            'NIK' => $NIK,
            'NoUrutKeluarga' => $NoUrutKeluarga,
            'Hubungan' => $Hubungan,
            'Nama' => $Nama,
            'Kelamin' => $Kelamin,
            'TglLahir' => $TglLahir,
            'Pekerjaan' => $Pekerjaan,
            'Perusahaan' => $Perusahaan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );

        $simpan = $this->M_profile->saveDataKeluarga($data);

        if ($simpan == TRUE) {
            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;

    }

    function Aksi_Hapus_Keluarga(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);
        $NoUrutKeluarga = $this->input->post('NoUrutKeluarga',TRUE);

        $data = array(
            'NIK' => $NIK,
            'NoUrutKeluarga' => $NoUrutKeluarga
        );

        $hapus = $this->M_profile->HapusDataKeluarga($data);
        if ($hapus == TRUE) {
            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;
    }

    function dapat_form_edit_DataKeluarga(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);
        $NoUrutKeluarga = $this->input->post('NoUrutKeluarga',TRUE);

        $data = array(
            'NIK' => $NIK,
            'NoUrutKeluarga' => $NoUrutKeluarga
        );

        $getDataKeluarga = $this->M_profile->dapatDataKeluarga($data);
        $data['DataKeluarga'] = $getDataKeluarga;

        $this->load->view('profile/Form_Edit_DataKeluarga',$data);



    }

    function UpdateDataKeluarga(){

        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);
        $NoUrutKeluarga = $this->input->post('NoUrutKeluarga',TRUE);

        $Hubungan = $this->input->post('Hubungan',TRUE);
        $Nama = $this->input->post('Nama',TRUE);
        $Kelamin = $this->input->post('Kelamin',TRUE);
        $TglLahir = ($this->input->post('TglLahir',TRUE)) ? $this->input->post('TglLahir',TRUE) : NULL;
        $Pekerjaan = $this->input->post('Pekerjaan',TRUE);
        $Perusahaan = $this->input->post('Perusahaan',TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');


        $set = array(
            'Hubungan' => $Hubungan,
            'Nama' => $Nama,
            'Kelamin' => $Kelamin,
            'TglLahir' => $TglLahir,
            'Pekerjaan' => $Pekerjaan,
            'Perusahaan' => $Perusahaan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );

        $where = array(
            'NIK' => $NIK,
            'NoUrutKeluarga' => $NoUrutKeluarga
        );

        $table = 'EmpKeluarga';

        $ubah = $this->M_profile->Query_UpdateKeluarga($set,$where,$table);

        if ($ubah == TRUE) {
            echo 'berhasil';
            die;
        }
        echo 'gagal';
        die;
    }

    private function cekDataKeluarga($NIK){
        $query = $this->db->query("SELECT * FROM EmpKeluarga WHERE NIK='$NIK' ");

        $NoUrutKeluarga = 0;
        if ($query->num_rows() == 0) {
            $NoUrutKeluarga = 1;
        }else{
            $NoUrutKeluarga =  ($query->num_rows() + 1 );
        }

        return $NoUrutKeluarga;
        
        // return $query['NoUrut'];

    }

    //akhir data keluarga


    // Emergency Contact

    function tampil_FormEmergency(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);
        $data['NIK'] = $NIK;

        $this->load->view('profile/formTambah_EC',$data);
    }

    function aksiSimpan_EC(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);


        $EmergencyName = $this->input->post('EmergencyName', TRUE);
        $EmergencyRelationShip = $this->input->post('EmergencyRelationShip', TRUE);
        $EmergencyAddress = $this->input->post('EmergencyAddress', TRUE);
        $EmergencyPhone = $this->input->post('EmergencyPhone', TRUE);


        $data = array(
            'EmergencyName' => $EmergencyName,
            'EmergencyRelationShip' => $EmergencyRelationShip,
            'EmergencyAddress' => $EmergencyAddress,
            'EmergencyPhone' => $EmergencyPhone,
            'NIK' => $NIK
        );

        $where = array('NIK' => $NIK );

        $AksiSimpan = $this->M_profile->AksiSimpan_EC($data,$where);
        if ($AksiSimpan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
        
    }

    function AksiHapus_EC(){

        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $LedgerNo = $this->input->post('LedgerNo', TRUE);

        $where = array(
            'NIK' => $NIK,
            'LedgerNo' => $LedgerNo,
        );

        $AksiHapus_EC = $this->M_profile->AksiHapus_EC($where);

        if ($AksiHapus_EC == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

    }

    function tampilFormEdit_EC(){

        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $LedgerNo = $this->input->post('LedgerNo', TRUE);

        $where = array(
            'NIK' => $NIK,
            'LedgerNo' => $LedgerNo,
        );

        $dataEC = $this->db->where($where)->get('EmpEmergency')->row();
        $data['dataEC'] = $dataEC;

        $this->load->view('profile/form_EditEC',$data);

    }

    function aksiUpdate_EC(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $LedgerNo = $this->input->post('LedgerNo', TRUE);


        $EmergencyName = $this->input->post('EmergencyName', TRUE);
        $EmergencyRelationShip = $this->input->post('EmergencyRelationShip', TRUE);
        $EmergencyAddress = $this->input->post('EmergencyAddress', TRUE);
        $EmergencyPhone = $this->input->post('EmergencyPhone', TRUE);


        $set = array(
            'EmergencyName' => $EmergencyName,
            'EmergencyRelationShip' => $EmergencyRelationShip,
            'EmergencyAddress' => $EmergencyAddress,
            'EmergencyPhone' => $EmergencyPhone,
        );



        $where = array(
            'NIK' => $NIK,
            'LedgerNo' => $LedgerNo,
        );

        $aksiUpdate_EC = $this->M_profile->aksiUpdate_EC($set,$where);

        if ($aksiUpdate_EC == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
        
    }

    // akhir Emergency Contact


    //data pendidikan
    function tampil_FormTambahPendidikan(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $data['NIK'] = $NIK;

        // $dataPendidikan = $this->db->where('NIK',$NIK)->get('EmpPendidikan')->row();
        // $data['rowPendidikan'] = $dataPendidikan;

        $this->load->view('profile/formAdd_DataPendidikan',$data);

    }

    private function NoUrutPendidikan($NIK){
        $query = $this->db->query("SELECT NoUrutPendidikan FROM EmpPendidikan WHERE NIK='$NIK' ");

        $NoUrutPendidikan = 0;

        if ($query->num_rows() == 0) {
            $NoUrutPendidikan = 1;
        }else{
            $NoUrutPendidikan =  ($query->num_rows() + 1 );
        }

        return $NoUrutPendidikan;
        
        // return $query['NoUrut'];

    }

    function aksiSimpan_pendidikan(){
        if (!$this->input->post()) {
            die;
        }

        // print_rr($_POST);
        // die;

        $NIK = $this->input->post('NIK', TRUE);

        $TgkPendidikan = $this->input->post('TgkPendidikan',TRUE);

        $PrdAwal = ($this->input->post('PrdAwal', TRUE) == '') ?  NULL : $this->input->post('PrdAwal', TRUE);
        $PrdAkhir = ($this->input->post('PrdAkhir', TRUE) == '') ?  NULL : $this->input->post('PrdAkhir', TRUE);
        $Institusi = $this->input->post('Institusi', TRUE);
        $Alamat = $this->input->post('Alamat', TRUE);
        $Jurusan = $this->input->post('Jurusan', TRUE);
        $LlsTdkLls = $this->input->post('LlsTdkLls', TRUE);
        $IPK = $this->input->post('IPK', TRUE);
        $NoIjazah = $this->input->post('NoIjazah', TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $NoUrutPendidikan = $this->NoUrutPendidikan($NIK);

        // echo $NoUrutPendidikan;
        // exit;

        $array_data = array(
            'NIK' => $NIK,
            'NoUrutPendidikan' => $NoUrutPendidikan,
            'TgkPendidikan' => $TgkPendidikan,
            'PrdAwal' => $PrdAwal,
            'PrdAkhir' => $PrdAkhir,
            'Institusi' => $Institusi,
            'Alamat' => $Alamat,
            'Jurusan' => $Jurusan,
            'LlsTdkLls' => $LlsTdkLls,
            'IPK' => $IPK,
            'NoIjazah' => $NoIjazah,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
        );

        $Simpan_pendidikan = $this->M_profile->aksi_SimpanPendidikan($array_data);
        if ($Simpan_pendidikan == TRUE) {
            echo 'berhasil';
            exit;
        }

        echo 'gagal';
        exit;

    }

    function aksi_HapusPendidikan(){

        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $NoUrutPendidikan = $this->input->post('NoUrutPendidikan', TRUE);

        $where = array(
            'NIK' => $NIK,
            'NoUrutPendidikan' => $NoUrutPendidikan,
        );

        // print_rr($where);
        // exit;

        $aksi_HapusPendidikan = $this->M_profile->aksi_HapusPendidikan($where);

        if ($aksi_HapusPendidikan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

    }

    function tampilFormEdit_Pendidikan(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $NoUrutPendidikan = $this->input->post('NoUrutPendidikan', TRUE);

        $where = array(
            'NIK' => $NIK,
            'NoUrutPendidikan' => $NoUrutPendidikan,
        );

        $data['NIK'] = $NIK;
        $data['NoUrutPendidikan'] = $NoUrutPendidikan;

        $dataPendidikan = $this->db->where($where)->get('EmpPendidikan')->row();
        $data['dataPendidikan'] = $dataPendidikan;

        $this->load->view('profile/form_EditPendidikan',$data);

    }

    function aksiUpdate_pendidikan(){

        if (!$this->input->post()) {
            die;
        }
        // print_rr($_POST);

        $NIK = $this->input->post('NIK', TRUE);
        $NoUrutPendidikan = $this->input->post('NoUrutPendidikan', TRUE);

        $TgkPendidikan = $this->input->post('TgkPendidikan',TRUE);
        $PrdAwal = ($this->input->post('PrdAwal', TRUE) == '') ?  NULL : $this->input->post('PrdAwal', TRUE);
        $PrdAkhir = ($this->input->post('PrdAkhir', TRUE) == '') ?  NULL : $this->input->post('PrdAkhir', TRUE);
        $Institusi = $this->input->post('Institusi', TRUE);
        $Alamat = $this->input->post('Alamat', TRUE);
        $Jurusan = $this->input->post('Jurusan', TRUE);
        $LlsTdkLls = $this->input->post('LlsTdkLls', TRUE);
        $IPK = $this->input->post('IPK', TRUE);
        $NoIjazah = $this->input->post('NoIjazah', TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $set = array(
            'TgkPendidikan' => $TgkPendidikan,
            'PrdAwal' => $PrdAwal,
            'PrdAkhir' => $PrdAkhir,
            'Institusi' => $Institusi,
            'Alamat' => $Alamat,
            'Jurusan' => $Jurusan,
            'LlsTdkLls' => $LlsTdkLls,
            'IPK' => $IPK,
            'NoIjazah' => $NoIjazah,
        );

        $where = array(
            'NIK' => $NIK,
            'NoUrutPendidikan' => $NoUrutPendidikan,
        );

        $aksi_UpdatePendidikan = $this->M_profile->aksi_UpdatePendidikan($set,$where);

        if ($aksi_UpdatePendidikan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
        


    }

    //akhir data pendidikan


    // ------------------------batas---------------------------

    // -- awal ketrampilan -- 
    function tampil_form_add_ketrampilan(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $data['NIK'] = $NIK;
        $this->load->view('profile/form_add_ketrampilan',$data);

    }

    private function NoUrutKetrampilan($NIK){
        $query = $this->db->query("SELECT NoUrutKetrampilan FROM EmpKetrampilan WHERE NIK='$NIK' ");

        $NoUrutKetrampilan = 0;

        if ($query->num_rows() == 0) {
            $NoUrutKetrampilan = 1;
        }else{
            $NoUrutKetrampilan =  ($query->num_rows() + 1 );
        }

        return $NoUrutKetrampilan;
        
        // return $query['NoUrut'];

    }

    function aksiSimpan_ketrampilan(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);

        $Nama = $this->input->post('Nama',TRUE);
        $PrdAwal = ($this->input->post('PrdAwal', TRUE) == '') ?  NULL : $this->input->post('PrdAwal', TRUE);
        $PrdAkhir = ($this->input->post('PrdAkhir', TRUE) == '') ?  NULL : $this->input->post('PrdAkhir', TRUE);
        $Sertifikat = $this->input->post('Sertifikat', TRUE);
        $Grade = $this->input->post('Grade', TRUE);
        $NoSertifikat = $this->input->post('NoSertifikat', TRUE);
        $Institusi = $this->input->post('Institusi', TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $NoUrutKetrampilan = $this->NoUrutKetrampilan($NIK);


        $array_data = array(
            'NIK' => $NIK,
            'NoUrutKetrampilan' => $NoUrutKetrampilan,
            'Nama' => $Nama,
            'PrdAwal' => $PrdAwal,
            'PrdAkhir' => $PrdAkhir,
            'Sertifikat' => $Sertifikat,
            'Grade' => $Grade,
            'NoSertifikat' => $NoSertifikat,
            'Institusi' => $Institusi,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
        );
        // exit;

        // print_rr($_POST);
        // echo "<hr>";
        // print_rr($array_data);
        // exit;

        $aksi_simpan_ketrampilan = $this->M_profile->aksi_simpan_ketrampilan($array_data);
        if ($aksi_simpan_ketrampilan == TRUE) {
            echo 'berhasil';
            exit;
        }

        echo 'gagal';
        exit;

    }

    function aksi_hapus_ketrampilan(){

        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $NoUrutKetrampilan = $this->input->post('NoUrutKetrampilan', TRUE);

        $where = array(
            'NIK' => $NIK,
            'NoUrutKetrampilan' => $NoUrutKetrampilan,
        );

        $aksi_hapus_ketrampilan = $this->M_profile->aksi_hapus_ketrampilan($where);

        if ($aksi_hapus_ketrampilan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

    }

    function tampil_form_edit_ketrampilan(){

        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $NoUrutKetrampilan = $this->input->post('NoUrutKetrampilan', TRUE);

        $where = array(
            'NIK' => $NIK,
            'NoUrutKetrampilan' => $NoUrutKetrampilan,
        );

        $data['NIK'] = $NIK;
        $data['NoUrutKetrampilan'] = $NoUrutKetrampilan;

        $data_ketrampilan = $this->db->where($where)->get('EmpKetrampilan')->row();
        $data['data_ketrampilan'] = $data_ketrampilan;

        $this->load->view('profile/form_edit_ketrampilan',$data);

    }

    function aksi_update_ketrampilan(){

        if (!$this->input->post()) {
            die;
        }


        $NIK = $this->input->post('NIK', TRUE);
        $NoUrutKetrampilan = $this->input->post('NoUrutKetrampilan', TRUE);

        $Nama = $this->input->post('Nama',TRUE);
        $PrdAwal = ($this->input->post('PrdAwal', TRUE) == '') ?  NULL : $this->input->post('PrdAwal', TRUE);
        $PrdAkhir = ($this->input->post('PrdAkhir', TRUE) == '') ?  NULL : $this->input->post('PrdAkhir', TRUE);
        $Sertifikat = $this->input->post('Sertifikat', TRUE);
        $Grade = $this->input->post('Grade', TRUE);
        $NoSertifikat = $this->input->post('NoSertifikat', TRUE);
        $Institusi = $this->input->post('Institusi', TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $set = array(
            'Nama' => $Nama,
            'PrdAwal' => $PrdAwal,
            'PrdAkhir' => $PrdAkhir,
            'Sertifikat' => $Sertifikat,
            'Grade' => $Grade,
            'NoSertifikat' => $NoSertifikat,
            'Institusi' => $Institusi,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
        );

        $where = array(
            'NIK' => $NIK,
            'NoUrutKetrampilan' => $NoUrutKetrampilan,
        );

        $aksi_update_ketrampilan = $this->M_profile->aksi_update_ketrampilan($set,$where);

        if ($aksi_update_ketrampilan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;
        


    }

    // -- akhir ketrampilan --

    // ------------batas-----------------

    //awal riwayat pekerjaan
    function tampil_form_add_riwayat_pekerjaan(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $data['NIK'] = $NIK;
        $this->load->view('profile/tampil_form_add_riwayat_pekerjaan',$data);
    }

    function aksi_simpan_riwayat_pekerjaan(){
        if (!$this->input->post()) {
            die;
        }

        $PrdAwal = ($this->input->post('PrdAwal', TRUE) == '') ?  NULL : $this->input->post('PrdAwal', TRUE);
        $PrdAkhir = ($this->input->post('PrdAkhir', TRUE) == '') ?  NULL : $this->input->post('PrdAkhir', TRUE);
        $Perusahaan = $this->input->post('Perusahaan', TRUE);
        $Alamat = $this->input->post('Alamat', TRUE);
        $Industri = $this->input->post('Industri', TRUE);
        $Jabatan = $this->input->post('Jabatan', TRUE);

        $LokasiKerja = $this->input->post('LokasiKerja', TRUE);
        $GajiPokok = $this->input->post('GajiPokok', TRUE);

        $Tunjangan = $this->input->post('Tunjangan', TRUE);
        $UraianPekerjaan = $this->input->post('UraianPekerjaan', TRUE);

        $NIK = $this->input->post('NIK', TRUE);

        $NoUrutRwytPekerjaan = $this->NoUrutRwytPekerjaan($NIK);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');


        $data = array(
            'NIK' => $NIK,
            'NoUrutRwytPekerjaan' => $NoUrutRwytPekerjaan,
            'PrdAwal' => $PrdAwal,
            'PrdAkhir' => $PrdAkhir,
            'Perusahaan' => $Perusahaan,
            'Alamat' => $Alamat,
            'Industri' => $Industri,
            'Jabatan' => $Jabatan,
            'LokasiKerja' => $LokasiKerja,
            'GajiPokok' => $GajiPokok,
            'Tunjangan' => $Tunjangan,
            'UraianPekerjaan' => $UraianPekerjaan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
        );


        $aksi_simpan_riwayat_pekerjaan = $this->M_profile->aksi_simpan_riwayat_pekerjaan($data);

        if ($aksi_simpan_riwayat_pekerjaan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;



    }

    private function NoUrutRwytPekerjaan($NIK){
        $query = $this->db->query("SELECT NoUrutRwytPekerjaan FROM EmpPekerjaanH WHERE NIK='$NIK' ");

        $NoUrutRwytPekerjaan = 0;

        if ($query->num_rows() == 0) {
            $NoUrutRwytPekerjaan = 1;
        }else{
            $NoUrutRwytPekerjaan =  ($query->num_rows() + 1 );
        }

        return $NoUrutRwytPekerjaan;
        
        // return $query['NoUrut'];

    }

    function aksi_hapus_riwayat_pekerjaan(){

        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $NoUrutRwytPekerjaan = $this->input->post('NoUrutRwytPekerjaan', TRUE);

        $where = array(
            'NIK' => $NIK,
            'NoUrutRwytPekerjaan' => $NoUrutRwytPekerjaan,
        );

        $aksi_hapus_riwayat_pekerjaan = $this->M_profile->aksi_hapus_riwayat_pekerjaan($where);

        if ($aksi_hapus_riwayat_pekerjaan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

    }

    function tampil_form_edit_riwayat_pekerjaan(){

        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $NoUrutRwytPekerjaan = $this->input->post('NoUrutRwytPekerjaan', TRUE);

        $where = array(
            'NIK' => $NIK,
            'NoUrutRwytPekerjaan' => $NoUrutRwytPekerjaan,
        );

        $data['NIK'] = $NIK;
        $data['NoUrutRwytPekerjaan'] = $NoUrutRwytPekerjaan;

        $data_riwayat_pekerjaan = $this->db->where($where)->get('EmpPekerjaanH')->row();
        $data['data_riwayat_pekerjaan'] = $data_riwayat_pekerjaan;

        $this->load->view('profile/tampil_form_edit_riwayat_pekerjaan',$data);

    }

    function aksi_update_riwayat_pekerjaan(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK', TRUE);
        $NoUrutRwytPekerjaan = $this->input->post('NoUrutRwytPekerjaan', TRUE);

        $PrdAwal = ($this->input->post('PrdAwal', TRUE) == '') ?  NULL : $this->input->post('PrdAwal', TRUE);
        $PrdAkhir = ($this->input->post('PrdAkhir', TRUE) == '') ?  NULL : $this->input->post('PrdAkhir', TRUE);
        $Perusahaan = $this->input->post('Perusahaan', TRUE);
        $Alamat = $this->input->post('Alamat', TRUE);
        $Industri = $this->input->post('Industri', TRUE);
        $Jabatan = $this->input->post('Jabatan', TRUE);

        $LokasiKerja = $this->input->post('LokasiKerja', TRUE);
        $GajiPokok = $this->input->post('GajiPokok', TRUE);

        $Tunjangan = $this->input->post('Tunjangan', TRUE);
        $UraianPekerjaan = $this->input->post('UraianPekerjaan', TRUE);

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $set = array(
            'PrdAwal' => $PrdAwal,
            'PrdAkhir' => $PrdAkhir,
            'Perusahaan' => $Perusahaan,
            'Alamat' => $Alamat,
            'Industri' => $Industri,
            'Jabatan' => $Jabatan,
            'LokasiKerja' => $LokasiKerja,
            'GajiPokok' => $GajiPokok,
            'Tunjangan' => $Tunjangan,
            'UraianPekerjaan' => $UraianPekerjaan,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry,
        );

        $where = array(
            'NIK' => $NIK,
            'NoUrutRwytPekerjaan' => $NoUrutRwytPekerjaan,
        );


        $aksi_update_riwayat_pekerjaan = $this->M_profile->aksi_update_riwayat_pekerjaan($set,$where);

        if ($aksi_update_riwayat_pekerjaan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;


    }

    //akhir riwayat pekerjaan

    // ----batas---

    // --awal posisi pekerjaan---

    function aksi_update_posisi_pekerjaan(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);

        $ID_JobPosition = $this->input->post('ID_JobPosition',TRUE);
        $ID_JobPosition1 = $this->input->post('ID_JobPosition1',TRUE);
        $Golongan = $this->input->post('Golongan',TRUE);
        $Grade = $this->input->post('Grade',TRUE);
        $PrdAwal = ($this->input->post('PrdAwal',TRUE) == '') ? '' : $this->input->post('PrdAwal',TRUE);
        $UraianPekerjaan = $this->input->post('UraianPekerjaan',TRUE);
        $LokasiKerja = $this->input->post('LokasiKerja',TRUE);

        $set = array(
            'ID_JobPosition' => $ID_JobPosition,
            'ID_JobPosition1' => $ID_JobPosition1,
            'Golongan' => $Golongan,
            'Grade' => $Grade,
            'PrdAwal' => $PrdAwal,
            'UraianPekerjaan' => $UraianPekerjaan,
            'LokasiKerja' => $LokasiKerja,

        );

        $where = array('NIK'=>$NIK);

        $aksi_update_posisi_pekerjaan = $this->M_profile->aksi_update_posisi_pekerjaan($set,$where);
        if ($aksi_update_posisi_pekerjaan == TRUE) {
            echo 'berhasil';
            die;
        }

        echo 'gagal';
        die;

        // print_rr($_POST);
        // echo '<hr>';
        // print_rr($data);
        // exit;
    }

    // --akhir posisi pekerjaan---


    // ------batas------

    // --awal dokumen pendukung--
    function tampil_form_tambah_file(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);
        $where = array(
            'NIK' => $NIK,
        );
        $data['NIK'] = $NIK;
        $this->load->view('profile/tampil_form_tambah_file',$data);


    }

    function form_simpan_dokumen(){
        if (!$this->input->post()) {
            die;
        }

        $NIK = $this->input->post('NIK',TRUE);
        $UserName = $this->db->where('NIK',$NIK)->get('Login')->row();

        // $NamaUser = $this->session->userdata("MIS_LOGGED_NAME");
        $NamaUser = $UserName->UserName;
        $KeteranganFile = $this->input->post('KeteranganFile',TRUE);
        $TipeFile = $this->input->post('TipeFile',TRUE);

        if (!empty($_FILES['NamaFile']['name'])) {

            $this->load->library('upload');
            // $config['upload_path']          = 'assets/dokumen_pendukung_karyawan/';
            $config['upload_path']          =  hapus_file_untuk_document_root('local');
            $config['allowed_types']        = '*';
            $config['max_size']             = 2048;
            $config['file_name'] = $NIK.trim($NamaUser).' '.date('ymdHis').' DokumenOrFoto';


            $this->upload->initialize($config);

            if ($this->upload->data('file_size') > 2048 ) {
                echo 'size_lewat';
                exit;
            }

            if (!$this->upload->do_upload('NamaFile')) {
                echo 'gagal';
                exit;

            } else {

                $NamaFile = $this->upload->data('file_name');
            }
        }


        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $data = array(
            'NIK' => $NIK,
            'NamaFile' => $NamaFile,
            'TipeFile' => $TipeFile,
            'KeteranganFile' => $KeteranganFile,
            'UserEntry' => $UserEntry,
            'TimeEntry' => $TimeEntry
        );

        $this->db->trans_start();

        $this->db->insert('tbl_dokumen_pendukung',$data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo 'gagal';
        } else {
            echo 'berhasil';
        }

    }

    function lihat_file_full(){
        if (!$this->input->post()) {
            die;
        }

        $LedgerNo = $this->input->post('LedgerNo',TRUE);
        $NIK = $this->input->post('NIK',TRUE);
        $NamaFile = $this->input->post('NamaFile',TRUE);
        $TipeFile = $this->input->post('TipeFile',TRUE);

        echo file_get_contents(alamat_server_dan_local_dan_folder('server').'Akses_get_file/lihat_file_full/' . $LedgerNo.'/'.$NIK);
        die;
    }   


    function hapus_file(){

        if (!$this->input->post()) {
            die;
        }

        $LedgerNo = $this->input->post('LedgerNo',TRUE);
        $NIK = $this->input->post('NIK',TRUE);
        $NamaFile = $this->input->post('NamaFile',TRUE);
        $TipeFile = $this->input->post('TipeFile',TRUE);


        $this->db->trans_start();

        $this->db->where('LedgerNo',$LedgerNo)->delete('tbl_dokumen_pendukung');
        if (file_exists(hapus_file_untuk_document_root('local',$NamaFile))) {   
            unlink(hapus_file_untuk_document_root('local',$NamaFile));
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo 'gagal';
        } else {

            echo 'berhasil';
        }

    }

    // --akhir dokumen pendukung--

    function tampil_payroll_periode(){
        if (!$this->input->post()) {
            die;
        }

        // print_rr($_POST);
        // exit;

        $Periode = $this->input->post('Periode',TRUE);
        $NIK = $this->input->post('NIK',TRUE);

        $Periode_pecah = explode('-',$Periode);
        $PrdBulan = $Periode_pecah[0];
        $PrdTahun = $Periode_pecah[1];

        // echo $PrdBulan."<br>".$PrdTahun;
        // exit;


        // $query_get_payroll = $this->db->query("
        //     SELECT 
        //     TOP 1 a.*, 
        //     (SELECT Jabatan FROM Karyawan WHERE NIK=a.NIK ) AS Jabatan,
        //     (a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional ) AS 'GajiBruto', 
        //     (a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional - a.PPh21 - a.JHTEmployee - a.JPEmployee - a.PotonganKedisiplinan - a.PotonganTHP ) AS 'GajiNetto',
        //     a.PotonganTHP AS 'PotTHP'
        //     FROM PayrollComponent a
        //     WHERE a.NIK = '$NIK' AND 
        //     MONTH(a.Periode)='$PrdBulan' AND YEAR(a.Periode)='$PrdTahun' ORDER BY a.LedgerNo DESC")->row_array();

        $query_get_payroll = $this->db->query("
            SELECT 
            TOP 1 a.*, 
            (SELECT Jabatan FROM Karyawan WHERE NIK=a.NIK ) AS Jabatan,
            (a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional ) AS 'GajiBruto', 
            (a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional - a.PPh21 - a.JHTEmployee - a.JPEmployee - a.PotonganKedisiplinan - a.PotonganTHP ) AS 'GajiNetto',
            a.PotonganTHP AS 'PotTHP'
            FROM PayrollComponent a
            WHERE a.NIK = '$NIK' AND 
            MONTH(a.Periode)='$PrdBulan' AND YEAR(a.Periode)='$PrdTahun' ORDER BY a.TimeEntry ASC")->row_array();

        $data['info_payroll'] = $query_get_payroll;
        $data['NamaKaryawan'] = $this->session->userdata('MIS_LOGGED_NAME');


        $NamaPT = '';

        if ($query_get_payroll['Company'] == 'MDH') {
            $NamaPT = 'PT Minarta Dutahutama';

            if (file_exists('LogoPT/logoMinarta.jpeg')) {
                $LogoPT = base_url('LogoPT/logoMinarta.jpeg');
            }

        }else if ($query_get_payroll['Company'] == 'KGS') {

            $NamaPT = 'PT Kelman Global Sinergi';

            if (file_exists('LogoPT/logoKelman.jpeg')) {
                $LogoPT = base_url('LogoPT/logoKelman.jpeg');
            }

        }else{

            $NamaPT = 'PT KELMAN INFRA PRATAMA';

            if (file_exists('LogoPT/logoKelman.jpeg')) {
                $LogoPT = base_url('LogoPT/logoKelman.jpeg');
            }
        }

        $data['NamaPT'] = $NamaPT;
        $data['LogoPT'] = $LogoPT;
        $data['NIK'] = $NIK;
        $data['PrdBulan'] = $PrdBulan;
        $data['PrdTahun'] = $PrdTahun;

        $bulan = array (1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $data['BulanKata'] = strtoupper($bulan[$PrdBulan]);

        $this->load->view('profile/tampil_payroll_periode',$data);

    }

    function pdf_payroll($NIK='',$PrdBulan='',$PrdTahun=''){
        if ($NIK=='' || $PrdBulan=='' || $PrdTahun=='') {
            redirect('welcome','refresh');
        }


        // $query_get_payroll = $this->db->query("
        //     SELECT 
        //     TOP 1 a.*, 
        //     (SELECT Jabatan FROM Karyawan WHERE NIK=a.NIK ) AS Jabatan,
        //     (a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional ) AS 'GajiBruto', 
        //     (a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional - a.PPh21 - a.JHTEmployee - a.JPEmployee - a.PotonganKedisiplinan - a.PotonganTHP ) AS 'GajiNetto',
        //     a.PotonganTHP AS 'PotTHP'
        //     FROM PayrollComponent a
        //     WHERE a.NIK = '$NIK' AND 
        //     MONTH(a.Periode)='$PrdBulan' AND YEAR(a.Periode)='$PrdTahun' ORDER BY a.LedgerNo DESC")->row_array();

        $query_get_payroll = $this->db->query("
            SELECT 
            TOP 1 a.*, 
            (SELECT Jabatan FROM Karyawan WHERE NIK=a.NIK ) AS Jabatan,
            (a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional ) AS 'GajiBruto', 
            (a.GajiPokok + a.TunjanganKesehatan + a.TunjanganJabatan + a.TunjanganOperasional - a.PPh21 - a.JHTEmployee - a.JPEmployee - a.PotonganKedisiplinan - a.PotonganTHP ) AS 'GajiNetto',
            a.PotonganTHP AS 'PotTHP'
            FROM PayrollComponent a
            WHERE a.NIK = '$NIK' AND 
            MONTH(a.Periode)='$PrdBulan' AND YEAR(a.Periode)='$PrdTahun' ORDER BY a.TimeEntry ASC")->row_array();

        $data['info_payroll'] = $query_get_payroll;
        $data['NamaKaryawan'] = $this->session->userdata('MIS_LOGGED_NAME');


        $NamaPT = '';

        if ($query_get_payroll['Company'] == 'MDH') {
            $NamaPT = 'PT Minarta Dutahutama';

            if (file_exists('LogoPT/logoMinarta.jpeg')) {
                $LogoPT = base_url('LogoPT/logoMinarta.jpeg');
            }

        }else if ($query_get_payroll['Company'] == 'KGS') {

            $NamaPT = 'PT Kelman Global Sinergi';

            if (file_exists('LogoPT/logoKelman.jpeg')) {
                $LogoPT = base_url('LogoPT/logoKelman.jpeg');
            }

        }else{

            $NamaPT = 'PT KELMAN INFRA PRATAMA';

            if (file_exists('LogoPT/logoKelman.jpeg')) {
                $LogoPT = base_url('LogoPT/logoKelman.jpeg');
            }
        }

        $data['NamaPT'] = $NamaPT;
        $data['LogoPT'] = $LogoPT;
        $data['NIK'] = $NIK;
        $data['PrdBulan'] = $PrdBulan;
        $data['PrdTahun'] = $PrdTahun;

        $UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        $bulan = array (1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $data['BulanKata'] = strtoupper($bulan[$PrdBulan]);

        $this->load->view('profile/slip_gaji_tengah_pdf',$data);

    }


    function download_paksa($file_name='')
    {
        $this->load->helper('download');
        force_download(FCPATH.'/assets/dokumen_pendukung_karyawan/'.$file_name, null);
    }

    function Form_TambahCuti()
    {
      if (!$this->input->post()) {
       die;
   }

   $NIK = $this->input->post('NIK', TRUE);
   $where = array(
       'NIK' => $NIK,
   );


   $data['TtlCuti'] = $this->db->query("SELECT ISNULL(SUM(CASE WHEN Status='Cuti' THEN 1 ELSE 0.5 END),0) AS 'TtlCuti' FROM Absensi WHERE NIK='$NIK' AND (Status='Cuti' OR Status='Cuti 1/2 Hari')")->result();

   $data['Karyawan'] = $this->db->query("select * From Karyawan Where Active='1' order by NIK DESC")->result();
   $data['NIK'] = $NIK;

   $this->load->view('profile/tampil_form_tambah_Cuti', $data);
}







}
