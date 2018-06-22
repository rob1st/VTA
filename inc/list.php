<?php
require_once '../inc/sqlFunctions.php';

$sql = file_get_contents('../sql/listCountTables.sql');

$link = connect();

$res = $link->query($sql);

// initialize empty array to store results
$data = array();

while ($row = $res->fetch_row()) {
    $data[] = array(
        'name' => ucfirst($row[0]),
        'count' => $row[1],
        'href' => "manage.php/list/{$row[0]}"
    );
}

$title = 'Manage data';
$pageHeading = 'Manage';
$cardHeading = '';
$count = $res->num_rows;

$res->close();
$link->close();