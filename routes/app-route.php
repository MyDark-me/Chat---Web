<?php

// MySQL connection erstellen
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

// Leite zur richtigen seite weiter
$router->map('GET', '/public', '/public/home.php','home');
$router->map('GET', '/public/login', '/public/login.php','login');
$router->map('GET', '/public/signup', '/public/signup.php','signup');

?>