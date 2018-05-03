<?php
include 'session.php';
include 'SQLFunctions.php';

$userID = $_SESSION['UserID'];
$role = $_SESSION['Role'];

$link = f_sqlConnect();
$qry = "SELECT idrID, UserID, idrDate FROM IDR";
$errorMsg = [
    'myIDRs' => 'Unable to retrieve reports for user',
    'idrList' => 'There was a problem retrieving report list'
];

// first find out if user has an IDRs of their own
if ($result = $link->query("$qry WHERE UserID='$userID'")) {
    if ($result->num_rows) {
        $myIDRs = [];
        while ($row = $result->fetch_row()) {
             array_push($myIDRs, $row);
        }
    } else $myIDRs = null;
    $result->close();
} elseif ($link->error) $myIDRs = $errorMsg['myIDRs'];

include 'filestart.php';
?>
<header class='container page-header'>
    <h1 class='page-title'>Inspectors' Daily Reports</h1>
</header>
<main class='container main-content'>
    <div class='row'>
        <div class='col-6'>
            <a role='button' href='#allReports'>All Reports</a>
        </div>
        <div class='col-6'>
            <a role='button' href='#myReports'>My Reports</a>
        </div>
    <div id='allReports'>
        <div class='col-12'>
            <?php
                // if user is admin or super, query for all IDRs
                if ($role === 'S' || $role === 'A') {
                    if ($result = $link->query($qry)) {
                        if ($result->num_rows) {
                            echo "<ul>";
                            while ($row = $result->fetch_assoc()) {
                                printf("<li><a href='%s'>%s <span class='text-danger font-italic'>%s</span></a></li>", "/idr.php?idrID={$row['idrID']}", $row['idrDate'], $row['UserID']);
                            }
                            echo "</ul>";
                        } else echo "<h4>That's strange. No reports were found. I suspect something's up.</h4>";
                    } elseif ($link->error) echo "<h4>{$errorMsg['idrList']}</h4>";
                }
            ?>
        </div>
    </div>
    <div id='myReports' class='row'>
        <div class='col-12'>
            <?php
                if ($myIDRs) {
                    if ($myIDRs === $errorMsg['myIDRs']) {
                        
                    }
                }
            ?>
        </div>
    </div>
</main>
<?php
include 'fileend.php';
?>