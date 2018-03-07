<?php
include('session.php');
?>

<HTML>
    <HEAD>
        <TITLE>New Status</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            $link = f_sqlConnect();

                //echo '<br>Source table: ' .$table;
    ?>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Enter a new status type into the database</H1>
        <FORM action="RecStatus.php" method="POST">
            <table>
                <tr>
                    <th colspan='2'>New Status</th>
                </tr>
                <tr>
                    <td>Status Name:*</td>
                    <td>
                        <input type="text" name="Status" maxlength="50" required/>
                    </td>
                </tr>
            </table>
            <input type="submit" class="button">
            <input type="reset" class="button">
        </FORM>
<?php include('fileend.php') ?>
    </BODY>
</HTML>