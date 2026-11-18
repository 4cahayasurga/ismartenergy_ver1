<?php
// Run from the project root: php application/scripts/pipeline_migrate.php --apply
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
sqlsrv_configure('WarningsReturnAsErrors',0);
define('BASEPATH', dirname(__DIR__, 2) . '/system/');
define('ENVIRONMENT', 'production');
require dirname(__DIR__) . '/config/database.php';
$config = $db['default'];
$connection = sqlsrv_connect($config['hostname'], array('Database' => $config['database'],
    'UID' => $config['username'], 'PWD' => $config['password'], 'CharacterSet' => 'UTF-8', 'Encrypt' => !empty($config['encrypt']), 'LoginTimeout' => 5));
if (!$connection) { fwrite(STDERR, "Database connection unavailable; migration not applied.\n"); exit(1); }
if (in_array('--apply', $argv, true)) {
    foreach (array('20260928_pipeline_delivery.sql','20261013_pipeline_billing.sql','20261015_pipeline_totalizer.sql','20261015_pipeline_standalone.sql','20261015_pipeline_spp_ocr.sql','20261028_ver1.sql','20261104_pipeline_user_management.sql') as $migration) {
    $sql = file_get_contents(dirname(__DIR__, 2) . '/database/'.$migration);
    $statement = sqlsrv_query($connection, $sql);
    if (!$statement) { fwrite(STDERR, "Migration failed: ".$migration."\n"); foreach(sqlsrv_errors()?:array() as $error)fwrite(STDERR,$error["message"]."\n"); exit(1); }
    while (($next = sqlsrv_next_result($statement)) === true) {}
    if ($next === false) { fwrite(STDERR, "Migration failed: ".$migration."\n"); foreach(sqlsrv_errors()?:array() as $error)fwrite(STDERR,$error["message"]."\n"); exit(1); }
    }
}
$statement = sqlsrv_query($connection, "SELECT OBJECT_ID('dbo.PipelineDelivery', 'U') DeliveryTable, OBJECT_ID('dbo.PipelineMenuAccess', 'U') AccessTable, OBJECT_ID('dbo.PipelinePayment', 'U') BillingTable, COL_LENGTH('dbo.PipelineDelivery','TotalizerStart') TotalizerStart, COL_LENGTH('dbo.PipelineDelivery','TotalizerEnd') TotalizerEnd, COL_LENGTH('dbo.PipelineDelivery','TotalizerUpdatedBy') TotalizerUpdatedBy, COL_LENGTH('dbo.PipelineDelivery','TotalizerUpdatedAt') TotalizerUpdatedAt, COL_LENGTH('dbo.PipelineDelivery','BillingCompany') BillingCompany, OBJECT_ID('dbo.PipelineSppCapture','U') SppTable");
$row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC);
$ready=$row['DeliveryTable'] && $row['AccessTable'] && $row['BillingTable'] && $row['TotalizerStart'] && $row['TotalizerEnd'] && $row['TotalizerUpdatedBy'] && $row['TotalizerUpdatedAt'] && $row['BillingCompany'] && $row['SppTable'];
echo $ready ? "Pipeline delivery, totalizers, SPP OCR, documents, rates, invoices and receivables schema ready.\n" : "Pipeline migration pending.\n";
sqlsrv_close($connection);
exit($ready ? 0 : 1);

