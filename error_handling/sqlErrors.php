<?php
function printSqlErrorAndExit(&$cnxn, $sql = '') {
    echo "
    <div class='container page-header'>
    <h5>There was a problem with the request</h5>";
    echo "<pre>";
    echo $cnxn->error;
    echo "</pre>";
    echo "<p>$sql</p>";
    echo "</div></div>";
    $cnxn->close();
    exit;
}
?>