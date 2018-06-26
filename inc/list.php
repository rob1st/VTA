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
    
    if (!$res = $link->query($sql)) throw new mysqli_sql_exception($link->error);
    
    // initialize empty array to store results
    $data = array();
    
    while ($row = $res->fetch_row()) {
        $data[] = array(
            'name' => ucfirst($row[0]),
            'count' => $row[1],
            'href' => "/public_html/manage.php/list/{$linkPointers[$row[0]]}"
        );
    }
    
    $count = $res->num_rows;
} catch (mysqli_sql_exception $e) {
    echo "<pre style='color: deepPink'>There was a problem fetching from the database: $e</pre>";
} finally {
    $cardHeading = '';

    if (isset($res)) $res->close();
    $link->close();
    exit;
}
