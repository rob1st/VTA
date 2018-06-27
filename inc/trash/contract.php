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
    $displayName = ucfirst(isset($displayNames[$table])
        ? $displayNames[$table]
        : $table);
    $nameLabel = $sqlStrings[$table]['insertFields'][0];
    $descripLabel = $sqlStrings[$table]['insertFields'][1];
        
    $formCtrls = array(
        "<label for='$nameLabel' class='required'>$displayName name</label>
        <input name='$nameLabel' type='text' maxlength='15' class='form-control item-margin-bottom' required>",
        "<label for='$descripLabel'>$displayName description</label>
        <textarea name='$descripLabel' maxlength='100' class='form-control item-margin-bottom'></textarea>"
    );

    $cardHeading = 'Enter ' . $table . ' information';
    $target = 'commitNewData.php';
}
