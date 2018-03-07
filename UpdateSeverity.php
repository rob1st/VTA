<?php
include('session.php');
session_start();
?>

<HTML>
    <HEAD>
        <TITLE>SVBX - Update Severity</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = Severity;
            $q = $_POST["q"];
            $Loc = "SELECT SeverityName, Description FROM $table WHERE SeverityID = ".$q;
                //echo '<br>Source table: ' .$table;
    ?>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Update Severity</H1>
<?php       if($stmt = $link->prepare($Loc)) {
            $stmt->execute();
            $stmt->bind_result($SeverityName, $Description);
            while ($stmt->fetch()) {
                echo "
        <FORM action='UpdateSeverityCommit.php' method='POST' onsubmit='' />
            <input type='hidden' name='SeverityID' value='".$q."'>
            <table>
                <tr>
                    <th colspan='2'>Severity</th>
                </tr>
                <tr>
                    <td>Severity Name:</td>
                    <td>
                        <input type='text' name='SeverityName' maxlength='50' required value='".$SeverityName."'/>
                    </td>
                </tr>
                <tr>
                    <td coldpan='2'>Severity Description:</td>
                    <td>
                        <textarea type='message'  rows='5' cols='99%' name='Description' max='255' required>$Description</textarea>
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