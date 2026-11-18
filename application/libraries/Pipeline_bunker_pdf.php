<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** Menerbitkan dan melampirkan PDF dari approval sah tanpa unggah ulang oleh Customer. */
class Pipeline_bunker_pdf
{
    public function html($row,$pdf)
    {
        $ci=get_instance();require_once APPPATH.'libraries/Pipeline_approval_qr.php';
        $proof=null;$a=$ci->flow->approved_bunker($row);
        if($a){
            require_once APPPATH.'third_party/pipeline_qr/qrlib.php';
            $tmp=tempnam(sys_get_temp_dir(),'bunker-qr-');if(!$tmp)throw new RuntimeException('QR tidak dapat dibuat.');
            try{QRcode::png(Pipeline_approval_qr::payload($a),$tmp,QR_ECLEVEL_M,4,4);$png=file_get_contents($tmp);}finally{unlink($tmp);}
            $proof=array('png'=>'data:image/png;base64,'.base64_encode($png),'url'=>site_url('Pipeline_verify/index/'.$a['Token']),'name'=>$a['Approver'],'date'=>$a['ApprovedAt'],'number'=>$a['DocumentNo']);
        }
        $letter=json_decode($row['Letter'],true);$logo=APPPATH.'storage/customer_flow/'.$letter['Logo'];
        if(!is_file($logo))throw new RuntimeException('Logo yang tersimpan tidak ditemukan.');
        return $ci->load->view('pipeline/bunker_print',array('request'=>$row,'s'=>$letter,'letter'=>$ci->pipeline_bunker_letter,'pdf_mode'=>$pdf,
            'workflow_bunker_proof'=>$proof,'workflow_url'=>site_url('Pipeline_flow/document/'.$row['RequestID'].'/Bunker'),
            'workflow_logo'=>$pdf?str_replace(chr(92),'/',$logo):'data:'.$letter['LogoMime'].';base64,'.base64_encode(file_get_contents($logo))),true);
    }
    public function ensure($row)
    {
        $ci=get_instance();if(!$ci->flow->approved_bunker($row))return null;
        $path=$ci->flow->bunker_pdf($row);
        if(!$path){
            require_once FCPATH.'vendor/autoload.php';$temp=sys_get_temp_dir().'/ismartenergy_ver1_pdf';
            if(!is_dir($temp))mkdir($temp,0700,true);
            $pdf=new \Mpdf\Mpdf(array('mode'=>'c','format'=>'A4','default_font'=>'times','margin_top'=>9,'margin_bottom'=>10,'margin_left'=>26,'margin_right'=>15,'tempDir'=>$temp));
            $pdf->WriteHTML($this->html($row,true));
            $path=$ci->flow->save_bunker_pdf((int)$row['RequestID'],hash('sha256',$row['Letter']),$pdf->Output('','S'));
        }
        if(in_array($row['Status'],array('DRAFT','REVISION','SUBMITTED'),true))$ci->flow->attach_bunker_pdf((int)$row['RequestID']);
        return $path;
    }
}
