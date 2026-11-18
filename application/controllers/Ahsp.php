<?php

use function PHPSTORM_META\type;
error_reporting(E_ALL);
defined('BASEPATH') or exit('No direct script access allowed');

class Ahsp extends CI_Controller{

     public function __construct(){
          parent::__construct();
          $this->load->model('Users');
          $this->Company = $this->config->item('Company');
          if (!$this->db->field_exists('PPN', 'Ahsp'))
          {
               $this->db->query("ALTER TABLE Ahsp ADD PPN char(1) NULL DEFAULT((0))");
               $this->db->query("ALTER TABLE Ahsp ADD PPN_Nominal money NULL DEFAULT((0))");
               $this->db->query("ALTER TABLE Ahsp ADD Total money NULL DEFAULT((0))");

          }
     }


     public function proses_ajax($KdHuba){
          $query = $this->db->query("SELECT * FROM Huba Where KdHuba='$KdHuba'")->row_object();
          echo json_encode($query);

     }

     function ahsp_awal($JobNo=null, $KdAhsp=null, $KdAlokasiHeader=null){

          $JobNo = $JobNo;
          $panggil_queryHeader = $this->db->query("SELECT * FROM Ahsp where JobNo='$JobNo' AND Tipe ='NamaPekerjaan' and Header='0' ORDER BY KdAhsp ASC  ");
          $Array_TipeHeader_Nol = array();
          $Nomor = 1;

          foreach ($panggil_queryHeader->result_array() as $rowHeader_Nol) {
               $JobNoHeader = $rowHeader_Nol['JobNo'];
               $KdAlokasiHeader = $rowHeader_Nol['KdAlokasiHeader'];

               $ArrayDetail = array();
               $QueryDetail = $this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNoHeader' AND KdAHSP LIKE '%$KdAlokasiHeader%' AND tipe !='NamaPekerjaan' ORDER BY KdAHSP ASC ");


               if ($QueryDetail->num_rows() > 0) {

                    $NomorUrut = 1;

                    foreach($QueryDetail->result_array() as $rowDetail):

                         $ArrayDetail[] = (object) array(
                              
                              'NomorUrut' => $NomorUrut++,
                              'JobNo' => $rowDetail['JobNo'],
                              'tipe' => $rowDetail['tipe'],
                              'Header' => $rowDetail['Header'],
                              'KdAlokasiHeader' => $rowDetail['KdAlokasiHeader'],
                              'NoUrut' => $rowDetail['NoUrut'],
                              'KdAHSP' => $rowDetail['KdAHSP'],
                              'Analisa' => $rowDetail['Analisa'],
                              'NamaPekerjaan' => $rowDetail['NamaPekerjaan'],
                              'Uom' => $rowDetail['Uom'],
                              'LedgerNo' => $rowDetail['LedgerNo'],

                              'Vol' => ($rowDetail['tipe'] == 'Header') ? 1 : $rowDetail['Vol'],
                              'HrgSatuan' => ($rowDetail['tipe'] == 'Header') ? 0 : $rowDetail['HrgSatuan'],
                              'Jumlah' => ($rowDetail['tipe'] == 'Header') ? 0 : $rowDetail['Jumlah'],
                              'PPN' => $rowDetail['PPN'],
                              'PPN_Nominal' => ($rowDetail['tipe'] == 'Header') ? 0 : $rowDetail['PPN_Nominal'],
                              'Total' => ($rowDetail['tipe'] == 'Header') ? 0 : $rowDetail['Total'],

                         );
                    endforeach;


               }




               $Array_TipeHeader_Nol[] = array(
                    'Nomor' => $Nomor++,
                    'LedgerNo' => $rowHeader_Nol['LedgerNo'],
                    'JobNo' => $rowHeader_Nol['JobNo'],
                    'tipe' => $rowHeader_Nol['tipe'],
                    'Header' => $rowHeader_Nol['Header'],
                    'KdAHSP' => $rowHeader_Nol['KdAHSP'],
                    'KdAlokasiHeader' => $rowHeader_Nol['KdAlokasiHeader'],
                    'NoUrut' => $rowHeader_Nol['NoUrut'],
                    'Analisa' => $rowHeader_Nol['Analisa'],
                    'NamaPekerjaan' => $rowHeader_Nol['NamaPekerjaan'],
                    'Uom' => $rowHeader_Nol['Uom'],
                    'JumlahDetail' => count($ArrayDetail),
                    'ArrayDetail' => $ArrayDetail,
               );


          } 

          // print_rr($Array_TipeHeader_Nol);
          // exit;

          // echo $sumJumlah;
          // exit;


          $this->load->model('m_job');
          $datadipa = $this->m_job->dipa($JobNo);
          $data['dipa'] = $datadipa;

          $AHSP = $this->db->query("SELECT * FROM Ahsp where JobNo='$JobNo' AND Tipe ='NamaPekerjaan' and Header='0' ORDER BY KdAhsp ASC");
          $data['dataahsp'] = $AHSP->result();

          $header = $this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND Tipe !='Detail'");
          $data['header'] = $header->result();

          $detailahsp = $this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND KdAlokasiHeader='$KdAlokasiHeader' AND tipe !='NamaPekerjaan' ORDER BY KdAhsp ASC");
          $data['detailahsp'] = $detailahsp->result();

          $Huba = $this->db->query("SELECT * FROM Huba where JobNo='$JobNo' AND Tipe='Detail' Order by KdHuba ASC");
          $data['datahuba'] = $Huba->result();

          $dataPaket = $this->db->where('JobNo', $JobNo)->get('Job')->row_object();
          $data['datapaket'] = $dataPaket;


          $data['bodyclass'] = 'sidebar-collapse skin-blue';
          $data['judul']  = 'Analisa Harga Satuan Pekerjaan';

          $data['Array_TipeHeader_Nol'] = $Array_TipeHeader_Nol;
          // print_rr($data['Array_TipeHeader_Nol']);
          // exit;

          // $QueryHeaderNew=$this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND tipe='NamaPekerjaan' AND (Header=0 OR Header='0') ");
          // print_rr($QueryHeaderNew->result());
          // exit;


          $data['dataAhsp_New'] = $this->db->where('JobNo',$JobNo)->get('Ahsp')->result_array();



          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar');
          $this->load->view('content/Ahsp/Ahsp', $data);
          // $this->load->view('content/Ahsp/Ahsp-bc1-02-agustus-2023', $data);
          $this->load->view('templates/footer');
     }


     private function dapat_NoUrut($JobNo, $AlokasiKdHeader){
          $query = $this->db->query("SELECT TOP 1 NoUrut From Ahsp WHERE JobNo='$JobNo' AND KdAlokasiHeader='$AlokasiKdHeader' ORDER BY NoUrut DESC")->row_array();
          //nomor urut tambah satu
          $tambah_satu = $query['NoUrut'] + 1;
          return $tambah_satu;
     }

     function AddAhsp(){
          $JobNo = ($this->input->post('JobNo',TRUE)) ? $this->input->post('JobNo',TRUE) : NULL;
          if ($this->input->post('type') == '' ) {
               is_pesan('danger','terjadi kesalahan');
               redirect('Ahsp/ahsp_awal/'.$JobNo);
          }

          // print_rr($_POST);

          $JobNo = ($this->input->post('JobNo',TRUE)) ? $this->input->post('JobNo',TRUE) : NULL;
          $tipe = ($this->input->post('type',TRUE)) ? $this->input->post('type',TRUE) : NULL;
          $Header = $this->input->post('Header',TRUE);
          $KdAlokasiHeader = $this->input->post('KdAlokasiHeader',TRUE);
          $KdAHSP = ($this->input->post('KdAHSP',TRUE)) ? $this->input->post('KdAHSP',TRUE) : NULL;
          $NoUrut = NULL;
          $Analisa = ($this->input->post('Analisa',TRUE)) ? $this->input->post('Analisa',TRUE) : NULL;
          $Uraian = ($this->input->post('Uraian',TRUE)) ? $this->input->post('Uraian',TRUE) : NULL;
          $Uraian1 = ($this->input->post('Uraian1',TRUE)) ? $this->input->post('Uraian1',TRUE) : NULL;
          $Uom = ($this->input->post('Satuan',TRUE)) ? $this->input->post('Satuan',TRUE) : NULL;
          $Vol = ($this->input->post('Vol',TRUE) || $this->input->post('Vol',TRUE) !='') ? $this->input->post('Vol',TRUE) : NULL;
          $HrgSatuan = ($this->input->post('harga',TRUE)) ? $this->input->post('harga',TRUE) : 0;

          $Jumlah = ($this->input->post('Jumlah',TRUE)) ? $this->input->post('Jumlah',TRUE) : 0;
          $PPN = ($this->input->post('PPN',TRUE)) ? 1 : 0;

          $PPN_Nominal = ($this->input->post('PPN_Nominal',TRUE)) ? $this->input->post('PPN_Nominal',TRUE) : 0;
          $Total = ($this->input->post('Total',TRUE)) ? $this->input->post('Total',TRUE) : 0;

          $UserEntry          = $this->session->userdata('MIS_LOGGED_NAME');
          $TimeEntry          = date('Y-m-d H:i:s');

          $NamaPekerjaan = NULL;
          $NamaPekerjaan = ($tipe == 'NamaPekerjaan' || $tipe =='Header') ? $Uraian : $Uraian1;

          $searchForValue = ',';

          if( strpos($HrgSatuan, $searchForValue) !== false ) {
               $HrgSatuan = str_replace(",", "", $HrgSatuan);
          }

          if (is_numeric($HrgSatuan) !=1) {
               $HrgSatuan = 0;
          }

          if( strpos($Vol, $searchForValue) !== false ) {
               $Vol = str_replace(",", "", $Vol);
          }

          if (is_numeric($Vol) !=1) {
               $Vol = 0;
          }

          if( strpos($Jumlah, $searchForValue) !== false ) {
               $Jumlah = str_replace(",", "", $Jumlah);
          }

          if (is_numeric($Jumlah) !=1) {
               $Jumlah = 0;
          }

          if( strpos($PPN_Nominal, $searchForValue) !== false ) {
               $PPN_Nominal = str_replace(",", "", $PPN_Nominal);
          }

          if (is_numeric($PPN_Nominal) !=1) {
               $PPN_Nominal = 0;
          }

          if( strpos($Total, $searchForValue) !== false ) {
               $Total = str_replace(",", "", $Total);
          }

          if (is_numeric($Total) !=1) {
               $Total = 0;
          }

          $NoUrut = 0;

          if ($tipe == 'NamaPekerjaan' ) {
               $txtQuery = $this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND tipe='NamaPekerjaan' AND Header='0'   ");

               if ($txtQuery->num_rows() > 0) {
                    $row = $txtQuery->row_array();
                    $NoUrut = (int) $row['NoUrut'] + 1;
               }else{
                    $NoUrut = 1;
               }
          }


          // === PENGECEKAN UNTUK TIPE INPUT DATA 'HEADER'
          if ($tipe == 'Header') {

               $TambahanQuery = "";
               $txtQueryHeader = "SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND tipe='Header' AND Header='$Header' AND KdAlokasiHeader='$KdAlokasiHeader'   ";

               // == Cek KdAHSP == 
               $cekAHSP = $txtQueryHeader." AND KdAHSP='".$KdAHSP."'  ";
               $queryCek = $this->db->query($cekAHSP);
               if ($queryCek->num_rows() > 0) {
                    is_pesan('info'," Kd AHSP '".$KdAHSP."' sudah di-gunakan",'16px');
                    redirect('AHSP/ahsp_awal/'.$JobNo);
                    die;
               }

               $txtQueryHeader = $this->db->query($txtQueryHeader);
               if ($txtQueryHeader->num_rows() > 0) {
                    $row = $txtQueryHeader->row_array();
                    $NoUrut = (int) $row['NoUrut'] + 1;
               }else{
                    $NoUrut = 1;
               }
          }
          // === END PENGECEKAN UNTUK TIPE INPUT DATA 'HEADER'


          // === PENGECEKAN UNTUK TIPE INPUT DATA 'DETAIL'
          if ($tipe == 'Detail') {

               $TambahanQuery = "";
               $txtQuery_Detail = "SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND tipe='Detail' AND Header='$Header' AND KdAlokasiHeader='$KdAlokasiHeader'   ";

               // == Cek KdAHSP == 
               $cekAHSP = $txtQuery_Detail." AND KdAHSP='".$KdAHSP."'  ";
               $queryCek = $this->db->query($cekAHSP);
               if ($queryCek->num_rows() > 0) {
                    is_pesan('info'," Kd AHSP '".$KdAHSP."' sudah di-gunakan",'16px');
                    redirect('AHSP/ahsp_awal/'.$JobNo);
                    die;
               }

               $txtQuery_Detail = $this->db->query($txtQuery_Detail);
               if ($txtQuery_Detail->num_rows() > 0) {
                    $row = $txtQuery_Detail->row_array();
                    $NoUrut = (int) $row['NoUrut'] + 1;
               }else{
                    $NoUrut = 1;
               }

               $Analisa = NULL;

          }
          // === END PENGECEKAN UNTUK TIPE INPUT DATA 'DETAIL'
          

          $data = array(
               'JobNo' => $JobNo,
               'tipe' => $tipe,
               'Header' => $Header,
               'KdAlokasiHeader' => $KdAlokasiHeader,
               'NoUrut' => $NoUrut,
               'KdAHSP' => $KdAHSP,
               'Analisa' => $Analisa,
               'NamaPekerjaan' => $NamaPekerjaan,
               'Uom' => $Uom,
               'Vol' => $Vol,
               'HrgSatuan' => $HrgSatuan,
               'Jumlah' => $Jumlah,
               'UserEntry' => $UserEntry,
               'TimeEntry' => $TimeEntry,
               // 'LedgerNo' => $LedgerNo,
               'PPN' => $PPN,
               'PPN_Nominal' => $PPN_Nominal,
               'Total' => $Total,
          );



          // print_rr($_POST);
          // echo '<hr>';
          // print_rr($data);
          // exit;  

          $this->db->trans_begin();

          $this->db->insert('Ahsp',$data);

          if ($this->db->trans_status() === FALSE) {
               $this->db->trans_rollback();
               is_pesan('error','gagal menginput data');
               redirect('AHSP/ahsp_awal/'.$JobNo);
          }else{
               $this->db->trans_commit();
               is_pesan('success','berhasil');
               redirect('AHSP/ahsp_awal/'.$JobNo);

          }

     }

     function simpanAhsp(){
          $JobNo              = $this->input->post('JobNo');
          $Type               = $this->input->post('type');
          $Header             = $this->input->post('Header');
          $AlokasiKdHeader    = $this->input->post('KdAlokasiHeader');
          $KdAHSP             = $this->input->post('KdAHSP');
          $Analisa            = $this->input->post('Analisa');


          // == CEK Data Sudah ada di table Ahsp ==
          // $Cek = $this->db->query("S")
          // if ($Type == 'NamaPekerjaan') {
          //      $cek = $this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND tipe='NamaPekerjaan' AND KdAlokasiHeader='$AlokasiKdHeader'  ")->num_rows();
          //      if ($cek > 0) {
          //           is_pesan('info',"Kode AHSP '".$AlokasiKdHeader."' sudah terpakai ");
          //           redirect('Ahsp/ahsp_awal/' . ($JobNo));
          //      }
          // }

          // if ($Type == 'Header') {
          //      $cek = $this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND tipe='Header' AND Header='$AlokasiKdHeader'  ")->num_rows();
          //      if ($cek > 0) {
          //           is_pesan('info',"Kode AHSP '".$AlokasiKdHeader."' sudah terpakai ");
          //           redirect('Ahsp/ahsp_awal/' . ($JobNo));
          //      }
          // }

          // echo $AlokasiKdHeader;

          // exit;

          if($this->input->post('type')!='Detail'){
               $Uraian = $this->input->post('Uraian');
          }else{
               $Uraian = $this->input->post('Uraian1');
          }

          $Satuan             = $this->input->post('Satuan');
          $Vol                = $this->input->post('Vol');
          $harga              = $this->input->post('harga');
          $Jumlah             = $this->input->post('Jumlah');
          $NoUrut             = $this->dapat_NoUrut($JobNo, $AlokasiKdHeader);
          $UserEntry          = $this->session->userdata('MIS_LOGGED_NAME');
          $TimeEntry          = date('Y-m-d H:i:s');

          $PPN = ($this->input->post('PPN')) ? 1 : 0;

          $PPN_Nominal  = $this->input->post('PPN_Nominal');
          $Total   = $this->input->post('Total');

          $searchForValue = ',';

          if( strpos($harga, $searchForValue) !== false ) {
               $harga = str_replace(",", "", $harga);
          }

          if (is_numeric($harga) !=1) {
               $harga = 0;
          }

          if( strpos($Vol, $searchForValue) !== false ) {
               $Vol = str_replace(",", "", $Vol);
          }

          if (is_numeric($Vol) !=1) {
               $Vol = 0;
          }

          $data = array(
               'JobNo'         =>$JobNo,
               'Tipe'          =>$Type,
               'Header'        =>$Header,
               'KdAlokasiHeader' => $AlokasiKdHeader,
               'KdAHSP'        => $KdAHSP,
               'Analisa'       => $Analisa,
               'NamaPekerjaan' => $Uraian,
               'Uom'           => $Satuan,
               'Vol'           => $Vol,
               'HrgSatuan'     => $harga,
               'Jumlah'        => ($Vol * $harga),
               'NoUrut'        => $NoUrut,
               'UserEntry'     => $UserEntry,
               'TimeEntry'     => $TimeEntry,
               'PPN' => $PPN,
               'PPN_Nominal' => $PPN_Nominal,
               'Total' => $Total,
          );
          // print_rr($_POST);
          // print_rr($data);
          // exit;

          $this->db->trans_begin();

          // $this->db->query("INSERT INTO Ahsp (JobNo, Tipe, Header, KdAlokasiHeader, KdAHSP, Analisa, NamaPekerjaan, Uom, Vol, HrgSatuan, Tipe, Tipe, Tipe, Tipe, Tipe, Tipe, Tipe, Tipe, Tipe, Tipe) ");

          $this->db->insert('Ahsp', $data);

          if ($this->db->trans_status() === FALSE) {
               $this->db->trans_rollback();
               is_pesan('danger','Terjadi Kesalahan, ulangi proses');
          }else{
               is_pesan('success','Saved!');
               $this->db->trans_commit();
          }

          
          redirect('AHS/ahsp_awal/' . ($JobNo));

     }

     function ahsp_awal_aa($JobNo=null, $KdAhsp=null, $KdAlokasiHeader=null){

          $JobNo = $JobNo;
          $panggil_queryHeader = $this->db->query("SELECT * FROM Ahsp where JobNo='$JobNo' AND Tipe ='NamaPekerjaan' and Header='0' ORDER BY KdAhsp ASC  ");
          $Array_TipeHeader_Nol = array();
          $Nomor = 1;

          foreach ($panggil_queryHeader->result_array() as $rowHeader_Nol) {
               $JobNoHeader = $rowHeader_Nol['JobNo'];
               $KdAlokasiHeader = $rowHeader_Nol['KdAlokasiHeader'];

               $ArrayDetail = array();
               $QueryDetail = $this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNoHeader' AND KdAHSP LIKE '%$KdAlokasiHeader%' AND tipe !='NamaPekerjaan' ORDER BY KdAHSP ASC ");
               $sumVol = 0;
               $sumHargaSatuan = 0;
               $sumTotal_Hrg_Vol = 0;
               $sumTotal_PPN_Nominal = 0;
               $sumTotal_Include_PPN = 0;
               if ($QueryDetail->num_rows() > 0) {



                    $BiayaUmum_10Persen_HrgSatuan = 0;
                    $BiayaUmum_10Persen_Jumlah = 0;
                    $BiayaUmum_10Persen_PPN_Nominal = 0;
                    $BiayaUmum_10Persen_Include_PPN = 0;


                    $GrandTotal_BiayaUmum_10Persen_HrgSatuan = 0;
                    $GrandTotal_BiayaUmum_10Persen_Jumlah = 0;
                    $GrandTotal_BiayaUmum_10Persen_PPN_Nominal = 0;
                    $GrandTotal_BiayaUmum_10Persen_Include_PPN = 0;

                    $NomorUrut = 1;
                    foreach($QueryDetail->result_array() as $rowDetail):

                         // $sumVol += $rowDetail['Vol'];
                         // $sumHargaSatuan += $rowDetail['HrgSatuan'];
                         // $sumTotal_Hrg_Vol += ($rowDetail['Vol'] * $rowDetail['HrgSatuan']);

                         $Vol = 0;
                         $HrgSatuan = 0;
                         $TotalVolDikaliHrgSatuan = 0;
                         $PPN_Nominal = 0;
                         $Total_Include_PPN = 0;

                         // ==DITAMBAHKAN 21 AGUSTUS 2023
                         $Total_Include_PPN_Terbaru = 0;
                         // ==END DITAMBAHKAN 21 AGUSTUS 2023 

                         $Vol = (double)$rowDetail['Vol'];
                         $HrgSatuan = (double) $rowDetail['HrgSatuan'];

                         $TotalVolDikaliHrgSatuan = $Vol * $HrgSatuan;
                         $PPN_Nominal = (double) $rowDetail['PPN_Nominal'];
                         $Total_Include_PPN = (double) $TotalVolDikaliHrgSatuan + $PPN_Nominal;

                         $sumVol += $Vol;
                         $sumHargaSatuan += $HrgSatuan;
                         $sumTotal_Hrg_Vol += $TotalVolDikaliHrgSatuan;
                         $sumTotal_PPN_Nominal += $PPN_Nominal;
                         $sumTotal_Include_PPN += $PPN_Nominal;


                         // ==DITAMBAHKAN 21 AGUSTUS 2023
                         // $Total_Include_PPN_Terbaru = ($)
                         // ==END DITAMBAHKAN 21 AGUSTUS 2023 


                         $ArrayDetail[] = array(
                              'NomorUrut' => $NomorUrut++,
                              'JobNo' => $rowDetail['JobNo'],
                              'tipe' => $rowDetail['tipe'],
                              'Header' => $rowDetail['Header'],
                              'KdAlokasiHeader' => $rowDetail['KdAlokasiHeader'],
                              'NoUrut' => $rowDetail['NoUrut'],
                              'KdAHSP' => $rowDetail['KdAHSP'],
                              'Analisa' => $rowDetail['Analisa'],
                              'NamaPekerjaan' => $rowDetail['NamaPekerjaan'],
                              'Uom' => $rowDetail['Uom'],
                              'Vol' => $Vol,
                              'HrgSatuan' => $HrgSatuan,
                              'TotalVolDikaliHrgSatuan' => $TotalVolDikaliHrgSatuan,
                              'LedgerNo' => $rowDetail['LedgerNo'],
                              'PPN' => $rowDetail['PPN'],
                              'PPN_Nominal' => (double) $PPN_Nominal,
                              'Total' => $rowDetail['Total'],
                              'Total_Include_PPN' => $Total_Include_PPN,

                         );
                    endforeach;
               }


               $BiayaUmum_10Persen_HrgSatuan = ($sumHargaSatuan * 10) / 100;
               $BiayaUmum_10Persen_Jumlah = ($sumTotal_Hrg_Vol * 10) / 100;
               $BiayaUmum_10Persen_PPN_Nominal = ($sumTotal_PPN_Nominal * 10) / 100;
               $BiayaUmum_10Persen_Include_PPN = ($sumTotal_Include_PPN * 10) / 100;


               $GrandTotal_BiayaUmum_10Persen_HrgSatuan = ($sumHargaSatuan + $BiayaUmum_10Persen_HrgSatuan);
               $GrandTotal_BiayaUmum_10Persen_Jumlah = ($sumTotal_Hrg_Vol + $BiayaUmum_10Persen_Jumlah);
               $GrandTotal_BiayaUmum_10Persen_PPN_Nominal = ($sumTotal_PPN_Nominal + $BiayaUmum_10Persen_PPN_Nominal);
               $GrandTotal_BiayaUmum_10Persen_Include_PPN = ($sumTotal_Include_PPN + $BiayaUmum_10Persen_Include_PPN);

               $Array_TipeHeader_Nol[] = array(
                    'Nomor' => $Nomor++,
                    'LedgerNo' => $rowHeader_Nol['LedgerNo'],
                    'JobNo' => $rowHeader_Nol['JobNo'],
                    'tipe' => $rowHeader_Nol['tipe'],
                    'Header' => $rowHeader_Nol['Header'],
                    'KdAHSP' => $rowHeader_Nol['KdAHSP'],
                    'KdAlokasiHeader' => $rowHeader_Nol['KdAlokasiHeader'],
                    'NoUrut' => $rowHeader_Nol['NoUrut'],
                    // 'KdAHSP' => $rowHeader_Nol['KdAHSP'],
                    'Analisa' => $rowHeader_Nol['Analisa'],
                    'NamaPekerjaan' => $rowHeader_Nol['NamaPekerjaan'],
                    'Uom' => $rowHeader_Nol['Uom'],
                    // 'Vol' => $rowHeader_Nol['Vol'],
                    // 'HrgSatuan' => $rowHeader_Nol['HrgSatuan'],
                    // 'LedgerNo' => $rowHeader_Nol['LedgerNo'],
                    // 'PPN' => $rowHeader_Nol['PPN'],
                    // 'PPN_Nominal' => $rowHeader_Nol['PPN_Nominal'],
                    // 'Total' => $rowHeader_Nol['Total'],
                    'JumlahDetail' => count($ArrayDetail),
                    'ArrayDetail' => $ArrayDetail,
                    'sumVol' => $sumVol,
                    'sumHargaSatuan' => $sumHargaSatuan,
                    'sumTotal_Hrg_Vol' => $sumTotal_Hrg_Vol,
                    'sumTotal_PPN_Nominal' => $sumTotal_PPN_Nominal,
                    'sumTotal_Include_PPN' => $sumTotal_Include_PPN,


                    'BiayaUmum_10Persen_HrgSatuan' => $BiayaUmum_10Persen_HrgSatuan,
                    'BiayaUmum_10Persen_Jumlah' => $BiayaUmum_10Persen_Jumlah,
                    'BiayaUmum_10Persen_PPN_Nominal' => $BiayaUmum_10Persen_PPN_Nominal,
                    'BiayaUmum_10Persen_Include_PPN' => $BiayaUmum_10Persen_Include_PPN,

                    'GrandTotal_BiayaUmum_10Persen_HrgSatuan' => $GrandTotal_BiayaUmum_10Persen_HrgSatuan,
                    'GrandTotal_BiayaUmum_10Persen_Jumlah' => $GrandTotal_BiayaUmum_10Persen_Jumlah,
                    'GrandTotal_BiayaUmum_10Persen_PPN_Nominal' => $GrandTotal_BiayaUmum_10Persen_PPN_Nominal,
                    'GrandTotal_BiayaUmum_10Persen_Include_PPN' => $GrandTotal_BiayaUmum_10Persen_Include_PPN,

               );
          } 

          // print_rr($Array_TipeHeader_Nol);
          // exit;


          $this->load->model('m_job');
          $datadipa = $this->m_job->dipa($JobNo);
          $data['dipa'] = $datadipa;

          $AHSP = $this->db->query("SELECT * FROM Ahsp where JobNo='$JobNo' AND Tipe ='NamaPekerjaan' and Header='0' ORDER BY KdAhsp ASC");
          $data['dataahsp'] = $AHSP->result();

          $header = $this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND Tipe !='Detail'");
          $data['header'] = $header->result();

          $detailahsp = $this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND KdAlokasiHeader='$KdAlokasiHeader' AND tipe !='NamaPekerjaan' ORDER BY KdAhsp ASC");
          $data['detailahsp'] = $detailahsp->result();

          $Huba = $this->db->query("SELECT * FROM Huba where JobNo='$JobNo' AND Tipe='Detail' Order by KdHuba ASC");
          $data['datahuba'] = $Huba->result();

          $dataPaket = $this->db->where('JobNo', $JobNo)->get('Job')->row_object();
          $data['datapaket'] = $dataPaket;


          $data['bodyclass'] = 'sidebar-collapse skin-blue';
          $data['judul']  = 'Analisa Harga Satuan Pekerjaan';

          $data['Array_TipeHeader_Nol'] = $Array_TipeHeader_Nol;
          // print_rr($data['Array_TipeHeader_Nol']);
          // exit;

          // $QueryHeaderNew=$this->db->query("SELECT * FROM Ahsp WHERE JobNo='$JobNo' AND tipe='NamaPekerjaan' AND (Header=0 OR Header='0') ");
          // print_rr($QueryHeaderNew->result());
          // exit;


          $data['dataAhsp_New'] = $this->db->where('JobNo',$JobNo)->get('Ahsp')->result_array();



          $this->load->view('templates/header', $data);
          $this->load->view('templates/sidebar');
          $this->load->view('content/Ahsp/Ahsp-bc1-21-agustus-2023', $data);
          // $this->load->view('content/Ahsp/Ahsp-bc1-02-agustus-2023', $data);
          $this->load->view('templates/footer');
     }





}
