<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_lapangan_pd extends CI_Model
{

    function masukkan_kodtl_ke_pd_sementara($NoPD, $NoKO, $Alokasi)
    {

        $this->db->trans_begin();

        $UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
        $TimeEntry = date('Y-m-d H:i:s');

        $insert_dulu_ke_kodtl_sementara = $this->db->query("
			DELETE FROM table_pd_detail_sementara WHERE NoPD='$NoPD'
			INSERT INTO table_pd_detail_sementara(NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry)
			SELECT '$NoPD',NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,'$UserEntry','$TimeEntry'  FROM KoDtl WHERE NoKO='$NoKO' AND Alokasi='$Alokasi' ");


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function dapat_data_detail($type = '', $NoPD)
    {
        if ($type == 'sementara') {
            $query = $this->db->query(" 
            SELECT a.*, (a.HrgSatuan * a.Vol) as JumlahHarga,
            CONCAT_WS(' - ',b.KdRAP,b.Uraian) as KdRAP_detail
            
            FROM table_pd_detail_sementara a
            LEFT JOIN RAP b ON a.KdRAP=b.KdRAP
            WHERE NoPD='$NoPD' ORDER BY NoUrut DESC ");
            return $query->result_array();
        } else {

            $query = $this->db->query(" 
            SELECT a.*, (a.HrgSatuan * a.Vol) as JumlahHarga,
            CONCAT_WS(' - ',b.KdRAP,b.Uraian) as KdRAP_detail
            FROM PdDtl a
            LEFT JOIN RAP b ON a.KdRAP=b.KdRAP
            WHERE NoPD='$NoPD' ORDER BY NoUrut DESC ");
            return $query->result_array();

            // $query = $this->db->query(" SELECT *, (HrgSatuan * Vol) as JumlahHarga FROM PdDtl WHERE NoPD='$NoPD' ORDER BY NoUrut DESC ");
            // return $query->result_array();
        }
    }

    function hapus_detail_pd($data, $nama_table)
    {
        $this->db->trans_begin();

        $this->db->where($data)->delete($nama_table);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function simpan_pd_detail($data, $nama_table)
    {
        $this->db->trans_begin();

        $this->db->insert($nama_table, $data);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function update_pd_detail($data, $where, $nama_table)
    {
        $this->db->trans_begin();

        $this->db->set($data)->where($where)->update($nama_table);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function simpanPD($data, $NoPD, $JobNo, $Alokasi, $status_kode)
    {
        $this->db->trans_begin();

        OtomatisUbahCounter($JobNo, $Alokasi, $status_kode);
        $simpan_ke_pdhdr = $this->db->insert('PdHdr', $data);

        $pindah_data_table_sementara_ke_pdDtl = $this->db->query("INSERT INTO PdDtl (NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry) 
			SELECT NoPD,NoUrut,KdRAP,Uraian,Vol,Uom,HrgSatuan,UserEntry,TimeEntry FROM table_pd_detail_sementara 
			WHERE NoPD='$NoPD'");

        $hapus_table_sementara = $this->db->query("DELETE FROM table_pd_detail_sementara WHERE  NoPD='$NoPD'");


        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    function dapat_invoice($JobNo, $NoKO)
    {
        $JobNo = '21570';
        $NoKO = 'KO21570014';
        return $this->db->query("

        SELECT *,
        (SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS 'TotalPayment'
        FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO' 
        ORDER BY TimeEntry DESC

        ")->result_array();
    }
}

/* End of file M_lapangan_pd.php */
