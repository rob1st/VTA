<?php
require_once '../inc/sqlFunctions.php';
require_once '../inc/sqlStrings.php';

$sql = $sqlStrings['listAll'];

$linkPointers = [
    'location' => 'location',
    'system' => 'system',
    'component' => 'component',
    'deficiency type' => 'defType',
    'contract' => 'contract',
    'evidence' => 'evidence',
    'status' => 'status',
    'severity' => 'severity',
    'test status' => 'testStatus'
];

try {
    $link = connect();
    
    if (!$data = $link->query($sql)) throw new mysqli_sql_exception($link->getLastError());
    
    foreach ($data as &$row) {
        $row['href'] = "/public_html/manage.php/list/{$linkPointers[$row['name']]}";
        $row['name'] = ucwords($row['name']);
    }
    
    $count = $link->count;
} catch (mysqli_sql_exception $e) {
    echo "<pre style='color: deepPink'>There was a problem fetching from the database: $e</pre>";
} finally {
    $link->disconnect();
    $cardHeading = '';
}
