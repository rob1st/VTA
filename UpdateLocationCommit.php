<?php
include('SQLFunctions.php');
include('Session.php');
session_start();

if(!empty($_POST)) {
    $LocationID = $_POST['LocationID'];
    $LocationName = $_POST['LocationName'];
    $UserID = $_SESSION['UserID'];
    $link = f_sqlConnect();
    
    $user = "SELECT Username FROM users_enc WHERE UserID = ".$UserID;
    if($result=mysqli_query($link,$user)) 
        {
          /*from the sql results, assign the username that returned to the $username variable*/    
          while($row = mysqli_fetch_assoc($result)) {
            $Username = $row['Username'];
          }
        }
    
    $sql = "UPDATE Location
            SET LocationName = '".$LocationName."'
                ,Updated_by = '".$Username."'
                ,Update_TS = NOW()
            WHERE LocationID = ".$LocationID.";";

            if(mysqli_query($link,$sql)) {
                echo "<br>Update Completed successfully";
        } else {
            echo "<br>Error: " .$sql. "<br>" .mysqli_error($link);
        }
        mysqli_close($link);
        header("Location: DisplayLocations.php?msg=1");
        //echo "<br>Username: ".$Username;
        //echo "<br>UserID: ".$user;        
}
?>