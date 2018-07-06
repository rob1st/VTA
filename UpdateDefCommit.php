<?php
session_start();
include('SQLFunctions.php');
include('uploadImg.php');

// prepare POST and sql string for commit
$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
$defID = $post['defID'];
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

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
// $fieldList = preg_replace('/\s+/', '', file_get_contents('updateDef.sql'));
// $fieldsArr = array_fill_keys(explode(',', $fieldList), '?');

// unset keys that will not be updated before imploding back to string
// unset(
//     $fieldsArr['defID'],
//     $fieldsArr['created_by'],
//     $fieldsArr['dateCreated'],
//     $fieldsArr['lastUpdated'],
//     $fieldsArr['dateClosed']
// );
unset(
    $post['defID'],
    $post['cdlCommText']
);

// if Closed, set dateClosed
// if Closure Requested, record by whom
if ($post['status'] === '2') {
    $post['dateClosed'] = 'NOW()';
} elseif ($post['status'] === '4') {
    $post['status'] = 1;
    $post['closureRequested'] = 1;
    $post['closureRequestedBy'] = $userID;
}

// append keys that do not or may not come from html form
// or whose values may be ambiguous in $_POST (e.g., checkboxes)
$post['updated_by'] = $username;

try {
    $link = connect();
    $link->where('defID', $defID);
    $link->update('CDL', $post);

    // if INSERT succesful, prepare, upload, and INSERT photo
    if ($CDL_pics) {
        // $sql = "INSERT CDL_pics (defID, pathToFile) values (?, ?)";

        // execute save image and hold onto its new file path
        try {
            $pathToFile = saveImgToServer($_FILES['CDL_pics'], $defID);

            $fileData = [
                'pathToFile' => $pathToFile,
                'defID' => $defID
            ];

            $link->insert('CDL_pics', $fileData);
        } catch (uploadException $e) {
            header("Location: updateDef.php?defID=$defID");
            $_SESSION['errorMsg'] = "There was an error uploading your file: $e";
        } catch (Exception $e) {
            header("Location: updateDef.php?defID=$defID");
            $_SESSION['errorMsg'] = "There was a problem recording your file: $e";
        }
    }

    // if comment submitted commit it to a separate table
    if (strlen($cdlCommText)) {
        // $sql = "INSERT cdlComments (defID, cdlCommText, userID) VALUES (?, ?, ?)";
        try {
            $commentData = [
                'defID' => $defID,
                'cdlCommText' => filter_var($cdlCommText, FILTER_SANITIZE_SPECIAL_CHARS),
                'userID' => $userID
            ];

            $link->insert('cdlComments', $commentData);
        } catch (Exception $e) {
            header("Location: updateDef.php?defID=$defID");
            $_SESSION['errorMsg'] = "There was a problem recording your comment: $e";
        }
        // if (!$stmt = $link->prepare($sql)) throw new Exception($link->error);
        // $success = sprintf($success, sprintf($successFormat, 'darkCyan', '&#x2714; cdlComments stmt prepared') . '%s');
        // if (!$stmt->bind_param('isi',
        //     intval($defID),
        //     $commentText,
        //     intval($userID))) throw new mysqli_sql_exception($stmt->error);
        // $success = sprintf($success, sprintf($successFormat, 'darkBlue', '&#x2714; cdlComments params bound') . '%s');
        // if (!$stmt->execute()) throw new mysqli_sql_exception($stmt->error);
        // $success = sprintf($success, sprintf($successFormat, 'darkTurquoise', '&#x2714; cdlComments stmt executed') . '%s');
        // $stmt->close();
        // $success = sprintf($success, sprintf($successFormat, 'deepSkyBlue', '&#x2714; cdlComments stmt closed') . '%s');
    }
    // $link->close();
    // $success = sprintf($success, sprintf($successFormat, 'lightSteelBlue', '&#x2714; link closed') . '%s');
    // $success = sprintf($success, sprintf($linkBtn, $defID));
    // print $success;

    header("Location: viewDef.php?defID=$defID");
} catch (Exception $e) {
    header("Location: updateDef.php?defID=$defID");
    $_SESSION['errorMsg'] = "There was an error in committing your submission: $e";
} finally {
    $link->disconnect();
    exit;
}
