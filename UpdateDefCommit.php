<?php
session_start();
include('SQLFunctions.php');
include('uploadImg.php');

// prepare POST and sql string for commit
$post = $_POST;
$defID = $post['defID'];
$userID = $_SESSION['UserID'];

// validate POST data
// if it's empty then file upload exceeds post_max_size
// bump user back to form
if (!count($post) || !$defID) {
    include('js/emptyPostRedirect.php');
    exit;
}

// hold onto comments separately
$cdlCommText = $post['cdlCommText'];

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


if(!empty($_POST)) {
    $link = f_sqlConnect();
    $defID = $_POST['DefID'];
    $SystemAffected = $_POST['SystemAffected'];
    $LocationName = $_POST['LocationName'];
    $SpecLoc = $link->real_escape_string($_POST['SpecLoc']);
    $Status = $_POST['Status'];
    $SeverityName = $_POST['SeverityName'];
    $GroupToResolve = $_POST['GroupToResolve'];
    $IdentifiedBy = $link->real_escape_string($_POST['IdentifiedBy']);
    $defType = $_POST['defType'];
    $Description = $link->real_escape_string($_POST['Description']);
    $EvidenceType = $_POST['EviType'];
    $EvidenceLink = $link->real_escape_string($_POST['EvidenceLink']);
    $ActionOwner = $link->real_escape_string($_POST['ActionOwner']);
    $OldID = $link->real_escape_string($_POST['OldID']);
    $comments = $link->real_escape_string($_POST['comments']);
    $Spec = $link->real_escape_string($_POST['Spec']);
    $ClosureComments = $link->real_escape_string($_POST['ClosureComments']);
    $RequiredBy = $_POST['RequiredBy'];
    $contractID = $_POST['contractID'];
    $Repo = $_POST['Repo'];
    $Pics = $_POST['Pics'];
    $DueDate = $_POST['DueDate'];
    $SafetyCert = $_POST['SafetyCert'];
    $UserID = $_SESSION['UserID'];
    $Username = $_SESSION['Username'];
    
    $sql = "UPDATE CDL
            SET  
                 OldID = '$OldID',
                 Location = '$LocationName',
                 SpecLoc = '$SpecLoc',
                 Severity = '$SeverityName',
                 Description = '$Description',
                 Spec = '$Spec',
                 Status = '$Status',
                 IdentifiedBy = '$IdentifiedBy',
                 defType = '$defType',
                 SystemAffected = '$SystemAffected',
                 GroupToResolve = '$GroupToResolve',
                 ActionOwner = '$ActionOwner',
                 EvidenceType = '$EvidenceType',
                 EvidenceLink = '$EvidenceLink',
                 comments = '$comments',
                 SafetyCert = '$SafetyCert',
                 Requiredby = '$RequiredBy',
                 contractID = '$contractID',
                 Repo = '$Repo',
                 ClosureComments = '$ClosureComments',
                 DueDate = '$DueDate',
                 Updated_by = '$Username',
                 LastUpdated = NOW(),
                 defType = {$_POST['defType']}
            WHERE DefID = $defID;";

    // if photo in POST it will be committed to a separate table
    if ($_FILES['CDL_pics']['size']
        && $_FILES['CDL_pics']['name']
        && $_FILES['CDL_pics']['tmp_name']
        && $_FILES['CDL_pics']['type']) {
        $CDL_pics = $_FILES['CDL_pics'];
    } else $CDL_pics = null;
    
    if($link->query($sql)) {
        $qs = "defID=$defID";
        // if INSERT succesful, prepare, upload, and INSERT photo
        if ($CDL_pics) {
            $pathToFile = saveImgToServer($_FILES['CDL_pics'], $defID);
            $qs .= "&$pathToFile";
            $sql = "INSERT CDL_pics (defID, pathToFile) values (?, ?)";
            if ($stmt = $link->prepare($sql)) {
                if ($stmt->bind_param('is', $defID, $pathToFile)) {
                    if (!$stmt->execute()) $pathToFile = 'execute_failed';
                    $stmt->close();
                } else $pathToFile = 'bind_failed';
            } else $pathToFile = 'prepare_failed';
        }
    } else {
        echo "
        <div class='container page-header'>
        <h5>There was a problem with the database query</h5>
        <pre>";
        echo $link->error;
        echo "</pre></div>";
    }
    $link->close();
    header("Location: ViewDef.php?$qs");
}
?>