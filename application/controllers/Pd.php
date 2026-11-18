
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pd extends CI_Controller
{
    private $akses;
    public function __construct()
    {
        parent::__construct();
        $this->akses = array(
            'UserID' => GetAksesUser('UserID')

        );
        $this->akses = GetAksesUser('UserID');
        $this->load->model('Pd_model');
    }


    public function edit($JobNo = '', $NoPD = '')
    {
        if ($JobNo == '' || $NoPD == '') {
            redirect('Admin/pd/' . $JobNo);
            exit;
        }

        $pdkeu = $this->Pd_model->getPD($JobNo, $NoPD);

        // $data['pdkeu'] = $pdkeu;

        $data['judul'] = 'EDIT PERMINTAAN DANA';
        $data['pdkeu'] = $pdkeu->row();
        // $data['get_tipe_form'] = GetTipeForm($Alokasi_Pd)->result();
        // $data['KodeUrutPd'] = KodeUrutPd($JobNo, $Alokasi_Pd);


        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('content/JobEntry/editpd', $data);
        $this->load->view('templates/footer');
    }

    function getDetailUraianPD()
    {
        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            $NoPD = $this->input->post('NoPD');

            $data['disab'] = '';
            $cek = $this->Pd_model->getPD($JobNo, $NoPD)->row();
            if ($cek->ApprovedByAK != '') {
                $data['disab'] = 'disabled';
            }

            $data['row'] = $this->Pd_model->getPdDetail($NoPD)->result();
            $data['JobNo'] = $JobNo;
            $this->load->view('view_table/pd/table_detail_pd', $data);
        }
    }

    function getEditDetailPd()
    {
        if ($this->input->post()) {
            $NoPD = $this->input->post('NoPD');
            $NoUrut = $this->input->post('NoUrut');
            $JobNo = $this->input->post('JobNo');
            $data['row'] = $this->db->get_where('PdDtl', array(
                'NoPD' => $NoPD,
                'NoUrut' => $NoUrut

            ))->row();
            $data['JobNo'] = $JobNo;
            $cek = $this->Pd_model->getPD($JobNo, $NoPD)->row();
            if ($cek->ApprovedByAK != '') {
                $data['disab'] = 'disabled';
            }
            $this->load->view('form/pd/form_edit_detail', $data);
        }
    }

    function do_edit_detail()
    {
        if ($this->input->post()) {
            $NoUrut = $this->input->post('NoUrut');
            $KdRAP = $this->input->post('KdRAP');
            $Uraian = $this->input->post('Uraian');
            $Vol = $this->input->post('Vol');
            $Uom = $this->input->post('Uom');
            $HrgSatuan = HapusFormatUang($this->input->post('HrgSatuan'));
            $NoPD = $this->input->post('NoPD');

            $data = array(
                'Uraian' => $Uraian,
                'Vol' => $Vol,
                'Uom' => $Uom,
                'HrgSatuan' => $HrgSatuan,
            );

            // print_rr($data);
            // exit;

            $where = array(
                'NoUrut' => $NoUrut,
                'NoPD' => $NoPD
            );

            $json = json_encode(array(
                'status' => 'success',
                'NoPD' => $NoPD
            ));

            $simpan = $this->Pd_model->do_edit_detail($where, $data);
            if ($simpan === TRUE) {
                echo $json;
                exit;
            }
            json_encode(array(
                'status' => 'no',
                'NoPD' => $NoPD
            ));
            exit;
        }
    }

    function hapus_detail()
    {
        if ($this->input->post()) {
            $NoUrut = $this->input->post('NoUrut');
            $NoPD = $this->input->post('NoPD');

            $where = array(
                'NoUrut' => $NoUrut,
                'NoPD' => $NoPD
            );

            $json = json_encode(array(
                'status' => 'success',
                'NoPD' => $NoPD
            ));

            $hapus = $this->Pd_model->do_hapus($where);
            if ($hapus === TRUE) {
                echo $json;
                exit;
            }
            json_encode(array(
                'status' => 'no',
                'NoPD' => $NoPD
            ));
            exit;
        }
    }

    function do_edit_pd()
    {
        if ($this->input->post()) {
            $NoPD = $this->input->post('NoPD', TRUE);
            $JobNo = $this->input->post('JobNo', TRUE);
            $NoRef = $this->input->post('NoRef', TRUE);
            $TglPD = $this->input->post('tglPermintaan', TRUE);
            $Jaminan = (isset($_POST['pd_jaminan'])) ? $this->input->post('pd_jaminan', TRUE) : 0;
            $Deskripsi = $this->input->post('Deskripsi', TRUE);
            $PrdAwal = $this->input->post('periode');
            $PrdAkhir = $this->input->post('periode2');
            $Minggu = $this->input->post('mingguke');
            $BuktiPendukung = (isset($_POST['BuktiPendukung'])) ?  MembuatMenjadiKoma($this->input->post('BuktiPendukung')) : '';
            $NoTagihan = $this->input->post('NoTagihan');
            $NoRek = $this->input->post('Norek');
            $AtasNama = $this->input->post('Anbank');
            $Bank = $this->input->post('Bank');
            $Keterangan = $this->input->post('Keterangan');

            $TotalPD = (isset($_POST['TotalPD'])) ? $this->input->post('TotalPD', TRUE) : 0;

            $data = array(
                'NoRef' => $NoRef,
                'TglPD' => $TglPD,
                'Jaminan' => $Jaminan,
                'Deskripsi' => $Deskripsi,
                'PrdAwal' => $PrdAwal,
                'PrdAkhir' => $PrdAkhir,
                'Minggu' => $Minggu,
                'BuktiPendukung' => $BuktiPendukung,
                'NoTagihan' => $NoTagihan,
                'Keterangan' => $Keterangan,
                'NoRek' => $NoRek,
                'AtasNama' => $AtasNama,
                'Bank' => $Bank,
                'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
                'TimeEntry' => date('Y-m-d h:i:s'),
                'TotalPD' => HapusFormatUang($TotalPD)
            );

            $where = array(
                'NoPD' => $NoPD,
                'JobNo' => $JobNo

            );

            $edit = $this->Pd_model->EditPD($where, $data);
            if ($edit === TRUE) {
                echo json_encode(array(
                    'status' => 'success',
                ));
                exit;
            }
            echo json_encode(array(
                'status' => 'no',
            ));
        }
    }

    function prn($JobNo = '', $alokasi, $cari = '')
    {
        if ($JobNo != '' && $alokasi != '') {

            $data['pd']  = $this->Pd_model->getDataPd($JobNo, $alokasi, $cari)->result();
            $data['row']  = $this->db->select('JobNo,JobNm')->get_where('Job', array('JobNo' => $JobNo))->row();

            $data['alokasi'] = '';
            if ($alokasi != '') {
                $get = $this->db->select("CONCAT_WS(' - ',Alokasi,Keterangan) as gabung")->where('Alokasi', $alokasi)->get('Alokasi')->row();
                $data['alokasi'] = "<span class='judul'>Alokasi : " . $get->gabung . "</span><br>";
            }

            $data['cari'] = '';
            if ($cari != '') {

                $data['cari'] = "<span class='judul'>Pencarian : " . $cari . "</span><br>";
            }

            $this->load->library('pdf');
            $this->pdf->setPaper('A3', 'Landscape');
            $this->pdf->filename = "Rpt-Pd-" . date('Y-m-d H:i:s') . "-.pdf";
            $this->pdf->load_view('pd/RptPd', $data);
        }
    }
}

/* End of file Controllername.php */
