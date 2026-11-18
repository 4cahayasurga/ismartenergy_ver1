<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FrmPelamarPosting extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Model_PelamarPosting');
    }


    public function index()
    {
        $data['judul'] = 'Data Posting Pelamar';
        $data['bodyclass'] = 'sidebar-collapse skin-blue';
        $data['allData'] = $this->Model_PelamarPosting->getAllData();
        // print_rr($data['allData']);
        // die;
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('FrmPelamarPosting/halaman_utama', $data);
        $this->load->view('templates/footer', $data);
    }

    public function createVote()
    {
        $pelamarID = $this->input->post('pelamarid');
        $votersID = $this->session->userdata('MIS_LOGGED_ID');

        $getVote = $this->db->query("SELECT Voters, lastVote from ViewPelamar WHERE PelamarID='$pelamarID'")->row_array();
        $parseArrVoters = explode(',', $getVote['Voters']);
        $parseArrLastVoters = explode(',', $getVote['lastVote']);
        if (in_array($votersID, $parseArrVoters)) {
            var_dump('and sudah melakukan vote');
            die;
        } else {
            $parseArrVoters[] = $votersID;
            $addVote = implode(',', $parseArrVoters);

            $parseArrLastVoters[] = $votersID;
            $addLastVote = implode(',', $parseArrLastVoters);

            $this->db->query("UPDATE ViewPelamar SET Voters='$addVote', lastVote='$addLastVote' WHERE PelamarID='$pelamarID'");

            $this->db->query("UPDATE ViewPelamar SET dilihat='false'");
            $this->session->set_flashdata('message', 'Vote berhasil');
            redirect('FrmPelamarPosting');
        }
    }

    public function cekHasVote()
    {
        $userid = $this->input->post('userid');
        $cekAksesMenuPelamar = $this->db->query("SELECT MenuPelamar FROM Login WHERE UserID ='$userid'")->row_array();
        $totalSudahVote = 0;
        if ($cekAksesMenuPelamar['MenuPelamar'] == 1) {
            $getData = $this->db->query("SELECT Voters FROM ViewPelamar")->result_array();
            foreach ($getData as $row) {
                $trim = explode(',', $row['Voters']);
                if (in_array($userid, $trim)) {
                    $totalSudahVote++;
                }
            }
            echo $totalSudahVote;
        } else {
            echo 'Akses ditolak';
        }
    }
}
