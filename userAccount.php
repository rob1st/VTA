<?php
    include('session.php');
    $role = $_SESSION['Role'];
    $username = $_SESSION['Username'];
    if ($role == 'S') $roleT = 'Super Admin';
    elseif ($role == 'A') $roleT = 'Admin';
    elseif ($role == 'U') $roleT = 'User';
    elseif ($role == 'V') $roleT = 'Viewer';
    else $roleT = '';
    echo '
        <header class="container page-header">
            <h1 class="page-title">'.$username.'</h1>
            <h3 class="page-subtitle user-role-title">'.$roleT.'</h3>
        </header>
    ';
?>
<?php
    include('filestart.php');
    echo '
        <main class="container main-content">
            <ul class="container grey-back user-menu">
                <li class="user-menu-item"><a href="UpdateProfile.php">Update Profile</a></li>
                <li class="user-menu-item"><a href="UpdatePassword.php">Change Password</a></li>
            </ul>
    ';
    if($role == 'A' OR 'S') {
        echo '
            <ul class="container grey-back user-menu admin-menu">
                <li class="user-menu-item admin-menu-item"><a href="NewUser.php">Add User</a></li>
                <li class="user-menu-item admin-menu-item"><a href="NewLocation.php">Add Location</a></li>
                <li class="user-menu-item admin-menu-item"><a href="NewSystem.php">Add System</a></li>
            </ul>
        ';
        if ($role == 'S') {
            echo '
                <ul class="container grey-back user-menu superadmin-menu">
                    <li class="user-menu-item superadmin-menu-item"><a href="NewEvidence.php">Add Evidence Type</a></li>
                    <li class="user-menu-item admin-menu-item"><a href="NewSeverity.php">Add Severity Type</a></li>
                    <li class="user-menu-item admin-menu-item"><a href="NewStatus.php">Add Status Type</a></li>
                </ul>
                <a href="logout.php" class="btn btn-primary">Logout</a>
                </main>
            ';
        } else echo '<a href="logout.php" class="btn btn-primary">Logout</a></main>';

    }
    else echo '</main>';
    include('fileend.php');
?>