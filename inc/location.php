<?php
require_once '../inc/sqlFunctions.php';
require_once '../inc/sqlStrings.php';

if ($action === 'list') {
    $sql = $sqlStrings['component']['list'];
    
    $link = connect();
    
    $res = $link->query($sql);
    
    $data = array();
    
    while ($row = $res->fetch_row()) {
        $data[] = array(
            'id' => $row[0],
            'name' => $row[1],
            'description' => $row[2],
            'href' => "/public_html/manage.php/update/component?id={$row[0]}"
        );
    }
    
    $count = $res->num_rows;
    
    $res->close();
    $link->close();
}
