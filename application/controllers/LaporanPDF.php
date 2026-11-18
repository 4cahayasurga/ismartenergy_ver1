<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LaporanPDF extends CI_Controller {

	public function index(){

		$data = array(
			"dataku" => array(
				"nama" => "Petani Kode",
				"url" => "http://petanikode.com"
			)
		);

		$this->load->library('pdf');

		$this->pdf->setPaper('A4', 'potrait');
		$this->pdf->filename = "laporan-petanikode.pdf";
		$this->pdf->load_view('test_pdf', $data);


	}

}

/* End of file LaporanPDF.php */
/* Location: ./application/controllers/LaporanPDF.php */