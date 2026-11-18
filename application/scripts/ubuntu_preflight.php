<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
define('BASEPATH',dirname(__DIR__,2).'/system/');define('ENVIRONMENT','production');
require dirname(__DIR__).'/config/database.php';$fail=0;
function check_deploy($ok,$message){global $fail;echo ($ok?'PASS: ':'FAIL: ').$message.PHP_EOL;if(!$ok)$fail++;}
check_deploy(DIRECTORY_SEPARATOR==='/', 'Linux runtime');
foreach(array('sqlsrv','mbstring','gd','fileinfo','curl','openssl','dom','zip') as $extension)check_deploy(extension_loaded($extension),'PHP extension '.$extension);
check_deploy(function_exists('proc_open'),'proc_open available for OCR');
check_deploy(strpos($db['default']['hostname'],'\\')===false&&getenv('ISMART_DB_HOST')!==false,'SQL Server TCP host configured through ISMART_DB_HOST');
check_deploy(getenv('ISMART_DB_USER')!==false&&getenv('ISMART_DB_PASSWORD')!==false,'SQL Server credentials configured through environment');
foreach(array('storage','logs','cache') as $directory)check_deploy(is_writable(dirname(__DIR__).'/'.$directory),'Writable application/'.$directory);
check_deploy(is_writable(sys_get_temp_dir()),'Writable temporary directory');
if(function_exists('proc_open')){foreach(array(array(getenv('SPP_OCR_PYTHON')?:'python3','-c','from PIL import Image; print("Pillow ready")'),array('tesseract','--list-langs'),array('pdftoppm','-v'),array('pdftotext','-v')) as $args){$command=implode(' ',array_map('escapeshellarg',$args));$p=proc_open($command,array(0=>array('pipe','r'),1=>array('pipe','w'),2=>array('pipe','w')),$pipes);$ok=false;if(is_resource($p)){fclose($pipes[0]);$out=stream_get_contents($pipes[1]);$err=stream_get_contents($pipes[2]);fclose($pipes[1]);fclose($pipes[2]);$ok=proc_close($p)===0;if($args[0]==='tesseract')$ok=$ok&&preg_match('/\beng\b/',$out)&&preg_match('/\bind\b/',$out);}check_deploy($ok,'OCR dependency '.basename($args[0]));}}
echo 'PHP '.PHP_VERSION.'; full live Ubuntu transaction acceptance remains required.'.PHP_EOL;exit($fail?1:0);
