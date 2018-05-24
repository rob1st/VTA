<?php
include('SQLFunctions.php');
include('session.php');
include('uploadImg.php');

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