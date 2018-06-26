<?php
require_once '../inc/sqlFunctions.php';
require_once '../inc/sqlStrings.php';

$formCtrls = [
    "<label for='locationName' class='required'>Location name</label>
    <input name='locationName' type='text' maxlength='100' class='form-control item-margin-bottom' required>",
    "<label for='locationDescrip'>Location description</label>
    <textarea name='locationDescrip' maxlength='100' class='form-control item-margin-bottom'></textarea>"
];

if ($action === 'list') {
    $sql = $sqlStrings['location']['list'];
    
    try {
        $link = connect();
        
        $data = $link->query($sql);
        
        foreach ($data as &$row) {
            $row['href'] = "/public_html/manage.php/update/component?id={$row[0]}";
        }
        
        $count = $link->count;
    } catch (mysqli_sql_exception $e) {
        echo "<pre style='color: deepPink'>There was a problem fetching from the database: $e</pre>";
    } finally {
        $cardHeading = $action === 'list'
            ? 'Locations'
            : 'Enter location information';
        $tableName = 'location';
        $target = 'commitNewData.php';

        $link->disconnect();
    }
}

