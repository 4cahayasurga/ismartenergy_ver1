<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan_dana_lapangan_model extends CI_Model
{

	function dapat_list_pd_json($where,  $dari_tgl='', $sampai_tgl='', $UrlPengenal='')
	{
		$JobNo = $where['JobNo'];
		$Alokasi = $where['Alokasi'];


		$this->load->library('Datatables');


		$this->datatables->select("
			LedgerNo,
			NoPD,
			NoRef,
			TglPD,
			Alokasi,
			TipeForm,
			NoKO,
			Deskripsi,
			TotalPD,
			ApprovedByAK,
			TimeApprovedAK,
			TimeEntry,
			JobNo


			");
		$this->datatables->from('PdHdr');
		$this->datatables->where('JobNo',$JobNo);
		
		if ($Alokasi <> '') {
			$this->datatables->where('Alokasi',$Alokasi);
		}

		$this->datatables->where("(NoRef IS NOT NULL AND NoRef<>'')", NULL, FALSE);

		if ($dari_tgl !== '' && $sampai_tgl !== '') {
			$this->datatables->where('TglPD >=', $dari_tgl);
			$this->datatables->where('TglPD <=', $sampai_tgl);
		}

		$atur_url = $JobNo.'/'.$Alokasi;
		$atur_url .= ($dari_tgl == '') ? '/0' : '/'.$dari_tgl;
		$atur_url .= ($sampai_tgl == '') ? '/0' : '/'.$sampai_tgl;
		$atur_url .= ($UrlPengenal == 'Permintaan_dana_lapangan') ? '/1' : '/0';

		$dari_tgl_khusus_print = ($dari_tgl == '' || $dari_tgl == null ) ? 0 : $dari_tgl;
		$sampai_tgl_khusus_print = ($sampai_tgl == '' || $sampai_tgl == null ) ? 0 : $sampai_tgl;

		$this->datatables->add_column('ViewPDLapangan','<a href="'.site_url("Permintaan_dana_lapangan/ViewPDLapangan/$1/").$atur_url.'" class="btn btn-sm" style="background-color:#009432;color:white;" title="Lihat PD"  ><i class="fa fa-eye"></i></a>','LedgerNo');
		
		$this->datatables->add_column('print_pd_new','<a href="javascript:void(0)" class="btn btn-sm" onclick="btn_print_PD($1)" id="btn_print_PD_$1" data-NoPD="$2" data-JobNo="$3" data-Alokasi="$4" data-ApprovedByAK="$5" data-url_print="'.site_url('Permintaan_dana_lapangan/print_pd_lapangan/$1/$2/$3/$4').'" style="background-color:#c0392b;color:white;" title="PRINT"><i class="fa fa-print"></i></a>','LedgerNo,NoPD,JobNo,Alokasi, ApprovedByAK');

		$this->datatables->add_column('pdlap_pdf','<a href="' . site_url('Permintaan_dana_lapangan/print_pd_lapangan/$1/$2/$3/$4/') . '" class="btn btn-sm" style="background-color:#c0392b;color:white;" title="PRINT" target="_blank" ><i class="fa fa-print"></i></a>','LedgerNo,NoPD,JobNo,Alokasi');

		$this->datatables->add_column('print_PD_terbaru','<a href="javascript:void(0)" onclick="cek_printPD($1)" id="print_ID_$1" data-LedgerNo="$1" data-ApprovedByAK="$5" data-NoPD="$4" data-urlsaya="'.site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD/$1/$2/$3/$4/'.$dari_tgl_khusus_print.'/'.$sampai_tgl_khusus_print).'" class="btn btn-sm" style="background-color:#c0392b;color:white;" title="PRINT" ><i class="fa fa-print"></i></a>','LedgerNo, JobNo, Alokasi, NoPD, ApprovedByAK');


		$this->datatables->edit_column('TglPD', '$1', 'tgl_baru_tahun_pendek(TglPD)');
		$this->datatables->edit_column('TotalPD', '$1', 'uang_koma_genap_decimal_kedua(TotalPD)');
		$this->datatables->edit_column('TimeApprovedAK', '$1', 'tgl_baru_tahun_pendek(TimeApprovedAK)');

		return $this->datatables->generate();
	}

	function dapatkan_judul($JobNo)
	{
		return $this->db->query("SELECT JobNm,JobNo,CONCAT_WS(' - ',JobNo,JobNm) as JobGabung,Lokasi FROM Job WHERE JobNo='$JobNo' ")->row_array();
	}


	function Pembuatan_NoRef($JobNo, $Alokasi)
	{
		$CI = &get_instance();
		$kunci_lapangan = 'L';

		$nomor_buatan = '';

		$query = $CI->db->query(" SELECT TOP 1 NoRef FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY LedgerNo DESC ");
		$row = $query->row_array();

		$NoRef = $row['NoRef'];
		$potong = substr($NoRef, -5);
		$gabung_lagi = (int) $potong + 1;
		$nomor_buatan = '0000' . $gabung_lagi;


		$penggabungan = $kunci_lapangan . $JobNo . $Alokasi . $nomor_buatan;

		return $penggabungan;
	}

	function dapatkan_list_no_ko($JobNo,$type='',$TipeForm='')
	{

		$query_tambahan = "";
		$PDDari = 'LAPANGAN';

		if ($PDDari == "LAPANGAN") {
			$query_tambahan = "AND KategoriId !='PO' ";
		}

		return $this->db->query("SELECT * FROM KoHdr WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ".$query_tambahan." ")->result_array();

		// if ($PDDari == "LAPANGAN") {
		// 	$query_tambahan = "AND KategoriId='KONTRAK' ";
		// }

		// return $this->db->query("SELECT * FROM KoHdr WHERE JobNo='$JobNo' AND ApprovedBy IS NOT NULL AND CanceledBy IS NULL AND ClosedBy IS NULL ".$query_tambahan."  ORDER BY NoKO ASC ")->result_array();

		return $query;
	}

	function dapatkan_vendor($NoKO,$JobNo='')
	{	
		$cek_NoKO = substr($NoKO,'-3');

		$VendorId = '';
		$Nama = '';
		$Alamat = '';
		$Telepon = '';
		$NPWP = '';
		$NoRek = '';
		$AtasNama = '';
		$Bank = '';
		$Status = 'tidak_ada';



		if ($cek_NoKO == 'INT') {
			$query_saya = $this->db->query("SELECT Nama, Alamat, Telepon, NPWP, NoRek, AtasNama, Bank FROM Job WHERE JobNo='$JobNo' ")->row_array();

			$row_ko = $query_saya;
			$VendorId = '';
			$Nama = $row_ko['Nama'];
			$Alamat = $row_ko['Alamat'];
			$Telepon = $row_ko['Telepon'];
			$NPWP = $row_ko['NPWP'];

			$NoRek = $row_ko['NoRek'];
			$AtasNama = $row_ko['AtasNama'];
			$Bank = $row_ko['Bank'];
			$Status = 'tidak_ada';


		}else{

			$query = $this->db->query("SELECT B.* FROM KoHdr A LEFT JOIN Vendor B ON A.VendorId=B.VendorId WHERE NoKO='$NoKO'  ");
			if ($query->num_rows() > 0) {
				$row = $query->row_array();
				$VendorId = $row['VendorId'];
				$Nama = $row['VendorNm'];
				$Alamat = $row['Alamat'];
				$Telepon = $row['Telepon1'];
				$NPWP = $row['NPWP'];

				$NoRek = $row['NoRek'];
				$AtasNama = $row['AtasNama'];
				$Bank = $row['Bank'];
				$Status = 'ada';
				// $disab = 'disabled="disabled"';
			}


		}

		return array(
			'VendorId' => $VendorId,
			'Nama' => $Nama,
			'Alamat' => $Alamat,
			'Telepon' => $Telepon,
			'NPWP' => $NPWP,
			'NoRek' => $NoRek,
			'AtasNama' => $AtasNama,
			'Bank' => $Bank,
			'Status' => $Status,
		);
	}


	function KoDtl_ke_PdDtl_sementara($JobNo,$Alokasi,$NoPD,$NoKO){
		$this->db->trans_begin();

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$Company = $this->config->item('Company');

		$where = array(
			'NoPD' => $NoPD,
			'Company' => $Company
		);


		$cek_data = $this->db->where($where)->get('PdDtl_sementara')->num_rows();
		if ($cek_data > 0) {
			$this->db->where($where)->delete('PdDtl_sementara');

		}

		// $this->db->query("
		// 	INSERT INTO PdDtl_sementara (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Company)
		// 	SELECT '$NoPD', NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan,'$UserEntry', '$TimeEntry', '$Company' FROM KoDtl WHERE NoKO='$NoKO' AND Alokasi='$Alokasi' ");

		// $this->db->query("
		// 	INSERT INTO PdDtl_sementara (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Company)
		// 	SELECT '$NoPD', NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan,'$UserEntry', '$TimeEntry', '$Company' FROM KoDtl WHERE NoKO='$NoKO' AND Alokasi='$Alokasi' 
		// 	");

		$this->db->query("
			INSERT INTO PdDtl_sementara (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Company)
			SELECT '$NoPD', NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan,'$UserEntry', '$TimeEntry', '$Company' FROM KoDtl WHERE NoKO='$NoKO'
			");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}


	function dapat_PdDtl_sementara($NoPD){
		$Company = $this->config->item('Company');

		$where = array(
			'NoPD' => $NoPD,
			'Company' => $Company
		);
		$query = $this->db->query(" SELECT *, (Vol * HrgSatuan) as Total FROM PdDtl_sementara WHERE NoPD='$NoPD' ORDER BY NoUrut ASC");
		return $query;
	}

	function dapat_NoPJSebelumnya($NoPD){
    	// $query = $this->db->query(" SELECT NoPJSebelumnya FROM PdHdr WHERE NoPD='PD21570B00013' ")->result_array();
		$query = $this->db->select('NoPJSebelumnya')->where('NoPD',$NoPD)->get('PdHdr')->result_array();
		return $query;
	}

	function pembuatan_NoUrut($Tipe,$NoPD){
		$Company = $this->config->item('Company');

		if ($Tipe == 'pd_baru') {
			$query = $this->db->query("SELECT TOP 1 NoUrut FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$Company'  ORDER BY NoUrut DESC ");

			if ($query->num_rows() == 0) {
				return 1;
			}else{
				$row = $query->row_array();
				return (int) $row['NoUrut'] + 1;
			}

			
		}
	}

	function aksi_simpan_uraian_pd($data)
	{
		$this->db->trans_begin();

		// $this->db->insert('table_pd_detail_sementara', $data);
		$this->db->insert('PdDtl_sementara', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function hapus_uraian_baru($NoUrut,$NoPD)
	{
		$this->db->trans_begin();

		$Company = $this->config->item('Company');

		$where = array(
			'NoUrut' => $NoUrut,
			'NoPD' => $NoPD,
			'Company' => $Company
		);

		$this->db->where($where)->delete('PdDtl_sementara');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function aksi_hapus_multiple_uraian($NoUrut,$NoPD){
		$this->db->trans_begin();

		$Company = $this->config->item('Company');

		$this->db->query("DELETE FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$Company' AND NoUrut IN (".$NoUrut.")  ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}

		function aksi_ubah_data_uraian_di_tambah_pd($data,$where){
			$this->db->trans_begin();

			$query = $this->db->set($data)->where($where)->update('PdDtl_sementara');

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}

		function Cek_Table_PdDtl_sementara($NoPD){
			$Company = $this->config->item('Company');

			$where = array(
				'NoPD' => $NoPD,
				'Company' => $Company
			);


			$query = $this->db->get_where('PdDtl_sementara',$where);
			return $query->num_rows();
		}

		function CekNoPD_ada_atau_tidak($NoPD){

			$where = array(
				'NoPD' => $NoPD,
			);

			$query = $this->db->get_where('PdHdr',$where);
			return $query->num_rows();
		}


		function simpan_data_dari_module_tambah_pd_keuangan($data, $NoPD, $JobNo, $Alokasi, $status_kode)
		{
			$this->db->trans_begin();
			$Company = $this->config->item('Company');

		//ubah nomor urut di counter pd
			OtomatisUbahCounter($JobNo, $Alokasi, $status_kode);

		//simpan data di PdHdr
			$this->db->insert('PdHdr', $data);

		// pindahkan data dr PdDtl_sementara ke Table PdDtl
			$this->db->query("INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry) 
				SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry FROM PdDtl_sementara 
				WHERE Company='$Company' AND NoPD='$NoPD'");

		//Hapus Data di PdDtl_sementara
			$this->db->query("DELETE FROM PdDtl_sementara WHERE Company='$Company' AND NoPD='$NoPD'");


			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}

		function dapatDataPD($LedgerNo)
		{
			$query = $this->db->query("SELECT a.*, b.JobNm, c.Alokasi, c.Keterangan AS 'KeteranganAlokasi', (SELECT Keterangan FROM TipeForm  WHERE TipeForm=a.TipeForm AND Alokasi=a.Alokasi ) AS 'KeteraganTipeForm'
				FROM PdHdr a
				LEFT JOIN  Job b ON a.JobNo=b.JobNo
				LEFT JOIN  Alokasi c ON a.Alokasi=c.Alokasi

				WHERE a.LedgerNo='$LedgerNo' ")->row_array();

			return $query;

			// $query = $this->db->query("SELECT a.*, b.JobNm, c.Alokasi, c.Keterangan AS 'KeteranganAlokasi', (SELECT Keterangan FROM TipeForm  WHERE TipeForm=a.TipeForm AND Alokasi=a.Alokasi ) AS 'KeteraganTipeForm'
			// 	FROM PdHdr a
			// 	LEFT JOIN  Job b ON a.JobNo=b.JobNo
			// 	LEFT JOIN  Alokasi c ON a.Alokasi=c.Alokasi

			// 	WHERE a.LedgerNo='$LedgerNo' ")->row_array();

			// return $query;


		}

		function dapat_PdDtl_sementara_EditPD($NoPD){
			$Company = $this->config->item('Company');

			$where = array(
				'NoPD' => $NoPD,
				'Company' => $Company
			);

			$query = $this->db->query(" SELECT *, (Vol * HrgSatuan) as Total FROM PdDtl_sementara WHERE NoPD='$NoPD' AND Company='$Company' ORDER BY NoUrut ASC");

		// $query = $this->db->query(" SELECT *, (Vol * HrgSatuan) as Total FROM PdDtl WHERE NoPD='$NoPD'  ORDER BY NoUrut DESC");

			return $query;
		}

		function PindahData_PdDtl_ke_PdDtl_sementara_EditPD($JobNo,$Alokasi,$NoPD,$LedgerNo){
			$this->db->trans_begin();

			$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
			$TimeEntry = date('Y-m-d H:i:s');

			$Company = $this->config->item('Company');

			$cek_data = $this->db
			->where('NoPD',$NoPD)
			->where('Company',$Company)
			->get('PdDtl_sementara')->num_rows();


			if ($cek_data > 0) {
				$this->db
				->where('NoPD',$NoPD)
				->where('Company',$Company)
				->delete('PdDtl_sementara');

			}

			$this->db->query("
				INSERT INTO PdDtl_sementara (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry,Company)
				SELECT '$NoPD', NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan,'$UserEntry', '$TimeEntry', '$Company' FROM PdDtl WHERE NoPD='$NoPD' ORDER BY NoUrut ASC ");

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}

		function dapat_NoPJSebelumnya_EditPD($NoPD){
    	// $query = $this->db->query(" SELECT NoPJSebelumnya FROM PdHdr WHERE NoPD='PD21570B00013' ")->result_array();
			$query = $this->db->select('NoPJSebelumnya')->where('NoPD',$NoPD)->get('PdHdr')->result_array();
			return $query;
		}


		function AksiSimpanPDUraian_EditPD($data)
		{
			$this->db->trans_begin();

			$this->db->insert('PdDtl_sementara', $data);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}


		function AksiEditUraian_EditPD($data,$where){
			$this->db->trans_begin();

			$query = $this->db->set($data)->where($where)->update('PdDtl_sementara');

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}



		function AksiUbahPD_EditPD($data, $NoPD, $JobNo, $Alokasi, $LedgerNo,$where_invoice)
		{
			$this->db->trans_begin();
			$Company = $this->config->item('Company');

		//hapus terlebih PdDtl yang ada
			$this->db->query("DELETE FROM PdDtl WHERE NoPD='$NoPD'");


		// pindahkan data dr PdDtl_sementara ke Table PdDtl
			$this->db->query("INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry) 
				SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry FROM PdDtl_sementara 
				WHERE Company='$Company' AND NoPD='$NoPD'");

		//Hapus Data di PdDtl_sementara
			$this->db->query("DELETE FROM PdDtl_sementara WHERE Company='$Company' AND NoPD='$NoPD'");

		//Ubah PdHdr


			$this->db->set($data)->where('NoPD',$NoPD)->update('PdHdr');

			// $where_invoice['Company'] = $this->Company;
			$cek_inv = $this->db->where($where_invoice)->get('InvPD_sementara');
			if ($cek_inv->num_rows() > 0 ) {
				//hapus InvPD
				$this->db->where($where_invoice)->delete('InvPD');

				$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
				$TimeEntry = date('Y-m-d H:i:s');

				$JobNo = $where_invoice['JobNo'];
				$NoKO = $where_invoice['NoKO'];
				$NoPD = $where_invoice['NoPD'];


				$copy_data_dari_InvPD_ke_InvPD_sementara = $this->db->query("
					INSERT INTO InvPD (JobNo, NoKO, InvNo, NoPD, PaymentAmount, UserEntry, TimeEntry)
					SELECT JobNo, NoKO, InvNo, NoPD, PaymentAmount, '$UserEntry', '$TimeEntry' FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND NoPD='$NoPD' AND Company='$Company'

					");

				$this->db->query("DELETE FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND NoPD='$NoPD' AND Company='$Company'  ");
			}

			$this->db->trans_complete();


			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}


		function Aksi_Ubah_Approved($data,$LedgerNo){
			$this->db->trans_begin();

			$this->db->set($data)->where('LedgerNo',$LedgerNo)->update('PdHdr');

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}


		function dapat_data_pd($LedgerNo)
		{
			$query = $this->db->select('a.*,(SELECT JobNm FROM Job b WHERE b.JobNo=a.JobNo ) as NmProyek ')->where('a.LedgerNo', $LedgerNo)->get('PdHdr a');
			return $query->row_array();
		}

		function dapat_pd_detail($NoPD)
		{
			$query = $this->db->query("SELECT a.*,(a.Vol * a.HrgSatuan) as JumlahHarga FROM PdDtl a WHERE a.NoPD='$NoPD' ORDER BY a.NoUrut ASC")->result_array();
			return $query;
		}

		function cekSaldoSebelumnya($where)
		{

			$query = $this->db->select('Amount')->get_where('BLE', $where);

			$Amount = 0;

			if ($query->num_rows() > 0) {
				$row = $query->row_array();
				$Amount = $row['Amount'];
			} else {
				$Amount = 0;
			}
			return $Amount;
		}

	//module invoice

		function get_data_invoice($data){
			$JobNo = $data['JobNo'];
			$NoKO = $data['NoKO'];

			$myquery = $this->db->query("
				SELECT *, 
				(SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS 'TotalPayment',
				(SELECT (SELECT b.VendorNm FROM Vendor b WHERE a.VendorId=b.VendorId )  FROM KoHdr a WHERE a.NoKO='$NoKO') as 'NamaVendor' 
				FROM Invoice 
				WHERE JobNo='$JobNo' AND NoKO='$NoKO' ")->result();

			return $myquery;
		}

		function aksi_simpan_inv($array){
			$this->db->trans_begin();

			$this->db->insert_batch('InvPD_sementara',$array);

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}

		function simpan_data_dari_module_tambah_pd_lapangan($data, $NoPD, $JobNo, $Alokasi, $status_kode,$array_dari_InvPD_sementara,$status_tambah=''){

			$this->db->trans_begin();
			$Company = $this->config->item('Company');

			


			//ubah nomor urut di counter pd
			OtomatisUbahCounter($JobNo, $Alokasi, $status_kode,$status_tambah);

			//simpan data di PdHdr
			$this->db->insert('PdHdr', $data);

			// pindahkan data dr PdDtl_sementara ke Table PdDtl
			$this->db->query("INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry) 
				SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry FROM PdDtl_sementara 
				WHERE Company='$Company' AND NoPD='$NoPD'");

			//Hapus Data di PdDtl_sementara
			$this->db->query("DELETE FROM PdDtl_sementara WHERE Company='$Company' AND NoPD='$NoPD'");


			//cek data di "InvPD_sementara"
			$get_InvPD_sementara = $this->db->where($array_dari_InvPD_sementara)->get('InvPD_sementara');

			if ($get_InvPD_sementara->num_rows() > 0) {

				$JobNo 		= $array_dari_InvPD_sementara['JobNo'];
				$NoPD 		= $array_dari_InvPD_sementara['NoPD'];
				$NoKO 		= $array_dari_InvPD_sementara['NoKO'];
				$Company 	= $array_dari_InvPD_sementara['Company'];

				$this->db->query("
					INSERT INTO InvPD (JobNo, NoKO, InvNo, NoPD, PaymentAmount, UserEntry, TimeEntry)
					SELECT JobNo, NoKO, InvNo, NoPD, PaymentAmount, UserEntry, TimeEntry 
					FROM InvPD_sementara 
					WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND NoPD='$NoPD' AND Company='$this->Company'");

				$this->db->query("DELETE FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND NoPD='$NoPD' AND Company='$this->Company' ");

			}


			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return false;
			} else {
				$this->db->trans_commit();
				return true;
			}
		}






	}

	/* End of file Permintaan_dana_lapangan_model.php */
/* Location: ./application/models/Permintaan_dana_lapangan_model.php */