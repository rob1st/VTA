<?php
require_once '../vendor/autoload.php';
require_once '../inc/sqlFunctions.php';

session_start();

// instantiate objects
$link = connect();

$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader,
    array(
        'debug' => true
    )
);

// load template into new TemplateWrapper
$template = $twig->load('list.html');

// grab data from db

// process some data here...

// then render the template with appropriate variables
$template->display(array(
    'title' => 'Asset list',
    'navbarHeading' => $_SESSION['Username'],
    'pageHeading' => 'Assets',
));
