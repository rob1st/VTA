<?php
include('session.php');
session_start();
?>

<HTML>
    <HEAD>
        <TITLE>SVBX - Update User</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = Users;
            $q = $_POST["q"];
            $Loc = "SELECT Username, Role, firstname, lastname, Email FROM $table WHERE UserID = ".$q;
                //echo '<br>Source table: ' .$table;
            $adminID = $_SESSION['UserID'];
            $admin = "SELECT Role FROM Users WHERE UserID = ".$adminID;
        
            /*execute the sql statement*/
            if($result=mysqli_query($link,$admin)) {
            /*from the sql results, assign the username that returned to the $username variable*/    
            while($row = mysqli_fetch_assoc($result)) {
            $ARole = $row['Role'];
          }
        }
    ?>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Update User</H1>
<?php       if($stmt = $link->prepare($Loc)) {
            $stmt->execute();
            $stmt->bind_result($Username, $Role, $firstname, $lastname, $Email);
            while ($stmt->fetch()) {
                echo "
        <FORM action='UpdateUserCommit.php' method='POST' onsubmit='' />
            <input type='hidden' name='UserID' value='".$q."'>
            <table>
                <tr>
                    <th colspan='2'>User</th>
                </tr>
                <tr>
                    <td>First name:</td>
                    <td>
                        <input type='text' name='firstname' maxlength='25' required value='".$firstname."'/>
                    </td>
                </tr>
                <tr>
                    <td>Last name:</td>
                    <td>
                        <input type='text' name='lastname' maxlength='25' required value='".$lastname."'/>
                    </td>
                </tr>
                <tr>
                    <td>Email Address:</td>
                    <td>
                        <input type='text' name='Email' maxlength='55' required value='".$Email."'/>
                    </td>
                </tr>
                <tr>
                    <td>Username:</td>
                    <td>
                        <input type='text' name='Username' maxlength='25' required value='".$Username."'/>
                    </td>
                </tr>";
                }
                if($ARole == 'S')
                {
                echo "
                <tr>
                    <td rowspan='3'>Role:</td>
                    <td>
                        <input type='radio' name='Role' value='S'"; 
                        if($Role== 'S') { 
                        echo ' checked'; 
                        } else { 
                        }
                        echo "/>Super Admin
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='radio' name='Role' value='A'"; 
                        if($Role== 'A') { 
                            echo ' checked'; 
                        } else { 
                        }
                        echo "/>Administrator
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='radio' name='Role' value='U'"; 
                        if($Role== 'U') { 
                            echo ' checked'; 
                        } else { 
                        }
                        echo "/>User
                    </td>
                </tr>";
                }
                elseif($ARole == 'A')
                {
                echo "
                <tr>
                    <td rowspan='2'>Role:</td>
                    <td>
                        <input type='radio' name='Role' value='A'"; 
                        if($Role== 'A') { 
                        echo ' checked'; 
                        } else { 
                        }
                        echo "/>Administrator
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='radio' name='Role' value='U'"; 
                        if($Role== 'U') { 
                            echo ' checked'; 
                        } else { 
                        }
                        echo "/>User
                    </td>
                </tr>";
                }
                else
                {
                echo "
                <tr>
                    <td>Role:</td>
                    <td>
                        <input type='radio' name='Role' value='U'"; 
                        if($Role== 'U') { 
                        echo ' checked'; 
                        } else { 
                        }
                        echo "/>User
                    </td>
                </tr>";
                }
            echo "</table>
            <input type='submit' class='button'>
            <input type='reset' class='button'>
        </FORM>";
        //echo "Description: ".$Description;
                } else {
                    echo '<br>Unable to connect';
                    exit();
                }
        include('fileend.php') ?>
    </BODY>
</HTML>