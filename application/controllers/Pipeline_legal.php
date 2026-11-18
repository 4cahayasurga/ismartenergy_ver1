<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pipeline_legal extends CI_Controller
{
    /** Membaca legalitas melalui otorisasi perusahaan atau administrator. */
    public function file($name='')
    {
        is_login();
        if ($this->input->method()!=='get') show_error('Gunakan tautan dokumen.',405);
        if (!is_string($name) || basename($name)!==$name
            || !preg_match('/^[A-Za-z0-9][A-Za-z0-9._-]{0,240}\.(?:pdf|jpe?g|png)$/iD',$name)) show_404();
        $this->load->library('Pipeline_access');
        $this->load->model('Spbb_model');
        $role=$this->Spbb_model->get_role($this->session->userdata('MIS_LOGGED_ID'));
        if ($this->pipeline_access->allowed('registration_approval') || $this->pipeline_access->allowed('customer_accounts')) {
            // Akses administrator yang telah diotorisasi pada menu terkait.
        } else {
            $this->pipeline_access->require_menu('customer_documents');
            if ($role['role']!=='CUSTOMER' || empty($role['business_entity_id'])) show_404();
            $entity=$this->db->select('NibFile,NpwpFile,DeedFile,AhuFile')
                ->where('BusinessEntityID',(int)$role['business_entity_id'])->get('JbtBusinessEntity')->row_array();
            if (!$entity || !in_array($name,array_values($entity),true)) show_404();
        }
        $root=realpath(FCPATH.'uploads/company_legal');
        $path=realpath(FCPATH.'uploads/company_legal/'.$name);
        if (!$root || !$path || strncmp($path,$root.DIRECTORY_SEPARATOR,strlen($root)+1)!==0 || !is_file($path)) show_404();
        $mime=(new finfo(FILEINFO_MIME_TYPE))->file($path);
        if (!in_array($mime,array('application/pdf','image/jpeg','image/png'),true)) show_404();
        $this->output->set_content_type($mime)->set_header('Content-Disposition: inline; filename="'.$name.'"')
            ->set_header('Cache-Control: private, no-store')->set_header('X-Content-Type-Options: nosniff')
            ->set_output(file_get_contents($path));
    }
}
