<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$databaseMode=getenv('ISMART_VER11_DATABASE_MODE') ?: 'shared';
if (!in_array($databaseMode,array('shared','staging'),true)) show_error('Mode database tidak dikenal.',503);
if ($databaseMode==='staging') {
    require __DIR__.'/database_staging.php';
} else {
    $databaseHost=getenv('ISMART_VER11_DB_HOST');
    $databaseName=getenv('ISMART_VER11_DB_NAME');
    if ($databaseHost!==false || $databaseName!==false) {
        if (!$databaseHost || !$databaseName) show_error('Host dan nama database operasional wajib dikonfigurasi.',503);
        $active_group='default';
        $query_builder=true;
        $db['default']=array('dsn'=>'','hostname'=>$databaseHost,'database'=>$databaseName,
            'username'=>getenv('ISMART_VER11_DB_USER')?:'','password'=>getenv('ISMART_VER11_DB_PASSWORD')?:'',
            'dbdriver'=>'sqlsrv','dbprefix'=>'','pconnect'=>false,'db_debug'=>false,'cache_on'=>false,
            'cachedir'=>'','char_set'=>'utf8','dbcollat'=>'','swap_pre'=>'',
            'encrypt'=>getenv('ISMART_VER11_DB_ENCRYPT')!=='0','compress'=>false,'stricton'=>false,
            'failover'=>array(),'save_queries'=>false);
    } else {
        $databaseConfig=getenv('ISMART_VER11_DATABASE_CONFIG') ?: 'D:/xampp1/htdocs/iSmartEnergy_Ver1/application/config/database.php';
        if (!is_file($databaseConfig)) show_error('Konfigurasi database belum tersedia.',503);
        require $databaseConfig;
    }
    unset($databaseConfig,$databaseHost,$databaseName);
}
$db['default']['db_debug']=false;
unset($databaseMode);
