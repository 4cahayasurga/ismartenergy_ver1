<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_access
{
    public function menus()
    {
        return array('areas'=>'Basic Data Area', 'master'=>'Basic Data Kapal / Perusahaan', 'bunker'=>'Permohonan Bunker', 'ksop'=>'Surat Pengawasan KSOP', 'rates'=>'Basic Harga Pipanisasi', 'dashboard' => 'Dashboard Pipanisasi', 'orders' => 'Pesanan Pipanisasi',
            'delivery' => 'Input DO Pipanisasi', 'spp_ocr'=>'Upload / Foto SPP', 'export' => 'Ekspor Excel Pipanisasi',
            'pipeline_documents'=>'Dokumen DO / SPP / Loading Order',
            'invoices'=>'Create Invoice Pipanisasi','receivables'=>'Kartu Piutang Pipanisasi');
    }

    public function allowed($menu, $user_id = null, $role = null)
    {
        $ci = get_instance();
        $user_id = $user_id === null ? $ci->session->userdata('MIS_LOGGED_ID') : $user_id;
        if ($role === null) {
            $user = $ci->db->select('UserRole')->where('UserID', $user_id)->get('Login')->row_array();
            $role = isset($user['UserRole']) ? $user['UserRole'] : 'CUSTOMER';
        }
        if ($role === 'SUPERADMIN' || strtolower((string) $user_id) === 'admin') return true;
        if ($menu === 'areas') return false;
        if (!isset($this->menus()[$menu])) return false;
        if ($ci->db->table_exists('PipelineMenuAccess')) {
            $row = $ci->db->where('UserID', $user_id)->where('MenuKey', $menu)->get('PipelineMenuAccess')->row_array();
            if ($row) return (bool) $row['Allowed'];
        }
        return $role === 'PEMILIK_SPBB' || ($role === 'CUSTOMER' && in_array($menu, array('dashboard', 'orders', 'export')));
    }

    public function require_menu($menu)
    {
        if (!$this->allowed($menu)) show_error('Anda tidak memiliki akses ke menu Pipanisasi ini.', 403);
    }
}
