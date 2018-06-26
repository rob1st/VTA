<?php
require_once "../vendor/autoload.php";
require_once "../inc/sqlFunctions.php";
require_once "../inc/sqlStrings.php";

session_start();

$link = connect();

try {
    if (!$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS))
        throw new Exception('There was a problem with the post data');
        
    // get sql string by value of hidden input in form
    $table = $post['category'];
    $sql = $sqlStrings[$table]['insert'];
    $types = $sqlStrings[$table]['types'];

    if (!$stmt = $link->prepare($sql)) throw new mysqli_sql_exception($link->error);
    if (!$stmt->bind_param($types,
        $post['compName'],
        $post['compDescrip'])
    )
        throw new mysqli_sql_exception($stmt->error);
    if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
    $stmt->close();
    
    header("Location: /public_html/manage.php/list/$table");
    
} catch (mysqli_sql_exception $e) {
    echo "<pre style='color: coral'>$e</pre>";
} catch (Exception $e) {
    echo "<pre style='color: salmon'>$e</pre>";
} finally {
    if (isset($stmt)) $stmt->close();
    $link->close();
    exit;    
}
