<?php
session_start();
include('SQLFunctions.php');
include('uploadImg.php');
$link = f_sqlConnect();

$date = date('Y-m-d');
$nullVal = null;

// prepare POST and sql string for commit
$post = $_POST;
$fieldList = file_get_contents('bartdl.sql');
$fieldsArr = array_fill_keys(explode(',', $fieldList), null);
unset($fieldsArr['ID']); // unset ID b/c it will be generated by MySQL
$fieldList = substr(file_get_contents('bartdl.sql'), strpos($fieldList, 'ID,') + 3);
$sql = 'INSERT INTO BARTDL ('.$fieldList.') VALUES ('.implode(', ', array_fill(0, 23, '?')).')';

if ($stmt = $link->prepare($sql)) {
    $types = 'isissssssssiisssisisssi';
    // if ($stmt->bind_params(
        
    // )) {
        echo "
            <div style='margin-top: 3.5rem; color: brown'>
                <p>$fieldList</p>
                <p>$sql</p>
                <p>$types</p>
            </div>";
        echo "<pre>";
        var_dump($post);
        echo "</pre>";
    // } else {
    //     echo "<pre style='margin-top: 3.5rem; color: limeGreen'>{$stmt->error}</pre>";
    //     $stmt-close();
    //     $link->close();
    //     exit;
    // }
} else {
    echo "<pre style='margin-top: 3.5rem; color: fuchsia'>{$link->error}</pre>";
    $link->close();
    exit;
}