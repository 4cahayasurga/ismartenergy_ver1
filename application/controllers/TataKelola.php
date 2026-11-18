<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TataKelola extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		is_login();
		$this->load->model('M_tata_kelola');
	}
	
	public function view($JobNo)
	{
		$this->load->helper('bantu');
		$data['judul'] = 'TATA KELOLA';

		$data['dapat_data_job'] = dapat_data_job($JobNo);

		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view("tata_kelola/v_tata_kelola", $data);
		$this->load->view('templates/footer');
	}

	function updata_data_kso(){
		if (!$this->input->post()) {
			exit;
		}

		$JobNo = $this->input->post('JobNo',TRUE);

		$KSO = $this->input->post('KSO',TRUE);
		$Own = ($this->input->post('Own',TRUE)) ? $this->input->post('Own',TRUE) : NULL ;
		$TipeManagerial = ($this->input->post('TipeManagerial',TRUE)) ? $this->input->post('TipeManagerial',TRUE) : NULL ;
		$NoRekKSO = ($this->input->post('TipeManagerial',TRUE)) ? $this->input->post('TipeManagerial',TRUE) : NULL ;

        //leader
		$NamaLeader = ($this->input->post('NamaLeader',TRUE)) ? $this->input->post('NamaLeader',TRUE) : NULL ;
		$PersenLeader = ($this->input->post('PersenLeader',TRUE)) ? $this->input->post('PersenLeader',TRUE) : NULL ;
		$BrutoLeader = ($this->input->post('BrutoLeader',TRUE)) ? $this->input->post('BrutoLeader',TRUE) : NULL ;
		$BankLeader = ($this->input->post('BankLeader',TRUE)) ? $this->input->post('BankLeader',TRUE) : NULL ;
		$NoRekLeader = ($this->input->post('NoRekLeader',TRUE)) ? $this->input->post('NoRekLeader',TRUE) : NULL ;


        //member

		$chkMember = ($this->input->post('chkMember',TRUE)) ? 1 : 0 ;

		$norut_member = ($this->input->post('norut_member',TRUE)) ? $this->input->post('norut_member',TRUE) : NULL ;
		$NamaMember = ($this->input->post('NamaMember',TRUE)) ? $this->input->post('NamaMember',TRUE) : NULL ;
		$PersenMember = ($this->input->post('PersenMember',TRUE)) ? $this->input->post('PersenMember',TRUE) : NULL ;
		// $BrutoMember = ($this->input->post('BrutoMember',TRUE)) ? $this->input->post('BrutoMember',TRUE) : NULL ;
		// $BankMember = ($this->input->post('NamaLeader',TRUE)) ? $this->input->post('BankMember',TRUE) : NULL ;
		// $NoRekMember = ($this->input->post('NamaLeader',TRUE)) ? $this->input->post('NoRekMember',TRUE) : NULL ;


		$data = array(
			'KSO' => $KSO,
			'Own' => $Own,
			'TipeManagerial' => $TipeManagerial,
			'NoRekKSO' => $NoRekKSO,
		);


		// if ($chkMember == 1) {
			
		// 	foreach($norut_member as $nomor){
		// 		$data['Member'.$nomor] = $NamaMember;
		// 	}


		// }

		// if($chkMember == 1 ){



		// 	$data['Member'.$norut_member] = $this->input->post('NamaMember',TRUE);
		// 	$data['PorsiMember'.$norut_member] = $this->input->post('PersenMember',TRUE);

		// }

		// $Member = 'Member';

		if (is_array($norut_member)) {
		   for ($i=0;$i<count($norut_member);$i++)
		   {
		       // echo  ($norut_member[$i]),'<br>';
		       $data['Member'.$norut_member[$i]] = $NamaMember[$i]; 
		       $data['PersenMember'.$norut_member[$i]] = $PersenMember[$i]; 
		      
		   }
		}

		// echo $Member;


		// if(($this->input->post('chkMember',TRUE))){

			// $result = array();
			// foreach ($_POST['norut_member'] as $key => $val) {
			// 	$result[] = array(

			// 		'Member1' => $_POST['NamaMember'][$key],
			// 		'PorsiMember1' => $_POST['PersenMember'][$key],

			// 		'Member2' => $_POST['NamaMember'][$key],
			// 		'PorsiMember2' => $_POST['PersenMember'][$key],

			// 	);      
			// }
			// echo $this->db->insert_batch('PesertaTender_trial',$result);
			// die;
		// }
		print_rr($data);
		// echo "<hr>";
		// print_rr($_POST);

	}
	
}

/* End of file TataKelola.php */
	/* Location: ./application/controllers/TataKelola.php */