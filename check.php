<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
session_start();
require 'SQLFunctions.php';

$link = f_sqlConnect();

$dir = new DirectoryIterator('/home/ubuntu/workspace/');
?>
<?php
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";
?>
<?php
if ($idrID = $_GET['idrID']) {
    $idrQry = "SELECT idrID, i.UserID, firstname, lastname, idrDate, Contract, weather, shift, EIC, watchman, rapNum, sswpNum, tcpNum, LocationName, opDesc, approvedBy
    FROM (((IDR i
    JOIN users_enc u ON
    i.UserID=u.UserID)
    JOIN Location l ON
    i.LocationID=l.LocationID)
    JOIN Contract c ON
    i.ContractID=c.ContractID)
    WHERE i.idrID=$idrID";

    if ($result = $link->query($idrQry)) {
        while($row = $result->fetch_assoc()) {
            $idrDate = new DateTime($row['idrDate']);
            $idrDate->
                setDate($idrDate->format('Y'), $idrDate->format('m'), $idrDate->format('j') + 1)->
                setTime('12', '59', '59');
            echo "
            <p style='font-size: .85rem; color: orangeRed;'>{$row['idrDate']}</p>
            <pre style='color: magenta'>";
            echo $idrDate->format($idrDate::W3C);
            echo "</pre>";
        }
    } else {
        echo "</pre>";
        echo "<pre style='color: crimson'>";
        var_dump($link);
        echo "</pre>";
    }
} else {
    echo "<ol style='color: grey'>";
    foreach ($dir as $file) {
        $content = file_get_contents($file->getPathname());
        if (strpos($content, $string) !== false) {
            echo "<li>{$file}</li>";
        }
    }
    echo "</ol>";
}
?>
</body>
</html>