<?php
include('session.php');
?>

<?php
include('sqlFunctions.php');

if(!empty($_POST)) {
    $StatusID = $_POST['q'];
    
    $link = f_sqlConnect();
 
    $sql = "DELETE 
            FROM status
            WHERE StatusID = ".$StatusID.";";
    //echo "sql: " .$sql. "Comment out Later";
    
    if(mysqli_query($link,$sql)) {
        echo "<br>Deleted record successfully";
    } else {
        echo "<br>Error: " .$sql. "<br>" .mysqli_error($link);
    }
    mysqli_close($link);
    header("Location: DisplayStatuses.php");
    
}
?>