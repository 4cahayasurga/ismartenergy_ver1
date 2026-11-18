<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** Mencocokkan identitas rekening pengirim; OCR tidak membuktikan transaksi bank asli. */
class Pipeline_payment_ocr
{
    private function normalized($value) { return trim(preg_replace('/[^A-Z0-9]+/',' ',strtoupper((string)$value))); }
    public function compare($text,$profile)
    {
        $expected=array();
        foreach(array('BankAccountName','BankName','BankAccountNumber') as $key) $expected[$key]=(string)($profile[$key]??'');
        $out=array('status'=>'REVIEW','message'=>'Identitas pengirim belum terbaca lengkap; periksa bukti asli.','expected'=>$expected,
            'checked_at'=>date('Y-m-d H:i:s'),'text'=>mb_substr((string)$text,0,30000),'matches'=>array());
        foreach($expected as $value) if(trim($value)==='') {$out['message']='Lengkapi identitas rekening pengirim pada Profil & Logo Perusahaan, kemudian unggah ulang bukti bayar.';return $out;}
        $parsed=$this->extract($text);$out['extracted']=$parsed;
        $number=preg_replace('/\D/','',$expected['BankAccountNumber']);
        $bank=function($v){return trim(preg_replace('/\bBANK\b/','',$this->normalized($v)));};
        $out['matches']=array('name'=>$parsed['sender_name']!=='' && $this->normalized($parsed['sender_name'])===$this->normalized($expected['BankAccountName']),
            'bank'=>$parsed['sender_bank']!=='' && $bank($parsed['sender_bank'])===$bank($expected['BankName']), 'number'=>false);
        $account=$parsed['sender_account'];
        if($account!=='') {
            $masked=$parsed['account_visibility']!=='FULL';
            if(!$masked) {
                $out['matches']['number']=preg_replace('/\D/','',$account)===$number;
                if(!$out['matches']['number']) {$out['status']='MISMATCH';$out['message']='Nomor rekening pengirim berbeda dari rekening perusahaan.';}
            } else {
                // Dots/stars vary by application; only visible prefix and suffix are evidence.
                preg_match('/^([0-9]*)/',preg_replace('/[ .-]/','',$account),$prefix);
                preg_match('/([0-9]+)$/u',$account,$suffix);
                $head=$prefix[1]??'';$tail=$suffix[1]??'';
                if($parsed['account_visibility']==='SUFFIX')$head='';
                if($parsed['account_visibility']==='PREFIX')$tail='';
                $partial=($head!==''||$tail!=='') && ($head===''||strpos($number,$head)===0) && ($tail===''||substr($number,-strlen($tail))===$tail);
                $out['matches']['visible_account']=$partial;
                if(!$partial && ($head!==''||$tail!=='')) {$out['status']='MISMATCH';$out['message']='Digit rekening pengirim yang terlihat tidak cocok dengan profil perusahaan.';}
                else $out['message']='Digit rekening pengirim yang terlihat cocok, tetapi sebagian nomor disamarkan. Periksa bukti asli; kecocokan nomor lengkap belum dapat dipastikan.';
            }
        }
        if($out['matches']['name'] && $out['matches']['bank'] && $out['matches']['number']) {
            $out['status']='MATCH';$out['message']='Nama pemilik, bank, dan nomor rekening pengirim lengkap cocok dengan profil perusahaan.';
        }
        if($parsed['sender_name']==='' && $out['status']==='REVIEW') $out['message'].=' Nama pengirim tidak tercantum atau belum terbaca.';

        return $out;
    }
    /** Memisahkan rekening pengirim dari penerima pada Livin Mandiri dan bukti BCA. */
    public function extract($text)
    {
        $text=str_replace(array("\r\n","\r"),"\n",(string)$text);
        $out=array('format'=>'OTHER','sender_name'=>'','sender_bank'=>'','sender_account'=>'','amount'=>'','reference'=>'','transaction_time'=>'','transfer_status'=>'','account_visibility'=>'');
        if(preg_match('/(?:LIVIN|MANDIRI)/iu',$text))$out['format']='MANDIRI';
        elseif(preg_match('/\bBCA\b/iu',$text))$out['format']='BCA';
        if(preg_match('/TRANSFER\s+(?:BERHASIL|SUCCESSFUL)/iu',$text))$out['transfer_status']='SUCCESS';
        if(preg_match('/(?:REKENING\s+(?:SUMBER|ASAL|PENGIRIM)|SUMBER\s+DANA|SOURCE\s+OF\s+FUND|DETAIL\s+PENGIRIM|PENGIRIM|SENDER|FROM\s+ACCOUNT|DEBIT\s+ACCOUNT)\s*[:\-]?\s*(.+)/isu',$text,$m)) {
            $block=preg_split('/(?:PENERIMA|REKENING\s+TUJUAN|TUJUAN\s+TRANSFER|BENEFICIARY|RECIPIENT|TO\s+ACCOUNT|SOURCE\s+CURRENCY|KETERANGAN\s+TRANSAKSI|TRANSFER\s+AMOUNT|REMARKS|REFERENCE\s+NO)/iu',$m[1])[0];
            $rows=array_values(array_filter(array_map('trim',explode("\n",$block))));
            if(preg_match('/\b(?:BANK\s+)?(MANDIRI|BCA|BNI|BRI|BSI)\b/iu',$block,$bank))$out['sender_bank']=strtoupper($bank[1]);
            elseif($out['format']==='BCA')$out['sender_bank']='BCA';
            foreach($rows as $row) {
                if(preg_match('/(?:NO\.?\s*(?:REKENING|REK)|ACCOUNT\s*(?:NO|NUMBER)|REKENING)\s*[:\-]?\s*([0-9*Xx•●·][0-9*Xx•●· .-]{2,50})/iu',$row,$account)
                    || preg_match('/(?:BANK\s+)?(?:MANDIRI|BCA|BNI|BRI|BSI)\s*[-:]\s*([0-9*Xx•●·][0-9*Xx•●· .-]{2,50})/iu',$row,$account)
                    || preg_match('/^([0-9*Xx•●·][0-9*Xx•●· .-]{2,50})$/u',$row,$account)) {$out['sender_account']=trim($account[1]);continue;}
                if(preg_match('/^(?:BANK\s+)?(?:MANDIRI|BCA|BNI|BRI|BSI)$/iu',$row))continue;
                if(preg_match('/^[\p{L} .,&\x{2019}\'-]{3,200}$/u',$row))$out['sender_name']=trim($out['sender_name'].' '.$row);
            }
        }
        if($out['sender_account']!=='') {
            if(preg_match('/[*xX•●·]/u',$out['sender_account']))$out['account_visibility']='MASKED';
            elseif(strlen(preg_replace('/\D/','',$out['sender_account']))<=4)$out['account_visibility']=$out['format']==='BCA'?'PREFIX':'SUFFIX';
            else $out['account_visibility']='FULL';
        }
        if(preg_match('/(?:TOTAL\s+TRANSAKSI|TRANSFER\s+AMOUNT)\s*(?:RP\.?|IDR)\s*([0-9IOol.,]+)/iu',$text,$amount)
            || preg_match('/(?:RP\.?|IDR)\s*([0-9][0-9.,]+)/iu',$text,$amount)) {
            $v=strtr($amount[1],array('I'=>'1','l'=>'1','O'=>'0','o'=>'0'));if(preg_match('/\.[0-9]{2}$/',$v))$v=str_replace(',','',$v);elseif(preg_match('/,[0-9]{2}$/',$v))$v=str_replace(',','.',str_replace('.','',$v));else $v=str_replace(array('.',','),'',$v);
            $out['amount']=$v;
        }
        if(preg_match('/(?:NO\.?\s*REF\.?|REFERENCE\s+NO\.?)\s*[:\-]?\s*([0-9][A-Z0-9]+(?:[\r\n]+[0-9][A-Z0-9]+)?)/iu',$text,$ref))$out['reference']=preg_replace('/\s/','',$ref[1]);
        if(preg_match('/\b[0-9]{1,2}\s+[A-Z]{3,9}\s+[0-9]{4}\s*[·•.]?\s*[0-9]{1,2}:[0-9]{2}:[0-9]{2}(?:\s+WIB)?/iu',$text,$date))$out['transaction_time']=$date[0];
        return $out;
    }
    /** Memilih rekening yang cocok dari daftar tersimpan, tanpa menganggap digit parsial unik. */
    public function compare_accounts($text,$profile)
    {
        require_once APPPATH.'libraries/Pipeline_bank_accounts.php';
        $accounts=(new Pipeline_bank_accounts())->from_profile($profile);
        if(!$accounts)return $this->compare($text,array());
        $results=array();$matches=array();$partialMatches=array();
        foreach($accounts as $index=>$account){
            $result=$this->compare($text,$account);$result['account_index']=$index;
            $results[]=$result;if($result['status']==='MATCH')$matches[]=$result;
            $parsed=$result['extracted']??array();
            $visible=preg_replace('/[ .-]/','',$parsed['sender_account']??'');
            if ($result['status']==='REVIEW' && !empty($result['matches']['visible_account'])
                && !empty($result['matches']['name']) && !empty($result['matches']['bank'])
                && ($parsed['transfer_status']??'')==='SUCCESS'
                && strlen(preg_replace('/\D/','',$visible))>=4
                && preg_match('/^[0-9]*[*xX•●·]*[0-9]*$/uD',$visible)) {
                $partialMatches[]=$result;
            }
        }
        if(count($matches)===1)$out=$matches[0];
        elseif (!$matches && count($partialMatches)===1) {
            $out=$partialMatches[0];
            $out['status']='PARTIAL_MATCH';
            $out['message']='Nama, bank, dan digit yang terlihat cocok dengan satu rekening terdaftar. Pesanan dapat dikirim untuk pemeriksaan bukti oleh Petugas Bunkering / Pipanisasi.';
        }
        else {
            usort($results,function($a,$b){
                $score=function($r){return ($r['status']==='REVIEW'?10:0)+(!empty($r['matches']['visible_account'])?20:0)+(!empty($r['matches']['number'])?30:0)+(!empty($r['matches']['name'])?4:0)+(!empty($r['matches']['bank'])?2:0);};
                return $score($b)<=>$score($a);
            });
            $out=$results[0];
            if(count($matches)>1 || count($partialMatches)>1){$out['status']='REVIEW';$out['message']='Lebih dari satu rekening terdaftar cocok. Gunakan bukti dengan identitas pengirim yang dapat dibedakan.';}
        }
        $out['expected_accounts']=$accounts;
        $out['account_checks']=array_map(function($r){return array('account_index'=>$r['account_index'],'expected'=>$r['expected'],'status'=>$r['status'],'matches'=>$r['matches']);},$results);
        return $out;
    }
    public function read($path,$profile)
    {
        try {
            $ci=get_instance();$ci->load->library('Pipeline_spp_ocr');
            $result=$ci->pipeline_spp_ocr->read($path);
            $out=$this->compare_accounts($result['text']??'',$profile);
            if(($result['pdf_pages']??0)>1 || (($result['pdf_pages']??null)===null && substr(file_get_contents($path,false,null,0,5),0,5)==='%PDF-')) {$out['status']='REVIEW';$out['message']='Dokumen memiliki beberapa halaman; periksa rekening pada seluruh halaman bukti bayar.';}
            return $out;
        } catch(Throwable $e) {
            $out=$this->compare_accounts('',$profile);$out['status']='REVIEW';$out['message']='OCR belum berhasil membaca bukti bayar. File tersimpan; Petugas Bunkering / Pipanisasi perlu memeriksa dokumen asli.';return $out;
        }
    }
}
