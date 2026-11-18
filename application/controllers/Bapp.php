<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bapp extends CI_Controller
{

	public function index($JobNo=null)
	{

		$this->load->model('m_job');
		$datadipa = $this->m_job->dipa($JobNo);
		$data['dipa'] = $datadipa;


		$data['title'] = 'Berita Acara Pembayaran Termin';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';


		$data['NilaiKontrak'] = $this->db->query("SELECT (CASE WHEN SumberDana LIKE '%LOAN%' THEN 
		CASE
				WHEN KSO='1' AND TipeManajerial='JO Integrated' THEN Bruto
				WHEN KSO='1' AND Own=1 THEN (Bruto*PersenShare1)/100
				WHEN KSO='1' AND Own=2 THEN (Bruto*PersenShare2)/100
				WHEN KSO='0' THEN Bruto
		END
		ELSE 
		CASE
			WHEN KSO='1' AND TipeManajerial='JO Integrated' THEN Netto
			WHEN KSO='1' AND Own=1 THEN (Netto*PersenShare1)/100
			WHEN KSO='1' AND Own=2 THEN (Netto*PersenShare2)/100
			WHEN KSO='0' THEN Netto
		END
		END ) AS NilaiKontrak 
		FROM Job
		WHERE JobNo='$JobNo'")->row(); 

		

		// print_rr($data['KontrakBruto']);
		// exit;

		$data['listBAPP'] = $this->db->query("SELECT * FROM BAPT WHERE JobNo='$JobNo'")->result();

		


		$this->load->view('templates/header',$data);
		$this->load->view('templates/sidebar');
		$this->load->view('Bapp/ListBapp',$data);
		$this->load->view('templates/footer');
	}

	public function menutambah($JobNo = null)
	{

		$this->load->model('m_job');
		$datadipa = $this->m_job->dipa($JobNo);
		$data['dipa'] = $datadipa;


		$data['title'] = 'Berita Acara Pembayaran Termin';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';


		$data['NilaiKontrak'] = $this->db->query("SELECT (CASE WHEN SumberDana LIKE '%LOAN%' THEN 
		CASE
				WHEN KSO='1' AND TipeManajerial='JO Integrated' THEN Bruto
				WHEN KSO='1' AND Own=1 THEN (Bruto*PersenShare1)/100
				WHEN KSO='1' AND Own=2 THEN (Bruto*PersenShare2)/100
				WHEN KSO='0' THEN Bruto
		END
		ELSE 
		CASE
			WHEN KSO='1' AND TipeManajerial='JO Integrated' THEN Netto
			WHEN KSO='1' AND Own=1 THEN (Netto*PersenShare1)/100
			WHEN KSO='1' AND Own=2 THEN (Netto*PersenShare2)/100
			WHEN KSO='0' THEN Netto
		END
		END ) AS NilaiKontrak 
		FROM Job
		WHERE JobNo='$JobNo'")->row();

		$data['KontrakBruto'] = $this->db->query("SELECT Bruto FROM Job Where JobNo='$JobNo'")->row();



		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Bapp/Bap', $data);
		$this->load->view('templates/footer');
	}

	public function simpan()
	{
		$this->load->model('m_job');

		$JobNo		= $this->input->post('JobNo');
		$tglBAP 		= $this->input->post('TglBAPT');
		$NoBAPT 		= $this->input->post('NoBAPT');
		$NilaiKontrak	= $this->input->post('NilaiKontrak');
		$TerminKe   	= $this->input->post('TerminKe');
		$PersenSkrg 	= $this->input->post('PersenPekerjaan');
		$NilaiPekSkrg	= $this->input->post('HasilSi');
		$Persenlalu	= $this->input->post('PersenPekerjaanLalu');
		$NilaiLalu 	= $this->input->post('Jumlahll');
		$JmlBAP		= $this->input->post('HasilBap');
		$PersenRetensi	= $this->input->post('PersenRetensi');
		$NilaiRetensi	= $this->input->post('JmlRetensi');
		$PersenUM		= $this->input->post('PersenUM');
		$NilaiPotUM	= $this->input->post('JmlPotum');
		$JmlPotongan 	= $this->input->post('JumlahPemotong');
		$TotalBAP		= $this->input->post('Total');
		$ppn 		= $this->input->post('PPN');
		$DPP 		= $this->input->post('bapdpp');
		$pph 		= $this->input->post('PPH');
		$netto 		= $this->input->post('Netto');
		$UserEntry	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$data = array(
			'JobNo'			=> $JobNo,
			'TglBAPT' 		=> $tglBAP,
			'NoBAPT'			=> $NoBAPT,
			'NilaiKontrak'		=>  str_replace(',', '', $NilaiKontrak),
			'TerminKe'		=> $TerminKe,
			'PersenSekarang'	=> $PersenSkrg,
			'NilaiPekSekarang'	=> str_replace(',', '', $NilaiPekSkrg),
			'persenSebelumnya'	=> $Persenlalu,
			'NilaiSebelumnya'	=> str_replace(',', '', $NilaiLalu),
			'JmlBAP'			=> str_replace(',', '', $JmlBAP),
			'PersenRetensi'	=> $PersenRetensi,
			'NilaiRetensi'		=> str_replace(',', '', $NilaiRetensi),
			'PersenUM'		=> $PersenUM,
			'NilaiPotUM'		=> str_replace(',', '', $NilaiPotUM),
			'JmlPotongan'		=> str_replace(',', '', $JmlPotongan),
			'TotalBAP'		=> str_replace(',', '', $TotalBAP),
			'PPN'			=> $ppn,
			'DPP'			=> str_replace(',','',$DPP),
			'PPH'			=> $pph,
			'Netto'			=> str_replace(',','',$netto),
			'UserEntry'		=> $UserEntry,
			'TimeEntry'		=> $TimeEntry
		);

		// print_rr($data);
		// exit;

		$this->m_job->SimpanData('BAPT', $data);
		Redirect('/Bapp/index/' . $JobNo);
	}

	public function EditBAPT($LedgerNo='', $JobNo='')
	{
		$this->load->model('m_job');

		$data['title'] = 'Edit Berita Acara Pembayaran Termin';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';
		$datadipa = $this->m_job->dipa($JobNo);
		$data['dipa'] = $datadipa;

		$query=$this->db->query("SELECT * FROM BAPT WHERE LedgerNo=$LedgerNo")->result();
		$data['DataBAP'] = $query;
		// print_rr($data['DataBAP']);
		// exit;


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('Bapp/Bap_Edit', $data);
		$this->load->view('templates/footer');
	}

	function SimpanEdit($LedgerNo='', $JobNo='')
	{
		$this->load->model('m_job');

		$LedgerNo 	= $this->input->post('LedgerNo');
		$JobNo		= $this->input->post('JobNo');
		$tglBAP 		= $this->input->post('TglBAPT');
		$NoBAPT 		= $this->input->post('NoBAPT');
		$NilaiKontrak	= $this->input->post('NilaiKontrak');
		$TerminKe   	= $this->input->post('TerminKe');
		$PersenSkrg 	= $this->input->post('PersenPekerjaan');
		$NilaiPekSkrg	= $this->input->post('HasilSi');
		$Persenlalu	= $this->input->post('PersenPekerjaanLalu');
		$NilaiLalu 	= $this->input->post('Jumlahll');
		$JmlBAP		= $this->input->post('HasilBap');
		$PersenRetensi	= $this->input->post('PersenRetensi');
		$NilaiRetensi	= $this->input->post('JmlRetensi');
		$PersenUM		= $this->input->post('PersenUM');
		$NilaiPotUM	= $this->input->post('JmlPotum');
		$JmlPotongan 	= $this->input->post('JumlahPemotong');
		$TotalBAP		= $this->input->post('Total');
		$ppn 		= $this->input->post('PPN');
		$DPP 		= $this->input->post('bapdpp');
		$pph 		= $this->input->post('PPH');
		$netto 		= $this->input->post('Netto');
		$UserEntry	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$data = array(
			'JobNo'			=> $JobNo,
			'TglBAPT' 		=> $tglBAP,
			'NoBAPT'			=> $NoBAPT,
			'NilaiKontrak'		=>  str_replace(',', '', $NilaiKontrak),
			'TerminKe'		=> $TerminKe,
			'PersenSekarang'	=> $PersenSkrg,
			'NilaiPekSekarang'	=> str_replace(',', '', $NilaiPekSkrg),
			'persenSebelumnya'	=> $Persenlalu,
			'NilaiSebelumnya'	=> str_replace(',', '', $NilaiLalu),
			'JmlBAP'			=> str_replace(',', '', $JmlBAP),
			'PersenRetensi'	=> $PersenRetensi,
			'NilaiRetensi'		=> str_replace(',', '', $NilaiRetensi),
			'PersenUM'		=> $PersenUM,
			'NilaiPotUM'		=> str_replace(',', '', $NilaiPotUM),
			'JmlPotongan'		=> str_replace(',', '', $JmlPotongan),
			'TotalBAP'		=> str_replace(',', '', $TotalBAP),
			'PPN'			=> $ppn,
			'DPP'			=> str_replace(',', '', $DPP),
			'PPH'			=> $pph,
			'Netto'			=> str_replace(',', '', $netto),
			'UserEntry'		=> $UserEntry,
			'TimeEntry'		=> $TimeEntry
		);

		$where = array('LedgerNo' => $LedgerNo);

		// print_rr($data);
		// exit;

		$this->m_job->UpdateDataProyek('BAPT',$data, $where);
		Redirect('/Bapp/index/' . $JobNo);
	}

	function hapusBAP($LedgerNo = null, $JobNo = null)
	{
		$this->load->model('m_job');

		$where = array('LedgerNo' => $LedgerNo);
		$this->m_job->hapus_data($where, 'BAPT');
		redirect('Bapp/index/' . $JobNo);
	}



	
}
