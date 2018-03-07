<?php
include('session.php');
?>

<HTML>
    <HEAD>
        <TITLE>New Severity</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = Severity;
                //echo '<br>Source table: ' .$table;
    ?>
    <BODY>
<?php include('filestart.php') ?>
        <H1>Enter a new severity type into the database</H1>
        <FORM action="RecSeverity.php" method="POST">
            <table>
                <tr>
                    <th colspan='2'>New Severity</th>
                </tr>
                <tr>
                    <td>Severity Name:</td>
                    <td>
                        <input type="text" name="SeverityName" maxlength="12" required/>
                    </td>
                </tr>
                <tr>
                    <td>Description:</td>
                    <td>
                        <textarea type="message"  rows="5" cols="99%" name="Description" max="255" required></textarea>
                    </td>
                </tr>
            </table><br>
            <input type="submit" class="button">
            <input type="reset" class="button">
        </FORM>
<?php include('fileend.php') ?>
    </BODY>
</HTML>