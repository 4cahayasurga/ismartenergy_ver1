<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_DaftarHarga extends CI_Model {

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');

	}

	function dapatkan_list_harga_json()
	{


		$this->load->library('Datatables');
		$this->ekse_json();
		$this->datatables->select('LedgerNo, Uraian, Uom, Vol, HrgSatuan, TotalHarga, VendorNm, NoKO, TglKO, KategoriId, JobNo, JobNm ');
		$this->datatables->from('#tmp_daftar_harga');

		$this->datatables->edit_column('TglKO', '$1', 'tgl_baru_tahun_pendek(TglKO)');
		$this->datatables->edit_column('Vol', '$1', 'new_decimal(Vol)');
		$this->datatables->edit_column('HrgSatuan', '$1', 'UangBiasa(HrgSatuan)');
		$this->datatables->edit_column('TotalHarga', '$1', 'UangBiasa(TotalHarga)');

		return $this->datatables->generate();

		
	}


	private function ekse_json(){
		$Company = $this->Company;

		$query = $this->db->query("
			DROP TABLE IF EXISTS #tmp_daftar_harga
			CREATE TABLE #tmp_daftar_harga(
			LedgerNo bigint identity NOT NULL,
			Uraian nvarchar(255) NULL,
			Uom nvarchar(15) NULL,
			Vol decimal(10, 3) NULL,
			HrgSatuan money NULL,
			TotalHarga money NULL,
			VendorNm nvarchar(100) NULL,
			NoKO  nvarchar(15) NULL,
			TglKO date NULL,
			KategoriId nvarchar(30) NULL, 
			JobNo nvarchar(10) NULL,
			JobNm nvarchar(100) NULL,
		);
		TRUNCATE TABLE #tmp_daftar_harga

		INSERT INTO #tmp_daftar_harga (Uraian, Uom, Vol, HrgSatuan, TotalHarga, VendorNm, NoKO, TglKO, KategoriId, JobNo, JobNm)
			SELECT  A.Uraian, A.Uom, A.Vol, A.HrgSatuan, (A.Vol * A.HrgSatuan) AS 'TotalHarga',  D.VendorNm,  B.NoKO, B.TglKO, B.KategoriId, C.JobNo, C.JobNm  FROM KoDtl A  
			LEFT JOIN KoHdr B ON A.NoKO=B.NoKO  
			LEFT JOIN Job C ON B.JobNo=C.JobNo  
			LEFT JOIN Vendor D ON B.VendorId=D.VendorId  
			WHERE C.Company='$Company' ");



	}

	function get_data_hrg(){
		$Company = $this->Company;

		$query = $this->db->query("
			DROP TABLE IF EXISTS #tmp_daftar_harga_Excel
			CREATE TABLE #tmp_daftar_harga_Excel(
			LedgerNo bigint identity NOT NULL,
			Uraian nvarchar(255) NULL,
			Uom nvarchar(15) NULL,
			Vol decimal(10, 3) NULL,
			HrgSatuan money NULL,
			TotalHarga money NULL,
			VendorNm nvarchar(100) NULL,
			NoKO  nvarchar(15) NULL,
			TglKO date NULL,
			KategoriId nvarchar(30) NULL, 
			JobNo nvarchar(10) NULL,
			JobNm nvarchar(100) NULL,
		);
		TRUNCATE TABLE #tmp_daftar_harga_Excel

		INSERT INTO #tmp_daftar_harga_Excel (Uraian, Uom, Vol, HrgSatuan, TotalHarga, VendorNm, NoKO, TglKO, KategoriId, JobNo, JobNm)
			SELECT A.Uraian, A.Uom, A.Vol, A.HrgSatuan, (A.Vol * A.HrgSatuan) AS 'TotalHarga',  D.VendorNm,  B.NoKO, B.TglKO, B.KategoriId, C.JobNo, C.JobNm  FROM KoDtl A  
			LEFT JOIN KoHdr B ON A.NoKO=B.NoKO  
			LEFT JOIN Job C ON B.JobNo=C.JobNo  
			LEFT JOIN Vendor D ON B.VendorId=D.VendorId  
			WHERE C.Company='$Company' ORDER BY LedgerNo DESC ");
		$query = $this->db->query("SELECT * FROM #tmp_daftar_harga_Excel ");
		return $query;
	}

}

/* End of file Model_DaftarHarga.php */
/* Location: ./application/models/MyReport_model/Model_DaftarHarga.php */