<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class RekapInvoice_Supplier extends CI_Controller {

	var $Company;

	function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		// $this->load->model('MyReport_model/Model_RekapInv_Supplier');
	}

	function view()
	{
		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'REKAP INVOICE SUPPLIER (PROCUREMENT)';
		$data['bodyclass'] = 'sidebar-collapse skin-green-light';

		$data['dari_tgl'] = date('Y-m-01');
		$data['sampai_tgl'] = date('Y-m-t');
		$data['dataJob'] = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$this->Company'")->result_array();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('report_view/procurement/rekap_invoice_supplier/view_inv', $data);
		$this->load->view('templates/footer');
	}

	function get_rekap_inv_supplier(){
		if (!$this->input->post() ) {
			die;
		}

		

		$JobNo = $this->input->post('JobNo',TRUE);
		$dari_tgl = $this->input->post('dari_tgl',TRUE);
		$sampai_tgl = $this->input->post('sampai_tgl',TRUE);

		$data['JobNo'] = $JobNo;
		$data['dari_tgl'] = $dari_tgl;
		$data['sampai_tgl'] = $sampai_tgl;


		$VendorId = $this->input->post('VendorId',TRUE);
		$SortBy = $this->input->post('SortBy',TRUE);

		$data['VendorId'] = $VendorId;
		$data['SortBy'] = $SortBy;

		$SortBy = ($SortBy == 'ByVendor') ? 'D.VendorNm' : 'A.JobNo';

		$dari_tgl = $dari_tgl.' 00:00';
		$sampai_tgl = $sampai_tgl.' 23:59';

		$myquery =" SELECT A.*, B.CompanyId, B.JobNm, D.VendorNm FROM Invoice A 
                   LEFT JOIN Job B ON A.JobNo=B.JobNo 
                   LEFT JOIN KoHdr C ON A.NoKO=C.NoKO 
                   LEFT JOIN Vendor D ON C.VendorId=D.VendorId 
                   WHERE A.TimeEntry>='$dari_tgl' AND A.TimeEntry<='$sampai_tgl' ";

		$myquery .= ($JobNo !='AllJob') ? " AND B.JobNo='$JobNo'" : "AND B.Company='$this->Company' " ;
		$myquery .= ($VendorId !='AllVendor') ? " AND D.VendorId='$VendorId' " : "" ;

		$myquery .= "ORDER BY ".$SortBy;


		$get_data = $this->db->query($myquery)->result_array();
		$data['dataInv'] = $get_data;

		

		$this->load->view('report_view/procurement/rekap_invoice_supplier/tbl_inv',$data);
	}

	function DataList_InvSupplier($dari_tgl='',$sampai_tgl='',$JobNo='',$VendorId='',$SortBy=''){
		if ($JobNo=='') {
			die;
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['judul_atas'] = 'REKAP INVOICE SUPPLIER '.$this->Company;
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$where['dari_tgl'] = $dari_tgl;
		$where['sampai_tgl'] = $sampai_tgl;
		$where['JobNo'] = $JobNo;
		$where['VendorId'] = $VendorId;
		$where['SortBy'] = $SortBy;

		$data['dari_tgl'] = $dari_tgl;
		$data['sampai_tgl'] = $sampai_tgl;
		$data['JobNo'] = $JobNo;
		$data['VendorId'] = $VendorId;
		$data['SortBy'] = $SortBy;


		$SortBy = ($SortBy == 'ByVendor') ? 'D.VendorNm' : 'A.JobNo';
		$dari_tgl = $dari_tgl.' 00:00';
		$sampai_tgl = $sampai_tgl.' 23:59';

		$myquery =" SELECT A.*, B.CompanyId, B.JobNm, D.VendorNm FROM Invoice A 
                   LEFT JOIN Job B ON A.JobNo=B.JobNo 
                   LEFT JOIN KoHdr C ON A.NoKO=C.NoKO 
                   LEFT JOIN Vendor D ON C.VendorId=D.VendorId 
                   WHERE A.TimeEntry>='$dari_tgl' AND A.TimeEntry<='$sampai_tgl' ";

		$myquery .= ($JobNo !='AllJob') ? " AND B.JobNo='$JobNo'" : "AND B.Company='$this->Company' " ;
		$myquery .= ($VendorId !='AllVendor') ? " AND D.VendorId='$VendorId' " : "" ;
		$myquery .= "ORDER BY ".$SortBy;

		$get_data = $this->db->query($myquery)->result();
		$data['dataInv'] = $get_data;

		$data['JobNm'] = $this->db->select('JobNm')->where('JobNo',$JobNo)->get('Job')->row()->JobNm;

		$this->load->view('report_view/procurement/rekap_invoice_supplier/pdf_tengah', $data);



		// print_rr($where);

	}




}

/* End of file RekapInvoice_Supplier.php */
/* Location: ./application/controllers/MyReport/Procurement/RekapInvoice_Supplier.php */