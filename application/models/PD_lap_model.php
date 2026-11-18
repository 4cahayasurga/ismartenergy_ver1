<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PD_lap_model extends CI_Model
{

    function json_table_pd_lapangan($where, $dari_tgl, $sampai_tgl, $Pencarian = '')
    {
        $this->load->library('Datatables');



        $this->datatables->select('
        NoPD,
		NoRef,
		TglPD,
		Alokasi,
		TipeForm,
		NoKO,
	    Deskripsi,
		TotalPD,
		ApprovedByAK,
		TimeApprovedAK,
        LedgerNo
        ');
        $this->datatables->from('PdHdr');
        $this->datatables->where($where);

        if ($dari_tgl !== '' || $sampai_tgl !== '') {
            $this->db->where('TglPD >=', $dari_tgl);
            $this->db->where('TglPD <=', $sampai_tgl);
        }

        if ($Pencarian != '') {
            $this->db->like('NoPD', $Pencarian);
            // $this->db->or_like('NoKO', $Pencarian);
        }

        // $this->datatables->add_column('aksi', '
        // <a type="button" href="javascript:void(0)" class="btn btn-xs btn-secondary"  onclick="aksi_print_pd(\'$1\',\'$2\')" title="Print PD Lapangan"><i class="fa fa-print"> PRINT</i></a>
        // ', 'NoPD,LedgerNo');

        // $this->datatables->add_column('aksi', '
        // <a href="' . site_url('PD_lap/print_pd_lap/$1') . '" class="btn btn-xs btn-link" target="_blank"  title="Print PD Lapangan"><i class="fa fa-print"> PRINT</i></a>

        // <a href="javascript:void(0)" class="btn btn-xs btn-success" onclick="tampil_pdf_pd(\'$1\')" title="Print PD Lapangan"><i class="fa fa-print"> PDF</i></a>
        // ', 'LedgerNo');


        $this->datatables->add_column('aksi', '
        <a href="' . site_url('PD_lap/print_pd_lap/$1') . '" class="btn btn-xs btn-link" target="_blank"  title="Print PD Lapangan"><i class="fa fa-print"> PRINT</i></a>
        ', 'LedgerNo');

        $this->datatables->edit_column('TglPD', '$1', 'TglStrip_tanpa_waktu(TglPD)');
        $this->datatables->edit_column('TimeApprovedAK', '$1', 'TglStrip_tanpa_waktu(TimeApprovedAK)');
        $this->datatables->edit_column('TotalPD', '$1', 'UbahGabunganUang(TotalPD)');

        return $this->datatables->generate();
    }

    function get_data_pd($where)
    {
        $NoPD = $where['NoPD'];
        $JobNo = $where['JobNo'];

        return $this->db->query("
			SELECT a.*, 
			(SELECT JobNm From Job WHERE JobNo=a.JobNo ) as NmProyeknya,
			(CASE
				WHEN (SELECT JobNm From Job WHERE JobNo=a.JobNo) IS NULL THEN (SELECT NamaPaket From InfoPasar WHERE JobNo=a.JobNo)
				ELSE (SELECT JobNm From Job WHERE JobNo=a.JobNo)
				END) as NmProyek
			FROM PdHdr a WHERE a.NoPD = '$NoPD' AND a.JobNo = '$JobNo'")->row_array();
    }

    function dapat_data_pd($LedgerNo)
    {
        $query = $this->db->select('a.*,(SELECT JobNm FROM Job b WHERE b.JobNo=a.JobNo ) as NmProyek ')->where('a.LedgerNo', $LedgerNo)->get('PdHdr a');
        return $query;
    }

    function dapat_pd_detail($NoPD)
    {
        $query = $this->db->query("SELECT a.*,(a.Vol * a.HrgSatuan) as JumlahHarga FROM PdDtl a WHERE a.NoPD='$NoPD' ORDER BY a.NoUrut DESC")->result_array();
        return $query;
    }

    function cekSaldoSebelumnya($where)
    {

        $query = $this->db->select('JobNo,NoPD,Amount')->get_where('BLE', $where);

        $Amount = 0;

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $Amount = $row['Amount'];
        } else {
            $Amount = 0;
        }
        return $Amount;
    }
}

/* End of file PD_lap_model.php */
