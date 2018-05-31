<?php
session_start();
include('SQLFunctions.php');
include('uploadImg.php');
$link = f_sqlConnect();
$date = date('Y-m-d');
$nullVal = null;
$post = $_POST;
$fieldList = substr(file_get_contents('bartdl.sql'), strpos($fieldList, 'ID,') + 3);
$sql = 'INSERT INTO BARTDL ('.$fieldList.') VALUES ('.implode(', ', array_fill(0, 22, '?')).')';

if ($stmt = $link->prepare($sql)) {
    $types = 'sissssssssiisssisisssi';
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