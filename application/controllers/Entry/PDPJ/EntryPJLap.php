<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EntryPJLap extends CI_Controller {

	var $Company;
	var $PJ_Status;
	var $Url_Batal;

	var $Action_Global;
	var $JobNo_Global;
	var $NoPD_Global;
	var $Alokasi_Global;
	var $UrlDari_Global;
	
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		PembuatanInvoiceSementara(); 
		tbl_NewInvoice();
		PembuatanTable_PdDtl_Temp_Keuangan();
		PembuatanTable_Invoice_Temp_Keuangan();

		// == START ADD PD_PPN,23 Nov 2023.
		PenambahanField_PD_PPN();
		// PenambahanField_InputDari();
		// == END ADD PD_PPN,23 Nov 2023.

		// if ($this->session->userdata('PecahToken')->PJ!=1) {
		// 	redirect('Welcome');
		// }

		$this->PJ_Status='Lapangan';

		// $this->Url_Batal = site_url('Entry/PDPJ/'.$this->router->fetch_class());

		if ($this->session->userdata('SesiEntryPJ')) {
			$this->Action_Global 	= explode('|', $this->session->userdata('SesiEntryPJ'))[0];
			$this->JobNo_Global	= explode('|', $this->session->userdata('SesiEntryPJ'))[1];
			$this->NoPD_Global 	= explode('|', $this->session->userdata('SesiEntryPJ'))[2];
			$this->Alokasi_Global = explode('|', $this->session->userdata('SesiEntryPJ'))[3];
			$this->UrlDari_Global = explode('|', $this->session->userdata('SesiEntryPJ'))[4];
		}

		// -- DITAMBAHKAN TANGGAL : 13 DES 2023
		PembuatanTable_PJCadangan();

	}

	function index(){

		if ($this->JobNo_Global=='' || $this->Alokasi_Global=='' || $this->NoPD_Global=='') {
			redirect($_SERVER['HTTP_REFERER']);
		}

		$data['title'] 			= 'Entry Pertanggungjawaban Dana (Lapangan)';
		$data['judul'] 			= 'Entry Pertanggungjawaban Dana (Lapangan)';
		$data['atur_kiri'] 		= '1';
		$data['bodyclass'] 		= 'sidebar-collapse skin-red-light';
		$data['js_saya'] 		= 'entry_view/PDPJ_View/EntryPJLap_VIEW/js_form_entry_PJLap';

		$data['JobNo'] 			= $this->JobNo_Global;
		$data['Alokasi'] 		= $this->Alokasi_Global;
		$data['NoPD'] 			= $this->NoPD_Global;

		$data['Url_Batal'] 		= "";

		if ($this->UrlDari_Global=='FrmPJLap') {
			$data['Url_Batal'] 	= "FrmPJLap/view/".$this->JobNo_Global.'/'.$this->Alokasi_Global;
		}

		$data['rowPD'] 			= $this->getPdHdr();
		$this->PerpindahanData_PdDtl_to_PJCadangan();

		$data['btn_dimatikan'] = ($this->Action_Global=='SEE') ? 'disabled' : '';

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('entry_view/PDPJ_View/EntryPJLap_VIEW/form_entry_PJLap', $data);
		$this->load->view('templates/footer');
	}

	function HurufKosong_Validasi($dataparam,$flag=''){
		if ($flag=='tanggal') {
			return ($dataparam=='' || $dataparam==NULL || $dataparam=='1900-01-01' ) ? '' : $dataparam;
		}else{
			return ($dataparam=='' || $dataparam==NULL) ? '' : $dataparam;
		}
		
	}

	function getPdHdr(){
		$getDataPD = $this->db->query("SELECT *, (SELECT ISNULL(JobNm,NULL) FROM Job WHERE JobNo='$this->JobNo_Global') AS 'JobNm', (SELECT ISNULL(Keterangan,NULL) FROM Alokasi WHERE Alokasi='$this->Alokasi_Global') AS 'KeteranganAlokasi', (SELECT ISNULL(Keterangan,NULL) FROM TipeForm WHERE Alokasi='$this->Alokasi_Global' AND TipeForm=PdHdr.TipeForm) AS 'KeteranganTipeForm' FROM PdHdr WHERE NoPD='$this->NoPD_Global' ")->row();

		return $getDataPD;
	}

	function PerpindahanData_PdDtl_to_PJCadangan(){
		$this->db->trans_begin();
		try {
			
			$this->db->query("DELETE FROM PJCadangan WHERE NoPD='$this->NoPD_Global'  ");

			$myquery = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$this->NoPD_Global' ");
			if ($myquery->num_rows() > 0) {
				$myarray = array();
				foreach ($myquery->result() as $row) {
					$myarray[] = array(
						'NoPD' => CekNull_Huruf($row->NoPD),
						'NoUrut' => (int) $row->NoUrut,
						'KdRAP' => CekNull_Huruf($row->KdRAP),
						'Uraian' => CekNull_Huruf($row->Uraian),
						'Vol' => CekNull_Angka_Dan_Bikin_Float($row->Vol),
						'Uom' => CekNull_Huruf($row->Uom),
						'HrgSatuan' => CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan),
						'UserEntry' => $row->UserEntry,
						'TimeEntry' => $row->TimeEntry,
						'NoPJ' => CekNull_Huruf($row->NoPJ),
						'PjUraian' => CekNull_Huruf($row->PjUraian),
						'PjVol' => CekNull_Angka_Dan_Bikin_Float($row->PjVol),
						'PjHrgSatuan' => CekNull_Angka_Dan_Bikin_Float($row->PjHrgSatuan),
						'PjUserEntry' => $row->PjUserEntry,
						'PjTimeEntry' => $row->PjTimeEntry,
						'Company' => $this->Company,
						'NewRecord' => 'No',
					);
				}
				$this->db->insert_batch('PJCadangan',$myarray);
			}

			$this->db->trans_commit();

		} catch (Exception $e) {
			$this->db->trans_rollback();

		}
	}

	function getPdDtl(){
		$getPdDtl = $this->db->query("SELECT * FROM PdDtl WHERE NoPD='$this->NoPD_Global' ORDER BY NoUrut")->result();
		return $getPdDtl;
		// $MyArray = array();
		// if ($getPdDtl->num_rows() > 0) {

		// 	foreach ($getPdDtl->result() as $row) {

		// 		$MyArray[] = (object) array();
		// 	}
		// }
	}

	function tampilkan_tablePJLap(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD = $this->input->post('NoPD');

		$data['NoPD'] = $NoPD;
		$ArrayBuatan = array();

		$ListPdDtl = $this->db->query("SELECT *,(ISNULL(PJVol,0) * ISNULL(PjHrgSatuan,0)) AS JumlahHarga FROM PJCadangan WHERE NoPD='$NoPD' ORDER BY NoUrut ASC  ")->result();
		$data['ListPdDtl'] = $ListPdDtl;

		$sumTotalPJ = 0;
		$query = $this->db->query("SELECT COALESCE(sum(PJVol * PjHrgSatuan),0) AS 'TotalPJ' FROM PJCadangan WHERE NoPD='$NoPD' GROUP BY NoPD  ");
		if ($query->num_rows()>0) {
			$sumTotalPJ = $query->row()->TotalPJ;
		}else{
			$sumTotalPJ = 0;
		}

		$data['sumTotalPJ'] = number_format($sumTotalPJ);

		$this->load->view('entry_view/PDPJ_View/EntryPJLap_VIEW/tablePJLap', $data);
	}

	function AddNewUraian(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD 			= $this->input->post('NoPD');
		$NoUrut 		= (int) $this->input->post('NoUrut');
		$KdRAP 			= CekNull_Huruf($this->input->post('KdRAP'));
		$PjUraian 		= CekNull_Huruf($this->input->post('Uraian'));
		$PjVol 			= CekDanHapusKoma($this->input->post('Vol'));
		$Uom 			= CekNull_Huruf($this->input->post('Uom'));
		$PjHrgSatuan 	= CekNull_Huruf($this->input->post('HrgSatuan'));	

		
		$this->db->trans_begin();
		try {
			
			$data = array(
				'NoPD' 			=> $NoPD,
				'NoUrut' 		=> $NoUrut, 
				'KdRAP' 		=> $KdRAP,
				'PjUraian' 		=> $PjUraian,
				'PjVol' 		=> $PjVol,
				'Uom' 			=> strtoupper($Uom),
				'PjHrgSatuan' 	=> $PjHrgSatuan,
				'PjUserEntry' 	=> $this->session->userdata('MIS_LOGGED_NAME'),
				'PjTimeEntry' 	=> date('Y-m-d H:i:s'),
				'NewRecord' 	=> 'Yes',
			);
			$this->db->insert('PJCadangan',$data);

			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}

	}

	function UpdateUraian(){
		if (!$this->input->post()) {
			die;
		}

		// print_rr($_POST);
		// die;

		$NoPD 			= $this->input->post('NoPD');
		$NoUrut 		= (int) $this->input->post('NoUrut');

		$PjUraian 		= CekNull_Huruf($this->input->post('PjUraian'));
		$PjVol 			= CekDanHapusKoma($this->input->post('PjVol'));
		$PjHrgSatuan 	= CekDanHapusKoma($this->input->post('PjHrgSatuan'));

		
		$this->db->trans_begin();
		try {

			$set = array(
				'PjUraian' 		=> $PjUraian,
				'PjVol' 		=> $PjVol,
				'PjHrgSatuan' 	=> $PjHrgSatuan,
				'PjUserEntry' 	=> $this->session->userdata('MIS_LOGGED_NAME'),
				'PjTimeEntry' 	=> date('Y-m-d H:i:s'),
			);
			
			$where = array(
				'NoPD' 			=> $NoPD,
				'NoUrut' 		=> $NoUrut, 
			);

			// print_rr($where);

			$this->db->set($set)->where($where)->update('PJCadangan');

			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}

	}
	function Aksi_UraianSamaDenganPD(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD 			= $this->input->post('NoPD');
		
		$this->db->trans_begin();
		try {

			$query = $this->db->query("SELECT * FROM PJCadangan WHERE NoPD='$NoPD' ");
			if ($query->num_rows() > 0) {

				$myarray = array();

				foreach ($query->result() as $row) {
					$Uraian = CekNull_Huruf($row->Uraian);
					$Vol = CekNull_Angka_Dan_Bikin_Float($row->Vol);
					$HrgSatuan = CekNull_Angka_Dan_Bikin_Float($row->HrgSatuan);
					$NoUrut = (int) $row->NoUrut;

					$set = array(
						'PjUraian' => $Uraian,
						'PjVol' => $Vol,
						'PjHrgSatuan' => $HrgSatuan,
					);

					$where = array('NoPD' => $NoPD, 'NoUrut' => $NoUrut);

					$this->db->set($set)->where($where)->update('PJCadangan');
				}
			}

			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}

	}

	function CekBuktiPendukung($param_BuktiPendukung=""){
		$ListBuktiPendukung = "";
		
		$ListBuktiPendukung = ($this->input->post('INV')) ? "INV" : "";
		$ListBuktiPendukung = ($this->input->post('SJ')) ? ($ListBuktiPendukung=="") ? "SJ" : $ListBuktiPendukung.",SJ" : $ListBuktiPendukung;
		$ListBuktiPendukung = ($this->input->post('PO')) ? ($ListBuktiPendukung=="") ? "PO" : $ListBuktiPendukung.",PO" : $ListBuktiPendukung;
		$ListBuktiPendukung = ($this->input->post('FP')) ? ($ListBuktiPendukung=="") ? "FP" : $ListBuktiPendukung.",FP" : $ListBuktiPendukung;
		$ListBuktiPendukung = ($this->input->post('BAP')) ? ($ListBuktiPendukung=="") ? "BAP" : $ListBuktiPendukung.",BAP" : $ListBuktiPendukung;
		$ListBuktiPendukung = ($this->input->post('BAOP')) ? ($ListBuktiPendukung=="") ? "BAOP" : $ListBuktiPendukung.",BAOP" : $ListBuktiPendukung;

		return $ListBuktiPendukung;
	}

	function UpdatePD_To_Database(){
		if (!$this->input->post()) {
			die;
		}

		$NoPD 			= $this->input->post('NoPD');
		$NoPJ 			= $this->input->post('NoPJ');
		$TglPJ 			= $this->input->post('TglPJ');
		$TotalPJ 		= CekDanHapusKoma($this->input->post('TotalPJ'));
		$BuktiPendukung = $this->CekBuktiPendukung($this->input->post());
		$Saldo 			= CekDanHapusKoma($this->input->post('Saldo'));
		$PjUserEntry 	= $this->session->userdata('MIS_LOGGED_NAME');
		$PjTimeEntry 	= date('Y-m-d H:i:s');


		$this->db->trans_begin();
		try {


			// -- Update table di PdHdr
			$set_PdHdr = array(
				'NoPJ' => $NoPJ,
				'TglPJ' => $TglPJ,
				'TotalPJ' => $TotalPJ,
				'BuktiPendukung' => $BuktiPendukung,
				'Saldo' => $Saldo,
				'PjUserEntry' => $PjUserEntry,
				'PjTimeEntry' => $PjTimeEntry,
			);
			$this->db->set($set_PdHdr)->where(array('NoPD' => $NoPD))->update('PdHdr');


				// -- update dan insert pada table PdDtl
			$myarray = array();
			foreach($this->input->post('NoUrut_table') as $key => $val) :
				$NoUrut 		= (int) $val;
				$KdRAP 			= $this->input->post('KdRAP_table')[$key];
				$PjUraian 		= $this->input->post('PjUraian_table')[$key];
				$PjVol 			= $this->input->post('PjVol_table')[$key];
				$Uom 			= $this->input->post('Uom_table')[$key];
				$PjHrgSatuan 	= $this->input->post('PjHrgSatuan_table')[$key];
				$NewRecord 		= $this->input->post('NewRecord_table')[$key];

				$PjVol 			= CekDanHapusKoma($PjVol);
				$PjHrgSatuan 	= CekDanHapusKoma($PjHrgSatuan);

				if ($NewRecord=='Yes') {

					$array_simpan = array(
						'NoPD' 			=> $NoPD,
						'NoUrut' 		=> $NoUrut,
						'KdRAP' 		=> $KdRAP, 
						'NoPJ' 			=> $NoPJ, 
						'PjUraian' 		=> $PjUraian, 
						'PjVol' 		=> $PjVol, 
						'Uom' 			=> $Uom, 
						'PjHrgSatuan' 	=> $PjHrgSatuan, 
						'PjUserEntry' 	=> $PjUserEntry,
						'PjTimeEntry' 	=> $PjTimeEntry,
					);

					$this->db->insert('PdDtl',$array_simpan);

				}else{

					$array_set = array(
						'NoPJ' 			=> $NoPJ,
						'PjUraian'		=> $PjUraian,
						'PjVol' 		=> $PjVol,
						'PjHrgSatuan' 	=> $PjHrgSatuan,
						'PjUserEntry' 	=> $PjUserEntry,
						'PjTimeEntry' 	=> $PjTimeEntry,
					);

					$array_where = array(
						'NoPD' 			=> $NoPD,
						'NoUrut'		=> $NoUrut,
					);

					$this->db->set($array_set)->where($array_where)->update('PdDtl');

				}

			endforeach;

			$this->db->query("DELETE PJCadangan WHERE NoPD='$NoPD' ");

			is_pesan('success','Sukses, data telah di-Update');
			echo 'berhasil';
			$this->db->trans_commit();

		} catch (Exception $e) {
			echo 'gagal';
			$this->db->trans_rollback();

		}

		// print_rr($myarray);
	}
	// function Assign

}

/* End of file EntryPJLap.php */
/* Location: ./application/controllers/Entry/PDPJ/EntryPJLap.php */