<?php
require_once '../vendor/autoload.php';

session_start();

$loader = new Twig_Loader_Filesystem('../templates');
$twig = new Twig_Environment($loader,
    array(
        'debug' => true
    )
);

// load template into new TemplateWrapper
$template = $twig->load('page.html');

// process some data here...

// then render the template with appropriate variables
$template->display(array(
    'title' => 'Asset list',
    'navbarHeading' => $_SESSION['Username'],
    'navItems' => array(
        'Home' => 'account.php',
        'Asset List' => 'assets.php',
        'Deficiencies' => 'defs.php',
        'Daily Report' => 'idr.php',
        'Help' => 'help.php',
        'Logout' => 'logout.php'
    ),
    'pageHeading' => 'Assets',
));
