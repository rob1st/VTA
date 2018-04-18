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
    $check = "SELECT * FROM $idrTable WHERE (idrDate='{$_POST['idrDate']}') AND (userID={$_POST['userID']})";
    $result = mysqli_query($link, $check);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<h1 style='padding: 1rem; background-color: #30303699; text-align: center; color: #ed1; font-family: monospace;'>Duplicate record found</h1>";
    } else {
        // if no dupe, handle POST data
        // first, qry IDR column names, store them as keys of an array
        $query = 'SHOW COLUMNS FROM '.$idrTable;
        $result = $link->query($query);
        
        while($row = $result->fetch_assoc()){
            $key = $row['Field'];
            if ($post[$key]) {
                $idrData[$key] = $post[$key];
            }
            // destroy in idrData any key=>val pair not in result
            else unset($idrData[$key]);
            // destroy in $post any key found in result
            unset($post[$key]);
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
            $query = "INSERT INTO $idrTable ($keys) VALUES ('$vals')";
        }
    
        // this is the block that does actually does the INSERT query
        if ($result = $link->query($query)) {
            http_response_code(201);
            $code = http_response_code();
            $newIdrID = $link->insert_id;
            echo "
                <div style='max-width: 80%; margin: 2.5rem auto; font-family: monospace; padding: 1.5rem; border: 1px solid #3333;'>
                    <h3>$code</h3>
                    <h1>New record created</h1>
                    <h3>{$link->insert_id}</h3>";
                    echoAs2OLs(array_keys($post), array_values($post));
            echo "</div>";
            // grab new ID and attach it to equip, labor, activity data
            $laborData = [];
            $equipData = [];
            foreach ($post as $key => $val) {
                if (strpos($key, 'labor') || strpos($key, 'equip')) {
                    $num = intval(substr($key, strpos($key, '_') + 1));
                    if (strpos($key, 'labor')) {
                        // assign 'labor' vals to 'labor' keys @ num
                        $laborKey = substr($key, 0, strpos('_'));
                        $laborData[$num][$laborKey] = $val;
                        $laborData[$num]['idrID'] = $newIdrID;
                    } else {
                        // assign 'equip' vals to 'equip' keys @ num
                        $equipKey = substr($key, 0, strpos('_'));
                        $equipData[$num][$equipKey] = $val;
                        $equipData[$num]['idrID'] = $newIdrID;
                    }
                    // and unset $key from $post
                     unset($post, $key);
                } else {
                    // assign 'act' vals to 'act' keys @ actNum, append equip or labor ID to act
                    // I'll have to do this one after slicing out 'labor' and 'equip' keys/vals
                    continue;
                }
            }
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
    $typeOfUserID = gettype($_POST['userID']);
    $typeOfIdrDate = gettype($_POST['idrDate']);
    echo "
        <h3>{$_POST['userID']} {$typeOfUserID}</h3>
        <h3>{$_POST['idrDate']} {$typeOfIdrDate}</h3>";
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
