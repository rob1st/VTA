<?php
require_once '../inc/sqlFunctions.php';
require_once '../inc/sqlStrings.php';

$formCtrls = [
    "<label for='locationName' class='required'>Location name</label>
    <input name='locationName' type='text' maxlength='100' class='form-control item-margin-bottom' required>",
    "<label for='locationDescrip'>Location description</label>
    <textarea name='locationDescrip' maxlength='100' class='form-control item-margin-bottom'></textarea>"
];

if ($action === 'list') {
    $sql = $sqlStrings['location']['list'];
    
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

$cardHeading = $action === 'list'
    ? 'Locations'
    : 'Enter location information';
$tableName = 'location';
$target = 'commitNewData.php';
