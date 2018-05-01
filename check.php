<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
<?php
session_start();

if ($_SESSION['Role'] === 'S') {
    require 'SQLFunctions.php';
    
    $link = f_sqlConnect();
    
    $d = new DateTime();
    
    $dir = new DirectoryIterator('/home/ubuntu/workspace/');
    
    echo "<pre>";
    var_dump($_SESSION);
    echo "</pre>";
    
    if ($idrID = $_GET['idrID']) {
        $idrQry = "SELECT idrID, i.UserID, firstname, lastname, idrDate, Contract, weather, shift, EIC, watchman, rapNum, sswpNum, tcpNum, LocationName, opDesc, approvedBy, editableUntil
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
                $expiry = new DateTime($row['idrDate']);
                $expiry->
                    setDate($expiry->format('Y'), $expiry->format('m'), $expiry->format('j') + 1)->
                    setTime('12', '59', '59');
                $timeLeft = $d->diff($expiry);
                
                echo "
                <p style='font-size: .85rem; color: orangeRed;'>{$row['idrDate']}</p>
                <pre style='color: magenta'>";
                echo $expiry->format($expiry::W3C);
                echo "<br>";
                echo $d->format($d::W3C);
                echo "<br>";
                echo intval($timeLeft->f);
                echo "</pre>";
            }
        } else {
            echo "</pre>";
            echo "<pre style='color: crimson'>";
            echo $link->error;
            echo "</pre>";
        }
    }
}
?>
</body>
</html>
