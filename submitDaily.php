<?php
    include('SQLFunctions.php');
    session_start();
    $table = CDL;
    $link = f_sqlConnect();

    $post = var_dump($_POST);

    // $userId = $_SESSION['UserID'];
    // $user = 'SELECT Username FROM user_enc WHERE UserID='.$userId;
    // if ($result = mysqli_query($link, $userId)) {
    //     while ($row = mysqli_fetch_assoc($result)) $username = $row['Username'];
    // }
?>
