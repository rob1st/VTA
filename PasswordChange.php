<?php

include('session.php');
session_start();
$Username = $_POST['Username'];
$oldpw = ($_POST['oldpw']);
$newpw = ($_POST['newpw']);

    
if(empty($oldpw))
{
    $message = 'Please enter your old password';
}
elseif (strlen( $_POST['newpw']) > 20 || strlen($_POST['newpw']) < 4)
{
    $message = 'incorrect length for new password';
}
elseif (ctype_alnum($_POST['newpw']) != true)
{
    $message = "New password must be alpha numeric";
}
elseif ($_POST['newpw'] <> $_POST['conpw'])
{
    $message = "Your new passwords do not match";
}
elseif(!empty($_POST)) {
    $UserID = $_POST['UserID'];
    $oldpwd = filter_var($_POST['oldpwd'], FILTER_SANITIZE_STRING);
    $newpwd = filter_var($_POST['newpwd'], FILTER_SANITIZE_STRING);
    
    try {
        
        include('SQLFunctions.php');
        $link = f_sqlConnect();
        $oldpw = Sha1($oldpw);
        $newps = Sha1($newpw);

        // check whether username exists and check that $oldpass is correct!
        
        $query = "SELECT Password FROM Users WHERE Password='".$oldpw."' AND  Username='".$Username."'";

        $result = mysqli_query($link, $query);
        if(!$result){

            $message = "<p class='message'>Error: Your username and or password are incorrect.</p>" ;
        }else{

            // Test with mysqli_num_rows()
            if (mysqli_num_rows($result) > 0) {
        
                $query = "
                        UPDATE 
                            Users 
                        SET 
                            Password = '$newpw'
                            ,Updated_by = '$Username'
                            ,LastUpdated = NOW()
                        WHERE 
                            Username = '$Username'";
                            
              mysqli_query($link, $query) or
                    die("Insert failed. " . mysqli_error($link));
              
              $message = "<p class='message'>Your password has been changed</p>";
              
              mysqli_free_result($result);
            }
            else {
               // Username or password is incorrect
                $message = "<p class='message'>Error: Your username and password do not match.</p>" ;
            } 
        }
} catch(Exception $e) { $message = "Unable to process request";
}
}
?>
<html>
    <head>
        <title>
            SVBX - Update Password
        </title>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </head>
    <body>
        <?php include('filestart.php') ?>
        <p><?php echo $message;?></p>
        <?php include('fileend.php') ?>
    </body>
</html>