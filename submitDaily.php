<?php
    include('SQLFunctions.php');
    session_start();
    $table = IDR;
    $link = f_sqlConnect();
    
    $userID = $_SESSION['UserID'];
    $userQry = 'SELECT Username FROM users_enc WHERE UserID = '.$userID;

    $check = "SELECT * FROM $table WHERE reportDate AND inspectorID = '{$_POST['curDate']}' & '{$userID}'";
    
    if ($result = mysqli_query($link, $userQry)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $username = $row['Username'];
        }
    }

    $keys = implode(", ", (array_keys($_POST)));
    $values = implode("', '", (array_values($_POST)));
    $json = json_encode($_POST);
    
    $result = mysqli_query($link, $check);
    $numRows = mysqli_num_rows($result);
    
    if ($num_rows > 0) {
    // what is this variable(?) I don't see it declared anywhere
      header("location: $duplicate?msg=1");
    }
    
    if(!f_tableExists($link, $table, DB_Name)) {
        echo 'table "'.$table.'" could not be found';
    } else echo var_dump($_POST);
?>
