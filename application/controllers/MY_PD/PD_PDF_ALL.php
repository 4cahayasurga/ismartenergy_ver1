<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Mpdf\Mpdf;
class PD_PDF_ALL extends CI_Controller {

	var $Company;

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->helper('bantu');
		$this->Company = $this->config->item('Company');
	}


	function ExpPDF_PD($LedgerNo='', $JobNo='', $Alokasi='', $NoPD='', $dari_tgl='', $sampai_tgl='',$PD_DARI_LAP_OR_KEU=''){

		if ($LedgerNo=='') {
			die;
		}
		
		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        $JobNm = $this->dapat_JobNm($JobNo);
        $dataPD = $this->get_data_PdHdr($LedgerNo, $JobNo, $Alokasi, $NoPD, $dari_tgl, $sampai_tgl,$PD_DARI_LAP_OR_KEU);

        //ini cek no pj sebelumnya
        $NoPJSebelumnya = '';
        $Saldo = 0;
        $cek_NoPJSebelumnya_dan_Saldo = $this->cek_NoPJSebelumnya_dan_Saldo($NoPD,$LedgerNo);
        $NoPJSebelumnya = $cek_NoPJSebelumnya_dan_Saldo['NoPJSebelumnya'];
        $Saldo = $cek_NoPJSebelumnya_dan_Saldo['Saldo'];
        // end

        //dapat detail PD - PdDtl
        $array_PdDtl = array();
        $query_PdDtl = $this->db->query(" SELECT NoUrut, NoPD, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, (Vol * HrgSatuan) AS 'JumlahHarga' FROM PdDtl WHERE NoPD='$NoPD' ORDER BY NoUrut ASC ")->result_array();
        $SubTotal_PdDtl = 0;
        
        foreach($query_PdDtl as $PdDtl_data ){
            $SubTotal_PdDtl += $PdDtl_data['JumlahHarga'];
            $array_PdDtl[] = array(
                'NoUrut' => $PdDtl_data['NoUrut'],
                'NoPD' => $PdDtl_data['NoPD'],
                'KdRAP' => $PdDtl_data['KdRAP'],
                'Uraian' => $PdDtl_data['Uraian'],
                'Vol' => $PdDtl_data['Vol'],
                'Uom' => $PdDtl_data['Uom'],
                'HrgSatuan' => $PdDtl_data['HrgSatuan'],
                'UserEntry' => $PdDtl_data['UserEntry'],
                'TimeEntry' => $PdDtl_data['TimeEntry'],
                'JumlahHarga' => $PdDtl_data['JumlahHarga'],
            );
        }

        // hitung semua saldo utk PD PdDtl
        $TotalPermintaan = 0;
        $TotalPermintaan = $SubTotal_PdDtl - $Saldo;

        $data['NoPJSebelumnya'] = $NoPJSebelumnya;
        $data['Saldo'] = $Saldo;
        $data['SubTotal_PdDtl'] = $SubTotal_PdDtl;
        $data['TotalPermintaan'] = $TotalPermintaan;

        $data['array_PdDtl'] = $array_PdDtl;
        //end -----------------------------------------------------------------


        //dapat detail PJ - PdDtl
        $array_PdDtl_PJ = array();
        $query_PdDtl_PJ = $this->db->query(" SELECT NoPJ, NoUrut, NoPD, KdRAP, PjUraian, PjVol, Uom, PjHrgSatuan, PjUserEntry, PjTimeEntry, (PjVol * PjHrgSatuan) AS 'PjJumlahHarga' FROM PdDtl WHERE NoPD='$NoPD' ORDER BY NoUrut ASC ")->result_array();
        $SubTotal_PdDtl_PJ = 0;
        
        foreach($query_PdDtl_PJ as $PdDtl_data_PJ ){
            $SubTotal_PdDtl_PJ += $PdDtl_data_PJ['PjJumlahHarga'];
            $array_PdDtl_PJ[] = array(
                'NoUrut' 		=> $PdDtl_data_PJ['NoUrut'],
                'NoPD' 			=> $PdDtl_data_PJ['NoPD'],
                'NoPJ' 			=> $PdDtl_data_PJ['NoPJ'],
                'KdRAP' 		=> $PdDtl_data_PJ['KdRAP'],
                'PjUraian' 		=> $PdDtl_data_PJ['PjUraian'],
                'PjVol' 		=> $PdDtl_data_PJ['PjVol'],
                'Uom' 			=> $PdDtl_data_PJ['Uom'],
                'PjHrgSatuan' 	=> $PdDtl_data_PJ['PjHrgSatuan'],
                'PjUserEntry' 	=> $PdDtl_data_PJ['PjUserEntry'],
                'PjTimeEntry' 	=> $PdDtl_data_PJ['PjTimeEntry'],
                'PjJumlahHarga' => $PdDtl_data_PJ['PjJumlahHarga'],
            );
        }

        $data['NoPJ'] = ($dataPD['NoPJ'] == '' ||  $dataPD == null ) ? ''  : $dataPD['NoPJ'] ;
        $data['SubTotal_PdDtl_PJ'] = $SubTotal_PdDtl_PJ;
        $data['DanaTersediaDiLapangan_PJ'] = $SubTotal_PdDtl;
        $data['Saldo_PJ'] = $SubTotal_PdDtl - $SubTotal_PdDtl_PJ;
        

        $data['array_PdDtl_PJ'] = $array_PdDtl_PJ;

        //cek pembayaran
        $tglBayarPJ = '';
        $CaraBayar = '';

        $query_bayar = $this->db->query("
            SELECT TOP 1 *
            FROM 
            BLE WHERE NoPD='$NoPD'  OR 
            NoPD='$dataPD[NoRef]' ORDER BY TimeEntry DESC");

        if ($query_bayar->num_rows() > 0 ) {
            $row_ble = $query_bayar->row();
            $tglBayarPJ = ($row_ble->TglBayar == NULL || $row_ble== '' ) ? '-' : $row_ble->TglBayar;


        }

        $data['tglBayarPJ']= $tglBayarPJ;

        $data['pj_bayar'] = $query_bayar->row_array();


        $data['dataPD'] = $dataPD;
        $data['LedgerNo'] = $LedgerNo;
        $data['JobNo'] = $JobNo;
        $data['JobNm'] = $JobNm;
        $data['Alokasi'] = $Alokasi;
        $data['NoPD'] = $NoPD;
        $data['dari_tgl'] = $dari_tgl;
        $data['sampai_tgl'] = $sampai_tgl;


        $data['Dari'] = NULL;
        $data['Dari'] = ($PD_DARI_LAP_OR_KEU == 'KEU') ? 'KEUANGAN' : 'LAPANGAN';


        if ($PD_DARI_LAP_OR_KEU!='KEU') {
            // echo $TotalPJ;
            $PanggilData = $this->db->query("SELECT 
                ISNULL(TotalPD,0) AS TotalPD, 
                ISNULL(TotalPJ,0) AS TotalPJ, 
                NoPJ, 
                ISNULL(Saldo,0) AS Saldo FROM PdHdr WHERE  NoPD='$NoPD' ")->row();
            
            // echo $PanggilData->TotalPJ.'<br>';
            // echo $PanggilData->TotalPD;
            // echo $PanggilData->TotalPD - $PanggilData->TotalPJ;

            if ($PanggilData->NoPJ!='' || $PanggilData->NoPJ!=NULL) {
                $data['SubTotal_PdDtl'] = $PanggilData->TotalPD;
                $data['Saldo_PJ'] = $PanggilData->Saldo;

                // echo$PanggilData->Saldo;
            }

            // die;

            // $data['SaldoPJ'] = $PanggilData->TotalPD - $PanggilData->TotalPJ;
            // $data['SubTotal_PdDtl'] = $PanggilData->TotalPD;
        

        //     $apa['TotalPD'] = $PanggilData->TotalPD;
        //     $apa['Saldo'] = $PanggilData->Saldo;
        //     $
        //     $apa['RemarkOverrideSaldo'] = $PanggilData->RemarkOverrideSaldo;
        //     $data['OverrideSaldo'] = $PanggilData->OverrideSaldo;

            // print_rr($apa);
        //     die;
        }
// print_rr($data);
// die;/
        // die;
        
        $this->load->view('MY_PD/PD_PDF_ALL_view/konten_pdf', $data);

    }

    private function get_data_PdDtl($NoPD){

      $array_PdDtl = array();
      $query_PdDtl = $this->db->query(" SELECT NoUrut, NoPD, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, (Vol * HrgSatuan) AS 'JumlahHarga' FROM PdDtl WHERE NoPD='$NoPD' ORDER BY NoUrut ASC ")->result_array();
      $SubTotal_PdDtl = 0;

      foreach($query_PdDtl as $PdDtl_data ){
        $SubTotal_PdDtl += $PdDtl_data['JumlahHarga'];
        $array_PdDtl[] = array(
            'NoUrut' => $PdDtl_data['NoUrut'],
            'NoPD' => $PdDtl_data['NoPD'],
            'KdRAP' => $PdDtl_data['KdRAP'],
            'Uraian' => $PdDtl_data['Uraian'],
            'Vol' => $PdDtl_data['Vol'],
            'Uom' => $PdDtl_data['Uom'],
            'HrgSatuan' => $PdDtl_data['HrgSatuan'],
            'UserEntry' => $PdDtl_data['UserEntry'],
            'TimeEntry' => $PdDtl_data['TimeEntry'],
            'JumlahHarga' => $PdDtl_data['JumlahHarga'],
        );
    }
}

private function cek_NoPJSebelumnya_dan_Saldo($NoPD, $LedgerNo){

  $NoPJSebelumnya = '';
  $Saldo = 0;

  $get_DataPD = $this->db->query("SELECT NoPD, TipeForm, TglPD, KSO, Alokasi, NoPJSebelumnya, NoPJ FROM PdHdr WHERE LedgerNo='$LedgerNo' AND NoPD='$NoPD'  ");

  if ($get_DataPD->num_rows() > 0 ) {

    $row_saya = $get_DataPD->row_array();

    $cek = $this->get_Saldo($NoPD, $row_saya['TipeForm'], $row_saya['TglPD'], $row_saya['KSO'], $row_saya['Alokasi'],$row_saya['NoPJSebelumnya'], $row_saya['NoPJ']);

    $NoPJSebelumnya = $cek['NoPJSebelumnya'];
    $Saldo= $cek['Saldo'];

}

return array('NoPJSebelumnya' => $NoPJSebelumnya, 'Saldo' => $Saldo);
}

private function get_data_PdHdr($LedgerNo='', $JobNo='', $Alokasi='', $NoPD='', $dari_tgl='', $sampai_tgl='',$PD_DARI_LAP_OR_KEU=''){

  $dari_tgl_validasi = date('Y-m-d',strtotime($dari_tgl));
  $sampai_tgl_validasi = date('Y-m-d',strtotime($sampai_tgl));

  $tambahan = ($dari_tgl !=0 && $sampai_tgl !=0 ) ? "AND TglPD >='$dari_tgl_validasi' AND TglPD <='$sampai_tgl_validasi'" :'';


  $validasi_print_dari_mana='';

  if ($PD_DARI_LAP_OR_KEU == 'KEU' ) {
    $validasi_print_dari_mana= "AND (NoRef IS NULL OR NoRef ='')";
}else{
    $validasi_print_dari_mana= "AND (NoRef IS NOT NULL OR NoRef <>'')";
}

        // return $validasi_print_dari_mana;

$query_teks = "SELECT * FROM PdHdr WHERE LedgerNo='$LedgerNo' AND JobNo='$JobNo' AND Alokasi='$Alokasi' ".$validasi_print_dari_mana."  ".$tambahan;

$query = $this->db->query($query_teks)->row_array();

return $query;

}

private function dapat_JobNm($JobNo){
  $query = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row_array();
  return $query['JobNm'];
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

}

/* End of file PD_print_all.php */
/* Location: ./application/controllers/MY_PD/PD_print_all.php */
