<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Spbb_registration_model extends CI_Model
{
    public function create($data)
    {
        $this->db->insert('SpbbRegistration', $data);
        return $this->insert_id();
    }

    public function all($status = null)
    {
        $this->db->select('*');
        $this->db->from('SpbbRegistration');
        if ($status) {
            $this->db->where('Status', $status);
        }
        $this->db->order_by('RegistrationID', 'DESC');
        return $this->db->get()->result();
    }

    public function find($id)
    {
        return $this->db->where('RegistrationID', $id)
            ->get('SpbbRegistration')
            ->row();
    }

    public function update($id, $data)
    {
        return $this->db->where('RegistrationID', $id)
            ->update('SpbbRegistration', $data);
    }

    public function requested_spbb_ids($registration)
    {
        if (!$registration || !$registration->RequestedSpbbIDs) {
            return array();
        }

        $ids = array();
        foreach (explode(',', $registration->RequestedSpbbIDs) as $id) {
            $id = trim($id);
            if ($id !== '') {
                $ids[] = (int) $id;
            }
        }
        return $ids;
    }

    public function spbb_names($ids)
    {
        if (!is_array($ids) || !count($ids)) {
            return '';
        }

        $rows = $this->db->select('No_SPBB, Area')
            ->where_in('LedgerNo', $ids)
            ->get('SPBB')
            ->result();

        $names = array();
        foreach ($rows as $row) {
            $names[] = trim($row->No_SPBB . ' - ' . $row->Area);
        }

        return implode(', ', $names);
    }

    public function generate_whatsapp_url($phone, $message)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        if (!$phone) {
            return '';
        }
        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($message);
    }

    private function insert_id()
    {
        $row = $this->db->query('SELECT SCOPE_IDENTITY() AS id')->row_array();
        return isset($row['id']) ? (int) $row['id'] : (int) $this->db->insert_id();
    }
}
