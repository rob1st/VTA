<?php
use codeguy\Upload;
require 'vendor/autoload.php';

session_start();
include('SQLFunctions.php');
include_once('error_handling/sqlErrors.php');
include('utils/utils.php');
include('uploadAttachment.php');

$link = f_sqlConnect();

function printException(Exception $exc) {
    print "
        <div style='font-family: monospace'>
            <h4>Caught exception:</h4>
            <h2 style='color: orangeRed'>".$exc->getMessage()."</h2>
            <h3>".$exc->getFile().": ".$exc->getLine()."</h3>
            <h4>$line</h4>
        </div>";
}

// prepare POST and sql string for commit
$post = $_POST;
$defID = $post['id'];
$userID = $_SESSION['UserID'];

// hold onto comments separately
$bdCommText = $post['bdCommText'];

// check for attachment and prepare Upload object
if ($_FILES['attachment']['size']
    && $_FILES['attachment']['name']
    && $_FILES['attachment']['tmp_name']
    && $_FILES['attachment']['type']) {
    $folder = 'uploads/bartdlUploads';
    $attachmentKey = 'attachment';
}

// unset keys from field list that will not be UPDATE'd
$fieldList = preg_replace('/\s+/', '', file_get_contents('bartdl.sql'));
$fieldsArr = array_fill_keys(explode(',', $fieldList), '?');
unset($fieldsArr['id'], $fieldsArr['created_by'], $fieldsArr['form_modified']);

// append keys that do not or may not come from html form
$post = ['updated_by' => $_SESSION['UserID']] + $post;
$post['resolution_disputed'] || $post['resolution_disputed'] = 0;
$post['structural'] || $post['structural'] = 0;

$assignmentList = implode(' = ?, ', array_keys($fieldsArr)).' = ?';
$sql = "UPDATE BARTDL SET $assignmentList WHERE id=$defID";

try {
    if (!$stmt = $link->prepare($sql)) throw new mysqli_sql_exception($link->error);

    $types = 'iiiiisssiiiiissssssss';
    if (!$stmt->bind_param($types,
        intval($post['updated_by']),
        intval($post['creator']),
        intval($post['next_step']),
        intval($post['bic']),
        intval($post['status']),
        $link->escape_string($post['descriptive_title_vta']),
        $link->escape_string($post['root_prob_vta']),
        $link->escape_string($post['resolution_vta']),
        intval($post['priority_vta']),
        intval($post['agree_vta']),
        intval($post['safety_cert_vta']),
        intval($post['resolution_disputed']),
        intval($post['structural']),
        $link->escape_string($post['id_bart']),
        $link->escape_string($post['description_bart']),
        $link->escape_string($post['cat1_bart']),
        $link->escape_string($post['cat2_bart']),
        $link->escape_string($post['cat3_bart']),
        $link->escape_string($post['level_bart']),
        $link->escape_string($post['dateOpen_bart']),
        $link->escape_string($post['dateClose_bart'])
    )) throw new mysqli_sql_exception($stmt->error);

    if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
            
    $stmt->close();
    
    $location = "ViewDef.php?bartDefID=$defID";
    http_response_code(201);
    
    // insert new comment if one was submitted
    if ($bdCommText) {
        $sql = "INSERT bartdlComments (bdCommText, userID, bartdlID) VALUES (?, ?, ?)";
        $types = 'sii';
        
        if(!$stmt = $link->prepare($sql)) throw new mysqli_sql_exception($link->error);
        if(!$stmt->bind_param($types,
            $link->escape_string($bdCommText),
            intval($userID),
            intval($defID))) throw new mysqli_sql_exception($stmt->error);
        if(!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
        
        $stmt->close();
    }
            
    // upload and insert new attachment if submitted
    if ($attachmentKey) uploadAttachment($link, $attachmentKey, $folder, $defID);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    printException($e);
} catch (UploadException $e) {
    http_response_code(500);
    printException($e);
} catch (Exception $e) {
    http_response_code(500);
    printException($e);
}

$link->close();

print "
    <p><a href='DisplayDefs.php?view=BART'>back to DisplayDefs</a></p>
    <p><a href='ViewDef.php?bartDefID=$defID'>view updated def</a></p>
    <p><a href='updateBartDef.php?bartDefID=$defID'>back to updateBartDef</a></p>";

if (http_response_code() < 400) {
    print "<h2 style='color: dodgerBlue'>".http_response_code()."</h2>";
}

if ($location) {
    print "<h3 style='color: dodgerBlue'>$location</h3>";
}

header("Location: $location");
