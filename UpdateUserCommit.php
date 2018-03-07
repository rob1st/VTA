<?php
include('SQLFunctions.php');
include('session.php');
session_start();
$AUserID = $_SESSION['UserID'];
$link = f_sqlConnect();
    
$user = "SELECT Username FROM Users WHERE UserID = ".$AUserID;
if($result=mysqli_query($link,$user)) {
    /*from the sql results, assign the username that returned to the $username variable*/    
    while($row = mysqli_fetch_assoc($result)) {
        $AUsername = $row['Username'];
    }
}

if(!isset( $_POST['Username']))
{
    $message = 'Please enter a valid username';
}
elseif (strlen( $_POST['Username']) > 20 || strlen($_POST['Username']) < 4)
{
    $message = 'incorrect length for Username';
}
elseif (ctype_alnum($_POST['Username']) != true)
{
    $message = "Username must be alpha numeric";
}
elseif (filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL) !=true)
{
    $message = "Email is not a valid email address";
}
elseif(!empty($_POST)) {
    $UserID = $_POST['UserID'];
    $Username = filter_var($_POST['Username'], FILTER_SANITIZE_STRING);
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    $Email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
    $Role = $_POST['Role'];
    
    try {
    
    $sql = "UPDATE Users
            SET  Username = '".$Username."'
                ,firstname = '".$firstname."'
                ,lastname = '".$lastname."'
                ,Role = '".$Role."'
                ,Email = '".$Email."'
                ,Updated_by = '".$AUsername."'
                ,LastUpdated = NOW()
            WHERE UserID = ".$UserID.";";

            if(mysqli_query($link,$sql)) {
                echo "<br>Update Completed successfully";
        } else {
            echo "<br>Error: " .$sql. "<br>" .mysqli_error($link);
        }
        mysqli_close($link);
        header("Location: DisplayUsers.php?msg=1");
        //echo "<br>SQL: ".$sql;
    } catch(Exception $e) { $message = "Unable to process request";}
}
?>
<html>
    <head>
        <title>
            SVBX - Update User
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </head>
    <body>
        <?php include('filestart.php') ?>
        <p><?php echo $message; ?></p>
        <?php include('fileend.php') ?>
    </body>
</html>