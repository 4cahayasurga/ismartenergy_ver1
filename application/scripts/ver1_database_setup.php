<?php
if(PHP_SAPI!=='cli')exit;
sqlsrv_configure('WarningsReturnAsErrors',0);
define('BASEPATH',dirname(__DIR__,2).'/system/');define('ENVIRONMENT','production');
require dirname(__DIR__).'/config/database.php';
$c=$db['default'];$cn=sqlsrv_connect($c['hostname'],array('Database'=>'master','UID'=>$c['username'],'PWD'=>$c['password'],'CharacterSet'=>'UTF-8','LoginTimeout'=>8,'Encrypt'=>false));
function runq($sql){global $cn;$s=sqlsrv_query($cn,$sql);if(!$s){foreach(sqlsrv_errors()?:array() as $e)fwrite(STDERR,$e['message']."\n");exit(1);}return $s;}
if(!$cn){foreach(sqlsrv_errors()?:array() as $e)fwrite(STDERR,$e["message"]."\n");exit(1);}
$s=runq("SELECT name FROM sys.databases WHERE name IN ('SmartFuel_JBT','SmartFuel_JBT_AUDIT_FIXED','iSmartEnergy_Ver1')");while($r=sqlsrv_fetch_array($s,SQLSRV_FETCH_ASSOC))echo $r['name']."\n";
if(in_array('--create',$argv,true)){
 $s=runq("SELECT DB_ID('iSmartEnergy_Ver1') id");$r=sqlsrv_fetch_array($s,SQLSRV_FETCH_ASSOC);if($r['id']){echo "Target exists; restore skipped\n";exit;}
 $s=runq("SELECT CONVERT(nvarchar(4000),SERVERPROPERTY('InstanceDefaultBackupPath')) b, CONVERT(nvarchar(4000),SERVERPROPERTY('InstanceDefaultDataPath')) d, CONVERT(nvarchar(4000),SERVERPROPERTY('InstanceDefaultLogPath')) l");$paths=sqlsrv_fetch_array($s,SQLSRV_FETCH_ASSOC);
 if(empty($paths['d'])||empty($paths['l'])){fwrite(STDERR,"SQL Server default data/log paths unavailable.\n");exit(1);}if(empty($paths['b']))$paths['b']=$paths['d'];
 $backup=rtrim($paths['b'],'\\/').'\\ismartenergy_ver1_initial_20261028.bak';$esc=function($v){return str_replace("'","''",$v);};
 $s=runq("BACKUP DATABASE [SmartFuel_JBT] TO DISK=N'".$esc($backup)."' WITH COPY_ONLY, INIT, CHECKSUM");while(($n=sqlsrv_next_result($s))===true){}if($n===false){fwrite(STDERR,"Backup failed; target was not restored.\n");exit(1);}
 $s=runq("RESTORE FILELISTONLY FROM DISK=N'".$esc($backup)."'");$moves=array();$i=0;while($r=sqlsrv_fetch_array($s,SQLSRV_FETCH_ASSOC)){$i++;$dest=rtrim($r['Type']==='L'?$paths['l']:$paths['d'],'\\/').'\\iSmartEnergy_Ver1_'.$i.($r['Type']==='L'?'.ldf':'.mdf');$moves[]="MOVE N'".$esc($r['LogicalName'])."' TO N'".$esc($dest)."'";}
 $s=runq("RESTORE DATABASE [iSmartEnergy_Ver1] FROM DISK=N'".$esc($backup)."' WITH ".implode(',',$moves).", RECOVERY, CHECKSUM");while(($n=sqlsrv_next_result($s))===true){}if($n===false){echo "Restore failed\n";exit(1);}echo "Separate database iSmartEnergy_Ver1 restored successfully\n";
}



