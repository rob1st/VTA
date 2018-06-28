<?php
require_once '../vendor/autoload.php';
require_once '../inc/sqlFunctions.php';
require_once '../inc/lookupQryFcns.php';

session_start();

// instantiate classes
$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader,
    array(
        'debug' => true
    )
);

session_start();

//* DEFAULTS */
$contextVars = array(
    'navbarHeading' => $_SESSION['Username'],
    'title' => 'Manage Data',
    'pageHeading' => 'List of lookup tables',
    'cardHeading' => '',
    'tableName' => '',
    'data' => [],
    'count' => 0
);

/* parse url to establish which view to display
** [0] => action of view, e.g., 'list', 'add'
** [1] => name of table to manage
*/
$pathinfo = substr($_SERVER['PATH_INFO'], strpos($_SERVER['PATH_INFO'], '/') + 1);
    
$pathParams = explode("/", $pathinfo);

// appropriately named file selects template and sql string
// otherwise use default
list($action, $tableName) = count($pathParams) >= 2
    ? [ $pathParams[0], $pathParams[1] ]
    : [ 'list', '' ];

$template = $twig->load("$action.html");
$contextVars['meta'] = $action;

// included sql file should perform the query and return table data
/* included file will also include relevant vars for display
** $title
** $pageHeading
** $cardHeading
** $tableName
** $data
** $count
*/
// include "../inc/$include";

if ($tableName) {
    $displayName = $displayNames[$tableName] ?: $tableName;
    
    $contextVars['meta'] = $tableName;
    $contextVars['tableName'] = $tableName;
    $contextVars['pageHeading'] = $contextVars['title'] = ucfirst(
        $action === 'list'
            ? $action . ' of '. pluralize($displayName)
            : $action . ' ' . $displayName
    );
    $contextVars['cardHeading'] = ucfirst(pluralize($displayName));

    $link = connect();

    try {
        $contextVars = array_merge(
            $contextVars,
            getLookupData($action, $tableName, $link)
        );
    } catch (Exception $e) {
        echo "<pre style='color: orangeRed'>There was a problem retrieving the data: $e</pre>";
    } finally {
        $link->disconnect();
    }
} else include '../inc/listAllLookups.php';

// then render the template with appropriate variables
/* !! navbar only has two possible states
**    should rely on the nav template for which state is shown
**    and pass it only a loggedIn/notLoggiedIn param
*/
$template->display($contextVars);
