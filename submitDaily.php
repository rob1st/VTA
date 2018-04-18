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
    if ($result = $link->query($userQry)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $username = $row['Username'];
        }
    }

    // check for existing submission
    $check = "SELECT * FROM $idrTable WHERE idrDate AND inspectorID = '{$_POST['idrDate']}' & '{$_POST['inspectorID']}'";
    if($link->query($check)->num_rows) {
        echo "<h1 style='padding: 1rem; background-color: #3030369; text-align: center; color: #dc1; font-family: monospace;'>Duplicate record found</h1>";
    } else {
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
    
        $keys = implode(", ", array_keys($idrData));
        $vals = implode("', '", array_values($idrData));
    
        // currently this fcn, found in SQLFunctions, is broken
        // don't repair it unless you're ready to deal with the bugs that may produce
        if (!f_tableExists($link, $idrTable, DB_Name)) {
        // shouldn't this be an error handler like the duplicate check above(?)
            echo 'table "'.$idrTable.'" could not be found';
        } else {
        // create INSERT query
            $insertIdrQry = "INSERT INTO $idrTable ($keys) VALUES ('$vals')";
        }
    
        // this is the block that does actually does the INSERT query
        if ($result = $link->query($insertIdrQry)) {
            http_response_code(201);
            $code = http_response_code();
            echo "
                <div style='max-width: 80%; margin: 2.5rem auto; font-family: monospace; padding: 1.5rem; border: 1px solid #3333;'>
                    <h3>$code</h3>
                    <h1>New record created</h1>
                    <h3>$result</h3>
                </div>";
        } else {
            http_response_code(500);
            $code = http_response_code();
            echo "
                <div style='max-width: 80%; margin: 2.5rem auto; font-family: monospace; padding: 1.5rem; border: 1px solid #3333;'>
                    <h3>$code</h3>
                    <h1>Failed to create new record:</h1>
                    <h3>$result</h3>
                </div>";
        }
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
