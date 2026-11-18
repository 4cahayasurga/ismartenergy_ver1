<?php
error_reporting(E_ALL);
defined('BASEPATH') or exit('No direct script access allowed');

class ForumDiskusi extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		is_login();
    }

    public function create()
    {
        $data = array(
            'IDThread' => $this->generateId(),
            'Thread' => $this->input->post('thread'),
            'DatePost' => date('Y-m-d H:i:s'),
            'CreatedBy' =>  $this->session->userdata('MIS_LOGGED_ID'),
            'UserName' => $this->session->userdata('MIS_LOGGED_NAME'),
            'IsActive' => 1
        );

        $this->db->insert('tblForumDiskusi', $data);
        redirect('Welcome/index');        
    }

    public function pilihForum()
    {
        $idThread = $this->input->post('IDThread');
        $data['detail'] = $this->db->get_where('tblForumDiskusi', ['IDThread' => $idThread])->row_array();
        $this->load->view('ForumDiskusi/halamanUtama', $data);
    }

    public function showChat()
    {
        $idThread = $this->input->post('idThread');
        $data['comment'] = $this->db->query("SELECT * FROM tblCommentDiskusi WHERE IDThread = '$idThread'")->result_array();
        $this->load->view('ForumDiskusi/viewChat', $data);
    }

    public function addComment()
    {
        $data = array(
            'IDThread' => $this->input->post('idThread'),
            'IdUser' =>  $this->session->userdata('MIS_LOGGED_ID'),
            'UserName' => $this->session->userdata('MIS_LOGGED_NAME'),
            'ValueComment' => $this->input->post('value'),
            'DateCreated' => date('Y-m-d H:i:s')
        );
        $insert = $this->db->insert('tblCommentDiskusi', $data);

        if ($insert) {
            echo json_encode(array("status" => TRUE));
        }
    }

    private function generateId()
    {
       
        $query = $this->db->query("SELECT TOP 1 * FROM tblForumDiskusi ORDER BY Id DESC ")->row();
		if ($query == NULL) {
			$ID = 'THR0001';
		} else {
			$getID = $query->IDThread;
			$trimID = substr($getID, 4);
			$addID =  str_pad($trimID + 1, 4, '0', STR_PAD_LEFT);
			$ID = 'THR' . $addID;
		}
		return $ID;

    }
}