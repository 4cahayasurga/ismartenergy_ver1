<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbb_pertamina extends CI_Controller
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
        $this->render('spbb/pertamina_order', array(
            'judul' => 'Order BBM ke Pertamina',
            'spbb_list' => $this->Spbb_model->spbb_list_by_ids($this->spbb_ids),
            'fuel_list' => $this->Spbb_model->fuel_list($this->spbb_ids),
            'purchases' => $this->Spbb_model->purchases($this->spbb_ids),
            'owner_spbb_ids' => $this->spbb_ids,
        ));
    }

    public function save()
    {
        if (!$this->input->post()) {
            redirect('Spbb_pertamina');
        }

        $spbb_id = (int) $this->input->post('SpbbID', true);
        $fuel_id = (int) $this->input->post('FuelID', true);
        $quantity = (float) $this->input->post('Quantity', true);
        $unit_price = (float) $this->input->post('UnitPrice', true);
        $fuel = $this->Spbb_model->get_fuel($fuel_id);

        if (!in_array($spbb_id, $this->spbb_ids) || !$fuel || (int) $fuel['SpbbID'] !== $spbb_id) {
            $this->flash(false, 'SPBB atau material tidak sesuai dengan akses user');
        } elseif (!$this->input->post('PoNumber', true) || $quantity <= 0 || $unit_price < 0) {
            $this->flash(false, 'Nomor PO, quantity, dan harga wajib diisi dengan benar');
        } else {
            $id = $this->Spbb_model->create_pertamina_purchase($this->user_id, array(
                'SpbbID' => $spbb_id,
                'FuelID' => $fuel_id,
                'PoNumber' => trim($this->input->post('PoNumber', true)),
                'PurchaseDate' => $this->input->post('PurchaseDate', true),
                'RequestedDeliveryDate' => $this->input->post('RequestedDeliveryDate', true),
                'Supplier' => trim($this->input->post('Supplier', true)),
                'PaymentTerms' => trim($this->input->post('PaymentTerms', true)),
                'Quantity' => $quantity,
                'UnitPrice' => $unit_price,
                'PurchaseStatus' => 'DIPESAN',
                'Notes' => trim($this->input->post('Notes', true)),
            ));
            $this->flash((bool) $id, $id ? 'Order Pertamina berhasil disimpan' : 'Order Pertamina gagal disimpan');
        }
        redirect('Spbb_pertamina');
    }

    private function flash($ok, $message)
    {
        $this->session->set_flashdata($ok ? 'Berhasil' : 'Gagal', $message);
    }

    private function render($view, $data)
    {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}
