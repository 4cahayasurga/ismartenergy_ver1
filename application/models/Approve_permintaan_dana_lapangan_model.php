<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Approve_permintaan_dana_lapangan_model extends CI_Model
{

	function dapat_list_pd_json($where,  $dari_tgl, $sampai_tgl, $UrlPengenal)
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
			JobNo


			");
		$this->datatables->from('PdHdr');
		$this->datatables->where('JobNo',$JobNo);
		
		if ($Alokasi <> '') {
			$this->datatables->where('Alokasi',$Alokasi);
		}
		// $this->datatables->where("(NoRef IS NOT NULL AND NoRef<>'')", NULL, FALSE);
		$this->datatables->where('ApprovedByAK IS NOT NULL', NULL, FALSE);

		if ($dari_tgl !== '' && $sampai_tgl !== '') {
			$this->datatables->where('TglPD >=', $dari_tgl);
			$this->datatables->where('TglPD <=', $sampai_tgl);
		}

		$atur_url = $JobNo.'/'.$Alokasi;
		$atur_url .= ($dari_tgl == '') ? '/0' : '/'.$dari_tgl;
		$atur_url .= ($sampai_tgl == '') ? '/0' : '/'.$sampai_tgl;
		$atur_url .= '/0';

		$url = ($dari_tgl == '') ? '/0' : '/'.$dari_tgl;
		$url .= ($sampai_tgl == '') ? '/0' : '/'.$sampai_tgl;


		// $this->datatables->add_column('ViewPDLapangan','<a href="'.site_url("Permintaan_dana_lapangan/ViewPDLapangan/$1").'" class="btn btn-sm" style="background-color:#009432;color:white;" title="Lihat PD"><i class="fa fa-eye"></i></a>','LedgerNo');

		$this->datatables->add_column('ViewPDKeuangan','<a href="'.site_url("Permintaan_dana_keuangan/ViewPdKeuangan/$1/$2/$3").$url.'" class="btn btn-sm" style="background-color:#009432;color:white;" title="Lihat PD"><i class="fa fa-eye"></i></a>','LedgerNo,JobNo,Alokasi');

		$this->datatables->add_column('ViewPDLapangan','<a href="'.site_url("Permintaan_dana_lapangan/ViewPDLapangan/$1/").$atur_url.'" class="btn btn-sm" style="background-color:#009432;color:white;" title="Lihat PD"  ><i class="fa fa-eye"></i></a>','LedgerNo');

		// $this->datatables->add_column('pdlap_pdf','<a href="' . site_url('Permintaan_dana_lapangan/print_pd_lapangan/$1/$2/$3/$4') . '" class="btn btn-sm" style="background-color:#c0392b;color:white;" title="PRINT"><i class="fa fa-print"></a>','LedgerNo,NoPD,JobNo,Alokasi');

		$this->datatables->add_column('print_pd_new','<a href="javascript:void(0)" class="btn btn-sm" onclick="btn_print_PD($1)" id="btn_print_PD_$1" data-NoPD="$2" data-JobNo="$3" data-Alokasi="$4" data-ApprovedByKT="$5" data-url_print="'.site_url('Permintaan_dana_lapangan/print_pd_lapangan/$1/$2/$3/$4').'" style="background-color:#c0392b;color:white;" title="PRINT"><i class="fa fa-print"></i></a>','LedgerNo,NoPD,JobNo,Alokasi, ApprovedByKT');

		// $this->datatables->add_column('aksi_baru','<a href="'.site_url("Permintaan_dana_lapangan/ViewPDLapangan/$1").'" class="btn btn-sm" style="background-color:#009432;color:white;" title="Lihat PD"><i class="fa fa-eye"></i></a>','LedgerNo');



		// $this->datatables->add_column('aksi_baru', '
		// 	<a href="' . site_url('Permintaan_dana_lapangan/detail_pd/$1/$2/$3/$4') . '" class="btn btn-sm btn-success " ><i class="fa fa-eye"></i></a>

		// 	<a href="' . site_url('Permintaan_dana_lapangan/print_pd_lapangan/$1/$2/$3/$4') . '" class="btn btn-xs btn-link" style="text-decoration:underline;font-size:10px !important;" target="_blank" >PRINT <i class="fa fa-print"></i></a>
		// 	', 'LedgerNo,NoPD,JobNo,Alokasi');


		$this->datatables->edit_column('TglPD', '$1', 'TanggalSaya(TglPD)');
		$this->datatables->edit_column('TotalPD', '$1', 'uang_koma_genap_decimal_kedua(TotalPD)');
		$this->datatables->edit_column('TimeApprovedKT', '$1', 'TanggalSaya(TimeApprovedKT)');

		return $this->datatables->generate();
	}

	function aksi_approve_lapangan($where, $data)
	{


		$this->db->trans_start();

		$this->db->set($data)->where($where)->update('PdHdr');

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE)
		{
        	return false;
		}else{
			return true;
		}

		// $this->db->trans_begin();


		// $this->db->set($data);
		// $this->db->where($where);
		// $this->db->update('PdHdr');

		// if ($this->db->trans_status() === FALSE) {
		// 	$this->db->trans_rollback();
		// 	return false;
		// } else {
		// 	$this->db->trans_commit();
		// 	return true;
		// }
	}
}

/* End of file Approve_permintaan_dana_lapangan_model.php */
/* Location: ./application/models/Approve_permintaan_dana_lapangan_model.php */