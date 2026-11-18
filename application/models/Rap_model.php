<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Rap_model extends CI_Model
{
    function getHeader($JobNo, $alokasi, $versi)
    {
        $this->db->select('KdRAP,Uraian');
        $this->db->from('RAP');
        $array = array(
            "JobNo" => $JobNo,
            "Alokasi" => $alokasi,
            "Versi" => $versi,
            "Tipe" => "Header"

        );
        $this->db->where($array);
        return $this->db->get();
    }

    function simpanRAP($data)
    {
        $this->db->trans_start();
        $JobNo = $data['JobNo'];
        $Alokasi = $data['Alokasi'];

        $query = $this->db->query("SELECT TOP 1 NoUrut FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut DESC ");
        $row = $query->row();
        $NoUrut = $row->NoUrut;
        $hitung = $NoUrut + 1;

        $data['NoUrut'] = $hitung;
        $this->db->insert('RAP', $data);


        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }
}

/* End of file Rap_model.php */
/* Location: ./application/models/Rap_model.php */