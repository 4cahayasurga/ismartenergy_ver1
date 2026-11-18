<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pembatalan_ko extends CI_Controller
{


	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('M_pembatalan_ko');
		$this->load->helper('bantu');
	}

	public function view($JobNo = '')
	{

		redirect('Entry/Procurement/PembatalanKO_C/index/'.$JobNo);
		die;
		
		if ($JobNo == '') {
			redirect('Welcome', 'refresh');
			die;
		}

		// $data['JobNo'] = ($JobNo == '') ? '' : $JobNo;
		$data['judul'] = "PEMBATALAN KO";
		$data['bodyclass'] = 'sidebar-collapse skin-blue';

		$query = $this->db->query("SELECT JobNo,JobNm,Lokasi FROM Job WHERE JobNo='$JobNo' ")->row_array();
		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $query['JobNm'];
		$data['Lokasi'] = $query['Lokasi'];

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('pembatalan_ko/view_pembatalan_ko', $data);
		$this->load->view('templates/footer');
	}

	function request_list_pembatalan_ko()
	{
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$data['JobNo'] = $JobNo;
		// echo $JobNo;
		// die;

		$this->load->view('pembatalan_ko/request_list_pembatalan_ko', $data);
	}

	function dapat_list_pembatalan_ko()
	{
		if (!$this->input->post('JobNo', TRUE)) {
			die;
		}

		$JobNo = $this->input->post('JobNo', TRUE);

		header('Content-Type: application/json');
		echo $this->M_pembatalan_ko->dapat_list_pembatalan_ko($JobNo);
	}

	function lakukan_cancel()
	{
		if (!$this->input->post()) {
			die;
		}



		$LedgerNo = $this->input->post('LedgerNo', TRUE);
		$AlasanBatal = $this->input->post('AlasanBatal', TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');


		$data = array(
			'CanceledBy' => $UserEntry,
			'TimeCancel' => $TimeEntry,
			'CancelReason' => $AlasanBatal
		);

		$this->db->set($data)->where('LedgerNo', $LedgerNo)->update('KoHdr');
		echo 'berhasil';

	}

	function hapus_pembatalan()
	{
		if (!$this->input->post()) {
			die;
		}



		$LedgerNo = $this->input->post('LedgerNo', TRUE);
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');


		$data = array(
				'CanceledBy' => NULL,
				'TimeCancel' => NULL,
				'CancelReason' => NULL
			);

		// print_rr($_POST);
		// die;
		$this->db->set($data)->where('LedgerNo', $LedgerNo)->update('KoHdr');
		echo 'berhasil';
	}

	function dapat_form_cancel(){
		if (!$this->input->post()) {
			die;
		}

		$LedgerNo = $this->input->post('LedgerNo', TRUE);
		$JobNo = $this->input->post('JobNo', TRUE);
		$NoKO = $this->input->post('NoKO', TRUE);
		$VendorNm = $this->input->post('VendorNm', TRUE);
		$SubTotal = $this->input->post('SubTotal', TRUE);

		$data['LedgerNo'] = $LedgerNo;
		$data['JobNo'] = $JobNo;
		$data['NoKO'] = $NoKO;

		// $query = $this->db->query(" SELECT  ");

		$html = '';

		$html .='
		<div class="col-sm-12 text-center">
		<h4><label for="">Anda Yakin ingin membatalkan PO Berikut..?</label></h4>
		<br>
		<h5>'.$NoKO.' - '.$VendorNm.'<br></h5>
		<h5>Total PO = Rp. '.number_format($SubTotal).'</h5>


		<label for="">Alasan Reject : </label><br>
		<textarea name="AlasanBatal" id="AlasanBatal" placeholder="Isi Disini" cols="100" rows="5" required></textarea>
		<br>
		<button type="button" class="btn btn-primary btn-md" id="simpan_cancel" >Ya,Yakin</button>


		</div>
		
		';

		echo $html;

		// $this->load->view('')


	}
}

/* End of file Pembatalan_ko.php */
/* Location: ./application/controllers/Pembatalan_ko.php */