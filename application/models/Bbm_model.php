<?php
class Bbm_model extends CI_Model {

    public function countRecipients()
    {
        return $this->db->count_all('penerima_subsidi');
    }

    public function countTransactions()
    {
        return $this->db->count_all('transaksi_bbm');
    }

    public function quotaUsedPercent()
    {
        $used = $this->db->select_sum('volume')->get('transaksi_bbm')->row()->volume;
        $quota = 50000; // total kuota L per bulan (ubah sesuai kebutuhan)

        return round(($used / $quota) * 100, 2);
    }

    public function getDailyTransactions()
    {
        return $this->db->query("
            SELECT DAYNAME(tanggal) AS hari, COUNT(*) AS total
            FROM transaksi_bbm
            GROUP BY DAYNAME(tanggal)
        ")->result_array();
    }

    public function getRecipientsLimit($limit)
    {
        return $this->db->limit($limit)->get('penerima_subsidi')->result();
    }

    public function getSales()
    {
        return $this->db->order_by('tanggal', 'DESC')
            ->limit(10)
            ->get('penjualan_bbm')
            ->result();
    }
}
