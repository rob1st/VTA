<?php
include('SQLFunctions.php');
include('session.php');

if(!empty($_POST)) {
    $EviTypeID = $_POST['EviTypeID'];
    $EviType = $_POST['EviType'];
    $UserID = $_SESSION['userID'];
    $link = f_sqlConnect();
    
    $sql = "UPDATE evidenceType
            SET EviTypeName = '$EviType'
                ,updatedBy = '$UserID'
                ,lastUpdated = NOW()
            WHERE EviTypeID = $EviTypeID";

            if(mysqli_query($link,$sql)) {
                $_SESSION['successMsg'] = "Update Completed successfully";
        } else {
            $_SESSION['errorMsg'] = "Error:" . $sql . ":" . mysqli_error($link);
        }
        mysqli_close($link);
        header("Location: DisplayEviType.php");
        //echo "<br>SQL: ".$sql;
}
?>