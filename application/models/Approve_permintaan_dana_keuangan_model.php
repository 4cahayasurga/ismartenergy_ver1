<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approve_permintaan_dana_keuangan_model extends CI_Model {

	function dapat_list_pd_json($where,  $dari_tgl, $sampai_tgl)
	{
		$this->load->library('Datatables');

		$JobNo = $where['JobNo'];
		$Alokasi = $where['Alokasi'];


		$this->datatables->select("
			LedgerNo,
			NoPD,
			NoRef,
			CASE
				WHEN NoRef IS NULL THEN 'noref_kosong'
				WHEN NoRef='' THEN 'noref_kosong'
				ELSE 'noref_ada'
			END AS 'NoRef_validasi',
			TglPD,
			Alokasi,
			TipeForm,
			NoKO,
			Deskripsi,
			TotalPD,
			ApprovedByKK,
			TimeApprovedKK,
			ApprovedByKT,
			TimeApprovedKT,
			TimeEntry,
			JobNo


			");
		$this->datatables->from('PdHdr');
		// $this->datatables->where($where);
		$this->datatables->where('JobNo',$JobNo);
		
		if ($Alokasi <> '') {
			$this->datatables->where('Alokasi',$Alokasi);
		}

		$this->datatables->where('ApprovedByAK IS NOT NULL',NULL,FALSE);
		$this->datatables->where('ApprovedByKT IS NOT NULL',NULL,FALSE);

		if ($dari_tgl !== '' && $sampai_tgl !== '') {
			$this->datatables->where('TglPD >=', $dari_tgl);
			$this->datatables->where('TglPD <=', $sampai_tgl);
		}

		$url = '';
		$url = ($dari_tgl == '') ? '/0' : '/'.$dari_tgl;
		$url .= ($sampai_tgl == '') ? '/0' : '/'.$sampai_tgl;



		// $this->datatables->add_column('aksi_baru', '
		// 	<a href="' . site_url('Permintaan_dana_keuangan/detail_pd/$1/$2/$3/$4') . '" class="btn btn-xs btn-link " style="text-decoration:underline;font-size:10px !important;">SELECT</a>

		// 	<a href="' . site_url('Permintaan_dana_keuangan/print_pd_keuangan/$1/$2/$3/$4') . '" class="btn btn-xs btn-link" style="text-decoration:underline;font-size:10px !important;" target="_blank" >PRINT <i class="fa fa-print"></i></a>
		// 	', 'LedgerNo,NoPD,JobNo,Alokasi');

		$this->datatables->add_column('ViewPDKeuangan','<a href="'.site_url("Permintaan_dana_keuangan/ViewPdKeuangan/$1/$2/$3").$url.'" class="btn btn-sm" style="background-color:#009432;color:white;" title="Lihat PD"><i class="fa fa-eye"></i></a>','LedgerNo,JobNo,Alokasi');

		$this->datatables->add_column('ViewPDLapangan','','');

		// $this->datatables->add_column('pdlap_pdf','<a href="' . site_url('Permintaan_dana_keuangan/print_pd_keuangan/$1/$2/$3/$4') . '" class="btn btn-sm" style="background-color:#c0392b;color:white;" title="PRINT"><i class="fa fa-print"></a>','LedgerNo,NoPD,JobNo,Alokasi');




		$this->datatables->edit_column('TglPD', '$1', 'TanggalSaya(TglPD)');
		$this->datatables->edit_column('TotalPD', '$1', 'uang_koma_genap_decimal_kedua(TotalPD)');
		$this->datatables->edit_column('TimeApprovedKK', '$1', 'TanggalSaya(TimeApprovedKK)');
		// $this->datatables->edit_column('LedgerNo,NoPD,JobNo,Alokasi', '$1,$2,$3,$4', '$this->bedakan_PD_keuangan_dan_lapangan(LedgerNo,NoPD,JobNo,Alokasi)');

		return $this->datatables->generate();
	}

	private function bedakan_PD_keuangan_dan_lapangan($LedgerNo,$NoPD,$JobNo,$Alokasi){

		if ($LedgerNo == '' || $JobNo == '' ) {
			return 'terjadi kesalahan';
		}

		return 'aaa';

	}

	function aksi_approve_keuangan($where,$data)
    {
        $this->db->trans_begin();

        
        $this->db->set($data);
        $this->db->where($where);
        $this->db->update('PdHdr');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

}

/* End of file Approve_permintaan_dana_keuangan_model.php */
/* Location: ./application/models/Approve_permintaan_dana_keuangan_model.php */