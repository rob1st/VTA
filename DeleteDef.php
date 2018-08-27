<?php
include('session.php');
include('SQLFunctions.php');

if(!empty($_POST)) {
    $DefID = $_POST['q'];
    $username = $_SESSION['username'];
    
    $link = f_sqlConnect();
 
    $sql = "UPDATE CDL
            SET 
                 Status = '3'
                ,updated_By = '$username'
                ,LastUpdated = NOW()
            WHERE DefID = ".$DefID.";";
    //echo "sql: " .$sql. "Comment out Later";
    
    if(mysqli_query($link,$sql)) {
        echo "<br>Deleted record successfully";
    } else {
        echo "<br>Error: " .$sql. "<br>" .mysqli_error($link);
    }
    mysqli_close($link);
    header("Location: defs.php");
    
}
?>