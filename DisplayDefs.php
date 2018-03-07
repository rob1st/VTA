<HTML>
    <HEAD>
        <TITLE>SVBX - Display Deficiencies</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
        <meta charset="UTF-8">
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8">
    </HEAD>
<BODY>
<?php include('filestart.php') ?>
    <h1>Project Deficiencies</h1>

<?php
    //include('SQLFunctions.php');
    $link = f_sqlConnect();
    $CDL = file_get_contents("CDList.sql");
    //echo '<br>SQL String: ' .$CDL;
    $admin = "SELECT Role FROM Users WHERE UserID = ".$adminID;
        /*echo "<br>".$sql."<br>";*/
        
        /*execute the sql statement*/
        if($result=mysqli_query($link,$admin)) {
          /*from the sql results, assign the username that returned to the $username variable*/    
          while($row = mysqli_fetch_assoc($result)) {
            $Role = $row['Role'];
          }
        }
    
    if($result = mysqli_query($link,$CDL)) {
        echo "<p style='color:black'>Click Deficiency ID Number to see full details</p>
            <table width='98%'>
                <tr>
                    <th>Def ID</th>
                    <th>Location</th>
                    <th>Severity</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>System Affected</th>
                    <th>Brief Description</th>";
                    if(!isset($_SESSION['UserID'])) 
                    {
                        echo "</tr>";
                    } else {
                        echo "
                            <th>Last Updated</th>
                            <th>Edit</th>";
                            }
                            if($Role == 'S')
                            {
                            echo "
                                <th>Delete</th>
                            </tr>"; 
                    }
            while($row = mysqli_fetch_array($result)) {
                echo "  <tr>
                        <td style='text-align:center'><a href='ViewDef.php?DefID={$row[0]}' class='class1'>{$row[0]}</a></td>
                        <td>{$row[1]}</td>
                        <td style='text-align:center'>{$row[2]}</td>
                        <td style='text-align:center'>{$row[3]}</td>
                        <td style='text-align:center'>{$row[4]}</td>
                        <td>{$row[5]}</td>
                        <td>"; echo nl2br($row[6]);
                        echo "</td>";
                        if(!isset($_SESSION['UserID'])) 
                        {
                            echo "</tr>";
                        } else {
                        echo "
                            <td>{$row[7]}</td>
                            <td style='text-align:center'><form action='UpdateDef.php' method='POST' onsubmit=''/>
                            <input type='hidden' name='q' value='".$row[0]."'/><input type='submit' value='Update'></form></td>";
                            }
                            if($Role == 'S')
                            {
                            echo "
                            <td style='text-align:center'><form action='DeleteDef.php' method='POST' onsubmit='' onclick='return confirm(`ARE YOU SURE? Deficiencies should not be deleted, your deletion will be logged.`)'/>
                            <input type='hidden' name='q' value='".$row[0]."' /><input type='Submit' value='delete'></form></td>
                            </tr>";
                        }
            }
                echo "</table><br>";
    }
//mysqli_free_result($result);

    if(mysqli_error( $link )) {
        echo '<br>Error: ' .mysqli_error($link);
    }
                    
mysqli_close($link);
    
include 'fileend.php';
?>
</Body>
</HTML>