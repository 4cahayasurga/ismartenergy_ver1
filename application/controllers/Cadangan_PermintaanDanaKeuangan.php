<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cadangan_PermintaanDanaKeuangan extends CI_Controller {

	var $Company;

	function __construct()
	{
		parent::__construct();
		is_login();
		pembuatan_tbl_InvPD_sementara();
		pembuatan_tbl_invoice_sementara();
		tambah_ledgerNo_di_InvPD_sementara();
		tambah_ledgerNo_di_InvPD();
		pembuatan_tbl_PdDtl_sementara();

		$this->load->helper('bantu');
		$this->Company = $this->config->item('Company');

	}

	function lihat_invoice(){
		if(!$this->input->post('JobNo',TRUE)){
			echo 'error';
			exit;
		}

		$JobNo = $this->input->post('JobNo', TRUE);
		$Alokasi = $this->input->post('Alokasi', TRUE);

		$NoPD = $this->input->post('NoPD', TRUE);
		$NoKO = $this->input->post('NoKO', TRUE);
		$TipeForm = $this->input->post('TipeForm', TRUE);
		$khusus_inputan = ($this->input->post('khusus_inputan', TRUE)) ? $this->input->post('khusus_inputan', TRUE) : '' ;


		$data_mentah_Inv['JobNo'] = $JobNo;
		$data_mentah_Inv['Alokasi'] = $Alokasi;
		$data_mentah_Inv['NoPD'] = $NoPD;
		$data_mentah_Inv['NoKO'] = $NoKO;
		$data_mentah_Inv['TipeForm'] = $TipeForm;

		$pembuatan_invoice = $this->pembuatan_invoice($data_mentah_Inv);

		// print_rr($pembuatan_invoice);
		// die;

		$data['mydataInv'] = $pembuatan_invoice['array_buatan'];
		$data['TotalInvoice'] = $pembuatan_invoice['TotalInvoice'];
		$data['TotalBayar'] = $pembuatan_invoice['TotalBayar'];
		$data['jumlah_centang'] = $pembuatan_invoice['jumlah_centang'];

		$data['JobNo'] = $JobNo;
		$data['Alokasi'] = $Alokasi;
		$data['NoPD'] = $NoPD;
		$data['NoKO'] = $NoKO;
		$data['TipeForm'] = $TipeForm;
		$data['khusus_inputan'] = $khusus_inputan;

		$data['tombol_invoice'] = ($this->input->post('tombol_invoice',TRUE) == 1) ? 'hidden' :''  ;

		$this->load->view('permintaan_dana_keuangan/Edit_keuangan/lihat_invoice', $data);
	}

	private function pembuatan_invoice($data_mentah_Inv){

        $JobNo = $data_mentah_Inv['JobNo'];
        $Alokasi = $data_mentah_Inv['Alokasi'];
        $NoPD = $data_mentah_Inv['NoPD'];
        $NoKO = $data_mentah_Inv['NoKO'];
        $TipeForm = $data_mentah_Inv['TipeForm'];

        $Company = $this->Company;


        $query_saya = $this->db->query("SELECT *,
            (SELECT SUM(PaymentAmount) FROM InvPD WHERE NoKO=Invoice.NoKO AND InvNo=Invoice.InvNo) AS 'TotalPayment' 
            FROM Invoice WHERE JobNo='$JobNo' AND NoKO='$NoKO'");

        $nomor_Inv = 1;

        $TotalInvoice = 0;
        $TotalBayar = 0;
        $TotalPayment= 0;
        $TotalPayment_cek = 0;

        $status_centang = 0;
        $array_buatan = array();

        $jumlah_centang=0;

        foreach($query_saya->result_array() as $row){

            $JobNo = $row['JobNo'];
            $NoKO = $row['NoKO'];
            $InvNo = $row['InvNo'];

            $TotalPayment_cek = (is_null($row['TotalPayment'])) ? 0 : $row['TotalPayment'];

            $cek = $this->db->query("SELECT * FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND NoPD='$NoPD' AND Company='$Company'   ");
            if ($cek->num_rows() > 0) {
            	$this->db->query("DELETE FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND NoPD='$NoPD' ");
            }



            $copy_data_dari_InvPD_ke_InvPD_sementara = $this->db->query("
            	INSERT INTO InvPD_sementara (JobNo, NoKO, InvNo, NoPD, PaymentAmount, UserEntry, TimeEntry, Company, LedgerNo)
            	SELECT JobNo, NoKO, InvNo, NoPD, PaymentAmount, UserEntry, TimeEntry,'$Company', LedgerNo FROM InvPD WHERE JobNo='$JobNo' AND NoKO='$NoKO' AND NoPD='$NoPD'

            	");


            $myquery = $this->db->query("SELECT * FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$NoKO' AND InvNo='$InvNo' AND Company='$Company'  ");


            $cek_di_InvPD_sementara = $this->db->query("SELECT * FROM InvPD WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$NoKO' AND InvNo='$InvNo'");

            if ($myquery->num_rows() > 0 ) {
                $invrow = $myquery->row();
                $TotalBayar += $invrow->PaymentAmount;
                $status_centang = 1;
                    // $status_centang = ($invrow->StatusFrom_InvPD_sementara === 1 ) ? 1 : 0 ;

            }else{

                if ($row['Total'] <= $TotalPayment_cek) {
                    continue;   
                }

                $TotalInvoice += $row['Total'];
                $status_centang = 0;
            }

            if ($cek_di_InvPD_sementara->num_rows() > 0 ) {
                $status_centang = 1;
            }

            $jumlah_centang += $status_centang;

            $array_buatan[] = array(
                'JobNo' => $JobNo,
                'NoKO' => $NoKO, 
                'InvNo' => $InvNo,
                'NoPD' => $NoPD,
                'Total' => $row['Total'],
                'TotalPayment' => $row['TotalPayment'],
                'UserEntry' => $row['UserEntry'],
                'status_centang' => $status_centang,
                'LedgerNo' => $row['LedgerNo']
            );


        }
            // return json_encode($array_buatan);

        return array('array_buatan'=>$array_buatan,'TotalInvoice' => $TotalInvoice,'TotalBayar' => $TotalBayar,'jumlah_centang' => $jumlah_centang);
    }

    function simpan_inv_per_row(){
		if (!$this->input->post('LedgerNo',TRUE)) {
			die;
		}

		// // print_rr( json_encode($_POST));
		// die;

		$LedgerNo = $this->input->post('LedgerNo',TRUE);

		$JobNo = $this->input->post('JobNo',TRUE);
		$NoKO = $this->input->post('NoKO',TRUE);
		$InvNo = $this->input->post('InvNo',TRUE);
		$NoPD = $this->input->post('NoPD',TRUE);
		$TotalPayment = $this->input->post('TotalPayment',TRUE);

		$UserEntry = $this->session->userdata("MIS_LOGGED_NAME");
		$TimeEntry = date('Y-m-d H:i:s');

		$status = $this->input->post('status',TRUE);


		$data_send = array(
			'LedgerNo' => $LedgerNo,
			'JobNo' => $JobNo,
			'NoKO' => $NoKO,
			'InvNo' => $InvNo,
			'NoPD' => $NoPD,
			'UserEntry' => $UserEntry,
			'TimeEntry' => $TimeEntry,
			'Company' => $this->Company,
			'PaymentAmount' => $TotalPayment
		);
		// echo $status;
		// print_rr($data_send);
		// die;


		$this->db->trans_begin();

		if ($status == 1) {
			// $cek = $this->db->where("SELECT *  FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$NoKO' AND InvNo='$InvNo' ");
			// if ($cek->num_rows() > 0) {
				$this->db->query("DELETE FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$NoKO' AND InvNo='$InvNo' ");
			// }
			$this->db->insert('InvPD_sementara',$data_send);
		}else{
			$this->db->query("DELETE FROM InvPD_sementara WHERE JobNo='$JobNo' AND NoPD='$NoPD' AND NoKO='$NoKO' AND InvNo='$InvNo' ");
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array('kondisi' => 'gagal', 'status_centang' => ($status == 1) ? 'simpan' : 'hapus', 
				'jumlah_berhasil' => $this->db->affected_rows()  ));
		} else {
			$this->db->trans_commit();
			echo json_encode(array('kondisi' => 'berhasil', 'status_centang' => ($status == 1) ? 'disimpan' : 'dihapus', 
				'jumlah_berhasil' => $this->db->affected_rows()   ));

		}

	}

}

/* End of file Cadangan_PermintaanDanaKeuangan.php */
/* Location: ./application/controllers/Cadangan_PermintaanDanaKeuangan.php */