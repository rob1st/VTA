<?php
require_once '../vendor/autoload.php';
require_once '../inc/sqlFunctions.php';

/* !! much of this boilerplate could be abstracted away
**    How? a function? the includes folder??
*/
session_start();

$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader,
    array(
        'debug' => true
    )
);

// load template into new TemplateWrapper
$template = $twig->load('manage.html');

// process some data here...
$link = connect();

$sql = "SELECT *, count(compID) FROM component WHERE compName <> ''";

try {
    if (!$res = $link->query($sql)) throw new mysqli_sql_exception('Unable to connect to database');
    $count = $res->num_rows;
} catch (mysqli_sql_exception $e) {
    echo $e;
} catch (Exception $e) {
    echo $e;
}

// then render the template with appropriate variables
/* !! navbar only has two possible states
**    should rely on the nav template for which state is shown
**    and pass it only a loggedIn/notLoggiedIn param
*/
$template->display(array(
    'title' => 'Components',
    'navbarHeading' => $_SESSION['Username'],
    'navItems' => array(
        'Home' => 'account.php',
        'Asset List' => 'assets.php',
        'Deficiencies' => 'defs.php',
        'Daily Report' => 'idr.php',
        'Help' => 'help.php',
        'Logout' => 'logout.php'
    ),
    'pageHeading' => 'Manage Components',
    'tableName' => 'component',
    'count' => $count
));
