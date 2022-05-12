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

// ****************Weiterleitung zur richtigen Seite****************

$router->map('GET','/', function() { require ROOTPATH .'/public/dashboard/index.html'; } ,'home');
//$router->map('GET','/datenschutz','datenschutz.php','datenschutz');
//$router->map('GET','/impressum','impressum.php','impressum');


// Hier werden die css/js/map resources freigeschaltet
$router->map( 'GET', '/resources/[a:where]/[a:datei]', function( $where, $datei ) {
    // Setze den richtigen header
    if(str_ends_with($datei, '.css'))
        header("Content-Type: text/css");
    if(str_ends_with($datei, '.js'))
        header("Content-Type: text/javascript");
    // Gebe den Inhalt der Datei aus
    echo file_get_contents(ROOTPATH.'/public/dashboard/resources' . $where . '/' . $datei);
});

// ****************APIs****************

// Register
$router->map('POST|GET', '/api/v1/register', function() {
    require ROOTPATH.'/api/v1/register.php';
});

// Login
$router->map('POST|GET', '/api/v1/login', function() {
    require ROOTPATH.'/api/v1/login.php';
});


?>
