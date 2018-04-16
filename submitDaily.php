<?php
    include('SQLFunctions.php');
    session_start();
    $table = IDR;
    $link = f_sqlConnect();

    $userID = $_SESSION['UserID'];
    $userQry = 'SELECT Username FROM users_enc WHERE UserID = '.$userID;
    
    if ($result = mysqli_query($link,$userQry)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $username = $row['Username'];
        }
    }

    $post = var_dump($_POST);
?>
