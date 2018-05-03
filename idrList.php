<?php
include 'session.php';
include 'SQLFunctions.php';

$userID = $_SESSION['UserID'];
$role = $_SESSION['Role'];

$link = f_sqlConnect();
$qry = "SELECT userid, username, firstname, lastname, viewidr FROM users_enc where userid='$userID'";

if ($result = $link->query($qry)) {
        $row = $result->fetch_assoc();
        $userFullName = $row['firstname'].' '.$row['lastname'];
        $authLvl = [
            'V' => 0,
            'U' => 1,
            'A' => 2,
            'S' => 3
        ];
        $idrAuth = intval($row['viewidr']) || $authLvl[$role];
        $result->close();
} elseif ($link->error) {
    $userFullName = 'Unable to retrieve user account information';
    $idrAuth = 0;
}
    
$qry = "SELECT idrID, i.UserID, idrDate, Username FROM IDR i JOIN users_enc u on i.UserID=u.UserID";

$errorMsg = [
    'myIDRs' => 'Unable to retrieve reports for user',
    'idrList' => 'There was a problem retrieving report list'
];

$myIDRs = $link->query("$qry WHERE i.UserID='$userID'");
if ($link->error) $myIDRs = $errorMsg['myIDRs'];

include 'filestart.php';
?>
<header class='container page-header'>
    <h1 class='page-title'>Inspectors' Daily Reports</h1>
</header>
<main class='container main-content'>
    <!--tab controls-->
    <ul class='nav nav-tabs' role='tablist'>
        <li class='active' role='presentation'>
            <a href='#allReports' aria-controls='allReports' role='tab' data-toggle='tab'>All Reports</a>
        </li>
        <?php
            // render My IDRs button only if user has IDRs of their own
            if ($myIDRs->num_rows) {
                echo "
                    <li role='presentation'>
                        <a href='#myReports' aria-controls='myReports' role='tab' data-toggle='tab'>My Reports</a>
                    </li>";
            }
        ?>
    </ul>
    <div class='tab-content'>
        <div role='tabpanel' id='allReports' class='tab-pane active'>
            <?php
                // if user is admin or super, query for all IDRs
                if ($role === 'S' || $role === 'A') {
                    if ($result = $link->query($qry)) {
                        if ($result->num_rows) {
                            echo "<ul>";
                            while ($row = $result->fetch_assoc()) {
                                printf("<li><a href='%s'>%s <span class='text-secondary font-italic'>%s</span></a></li>", "/idr.php?idrID={$row['idrID']}", $row['idrDate'], $row['Username']);
                            }
                            echo "</ul>";
                        } else echo "<h4>That's strange. No reports were found. I suspect something's up.</h4>";
                        $result->close();
                    } elseif ($link->error) echo "<h4>{$errorMsg['idrList']}</h4>";
                }
            ?>
        </div>
        <?php
            // if user has an IDRs of their own render them under My IDRs tab
            if ($myIDRs) {
                if ($myIDRs === $errorMsg['myIDRs']) echo "<h4 class='text-secondary'>{$errorMsg['myIDRs']}</h4>";
                elseif ($myIDRs->num_rows) {
                    echo "
                        <div role='tabpanel' id='myReports' class='tab-pane'>
                            <ul>";
                            while ($row = $myIDRs->fetch_assoc()) {
                                printf("<li><a href='%s'>%s</a></li>", "/idr.php?idrID={$row['idrID']}", $row['idrDate']);
                            }
                    echo "
                            </ul>
                        </div>";
                    $myIDRs->close();
                }
            }
        ?>
    </div>
</main>
<?php
include 'fileend.php';
?>