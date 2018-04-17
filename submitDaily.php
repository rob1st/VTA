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
        else $idrData[$key] = 'null';
        // destroy in $post any key found in idrCols
        unset($post[$key]);
        // try unsetting idrID and see if query is accepted
        unset($idrData['idrID']);
    }
    
    $keys = implode(', ', array_keys($idrData));
    $vals = implode(', ', array_values($idrData));
    
    if (!f_tableExists($link, $idrTable, DB_Name)) {
    // shouldn't this be an error handler like the duplicate check above(?)
        echo 'table "'.$idrTable.'" could not be found';
    } else {
        $insertIdrQry = "INSERT INTO $idrTable ($keys) VALUES ('$vals')";
    }
    
    // this is the line that does actually does the INSERT query
    if (!$result = $link->query($insertIdrQry)) {
        echo "
            <div style='max-width: 80%; margin: 2.5rem auto; font-family: monospace; padding: 1.5rem; border: 1px solid #3333;'>
                <h1 style='width: fit-content; color: red'>Unable to create new records in db table \"{$idrTable}\"</h1>
                <h2 style='width: fit-content; color: #c33'>$link->error</h2>
                <h3 style='width: fit-content; color: #c33'>$insertIdrQry</h3>";
                echoAs2OLs(
                    explode(', ', $keys),
                    explode(', ', $vals)
                );
        echo "</div>";
    } else {
        while ($row = $result->fetch_assoc()) {
            $newIdrID = $row['idrID'];
        }
        http_response_code('201');
        echo "New record created: $newIdrID";
    }
?>
<?php
// this is all stuff for testing
// pls destroy before deploy
function echoAs2OLs($arr1, $arr2) {
    echo "
        <div style='display: flex; flex-flow: row nowrap;'>
        <ol start='0'>";
            foreach ($arr1 as $key) {
                echo "<li>$key</li>";
            }
    echo "
        </ol>
        <ol start='0'>";
            foreach ($arr2 as $val) {
                echo "<li>$val</li>";
            }
    echo "
        </ol>
        </div>
    ";
}
?>
