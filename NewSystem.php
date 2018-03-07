<?php
include('session.php');
?>

<HTML>
    <HEAD>
        <TITLE>New System</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = System;
                //echo '<br>Source table: ' .$table;
    ?>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Enter a new system into the database</H1>
        <FORM action="RecSystem.php" method="POST">
            <table>
                <tr>
                    <th colspan='2'>New System</th>
                </tr>
                <tr>
                    <td>System Name:*</td>
                    <td>
                        <input type="text" name="System" maxlength="50" required/>
                    </td>
                </tr>
            </table>
            <input type="submit" class="button">
            <input type="reset" class="button">
        </FORM>
<?php include('fileend.php') ?>
    </BODY>
</HTML>