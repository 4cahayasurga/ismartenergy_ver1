<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Pd extends CI_Model
{

	function AksiTambahPd($data)
	{
		$this->db->trans_start();

		$data_counter = $this->db->where('JobNo', $data['JobNo'])->get('Counter')->row();
		$CounterPD = $data_counter->CounterPD;
		$CounterPDJumlah = $CounterPD + 1;
		$NoKO = $data['NoKO'];
		$NoPD = $data['NoPD'];

		$this->db->insert('PdHdr', $data);
		$this->db->set(array("CounterPD" => $CounterPDJumlah))->where('JobNo', $data['JobNo'])->update('Counter');

		$this->db->query("INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry)
			SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry
			FROM TmpKoDtl
			WHERE NoKO='$NoKO' AND NoPD='$NoPD' ");

		$this->db->where('NoKO', $NoKO)->delete('TmpKoDtl');
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
}

/* End of file M_Pd.php */
/* Location: ./application/models/M_Pd.php */
