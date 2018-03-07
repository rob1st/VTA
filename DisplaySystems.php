<HTML>
    <HEAD>
        <TITLE>SVBX - Systems</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
<BODY>
<?php include('filestart.php') ?>
    <h1>Systems</h1>

<?php

    $link = f_sqlConnect();
    $table = System;
        //echo '<br>Source table: ' .$table;
        
    if(!f_tableExists($link, $table, DB_Name)) {
        die('<br>Destination table does not exist: '.$table);
    }
    
    $sql = "SELECT SystemID, System, Update_TS, Updated_by FROM $table ORDER BY System";
    $sql1 = "SELECT COUNT(*) FROM $table";
    
    if($result = mysqli_query($link,$sql1)) {
        echo"   <table>
                    <tr>
                        <td>System Types in Database: </td>";
            while ($row = mysqli_fetch_array($result)) {
                    echo "<td>{$row[0]}</td>";
            }    
            echo "</table><br>";
}
    if($result = mysqli_query($link,$sql)) {
        echo"   <table>
                    <tr>
                        <th>System ID</th>
                        <th>System</th>";
                        if(!isset($_SESSION['UserID'])) 
                        {
                        echo "</tr>";
                        } else {
                        echo "
                            <th>Last Updated</th>
                            <th>Updated By</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            </tr>"; 
                        } 
            while ($row = mysqli_fetch_array($result)) {
        echo"       <tr>
                        <td style='text-align:center'>{$row[0]}</td>
                        <td>{$row[1]}</td>";
                        if(!isset($_SESSION['UserID'])) 
                        {
                            echo "</tr>";
                        } else {
                        echo "
                        <td>{$row[2]}</td>
                        <td>{$row[3]}</td>
                        <td><form action='UpdateSystem.php' method='POST' onsubmit=''/>
                        <input type='hidden' name='q' value='".$row[0]."'/><input type='submit' value='Update'></form></td>
                        <td><form action='DeleteSystem.php' method='POST' onsubmit='' onsubmit='' onclick='return confirm(`do you want to delete {$row[0]} system`)'/>
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