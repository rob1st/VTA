<?php
include('session.php');
session_start();
?>

<HTML>
    <HEAD>
        <TITLE>SVBX - Update Evidence Type</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = EvidenceType;
            $q = $_POST["q"];
            $Loc = "SELECT EviType FROM $table WHERE EviTypeID = ".$q;
                //echo '<br>Source table: ' .$table;
    ?>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Update Evidence Type</H1>
<?php       if($stmt = $link->prepare($Loc)) {
            $stmt->execute();
            $stmt->bind_result($EviType);
            while ($stmt->fetch()) {
                echo "
        <FORM action='UpdateEvidenceCommit.php' method='POST' onsubmit='' />
            <input type='hidden' name='EviTypeID' value='".$q."'>
            <table>
                <tr>
                    <th colspan='2'>Update Evidence</th>
                </tr>
                <tr>
                    <td>Location Name:</td>
                    <td>
                        <input type='text' name='EviType' maxlength='50' required value='".$EviType."'/>
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