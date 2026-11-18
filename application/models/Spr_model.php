<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spr_model extends CI_Model
{


	public function __construct()
	{
		parent::__construct();
		$this->load->library('Datatables');
	}

	public function get_spr_table($JobNo = '')
	{

		$this->datatables->select('
			NoSPR,
			TglSPR,
			UtkPekerjaan,
			TglPenggunaan,
			ApprovedBy,
			TimeApproved,JobNo');
		$this->datatables->from('PRHdr');
		if ($JobNo != '') {
			$this->datatables->where('JobNo', $JobNo);
			
		}
		$this->datatables->add_column('view', '<a href="' . site_url('Spr/myedit/$1/' . $JobNo) . '"  data-toggle="tooltip" data-placement="top" class="btn btn-xs btn-warning waves-effect waves-light" title="Edit Data"><i class="fa fa-pencil" ></i></a> ', 'NoSPR');
		$this->db->order_by('TimeEntry', 'DESC');
		// $this->datatables->edit_column('HargaPerolehan', '$1', 'GetFormatNumber(HargaPerolehan)');
		// $this->datatables->edit_column('Um', '$1', 'GetFormatNumber(Um)');
		// $this->datatables->edit_column('Kpr', '$1', 'GetFormatNumber(Kpr)');
		// $this->datatables->edit_column('NilaiAppraisal', '$1', 'GetFormatNumber(NilaiAppraisal)');
		// $this->datatables->edit_column('PokokTerbayar', '$1', 'GetFormatNumber(PokokTerbayar)');
		// $this->datatables->edit_column('SisaPokok', '$1', 'GetFormatNumber(SisaPokok)');
		// $this->datatables->edit_column('Angsuran', '$1', 'GetFormatNumber(Angsuran)');
		// $this->datatables->edit_column('Estimasi', '$1', 'CekEstimasi(Estimasi)');
		return $this->datatables->generate();
	}

	public function simpan_detail($data)
	{
		$this->db->trans_begin();
		$UserEntry = $data['UserEntry'];

		$NoUrut = 1;

		$cekurut = $this->db->query(" SELECT TOP 1 * FROM PRDtlTemp WHERE UserEntry='$UserEntry'  ORDER BY NoUrut DESC ");
		if ($cekurut->num_rows() > 0) {
			$row = $cekurut->row();
			$NoUrut = $row->NoUrut + 1;
		} else {
			$NoUrut = 1;
		}

		$data['NoUrut'] = $NoUrut;

		$this->db->insert('PRDtlTemp', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function hapus_detail($where)
	{
		$this->db->trans_begin();
		$this->db->where($where)->delete('PRDtlTemp');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function edit_detail($where, $data)
	{
		$this->db->trans_begin();

		$this->db->set($data)->where($where)->update('PRDtlTemp');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function simpanSpr($data)
	{
		$this->db->trans_begin();
		$NoSPR = $data['NoSPR'];
		$UserEntry = $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry = date('Y-m-d H:i:s');

		$simpan = $this->db->insert('PRHdr', $data);
		$edit = $this->db->set(array('NoSPR' => $NoSPR))->where('UserEntry', $UserEntry)->update('PRDtlTemp');

		$query_saya = $this->db->query("SELECT NoSPR,NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,UserEntry,TimeEntry,HrgSatuanRAP,Total,TotalRAP,VolRAP FROM PRDtlTemp WHERE NoSPR='$NoSPR'  ");

		if ($query_saya->num_rows() > 0) {
			foreach($query_saya->result() as $row ):
				$NoSPR = CekNULL_KhususString_Terbaru($row->NoSPR);
				$NoUrut = (int) $row->NoUrut;
				$Alokasi = CekNULL_KhususString_Terbaru($row->Alokasi);
				$KdRAP = CekNULL_KhususString_Terbaru($row->KdRAP);
				$Uraian = CekNULL_KhususString_Terbaru($row->Uraian);
				$Vol = CekNull_Angka_Dan_Bikin_Float($row->Vol);
				$Uom = CekNULL_KhususString_Terbaru($row->Uom);

				$HrgSatuanRAP = CekNull_Angka_Dan_Bikin_Float($row->HrgSatuanRAP);
				$Total = CekNull_Angka_Dan_Bikin_Float($row->Total);
				$TotalRAP = CekNull_Angka_Dan_Bikin_Float($row->TotalRAP);
				$VolRAP = CekNull_Angka_Dan_Bikin_Float($row->VolRAP);

				$VersiRAP = PengecekanVersiRAP($KdRAP,$data['JobNo'],$Alokasi);

				$this->db->query("INSERT INTO PRDtl (
					NoSPR,
					NoUrut,
					Alokasi,
					KdRAP,
					Uraian,
					Vol,
					Uom,
					UserEntry,
					TimeEntry,
					HrgSatuan,
					Total,
					TotalRAP,
					VolRAP,
					VersiRAP) VALUES (
					'$NoSPR', 
					'$NoUrut', 
					'$Alokasi', 
					'$KdRAP', 
					'$Uraian', 
					'$Vol', 
					'$Uom', 
					'$UserEntry', 
					'$TimeEntry', 
					'$HrgSatuanRAP', 
					'$Total', 
					'$TotalRAP', 
					'$VolRAP',
					'$VersiRAP') ");


			endforeach;
		}

		$query_hapus = $this->db->query(" DELETE FROM PRDtlTemp WHERE NoSPR='$NoSPR' ");

		// $myquery = $this->db->query("
		// 	INSERT INTO PRDtl ( NoSPR,NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,UserEntry,TimeEntry,HrgSatuan,Total,TotalRAP,VolRAP )

		// 	SELECT NoSPR,NoUrut,Alokasi,KdRAP,Uraian,Vol,Uom,UserEntry,TimeEntry,HrgSatuanRAP,Total,TotalRAP,VolRAP
		// 	FROM PRDtlTemp
		// 	WHERE NoSPR='$NoSPR'  ");

		// $query_hapus = $this->db->query(" DELETE FROM PRDtlTemp WHERE NoSPR='$NoSPR' ");


		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function getDataSpr($JobNo, $NoSPR)
	{
		$where = array(
			'JobNo' => $JobNo,
			'NoSPR' => $NoSPR
		);

		return $this->db->where($where)->get('PRHdr');
	}

	function getKdRAP($where)
	{



		$this->db->select('KdRAP,Uraian,Tipe');
		$this->db->where($where);
		$this->db->order_by('NoUrut', 'ASC');
		return $this->db->get('RAP');
	}

	function getFromRAP($where)
	{
		$this->db->select(' a.Vol,a.HrgSatuan ');
		$this->db->from('RAP a');
		$this->db->where($where);
		return $this->db->get();
	}

	function getFromRAPEdit($where)
	{
		$this->db->select(' a.Vol,a.HrgSatuan ');
		$this->db->from('RAP a');
		$this->db->where($where);
		return $this->db->get();
	}


	public function simpan_detail_in_edit($data)
	{
		$this->db->trans_begin();
		$UserEntry = $data['UserEntry'];

		$NoUrut = 1;

		$cekurut = $this->db->query(" SELECT TOP 1 * FROM PRDtl WHERE UserEntry='$UserEntry' ORDER BY NoUrut DESC ");
		if ($cekurut->num_rows() > 0) {
			$row = $cekurut->row();
			$NoUrut = $row->NoUrut + 1;
		} else {
			$NoUrut = 1;
		}

		$data['NoUrut'] = $NoUrut;

		$this->db->insert('PRDtl', $data);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function hapus_detail_edit($where)
	{
		$this->db->trans_begin();
		$this->db->where($where)->delete('PRDtl');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	function edit_detail_edit($where, $data)
	{
		$this->db->trans_begin();

		$this->db->set($data)->where($where)->update('PRDtl');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}


	function EditSPR($data, $where)
	{
		$this->db->trans_begin();
		$simpan = $this->db->set($data)->where($where)->update('PRHdr');

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}



	// function simpan_detail_spr($data)
	// {
	// 	$this->db->trans_begin();

	// 	$this->db->insert('tmp_pr_dtl',$data);

	// 	if ($this->db->trans_status() === FALSE) {
	// 		$this->db->trans_rollback();
	// 		return false;
	// 	} else {
	// 		$this->db->trans_commit();
	// 		return true;
	// 	}
	// }
}

/* End of file Spr_model.php */
/* Location: ./application/models/Spr_model.php */
