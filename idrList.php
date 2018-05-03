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

include 'filestart.php';
?>
<header class='container page-header'>
    <h1 class='page-title'>Inspectors' Daily Reports</h1>
</header>
<main class='container main-content'>
    <div class='row'>
        <div class='col-6'>
            <h4 class='text-center'><a role='button' href='#allReports'>All Reports</a></h4>
        </div>
        <div class='col-6'>
            <h4 class='text-center'><a role='button' href='#myReports'>My Reports</a></h4>
        </div>
    </div>
    <div id='allReports' class='row'>
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
                        $result->close();
                    } elseif ($link->error) echo "<h4>{$errorMsg['idrList']}</h4>";
                }
            ?>
        </div>
    </div>
    <?php
        // if user has an IDRs of their own render a button to show them
        if ($result = $link->query("$qry WHERE UserID='$userID'")) {
            if ($result->num_rows) {
                echo "
                    <div id='myReports' class='row'>
                        <div class='col-12'>
                            <ul>";
                            while ($row = $result->fetch_assoc()) {
                                printf("<li><a href='%s'>%s</a></li>", "/idr.php?idrID={$row['idrID']}", $row['idrDate']);
                            }
                echo "
                            </ul>
                        </div>
                    </div>";
            }
            $result->close();
        } elseif ($link->error) echo "<h4 class='text-secondary'>{$errorMsg['myIDRs']}</h4>";
    ?>
</main>
<?php
include 'fileend.php';
?>