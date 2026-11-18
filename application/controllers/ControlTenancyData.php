<?php
class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Bbm_model');
    }

    public function index()
    {
        $data['total_penerima'] = $this->Bbm_model->countRecipients();
        $data['total_transaksi'] = $this->Bbm_model->countTransactions();
        $data['quota_used']     = $this->Bbm_model->quotaUsedPercent();

        $daily = $this->Bbm_model->getDailyTransactions();
        $data['daily_labels'] = array_column($daily, 'hari');
        $data['daily_values'] = array_column($daily, 'total');

        $data['recipients'] = $this->Bbm_model->getRecipientsLimit(4);
        $data['sales']      = $this->Bbm_model->getSales();

        $this->load->view('dashboard_bbm', $data);
    }
}