<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pertanggung_jawaban_entry_lapangan extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Datatables');
	}

	function data_json_pj_lapangan($where,  $dari_tgl='', $sampai_tgl='', $dari_tgl_pj='', $sampai_tgl_pj='')
	{
		$this->load->library('Datatables');

		$JobNo = $where['JobNo'];
		$Alokasi = $where['Alokasi'];


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
			ApprovedByKT,
			TimeApprovedKT,
			CASE
				WHEN ApprovedByKK IS NULL THEN 'belum_KK'
				ELSE 'sudah_KK'
			END AS 'ApprovedByKK', 
			TimeEntry,
			JobNo,
			NoPJ,
			TglPJ,
			TotalPJ,
			PjApprovedByAK,
			PjTimeApprovedAK


			");
		$this->datatables->from('PdHdr');
		$this->datatables->where('JobNo',$JobNo);
		if ($Alokasi <> '') {
			$this->datatables->where('Alokasi',$Alokasi);
		}
		
		if ($dari_tgl !== '' && $sampai_tgl !== '') {
			$this->datatables->where('TglPD >=', $dari_tgl);
			$this->datatables->where('TglPD <=', $sampai_tgl);
		}

		if ($dari_tgl_pj !== '' && $sampai_tgl_pj !== '') {
			$this->datatables->where('TglPJ >=', $dari_tgl_pj);
			$this->datatables->where('TglPJ <=', $sampai_tgl_pj);
		}

		$dari_tgl_khusus_print = ($dari_tgl == '' || $dari_tgl == null ) ? 0 : $dari_tgl;
		$sampai_tgl_khusus_print = ($sampai_tgl == '' || $sampai_tgl == null ) ? 0 : $sampai_tgl;

		$dari_tgl_khusus_print_pj = ($dari_tgl_pj == '' || $dari_tgl_pj == null ) ? 0 : $dari_tgl_pj;
		$sampai_tgl_khusus_print_pj = ($sampai_tgl_pj == '' || $sampai_tgl_pj == null ) ? 0 : $sampai_tgl_pj;


		$atur_url ='';
		$atur_url .= site_url('PDPJ/PJ_lapangan_entry_controller/detail_pj_lapangan/');
		$atur_url .= $JobNo.'/'.$Alokasi;
		$atur_url .= ($dari_tgl == '') ? '/0' : '/'.$dari_tgl;
		$atur_url .= ($sampai_tgl == '') ? '/0' : '/'.$sampai_tgl;
		$atur_url .= ($dari_tgl_pj == '') ? '/0' : '/'.$dari_tgl_pj;
		$atur_url .= ($sampai_tgl_pj == '') ? '/0' : '/'.$sampai_tgl_pj;
		$atur_url .= '/1';

		// $atur_url .= ($UrlPengenal == 'Permintaan_dana_lapangan') ? '/1' : '/0';

		$this->datatables->where("(NoRef IS NOT NULL OR NoRef<>'')", NULL, FALSE);
		$this->datatables->where('RejectBy IS NULL', NULL, FALSE);

		
		$this->datatables->add_column('aksi_pj','<a class="btn btn-sm btn-success" href="'.site_url("PDPJ/PJ_lapangan_entry_controller/detail_pj_lapangan/$1/epj_lap/$2/$3").'" target="_blank"><i class="fa fa-eye"></i></a>','LedgerNo,NoPD,NoPJ');


		$this->datatables->add_column('aksi_pj_js','<button type="button" class="btn btn-sm btn-success" onclick="detail_PJLapangan($1)" id="lihat_pj_lapangan_$1" data-NoPD="$2" data-NoPJ="$3"  title="LIHAT PJ LAPANGAN" data-url_back="'.$atur_url.'" ><i class="fa fa-eye"></i></button>','LedgerNo,NoPD,NoPJ');

		$this->datatables->add_column('aksi_loading_pj','<button type="button" class="btn btn-sm btn-danger" id="loading_pj_lapangan_$1" title="LIHAT PJ LAPANGAN" disabled="disabled" style="display:none;" ><i class="fa fa-spinner fa-spin"></i></button>','LedgerNo');

		$this->datatables->add_column('print_pd_new','<a href="javascript:void(0)" class="btn btn-sm" onclick="btn_print_PD($1)" id="btn_print_PD_$1" data-NoPD="$2" data-JobNo="$3" data-Alokasi="$4" data-PjApprovedByAK="$5" data-url_print="'.site_url('Permintaan_dana_lapangan/print_pd_lapangan/$1/$2/$3/$4').'" style="background-color:#c0392b;color:white;" title="PRINT"><i class="fa fa-print"></i></a>','LedgerNo,NoPD,JobNo,Alokasi, PjApprovedByAK');


		$this->datatables->add_column('print_PD_terbaru','<a href="javascript:void(0)" onclick="cek_printPD($1)" id="print_ID_$1" data-LedgerNo="$1" data-PjApprovedByAK="$5" data-NoPD="$4" data-NoPJ="$6" data-urlsaya="'.site_url('MY_PD/PD_PDF_ALL/ExpPDF_PD/$1/$2/$3/$4/'.$dari_tgl_khusus_print.'/'.$sampai_tgl_khusus_print.'/'.$dari_tgl_khusus_print_pj.'/'.$sampai_tgl_khusus_print_pj).'" class="btn btn-sm" style="background-color:#c0392b;color:white;" title="PRINT" ><i class="fa fa-print"></i></a>','LedgerNo, JobNo, Alokasi, NoPD, PjApprovedByAK, NoPJ');


		$this->datatables->edit_column('TglPD','$1','TanggalSaya(TglPD)');
		$this->datatables->edit_column('TotalPD', '$1', 'UbahGabunganUang(TotalPD)');

		$this->datatables->edit_column('TglPJ','$1','TanggalSaya(TglPJ)');
		$this->datatables->edit_column('TotalPJ', '$1', 'UbahGabunganUang(TotalPJ)');

		$this->datatables->edit_column('PjTimeApprovedAK', '$1', 'TanggalSaya(PjTimeApprovedAK)');

		return $this->datatables->generate();
	}

	// function data_json_pj_lapangan($where){

	// 	$this->datatables->select("LedgerNo, NoPD, TglPD, TipeForm, Deskripsi, TotalPD, NoPJ, TglPJ, TotalPJ, PjApprovedByAK, PjTimeApprovedAK, JobNo, Alokasi ");
	// 	$this->datatables->from('PdHdr');
	// 	$this->datatables->where($where);

	// 	$this->db->group_start();
	// 	$this->db->where('NoRef IS NOT NULL', NULL, FALSE);
	// 	$this->db->or_where('NoRef <>', '');
	// 	$this->db->group_end();

	// 	$this->datatables->where('RejectBy IS NULL', NULL, FALSE);

	// 	$this->datatables->edit_column('TglPD','$1','tgl_baru_tahun_pendek(TglPD)');
	// 	$this->datatables->edit_column('TglPJ','$1','tgl_baru_tahun_pendek(TglPJ)');
	// 	$this->datatables->edit_column('TotalPD','$1','UbahGabunganUang(TotalPD)');
	// 	$this->datatables->edit_column('PjTimeApprovedAK','$1','tgl_baru_tahun_pendek_dengan_waktu(PjTimeApprovedAK)');
	// 	$this->datatables->edit_column('TotalPJ','$1','UbahGabunganUang(TotalPJ)');

	// 	$this->datatables->add_column('aksi_pj','<a class="btn btn-xs btn-default" href="'.site_url("PDPJ/PJ_lapangan_entry_controller/detail_pj_lapangan/$1/epj_lap/$2").'"><i class="fa fa-eye"> Detail</i></a>','LedgerNo,NoPD');

	// 	return $this->datatables->generate();
	// }

	function Dapat_TotalPJ($JobNo,$Alokasi){
		$query = $this->db->query("SELECT SUM(TotalPJ) as TotalPJ FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND NoRef IS NOT  NULL AND NoRef <> '' AND RejectBy IS NULL")->row_array();

		return $query['TotalPJ'];
	}

	function aksi_update_pj($flag_status_approve,$NoPD){

		$this->db->trans_begin();

		$PjApprovedByAK = $this->session->userdata("MIS_LOGGED_NAME");
		$PjTimeApprovedAK = date('Y-m-d H:i:s');

		if ($flag_status_approve == 'APPROVE') {

			$this->db->query("UPDATE PdHdr SET PjApprovedByAK='$PjApprovedByAK', PjTimeApprovedAK='$PjTimeApprovedAK' WHERE NoPD='$NoPD'  ");
		}

		if ($flag_status_approve == 'UNAPPROVE') {

			$this->db->query("UPDATE PdHdr SET PjApprovedByAK=NULL, PjTimeApprovedAK=NULL WHERE NoPD='$NoPD'  ");
		}


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}

	}

	function get_data_pj_lapangan($LedgerNo,$NoPD){

		$query = $this->db->query("SELECT *,
			(SELECT ISNULL(COUNT(LedgerNo),0) FROM BLE WHERE (NoPD=PdHdr.NoPD OR NoPD=PdHdr.NoRef)) AS CountBLE,
			(SELECT JobNm FROM Job WHERE JobNo=PdHdr.JobNo) as JobNm,
			(SELECT Lokasi FROM Job WHERE JobNo=PdHdr.JobNo ) as Lokasi
			FROM PdHdr 
			WHERE NoPD='$NoPD' AND LedgerNo='$LedgerNo' ");
		return $query;
	}

	function dapat_data_detail_PdDtl($LedgerNo,$NoPD,$NoPJ,$status_detail_PdDtl){

		if ($status_detail_PdDtl == 'PdDtl_cadangan') {

			$cek_data = $this->db->where('NoPD',$NoPD)->get('PdDtl_cadangan')->num_rows();

			if ($cek_data == 0) {
				$this->db->query("
					DELETE FROM PdDtl_cadangan WHERE NoPD='$NoPD'
					INSERT INTO PdDtl_cadangan (NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjHrgSatuan, PjUserEntry, PjTimeEntry)
					SELECT NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjHrgSatuan, PjUserEntry, PjTimeEntry FROM PdDtl WHERE NoPD='$NoPD'");

				$query = $this->db->select('*,(Vol * HrgSatuan) as JumlahHarga, (PjVol * PjHrgSatuan) as PjJumlahHarga ')->where('NoPD',$NoPD)->get('PdDtl_cadangan');
				return $query;
			}else{
				$query = $this->db->select('*,(Vol * HrgSatuan) as JumlahHarga, (PjVol * PjHrgSatuan) as PjJumlahHarga')->where('NoPD',$NoPD)->get('PdDtl_cadangan');
				return $query;
			}

			
		}else{
			$query = $this->db->select('*,(Vol * HrgSatuan) as JumlahHarga, (PjVol * PjHrgSatuan) as PjJumlahHarga')->where('NoPD',$NoPD)->get('PdDtl');
			return $query;
		}
	}

	function dapat_PdDtl($NoUrut,$NoPD,$JobNo,$Alokasi,$status_detail_PdDtl){

		if ($status_detail_PdDtl == 'PdDtl_cadangan') {
			return $this->db->query(" SELECT a.*, (SELECT Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP=a.KdRAP ) as UraianRAP FROM PdDtl_cadangan a WHERE a.NoUrut='$NoUrut' AND a.NoPD='$NoPD'")->row_array();
		}else{
			return $this->db->query(" SELECT a.*, (SELECT Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP=a.KdRAP ) as UraianRAP FROM PdDtl a WHERE a.NoUrut='$NoUrut' AND a.NoPD='$NoPD'")->row_array();
		}
	}

	function simpan_uraian_pj($data,$where,$status_detail_PdDtl){
		$this->db->trans_begin();

		$this->db->set($data)->where($where)->update($status_detail_PdDtl);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
		
	}

	function update_pdhdr_pembuatan_NoPJ($data_set,$NoPD,$LedgerNo){

		$this->db->trans_start();		

		// $NoPJ = $data_set['NoPJ'];

		$aksi_update_pdhdr_pembuatan_NoPJ = $this->db->set($data_set)->where('LedgerNo',$LedgerNo)->or_where('NoPD',$NoPD)->update('PdHdr');

		$aksi_query_hapus_PdDtl_nya_terlebih_dahulu = $this->db->query("DELETE FROM PdDtl WHERE NoPD='$NoPD'");

		$aksi_query_pindah_data_dari_PdDtl_cadangan_ke_PdDtl = $this->db->query("INSERT INTO PdDtl 
			(NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjUraian, PjVol, PjHrgSatuan, PjUserEntry, PjTimeEntry)
			SELECT NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjUraian, PjVol, PjHrgSatuan, PjUserEntry, PjTimeEntry FROM PdDtl_cadangan WHERE NoPD='$NoPD'");
		
		$aksi_query_hapus_data_di_PdDtl_cadangan = $this->db->query("DELETE FROM PdDtl_cadangan WHERE NoPD='$NoPD' ");



		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
			return false;
		}else{
			return true;
		}


	}

	// function update_pdhdr_pembuatan_NoPJ_old($data_set,$NoPD,$LedgerNo){

	// 	$this->db->trans_begin();

	// 	$aksi_update_pdhdr_pembuatan_NoPJ = $this->db->set($data_set)->where('LedgerNo',$LedgerNo)->or_where('NoPD',$NoPD)->update('PdHdr');


	// 	$aksi_query_hapus_PdDtl_nya_terlebih_dahulu = $this->db->query("DELETE FROM PdDtl WHERE NoPD='$NoPD'");

	// 	$aksi_query_pindah_data_dari_PdDtl_cadangan_ke_PdDtl = $this->db->query("INSERT INTO PdDtl 
	// 	(NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjUraian, PjVol, PjHrgSatuan, PjUserEntry, PjTimeEntry)
	// 	SELECT NoPD, NoUrut, KdRAP, Uraian, Vol, Uom, HrgSatuan, UserEntry, TimeEntry, NoPJ, PjUraian, PjVol, PjHrgSatuan, PjUserEntry, PjTimeEntry FROM PdDtl_cadangan WHERE NoPD='$NoPD'");
		
	// 	$aksi_query_hapus_data_di_PdDtl_cadangan = $this->db->query("DELETE FROM PdDtl_cadangan WHERE NoPD='$NoPD' ");

		

	// 	if ($this->db->trans_status() === FALSE) {
	// 		$this->db->trans_rollback();
	// 		return false;
	// 	} else {
	// 		$this->db->trans_commit();
	// 		return true;
	// 	}

	// }

}

/* End of file M_pertanggung_jawaban_entry_lapangan.php */
/* Location: ./application/models/PD_PJ_model/M_pertanggung_jawaban_entry_lapangan.php */