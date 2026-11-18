<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FrmTrackingKO extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->session->userdata('MIS_LOGGED_ID')) {
			$this->session->sess_destroy();
			redirect('Auth/index');
		}

		if ($this->session->userdata('MIS_LOGGED_ID') == NULL) {
			$this->session->sess_destroy();
			redirect('Auth/index');
		}

		$this->load->model('Model_FrmTrackingKO');
	}

	public function index()
	{
		$data['bodyclass'] = 'sidebar-collapse skin-purple-light';
		$data['judul'] = 'Tracking KO';
		$data['ListJob'] = $this->Model_FrmTrackingKO->getJobNo();	

		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar');
		$this->load->view('laporan_view/procurement/TrackingKO/TrackingKO_halaman_utama',$data);
		$this->load->view('templates/footer');
	}

	function get_table_trackingKO(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		// == jika status 1=In Process, 2=In Delivered
		$Status = $this->input->post('Status',TRUE);
		$Company = $this->config->item('Company');

		$data['JobNo'] = $JobNo;
		$data['Status'] = $Status;

		$arrayTracking = array();
		$arrayDetail_Tracking = array();

		$NoKO = '';
		$TglKO = '';
		$VendorNm = '';
		$TotalKO = 0;
		$JumlahDetail = 0;
		$NoUrut = 1;
		
		$QueryJob = '';
		
		$QueryJob = ($JobNo == 'AllJob') ? "AND C.Company='$Company' " : "AND A.JobNo='$JobNo'";

		$QueryTrack = $this->db->query("
			SELECT A.*, B.VendorNm FROM KoHdr A
			JOIN Vendor B ON A.VendorId = B.VendorId
			JOIN Job C ON A.JobNo = C.JobNo 
			WHERE A.KategoriId='PO' ".$QueryJob." ORDER BY NoKO ");

		foreach($QueryTrack->result() AS $MyData) :


			
			$TotalKO = ($MyData->SubTotal - $MyData->DiscAmount) + $MyData->PPN;

			//Query Status bukan All
			if ($Status !='AllStatus') {

				
				$QuerySub = $this->db->query("SELECT TOP 1 * FROM TraceKO WHERE NoKO='$NoKO' ORDER BY Tanggal DESC ");

				if ($QuerySub->num_rows > 0) {

					$rowSub = $QuerySub->row_array();

					if ($rowSub['Status'] == $Status ) {

						$NoKO = $MyData->NoKO;
						$TglKO = $MyData->TglKO;
						$VendorNm = $MyData->VendorNm;

						$QueryDetail = $this->db->query("SELECT * FROM TraceKO WHERE NoKO='$NoKO'  ");
						$JumlahDetail = $QueryDetail->num_rows();

						foreach ($QueryDetail->result_array() as $rowDetail) {

							$arrayDetail_Tracking[] = array(
								'NoKO' => $rowDetail['NoKO'],
								'Keterangan' => $rowDetail['Keterangan'],
								'Tanggal' => $rowDetail['NoKO'],
								'Status' => ($rowDetail['Status'] == 0) ? 'In Process' : 'Delivered',

							);

						}

					
					}

				}

			}else{

				$NoKO = $MyData->NoKO;
				$TglKO = $MyData->TglKO;
				$VendorNm = $MyData->VendorNm;

				$QueryStatus = $this->db->query("SELECT * FROM TraceKO WHERE NoKO='$NoKO' ");

				$JumlahDetail = $QueryStatus->num_rows();
				
				foreach($QueryStatus->result_array() AS $dataStatus ){
					$arrayDetail_Tracking[] = array(
						'NoKO' => $dataStatus['NoKO'],
						'Keterangan' => $dataStatus['Keterangan'],
						'Tanggal' => $dataStatus['Tanggal'],
						'Status' => ($dataStatus['Status'] == 0) ? 'In Process' : 'Delivered',
					);
				}


			}

			$arrayTracking[] = (object) array(
					'NoUrut' => $NoUrut++,
					'NoKO' => $MyData->NoKO,
					'TglKO' => $MyData->TglKO,
					'VendorNm' => $MyData->VendorNm,
					'TotalKO' => $TotalKO,
					'JumlahDetail' => $JumlahDetail,
					'arrayDetail_Tracking' => $arrayDetail_Tracking,
				);

			$TotalKO = 0;

		endforeach;

		$data['arrayTracking'] = $arrayTracking;

		$this->load->view('laporan_view/procurement/TrackingKO/table_TrackingKO',$data);

		// print_rr($arrayTracking)
	
	}

	function ExportPDF_TrackKO($JobNo='',$Status=''){

		if ($JobNo == '') {
			redirect('Laporan/Procurement/FrmTrackingKO','refresh');
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
        $tglSkrg = date('d-M-Y');
        $data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

        $Company = $this->config->item('Company');

        $JobNm = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;

		$data['JobNo'] = $JobNo;
		$data['Status'] = $Status;
		$data['JobNm'] = $JobNm;

		$arrayTracking = array();
		$arrayDetail_Tracking = array();

		$NoKO = '';
		$TglKO = '';
		$VendorNm = '';
		$TotalKO = 0;
		$JumlahDetail = 0;
		$NoUrut = 1;
		
		$QueryJob = '';
		
		$QueryJob = ($JobNo == 'AllJob') ? "AND C.Company='$Company' " : "AND A.JobNo='$JobNo'";

		$QueryTrack = $this->db->query("
			SELECT A.*, B.VendorNm FROM KoHdr A
			JOIN Vendor B ON A.VendorId = B.VendorId
			JOIN Job C ON A.JobNo = C.JobNo 
			WHERE A.KategoriId='PO' ".$QueryJob." ORDER BY NoKO ");

		foreach($QueryTrack->result() AS $MyData) :


			
			$TotalKO = ($MyData->SubTotal - $MyData->DiscAmount) + $MyData->PPN;

			//Query Status bukan All
			if ($Status !='AllStatus') {

				
				$QuerySub = $this->db->query("SELECT TOP 1 * FROM TraceKO WHERE NoKO='$NoKO' ORDER BY Tanggal DESC ");

				if ($QuerySub->num_rows > 0) {

					$rowSub = $QuerySub->row_array();

					if ($rowSub['Status'] == $Status ) {

						$NoKO = $MyData->NoKO;
						$TglKO = $MyData->TglKO;
						$VendorNm = $MyData->VendorNm;

						$QueryDetail = $this->db->query("SELECT * FROM TraceKO WHERE NoKO='$NoKO'  ");
						$JumlahDetail = $QueryDetail->num_rows();

						foreach ($QueryDetail->result_array() as $rowDetail) {

							$arrayDetail_Tracking[] = array(
								'NoKO' => $rowDetail['NoKO'],
								'Keterangan' => $rowDetail['Keterangan'],
								'Tanggal' => $rowDetail['NoKO'],
								'Status' => ($rowDetail['Status'] == 0) ? 'In Process' : 'Delivered',

							);

						}

					
					}

				}

			}else{

				$NoKO = $MyData->NoKO;
				$TglKO = $MyData->TglKO;
				$VendorNm = $MyData->VendorNm;

				$QueryStatus = $this->db->query("SELECT * FROM TraceKO WHERE NoKO='$NoKO' ");

				$JumlahDetail = $QueryStatus->num_rows();
				
				foreach($QueryStatus->result_array() AS $dataStatus ){
					$arrayDetail_Tracking[] = array(
						'NoKO' => $dataStatus['NoKO'],
						'Keterangan' => $dataStatus['Keterangan'],
						'Tanggal' => $dataStatus['Tanggal'],
						'Status' => ($dataStatus['Status'] == 0) ? 'In Process' : 'Delivered',
					);
				}


			}

			$arrayTracking[] = (object) array(
					'NoUrut' => $NoUrut++,
					'NoKO' => $MyData->NoKO,
					'TglKO' => $MyData->TglKO,
					'VendorNm' => $MyData->VendorNm,
					'TotalKO' => $TotalKO,
					'JumlahDetail' => $JumlahDetail,
					'arrayDetail_Tracking' => $arrayDetail_Tracking,
				);

			$TotalKO = 0;

		endforeach;

		$data['arrayTracking'] = $arrayTracking;

		// print_rr($data);
		// die;

        $this->load->view('laporan_view/procurement/TrackingKO/pdf_tengah',$data);
	}

}

/* End of file FrmTrackingKO.php */
/* Location: ./application/controllers/Laporan/Procurement/FrmTrackingKO.php */