<?php
    include('session.php');
    $role = $_SESSION['Role'];
    $username = $_SESSION['Username'];
    include('filestart.php');
    echo '
        <header class="container page-header">
            <h1 class="page-title">'.$username.'</h1>
        </header>
    ';
?>
<?php
    echo '
        <main class="container main-content">
            <ul class="user-menu">
                <li><a href="UpdateProfile.php">Update Profile</a></li>
                <li><a href="UpdatePassword.php">Change Password</a></li>
            </ul>
        </main>
    ';
    include('fileend.php');
?>