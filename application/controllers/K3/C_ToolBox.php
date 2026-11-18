<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_ToolBox extends CI_Controller{

	var $Company;
	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->Company = $this->config->item('Company');
		$this->load->model('Entry_model/Procurement_model/M_invoice');
	}
	
	function index()
	{
		$data['judul'] = 'TOOLBOX MEETING';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';
		$Company = $this->config->item('Company');
		$UserID = $this->session->userdata('MIS_LOGGED_ID');

		$dapat_job = $this->M_invoice->dapat_list_job($UserID, $Company);
		$data['list_job_akses'] = $dapat_job;

		// $datak3toolbox = $this->db->query("SELECT a.KdSurat, a.JobNo, a.JobNm, a.Keterangan, b. NamaPeserta  FROM K3_MeetTool_hdr a, K3_ToolboxMeeting_dtl b Where a.KdSurat = b.KdSurat")->result();
		$datak3toolbox = $this->db->query("SELECT LedgerNo, KdSurat, JobNo, JobNm, Keterangan FROM K3_MeetTool_hdr order by ledgerNo ASC")->result_array();
		$data['K3ToolBox'] = $datak3toolbox;
		

		// print_rr($data['K3ToolBox']);
		// exit;

		// $data['JobNo'] = ($JobNo != '') ? $JobNo : '';



		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('K3/InspeksiK3/ToolboxMeeting', $data);
		// $this->load->view('K3/InspeksiK3/FrmToolboxCek', $data);
		$this->load->view('templates/footer');
	}

	private function dapat_NoUrut()
	{
		$query = $this->db->query("SELECT TOP 1 KdSurat From K3_ToolboxMeeting_dtl ORDER BY KdSurat DESC")->row_array();
		//nomor urut tambah satu
		$tambah_satu = $query['KdUrut'] + 1;
		return $tambah_satu;
	}

	function NoSurat()
	{
		$tahun = date('y');
		$bln = date('m');

		$NoSurat = $this->db->query("SELECT MAX(right(KdSurat,1)) as KdSurat FROM K3_MeetTool_hdr ORDER BY KdSurat DESC ");
		if ($NoSurat->num_rows() > 0) {
			$myno = $NoSurat->row();
			$nomor = ((int)$myno->KdSurat) + 1;
			// $nomor = SUBSTR($myno->NIK, 3) + 1;
			$NoSurat = $tahun . $bln . str_pad($nomor, 2, "0", STR_PAD_LEFT);
		} else {
			$NoSurat = $tahun . $bln . str_pad(1, 2, "0", STR_PAD_LEFT);
		}	

		return $NoSurat;
	}

	function TambahData($KdSurat='')
	{
		// $this->load->model('M_invoice');

		$data['judul'] = 'TOOLBOX MEETING';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';
		$Company = $this->config->item('Company');
		$UserID = $this->session->userdata('MIS_LOGGED_ID');

		$dapat_job = $this->M_invoice->dapat_list_job($UserID, $Company);
		$data['NoSurat'] = $this->NoSurat();

		if ($KdSurat!='') {
			$data['DataMember'] = $this->db->query("SELECT * From K3_ToolBoxMeeting_dtl where KdSurat = '$KdSurat' ")->result();
			// print_rr($data['DataMember']);
			// die;
		}
		$data['DataMember'] = $this->db->query("SELECT * From K3_ToolBoxMeeting_dtl where KdSurat = '$KdSurat' ")->result();

		$data['list_job_akses'] = $dapat_job;
	

		// $data['JobNo'] = ($JobNo != '') ? $JobNo : '';


		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('K3/InspeksiK3/FrmToolboxCek', $data);
		$this->load->view('templates/footer');
	}

	function simpandata()
	{
		$this->load->model('M_K3');

		$KdSurat = $this->input->post('KdSurat');
		$NamaPeserta = $this->input->post('NamaPeserta');
		$Divisi = $this->input->post('Divisi');
		$UserEntry	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$data = array(
			'KdSurat' =>$KdSurat,
			'NamaPeserta' => $NamaPeserta,
			'DivisiJabatan' => $Divisi,
			'UserEntry' 	=> $UserEntry,
			'TimeEntry' 	=> $TimeEntry,
		);

		$this->M_K3->SimpanData('K3_ToolboxMeeting_dtl', $data);
		redirect('K3/C_ToolBox/TambahData/'. $KdSurat);

	}

	function get_JobNo()
	{
		$JobNo = $this->input->post('nama_orang');
		$data = $this->M_K3->get_byJobNo($JobNo);
		echo json_encode($data);
	}

	function simpandatahdr()
	{
		$this->load->model('M_K3');

		$KdSurat = $this->input->post('NoSurat');
		$JobNo = $this->input->post('JobNo');
		$TglCek = $this->input->post('tgl');
		$jam = $this->input->post('jam');
		$topik = $this->input->post('topik');
		$UserEntry	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$data = array(
			'KdSurat' => $KdSurat,
			'JobNo' => $JobNo,
			'TglCek' => $TglCek,
			'Jam' =>$jam,
			'Keterangan' => $topik,
			'UserEntry' 	=> $UserEntry,
			'TimeEntry' 	=> $TimeEntry,
		);

		$this->M_K3->SimpanData('K3_MeetTool_hdr', $data);
		redirect('K3/C_ToolBox');
	}

	function EditData($LedgerNo = null, $KdSurat = null)
	{
		$data['judul'] = 'EDIT TOOLBOX MEETING';
		$data['bodyclass'] = 'sidebar-collapse skin-blue';
		$Company = $this->config->item('Company');
		$UserID = $this->session->userdata('MIS_LOGGED_ID');

		$datak3toolbox = $this->db->query("SELECT * FROM K3_MeetTool_hdr order by ledgerNo ASC")->row_array();
		$data['K3ToolBox'] = $datak3toolbox;

		$dataHeader = $this->db->query("SELECT * FROM K3_MeetTool_hdr Where LedgerNo = '$LedgerNo'")->result();
		$data['K3_Hdr'] = $dataHeader;

		$Q_Member = $this->db->query("SELECT * FROM K3_ToolboxMeeting_dtl where KdSurat = '$KdSurat'")->result();
		$data['DataMember'] = $Q_Member;

		$data['ListJob'] = $this->ListJob();

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('K3/InspeksiK3/FrmToolboxCek_edit', $data);
		$this->load->view('templates/footer');
	}

	function AksiEdit($LedgerNo=null)
	{
		$this->load->model('M_K3');

		$LedgerNo = $this->input->post('LedgerNo');
		$KdSurat = $this->input->post('NoSurat');
		$JobNo = $this->input->post('JobNo');
		$TglCek = $this->input->post('tgl');
		$jam = $this->input->post('jam');
		$topik = $this->input->post('topik');
		$UserEntry	= $this->session->userdata('MIS_LOGGED_NAME');
		$TimeEntry  	= date("Y-m-d H:i:s");

		$data = array(
			'KdSurat' => $KdSurat,
			'JobNo' => $JobNo,
			'TglCek' => $TglCek,
			'Jam' => $jam,
			'Keterangan' => $topik,
			'UserEntry' 	=> $UserEntry,
			'TimeEntry' 	=> $TimeEntry,
		);
		
		$where = array (
			'LedgerNo' => $LedgerNo,
		);

		$this->M_K3->UpdateDataProyek('K3_MeetTool_hdr', $data, $where);
		redirect('K3/C_ToolBox/index');
	}

	function ListJob($pengurutan = '')
	{
		$CI = &get_instance();
		$user_id = $CI->session->userdata('MIS_LOGGED_ID');
		$Company = $CI->config->item('Company');

		$var_pengurutan = '';
		if ($pengurutan != '') {
			$var_pengurutan = 'ORDER BY ax.JobNo DESC';
		}

		$query = $CI->db->query("

			select ax.JobNo, bx.JobNm from
			(select item as JobNo
			from
			dbo.SplitString ((select top 1 a.AksesJob from (select * from Login) as a
			left outer join
			(select * from Job) as b
			on b.JobNo = a.AksesJob
			Where a.UserID= '$user_id'), ',')) as ax
			left outer join 
			Job as bx
			on bx.JobNo = ax.JobNo WHERE bx.Company='" . $this->Company . "' group by ax.JobNo, bx.JobNm " . $var_pengurutan . "

			");

		return $query->result();
	}
}
