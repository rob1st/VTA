<?php
    include('SQLFunctions.php');
    session_start();
    $idrTable = IDR;
    $link = f_sqlConnect();
    
    $numRe = '/\d$/';
    
    $userID = $_SESSION['UserID'];
    $userQry = 'SELECT Username FROM users_enc WHERE UserID = '.$userID;

    // why do we need this if Username is already stored in $_SESSION(?)
    if ($result = mysqli_query($link, $userQry)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $username = $row['Username'];
        }
    }

    // check for existing submission
    $checkQry = "SELECT * FROM $idrTable WHERE reportDate AND inspectorID = '{$_POST['idrDate']}' & '{$_POST['inspectorID']}'";
    if($check = mysqli_query($link, $checkQry)) {
        $numRows = mysqli_num_rows($check);
    }
    
    if ($numRows > 0) {
    // what is this variable(?) I don't see it declared anywhere
    // shouldn't this be a msg about duplicate record(?)
      header("location: $duplicate?msg=1");
    }
    
    // if no dupe, handle POST data
    $idrKeys = [
        'inspectorID', 'contractDay', 'idrDate', 'project', 'weather', 'shift', 'EIC', 'watchman', 'rapNum', 'sswpNum', 'tcpNum', 'locationID', 'opDesc'
    ];
    // iterate over $_POST numbered keys and place them in equip or labor
    foreach ($_POST as $key => $val) {
        if (preg_match($numRe, $key)) {
        // if key has a number in it, it belongs to equip, labor, or activity
            if (strpos('equip', $key)) {
                $equipKeys .= $key;
                $equipVals .= $val;
            } elseif (strpos('labor', $key)) {
                
            }
        }
    }
    
    $keys = implode(", ", (array_keys($_POST)));
    $values = implode("', '", (array_values($_POST)));
    $json = json_encode($_POST);
    
    if(!f_tableExists($link, $idrTable, DB_Name)) {
    // shouldn't this be an error handler like the duplicate check above(?)
        echo 'table "'.$idrTable.'" could not be found';
    } else {
        $insertIdrQry = "INSERT INTO $idrTable($keys, DateCreated, Created_by) VALUES ('$values', CURDATE(), '$Username')";
        echo var_dump($_POST);
    }
?>
