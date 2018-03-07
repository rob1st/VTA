<HTML>
    <HEAD>
        <TITLE>Login Submit</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <BODY>
        <?php
            require_once('SQLFunctions.php');
            session_start();
            
            /* Check if the user is already logged in */
            if(isset( $_SESSION['UserID'] ))
            {
                $message = 'User is already logged in';
            }
            /* Check that username and password are populated */
            if(!isset( $_POST['Username'], $_POST['Password']))
            {
                $message = 'Please enter a valid username and password';
            }
            /* Check username length */
            elseif (strlen( $_POST['Username']) > 20 || strlen($_POST['Username']) < 4)
            {
                $message = 'Incorrect Length for Username';
            }
            /* Check password length */
            elseif (strlen( $_POST['Password']) > 20 || strlen($_POST['Password']) < 4)
            {
                $message = 'Incorrect Length for Password';
            }
            /* Check username for alpha numeric characters */
            elseif (ctype_alnum($_POST['Username']) != true)
            {
                $message = "Username must be alpha numeric";
            }
            /* Check password for alpha numeric characters */
            elseif (ctype_alnum($_POST['Password']) != true)
            {
                    $message = "Password must be alpha numeric";
            }
            else
            {
                /* Store username and pwds as variables*/
                $Username = filter_var($_POST['Username'], FILTER_SANITIZE_STRING);
                $Password = filter_var($_POST['Password'], FILTER_SANITIZE_STRING);
            
                /* Encrypt password with sha1*/
                $Password = sha1( $Password );
                
                try
                {
                     /*Connect to CRUD Database  mysqli(Server,User,Password,Database)*/
                    $link = f_sqlConnect();
            
                    /* Prep SQL statement which will compare the user credentials with what is stored in the database*/
                    $sql = "SELECT UserID FROM Users WHERE Username = '".$Username."' AND Password = '".$Password."'";
                    //echo $sql."<br>";
                    
                    /*Run the query*/
                    if($result=mysqli_query($link,$sql)) 
                    {
                      /*assign the User_id from the database to the session user_id*/
                      while($row = mysqli_fetch_assoc($result)) {
                        $UserID = $row['UserID'];
                        //echo "<br>UserID=".$UserID;
            
                        /* Set the session user_id parameter */
                        $_SESSION['UserID'] = $UserID;
                        $_SESSION['timeout'] = time();
                        header("Location: index.php"); 
                        //$message = 'You are now logged in';
                      }        
                    }
                      if($user_id == false)
                      {
                        $message = 'Login Failed';
                      }
                }    
                catch(Exception $e)
                {
                    $message = 'Unable to process request';
                }
            }
        include('filestart.php') ?>
                    <p STYLE="color:black">
                        <?php 
                            echo $message;

include 'fileend.php';
?>
    </body>
</html>