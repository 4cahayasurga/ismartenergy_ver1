<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
define('BASEPATH',dirname(__DIR__,2).'/system/');define('ENVIRONMENT','production');
require dirname(__DIR__).'/config/database.php';
$c=$db['default'];$conn=sqlsrv_connect($c['hostname'],array('Database'=>$c['database'],'UID'=>$c['username'],'PWD'=>$c['password'],'CharacterSet'=>'UTF-8','Encrypt'=>!empty($c['encrypt']),'LoginTimeout'=>5));
if(!$conn){fwrite(STDERR,"Database unavailable\n");exit(1);}
function query_checked($conn,$sql,$params=array()){$s=sqlsrv_query($conn,$sql,$params);if(!$s)throw new RuntimeException('SQL check failed: '.json_encode(sqlsrv_errors()));return $s;}
try {
 foreach(array('PipelineDocument','PipelineRate','PipelineInvoice','PipelineInvoiceLine','PipelineInvoiceDelivery','PipelinePayment') as $table){$s=query_checked($conn,'SELECT COUNT(*) AS Records FROM '.$table);$r=sqlsrv_fetch_array($s,SQLSRV_FETCH_ASSOC);echo $table.': ready ('.$r['Records']." records)\n";}
 // Validate identity retrieval and payment constraints without retaining test records.
 sqlsrv_begin_transaction($conn);
 $s=query_checked($conn,"INSERT INTO PipelineInvoice (InvoiceNumber,SpbbID,CustomerID,CustomerName,InvoiceDate,DueDate,PeriodStart,PeriodEnd,IssuerName,Place,Signatory,SignatoryTitle,Subject,ConversionMode,Subtotal,Tax,GrandTotal,CreatedBy) OUTPUT INSERTED.InvoiceID VALUES (?,0,'test','Test','2026-10-01','2026-10-08','2026-09-01','2026-09-30','Test','Test','Test','Test','Test','LITER_1000',120000,14400,134400,'automated-test')",array('TEST-ROLLBACK-'.bin2hex(random_bytes(8))));
 $r=sqlsrv_fetch_array($s,SQLSRV_FETCH_ASSOC);if(!$r['InvoiceID'])throw new RuntimeException('Identity not returned');
 query_checked($conn,"INSERT INTO PipelinePayment(InvoiceID,PaymentDate,Amount,ReferenceNo,CreatedBy) VALUES (?,'2026-10-02',400,'TEST','automated-test')",array($r['InvoiceID']));
 sqlsrv_rollback($conn);echo "PASS: SQL invoice identity and partial payment insert; test transaction rolled back.\n";
}catch(Exception $e){sqlsrv_rollback($conn);fwrite(STDERR,$e->getMessage()."\n");exit(1);}finally{sqlsrv_close($conn);}
