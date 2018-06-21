<?php
require_once '../vendor/autoload.php';
require_once '../inc/sqlFunctions.php';

/* !! much of this boilerplate could be abstracted away
**    How? a function? the includes folder??
*/

// instantiate Twig classes
$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader,
    array(
        'debug' => true
    )
);

session_start();

/* parse url to establish which view to display
** [0] => action of view, e.g., 'list', 'add'
** [1] => name of table to manage
*/
$pathinfo = strpos($_SERVER['PATH_INFO'], '/') === 0
    ? substr($_SERVER['PATH_INFO'], strpos($_SERVER['PATH_INFO'], '/') + 1)
    : $_SERVER['PATH_INFO'];
    
$pathParams = explode("/", $pathinfo);

// appropriately named file selects template, sql string
// load template into new TemplateWrapper
$template = $twig->load("{$pathParams[0]}.html");
include "../inc/{$pathParams[1]}.php";


// process some data here...
$link = connect();

// query for relevant data
$sql = $queries[$pathParams[0]];

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
    'pageHeading' => ucwords($pathParams[1]).'s',
    'global' => $pathinfo ." ". $sql,
    'tableName' => $pathParams[1],
    'count' => $count
));
