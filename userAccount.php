<?php
    include('session.php');
    $role = $_SESSION['Role'];
    $username = $_SESSION['Username'];
    echo '
        <header class="container page-header">
            <h1 class="page-title">'.$username.' - '.$role.'</h1>
        </header>
    ';
?>
<?php
    include('filestart.php');
    echo '
        <main class="container main-content">
            <ul class="user-menu">
                <li><a href="UpdateProfile.php">Update Profile</a></li>
                <li><a href="UpdatePassword.php">Change Password</a></li>
            </ul>
    ';
    if($role == 'A' OR 'S') {
        echo '
            <ul class="user-menu admin-menu">
                <li><a href="NewUser.php">Add User</a></li>
                <li><a href="NewLocation.php">Add Location</a></li>
                <li><a href="NewSystem.php">Add System</a></li>
            </ul>
        ';
        if ($role == 'S') {
            echo '
                <ul class="user-menu superadmin-menu">
                    <li><a href="NewEvidence.php">Add Evidence Type</a></li>
                    <li><a href="NewSeverity.php">Add Severity Type</a></li>
                    <li><a href="NewStatus.php">Add Status Type</a></li>
                </ul>
                </main>
            ';
        } else echo '</main>';

    }
    else echo '</main>';
    include('fileend.php');
?>