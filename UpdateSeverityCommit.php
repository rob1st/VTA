<?php
include('SQLFunctions.php');
include('session.php');

if(!empty($_POST)) {
    $SeverityID = $_POST['SeverityID'];
    $SeverityName = $_POST['SeverityName'];
    $Description = $_POST['Description'];
    $UserID = $_SESSION['userID'];
    $link = f_sqlConnect();
    
    $sql = "UPDATE severity
            SET SeverityName = '".$SeverityName."'
                ,severityDescrip = '".$Description."'
                ,updatedBy = '".$UserID."'
                ,lastUpdated = NOW()
            WHERE SeverityID = ".$SeverityID.";";

            if(mysqli_query($link,$sql)) {
                $_SESSION['successMsg'] = "Update Completed successfully";
        } else {
            $_SESSION['errorMsg'] = "Error: " .$sql. ": " .mysqli_error($link);
        }
        mysqli_close($link);
        header("Location: DisplaySeverities.php");
}
?>