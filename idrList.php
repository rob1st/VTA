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
        $idrAuth = $row['viewidr'] ? $authLvl[$role] : $row['viewidr'];
        $result->close();
} elseif ($link->error) {
    $userFullName = 'Unable to retrieve user account information';
    $idrAuth = 0;
}
    
$qry = "SELECT idrID, i.UserID, idrDate, Username FROM IDR i JOIN users_enc u on i.UserID=u.UserID";
$orderBy = " ORDER BY idrID";

$errorMsg = [
    'myIDRs' => 'Unable to retrieve reports for user',
    'idrList' => 'There was a problem retrieving report list'
];

$myIDRs = $link->query("$qry WHERE i.UserID='$userID'$orderBy");
if ($link->error) $myIDRs = $errorMsg['myIDRs'];

include 'filestart.php';
?>
<header class='container page-header'>
    <h1 class='page-title'>Inspectors' Daily Reports</h1>
</header>
<main class='container main-content'>
    <div class='row'>
        <div class='col-sm-10 col-md-6 offset-sm-1 offset-md-3'>
            <div class='card no-border-radius box-shadow'>
                <div class='card-body pad-more'>
                    <!--tab controls-->
                    <ul class='nav nav-tabs no-border flex-row flex-nowrap justify-content-center' role='tablist'>
                        <?php
                            $active = ' active';
                            $expanded = " aria-expanded='true'";
                            if ($idrAuth > 1) {
                                echo "
                                    <li class='item-margin-right-less' role='presentation'>
                                        <a href='#allReports' aria-controls='allReports'{$expanded} role='tab' data-toggle='tab' class='h4 pl-3 pb-3 pr-3 border-dark-blue{$active}'>All Reports</a>
                                    </li>";
                                $active = '';
                                $expanded = '';
                            }
                            // render My IDRs button only if user has IDRs of their own
                            if ($myIDRs->num_rows) {
                                echo "
                                    <li class='item-margin-right-less' role='presentation'>
                                        <a href='#myReports' aria-controls='myReports'{$expanded} role='tab' data-toggle='tab' class='h4 pl-3 pb-3 pr-3 border-dark-blue{$active}'>My Reports</a>
                                    </li>";
                            }
                        ?>
                    </ul>
                    <div class='tab-content mt-3 thick-grey-line'>
                        <?php
                            // if user is admin or super, query for all IDRs
                            $active = ' active';
                            if ($role === 'S' || $role === 'A') {
                                if ($result = $link->query($qry.$orderBy)) {
                                    if ($result->num_rows) {
                                        echo "
                                            <div role='tabpanel' id='allReports' class='tab-pane pt-1 pl-2 pr-2 fit-content center-element{$active}'>
                                                <ul class='pl-0'>";
                                        while ($row = $result->fetch_assoc()) {
                                            printf(
                                                "<li class='item-margin-bottom-less'>
                                                    <a href='%s'>%s <span class='text-secondary font-italic'>&bull; %s</span></a>
                                                </li>",
                                                "/idr.php?idrID={$row['idrID']}",
                                                $row['idrDate'],
                                                $row['Username']
                                            );
                                        }
                                        echo "
                                                </ul>
                                            </div>";
                                        $active = '';
                                    } else echo "<h4>That's strange. No reports were found. I suspect something's up.</h4>";
                                    $result->close();
                                } elseif ($link->error) echo "<h4>{$errorMsg['idrList']}</h4>";
                            }
                        ?>
                        <?php
                            // if user has an IDRs of their own render them under My IDRs tab
                            if ($myIDRs) {
                                if ($myIDRs === $errorMsg['myIDRs']) echo "<h4 class='text-secondary'>{$errorMsg['myIDRs']}</h4>";
                                elseif ($myIDRs->num_rows) {
                                    echo "
                                        <div role='tabpanel' id='myReports' class='tab-pane pt-1 pl-2 pr-2 fit-content center-element{$active}'>
                                            <ul class='pl-0'>";
                                            while ($row = $myIDRs->fetch_assoc()) {
                                                printf(
                                                    "<li class='item-margin-bottom-less'>
                                                        <a href='%s'>%s</a>
                                                    </li>",
                                                    "/idr.php?idrID={$row['idrID']}",
                                                    $row['idrDate']
                                                );
                                            }
                                    echo "
                                            </ul>
                                        </div>";
                                    $myIDRs->close();
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
include 'fileend.php';
?>