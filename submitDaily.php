<?php
    include('SQLFunctions.php');
    session_start();
    
    $post = $_POST;
    $idrTable = 'IDR';
    $equipTable = 'equipment';
    $laborTable = 'labor';
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
    
        // this is the block that does actually executes the INSERT query
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
            $actData = [];
            foreach ($post as $key => $val) {
                if (strpos($key, 'labor') !== false || strpos($key, 'equip') !== false) {
                    $num = intval(substr($key, strpos($key, '_') + 1));
                    // there shouldn't be any laborOrEquip keys submitted, but if one is found, rm it
                    if (strpos($key, 'laborOrEquip') !== false) unset($post[$key]);
                    elseif (strpos($key, 'labor') !== false) {
                        // assign 'labor' vals to 'labor' keys @ num
                        $laborKey = substr($key, 0, strpos($key, '_'));
                        $laborData[$num][$laborKey] = $val;
                        $laborData[$num]['idrID'] = $newIdrID;
                    } else {
                        // assign 'equip' vals to 'equip' keys @ num
                        $equipKey = substr($key, 0, strpos($key, '_'));
                        $equipData[$num][$equipKey] = $val;
                        $equipData[$num]['idrID'] = $newIdrID;
                    }
                    // and unset $key from $post
                     unset($post, $key);
                } elseif (strpos($key, 'act') !== false) {
                // assign 'act' vals to 'act' keys @ actNum
                    echo "<p style='margin: .125rem 0; font-size: .75rem; color: #446;'>strpos($key, 'act') !== false</p>";
                    // num following 1st '_' in $key is resource number (labor or equip)
                    $rsrcNum = intval(substr($key, strpos($key, '_') + 1));
                    // num following 2nd '_' in $key is activity number for that resource
                    $actNum = intval(substr($key, strpos($key, '_', strpos($key, '_') + 1) + 1));
                    $actKey = substr($key, 0, strpos($key, '_'));
                    $actData[$rsrcNum][$actNum][$actKey] = $val;
                    $actData[$rsrcNum][$actNum]['idrID'] = $newIdrID;
                    // I'll have to append laborID or equipID after INSERT and retrieval of insert_key
                } else {
                    continue;
                }
            }
            var_dump($actData);
            echo "<hr style='border-color: #3336;' />";
            // build labor & equipment queries
            if (count($laborData)) {
                // foreach labor data, find associated activity data & parse it to array
                var_dump($laborData);
                foreach ($laborData as $index => $subarr) {
                    $keys = implode(", ", array_keys($subarr));
                    $vals = implode("', '", array_values($subarr));
                    $query = "INSERT INTO $laborTable ($keys) VALUES ('$vals')";
                    echo "<p style='margin: .125rem 0; font-size: .9rem; color: magenta'>$query</p>";
                    // once data is parsed & committed, rm it from data array
                    unset($laborData[$index]);
                    // after successful INSERT, unset $key, grab insert_key
                    if ($result = 1) {
                        http_response_code(201);
                        $code = http_response_code();
                    // build queries from activities that fall within same index as labor
                        if (count($actData[$index])) {
                            foreach ($actData[$index] as $key => $val) {
                                $actKeys = implode(", ", array_keys($val));
                                $actVals = implode("', '", array_values($val));
                                $actQry = "INSERT INTO $actTable ($actKeys) VALUES ('$actVals')";
                                echo "<p style='margin: .125rem 0; font-size: .9rem; color: goldenrod'>$actQry</p>";
                            }
                        } else continue;
                    } else echo "
                        <div style='display: flex; flex-flow: column nowrap; justify-content: center; align-items: flex-start'>
                            <h1 style='color: red'>Houston, we have a problem:</h1>
                            <h3>$query</h3>
                            <h3>$result</h3>
                        <div>
                    ";
                }
            }
            if (count($equipData)) {
                // foreach equip data, find associated activity data & parse it to array
                var_dump($equipData);
                foreach ($equipData as $subarr) {
                    $keys = implode(", ", array_keys($subarr));
                    $vals = implode("', '", array_values($subarr));
                    $query = "INSERT INTO $equipTable ($keys) VALUES ('$vals')";
                    echo "<p style='margin: .125rem 0; font-size: .9rem; color: green'>$query</p>";
                    // once data is parsed & committed, rm it from data array
                    unset($equipData[$index]);
                    // after successful INSERT, unset $key, grab insert_key
                    if ($result = 1) {
                        http_response_code(201);
                        $code = http_response_code();
                    // build queries from activities that fall within same index as equip
                        if (count($actData[$index])) {
                            foreach ($actData[$index] as $key => $val) {
                                $actKeys = implode(", ", array_keys($val));
                                $actVals = implode("', '", array_values($val));
                                $actQry = "INSERT INTO $actTable ($actKeys) VALUES ('$actVals')";
                                echo "<p style='margin: .125rem 0; font-size: .9rem; color: goldenrod'>$actQry</p>";
                            }
                        } else continue;
                    } else echo "
                        <div style='display: flex; flex-flow: column nowrap; justify-content: center; align-items: flex-start'>
                            <h1 style='color: red'>Houston, we have a problem:</h1>
                            <h3>$query</h3>
                            <h3>$result</h3>
                        <div>
                    ";
                }
            }
        } else {
            http_response_code(500);
            $code = http_response_code();
            echo "
                <div style='max-width: 80%; margin: 2.5rem auto; font-family: monospace; padding: 1.5rem; border: 1px solid #3333;'>
                    <h3>$code</h3>
                    <h1>Failed to create new record:</h1>
                    <h3>db's response: $result</h3>
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
