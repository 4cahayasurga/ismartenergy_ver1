<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class KOAddendumRevisi_C extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}
	public function index($JobNo='')
	{

		$data['judul'] = 'KO ADDENDUM/REVISI';
		$data['bodyclass'] = 'skin-blue-light';

		$data['ListJob'] = ListJob();
		$data['JobNo'] = $JobNo;
		// $data['NoKO'] = $NoKO;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/procurement/KOAddendumRevisi_V/halaman_utama_KOAddendum', $data);
		$this->load->view('templates/footer');
	}

	public function Get_ListNoKO(){
		if (!$this->input->post()) {
			die;
		}
		$JobNo = $this->input->post('JobNo',TRUE);
		$html = '';
		$html .= '<option value="">:: Pilih salah satu ::</option>';
		$query = $this->db->query("SELECT NoKO FROM KoHdr WHERE JobNo='$JobNo' AND ClosedBy IS NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ORDER BY NoKO");
		if ($query->num_rows() > 0) {

			foreach ($query->result() as $row) {
				$selected = "";
				if ($this->session->has_userdata('NoKO_Addendum')==TRUE) {
					if ($this->session->userdata('NoKO_Addendum')==$row->NoKO) {
						$selected = "selected";
					}
				}
				$html .='<option value="'.$row->NoKO.'" '.$selected.' >'.$row->NoKO.'</option>';
			}
			
		}
		$this->session->unset_userdata('NoKO_Addendum');	
		echo $html;
	}

	public function tampiltable_KOAddendum(){
		if (!$this->input->post()) {
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);

		$Query_Current = $this->db->query("SELECT A.LedgerNo, A.JobNo, A.NoKO,A.TglKO,A.KategoriId,A.AddendumKe,B.VendorNm,A.SubTotal,A.ApprovedBy,A.TimeApproved, ISNULL(A.CanceledBy,NULL) AS CanceledBy, ISNULL(A.ClosedBy,NULL) AS ClosedBy FROM KoHdr A LEFT OUTER JOIN Vendor B ON A.VendorId = B.VendorId WHERE A.NoKO='$NoKO'")->result(); 

		$Query_History = $this->db->query("SELECT A.NoKO, A.JobNo,A.TglKO,A.KategoriId,A.AddendumKe,B.VendorNm,A.SubTotal,A.ApprovedBy,A.TimeApproved,A.AlasanAddendum, ISNULL(A.CanceledBy,NULL) AS CanceledBy, ISNULL(A.ClosedBy,NULL) AS ClosedBy, (SELECT LedgerNo FROM KoHdr WHERE NoKO=A.NoKO) AS LedgerNo FROM KoHdrH A LEFT OUTER JOIN Vendor B ON A.VendorId = B.VendorId WHERE A.NoKO='$NoKO'")->result();

		$data['JobNo'] = $JobNo;
		$data['NoKO'] = $NoKO;
		$data['KOAddendum_Current'] = $Query_Current;
		$data['KOAddendum_History'] = $Query_History;

		// print_rr($data);
		// die;

		$this->load->view('entry_view/procurement/KOAddendumRevisi_V/tampiltable_KOAddendum', $data);
	}

	function TambahAddendum(){
		if (!$this->input->post()) {
			echo json_encode(array(
				'flag' => 'error',
				'pesan' => 'Error'
			));
			die;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);
		$AlasanAddendum = $this->input->post('AlasanAddendum',TRUE);

		$AddendumKe = "";
		$TglKO = "";

		// == START CEK APAKAH SUDAH DI APPROVED BERDASARKAN NO KO ==
		$Query = $this->db->query("SELECT NoKO,TglKO,ApprovedBy,AddendumKe FROM KoHdr WHERE NoKO='$NoKO'");
		if ($Query->num_rows() < 1) {
			// echo 'data_tidak_ditemukan';
			echo json_encode(array(
				'flag' => 'data_tidak_ditemukan',
				'pesan' => 'data tidak ditemukan'
			));
			die;
		}else{
			$row = $Query->row();
			if ($row->ApprovedBy == NULL || $row->ApprovedBy == '' ) {
				echo json_encode(array(
					'flag' => 'KO_belum_approved',
					'pesan' => 'No '.$row->NoKO.' belum di-Approved.',
				));
				die;
			}

			$AddendumKe = (int) $row->AddendumKe;
			$TglKO = $row->TglKO;
		}
		// == END CEK APAKAH SUDAH DI APPROVED BERDASARKAN NO KO ==
		
		// == START Validasi jika addendum/revisi di hari yg sama lebih dari 1x ==
		$myquery = $this->db->query("SELECT NoKO,TglKO FROM KoHdrH WHERE NoKO='$NoKO' AND TglKO='$TglKO'  ");
		if ($myquery->num_rows() > 0) {
			$myrow = $myquery->row();
			echo json_encode(array(
				'flag' => 'revisi_sudah_ada',
				'pesan' => 'Sudah ada addendum/revisi untuk '.$myrow->NoKO.', Tanggal '.tgl_baru($myrow->TglKO),
			));
			die;
		}

		$this->db->trans_begin();

		$Query_KoDtl = $this->db->query("
			INSERT INTO KoDtlH (NoKO,TglKO,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Alokasi) 
			SELECT NoKO, '$TglKO', NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, Alokasi FROM KoDtl WHERE NoKO='$NoKO' ");

		$Query_Addendum = $this->db->query("
			INSERT INTO KoHdrH (NoKO, TglKO, JobNo, VendorId, KategoriId, SubKategoriId, NoSPR, SubTotal, DiscPercentage, DiscAmount, PPN, TotalTerbayar, AddendumKe, ClosedBy, TimeClosed, AlamatKirim, NamaKirim, TeleponKirim, MaterialApproval, RAP, K3, SyaratTeknis, SyaratPembayaran, JadwalPengiriman, Sanksi, JadwalPembayaran, Keterangan, QRCode, ApprovedBy, TimeApproved, UserEntry, TimeEntry, OverridePPN, PDFFile, CanceledBy, TimeCancel, CancelReason, AlasanAddendum, TglPengiriman, PotongKO, BeginningTotal)
			SELECT NoKO, TglKO, JobNo, VendorId, KategoriId, SubKategoriId, NoSPR, SubTotal, DiscPercentage, DiscAmount, PPN, TotalTerbayar, AddendumKe, ClosedBy, TimeClosed, AlamatKirim, NamaKirim, TeleponKirim, MaterialApproval, RAP, K3, SyaratTeknis, SyaratPembayaran, JadwalPengiriman, Sanksi, JadwalPembayaran, Keterangan, QRCode, ApprovedBy, TimeApproved, UserEntry, TimeEntry, OverridePPN, PDFFile, CanceledBy, TimeCancel, CancelReason, '$AlasanAddendum', TglPengiriman, PotongKO, BeginningTotal FROM KoHdr WHERE NoKO='$NoKO'  ");

		$AddendumKe = $AddendumKe + 1;
		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');


		$QueryUpdate = $this->db->query(" UPDATE KoHdr SET TglKO='$TglKO', ApprovedBy=NULL, TimeApproved=NULL, AddendumKe='$AddendumKe', UserEntry='$UserEntry', TimeEntry='$TimeEntry' WHERE NoKO='$NoKO'  ");

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) 
		{
			$this->db->trans_rollback();
			echo json_encode(array(
				'flag' => 'gagal',
				'pesan' => 'gagal menambahkan data' ,
			));
			die;
		} 
		else 
		{
			$this->db->trans_commit();
			is_pesan('success','berhasil menambahkan data');
			$this->session->set_userdata('JobNo_Addendum',$JobNo);
			$this->session->set_userdata('NoKO_Addendum',$NoKO);
			echo json_encode(array(
				'flag' => 'berhasil',
				'pesan' => 'berhasil menambahkan data' ,
			));
			die;
		}

	}

}

/* End of file KOAddendumRevisi_C.php */
/* Location: ./application/controllers/Entry/Procurement/KOAddendumRevisi_C.php */