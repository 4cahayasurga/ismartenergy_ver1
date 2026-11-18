<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Spr extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('MIS_LOGGED_NAME') == '') {
            $this->session->sess_destroy();
            redirect(base_url('auth'));
        }
        $this->load->model('Spr_model');
        AddField_SisaInvoice_To_tblBarangMasuk_Detail();
        AddField_SisaInvoice_To_Invoice_SJ();
        AddField_VersiRAP_ToTable_PRDtl();
    }

    function get_spr_table($JobNo)
    {

        header('Content-Type: application/json');
        echo $this->Spr_model->get_spr_table($JobNo);
    }


    // public function index()
    // {
    // }

    public function getForm()
    {
        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            $this->load->view('spr/spr-table');
        }
    }

    public function add($JobNo)
    {
        if ($JobNo == '') {
            redirect('Welcome');
            exit;
        }

        $this->load->model('m_job');
        $spr = $this->m_job->spr($JobNo);
        $data['spr'] = $spr;
        $data['NoSPR'] = AssignSpr($JobNo);

        $data['judul'] = 'Entry Permintaan Material / Alat';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view("spr/form-add", $data);
        $this->load->view('templates/footer');
    }

    function detail_table()
    {
        if ($this->input->post('NoSPR')) {
            $NoSPR = $this->input->post('NoSPR');
            $data['spr'] = $this->db->order_by('NoUrut', 'DESC')->get_where('PRDtlTemp', array("UserEntry" => $this->session->userdata('MIS_LOGGED_NAME')));

            if ($data['spr']->num_rows() > 0) {

                $this->load->view('spr/spr-table', $data);
            } else {
                echo '';
            }
        }
    }

    function simpanSpr()
    {

        if ($this->input->post()) {

            // $apa = substr("PR2157000002", -5);
            // echo sprintf("%03s", $apa);

            $JobNo = $this->input->post('JobNo');
            $NoSPR = $this->input->post('NoSPR');
            $TglSpr = $this->input->post('TglSpr');
            $Kepada = $this->input->post('Kepada');
            $TglPenggunaan = $this->input->post('TglPenggunaan');
            $UtkPekerjaan = $this->input->post('UtkPekerjaan');
            $NamaKirim = $this->input->post('NamaKirim');
            $TeleponKirim = $this->input->post('TeleponKirim');
            $AlamatKirim = $this->input->post('AlamatKirim');
            $TglKirim = $this->input->post('TglKirim');

            $TimeEntry = date('Y-m-d H:i:s');
            $UserEntry = $this->session->userdata('MIS_LOGGED_NAME');


            $data = array(
                "JobNo" => $JobNo,
                "NoSPR" => $NoSPR,
                "TglSpr" => $TglSpr,
                "Kepada" => $Kepada,
                "TglPenggunaan" => $TglPenggunaan,
                "UtkPekerjaan" => $UtkPekerjaan,
                "NamaKirim" => $NamaKirim,
                "TeleponKirim" => $TeleponKirim,
                "AlamatKirim" => $AlamatKirim,
                "TglKirim" => $TglKirim,
                'TimeEntry' => $TimeEntry,
                'UserEntry' => $UserEntry,


            );
            // print_rr($_POST);

            $simpan = $this->Spr_model->simpanSpr($data);
            if ($simpan == TRUE) {
                // echo 'success';
                echo json_encode(array(
                    "JobNo" => $JobNo,
                    "status" => "success",
                    "url" => site_url('Job/spr/' . $JobNo)
                ));
                exit;
            }
            echo json_encode(array("JobNo" => $JobNo, "status" => 'gagal', "url" => site_url('Job/spr/' . $JobNo)));
        }
    }

    function FormTambahDetail()
    {
        if ($this->input->post('JobNo')) {
            $data['JobNo'] = $this->input->post('JobNo');
            $data['NoSPR'] = $this->input->post('NoSPR');
            $this->load->view('spr/form-detail', $data);
        }
    }

    function getKdRAP()
    {
        if ($this->input->post('JobNo')) {
            $JobNo = $this->input->post('JobNo');
            $Alokasi = $this->input->post('Alokasi');
            $where = array(
                'JobNo' => $JobNo,
                'Alokasi' => $Alokasi,
                'Tipe !=' => 'Header'

            );
            $getData = $this->Spr_model->getKdRAP($where);
            $myquery = $getData->result();

            // $myquery = $this->db->query(" SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC ")->result();
            $html = "<option>-- Pilih Salah Satu --</option>";
            foreach ($myquery as $data) {
                $html .= "<option value=" . $data->KdRAP . ">" . $data->KdRAP . ' - ' . $data->Uraian . "</option>";
            }
            echo $html;
        }
    }
    function simpan_detail()
    {
        if ($this->input->post()) {
            // $JobNo = $this->input->post('JobNo');
            // $NoSPR = $this->input->post('NoSPR');
            $Alokasi = $this->input->post('Alokasi');
            $KdRAP = $this->input->post('KdRAP');
            $Uraian = $this->input->post('Uraian');
            $Vol = ($this->input->post('Vol') != '') ? $this->input->post('Vol') : 0;
            $Uom = $this->input->post('Uom');
            $TimeEntry = date('Y-m-d H:i:s');
            $UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
            // echo $NoSPR;
            // exit;

            $data = array(
                // 'JobNo' => $JobNo,
                // 'NoSPR' => NULL,
                'Alokasi' => $Alokasi,
                'KdRAP' => $KdRAP,
                'Uraian' => $Uraian,
                'Vol' => $Vol,
                'Uom' => $Uom,
                'TimeEntry' => $TimeEntry,
                'UserEntry' => $UserEntry,

            );

            $simpan = $this->Spr_model->simpan_detail($data);
            if ($simpan == true) {
                echo 'success';
                exit;
            }
            echo 'gagal';
            exit;
        }
    }

    function hapus_detail()
    {
        if ($this->input->post()) {
            $NoSPR = $this->input->post('NoSPR');
            $NoUrut = $this->input->post('NoUrut');
            $Alokasi = $this->input->post('Alokasi');
            $KdRAP = $this->input->post('KdRAP');

            $where = array(
                'NoUrut' => $NoUrut,
                'NoSPR' => $NoSPR,
                'Alokasi' => $Alokasi,
                'KdRAP' => $KdRAP,

            );


            $hapus = $this->Spr_model->hapus_detail($where);
            if ($hapus == TRUE) {
                echo 'success';
                exit;
            }
            echo 'gagal';
        }
    }

    function getFormEditDetail()
    {
        if ($this->input->post()) {

            $NoSPR = $this->input->post('NoSPR');
            $NoUrut = $this->input->post('NoUrut');
            $Alokasi = $this->input->post('Alokasi');
            $KdRAP = $this->input->post('KdRAP');

            $where = array(
                'NoUrut' => $NoUrut,
                'NoSPR' => $NoSPR,
                'Alokasi' => $Alokasi,
                'KdRAP' => $KdRAP,

            );


            $data['row'] = $this->db->get_where('PRDtlTemp', $where)->row();

            $this->load->view('spr/form-edit-detail', $data);
        }
    }

    function edit_detail()
    {
        if ($this->input->post()) {

            $NoSPR = $this->input->post('NoSPR');
            $Alokasi = $this->input->post('Alokasi');
            $KdRAP = $this->input->post('KdRAP');
            $Total = $this->input->post('Total');

            $HrgSatuanRAP = $this->input->post('HrgSatuanRAP');
            $VolRAP = $this->input->post('VolRAP');
            $TotalRAP = $this->input->post('TotalRAP');

            $NoUrut = $this->input->post('NoUrut');
            $Uraian = $this->input->post('Uraian');
            $Vol = ($this->input->post('Vol') != '') ? $this->input->post('Vol') : 0;
            $Uom = $this->input->post('Uom');
            $TimeEntry = date('Y-m-d H:i:s');
            $UserEntry = $this->input->post('UserEntry');

            $data = array(
                'Uraian' => $Uraian,
                'Vol' => $Vol,
                'Uom' => $Uom,
                'TimeEntry' => $TimeEntry,
                'UserEntry' => $UserEntry,
                'Total' => $Total,
                'HrgSatuanRAP' => $HrgSatuanRAP,
                'VolRAP' => $VolRAP,
                'TotalRAP' => $TotalRAP,
            );


            $where = array(
                'NoSPR' => $NoSPR,
                'UserEntry' => $UserEntry,
                'NoUrut' => $NoUrut,

            );


            $edit = $this->Spr_model->edit_detail($where, $data);
            if ($edit == true) {
                echo 'success';
                exit;
            }
            echo 'gagal';
            exit;
        }
    }

    function cekTemp()
    {
        if ($this->input->post('id')) {
            $UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
            $cek = $this->db->where('UserEntry', $UserEntry)->get('PRDtlTemp')->num_rows();
            echo json_encode(
                array(
                    'cek' => $cek
                )
            );
        }
    }



    function GetModal()
    {
        if ($this->input->post()) {
            $NoSPR = $this->input->post('NoSPR');
            $JobNo = $this->input->post('JobNo');
            $type = $this->input->post('type');

            if ($type == 'tambah') {
                $mydata = array(
                    'JobNo' => $JobNo,
                    'NoSPR' => $NoSPR,
                );
                $data['mydata'] = $mydata;
                $this->load->view('spr/form-tambah-detail', $data);
            }
        }
    }

    function getAlokasi()
    {
        if ($this->input->post()) {

            $NoSPR = $this->input->post('NoSPR');
            $JobNo = $this->input->post('JobNo');
            $Alokasi = $this->input->post('Alokasi');
            $Tipe = 'Header';

            $data = array(
                'Alokasi'   => $Alokasi,
                'JobNo'     => $JobNo,
                'Tipe !='   => $Tipe,
            );

            // $data['JobNo'] = $JobNo;
            // $data['Alokasi'] = $Alokasi;

            $data['alokasi'] = $this->db->select('KdRAP,Uraian,Tipe,JobNo,Alokasi')->where($data)->get('RAP');
            $this->load->view('spr/rap_form', $data);

            // $html = '';
            // if ($myquery->num_rows() > 0) {
            //     $html .= "<option>-- Pilih Salah Satu</option>";
            //     foreach ($myquery->result() as $row) {
            //         $html .= "<option value='" . $row->KdRAP . "'>" . $row->KdRAP . ' - ' . $row->Uraian . "</option>";
            //     }
            //     echo $html;
            // } else {
            //     echo '';
            // }
        } else {
            echo 'gagal';
        }
    }

    function simpan_detail_spr()
    {
        if (empty($this->input->post())) {
            echo '';
            exit;
        }
        $JobNo = $this->input->post('JobNo');
        $NoSPR = $this->input->post('NoSPR');
        $Alokasi = $this->input->post('Alokasi');
        $KdRAP = $this->input->post('KdRAP');
        $Uraian = $this->input->post('Uraian');
        $Vol = $this->input->post('Vol');
        $Uom = $this->input->post('Uom');
        $Total = $this->input->post('Total');

        $HrgSatuanRAP = $this->input->post('HrgSatuanRAP');
        $VolRAP = $this->input->post('VolRAP');
        $TotalRAP = $this->input->post('TotalRAP');

        // $Total = $Vol * $HrgSatuanRAP;

        $data = array(
            // 'JobNo' => $JobNo,
            'NoSPR' => $NoSPR,
            'Alokasi' => $Alokasi,
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'UserEntry' =>  $this->session->userdata('MIS_LOGGED_NAME'),
            'TimeEntry' => date("Y-m-d H:i:s"),
            'Total' => $Total,
            'HrgSatuanRAP' => $HrgSatuanRAP,
            'VolRAP' => $VolRAP,
            'TotalRAP' => $TotalRAP,

        );

        $simpan = $this->Spr_model->simpan_detail($data);
        if ($simpan == TRUE) {
            echo json_encode(array(
                'status' => TRUE,
                'NoSPR' => $NoSPR,
                'JobNo' => $JobNo
            ));
            exit;
        }
        echo json_encode(array(
            'status' => FALSE,
            'NoSPR' => $NoSPR,
            'JobNo' => $JobNo
        ));
    }

    function getFromRAP()
    {

        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            $Alokasi = $this->input->post('Alokasi');
            $KdRAP = $this->input->post('KdRAP');
            $where = array(
                'JobNo' => $JobNo,
                'Alokasi' => $Alokasi,
                'KdRAP' => $KdRAP

            );
            $query = $this->Spr_model->getFromRAP($where)->row();
            $array = array(
                'Vol' => floatval($query->Vol),
                'HrgSatuan' => floatval($query->HrgSatuan),
                'Total' => floatval(($query->Vol * $query->HrgSatuan)),

            );
            echo json_encode($array);
        }
    }







    //edit spr

    function myedit($NoSPR, $JobNo)
    {
        if ($JobNo == '') {
            redirect('Job', 'refresh');
            exit;
        }



        $myspr = $this->Spr_model->getDataSpr($JobNo, $NoSPR);
        $data['row'] = $myspr->row();

        $this->load->model('m_job');
        $spr = $this->m_job->spr($JobNo);
        $data['spr'] = $spr;

        $data['judul'] = 'Update Permintaan Material / Alat';
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view("spr/edit/form_edit_spr", $data);
        $this->load->view('templates/footer');
    }

    function detail_table_edit()
    {
        if ($this->input->post('NoSPR')) {
            $NoSPR = $this->input->post('NoSPR');
            $where = array(
                'NoSPR' => $NoSPR
            );

            $data['spr'] = $this->db->order_by('NoUrut', 'DESC')->get_where('PRDtl', $where);

            if ($data['spr']->num_rows() > 0) {

                $this->load->view('spr/edit/spr_table_edit', $data);
            } else {
                echo '';
            }
        }
    }

    function GetModalEdit()
    {
        if ($this->input->post()) {
            $NoSPR = $this->input->post('NoSPR');
            $JobNo = $this->input->post('JobNo');
            $type = $this->input->post('type');

            if ($type == 'tambah') {
                $mydata = array(
                    'JobNo' => $JobNo,
                    'NoSPR' => $NoSPR,
                );
                $data['mydata'] = $mydata;
                $this->load->view('spr/edit/form-tambah-detail-in-edit-spr', $data);
            }
        }
    }


    function simpan_detail_spr_in_edit()
    {
        if (empty($this->input->post())) {
            echo '';
            exit;
        }
        $JobNo = $this->input->post('JobNo');
        $NoSPR = $this->input->post('NoSPR');
        $Alokasi = $this->input->post('Alokasi');
        $KdRAP = $this->input->post('KdRAP');
        $Uraian = $this->input->post('Uraian');
        $Vol = $this->input->post('Vol');
        $Uom = $this->input->post('Uom');
        $Total = $this->input->post('Total');

        $HrgSatuanRAP = $this->input->post('HrgSatuan');
        $VolRAP = $this->input->post('VolRAP');
        $TotalRAP = $this->input->post('TotalRAP');

        // $Total = $Vol * $HrgSatuanRAP;

        $data = array(
            // 'JobNo' => $JobNo,
            'NoSPR' => $NoSPR,
            'Alokasi' => $Alokasi,
            'KdRAP' => $KdRAP,
            'Uraian' => $Uraian,
            'Vol' => $Vol,
            'Uom' => $Uom,
            'UserEntry' =>  $this->session->userdata('MIS_LOGGED_NAME'),
            'TimeEntry' => date("Y-m-d H:i:s"),
            'Total' => $Total,
            'HrgSatuan' => $HrgSatuanRAP,
            'VolRAP' => $VolRAP,
            'TotalRAP' => $TotalRAP,

        );

        // print_rr($_POST);

        // print_rr($data);
        // exit;

        $simpan = $this->Spr_model->simpan_detail_in_edit($data);
        if ($simpan == TRUE) {
            echo json_encode(array(
                'status' => TRUE,
                'NoSPR' => $NoSPR,
                'JobNo' => $JobNo
            ));
            exit;
        }
        echo json_encode(array(
            'status' => FALSE,
            'NoSPR' => $NoSPR,
            'JobNo' => $JobNo
        ));
    }

    function hapus_detail_edit()
    {
        if ($this->input->post()) {
            $NoSPR = $this->input->post('NoSPR');
            $NoUrut = $this->input->post('NoUrut');
            $Alokasi = $this->input->post('Alokasi');
            $KdRAP = $this->input->post('KdRAP');

            $where = array(
                'NoUrut' => $NoUrut,
                'NoSPR' => $NoSPR,
                'Alokasi' => $Alokasi,
                'KdRAP' => $KdRAP,

            );

            $hapus = $this->Spr_model->hapus_detail_edit($where);
            if ($hapus == TRUE) {
                echo 'success';
                exit;
            }
            echo 'gagal';
        }
    }

    function getFormEditDetailEdit()
    {
        if ($this->input->post()) {
            $NoSPR = $this->input->post('NoSPR');
            $NoUrut = $this->input->post('NoUrut');
            $Alokasi = $this->input->post('Alokasi');
            $KdRAP = $this->input->post('KdRAP');

            $where = array(
                'NoUrut' => $NoUrut,
                'NoSPR' => $NoSPR,
                'Alokasi' => $Alokasi,
                'KdRAP' => $KdRAP,

            );

            $data['row'] = $this->db->get_where('PRDtl', $where)->row();

            $this->load->view('spr/edit/form-edit-detail-edit', $data);
        }
    }


    function edit_detail_edit()
    {
        if ($this->input->post()) {

            $NoSPR = $this->input->post('NoSPR');
            $Alokasi = $this->input->post('Alokasi');
            $KdRAP = $this->input->post('KdRAP');
            $Total = $this->input->post('Total');

            $HrgSatuanRAP = $this->input->post('HrgSatuanRAP');
            $VolRAP = $this->input->post('VolRAP');
            $TotalRAP = $this->input->post('TotalRAP');

            $NoUrut = $this->input->post('NoUrut');
            $Uraian = $this->input->post('Uraian');
            $Vol = ($this->input->post('Vol') != '') ? $this->input->post('Vol') : 0;
            $Uom = $this->input->post('Uom');
            $TimeEntry = date('Y-m-d H:i:s');
            $UserEntry = $this->input->post('UserEntry');

            $data = array(
                'Uraian' => $Uraian,
                'Vol' => $Vol,
                'Uom' => $Uom,
                'TimeEntry' => $TimeEntry,
                'UserEntry' => $UserEntry,
                'Total' => $Total,
                'HrgSatuan' => $HrgSatuanRAP,
                'VolRAP' => $VolRAP,
                'TotalRAP' => $TotalRAP,
            );


            $where = array(
                'NoSPR' => $NoSPR,
                'NoUrut' => $NoUrut,
                'KdRAP' => $KdRAP,
                'Alokasi' => $Alokasi

            );



            $edit = $this->Spr_model->edit_detail_edit($where, $data);
            if ($edit == true) {
                echo 'success';
                exit;
            }
            echo 'gagal';
            exit;
        }
    }

    function EditSPR()
    {

        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            $NoSPR = $this->input->post('NoSPR');
            $TglSpr = $this->input->post('TglSpr');
            $Kepada = $this->input->post('Kepada');
            $TglPenggunaan = $this->input->post('TglPenggunaan');
            $UtkPekerjaan = $this->input->post('UtkPekerjaan');
            $NamaKirim = $this->input->post('NamaKirim');
            $TeleponKirim = $this->input->post('TeleponKirim');
            $AlamatKirim = $this->input->post('AlamatKirim');
            $TglKirim = $this->input->post('TglKirim');

            $TimeEntry = date('Y-m-d H:i:s');
            $UserEntry = $this->session->userdata('MIS_LOGGED_NAME');


            $data = array(

                "TglSpr" => $TglSpr,
                "Kepada" => $Kepada,
                "TglPenggunaan" => $TglPenggunaan,
                "UtkPekerjaan" => $UtkPekerjaan,
                "NamaKirim" => $NamaKirim,
                "TeleponKirim" => $TeleponKirim,
                "AlamatKirim" => $AlamatKirim,
                "TglKirim" => $TglKirim,
                'TimeEntry' => $TimeEntry,
                'UserEntry' => $UserEntry,


            );


            $where = array(
                'NoSPR' => $NoSPR,
                'JobNo' => $JobNo,

            );
            // print_rr($_POST);

            $simpan = $this->Spr_model->EditSPR($data, $where);
            if ($simpan == TRUE) {
                // echo 'success';
                echo json_encode(array(
                    "JobNo" => $JobNo,
                    "status" => "success",
                    "url" => site_url('Job/spr/' . $JobNo)
                ));
                exit;
            }
            echo json_encode(array("JobNo" => $JobNo, "status" => 'gagal', "url" => site_url('Job/spr/' . $JobNo)));
        }
    }



    //end edit spr




    // function detail_table_edit()
    // {

    //     if ($this->input->post('NoSPR')) {
    //         $NoSPR = $this->input->post('NoSPR');

    //         $where = array(

    //             'NoSPR' => $NoSPR
    //         );

    //         $data['spr'] = $this->db->order_by('NoUrut', 'DESC')->get_where('PRDtl', $where);

    //         if ($data['spr']->num_rows() > 0) {

    //             $this->load->view('spr/detail_table_edit', $data);
    //         } else {
    //             echo '';
    //         }
    //     }
    // }

    // function GetModalEdit()
    // {
    //     if ($this->input->post()) {
    //         $NoSPR = $this->input->post('NoSPR');
    //         $JobNo = $this->input->post('JobNo');
    //         $type = $this->input->post('type');

    //         if ($type == 'tambah') {

    //             $mydata = array(
    //                 'JobNo' => $JobNo,
    //                 'NoSPR' => $NoSPR,
    //             );

    //             $data['mydata'] = $mydata;

    //             $this->load->view('spr/form-edit-tambah-detail', $data);
    //         }
    //     }
    // }

    // function getAlokasiEdit()
    // {
    //     if ($this->input->post()) {

    //         $NoSPR = $this->input->post('NoSPR');
    //         $JobNo = $this->input->post('JobNo');
    //         $Alokasi = $this->input->post('Alokasi');
    //         $Tipe = 'Header';

    //         $data = array(
    //             'Alokasi'   => $Alokasi,
    //             'JobNo'     => $JobNo,
    //             'Tipe !='   => $Tipe,
    //         );

    //         $data['alokasi'] = $this->db->select('KdRAP,Uraian,Tipe,JobNo,Alokasi')->where($data)->get('RAP');
    //         $this->load->view('spr/rap_form_edit', $data);
    //     } else {
    //         echo 'gagal';
    //     }
    // }

    // function getFromRAPEdit()
    // {

    //     if ($this->input->post()) {
    //         $JobNo = $this->input->post('JobNo');
    //         $Alokasi = $this->input->post('Alokasi');
    //         $KdRAP = $this->input->post('KdRAP');
    //         $where = array(
    //             'JobNo' => $JobNo,
    //             'Alokasi' => $Alokasi,
    //             'KdRAP' => $KdRAP

    //         );
    //         $query = $this->Spr_model->getFromRAPEdit($where)->row();
    //         $array = array(
    //             'Vol' => floatval($query->Vol),
    //             'HrgSatuan' => floatval($query->HrgSatuan),
    //             'Total' => floatval(($query->Vol * $query->HrgSatuan)),

    //         );
    //         echo json_encode($array);
    //     }
    // }
}

/* End of file Controllername.php */
