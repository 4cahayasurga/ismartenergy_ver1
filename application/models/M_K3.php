
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_K3 extends CI_Model
{

	public function SimpanData($table, $data)
	{
		$this->db->insert($table, $data);
	}

	public function edit_data($where, $table)
	{
		return $this->db->get_where($table, $where);
	}

	function get_byJobNo($JobNo)
	{
		$hsl = $this->db->query("SELECT * FROM Job WHERE JobNo='$JobNo'");
		if ($hsl->num_rows() > 0) {
			foreach ($hsl->result() as $data) {
				$hasil = array(
					'JobNm' => $data->JobNm,
					// 'nohp' => $data->nohp,
					// 'email_cp' => $data->email_cp,
				);
			}
		}
		return $hasil;
	}

	public function UpdateDataProyek($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
	}


}
