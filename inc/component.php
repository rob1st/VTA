<?php
require_once '../inc/sqlFunctions.php';
require_once '../inc/sqlStrings.php';

$formCtrls = array(
    "<label for='compName' class='required'>Component name</label>
    <input type='text' name='compName' maxlength='100' value='' class='form-control item-margin-bottom' required>",
    "<label for='compDescrip'>Component description</label>
    <textarea name='compDescrip' maxlength='1000' class='form-control item-margin-bottom'></textarea>"
);

if ($action === 'list') {
    $sql = $sqlStrings['component']['list'];
        
    try {
        $link = connect();
        
        $data = $link->query($sql);
        
        foreach ($data as &$row) {
            $row['href'] = "/public_html/manage.php/update/component?id={$row['id']}";
        }
        
        $count = $link->count;
    } catch (mysqli_sql_exception $e) {
        echo "<pre style='color: deepPink'>There was a problem fetching from the database: $e</pre>";
    } finally {
        $link->disconnect();
        $cardHeading = $action === 'list'
            ? 'Components'
            : 'Enter component information';
        $tableName = 'component';
        $target = 'commitNewData.php';
    }
}
