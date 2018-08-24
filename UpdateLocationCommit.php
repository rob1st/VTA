<?php
include('SQLFunctions.php');
include('session.php');

if(!empty($_POST)) {
    $LocationID = $_POST['LocationID'];
    $LocationName = $_POST['LocationName'];
    $UserID = $_SESSION['userID'];
    $link = f_sqlConnect();
    
    $sql = "UPDATE location
            SET LocationName = '$LocationName'
                ,updatedBy = '$UserID'
                ,lastUpdated = NOW()
            WHERE LocationID = $LocationID";

    if(mysqli_query($link,$sql)) {
        $_SESSION['successMsg'] = "Update Completed successfully";
    } else {
        $_SESSION['errorMsg'] = "Error: " .$sql. "<br>" .mysqli_error($link);
    }
    mysqli_close($link);
    header("Location: DisplayLocations.php?msg=1");
}
?>