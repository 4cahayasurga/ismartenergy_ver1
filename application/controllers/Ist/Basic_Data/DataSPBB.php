<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DataSPBB extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('MIS_LOGGED_NAME') == '') {
            $this->session->sess_destroy();
            redirect(base_url('auth'));
        }
    }

    public function index()
    {
        $data['judul'] = 'Basic Data SPBB';
        $data['bodyclass'] = 'sidebar-collapse skin-black';

        $data['data_spbb'] = $this->db->get('SPBB')->result(); 



        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('Ist/Basic_Data/DataSPBB', $data);
        $this->load->view('templates/footer');
    }

       

    function add()
    {
        $data = [
            'No_SPBB' => $this->input->post('no_spbb'),
            'Area' => $this->input->post('area'),
            'Pic' => $this->input->post('pic'),
            'Status' => 'Aktif',
            'UserEntry' => $this->session->userdata('MIS_LOGGED_NAME'),
            'TimeEntry' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('SPBB', $data);
        redirect(site_url('Ist/Basic_Data/DataSPBB'));
    }
}