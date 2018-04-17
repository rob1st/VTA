<?php
    include('SQLFunctions.php');
    session_start();
    
    $post = $_POST;
    $idrTable = 'IDR';
        $idrEquipLink = 'idrEquip_link';
    $equipTable = 'equipment';
        $idrLaborLink = 'idrLabor_link';
    $laborTable = 'labor';
        $equipActLink = 'equipAct_link';
        $laborActLink = 'laborAct_link';
    $actTable = 'activity';
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
    // first, qry IDR column names, store them as keys of an array
    $idrColQry = 'SHOW COLUMNS FROM '.$idrTable;
    $idrCols = $link->query($idrColQry);
    
    while($row = $idrCols->fetch_assoc()){
        $key = $row['Field'];
        if ($post[$key]) {
            $idrData[$key] = $post[$key];
        }
        else $idrData[$key] = null;
        // destroy in $post any key found in idrCols
        unset($post[$key]);
    }
    
    $keys = array_keys($idrData);
    $vals = array_values($idrData);
    
    if(!f_tableExists($link, $idrTable, DB_Name)) {
    // shouldn't this be an error handler like the duplicate check above(?)
        echo 'table "'.$idrTable.'" could not be found';
    } else {
        // $insertIdrQry = "INSERT INTO $idrTable($keys, DateCreated, Created_by) VALUES ('$values', CURDATE(), '$Username')";
        echo "
            <div style='display: flex; flex-flow: row nowrap;'>
            <ol start='0'>";
                foreach ($keys as $key) {
                    echo "<li>$key</li>";
                }
        echo "
            </ol>
            <ol start='0'>";
                foreach ($vals as $val) {
                    echo "<li>$val</li>";
                }
        echo "
            </ol>
            </div>
        ";
    }
?>
