<?php
defined('BASEPATH') or exit('No direct script access allowed');
/** Memeriksa jenis dan identitas dokumen pendukung; bacaan samar tidak diluluskan. */
class Pipeline_flow_document_check
{
    private function normal($v){return preg_replace('/[^A-Z0-9]/','',strtoupper((string)$v));}
    public function compare($text,$row,$kind)
    {
        $letter=json_decode($row['Letter'],true);$normal=$this->normal($text);
        $matches=array();
        foreach(array('RequestNo'=>$row['RequestNo'],'CompanyName'=>$letter['CompanyName']??'','VesselName'=>$letter['VesselName']??'') as $key=>$value){$needle=$this->normal($value);$matches[$key]=$needle!=='' && strpos($normal,$needle)!==false;}
        $patterns=array('Supervision'=>'/PENGAWASAN.*(?:BAHAN\s*BAKAR|BBM|BUNKER)/isu','Notice'=>'/(?:PEMBERITAHUAN|KEGIATAN\s+USAHA|TRANSPORTIR)/iu');
        $type=isset($patterns[$kind]) && preg_match($patterns[$kind],$text);
        $ok=$type && ($matches['RequestNo'] || ($matches['CompanyName'] && $matches['VesselName']));
        return array('status'=>$ok?'MATCH':'REVIEW','message'=>$ok?'Jenis dan identitas dokumen cocok dengan pesanan.':'Jenis dokumen, nomor pengajuan atau identitas kapal/perusahaan belum cocok. Unggah dokumen yang jelas dan sesuai.',
            'checked_at'=>date('Y-m-d H:i:s'),'text'=>mb_substr($text,0,30000),'matches'=>$matches,'type_match'=>(bool)$type);
    }
    public function read($path,$row,$kind)
    {
        try{$ci=get_instance();$ci->load->library('Pipeline_spp_ocr');$result=$ci->pipeline_spp_ocr->read($path);$check=$this->compare($result['text']??'',$row,$kind);
            if(($result['pdf_pages']??0)>1 || (($result['pdf_pages']??null)===null && substr(file_get_contents($path,false,null,0,5),0,5)==='%PDF-')){$check['status']='REVIEW';$check['message']='Dokumen memiliki beberapa halaman; gunakan dokumen per surat yang dapat diperiksa lengkap.';}
            return $check;
        }catch(Throwable $e){return array('status'=>'REVIEW','message'=>'OCR belum berhasil membaca dokumen. Unggah ulang dokumen yang jelas.','checked_at'=>date('Y-m-d H:i:s'));}
    }
}
