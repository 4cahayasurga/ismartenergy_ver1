<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrackingKO_C extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}
	public function index($JobNo='',$NoKO='')
	{
		$data['title'] = 'TRACKING KO';
		$data['judul'] = 'TRACKING KO';
		$data['bodyclass'] = 'skin-blue';
		$data['ListJob'] = ListJob();

		$data['JobNo'] = $JobNo;
		$data['NoKO'] = $NoKO;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/procurement/TrackingKO_V/halaman_utama_TrackingKO', $data);
		$this->load->view('templates/footer');
	}

	function Pilih_NoKO(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$TxtQuery = "SELECT A.NoKO, FORMAT(A.TglKO,'dd-MMM-yyyy') AS TglKO, B.VendorNm, FORMAT((A.SubTotal - A.DiscAmount) + A.PPN,'N0') AS TotalKO FROM KoHdr A JOIN Vendor B ON A.VendorId=B.VendorId WHERE JobNo='$JobNo' AND KategoriId='PO' ORDER BY NoKO DESC";

		$query = $this->db->query($TxtQuery)->result();
		$data['List_NoKO'] = $query;
		$data['JobNo'] = $JobNo;
		$this->load->view('entry_view/procurement/TrackingKO_V/tableList_NoKO', $data);
	}

	function NoKO_Berganti(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);

		$query = $this->db->query("SELECT TglKO,AlamatKirim,NamaKirim,TeleponKirim FROM KoHdr WHERE NoKO='$NoKO'")->row();
		echo json_encode($query);
	}

	function table_TrackingKO(){
		if (!$this->input->post()) {
			echo 'error';
			exit;
		}

		$JobNo 	= $this->input->post('JobNo',TRUE);
		$NoKO 	= $this->input->post('NoKO',TRUE);
		$NoKO = explode(' ',$NoKO);
		$NoKO = $NoKO[0];

		
		$data['ListTraceKO'] = $this->db->query("SELECT *, ISNULL(NULL,Trace#) AS 'Trace' FROM TraceKO WHERE NoKO='$NoKO' ORDER BY TimeEntry DESC  ")->result();
		$data['JobNo']	= $JobNo;
		$data['NoKO'] 	= $NoKO;
		// print_rr($data);
		// die;
		$this->load->view('entry_view/procurement/TrackingKO_V/table_TrackingKO', $data);
	}

	function Aksi_AddStatus(){
		if (!$this->input->post('NoKO') || !$this->input->post('JobNo') ) {
			// redirect('Entry/Procurement/TrackingKO_C/');
			echo '';
			die;
		}

		// print_rr($_POST);

		$NoKO = $this->input->post('NoKO',TRUE);
		$NoKO = explode(' ',$NoKO);
		$NoKO = $NoKO[0];

		$JobNo = $this->input->post('JobNo',TRUE);
		$Status = $this->input->post('Status',TRUE);
		$Keterangan = $this->input->post('Keterangan',TRUE);
		$Tanggal = $this->input->post('Tanggal',TRUE);
		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		$this->db->trans_begin();

		$data = array(
			'NoKO' => $NoKO,
			'Status' => $Status,
			'Keterangan' => $Keterangan,
			'Tanggal' => $Tanggal,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
		);

		$this->db->insert('TraceKO',$data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
			// is_pesan('error','Failed!');
			// redirect('Entry/Procurement/TrackingKO_C/');
		}else{
			$this->db->trans_commit();
			echo 'berhasil';
			// is_pesan('success','Saved!');
			// redirect('Entry/Procurement/TrackingKO_C/index/'.$JobNo.'/'.$NoKO);
		}

	}

	function Aksi_EditStatus(){
		if (!$this->input->post('NoKO') || !$this->input->post('JobNo') ) {
			// redirect('Entry/Procurement/TrackingKO_C/');
			echo '';
			die;
		}

		$Trace = $this->input->post('Trace',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);

		$JobNo = $this->input->post('JobNo',TRUE);
		$Status = $this->input->post('Status',TRUE);
		$Keterangan = $this->input->post('Keterangan',TRUE);
		$Tanggal = $this->input->post('Tanggal',TRUE);
		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		$this->db->trans_begin();

		$set = array(
			'Status' => $Status,
			'Keterangan' => $Keterangan,
			'Tanggal' => $Tanggal,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
		);

		$where = array(
			'Trace#' => $Trace,
			'NoKO' => $NoKO,
		);

		$this->db->set($set)->where($where)->update('TraceKO');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo 'gagal';
			// is_pesan('error','Failed!');
			// redirect('Entry/Procurement/TrackingKO_C/');
		}else{
			$this->db->trans_commit();
			echo 'berhasil';
			// is_pesan('success','Saved!');
			// redirect('Entry/Procurement/TrackingKO_C/index/'.$JobNo.'/'.$NoKO);
		}

	}

}

/* End of file TrackingKO_C.php */
/* Location: ./application/controllers/Entry/Procurement/TrackingKO_C.php */