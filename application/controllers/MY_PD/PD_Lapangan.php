<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PD_Lapangan extends CI_Controller {

	var $Company;

	function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->helper('bantu');
        $this->Company = $this->config->item('Company');
        
    }

    function index($JobNo='', $Alokasi='', $dari_tgl='', $sampai_tgl=''){
    	if ($JobNo=='') {
    		is_pesan('warning','akses di tolak');
    		redirect('Welcome','refresh');
    	}

    	$data['judul'] = 'PERMINTAAN DANA / LAPANGAN';
    	$data['bodyclass'] = 'sidebar-collapse skin-green-light';
    	$data['JobNo'] = $JobNo;

    	$job_data = job_data($JobNo);
    	$data['job_detail'] = $job_data->JobNo.' - '.$job_data->JobNm.' ('.$job_data->Lokasi.') ';
    	$data['Alokasi'] = $Alokasi;

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('MY_PD/PD_LAPANGAN/halaman_utama_pd_lapangan', $data);
        $this->load->view('templates/footer');
    }

    function list_pd_lapangan(){
    	if (!$this->input->post()) {
    		echo 'gagal';
    		die;
    	}

    	$JobNo = ($this->input->post('JobNo',TRUE)) ? $this->input->post('JobNo',TRUE) : '';
    	$Alokasi = ($this->input->post('Alokasi',TRUE)) ? $this->input->post('Alokasi',TRUE) : '';
    	$dari_tgl = ($this->input->post('dari_tgl',TRUE)) ? $this->input->post('dari_tgl',TRUE) : '';
    	$sampai_tgl = ($this->input->post('sampai_tgl',TRUE)) ? $this->input->post('sampai_tgl',TRUE) : '';
        $LedgerNo = $this->db->select('LedgerNo')->where('JobNo',$JobNo)->where('Alokasi',$Alokasi)->get('PdHdr')->row()->LedgerNo;


    	if (($dari_tgl == '' && $sampai_tgl !='') || ($dari_tgl != '' && $sampai_tgl =='')) {
    		echo 'tgl_salah';
    		die;
    	}

    	if ($dari_tgl > $sampai_tgl ) {
    		echo 'tgl_salah';
    		die;
    	}

    	$data['JobNo'] = $JobNo;
    	$data['Alokasi'] = $Alokasi;
    	$data['dari_tgl'] = $dari_tgl;
    	$data['sampai_tgl'] = $sampai_tgl;
        $data['LedgerNo'] = $LedgerNo;

    	$this->load->view('MY_PD/PD_LAPANGAN/list_pd_lapangan', $data);

    }

    function  get_data_PD(){

        if (!$this->input->post()) {
            echo 'gagal';
            die;
        }

        $JobNo = ($this->input->post('JobNo',TRUE)) ? $this->input->post('JobNo',TRUE) : '';
        $Alokasi = ($this->input->post('Alokasi',TRUE)) ? $this->input->post('Alokasi',TRUE) : '';
        $dari_tgl = ($this->input->post('dari_tgl',TRUE)) ? date('Y-m-d',strtotime($this->input->post('dari_tgl',TRUE))) : '';
        $sampai_tgl = ($this->input->post('sampai_tgl',TRUE)) ? date('Y-m-d',strtotime($this->input->post('sampai_tgl',TRUE))) : '';



        header('Content-Type: application/json');
        echo $this->get_data_PD_json($JobNo,$Alokasi,$dari_tgl,$sampai_tgl);

    }

    function get_data_PD_json($JobNo,$Alokasi,$dari_tgl='',$sampai_tgl=''){
        $this->load->library('Datatables');
        $this->datatables->select("
            LedgerNo, JobNo, NoPD, NoRef, 
            TglPD, Alokasi, TipeForm, NoKO, 
            TotalPD, ApprovedByAK, TimeApprovedAK, 
            ApprovedByKT, ApprovedByKK,
            CASE WHEN (ApprovedByKT IS NOT NULL OR ApprovedByKT !='') THEN 'ada_KT' ELSE '' END AS 'cek_KT',
            CASE WHEN (ApprovedByKK IS NOT NULL OR ApprovedByKK !='') THEN 'ada_KK' ELSE '' END AS 'cek_KK'

            ");
        $this->datatables->from('PdHdr');
        $this->datatables->where('JobNo',$JobNo);
        $this->datatables->where('Alokasi',$Alokasi);
        $this->datatables->where("(NoRef IS NOT NULL AND NoRef<>'')", NULL, FALSE);

        if ($dari_tgl != '' && $sampai_tgl != '') {
            $this->datatables->where('TglPD >=', $dari_tgl);
            $this->datatables->where('TglPD <=', $sampai_tgl);
        }

        $this->datatables->add_column('btn_approve','','LedgerNo');

        $this->datatables->edit_column('TglPD', '$1', 'TanggalSaya(TglPD)');
        $this->datatables->edit_column('TimeApprovedAK', '$1', 'TanggalSaya(TimeApprovedAK)');
        return $this->datatables->generate();
    }

    function cek_icon($ApprovedByAK){
        if ($ApprovedByAK == '' OR $ApprovedByAK == null) {
            return '<i class="fa fa-check"></i>';
        }else{
            return '<i class="fa fa-close"></i>';
        }
    }

    function aksi_approve(){
        if (!$this->input->post()) {
            echo 'salah';
            die;
        }

        $LedgerNo = ($this->input->post('LedgerNo',TRUE)) ? $this->input->post('LedgerNo',TRUE) : '';
        $JobNo = ($this->input->post('JobNo',TRUE)) ? $this->input->post('JobNo',TRUE) : '';
        $Alokasi = ($this->input->post('Alokasi',TRUE)) ? $this->input->post('Alokasi',TRUE) : '';
        
        $ApprovedByKT = $this->input->post('ApprovedByKT',TRUE);
        $ApprovedByKK = $this->input->post('ApprovedByKK',TRUE);
        $type_app = ($this->input->post('type_app',TRUE)) ? $this->input->post('type_app',TRUE) : '';

        $set['ApprovedByAK'] = $this->session->userdata("MIS_LOGGED_NAME");
        $set['TimeApprovedAK'] = date('Y-m-d H:i:s');

        if ($type_app == 'UNAPPROVE') {
            if ($ApprovedByKT == 'ada_KT') {
                echo 'ada_KT';
                die;
            }
            if ($ApprovedByKK == 'ada_KK') {
                echo 'ada_KK';
                die;
            }

            $set['ApprovedByAK'] = NULL;
            $set['TimeApprovedAK'] = NULL;
        }
        

        $this->db->trans_start();
        $query = $this->db->set($set)->where('LedgerNo',$LedgerNo)->update('PdHdr');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            echo 'gagal';
        }else{
            echo 'berhasil';
        }


    }

    function aksi_approve_old(){
        if (!$this->input->post()) {
            echo 'salah';
            die;
        }

        $LedgerNo = ($this->input->post('LedgerNo',TRUE)) ? $this->input->post('LedgerNo',TRUE) : '';
        $JobNo = ($this->input->post('JobNo',TRUE)) ? $this->input->post('JobNo',TRUE) : '';
        $Alokasi = ($this->input->post('Alokasi',TRUE)) ? $this->input->post('Alokasi',TRUE) : '';

        $type_app = ($this->input->post('type_app',TRUE)) ? $this->input->post('type_app',TRUE) : '';

        $query = $this->db->select("
            ApprovedByAK, 
            CASE WHEN (ApprovedByKT IS NULL OR ApprovedByKT !='') THEN 'kosong_KT' ELSE ApprovedByKT END AS 'cek_KT', 
            CASE WHEN (ApprovedByKK IS NULL OR ApprovedByKK ='') THEN 'kosong_KK' ELSE ApprovedByKT END AS 'cek_KK' ")->where('LedgerNo',$LedgerNo)->get('PdHdr')->row_array();


        $set['ApprovedByAK'] = $this->session->userdata("MIS_LOGGED_NAME");
        $set['TimeApprovedAK'] = date('Y-m-d H:i:s');

        if ($type_app == 'UNAPPROVE') {

            if ($query['cek_KT'] != 'kosong_KT') {
                echo 'ada_KT';
                die;
            }

            if ($query['cek_KK'] != 'kosong_KK') {
                echo 'ada_KK';
                die;
            }

            $set['ApprovedByAK'] = NULL;
            $set['TimeApprovedAK'] = NULL;
        }

        // echo 

        print_rr($set);
        DIE;


        $this->db->trans_start();
        $query = $this->db->set($set)->where('LedgerNo',$LedgerNo)->update('PdHdr');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            echo 'gagal';
        }else{
            echo 'berhasil';
        }




        // print_rr($set);
        // die;

        // if ($type_app == 'UNAPPROVE') {
        //     $set['ApprovedByAK'] = NULL;
        //     $set['TimeApprovedAK'] = NULL;
        // }

        // if ($type_app == 'APPROVE') {
        //     $set['ApprovedByAK'] = $ApprovedByAK;
        //     $set['TimeApprovedAK'] = $TimeApprovedAK;
        // }

        // $this->db->trans_start();
        // $query = $this->db->set($set)->where('LedgerNo',$LedgerNo)->update('PdHdr');
        // $this->db->trans_complete();

        // if ($this->db->trans_status() === FALSE)
        // {
        //     echo 'gagal';
        // }else{
        //     echo 'berhasil';
        // }
    }

    function cekJob(){
        if (!$this->input->post()) {
            die;
        }
        $JobNo = $this->input->post('JobNo',TRUE);
        $from = $this->input->post('from',TRUE);

         // echo json_encode(array('status' => 'bukan_kso'));
         // die;

        $job_data = $this->db->select('TipeManajerial, KSO ,JobNm')->where('JobNo',$JobNo)->get('Job')->row_array();


        
        if ($from == 'tambah_PD') {
            if ($job_data['TipeManajerial'] == 'JO Integrated' ) {
                echo json_encode(array('status' => 'jo', 'pesan' => $JobNo.' - '.$job_data['JobNm'].' merupakan JO Integrated'  ));
                die;
            }
        }

        if ($from == 'tambah_PDRKD') {
           if ($job_data['KSO'] !='1' || $job_data['KSO'] !=1 ) {
                echo json_encode(array('status' => 'bukan_kso', 'pesan' => $JobNo.' - '.$job_data['JobNm'].' bukan KSO'  ));
                die;
            } 
        }

        echo json_encode(array('status' => 'sukses'));

        
    }

    // ===== jika jenis PD = 1, maka artinya tambah PD, jika 0 maka jenis PD tambah_PDRKD
    function PD_ADD($from='', $LedgerNo='', $JobNo='', $Alokasi='', $dari_tgl='',$sampai_tgl=''){

        if ($from == '' || $LedgerNo == ''  ) {
            is_pesan('error', 'Terjadi Kesalahan');
            redirect('MY_PD/PD_Lapangan/index/'.$JobNo, 'refresh');
        }

        // echo $from.' - '.$LedgerNo.' - '.$JobNo.' - '.$Alokasi.' - '.$dari_tgl.' - '.$sampai_tgl;
        // die;

        $data['judul'] = 'ENTRY PERMINTAAN DANA / LAPANGAN';
        $data['bodyclass'] = 'sidebar-collapse skin-green-light';

        $data_job = $this->db->select('JobNm, Lokasi')->from('Job')->where('JobNo',$JobNo)->get()->row();

        $data['JobNm'] = $data_job->JobNm;
        $data['Lokasi'] = $data_job->Lokasi;

        $data['job_detail'] = $JobNo.' - '.$data['JobNm'].' ('.$data['Lokasi'].') ';

        $PembuatanPD =  KodeUrutPd($JobNo, $Alokasi);
        $NoPD = $PembuatanPD['KodeUrutPd'];
        $status_kode = $PembuatanPD['status_kode'];

        $NoRef = BuatNoRef($JobNo,$Alokasi);

        $data['from'] = $from;
        $data['LedgerNo'] = $LedgerNo;
        $data['NoPD'] = $NoPD;
        $data['status_kode'] = $status_kode;
        $data['NoRef'] = $NoRef;
        $data['JobNo'] = $JobNo;
        $data['Alokasi'] = $Alokasi;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;



        // print_rr($data);

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('MY_PD/PD_LAPANGAN/entry_PD', $data);
        $this->load->view('templates/footer');


    }



}

/* End of file PD_Lapangan.php */
/* Location: ./application/controllers/MY_PD/PD_Lapangan.php */