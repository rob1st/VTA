<HTML>
    <HEAD>
        <TITLE>Help Page</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Help Page</H1>
            <p style='color:black'>If you are having difficulties with the database please contact one of the people below who have administrator rights,<br>otherwise email <a href="mailto:robert.burns@vta.org?subject=SVBX Deficiency Database Issue"  style='color:black'>Robert Burns (Click here)</a> for assistance.</p>
        <?php

    $link = f_sqlConnect();
    $table = Users;
        //echo '<br>Source table: ' .$table;
        
    if(!f_tableExists($link, $table, DB_Name)) {
        die('<br>Destination table does not exist: '.$table);
    }
    
    $sql = "SELECT firstname, lastname, Email FROM $table WHERE Role = 'A' OR Role = 'S' ORDER BY lastname";
   
    
    if($result = mysqli_query($link,$sql)) {
        echo"   <table>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                    </tr>"; 
                    while ($row = mysqli_fetch_array($result)) {
                    echo "       
                    <tr>
                        <td>{$row[0]}</td>
                        <td>{$row[1]}</td>
                        <td><a href='mailto:{$row[2]}' style='color:black'>{$row[2]}</a></td>
                    </tr>";
                    }
    }
            echo "</table><br>";
    mysqli_free_result($result);
    
    if(mysqli_error($link)) {
        echo '<br>Error: ' .mysqli_error($link);
    } else //echo '<br>Success';
    
    mysqli_close($link);
    
?>
<?php include('fileend.php') ?>
    </BODY>
</HTML>