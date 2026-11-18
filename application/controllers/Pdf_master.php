<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Mpdf\Mpdf;
class Pdf_master extends CI_Controller {

	public function PD_Keu($LedgerNo='')
	{
		if ($LedgerNo=='') {
			redirect('Welcome/index');
			exit;
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$get_DataPD = $this->dapatDataPD($LedgerNo);
		$data['dataPD'] = $get_DataPD;
		$data['LedgerNo'] = $LedgerNo;

		$data['VendorId'] = $this->get_Vendor($get_DataPD['NoKO']);

		$this->load->view('Pdf_master_view/PD_keuangan_view/pd_keuangan_content',$data);

	}

	private function dapatDataPD($LedgerNo)
	{

		$query = $this->db->query("SELECT a.*, b.JobNm, c.Alokasi, c.Keterangan AS 'KeteranganAlokasi', (SELECT Keterangan FROM TipeForm  WHERE TipeForm=a.TipeForm AND Alokasi=a.Alokasi ) AS 'KeteraganTipeForm'
			FROM PdHdr a
			LEFT JOIN  Job b ON a.JobNo=b.JobNo
			LEFT JOIN  Alokasi c ON a.Alokasi=c.Alokasi

			WHERE a.LedgerNo='$LedgerNo' ")->row_array();
		
		return $query;
	}

	private function get_Vendor($NoKO){
		$query = $this->db->query("SELECT B.* FROM KoHdr A LEFT JOIN Vendor B ON A.VendorId=B.VendorId WHERE NoKO='$NoKO'")->row_array();
		return $query['VendorId'];
	}


	function RkpHutangKO($JobNo,$DrTgl,$SpTgl){
		if ($JobNo == '') {
			redirect('Welcome','refresh');
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
    	$tglSkrg = date('d-M-Y');
    	$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$data['JobNo'] = $JobNo;

		$data['JobNm'] = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;

		$data['DrTgl'] = $DrTgl;
		$data['SpTgl'] = $SpTgl;

		$query_coba = $this->db->query("
			BEGIN

				DECLARE @JobNo nvarchar(10)
				DECLARE @DrTgl date
				DECLARE @SpTgl date


				SET @JobNo = '$JobNo'
				SET @DrTgl = '$DrTgl'
				SET @SpTgl = '$SpTgl'

				SELECT 
				a.JobNo, 
				a.JobNm,  
				a.TglKontrak,
				a.Netto, 
				a.PersenKSO,
				@DrTgl AS DrTgl,
				@SpTgl AS SpTgl,
				a.Netto * IIF(a.PersenKSO=0,100,a.PersenKSO) / 100 AS 'NettKontrak',
				IIF(@DrTgl=NULL,'1900-01-01',@DrTgl) AS 'TglPHO',
				(SELECT ISNULL(SUM(Vol*HrgSatuan),0) FROM RAP WHERE JobNo=a.JobNo And Alokasi='B' AND Tipe='Detail') AS 'RAP'
				FROM Job a WHERE JobNo IN (@JobNo) ORDER BY JobNo DESC

			END")->result_array();
		$data['data'] = $query_coba;

		// echo 'sss';
		$this->load->view('Pdf_master_view/rekap_hutangKO/rkp_hutangKO_tengah',$data);

	}

	function Query_HutangKO_PDF($JobNo='', $VendorId='', $Status=''){

		if ($JobNo == '' || $VendorId == '' || $Status == '' ) {
			redirect('Welcome','refresh');
		}

		$this->load->model('Laporan_model/M_query_hutangko');

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
    	$tglSkrg = date('d-M-Y');
    	$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

    	$getData =$this->M_query_hutangko->QueryPDF($JobNo, $VendorId, $Status);
		$data['dataKO'] = $getData;


    	$VendorNm = $this->db->query("SELECT  VendorNm FROM Vendor WHERE VendorId='$VendorId'")->row_array()['VendorNm'];
    	$Status_custom = explode('_',$Status);
    	$Status_custom = ($Status == 'ALL') ? 'ALL' :  $Status_custom[0].' '.$Status_custom[1];

    	$data['VendorNm'] = ($VendorId == 'ALL') ? $VendorId : $VendorNm;
    	$data['JobNm'] = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;
    	$data['Status_custom'] = $Status_custom;

    	$data['JobNo'] = $JobNo;
    	$data['VendorId'] = $VendorId;
    	$data['Status'] = $Status;
    	

    	$this->load->view('Pdf_master_view/query_HutangKO/tengah_pdf_QueryHutangKO', $data);
   

	}




}

/* End of file Pdf_for_all.php */
/* Location: ./application/controllers/Pdf_for_all.php */