<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Hanya konfigurasi. Tidak membuat database, menyalin data atau menjalankan migrasi.
$stagingName=getenv('ISMART_VER11_STAGING_DATABASE');
$stagingHost=getenv('ISMART_VER11_STAGING_SERVER');
if (!$stagingHost || !$stagingName || !preg_match('/^[A-Za-z0-9_]+$/D',$stagingName)
    || in_array(strtolower($stagingName),array('ismartenergy_ver1','ismartenergy'),true)) {
    show_error('Konfigurasi database staging terpisah belum lengkap.',503);
}
$active_group='default'; $query_builder=true;
$db['default']=array('dsn'=>'','hostname'=>$stagingHost,
    'username'=>getenv('ISMART_VER11_STAGING_USER')?:'', 'password'=>getenv('ISMART_VER11_STAGING_PASSWORD')?:'',
    'database'=>$stagingName,'dbdriver'=>'sqlsrv','dbprefix'=>'','pconnect'=>false,
    'db_debug'=>false,'cache_on'=>false,'cachedir'=>'','char_set'=>'utf8','dbcollat'=>'',
    'swap_pre'=>'','encrypt'=>getenv('ISMART_VER11_STAGING_ENCRYPT')!=='0',
    'compress'=>false,'stricton'=>false,'failover'=>array(),'save_queries'=>false);
unset($stagingName,$stagingHost);
