<?php
    include('session.php');
    $role = $_SESSION['Role'];
    $viewIDR = $_SESSION['viewIDR'];
    $username = $_SESSION['Username'];
    if ($role == 'S') $roleT = 'Super Admin';
    elseif ($role == 'A') $roleT = 'Admin';
    elseif ($role == 'U') $roleT = 'User';
    elseif ($role == 'V') $roleT = 'Viewer';
    else $roleT = '';
    $userLinks = [
        'views' => [ 'myIDRs' => "My Inspectors' Daily Reports" ]
    ];
    $adminLinks = [
        'views' => [ 'idrList' => "View Inspectors' Daily Reports" ],
        'forms' => [
            'NewUser' => 'Add new user',
            'NewLocation' => 'Add new Location',
            'NewSystem' => 'Add new system'
        ]
    ];
    $superLinks = [
        'views' => [
            'DisplayUsers' => 'View user list',
            'DisplayEviType' => 'View evidence type list'
        ],
        'forms' => [
            'NewEvidence' => 'Add new evidence type',
            'NewSeverity' => 'Add new severity level',
            'NewStatus' => 'Add new status type'
        ]
    ];
    echo '
        <header class="container page-header">
            <h1 class="page-title">'.$username.'</h1>
            <h3 class="text-secondary user-role-title">'.$roleT.'</h3>
        </header>
    ';
?>
<?php
    include('filestart.php');
    // user account management links
    echo '
        <main class="container main-content">
            <div class="card item-margin-bottom no-border-radius box-shadow">
                <ul class="card-body item-margin-bottom pad-more">
                    <li class="item-margin-bottom"><a href="UpdateProfile.php">Update Profile</a></li>
                    <li class="item-margin-bottom"><a href="UpdatePassword.php">Change Password</a></li>
                </ul>
            </div>
    ';
    echo "
            <div class='card item-margin-bottom no-border-radius box-shadow'>
                <ul class='card-body item-margin-bottom pad-more'>";
                // data views
                if ($role == 'A' OR $role == 'S') {
                    foreach ($adminLinks['views'] as $href => $text) {
                        printf("<li class='item-margin-bottom'><a href='%s.php'>%s</a></li>", $href, $text);
                    }
                    if ($role == 'S') {
                        foreach ($superLinks['views'] as $href => $text) {
                            printf("<li class='item-margin-bottom'><a href='%s.php'>%s</a></li>", $href, $text);
                        }
                    }
                }
    echo '
                </ul>
            </div>';
            // data management links
            if ($role === 'A' || $role === 'S') {
                echo '
                    <div class="card item-margin-bottom no-border-radius box-shadow">
                        <ul class="card-body item-margin-bottom pad-more">';
                        foreach ($adminLinks['forms'] as $href => $text) {
                            printf("<li class='item-margin-bottom'><a href='%s.php'>%s</a></li>", $href, $text);
                        }
                        if ($role === 'S') {
                            foreach ($superLinks['forms'] as $href => $text) {
                                printf("<li class='item-margin-bottom'><a href='%s.php'>%s</a></li>", $href, $text);
                            }
                        }
                echo '
                    </ul>
                </div>';
            }
    echo '
        <div class="center-content"><a href="logout.php" class="btn btn-primary btn-lg">Logout</a></div>
    </main>';
    include('fileend.php');
?>