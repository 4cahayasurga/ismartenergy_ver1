<?php
// Usage: php application/scripts/ver11_customer_migrate.php --database=iSmartEnergy_Ver1 [--apply]
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$options=getopt('',array('database:','apply'));
$expected=$options['database']??'';
if (!is_string($expected) || $expected==='') { fwrite(STDERR,"Specify --database with the expected database name.\n"); exit(1); }

define('BASEPATH',dirname(__DIR__,2).'/system/');
define('ENVIRONMENT','production');
function show_error($message,$code=500) { throw new RuntimeException($message); }
try {
    require dirname(__DIR__).'/config/database.php';
    $config=$db['default'];
    if ($config['database']!==$expected) throw new RuntimeException('Database name differs from configured target.');
    sqlsrv_configure('WarningsReturnAsErrors',0);
    $connection=sqlsrv_connect($config['hostname'],array('Database'=>$expected,'UID'=>$config['username'],
        'PWD'=>$config['password'],'CharacterSet'=>'UTF-8','Encrypt'=>!empty($config['encrypt']),'LoginTimeout'=>5));
    if (!$connection) throw new RuntimeException('Could not connect with the configured database account.');
    $query=function($sql) use ($connection) {
        $statement=sqlsrv_query($connection,$sql);
        if (!$statement) throw new RuntimeException(implode(' ',array_column(sqlsrv_errors()?:array(),'message')));
        return $statement;
    };
    $target=sqlsrv_fetch_array($query('SELECT DB_NAME() AS db'),SQLSRV_FETCH_ASSOC);
    if ($target['db']!==$expected) throw new RuntimeException('Connected database differs from requested target.');
    if (isset($options['apply'])) {
        $statement=$query(file_get_contents(__DIR__.'/20261116_ver11_customer.sql'));
        while (($next=sqlsrv_next_result($statement))===true) {}
        if ($next===false) throw new RuntimeException(implode(' ',array_column(sqlsrv_errors()?:array(),'message')));
    }
    $columns=array(
        'JbtVessel'=>array('DeadweightTonnage','IMONumber','RouteName','MainTankLiters','DailyTankLiters'),
        'PipelineFlowSettings'=>array('ShowCustomerQuota','RequiredVesselFields','ShowTransportAssignment'),
        'PipelineCustomerManagement'=>array('BusinessEntityID','SpbbID','Enabled'),
        'PipelineCustomerVesselAccess'=>array('UserID','VesselID'),
        'PipelineCustomerPasswordInvite'=>array('UserID','TokenHash','ExpiresAt'),
        'PipelineFlowRequest'=>array('Status','Letter','Requirements','Signers','CaptureID','DeliveryID','TotalizerStart','TotalizerEnd'),
        'PipelineFlowSource'=>array('RequestID','CaptureID','SourceKey','VerifiedBy','VerifiedAt'),
        'PipelineFlowApproval'=>array('Slot','DocumentHash','Token'),
        'PipelineShippingProfile'=>array('IssuerCode','BankAccountsJson'),
        'PipelineSppCapture'=>array('ProposedRequestID','DeliveryID','DocumentID'),
        'PipelineFlowFile'=>array('VerificationJson'),
        'PipelineBunkerSequence'=>array('LastNumber'),
        'PipelineDocumentAudit'=>array('Snapshot')
    );
    $metadata=$query("SELECT T.name+'.'+C.name AS field_name FROM sys.tables T JOIN sys.columns C ON C.object_id=T.object_id WHERE T.schema_id=SCHEMA_ID('dbo')");
    $actual=array();
    while ($row=sqlsrv_fetch_array($metadata,SQLSRV_FETCH_ASSOC)) $actual[$row['field_name']]=true;
    $missing=array();
    foreach ($columns as $table=>$fields) foreach ($fields as $field) {
        if (!isset($actual[$table.'.'.$field])) $missing[]=$table.'.'.$field;
    }
    if ($missing) throw new RuntimeException('Missing schema: '.implode(', ',$missing));
    echo "Ver1.1 schema verified on ",$target['db'],PHP_EOL;
    sqlsrv_close($connection);
} catch (Throwable $error) {
    fwrite(STDERR,$error->getMessage().PHP_EOL);
    exit(1);
}
