<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Progress_fisik extends CI_Controller {

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('Menu_Entry_model/M_progress_fisik');
	}

	private function GetJob_For_ProgressFisik(){

		$AksesJob = $this->session->userdata('PecahToken')->AksesJob;
		$QueryJob = $this->db->query("SELECT JobNo,JobNm FROM Job WHERE Company='$this->Company' ");
		$ArrayJob = array();
		foreach($QueryJob->result_array() as $myjob ):

			if (!preg_match('/\b' . $myjob['JobNo'] . '\b/', $AksesJob)) {
				continue;
			}
			$ArrayJob[] = (object) array('JobNo' => $myjob['JobNo'], 'JobNm' => $myjob['JobNm']);
		endforeach;

		return $ArrayJob;

	}

	public function list_data_PF($JobNo='')
	{	
		if ($this->input->post('JobNo')) {
			$JobNo = $this->input->post('JobNo');
			redirect('Menu_Entry/Progress_fisik/list_data_PF/'.$JobNo,'refresh');
			die;
		}

		$user_id = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'PROGRESS FISIK';
		$data['bodyclass'] = 'skin-blue';

		$data['JobNo'] = $JobNo;
		$data['dataJob'] = dapat_data_job($JobNo);
		$data['dataPF'] = $this->db->query("SELECT * FROM Progress WHERE JobNo='$JobNo' ")->result();

		$data['ListJob'] = $this->GetJob_For_ProgressFisik();

		$JobNm = "";
		$Lokasi = "";
		if ($JobNo!='') {
			$queryJob = $this->db->query("SELECT JobNm, Lokasi FROM Job WHERE JobNo='$JobNo' ");
			if ($queryJob->num_rows()>0) {
				$rowJob = $queryJob->row();
				$JobNm = $rowJob->JobNm;
				$Lokasi = $rowJob->Lokasi;
			}
		}
		$data['JobNm'] = $JobNm;
		$data['Lokasi'] = $Lokasi;

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Menu_entry_view/progress_fisik/list_view_PF', $data);
		$this->load->view('templates/footer');
	}

	function get_list_data_json_PF(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		header('Content-Type: application/json');
		echo $this->M_progress_fisik->get_list_data_json_PF($JobNo);
	}

	function dapat_form_tambah_data_PF(){
		if (!$this->input->post()) {
			exit;
		}

		$tahun_skrg = date('Y');
		$atur_tahun = date('Y',strtotime('-10 years'));
		$bulan_skrg = date('m');

		$data['tahun_skrg'] = $tahun_skrg;
		$data['atur_tahun'] = $atur_tahun;
		$data['bulan_skrg'] = $bulan_skrg;

		$JobNo = $this->input->post('JobNo',TRUE);

		$data['JobNo'] = $JobNo;



		$this->load->view('Menu_entry_view/progress_fisik/form_tambah_PF', $data);
	}

	function aksi_tambah_PF(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Tahun = $this->input->post('Tahun',TRUE);
		$Bulan = $this->input->post('Bulan',TRUE);
		$RencanaK = $this->input->post('RencanaK',TRUE);
		$RealisasiK = $this->input->post('RealisasiK',TRUE);
		$RealisasiKeuK = $this->input->post('RealisasiKeuK',TRUE);
		$RencanaTB = $this->input->post('RencanaTB',TRUE);
		$RealisasiTB = $this->input->post('RealisasiTB',TRUE);
		$RealisasiKeuTB = $this->input->post('RealisasiKeuTB',TRUE);

		// == Cek data Tahun dan Bulan Progress ==
		$QueryCek = $this->db->query("SELECT * FROM Progress WHERE JobNo='$JobNo' AND Bulan='$Bulan' AND Tahun='$Tahun' ");
		if ($QueryCek->num_rows() > 0 ) {
			is_pesan('error','Bulan/Tahun '.$Tahun.'/'.getBulan($Bulan).' sudah ada');
			redirect('Menu_Entry/Progress_fisik/list_data_PF/'.$JobNo);
			die;
		}

		$data = array(
			'JobNo' => $JobNo,
			'Tahun' => $Tahun,
			'Bulan' => $Bulan,

			'RencanaK' => $RencanaK,
			'RealisasiK' => $RealisasiK,
			'RealisasiKeuK' => $RealisasiKeuK,

			'RencanaTB' => $RencanaTB,
			'RealisasiTB' => $RealisasiTB,
			'RealisasiKeuTB' => $RealisasiKeuTB,
			'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
			'TimeEntry' => date('Y-m-d H:i:s'),

		);
		// $this->db->insert('Progress',$data);
		// echo $this->db->affected_rows();
		// exit;

		$simpan = $this->M_progress_fisik->aksi_simpan_PF($data);
		if ($simpan == TRUE) {
			is_pesan('success','berhasil menambahkan data');
			redirect('Menu_Entry/Progress_fisik/list_data_PF/'.$JobNo);
			// exit;
		}else{
			is_pesan('error','gagal menambahkan data');
			redirect('Menu_Entry/Progress_fisik/list_data_PF/'.$JobNo);
			// exit;
		}
		

		// print_rr($_POST);
	}

	function dapat_form_ubah_data_PF(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$LedgerNo = $this->input->post('LedgerNo',TRUE);

		$tahun_skrg = date('Y');
		$atur_tahun = date('Y',strtotime('-10 years'));
		$bulan_skrg = date('m');

		$get_data_PF = $this->M_progress_fisik->get_data_PF($JobNo,$LedgerNo)->row_array();
		$data['dataPF'] = $get_data_PF;


		$data['JobNo'] = $JobNo;
		$data['tahun_skrg'] = $tahun_skrg;
		$data['atur_tahun'] = $atur_tahun;
		$data['bulan_skrg'] = $bulan_skrg;

		// print_rr($data);
		// exit;

		// print_rr($get_data_PF);
		// exit;
		$this->load->view('Menu_entry_view/progress_fisik/form_ubah_PF', $data);
	}

	function aksi_ubah_PF(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$LedgerNo = $this->input->post('LedgerNo',TRUE);


		$Tahun = $this->input->post('Tahun',TRUE);
		$Bulan = $this->input->post('Bulan',TRUE);
		$RencanaK = $this->input->post('RencanaK',TRUE);
		$RealisasiK = $this->input->post('RealisasiK',TRUE);
		$RealisasiKeuK = $this->input->post('RealisasiKeuK',TRUE);
		$RencanaTB = $this->input->post('RencanaTB',TRUE);
		$RealisasiTB = $this->input->post('RealisasiTB',TRUE);
		$RealisasiKeuTB = $this->input->post('RealisasiKeuTB',TRUE);

		$where = array(
			'JobNo' => $JobNo,
			'LedgerNo' => $LedgerNo
		);


		$data = array(
			'Tahun' => $Tahun,
			'Bulan' => $Bulan,

			'RencanaK' => $RencanaK,
			'RealisasiK' => $RealisasiK,
			'RealisasiKeuK' => $RealisasiKeuK,

			'RencanaTB' => $RencanaTB,
			'RealisasiTB' => $RealisasiTB,
			'RealisasiKeuTB' => $RealisasiKeuTB,

		);



		$ubah = $this->M_progress_fisik->aksi_ubah_PF($data,$where);
		if ($ubah == TRUE) {
			is_pesan('success','Updated!');
			redirect('Menu_Entry/Progress_fisik/list_data_PF/'.$JobNo);
			// exit;
		}else{
			is_pesan('error','Failed!');
			redirect('Menu_Entry/Progress_fisik/list_data_PF/'.$JobNo);
			// exit;
		}
		

		// print_rr($_POST);
	}


	public function ExportExcel_PF($JobNo=''){
		if ($JobNo=='') {
			echo 'Job tidak ditemukan';
			die;
		}


		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$style_col = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
			],
		];

		$sheet->setCellValue('A1', "No");
		$sheet->setCellValue('B1', "Tahun");
		$sheet->setCellValue('C1', "Bulan");
		$sheet->setCellValue('D1', "Rencana Fisik Kumulatif (%)");
		$sheet->setCellValue('E1', "Realisasi Fisik Kumulatif (%)");
		$sheet->setCellValue('F1', "Realisasi Keuangan Kumulatif (%)");
		$sheet->setCellValue('G1', "Rencana Fisik Tahun Berjalan (%)");
		$sheet->setCellValue('H1', "Realisasi Fisik Tahun Berjalan (%)");
		$sheet->setCellValue('I1', "Realisasi Keuangan Tahun Berjalan (%))");

		$sheet->getStyle('A1')->applyFromArray($style_col);
		$sheet->getStyle('B1')->applyFromArray($style_col);
		$sheet->getStyle('C1')->applyFromArray($style_col);
		$sheet->getStyle('D1')->applyFromArray($style_col);
		$sheet->getStyle('E1')->applyFromArray($style_col);
		$sheet->getStyle('F1')->applyFromArray($style_col);
		$sheet->getStyle('G1')->applyFromArray($style_col);
		$sheet->getStyle('H1')->applyFromArray($style_col);
		$sheet->getStyle('I1')->applyFromArray($style_col);

		$no = 1;
		$numrow = 2;
		$sumRencanaK = 0;
		$sumRealisasiK = 0;
		$sumRealisasiKeuK = 0;
		$sumRencanaTB = 0;
		$sumRealisasiTB = 0;
		$sumRealisasiKeuTB = 0;

		$dataPF = $this->db->query("SELECT * FROM Progress WHERE JobNo='$JobNo' ")->result();

		foreach ($dataPF as $row): 

			$sumRencanaK += (float) $row->RencanaK;
			$sumRealisasiK += (float) $row->RealisasiK;
			$sumRealisasiKeuK += (float) $row->RealisasiKeuK;
			$sumRencanaTB += (float) $row->RencanaTB;
			$sumRealisasiTB += (float) $row->RealisasiTB;
			$sumRealisasiKeuTB += (float) $row->RealisasiKeuTB;

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $row->Tahun);
			$sheet->setCellValue('C' . $numrow, getBulan($row->Bulan));
			$sheet->setCellValue('D' . $numrow, new_decimal($row->RencanaK));
			$sheet->setCellValue('E' . $numrow, new_decimal($row->RealisasiK));
			$sheet->setCellValue('F' . $numrow, new_decimal($row->RealisasiKeuK));
			$sheet->setCellValue('G' . $numrow, new_decimal($row->RencanaTB));
			$sheet->setCellValue('H' . $numrow, new_decimal($row->RealisasiTB));
			$sheet->setCellValue('I' . $numrow, new_decimal($row->RealisasiKeuTB));

			$no++;
			$numrow++;
		endforeach;

		$new_gaya = [
			'font' => ['bold' => true],
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
			],
		];

		$new_gaya1 = [
			'font' => ['bold' => true],
		];

		$sheet->setCellValue('A' . $numrow, "TOTAL");
		$sheet->mergeCells('A' . $numrow . ':C' . $numrow);
		$sheet->getStyle('A' . $numrow)->applyFromArray($new_gaya1);

		$sheet->setCellValue('D' . $numrow, new_decimal($sumRencanaK));
		$sheet->getStyle('D' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('E' . $numrow, new_decimal($sumRealisasiK));
		$sheet->getStyle('E' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('F' . $numrow, new_decimal($sumRealisasiKeuK));
		$sheet->getStyle('F' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('G' . $numrow, new_decimal($sumRencanaTB));
		$sheet->getStyle('G' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('H' . $numrow, new_decimal($sumRealisasiTB));
		$sheet->getStyle('H' . $numrow)->applyFromArray($new_gaya);

		$sheet->setCellValue('I' . $numrow, new_decimal($sumRealisasiKeuTB));
		$sheet->getStyle('I' . $numrow)->applyFromArray($new_gaya);

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->setTitle("Progress Fisik");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Progress Fisik.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');




	}



}

/* End of file Progress_fisik.php */
/* Location: ./application/controllers/Menu_Entry/Progress_fisik.php */