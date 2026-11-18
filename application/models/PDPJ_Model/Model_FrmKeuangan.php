<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_FrmKeuangan extends CI_Model {

	function dapatkan_hari_senin_atau_monday ($TglPD=''){

		$TglPD_nama_hari = date('l', strtotime($TglPD));
		$TglPD_tanggal = ($TglPD_nama_hari == 'Sunday') ? date('Y-m-d',strtotime('-1 days',strtotime($TglPD))) : date('Y-m-d',strtotime('+1 days',strtotime($TglPD)));
		$PrdAwal_minggu = date('Y-m-d', strtotime('previous monday',strtotime($TglPD_tanggal)));
		$PrdAkhir_minggu = date('Y-m-d',strtotime('+6 days',strtotime($PrdAwal_minggu)));
		return array('PrdAwal_minggu' => $PrdAwal_minggu, 'PrdAkhir_minggu' => $PrdAkhir_minggu);
	}

	function hitung_jumlah_minggu($JobNo,$PrdAkhir_minggu){

		$dapat_hasil = '';
		$query_get_PrdAwal = $this->db->query("SELECT PrdAwal FROM Job WHERE JobNo='$JobNo' ")->row();
		$PrdAwal_minggu = $query_get_PrdAwal->PrdAwal;
		if ($PrdAwal_minggu == NULL || $PrdAwal_minggu == '' ) {
			$dapat_hasil = 0;
			return $dapat_hasil;
		}

		$diff = strtotime($PrdAkhir_minggu, 0) - strtotime($PrdAwal_minggu, 0);
		$diff = floor($diff / 604800) + 1;
		$dapat_hasil = $diff;
		return $dapat_hasil;
	}

	function ListBank(){
		return $this->db->query("SELECT * FROM Bank")->result();
	}

}

/* End of file Model_FrmKeuangan.php */
/* Location: ./application/models/Model_FrmKeuangan.php */