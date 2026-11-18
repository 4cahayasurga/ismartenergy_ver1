<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *
 * Model Sumda_model
 *
 * This Model for ...
 * 
 * @package		CodeIgniter
 * @category	Model
 * @author    Setiawan Jodi <jodisetiawan@fisip-untirta.ac.id>
 * @link      https://github.com/setdjod/myci-extension/
 * @param     ...
 * @return    ...
 *
 */

class Sumda_model extends CI_Model
{

	function get_header($array) {
    $this->db->select('KdRAP,Uraian');
    $this->db->from('RAP');
    $this->db->where($array);
    return $this->db->get();
	}

	function get_sumda($array) {
    $this->db->select('KdRAP,Uraian');
    $this->db->from('RAP');
    $this->db->where($array);
    return $this->db->get();
	}	

  function getHeader($JobNo, $alokasi_sumda, $versi_sumda)
  {
    $this->db->select('KdRAP,Uraian');
    $this->db->from('RAP');
    $array = array(
      "JobNo" => $JobNo,
      "Alokasi" => $alokasi_sumda,
      "Versi" => $versi_sumda,
      "Tipe" => "Header",
      "Header" => "0"
    );
    $this->db->where($array);
    return $this->db->get();
  }

  function simpanSumda($Header_asal,$data)
  {
    $this->db->trans_begin();

    $JobNo = $data['JobNo'];
    $Alokasi = $data['Alokasi'];
    $Versi = $data['Versi'];
		$Header = $data['Header'];

    // if ($data['Tipe'] == 'Header') {
		// 	$Header = '0';
		// }
		// else {
		// 	$Header = $data['Header'];
		// }

    $query = $this->db->query("SELECT TOP 1 NoUrut FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' ORDER BY NoUrut DESC ");
    $row = $query->row();
    $NoUrut = $row->NoUrut;
    $hitung = $NoUrut + 1;
    $data['NoUrut'] = $hitung;
    // $data['Header'] = $Header;
		
    $query = $this->db->insert('RAP', $data);

    if ($data['Tipe'] == 'Detail' || $data['Tipe'] == 'Sumda' || $data['Tipe'] == 'Detail Sumda') {
      $VolHrgSatuan = $data['Vol'] * $data['HrgSatuan'];

      $this->db->query(" UPDATE RAP SET HrgSatuan=HrgSatuan + $VolHrgSatuan 
        WHERE JobNo='$JobNo' AND Alokasi ='$Alokasi' AND Versi='$Versi' AND KdRAP='$Header';  
        ");

      $this->db->query("UPDATE  RAP SET HrgSatuan = (SELECT SUM(HrgSatuan) as oke
        FROM RAP
        WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' AND Tipe='Header' AND Header ='$Header_asal'  GROUP BY Header)
      WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' AND Versi='$Versi' AND Tipe='Header'  AND  Header='0' AND KdRAP='$Header_asal'");
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      return false;
    } else {
      $this->db->trans_commit();
      return true;
    }
  }
}

/* End of file Sumda_model.php */
/* Location: ./application/models/Sumda_model.php */
