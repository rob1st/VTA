<?php
include 'session.php';
include 'SQLFunctions.php';

$userID = $_SESSION['UserID'];
$role = $_SESSION['Role'];

$link = f_sqlConnect();
$idrQry = "SELECT idrID, idrDate FROM IDR WHERE UserID='$userID'";
?>
<ul>
    <?php
        if ($result = $link->query($idrQry)) {
            while ($row = $result->fetch_assoc()) {
                printf("<li><a href='%s'>%s</a></li>", "/idr.php?idrID={$row['idrID']}", $row['idrDate']);
            }
        }
    ?>
</ul>