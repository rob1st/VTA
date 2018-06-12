<?php
use codeguy\Upload;
require 'vendor/autoload.php';

session_start();
include('SQLFunctions.php');
include_once('error_handling/sqlErrors.php');
include('utils/utils.php');
include('uploadAttachment.php');
$colorsJson = json_decode(file_get_contents('webColors.json'), true);
$i = 0;
$j = 0;
// include('uploadImg.php');
$link = f_sqlConnect();

$date = date('Y-m-d');

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

echo "<pre style='color: {$colorsJson['coolColors'][$i]}'>";
var_dump($fieldList);
echo "</pre>";
$i++;

$fieldsArr = array_fill_keys(explode(',', $fieldList), '?');
unset($fieldsArr['id'], $fieldsArr['created_by'], $fieldsArr['form_modified']);
// append keys that do not or may not come from html form
$post = ['updated_by' => $_SESSION['UserID']] + $post;
$post['resolution_disputed'] || $post['resolution_disputed'] = 0;
$post['structural'] || $post['structural'] = 0;

$assignmentList = implode(' = ?, ', array_keys($fieldsArr)).' = ?';
$sql = "UPDATE BARTDL SET $assignmentList WHERE id=$defID";

echo "<pre style='color: {$colorsJson['coolColors'][$i]}'>";
var_dump($fieldsArr);
echo "</pre>";
$i++;

if ($stmt = $link->prepare($sql)) {
    $types = 'iiiisssiiiiiissssssssi';
    echo "<p id='sql' style='color: {$colorsJson['coolColors'][$i]}'>$sql</p>";
    $i++;
    echo "
        <p id='status_bart' style='color: {$colorsJson['coolColors'][$i]}'>status_bart ".intval($post['status_bart'])."</p>
        <p id='status_bart' style='color: {$colorsJson['coolColors'][$i]}'>is_int(status_bart) ".boolToStr(is_int(intval($post['status_bart'])))."</p>
        <p id='field_count' style='color: {$colorsJson['coolColors'][$i]}'>{$stmt->field_count}</p>
        <p id='param_count' style='color: {$colorsJson['coolColors'][$i]}'>{$stmt->param_count}</p>";
    $i++;
    echo "<pre style='color: {$colorsJson['coolColors'][$i]}'>";
    var_dump($post);
    echo "</pre>";
    $i++;

    if ($stmt->bind_param($types,
        intval($post['updated_by']),
        intval($post['creator']),
        intval($post['next_step']),
        intval($post['bic']),
        $link->escape_string($post['descriptive_title_vta']),
        $link->escape_string($post['root_prob_vta']),
        $link->escape_string($post['resolution_vta']),
        intval($post['status_vta']),
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
        $link->escape_string($post['dateClose_bart']),
        intval($post['status_bart'])
    )) {
        echo "<p style='color: {$colorsJson['coolColors'][$i]}'>$types</p>";
        $i++;
        if ($stmt->execute()) {
            echo "
                <p id='affected_rows' style='color: {$colorsJson['coolColors'][$i]}'>affected_rows {$stmt->affected_rows}</p>
                <p id='defID' style='color: {$colorsJson['coolColors'][$i]}'>ID = $defID</p>";
            $i++;
            
            $stmt->close();
    
            // insert new comment if one was submitted
            if ($bdCommText) {
                $sql = "INSERT bartdlComments (bdCommText, userID, bartdlID) VALUES (?, ?, ?)";
                $types = 'sii';
                
                if(!$stmt = $link->prepare($sql)) printSqlErrorAndExit($link, $sql);
                else print "<p id='commentSql'>$sql</p>";
                if(!$stmt->bind_param($types,
                    $link->escape_string($bdCommText),
                    intval($userID),
                    intval($defID))) printSqlErrorAndExit($stmt, $sql);
                else print "<p id='commentsParams'>$types, $bdCommText, $userID, $defID</p>";
                if(!$stmt->execute()) printSqlErrorAndExit($stmt, $sql);
                else print "<p id='newCommentID'>$stmt->insert_id</p>";
                
                $stmt->close();
            }
            
            // upload and insert new attachment if submitted
            if ($attachmentKey) {
                if ($href = uploadAttachment($link, $attachmentKey, $folder, $defID)) {
                    print "
                        <h4 style='darkTurquoise'>
                            <a href='$href'>$href</a>
                        </h4>";
                } else print "<h2 style='color: mediumVioletRed'>There musta been some problem with the upload</h4>";
            }

            echo "
                <a href='ViewDef.php?bartDefID=$defID' class='btn btn-large btn-primary'>View updated deficiency</a>";
            
            $link->close();
            // header("Location: ViewDef.php?bartDefID={$defID}");
        } else printSqlErrorAndExit($stmt, $sql);
    } else printSqlErrorAndExit($stmt, $sql);
} else printSqlErrorAndExit($link, $sql);
