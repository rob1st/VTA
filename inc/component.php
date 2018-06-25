<?php
require_once '../inc/sqlFunctions.php';
require_once '../inc/sqlStrings.php';

// this should instead rely on file_get_contents('sql_file')
$queries = array(
    'list' => "SELECT * FROM component WHERE compName <> ''"
);

$formCtrls = array(
    "<label for='compName'>Component name</label>
    <input type='text' name='compName' maxlength='100' value='' class='form-control item-margin-bottom'>",
    "<label for='compDescrip'>Component description</label>
    <textarea name='compDescrip' maxlength='1000' class='form-control item-margin-bottom'></textarea>"
);

if ($action === 'list') {
    $sql = $sqlStrings['component']['list'];
    
    $link = connect();
    
    $res = $link->query($sql);
    
    $data = array();
    
    while ($row = $res->fetch_row()) {
        $data[] = array(
            'id' => $row[0],
            'name' => $row[1],
            'description' => $row[2]
        );
    }
    
    $count = $res->num_rows;
    
    $res->close();
    $link->close();
}

$title = ucwords($action . ' ' . $table);
$pageHeading = ucfirst($action . ' ' . $table);
$cardHeading = $action === 'list'
    ? 'Components'
    : 'Enter component information';
$tableName = 'component';
$target = 'commitNewData.php';
