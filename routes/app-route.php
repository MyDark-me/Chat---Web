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
    require ROOTPATH.'/public/home.php';
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
