<?php
require_once '../inc/sqlFunctions.php';
require_once '../inc/sqlStrings.php';

$formCtrls = [
    "<label for='systemName' class='required'>System name</label>
    <input name='systemName' type='text' maxlength='55' class='form-control item-margin-bottom' required>",
    "<label for='systemDescrip'>System description</label>
    <textarea name='systemDescrip' maxlength='100' class='form-control item-margin-bottom'></textarea>"
];

$tableName = $table;

if ($action === 'list') { // should 'list' be the default $action, i.e., this all appears w/i else {...} block??
    $link = connect();
    
    try {
        $data = getLookupItems('system', $link);
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
    $cardHeading = 'Enter ' . $table . ' information';
    $target = 'commitNewData.php';
}
