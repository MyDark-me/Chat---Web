<?php

// MySQL Verbindung
function app_db ()
{
    include_once ROOTPATH.'/protected/config.php';

    $db_conn = array(
        'host' => DB_HOST, 
        'user' => DB_USER,
        'pass' => DB_PASSWORD,
        'database' => DB_NAME, 
    );
    $db = new SimpleDBClass($db_conn);

    return $db;     
}

// Weiterleitung zur richtigen Seite

// homepage
$router->map('GET', '/', function() {
    require ROOTPATH.'/public/dashboard/index.html';
});

// CSS
$router->map('GET', '/resources/Bootstrap/bootstrap.css', function() {
    require ROOTPATH.'/public/dashboard/resources/Bootstrap/bootstrap.css';
});
$router->map('GET', '/resources/FontAwesome/all.css', function() {
    require ROOTPATH.'/public/dashboard/resources/FontAwesome/all.css';
});

// Scripts
$router->map('GET', '/resources/jQuery/jquery.js', function() {
    require ROOTPATH.'/public/dashboard/resources/jQuery/jquery.js';
});
$router->map('GET', '/resources/Popper/popper.min.js', function() {
    require ROOTPATH.'/public/dashboard/resources/Popper/popper.min.js';
});
$router->map('GET', '/resources/Bootstrap/bootstrap.bundle.js', function() {
    require ROOTPATH.'/public/dashboard/resources/Bootstrap/bootstrap.bundle.js';
});
$router->map('GET', '/resources/FontAwesome/all.js', function() {
    require ROOTPATH.'/public/dashboard/resources/FontAwesome/all.js';
});

// APIs

// Register
$router->map('POST|GET', '/api/v1/register', function() {
    require ROOTPATH.'/api/v1/register.php';
});

// Login
$router->map('POST|GET', '/api/v1/login', function() {
    require ROOTPATH.'/api/v1/login.php';
});


?>
