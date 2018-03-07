<HTML>
    <HEAD>
        <TITLE>SVBX - Users</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
<BODY>
<?php include('filestart.php') ?>
    <h1>Users</h1>

<?php

    $link = f_sqlConnect();
    $table = Users;
        //echo '<br>Source table: ' .$table;
    $adminID = $_SESSION['UserID'];
    $admin = "SELECT Role FROM Users WHERE UserID = ".$adminID;
        /*echo "<br>".$sql."<br>";*/
        
        /*execute the sql statement*/
        if($result=mysqli_query($link,$admin)) {
          /*from the sql results, assign the username that returned to the $username variable*/    
          while($row = mysqli_fetch_assoc($result)) {
            $Role = $row['Role'];
          }
        }
        
    if(!f_tableExists($link, $table, DB_Name)) {
        die('<br>Destination table does not exist: '.$table);
    }
    
    $sql = "SELECT UserID, username, firstname, lastname, Role, LastUpdated, Updated_by, DateAdded, Created_by, Email FROM $table ORDER BY lastname";
    $sql1 = "SELECT COUNT(*) FROM $table";
    
    if($result = mysqli_query($link,$sql1)) {
        echo"   <table>
                    <tr>
                        <td>Users in Database: </td>";
            while ($row = mysqli_fetch_array($result)) {
                    echo "<td>{$row[0]}</td>";
            }    
            echo "</table><br>";
}
    if($result = mysqli_query($link,$sql)) {
        echo"   <table>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>";
                        if(!isset($_SESSION['UserID'])) 
                        {
                        echo "</tr>";
                        } else {
                        echo "
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Role</th>";
                            }
                        if($Role == 'A' OR $Role == 'S')
                        {
                        echo "
                            <th>Date Created</th>
                            <th>Created By</th></th>
                            <th>Last Updated</th>
                            <th>Updated By</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            </tr>"; 
                        }
            while ($row = mysqli_fetch_array($result)) {
        echo"       <tr>
                        
                        <td>{$row[2]}</td>
                        <td>{$row[3]}</td>
                        <td><a href='mailto:{$row[9]}' style='color:black'>{$row[9]}</a></td>";
                        if(!isset($_SESSION['UserID'])) 
                        {
                            echo "</tr>";
                        } else {
                        echo "
                        <td style='text-align:center'>{$row[0]}</td>
                        <td>{$row[1]}</td>
                        <td style='text-align:center'>{$row[4]}</td>";
                        }
                        if($Role == 'A' OR $Role == 'S')
                        {
                        echo "
                        <td>{$row[7]}</td>
                        <td>{$row[8]}</td>
                        <td>{$row[5]}</td>
                        <td>{$row[6]}</td>
                        <td><form action='UpdateUser.php' method='POST' onsubmit=''/>
                        <input type='hidden' name='q' value='".$row[0]."'/><input type='submit' value='Update'></form></td>
                        <td><form action='DeleteUser.php' method='POST' onsubmit='' onsubmit='' onclick='return confirm(`do you want to delete user {$row[2]} {$row[3]}`)'/>
                        <input type='hidden' name='q' value='".$row[0]."' /><input type='Submit' value='delete'></form></td>
                    </tr>";
                    }
            }    
            echo "</table><br>";
    }
    mysqli_free_result($result);
    
    if(mysqli_error($link)) {
        echo '<br>Error: ' .mysqli_error($link);
    } else //echo '<br>Success';
    
    mysqli_close($link);
    
?>
<?php include 'fileend.php';?>
</Body>
</HTML>