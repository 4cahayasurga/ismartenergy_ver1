<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification_center extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Spbb_model');
    }

    public function index()
    {
        $user_id = $this->session->userdata('MIS_LOGGED_ID');
        $access = $this->Spbb_model->get_role($user_id);
        $role = $access['role'];
        $items = array();

        if ($role === 'SUPERADMIN' || strtolower($user_id) === 'admin') {
            $this->admin_items($items);
        } elseif ($role === 'CUSTOMER') {
            $this->customer_items($items, $user_id, $access['business_entity_id']);
        } elseif ($role === 'OPERATOR') {
            $this->operator_items($items, $user_id, $access['spbb_ids']);
        } elseif ($role === 'PEMILIK_SPBB') {
            $this->owner_items($items, $access['spbb_ids']);
        }

        usort($items, function ($a, $b) {
            return strcmp((string) $b['time'], (string) $a['time']);
        });
        $items = array_slice($items, 0, 40);
        return $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'count' => count($items),
            'items' => $items,
        )));
    }

    private function admin_items(&$items)
    {
        foreach ($this->db->where('OwnerApprovalStatus', 'PENDING')->order_by('OrderID', 'DESC')->limit(15)->get('SpbbOrder')->result_array() as $row) {
            $url = $row['OrderChannel'] === 'PIPANISASI' ? 'Spbb/owner_pipeline#approval-pesanan' : 'Spbb/owner#approval-pesanan';
            $this->add($items, 'Pesanan '.$row['OrderChannel'].' menunggu approval', $row['InvoiceNo'].' - '.number_format($row['Quantity'], 0).' L', $url, $row['CreatedAt'], 'check-square-o');
        }
        foreach ($this->db->where('Status', 'PENDING')->order_by('RegistrationID', 'DESC')->limit(10)->get('SpbbRegistration')->result_array() as $row) {
            $this->add($items, 'Registrasi perusahaan baru', $row['CompanyName'].' - '.$row['UserID'], 'Superadmin_registration', $row['CreatedAt'], 'user-plus');
        }
        if ($this->db->table_exists('JbtCustomerUserRequest')) {
            foreach ($this->db->where('Status', 'PENDING')->order_by('UserRequestID', 'DESC')->limit(10)->get('JbtCustomerUserRequest')->result_array() as $row) {
                $this->add($items, 'Pengajuan user perusahaan', $row['UserName'].' ('.$row['UserID'].')', 'Superadmin_registration#customer-user-requests', $row['CreatedAt'], 'users');
            }
        }
        if ($this->db->table_exists('JbtVesselQuotaRequest')) {
            foreach ($this->db->where('Status', 'PENDING')->order_by('RequestID', 'DESC')->limit(10)->get('JbtVesselQuotaRequest')->result_array() as $row) {
                $this->add($items, 'Pengajuan kapal/kuota', $row['VesselName'].' - '.number_format($row['RequestedQuota'], 0).' L', 'Ist/Basic_Data/JbtQuota#pengajuan-kuota', $row['CreatedAt'], 'ship');
            }
        }
    }

    private function customer_items(&$items, $user_id, $entity_id)
    {
        $this->load->model('Pipeline_flow_model','branch_flow');
        if ($entity_id && $this->pipeline_access->allowed('flow_approval')) {
            $tasks=$this->db->where('BusinessEntityID',$entity_id)->where('Status','FILLED')
                ->where("JSON_VALUE(Signers,'$.BA_KKM')",$user_id)->get('PipelineFlowRequest')->result_array();
            foreach ($tasks as $task) if ($this->branch_flow->kkm_task($task)) {
                $this->add($items,'Konfirmasi pengisian BBM',$task['RequestNo'],'Pipeline_flow/detail/'.$task['RequestID'].'#konfirmasi-kkm',$task['UpdatedAt'],'check-square-o');
            }
        }
        $orders = $this->db->where('CustomerID', $user_id)->order_by('OrderID', 'DESC')->limit(15)->get('SpbbOrder')->result_array();
        foreach ($orders as $row) {
            $actionable = in_array($row['OrderStatus'], array('MENUNGGU_BUKTI_BAYAR', 'SIAP_PENGISIAN', 'SEDANG_PENGISIAN'), true)
                || ($row['OrderStatus'] === 'SELESAI' && $row['ReceiptConfirmationStatus'] !== 'CONFIRMED');
            if (!$actionable) continue;
            $message = $row['InvoiceNo'].' - '.str_replace('_', ' ', $row['OrderStatus']);
            $this->add($items, 'Transaksi BBM', $message, 'Spbb/customer_history', $row['UpdatedAt'] ?: $row['CreatedAt'], 'shopping-cart');
        }
        if ($entity_id && $this->db->table_exists('JbtVesselQuotaRequest')) {
            foreach ($this->db->where('BusinessEntityID', $entity_id)
                ->where_in('Status', array('APPROVED','REJECTED'))
                ->where('ReviewedAt IS NOT NULL', null, false)
                ->order_by('ReviewedAt', 'DESC')->limit(8)
                ->get('JbtVesselQuotaRequest')->result_array() as $row) {
                $this->add($items, 'Pengajuan kuota '.$row['Status'], $row['VesselName'].' - '.number_format($row['RequestedQuota'], 0).' L', 'Spbb/customer_vessels', $row['ReviewedAt'] ?: $row['CreatedAt'], 'ship');
            }
        }
        if ($entity_id && $this->db->table_exists('JbtCustomerUserRequest')) {
            foreach ($this->db->where('BusinessEntityID', $entity_id)
                ->where_in('Status', array('APPROVED','REJECTED'))
                ->where('ReviewedAt IS NOT NULL', null, false)
                ->order_by('ReviewedAt', 'DESC')->limit(8)
                ->get('JbtCustomerUserRequest')->result_array() as $row) {
                $this->add($items, 'Pengajuan user '.$row['Status'], $row['UserName'].' ('.$row['UserID'].')', 'Spbb/customer_users', $row['ReviewedAt'] ?: $row['CreatedAt'], 'user-plus');
            }
        }
        $documents = $this->Spbb_model->customer_document_status($user_id);
        if (!$documents['complete']) {
            $this->add($items, 'Dokumen legalitas belum lengkap', 'Lengkapi: '.implode(', ', $documents['missing']), 'Spbb/customer_documents', date('Y-m-d H:i:s'), 'folder-open');
        }
    }

    private function owner_items(&$items, $spbb_ids)
    {
        if (!count($spbb_ids)) return;
        $rows = $this->db->where_in('SpbbID', $spbb_ids)->where('OwnerApprovalStatus', 'PENDING')
            ->order_by('OrderID', 'DESC')->limit(20)->get('SpbbOrder')->result_array();
        foreach ($rows as $row) {
            $url = $row['OrderChannel'] === 'PIPANISASI' ? 'Spbb/owner_pipeline#approval-pesanan' : 'Spbb/owner#approval-pesanan';
            $this->add($items, 'Pesanan '.$row['OrderChannel'].' menunggu approval', $row['InvoiceNo'].' - '.number_format($row['Quantity'], 0).' L', $url, $row['CreatedAt'], 'check-square-o');
        }
        if ($this->db->table_exists('JbtVesselQuotaRequest')) {
            foreach ($this->db->where_in('RequestedSpbbID', $spbb_ids)->where('Status', 'PENDING')->order_by('RequestID', 'DESC')->limit(10)->get('JbtVesselQuotaRequest')->result_array() as $row) {
                $this->add($items, 'Pengajuan kuota kapal', $row['VesselName'].' - '.number_format($row['RequestedQuota'], 0).' L', 'Ist/Basic_Data/JbtQuota#pengajuan-kuota', $row['CreatedAt'], 'ship');
            }
        }
    }

    private function operator_items(&$items, $user_id, $spbb_ids)
    {
        if (!count($spbb_ids)) return;
        $this->db->where_in('SpbbID', $spbb_ids)->where_in('OrderStatus', array('SIAP_PENGISIAN','SEDANG_PENGISIAN'));
        $rows = $this->db->order_by('OrderID', 'DESC')->limit(25)->get('SpbbOrder')->result_array();
        foreach ($rows as $row) {
            if ($row['OrderStatus'] === 'SEDANG_PENGISIAN' && $row['OperatorStartedBy'] !== $user_id) continue;
            $this->add($items, $row['OrderStatus'] === 'SIAP_PENGISIAN' ? 'Antrean siap diisi' : 'Pengisian sedang berjalan', $row['InvoiceNo'].' - '.number_format($row['Quantity'], 0).' L', 'Spbb_operator#'.($row['OrderStatus']==='SIAP_PENGISIAN'?'antrean':'pengisian'), $row['UpdatedAt'] ?: $row['CreatedAt'], 'tint');
        }
    }

    private function add(&$items, $title, $message, $url, $time, $icon)
    {
        $items[] = array('title' => $title, 'message' => $message, 'url' => site_url($url), 'time' => $time, 'icon' => $icon);
    }
}
