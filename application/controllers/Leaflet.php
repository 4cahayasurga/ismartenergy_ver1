<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaflet extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Leaflet_model');
	}

	public function lakukan_simpan(){


		if ($this->input->post('JobNo') != '') {


			if ($_FILES['leaflet']['name'] == '' ) {
				echo 'kosong';
				exit;
			}

			$this->load->library('upload');
			$leaflet = '';

			$config['upload_path']          = './assets/doc/';
			$config['allowed_types']        = 'pdf';
			// $config['max_size']             = 100;
			// $config['max_width']            = 1024;
			$config['encrypt_name']           = true;

			$this->upload->initialize($config);


			if ( ! $this->upload->do_upload('leaflet'))
			{
				echo 'gagalupload';
				exit;
			}
			else
			{
				$leaflet = $this->upload->data('file_name');
			}

			if ($leaflet !='') {
				$data = array(
					'JobNo' => $this->input->post('JobNo'),
					'leaflet_file' => $leaflet
				);

				$this->db->trans_begin();
				$this->db->insert('Leaflet',$data);

				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					echo '0';
				}
				else
				{
					$this->db->trans_commit();
					echo '1';
				}


			}


		}

	}


	function getTable(){
		if ($this->input->post('type') == 'tampil') {
			$JobNo = $this->input->post('JobNo');
			// echo $JobNo;
			$data['data'] = $this->db->order_by('LedgerNo','DESC')->get_where('Leaflet',array('JobNo' => $JobNo));
			$this->load->view('leaflet/view_leaflet',$data);
		}
	}

	function ShowPdf(){
		if ($this->input->post()) {
			$LedgerNo = $this->input->post('LedgerNo');
			$get = $this->db->get_where('Leaflet',array('LedgerNo' => $LedgerNo))->row_array();
			$data['file'] = 'assets/doc/'.$get['leaflet_file'];
			$this->load->view('leaflet/show_pdf', $data);
		}
	}

	function HapusLeaflet(){
		if ($this->input->post()) {
			$LedgerNo = $this->input->post('LedgerNo');

			$get = $this->db->select('leaflet_file')->where('LedgerNo',$LedgerNo)->get('Leaflet')->row_array();
			if (file_exists('assets/doc/'.$get['leaflet_file'])) {
				unlink('assets/doc/'.$get['leaflet_file']);

			}
			// exit;


			$data = array(
				'LedgerNo' => $LedgerNo
			);

			$this->db->trans_begin();
			$this->db->where($data)->delete('Leaflet');

			if ($this->db->trans_status() === FALSE)
			{
				$this->db->trans_rollback();
				echo '0';
			}
			else
			{
				$this->db->trans_commit();
				echo '1';
			}
		}
	}

}

/* End of file Leaflet.php */
/* Location: ./application/controllers/Leaflet.php */