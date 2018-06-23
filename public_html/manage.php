<?php
require_once '../vendor/autoload.php';

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

//* DEFAULTS */
$title = 'Manage Data';
$pageHeading = 'List of _____';
$cardHeading = '';
$tableName = '';
$data = [];
$count = 0;

/* parse url to establish which view to display
** [0] => action of view, e.g., 'list', 'add'
** [1] => name of table to manage
*/
$pathinfo = strpos($_SERVER['PATH_INFO'], '/') === 0
    ? substr($_SERVER['PATH_INFO'], strpos($_SERVER['PATH_INFO'], '/') + 1)
    : $_SERVER['PATH_INFO'];
    
$pathParams = explode("/", $pathinfo);

// appropriately named file selects template and sql string
// otherwise use default
list($action, $table) = count($pathParams) >= 2
    ? [ $pathParams[0], $pathParams[1] ]
    : [ 'list', 'list' ];

$template = $twig->load("$action.html");
    
$include = "$table.php";

include "../inc/$include";

// process some data here...

// included sql file should perform the query and return the data
/* included file will also include relevant vars for display
** $title
** $pageHeading
** $cardHeading
** $tableName
** $data
** $count
*/

// then render the template with appropriate variables
/* !! navbar only has two possible states
**    should rely on the nav template for which state is shown
**    and pass it only a loggedIn/notLoggiedIn param
*/
$template->display(array(
    'navbarHeading' => $_SESSION['Username'],
    'navItems' => array(
        'Home' => 'account.php',
        'Asset List' => 'assets.php',
        'Deficiencies' => 'defs.php',
        'Daily Report' => 'idr.php',
        'Help' => 'help.php',
        'Logout' => 'logout.php'
    ),
    'title' => $title,
    'pageHeading' => $pageHeading,
    'meta' => 'meta',
    'cardHeading' => $cardHeading,
    'tableName' => $tableName,
    'data' => $data,
    'count' => $count
));
