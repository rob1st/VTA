<?php
include('session.php');
session_start();
?>

<HTML>
    <HEAD>
        <TITLE>Update Location</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = Location;
            $q = $_POST["q"];
            $Loc = "SELECT LocationName FROM Location WHERE LocationID = ".$q;
                //echo '<br>Source table: ' .$table;
    ?>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Enter a new location into the database</H1>
<?php       if($stmt = $link->prepare($Loc)) {
            $stmt->execute();
            $stmt->bind_result($LocationName);
            while ($stmt->fetch()) {
                echo "
        <FORM action='UpdateLocationCommit.php' method='POST' onsubmit='' />
            <input type='hidden' name='LocationID' value='".$q."'>
            <table>
                <tr>
                    <th colspan='2'>Update Location</th>
                </tr>
                <tr>
                    <td>Location Name:</td>
                    <td>
                        <input type='text' name='LocationName' maxlength='50' required value='".$LocationName."'/>
                    </td>
                </tr>
            </table>
            <input type='submit' class='button'>
            <input type='reset' class='button'>
        </FORM>";
            }
                } else {
                    echo '<br>Unable to connect';
                    exit();
                }
        include('fileend.php') ?>
    </BODY>
</HTML>