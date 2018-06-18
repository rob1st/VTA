<?php
session_start();
include('SQLFunctions.php');
include('uploadImg.php');

$link = f_sqlConnect();

// prepare POST and sql string for commit
$post = $_POST;
$defID = $post['defID'];
$userID = $_SESSION['UserID'];
$username = $_SESSION['Username'];

// validate POST data
// if it's empty then file upload exceeds post_max_size
// bump user back to form
if (!count($post) || !$defID) {
    include('js/emptyPostRedirect.php');
    exit;
}

// if photo in POST it will be committed to a separate table
if ($_FILES['CDL_pics']['size']
    && $_FILES['CDL_pics']['name']
    && $_FILES['CDL_pics']['tmp_name']
    && $_FILES['CDL_pics']['type']) {
    $CDL_pics = $_FILES['CDL_pics'];
} else $CDL_pics = null;
    
// hold onto comments separately
$cdlCommText = trim($post['cdlCommText']);

// prepare parameterized string from external .sql file
$fieldList = preg_replace('/\s+/', '', file_get_contents('UpdateDef.sql'));
$fieldsArr = array_fill_keys(explode(',', $fieldList), '?');

// unset keys that will not be updated before imploding back to string
unset(
    $fieldsArr['defID'],
    $fieldsArr['created_by'],
    $fieldsArr['dateCreated'],
    $fieldsArr['lastUpdated'],
    $fieldsArr['dateClosed']
);

$assignmentList = implode(' = ?, ', array_keys($fieldsArr)).' = ?';
$sql = "UPDATE CDL SET $assignmentList WHERE defID=$defID";

// append keys that do not or may not come from html form
// or whose values may be ambiguous in $_POST (e.g., checkboxes)
$post += ['updated_by' => $username];

try {
    $success = "<div style='background-color: pink; background-clip: padding-box; border: 5px dashed limeGreen;>%s</div>";
    $successFormat = "<p style='color: %s'>%s</p>";
    
    if (!$stmt = $link->prepare($sql)) throw new Exception($link->error);
    
    $success = sprintf($success, sprintf($successFormat, 'blue', '&#x2714; CDL update stmt prepared') . '%s');
    
    $types = 'iiisiisiiisissssiisss';
    if (!$stmt->bind_param($types,
        intval($post['safetyCert']),
        intval($post['systemAffected']),
        intval($post['location']),
        $link->escape_string($post['specLoc']),
        intval($post['status']),
        intval($post['severity']),
        $link->escape_string($post['dueDate']),
        intval($post['groupToResolve']),
        intval($post['requiredBy']),
        intval($post['contractID']),
        $link->escape_string($post['identifiedBy']),
        intval($post['defType']),
        $link->escape_string($post['description']),
        $link->escape_string($post['spec']),
        $link->escape_string($post['actionOwner']),
        $link->escape_string($post['oldID']),
        intval($post['evidenceType']),
        intval($post['repo']),
        $link->escape_string($post['evidenceLink']),
        $link->escape_string($post['closureComments']),
        $link->escape_string($post['updated_by'])
    )) throw new mysqli_sql_exception(
        $stmt->error
        . ': ' . strlen($types)
        . ', ' . count([
            intval($post['safetyCert']),
            intval($post['systemAffected']),
            intval($post['location']),
            $link->escape_string($post['specLoc']),
            intval($post['status']),
            intval($post['severity']),
            $link->escape_string($post['dueDate']),
            intval($post['groupToResolve']),
            intval($post['requiredBy']),
            intval($post['contractID']),
            $link->escape_string($post['identifiedBy']),
            intval($post['defType']),
            $link->escape_string($post['description']),
            $link->escape_string($post['spec']),
            $link->escape_string($post['actionOwner']),
            $link->escape_string($post['oldID']),
            intval($post['evidenceType']),
            intval($post['repo']),
            $link->escape_string($post['evidenceLink']),
            $link->escape_string($post['closureComments']),
            $link->escape_string($post['updated_by'])
            ])
        . '\n' . $sql);
    
    $success= sprintf($success, sprintf($successFormat, 'forestGreen', '&#x2714; CDL params bound') . '%s');
    
    if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
    
    $success = sprintf($success, sprintf($successFormat, 'dodgerBlue', '&#x2714; CDL insert executed') . '%s');
    
    $stmt->close();
    
    $success = sprintf($success, sprintf($successFormat, 'indigo', '&#x2714; CDL stmt closed') . '%s');
    
    // if INSERT succesful, prepare, upload, and INSERT photo
    if ($CDL_pics) {
        $sql = "INSERT CDL_pics (defID, pathToFile) values (?, ?)";
        if (!$stmt = $link->prepare($sql)) throw new Exception($link->error);
        $success = sprintf($success, sprintf($successFormat, 'cadetBlue', '&#x2714; cdlPics stmt prepared') . '%s');
        if (!$stmt->bind_param('is', $defID, $pathToFile)) throw new mysqli_sql_exception($stmt->error);
        $success = sprintf($success, sprintf($successFormat, 'cornFlower', '&#x2714; cdlPics params bound') . '%s');
        if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
        $success = sprintf($success, sprintf($successFormat, 'aqua', '&#x2714; cdlPics insert executed') . '%s');
        $stmt->close();
        $success = sprintf($success, sprintf($successFormat, 'aquamarine', '&#x2714; cdlPics stmt closed') . '%s');
    } else {
        $success = sprintf($success, sprintf($successFormat, 'cyan', '&#x2714; no cdlPics found') . '%s');
    }
    
    // if comment submitted commit it to a separate table
    if (count($cdlCommText)) {
        $sql = "INSERT cdlComments (defID, cdlCommText, userID) VALUES (?, ?, ?)";
        if (!$stmt = $link->prepare($sql)) throw new Exception($link->error);
        $success = sprintf($success, sprintf($successFormat, 'darkCyan', '&#x2714; cdlComments stmt prepared') . '%s');
        if (!$stmt->bind_param('isi',
            intval($defID),
            $link->escape_string($cdlCommText),
            intval($userID))) throw new mysqli_sql_exception($stmt->error);
        $success = sprintf($success, sprintf($successFormat, 'darkBlue', '&#x2714; cdlComments params bound') . '%s');
        if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
        $success = sprintf($success, sprintf($successFormat, 'darkTurquoise', '&#x2714; cdlComments stmt executed') . '%s');
        $stmt->close();
        $success = sprintf($success, sprintf($successFormat, 'deepSkyBlue', '&#x2714; cdlComments stmt closed') . '%s');
    } else {
        $success = sprintf($success, sprintf($successFormat, 'mediumAquamarine', '&#x2714; no cdlComments found') . '%s');
    }

    $link->close();
    
    $success = sprintf($success, sprintf($successFormat, 'lightSteelBlue', '&#x2714; link closed'));
    print $success;
    
    // header("Location: ViewDef.php?defID=$defID");
} catch (mysqli_sql_exception $e) {
    print "
        <div style='margin-top: 5.5rem;'>
            <p style='min-width: 7.5rem; min-height: 6rem; background-color: coral;'>{$e->getMessage()}</p>
        </div>";
    $stmt->close();
    $link->close();
    exit;
} catch (Exception $e) {
    print "
        <div style='margin-top: 5.5rem;'>
            <p style='min-width: 9rem; min-height: 5rem; background-color: purple;'>{$e->getMessage()}</p>
        </div>";
    $link->close();
    exit;
}
?>