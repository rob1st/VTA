<?php
include('session.php');
session_start();
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
        <H1>Enter a new location into the database</H1>
        <FORM action="RecLocation.php" method="POST">
            <table>
                <tr>
                    <th colspan='2'>New Location</th>
                </tr>
                <tr>
                    <td>Location Name:</td>
                    <td>
                        <input type="text" name="LocationName" maxlength="50" required/>
                    </td>
                </tr>
            </table>
            <input type="submit" class="button">
            <input type="reset" class="button">
        </FORM>
<?php include('fileend.php') ?>
    </BODY>
</HTML>