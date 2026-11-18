<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_invoice extends CI_Model {

	function dapat_json_tbl($JobNo=''){

		$this->load->library('Datatables');

		$this->db->query("
			DROP TABLE IF EXISTS #temp_Invoice
			CREATE TABLE #temp_Invoice(
			JobNo nvarchar(10) NOT NULL,
			NoKO nvarchar(15) NOT NULL,
			InvNo nvarchar(50) NOT NULL,
			InvDate date NULL,
			DueDate date NULL,
			PPN money NULL,
			FPNo nvarchar(50) NULL,
			FPDate date NULL,
			Total money NULL,
			Keterangan nvarchar(255) NULL,
			UserEntry nvarchar(30) NULL,
			TimeEntry datetime NULL,
			NoSJ nvarchar(1000) NULL,
			LedgerNo bigint NOT NULL,
			PPH money NULL,
			VendorNm nvarchar(100) NULL
			)

			INSERT INTO #temp_Invoice (
			JobNo, NoKO, InvNo, InvDate, DueDate, PPN, FPNo, FPDate, Total, Keterangan, UserEntry, TimeEntry, NoSJ, LedgerNo, PPH, VendorNm)
			SELECT A.JobNo, A.NoKO, A.InvNo, A.InvDate, A.DueDate, A.PPN, A.FPNo, A.FPDate, A.Total, A.Keterangan, A.UserEntry, A.TimeEntry, A.NoSJ, A.LedgerNo, A.PPH, C.VendorNm 
			FROM Invoice A
			LEFT JOIN KoHdr B ON A.NoKO=B.NoKO
			LEFT JOIN Vendor C ON B.VendorId=C.VendorId
			WHERE A.JobNo='$JobNo'

			");

		$this->datatables->select('JobNo, NoKO, InvNo, InvDate, DueDate, PPN, FPNo, FPDate, Total, Keterangan, UserEntry, TimeEntry, NoSJ, LedgerNo, PPH, VendorNm');
		$this->datatables->from('#temp_Invoice');
		$this->datatables->edit_column('InvDate','$1','TanggalSaya(InvDate)');
		$this->datatables->edit_column('DueDate','$1','TanggalSaya(DueDate)');
		$this->datatables->edit_column('FPDate','$1','TanggalSaya(FPDate)');
		$this->datatables->edit_column('TimeEntry','$1','TglIndoSTRIP_dengan_waktu(TimeEntry)');

		$this->datatables->edit_column('PPN', '$1', 'UbahGabunganUang_new(PPN)');
		$this->datatables->edit_column('PPH', '$1', 'UbahGabunganUang_new(PPH)');
		$this->datatables->edit_column('Total', '$1', 'UbahGabunganUang_new(Total)');
		$this->datatables->edit_column('InvNo','$1','ShowKodeAman(InvNo)');

		$this->datatables->add_column('hapus_invoice','<button class="btn btn-sm btn-link" id="btn_edit$1" title="Edit Invoice" onclick="btn_edit_invoice($1)" ><u><i class="fa fa-pencil"></i> UPDATE</u></button> <button class="btn btn-sm btn-link" id="btn_hapus$1" title="Hapus Invoice" onclick="btn_hapus_invoice($1)" ><u><i class="fa fa-trash"></i> HAPUS</u></button>','LedgerNo, JobNo, NoKO');

		return $this->datatables->generate();
	}

	function dapat_list_job($UserID,$Company){
		return $this->db->query("
			SELECT ax.JobNo, bx.JobNm FROM
			(SELECT item AS JobNo
				FROM
				dbo.SplitString ((SELECT top 1 a.AksesJob FROM (SELECT * FROM Login) AS a
					LEFT OUTER JOIN 
					(SELECT * FROM Job) AS b
					ON b.JobNo = a.AksesJob
					WHERE a.UserID= '$UserID'), ',')) AS ax
			LEFT OUTER JOIN 
			Job AS bx
			ON bx.JobNo = ax.JobNo WHERE bx.Company='$Company' AND bx.StatusJob<>'Closed' group by ax.JobNo, bx.JobNm

			")->result_array();

	}

	function get_detail_KO($JobNo){
		$q = $this->db->query("
			SELECT A.NoKO, 
			FORMAT(A.TglKO,'dd-MMM-yyyy') AS TglKO, 
			B.VendorNm, 
			FORMAT((A.SubTotal - A.DiscAmount) + A.PPN,'N0') AS TotalKO  
			FROM KoHdr A 
			JOIN Vendor B ON A.VendorId=B.VendorId 
			WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ORDER BY NoKO DESC ")->result();
		return $q;
	}

	function cekInvoice($JobNo, $NoKO, $InvNo){
		$q = $this->db->query(" SELECT InvNo FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND InvNo='$InvNo' ");
		$data = $q->row_array();
		$hitung = $q->num_rows();

		return $hitung;
	}

	function check_pembayaran_invoice($JobNo, $NoKO, $InvNo ){
		$q = $this->db->query("SELECT PaymentAmount FROM InvPD WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND InvNo='$InvNo' ");

		$NoKO_mati = NULL;
		$InvNo_mati = NULL;
		$InvDate_mati = NULL;
		if ($q->num_rows() > 0) {
			$NoKO_mati = 'readonly';
			$InvNo_mati = 'readonly';
			$InvDate_mati = 'readonly';
		}
		return $array = array(
			'NoKO_mati' => $NoKO_mati,
			'InvNo_mati' => $InvNo_mati,
			'InvDate_mati' => $InvDate_mati,
		);

	}

	function cek_total_invoice($JobNo,$NoKO, $InvNo,$Total,$Status){

		$query_tambahan = "";
		if ($Status != 'NEW') {
			$query_tambahan = " AND InvNo !='$InvNo' ";
		}


		$Jumlah = 0;

		$query_cek_total = $this->db->query("
			SELECT ISNULL(SUM(Total),0) AS 'Total1', 
			(SELECT SubTotal - DiscAmount + PPN AS TotalKO FROM KoHdr WHERE NoKO='$NoKO') AS 'TotalKO'
			FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO' ".$query_tambahan." ");

		if ($query_cek_total->num_rows() > 0 ) {
			$row = $query_cek_total->row();
			$Total1 = $row->Total1;
			$TotalKO = $row->TotalKO;

			if ($TotalKO != NULL) {
				$Jumlah = $Total1 +  $Total;

				if ( $Jumlah > $TotalKO ) {
					echo json_encode(array('teks_label' => 'Alert!', 'teks_pesan'=> 'Total invoice melebihi total '.$NoKO, 'teks_icon' => 'info')) ;
					exit;
				}else{
					return '';
				}

			}else{
				return '';
			}

		}
	}



}

/* End of file M_invoice.php */
/* Location: ./application/models/Entry_model/Procurement_model/M_invoice.php */