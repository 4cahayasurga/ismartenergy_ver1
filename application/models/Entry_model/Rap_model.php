<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rap_model extends CI_Model {

	function get_versi($JobNo,$Alokasi){
		$query = $this->db->query("SELECT TOP 1 Versi FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi'")->row_array();
		return $query['Versi'];
		
	}
	
	function aksi_simpan_RAP_baru($data){

		$JobNo = $data['JobNo'];
		$Alokasi = $data['Alokasi'];
		$Header = $data['Header'];
		$Tipe = $data['Tipe'];

		$this->db->insert('RAP',$data);

		if($Tipe == 'Detail'){
			$validasi_rap = $this->ubah_total_RAP($data);
		}
		return ($this->db->affected_rows() != 1) ? false : true;
		// return true;
		
	}

	private function ubah_total_RAP($data){

		$JobNo = $data['JobNo'];
		$Alokasi = $data['Alokasi'];
		$KdRAP = $data['Header'];
		$Tipe = $data['Tipe'];

		$query = $this->db->query("
			SELECT SUM(HrgSatuan*Vol) as TotalRAP, SUM(HrgRAB*Vol) as TotalRAB FROM RAP WHERE JobNo='$JobNo' AND Header='$KdRAP' AND Alokasi='$Alokasi'
			")->row_array();
		
		$TotalRAP = $query['TotalRAP'];
		$TotalRAB = $query['TotalRAB'];

		$this->db->query("UPDATE RAP SET HrgSatuan='$TotalRAP',HrgRAB='$TotalRAB' WHERE JobNo='$JobNo' AND KdRAP='$KdRAP' AND Alokasi='$Alokasi' ");


		$myquery = $this->db->query("SELECT TOP 1 Header FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND KdRAP='$KdRAP' ");
		if($myquery->num_rows() > 0){
			$row = $myquery->row_array();
			$data['Header'] = $row['Header'];
		}

		if($data['Header'] == '0'){
			exit;
		}else{
			$this->ubah_total_RAP($data);
		}
	}


	function aksi_edit_rap($data,$where){
		$this->db->trans_begin();

		$this->db->set($data)->where($where)->update('RAP');


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function M_DapatDataRAP($LedgerNo){
		return $this->db->query("
			SELECT a.*,(SELECT JobNm FROM Job WHERE JobNo=a.JobNo) as JobNm,(SELECT Keterangan FROM Alokasi WHERE Alokasi=a.Alokasi) as KeteranganAlokasi FROM RAP a WHERE a.LedgerNo='$LedgerNo'
			")->row_array();

	}


	// function Cek_Header_Sudah_Ada_Detail($where){

	// 	$query = $this->db->select('KdRAP')
	// 	->where($where)
	// 	->get('RAP')->num_rows();

	// 	return $query;

	// }

	function Query_Get_Data_RAP($LedgerNo){
		$this->db->select('a.*,(SELECT JobNm FROM Job WHERE JobNo=a.JobNo) as JobNm,(SELECT Keterangan FROM Alokasi WHERE Alokasi=a.Alokasi) as AlokasiKeterangan ');
		$this->db->where('LedgerNo',$LedgerNo);
		return $this->db->get('RAP a')->row_array();
	}


	function CekHeaderAdaDetail_model($where){

		$JobNo = $where['JobNo'];
		$Alokasi = $where['Alokasi'];
		$KdRAP = $where['KdRAP'];
		$Tipe = $where['Tipe'];


		return $this->db->query("SELECT KdRAP FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Header='$KdRAP' ");
		
	}

	function Dapat_List_Header($where){
		$JobNo = $where['JobNo'];
		$Alokasi = $where['Alokasi'];
		return $this->db->query("SELECT KdRAP,Uraian FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Tipe='Header'");
	}

	private function Cek_RAP_Ada_PD($JobNo,$Alokasi,$KdRAP){
		$query = $this->db->query("SELECT * FROM PdHdr A 
			JOIN PdDtl B ON A.NoPD=B.NoPD
			WHERE A.JobNo='$JobNo' AND A.Alokasi='$Alokasi' AND A.RejectBy IS NULL AND B.KdRAP='$KdRAP'");

		return $query->num_rows();
	}

	function LakukanEditRAP_model(){

		// $this->db->trans_begin();

		$status_rap = $this->input->post('status_rap',TRUE);
		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);


		$Tipe = $this->input->post('Tipe',TRUE);
		$Header = $this->input->post('Header',TRUE);
		$KdRAP = $this->input->post('KdRAP',TRUE);
		$Uraian = $this->input->post('Uraian',TRUE);
		$Uom = $this->input->post('Uom',TRUE);
		$Vol = $this->input->post('Vol',TRUE);
		$HrgSatuan= $this->input->post('HrgSatuan',TRUE);
		$HrgRAB = $this->input->post('HrgRAB',TRUE);
		$Versi = $this->input->post('Versi',TRUE);
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');
		

		$mydata = array(
			'Tipe' => $Tipe,
			'Header' => $Header,
			'KdRAP' => $KdRAP,
			'Uraian' => $Uraian,
			'Uom' => $Uom,
			'Vol' => $Vol,
			'HrgSatuan' => HapusFormatUang($HrgSatuan),
			'HrgRAB' => HapusFormatUang($HrgRAB),
			'Versi' => $Versi,
			'JobNo' => $JobNo,
			'Alokasi' => $Alokasi,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry
		);




		if ($status_rap == 1 && $Tipe=='Header' && $Header == 0 ) {

			$my_array = array(
				'Uraian' => $mydata['Uraian'],
				'UserEntry' => $mydata['UserEntry'],
				'TimeEntry' => $mydata['TimeEntry']
			);

			return $this->db->set($my_array)->where('LedgerNo',$LedgerNo)->update('RAP');

		}

		if ($status_rap == 0 && $Tipe=='Header' && $Header == 0 ) {

			unset($mydata['Vol']);
			unset($mydata['HrgSatuan']);
			unset($mydata['HrgRAB']);

			return $this->db->set($mydata)->where('LedgerNo',$LedgerNo)->update('RAP');

		}



		return 'no';


	}

	function UpdateRAP($data){
		$Tipe = $data['Tipe'];
		$LedgerNo = $data['LedgerNo'];
		

		if ($Tipe == 'Header') {

			$myarray = array(
				'Uraian' => $data['Uraian'],
				'TimeEntry' => $data['TimeEntry'],
				'UserEntry' => $data['UserEntry'],
			);
			$Uraian = $data['Uraian'];
			$this->db->query("UPDATE RAP SET Uraian='$Uraian' WHERE LedgerNo='LedgerNo' ");
			// $this->db->set($myarray)->where('LedgerNo',$LedgerNo)->update('RAP');
			return 'ppp';	
			

		}
		return $this->db->affected_rows();



	}

	private function getLen($var){
		$tmp = explode('.', $var);
		if(count($tmp)>1){
			return strlen($tmp[1]);
		}
	}

	function new_edit_rap(){
		$Tipe = $this->input->post('Tipe',TRUE);
		$Header = $this->input->post('Header',TRUE);
		$KdRAP = $this->input->post('KdRAP',TRUE);
		$Uraian = $this->input->post('Uraian',TRUE);
		$Uom = $this->input->post('Uom',TRUE);
		$Vol = $this->input->post('Vol',TRUE);
		$HrgSatuan = $this->input->post('HrgSatuan',TRUE);
		$PPN = $this->input->post('PPN',TRUE);
		$HrgRAB = $this->input->post('HrgRAB',TRUE);

		$LedgerNo = $this->input->post('LedgerNo',TRUE);
		$Versi = $this->input->post('Versi',TRUE);
		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);

		$NoUrut = $this->input->post('NoUrut',TRUE);
		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		
		// $Vol = str_replace("," , "" , $Vol);
		// $HrgSatuan = str_replace("," , "" , $HrgSatuan);
		// $HrgRAB = str_replace("," , "" , $HrgRAB);

		// $Vol = number_format($Vol, $this->getLen($Vol));
		// $HrgSatuan = number_format($HrgSatuan, $this->getLen($HrgSatuan));
		// $HrgRAB = number_format($HrgRAB, $this->getLen($HrgRAB));

		// $Vol = number_format($Vol, $this->getLen($Vol));
		// $HrgSatuan = number_format($HrgSatuan, $this->getLen($HrgSatuan));
		// $HrgRAB = number_format($HrgRAB, $this->getLen($HrgRAB));


		$data = array(
			'Uraian' => $Uraian,
			'JobNo' => $JobNo,
			'KdRAP' => $KdRAP,
			'Versi' => $Versi,
			'NoUrut' =>$NoUrut,
			'Alokasi' => $Alokasi,
			'Tipe' => $Tipe,
			'Header' => $Header,
			'Uom' => $Uom,
			'Vol' => $Vol,
			'HrgSatuan' => $HrgSatuan,
			'PPN' => $PPN,
			'HrgRAB' => $HrgRAB,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry
		);


		if ($Tipe == 'Header') {
			$Uraian_Header = $data['Uraian'];
			$UserEntry_Header = $data['UserEntry'];
			$TimeEntry_Header = $data['TimeEntry'];

			$array_edit_rap = array(
				'Uraian' => $data['Uraian'],
				'UserEntry' => $data['UserEntry'],
				'TimeEntry' => $data['TimeEntry'],
			);

			$this->db->set($array_edit_rap)->where('LedgerNo',$LedgerNo)->update('RAP');

		}


		if ($Tipe == 'Detail') {
			unset($data['Tipe']);
			unset($data['Header']);
			unset($data['Alokasi']);
			unset($data['KdRAP']);

			$this->db->set($data)->where('LedgerNo',$LedgerNo)->update('RAP');
		}

		return $this->db->affected_rows();



	}







}

/* End of file Rap_model.php */
/* Location: ./application/models/Entry_model/Rap_model.php */