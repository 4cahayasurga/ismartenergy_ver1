<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbb_material extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Spbb_model');
        $role = $this->Spbb_model->get_role($this->session->userdata('MIS_LOGGED_ID'));
        if (!in_array($role['role'], array('PEMILIK_SPBB', 'SUPERADMIN')) &&
            strtolower($this->session->userdata('MIS_LOGGED_ID')) !== 'admin') {
            show_error('Akses ditolak', 403);
        }
    }

    public function index()
    {
        $data['judul'] = 'Data Material dan Harga';
        $data['spbb_list'] = $this->Spbb_model->spbb_list();
        $data['materials'] = $this->Spbb_model->material_list();
        $data['price_history'] = $this->Spbb_model->price_history();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('spbb/material', $data);
        $this->load->view('templates/footer');
    }

    public function save()
    {
        if (!$this->input->post()) {
            redirect('Spbb_material');
        }

        $fuel_id = $this->input->post('FuelID', true);
        $spbb_id = $this->input->post('SpbbID', true);
        $fuel_code = strtoupper(trim($this->input->post('FuelCode', true)));
        $fuel_name = trim($this->input->post('FuelName', true));
        $unit_price = (float) $this->input->post('UnitPrice', true);
        $effective_date = $this->input->post('EffectiveDate', true);
        $is_active = $this->input->post('IsActive') ? 1 : 0;

        if (!$spbb_id || !$fuel_code || !$fuel_name || $unit_price < 0 || !$effective_date) {
            $this->session->set_flashdata('Gagal', 'Data material, SPBB, harga, dan tanggal efektif wajib diisi');
            redirect('Spbb_material');
        }

        if ($fuel_id) {
            $ok = $this->Spbb_model->update_material_price($fuel_id, array(
                'SpbbID' => $spbb_id,
                'FuelCode' => $fuel_code,
                'FuelName' => $fuel_name,
                'UnitPrice' => $unit_price,
                'EffectiveDate' => $effective_date,
                'IsActive' => $is_active,
                'UpdatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
            ));
        } else {
            $ok = $this->Spbb_model->create_material_price(array(
                'SpbbID' => $spbb_id,
                'FuelCode' => $fuel_code,
                'FuelName' => $fuel_name,
                'UnitPrice' => $unit_price,
                'EffectiveDate' => $effective_date,
                'IsActive' => $is_active,
                'CreatedBy' => $this->session->userdata('MIS_LOGGED_ID'),
            ));
        }

        $this->session->set_flashdata($ok ? 'Berhasil' : 'Gagal', $ok ? 'Data material dan harga berhasil disimpan' : 'Gagal menyimpan data material');
        redirect('Spbb_material');
    }
}
