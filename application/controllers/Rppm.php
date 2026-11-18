<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rppm extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_rppm');
  }

  public function index($JobNo='')
  {
		$TblProgress = $this->M_Dppd->tblProgress($JobNo);
		$data['Progress'] = $TblProgress;

		$data['judul'] = 'RPPM Dan Progress Fisik ';
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("content/JobEntry/rppm", $data);
		$this->load->view('templates/footer');
  }

  function get_json_progress_fisik($JobNo)
  {

    header('Content-Type: application/json');
    echo $this->M_rppm->get_json_progress_fisik($JobNo);
  }

  public function GetFormProgressFisik()
  {
    if (isset($_POST)) {
      $type = $this->input->post('type', TRUE);
      $JobNo = $this->input->post('JobNo', TRUE);
      $JobNm = $this->input->post('JobNm', TRUE);
      if ($type == 'Tambah') {
        $data['JobNo'] = $JobNo;
        $data['JobNm'] = $JobNm;

        $getData = $this->M_rppm->GetDataProgressFisik($JobNo);
        $data['RencanaK'] = '';
        $data['RealisasiK'] = '';
        $data['RealisasiKeuK'] = '';
        if ($getData->num_rows() > 0) {
          $row = $getData->row();
          $data['RencanaK'] = $row->RencanaK;
          $data['RealisasiK'] = $row->RealisasiK;;
          $data['RealisasiKeuK'] = $row->RealisasiKeuK;
        }

        $this->load->view('form/rppm/form_tambah_progress_fisik', $data);
      }

      if ($type == 'Edit') {
        $LedgerNo = $this->input->post('LedgerNo', TRUE);
        $data['JobNo'] = $JobNo;
        $data['JobNm'] = $JobNm;
        $data['data'] = $this->M_rppm->GetDataEmon($LedgerNo)->row();
				// print_rr($data);
				// exit();
        $this->load->view('form/rppm/form_edit', $data);
      }

		
    }
  }

	function editEmon()
	{


		
			$LedgerNo 	         = (isset($_POST['LedgerNo'])) ? $this->input->post('LedgerNo', TRUE) : '';
    //   echo $LedgerNo;
    // exit;
			$JobNo               = (isset($_POST['JobNo'])) ? $this->input->post('JobNo', TRUE) : '';
			$Tahun               = (isset($_POST['Tahun'])) ? $this->input->post('Tahun', TRUE) : '';
			$Bulan               = (isset($_POST['Bulan'])) ? $this->input->post('Bulan', TRUE) : '';
			$RencanaFisTB               = (isset($_POST['RencanaFisTB'])) ? $this->input->post('RencanaFisTB', TRUE) : '';
			$RealisasiFisTB               = (isset($_POST['RealisasiFisTB'])) ? $this->input->post('RealisasiFisTB', TRUE) : '';
			$RealisasiKeuTB               = (isset($_POST['RealisasiKeuTB'])) ? $this->input->post('RealisasiKeuTB', TRUE) : '';

			$RencanaFiskom               = (isset($_POST['RencanaFiskom'])) ? $this->input->post('RencanaFiskom', TRUE) : '';
			$RealisasiFiskom               = (isset($_POST['RealisasiFiskom'])) ? $this->input->post('RealisasiFiskom', TRUE) : '';
			$RealisasiKeuKom               = (isset($_POST['RealisasiKeuKom'])) ? $this->input->post('RealisasiKeuKom', TRUE) : '';

			$UserEntry              = $this->session->userdata('MIS_LOGGED_NAME');
			$TimeEntry              = date('Y-m-d h:i:s');

			$data = array(
				'JobNo'                     => $JobNo,
				'Tahun'                => $Tahun,
				'Bulan'                 => $Bulan,
				'RencanaTB'                     => $RencanaFisTB,
				'RealisasiTB'                 => $RealisasiFisTB,
				'RealisasiKeuTB'                 => $RealisasiKeuTB,
				'RencanaK'                 => $RencanaFiskom,
				'RealisasiK'                 => $RealisasiFiskom,
				'RealisasiKeuK'                       => $RealisasiKeuKom,
				'UserEntry'                 => $UserEntry,
				'TimeEntry'                 => $TimeEntry,
			);

			$where = array(
				'LedgerNo'  => $LedgerNo,
			);

      $update = $this->aksi_edit_progress_emon($data,$where);
      // $update = $this->db->set($data)->where('LedgerNo',$LedgerNo)->update('Progress');
      // echo $update;

      if ($update == TRUE) {
         $array = array(
          "status" => 'success',
          "JobNo" => $JobNo
        );
        echo json_encode($array);
        exit;
      }
      $array = array(
        "status" => 'fail',
        "JobNo" => $JobNo
      );
      echo json_encode($array);
      exit;

      // print_rr($_POST);
      // echo "<hr>";
      // print_rr($data);
      // exit;
			
	}

  private function aksi_edit_progress_emon($data,$where){
    $this->db->trans_begin();

    $this->db->set($data)->where($where)->update('Progress');

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return false;
    } else {
      $this->db->trans_commit();
      return true;
    }
  }


  function SimpanProgressFisik()
  {
    if (isset($_POST)) {
      $JobNo               = (isset($_POST['JobNo'])) ? $this->input->post('JobNo', TRUE) : '';
      $Tahun               = (isset($_POST['Tahun'])) ? $this->input->post('Tahun', TRUE) : '';
      $Bulan               = (isset($_POST['Bulan'])) ? $this->input->post('Bulan', TRUE) : '';
      $RencanaFisTB        = (isset($_POST['RencanaFisTB'])) ? $this->input->post('RencanaFisTB', TRUE) : '';
      $RealisasiFisTB      = (isset($_POST['RealisasiFisTB'])) ? $this->input->post('RealisasiFisTB', TRUE) : '';
      $RealisasiKeuTB      = (isset($_POST['RealisasiKeuTB'])) ? $this->input->post('RealisasiKeuTB', TRUE) : '';

      $RencanaFiskom       = (isset($_POST['RencanaFiskom'])) ? $this->input->post('RencanaFiskom', TRUE) : '';
      $RealisasiFiskom     = (isset($_POST['RealisasiFiskom'])) ? $this->input->post('RealisasiFiskom', TRUE) : '';
      $RealisasiKeuKom     = (isset($_POST['RealisasiKeuKom'])) ? $this->input->post('RealisasiKeuKom', TRUE) : '';

      $UserEntry              = $this->session->userdata('MIS_LOGGED_NAME');
      $TimeEntry              = date('Y-m-d h:i:s');

      $data = array(
        'JobNo'                     => $JobNo,
        'Tahun'                => $Tahun,
        'Bulan'                 => $Bulan,
        'RencanaTB'                     => $RencanaFisTB,
        'RealisasiTB'                 => $RealisasiFisTB,
        'RealisasiKeuTB'                 => $RealisasiKeuTB,
        'RencanaK'                 => $RencanaFiskom,
        'RealisasiK'                 => $RealisasiFiskom,
        'RealisasiKeuK'                       => $RealisasiKeuKom,
        'UserEntry'                 => $UserEntry,
        'TimeEntry'                 => $TimeEntry,
      );

      // print_rr($_POST);
      // print_rr($data);
      // exit;

      $tambah = $this->M_rppm->TambahProgressFisik($data);
      if ($tambah === TRUE) {
        $array = array(
          "status" => 'success',
          "JobNo" => $JobNo
        );
        echo json_encode($array);
        exit;
      }
      $array = array(
        "status" => 'fail',
        "JobNo" => $JobNo
      );
      echo json_encode($array);
      exit;
    }
  }

	function Hapus($LedgerNo = '')
	{
		if ($LedgerNo != '') {
			$hapus = $this->M_rppm->Hapus($LedgerNo);
			if ($hapus === TRUE) {
				echo 'success';
				exit;
			}

			echo 'fail';
			exit;
		}
	}
}


/* End of file Rppm.php */
/* Location: ./application/controllers/Rppm.php */
