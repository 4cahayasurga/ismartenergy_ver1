<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_verify extends CI_Controller
{
    public function index($token='')
    {
        if (!preg_match('/^[a-f0-9]{48}$/D',$token)) show_404();
        $proof = $this->db->select('A.Approver,A.DocumentNo,A.ApprovedAt,A.Slot,A.DocumentHash,R.BA,R.BASTB,R.Letter,R.Status')
            ->from('PipelineFlowApproval A')->join('PipelineFlowRequest R','R.RequestID=A.RequestID')
            ->where('A.Token',$token)->get()->row_array();
        if (!$proof || ($proof['Slot']==='BUNKER_SUPERVISOR' && $proof['Status']==='REVISION') || !hash_equals($proof['DocumentHash'],hash('sha256',$proof['Slot']==='BUNKER_SUPERVISOR'?$proof['Letter']:(strpos($proof['Slot'],'BA_')===0?$proof['BA']:$proof['BASTB'])))) show_404();
        $text = '<!doctype html><meta charset="utf-8"><meta name="viewport" content="width=device-width"><meta name="robots" content="noindex"><title>Verifikasi Approval</title>'
            .'<main style="font:17px system-ui;max-width:550px;margin:32px auto;padding:16px"><h1>Approval tercatat</h1><dl>';
        foreach (array('Approver'=>'Nama','ApprovedAt'=>'Tanggal dan waktu','DocumentNo'=>'Nomor dokumen') as $key=>$label) {
            $text .= '<dt><strong>'.$label.'</strong></dt><dd>'.html_escape($proof[$key]).'</dd>';
        }
        $this->output->set_header('Cache-Control: no-store')->set_output($text.'</dl></main>');
    }
}
