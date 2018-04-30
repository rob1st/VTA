<?php
    include('session.php');
    $role = $_SESSION['Role'];
    include('filestart.php');
?>
<header class="container page-header">
    <h1 class="page-title">Admin Functions</h1>
</header>
<?php
    echo '
        <main class="container main-content">
            <ul class="admin-menu admin-add-menu">
                <li><a href="NewUser.php">Add User</a></li>
                <li><a href="NewLocation.php">Add Location</a></li>
                <li><a href="NewSystem.php">Add System</a></li>
            </ul>
    ';
    if($role == 'S') {
        echo '
            <ul class="admin-menu superuser-menu admin-add-menu">
                <li><a href="NewEvidence.php">Add Evidence Type</a></li>
                <li><a href="NewSeverity.php">Add Severity Type</a></li>
                <li><a href="NewStatus.php">Add Status Type</a></li>
            </ul></main>
        ';
    } else echo '</main>';
    include('fileend.php');
?>