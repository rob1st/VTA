<?php
include('session.php');
?>

<HTML>
    <HEAD>
        <TITLE>New Location</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = Location;
                //echo '<br>Source table: ' .$table;
    ?>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Enter a new evidence type into the database</H1>
        <FORM action="RecEvidence.php" method="POST">
            <table>
                <tr>
                    <th colspan='2'>New Evidence Type</th>
                </tr>
                <tr>
                    <td>Evidence Type Name:</td>
                    <td>
                        <input type="text" name="EviType" maxlength="50" required/>
                    </td>
                </tr>
            </table>
            <input type="submit" class="button">
            <input type="reset" class="button">
        </FORM>
<?php include('fileend.php') ?>
    </BODY>
</HTML>