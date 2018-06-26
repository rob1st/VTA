<?php
require_once '../inc/sqlFunctions.php';
require_once '../inc/sqlStrings.php';

$tableName = $table;

// there's still a lot of boilerplate here. I could get rid of the individual lookupTable files and replace their fcntionality with a fcn
if ($action === 'list') { // should 'list' be the default $action, i.e., this all appears w/i else {...} block??
    $link = connect();
    
    try {
        $data = getLookupItems($table, $link);
        $count = $link->count;
    } catch (mysqli_sql_exception $e) {
        echo "<pre style='color: goldenRod'>There was a problem fetching from the database: $e</pre>";
    } catch (Exception $e) {
        echo "<pre style='color: deepPink'>There was a problem fetching from the database: $e</pre>";
    } finally {
        $cardHeading = ucfirst($table) . 's';
        $link->disconnect();
    }
} elseif ($action === 'add') {
    $formCtrls = array(
        "<label for='compName' class='required'>Component name</label>
        <input type='text' name='compName' maxlength='100' value='' class='form-control item-margin-bottom' required>",
        "<label for='compDescrip'>Component description</label>
        <textarea name='compDescrip' maxlength='1000' class='form-control item-margin-bottom'></textarea>"
    );

    $cardHeading = 'Enter ' . $table . ' information';
    $target = 'commitNewData.php';
}
