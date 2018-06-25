<?php
require_once '../inc/sqlFunctions.php';
require_once '../inc/sqlStrings.php';

$sql = $sqlStrings['listAll'];

$tableNames = [
    'system' => 'System',
    'component' => 'component',
    'deficiency type' => 'defType',
    'contract' => 'Contract',
    'evidence' => 'Evidence',
    'status' => 'Status',
    'severity' => 'Severity',
    'test status' => 'testStatus'
];

$link = connect();

$res = $link->query($sql);

// initialize empty array to store results
$data = array();

while ($row = $res->fetch_row()) {
    $data[] = array(
        'name' => ucfirst($row[0]),
        'count' => $row[1],
        'href' => "/public_html/manage.php/list/{$tableNames[$row[0]]}"
    );
}

$cardHeading = '';
$count = $res->num_rows;

$res->close();
$link->close();