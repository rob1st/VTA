<?php
require_once '../inc/sqlFunctions.php';

// this should instead rely on file_get_contents('sql_file')
$queries = array(
    'add' => "INSERT component (compName, compDescrip) VALUES (?, ?)",
    'list' => "SELECT * FROM component WHERE compName <> ''"
);

$sql = $queries['list'];

$link = connect();

$res = $link->query($sql);

$data = array();

while ($row = $res->fetch_row()) {
    $data[] = array(
        'name' => $row[1],
        'href' => ''
    );
}

$count = $res->num_rows;

$res->close();
$link->close();

$title = 'Components';
$pageHeading = 'Manage Components';
$cardHeading = 'Components';
$tableName = 'component';