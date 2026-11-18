<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbb_receipt extends CI_Controller
{
    private $user_id;
    private $spbb_ids;

    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Spbb_model');
        $this->user_id = $this->session->userdata('MIS_LOGGED_ID');
        $role = $this->Spbb_model->get_role($this->user_id);
        if ($role['role'] !== 'PEMILIK_SPBB') {
            redirect('Spbb/customer_dashboard');
        }
        $this->spbb_ids = $role['spbb_ids'];
    }

    public function index()
    {
        $this->render('spbb/pertamina_receipt', array(
            'judul' => 'Penerimaan BBM',
            'open_purchases' => $this->Spbb_model->open_purchases($this->spbb_ids),
            'receipts' => $this->Spbb_model->receipts($this->spbb_ids),
        ));
    }

    public function save()
    {
        if (!$this->input->post()) {
            redirect('Spbb_receipt');
        }

        list($ok, $message) = $this->Spbb_model->create_receipt($this->user_id, $this->spbb_ids, array(
            'PurchaseID' => (int) $this->input->post('PurchaseID', true),
            'ReceiptNo' => trim($this->input->post('ReceiptNo', true)),
            'DeliveryNoteNo' => trim($this->input->post('DeliveryNoteNo', true)),
            'ReceiptDate' => $this->input->post('ReceiptDate', true),
            'ReceiptTime' => $this->input->post('ReceiptTime', true),
            'VehicleNo' => trim($this->input->post('VehicleNo', true)),
            'DriverName' => trim($this->input->post('DriverName', true)),
            'SealNumber' => trim($this->input->post('SealNumber', true)),
            'ReceivedQuantity' => (float) $this->input->post('ReceivedQuantity', true),
            'RejectedQuantity' => (float) $this->input->post('RejectedQuantity', true),
            'Temperature' => $this->input->post('Temperature', true),
            'Density' => $this->input->post('Density', true),
            'QualityStatus' => $this->input->post('QualityStatus', true),
            'InspectionNotes' => trim($this->input->post('InspectionNotes', true)),
        ));

        $this->session->set_flashdata($ok ? 'Berhasil' : 'Gagal', $message);
        redirect('Spbb_receipt');
    }

    private function render($view, $data)
    {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}
