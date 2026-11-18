<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sumda extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sumda_model');
    }

    public function getTableSumda()
    {
        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            $alokasi_sumda = $this->input->post('alokasi_sumda');
            $versi_sumda = $this->input->post('versi_sumda');
            $Par = '1';

            $data['alokasi_sumda'] = $alokasi_sumda;
            $data['versi_sumda'] = $versi_sumda;
            $data['JobNo'] = $JobNo;

			$data['result'] = $this->db->query("RAPSumda_Load N'{$JobNo}', N'{$alokasi_sumda}', N'{$versi_sumda}', N'{$Par}'")->result();

            $this->load->view('sumda/table-sumda', $data);
        }
    }

    function getFormSumda()
    {
        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            $alokasi_sumda = $this->input->post('alokasi_sumda');
            $versi_sumda = $this->input->post('versi_sumda');

            $data['header'] = $this->Sumda_model->getHeader($JobNo, $alokasi_sumda, $versi_sumda)->result();
            $data['alokasi_sumda'] = $alokasi_sumda;
            $data['versi_sumda'] = $versi_sumda;
            $data['JobNo'] = $JobNo;
            // print_r($data);
            // exit;
            $this->load->view('sumda/form-tambah-sumda', $data);
        }
    }

    function getDetail()
    {
        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            $Alokasi = $this->input->post('Alokasi');
            $Versi = $this->input->post('Versi');
            $Header = $this->input->post('Header');

            $array = array(
                'JobNo' => $JobNo,
                'Alokasi' => $Alokasi,
                'Versi' => $Versi,
                'Tipe' => 'Detail',
                'Header' => $Header
            );

			$data = $this->Sumda_model->get_header($array)->result();
			echo json_encode($data);
        }
    }

    function getSumda()
    {
        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            $Alokasi = $this->input->post('Alokasi');
            $Versi = $this->input->post('Versi');
            $Header = $this->input->post('Header');

            $array = array(
                'JobNo' => $JobNo,
                'Alokasi' => $Alokasi,
                'Versi' => $Versi,
                'Tipe' => 'Sumda',
                'Header' => $Header
            );

			$data = $this->Sumda_model->get_sumda($array)->result();
			echo json_encode($data);
        }
    }

	function simpanSumda()
    {
        if ($this->input->post()) {
            $JobNo = $this->input->post('JobNo');
            // $Header = $this->input->post('Header');
            // $Detail = $this->input->post('Detail');
            $Alokasi = $this->input->post('Alokasi');
            $Versi = $this->input->post('Versi');
            $Tipe = $this->input->post('Tipe');
            $KdRAP = $this->input->post('KdRAP');
            $Uraian = $this->input->post('Uraian');
            $Vol = $this->input->post('Vol');
            $Uom = $this->input->post('Uom');
            $HrgSatuan = $this->input->post('HrgSatuan');
            $HrgRAB = $this->input->post('HrgRAB');

            $TimeEntry = date('Y-m-d H:i:s');
            $UserEntry = $this->session->userdata('MIS_LOGGED_NAME');

            $Header_asal =$this->input->post('Header');
		
            $data = array(
                'JobNo' => $JobNo,
                'KdRAP' => $KdRAP,
                'Versi' => $Versi,
                // 'NoUrut' => $NoUrut,
                'Uraian' => $Uraian,
                'Alokasi' => $Alokasi,
                'Tipe' => $Tipe,
                // 'Header' => $kodeHeader,
                'Uom' => $Uom,
                'Vol' => $Vol,
                'HrgSatuan' => HapusFormatUang($HrgSatuan),
                'HrgRAB' => HapusFormatUang($HrgRAB),
                'UserEntry' => $UserEntry,
                'TimeEntry' => $TimeEntry,
            );

			if ($Tipe == 'Header') {
                $data['Header'] = '0';
			}
			else{
				if ($this->input->post('Header')) {
					$data['Header'] = $this->input->post('Header');
				}
	
				if ($this->input->post('Detail')) {
					$data['Header'] = $this->input->post('Detail');
				}
	
				if ($this->input->post('Sumda')) {
					$data['Header'] = $this->input->post('Sumda');
				}	
			}

            // $apa = LEFT($data['Header'],1)
            // echo 

            // exit;

            // print_rr($data);
            // echo 'success';
            // exit;

            $simpan = $this->Sumda_model->simpanSumda($Header_asal,$data);
            if ($simpan === TRUE) {
                // print_rr($data);
                // echo $simpan;
                echo 'success';
                exit;
            }
            // echo $simpan;
            echo 'gagal';
        }
    }
}

/* End of file Sumda.php */
