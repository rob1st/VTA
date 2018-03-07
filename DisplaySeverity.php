<HTML>
    <HEAD>
        <TITLE>SVBX - Severity Types</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
<BODY>
<?php include('filestart.php') ?>
    <h1>Severity Types</h1>

<?php

    $link = f_sqlConnect();
    $table = Severity;
        //echo '<br>Source table: ' .$table;
        
    if(!f_tableExists($link, $table, DB_Name)) {
        die('<br>Destination table does not exist: '.$table);
    }
    
    $sql = "SELECT SeverityID, SeverityName, Description, Update_TS, Updated_by FROM $table ORDER BY SeverityID";
    $sql1 = "SELECT COUNT(*) FROM $table";
    
    if($result = mysqli_query($link,$sql1)) {
        echo"   <table>
                    <tr>
                        <td>Severity Types in Database: </td>";
            while ($row = mysqli_fetch_array($result)) {
                    echo "<td>{$row[0]}</td>";
            }    
            echo "</table><br>";
}
    if($result = mysqli_query($link,$sql)) {
        echo"   <table>
                    <tr>
                        <th>Severity ID</th>
                        <th>Severity</th>
                        <th>Description</th>";
                        if(!isset($_SESSION['UserID'])) 
                        {
                        echo "</tr>";
                        } else {
                        echo "
                            <th>Last updated</th>
                            <th>Updated by</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            </tr>"; 
                        }
            while ($row = mysqli_fetch_array($result)) {
        echo"       <tr>
                        <td style='text-align:center'>{$row[0]}</td>
                        <td>{$row[1]}</td>
                        <td>{$row[2]}</td>";
                        if(!isset($_SESSION['UserID'])) 
                        {
                            echo "</tr>";
                        } else {
                        echo "
                        <td>{$row[3]}</td>
                        <td>{$row[4]}</td>
                        <td><form action='UpdateSeverity.php' method='POST' onsubmit=''/>
                        <input type='hidden' name='q' value='".$row[0]."'/><input type='submit' value='Update'></form></td>
                        <td><form action='DeleteSeverity.php' method='POST' onsubmit='' onsubmit='' onclick='return confirm(`do you want to delete severity {$row[1]}`)'/>
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