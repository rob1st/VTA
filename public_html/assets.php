<?php
require_once '../vendor/autoload.php';
require_once '../inc/sqlFunctions.php';
require_once '../inc/assetQryFcns.php';

session_start();

// instantiate objects
$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader,
    array(
        'debug' => true
    )
);

// base context
$context = [
    'navbarHeading' => $_SESSION['username'],
    'title' => 'Asset List',
    'pageHeading' => 'Assets',
    'tableName' => 'asset',
];

/* parse url to establish which view to display
** [0] => action of view, e.g., 'list', 'add'
** [1] => name of table to manage
*/
$pathinfo = !empty($_SERVER['PATH_INFO'])
    ? substr($_SERVER['PATH_INFO'], strpos($_SERVER['PATH_INFO'], '/') + 1)
    : '';
    
$pathParams = explode("/", $pathinfo);

// assign template name and sql string based on path params
// if path params invalid, use default 'list' template
$action = intval(array_search($pathParams[0], $actions, true))
    ? $pathParams[0]
    : 'list';

$template = $twig->load("$action.html");

$context['backto'] = $action !== 'list' ? 'assets.php' : '';
$context['meta'] = $action; // THIS IS FOR DEV'S INFO ONLY

// retrieve data from db
$context = array_merge(
    $context,
    getAssetData($action)
);

// then render the template with appropriate variables
$template->display($context);
