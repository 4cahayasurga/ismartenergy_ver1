<?php
defined('BASEPATH') or exit('No direct script access allowed');
/** Memvalidasi rekening milik satu perusahaan sebelum disimpan dan diperiksa OCR. */
class Pipeline_bank_accounts
{
    public function from_profile($profile)
    {
        if(isset($profile['BankAccountsJson']) && $profile['BankAccountsJson']!==null) {
            $rows=json_decode($profile['BankAccountsJson'],true);
            if(is_array($rows))return $rows;
        }
        if(trim((string)($profile['BankAccountNumber']??''))==='')return array();
        return array(array_intersect_key($profile,array_flip(array('BankAccountName','BankName','BankAccountNumber'))));
    }
    public function validate($rows)
    {
        if(!is_array($rows)||count($rows)>30)throw new RuntimeException('Maksimal 30 rekening perusahaan.');
        $out=array();$seen=array();
        foreach($rows as $row) {
            if(!is_array($row))throw new RuntimeException('Data rekening tidak valid.');
            $account=array();
            foreach(array('BankAccountName'=>200,'BankName'=>100,'BankAccountNumber'=>50) as $key=>$max){
                $v=$row[$key]??'';
                if(!is_string($v)||mb_strlen($v)>$max)throw new RuntimeException('Identitas rekening tidak valid.');
                $account[$key]=trim($v);
            }
            $filled=count(array_filter($account,function($v){return $v!=='';}));
            if(!$filled)continue;
            if($filled!==3)throw new RuntimeException('Lengkapi nama pemilik, bank, dan nomor pada setiap rekening.');
            $digits=preg_replace('/[ .-]/','',$account['BankAccountNumber']);
            if(!preg_match('/^[0-9]{5,50}$/D',$digits))throw new RuntimeException('Nomor rekening harus 5–50 digit angka.');
            $key=preg_replace('/[^A-Z0-9]/','',str_replace('BANK','',strtoupper($account['BankName']))).'/'.$digits;
            if(isset($seen[$key]))throw new RuntimeException('Bank dan nomor rekening yang sama tidak boleh diulang.');
            $seen[$key]=true;$out[]=$account;
        }
        return $out;
    }
}
