<?php
require_once '../vendor/autoload.php';
require_once '../inc/sqlFunctions.php';
require_once '../routes/assetRoutes.php';

session_start();

echo "<h1 style='font-size: 5rem; font-family: monospace; color: red'>Hey</h1>";

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
** [0] => route for view, e.g., 'list', 'add', 'update', 'view'
** [1] => name of table to query
*/
$pathinfo = !empty($_SERVER['PATH_INFO'])
    ? $_SERVER['PATH_INFO']
    : '';
    
$pathParams = explode("/", $pathinfo);

// assign template name and sql string based on path params
// if path params invalid, use default 'table' template
$route = intval(array_search($pathParams[0], $routes, true))
    ? $pathParams[0]
    : 'table';

$template = $twig->load("$route.html");

// if it's not the list view, show a back button | list view gets no back button
$context['backto'] = $route !== 'table' ? 'assets.php' : '';
$context['meta'] = $route; // THIS IS FOR DEV'S INFO ONLY

// retrieve data from db
$context = array_merge(
    $context,
    getAssetData($route)
);

// then render the template with appropriate variables
// $template->display($context);