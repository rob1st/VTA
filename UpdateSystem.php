<?php
include('session.php');
session_start();
?>

<HTML>
    <HEAD>
        <TITLE>SVBX - Update System</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = System;
            $q = $_POST["q"];
            $Loc = "SELECT System FROM $table WHERE SystemID = ".$q;
                //echo '<br>Source table: ' .$table;
    ?>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Update System</H1>
<?php       if($stmt = $link->prepare($Loc)) {
            $stmt->execute();
            $stmt->bind_result($System);
            while ($stmt->fetch()) {
                echo "
        <FORM action='UpdateSystemCommit.php' method='POST' onsubmit='' />
            <input type='hidden' name='SystemID' value='".$q."'>
            <table>
                <tr>
                    <th colspan='2'>System</th>
                </tr>
                <tr>
                    <td>System Name:</td>
                    <td>
                        <input type='text' name='System' maxlength='50' required value='".$System."'/>
                    </td>
                </tr>
            </table>
            <input type='submit' class='button'>
            <input type='reset' class='button'>
        </FORM>";
        //echo "Description: ".$Description;
            }
                } else {
                    echo '<br>Unable to connect';
                    exit();
                }
        include('fileend.php') ?>
    </BODY>
</HTML>