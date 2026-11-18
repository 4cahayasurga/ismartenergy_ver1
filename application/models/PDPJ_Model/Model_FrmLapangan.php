<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_FrmLapangan extends CI_Model {

	var $PDStatus;
	var $Company;

	function __construct()
	{
		parent::__construct();
		$this->PDStatus = 'Lapangan';
		$this->Company = $this->config->item('Company');
	}

	function TipeForm_DB($Alokasi){
		if ($this->PDStatus == 'Lapangan') {
			$query = $this->db->query("SELECT TipeForm,Keterangan FROM TipeForm WHERE Alokasi='$Alokasi' AND TipeForm!='05B' ")->result();
		}else{
			$query = $this->db->query("SELECT TipeForm,Keterangan FROM TipeForm WHERE Alokasi='$Alokasi'  ")->result();
		}
		
		return $query;
	}

	function PembuatanNoRef_Lapangan($JobNo,$Alokasi){
		$LikeNoRef = "L".$JobNo.$Alokasi."%"; 
		$query = $this->db->query("SELECT TOP 1 NoRef FROM PdHdr WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND NoRef LIKE '$LikeNoRef' ORDER BY NoRef DESC ");
		$Kombinasi = 'L'.$JobNo.$Alokasi;
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$NoUrut = substr($row['NoRef'],-5) + 1;
			$NoUrut = sprintf("%05s", $NoUrut);
			$Kombinasi = $Kombinasi.''.$NoUrut;
		}else{
			$Kombinasi = $Kombinasi.'00001';
		}

		return $Kombinasi;


	}

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