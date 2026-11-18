<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class FrmSPR extends CI_Controller {

	var $Company;

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
	}

	public function view($JobNo='')
	{
		$UserID = $this->session->userdata('MIS_LOGGED_ID');
		// print_rr($_SESSION);

		$data['judul'] = 'DAFTAR SURAT PERMINTAAN MATERIAL / ALAT';
		$data['bodyclass'] = 'sidebar-collapse skin-blue-light';

		$data['tgl_pertama'] = date('Y-m-01');
		$data['tgl_terakhir'] = date('Y-m-t');

		$data['JobNo'] = $JobNo;
		$data['ListJob'] = Tampil_Job_Berdasarkan_AksesJob();

		// print_rr($data);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmSPR_View/halaman_utama_SPR', $data);
		$this->load->view('templates/footer');
	}

	function tbl_utama_SPR(){
		if (!$this->input->post()) {
			echo '';
			die;
		}

		$JobNo = $this->input->post('JobNo');
		$data['dataPRHdr'] = $this->db->query("SELECT * FROM PRHdr WHERE JobNo='$JobNo' ORDER BY NoSPR DESC")->result();
		$data['JobNo'] = $JobNo;
		$this->load->view('FrmSPR_View/tbl_utama_SPR',$data);

	}

	public function mypdf($NoSPR='')
	{
		if ($NoSPR == '') {
			echo 'error';
			exit;
		}

		$UserId = strtoupper($this->session->userdata('MIS_LOGGED_NAME'));
		$tglSkrg = date('d-M-Y');
		$data['gabung'] = 'Printed On ' . $tglSkrg . ' By ' . $UserId;

		$data['rowSPR'] = $this->getData_SPR($NoSPR);
		$data['detailSPR'] = $this->getDetail_SPR($NoSPR);


		$this->load->view('ExportSPR_view/content',$data);
        // print_rr($data);
	}

	public function FrmAdd_SPR($JobNo='')
	{
		if ($JobNo=='') {
			redirect('FrmSPR/view','refresh');
		}

		$UserID = $this->session->userdata('MIS_LOGGED_ID');

		$data['judul'] = 'ENTRY SURAT PERMINTAAN MATERIAL / ALAT';
		$data['bodyclass'] = 'sidebar-collapse skin-black-light';

		$data['JobNo'] = $JobNo;
		$data['JobNm'] = $this->db->query("SELECT JobNm FROM Job WHERE JobNo='$JobNo' ")->row()->JobNm;
		$data['AksesAlokasi'] = AksesAlokasi();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('FrmSPR_View/form_add_SPR', $data);
		$this->load->view('templates/footer');
	}


	private function getData_SPR($NoSPR){
		$query = $this->db->query("SELECT *, ISNULL((SELECT JobNm FROM Job WHERE Job.JobNo=PRHdr.JobNo ),NULL) AS 'JobNm' FROM PRHdr WHERE NoSPR='$NoSPR'")->row();
		return $query;
	}

	private function getDetail_SPR($NoSPR){
		$query = $this->db->query("SELECT * FROM PRDtl WHERE NoSPR='$NoSPR' ORDER BY NoUrut")->result();
		return $query;
	}

	function KodeRAP_List(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);
		$Alokasi = $this->input->post('Alokasi',TRUE);
		// print_rr($_POST);

		$query = $this->db->query("SELECT KdRAP,Uraian,Tipe FROM RAP WHERE JobNo='$JobNo' AND Alokasi='$Alokasi' ORDER BY NoUrut ASC")->result();
		$html ='';
		$html .= '<option value="">-- Pilih Salah Satu --</option>';
		foreach($query as $row):
			$html .='<option value="'.$row->KdRAP.'">'.$row->KdRAP.' - '.$row->Uraian.'</option>';
		endforeach;

		echo $html;

	}

	function AksiSimpanSPR(){
		if (!$this->input->post()) {
			exit;
		}

		// print_rr($_POST);

		$NoSPR = $this->input->post('NoSPR',TRUE);
		$Kepada = $this->input->post('Kepada',TRUE);
		$TglSPR = $this->input->post('TglSPR',TRUE);
		$TglPenggunaan = $this->input->post('TglPenggunaan',TRUE);
		$JobNo = $this->input->post('JobNo',TRUE);
		$UtkPekerjaan = $this->input->post('UtkPekerjaan',TRUE);
		$NamaKirim = $this->input->post('NamaKirim',TRUE);
		$AlamatKirim = $this->input->post('AlamatKirim',TRUE);
		$TeleponKirim = $this->input->post('TeleponKirim',TRUE);
		$TglKirim = $this->input->post('TglKirim',TRUE);



	}

	// == Ditambahkan pada 12 SEPTEMBER 2023 ==
	function ExportExcel_SPR($JobNo='')
	{
		if ($JobNo=='') {
			echo 'job tidak di-temukan';
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
		$sheet->setCellValue('B1', "No.SPR");
		$sheet->setCellValue('C1', "Tgl SPR");
		$sheet->setCellValue('D1', "Uraian Pekerjaan");
		$sheet->setCellValue('E1', "SPR Approved By");
		$sheet->setCellValue('F1', "SPR Approved On");

		$sheet->getStyle('A1')->applyFromArray($style_col);
		$sheet->getStyle('B1')->applyFromArray($style_col);
		$sheet->getStyle('C1')->applyFromArray($style_col);
		$sheet->getStyle('D1')->applyFromArray($style_col);
		$sheet->getStyle('E1')->applyFromArray($style_col);
		$sheet->getStyle('F1')->applyFromArray($style_col);

		$no = 1;
		$numrow = 2;

		$dataPRHdr = $this->db->query("SELECT * FROM PRHdr WHERE JobNo='$JobNo' ORDER BY NoSPR DESC")->result();

		foreach ($dataPRHdr as $row): 

	
			$sheet->setCellValue('A' . $numrow, $no++);
			$sheet->setCellValue('B' . $numrow, $row->NoSPR);
			$sheet->setCellValue('C' . $numrow, tgl_baru($row->TglSPR));
			$sheet->setCellValue('D' . $numrow, $row->UtkPekerjaan);
			$sheet->setCellValue('E' . $numrow, $row->ApprovedBy);
			$sheet->setCellValue('F' . $numrow, tgl_baru_tahun_pendek_dengan_waktu($row->TimeApproved));

			$no++;
			$numrow++;
		endforeach;

		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$sheet->setTitle("SPR");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="SPR.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');

	}






}

/* End of file FrmSPR.php */
/* Location: ./application/controllers/FrmSPR.php */