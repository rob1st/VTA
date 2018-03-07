<?php
include('session.php');
?>

<HTML>
    <HEAD>
        <TITLE>Update Status</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
    <?php 
            include('SQLFunctions.php');
            
            $link = f_sqlConnect();
            $table = Status;
            $q = $_POST["q"];
                //echo '<br>Source table: ' .$table;
    ?>
    <BODY>
        <?php include('filestart.php') ?>
        <H1>Update a status type</H1>
        <?php 
        $sql = "SELECT Status FROM Status WHERE StatusID = ".$q;
        
        if($stmt = $link->prepare($sql)) {
            $stmt->execute();
            $stmt->bind_result($Status);
            while ($stmt->fetch()) {
                echo "  <form action='UpdateStatusCommit.php' method='POST' onsubmit='' />
                            <input type='hidden' name='StatusID' value='".$q."'>
                            <table>
                                <tr>
                                    <td>Status Name:</td> 
                                    <td><input type='text' name='Status' maxlength='50' required value='".$Status."'/></td>
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