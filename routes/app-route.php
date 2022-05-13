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

// Hier werden die css/js/map resources freigeschaltet
$router->map( 'GET', '/resources/[a:where]/[*:datei]', function( $where, $datei ) {
    // Setze den richtigen header
    if(str_ends_with($datei, '.css')) 
        header("Content-Type: text/css");
    if(str_ends_with($datei, '.js')) 
        header("Content-Type: application/javascript");
    if(str_ends_with($datei, '.map')) 
        header("Content-Type: application/json");
    // Gebe den Inhalt der Datei aus
    echo file_get_contents(ROOTPATH.'/public/dashboard/resources/' . $where . '/' . $datei);
});

// ****************APIs****************

// Register
$router->map('POST|GET', APIPATH . '/users/register', function() { 
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    require_once ROOTPATH . '/routes/lib/BruteForceBlock.php';
    require_once ROOTPATH.'/api/v1/users/register.php'; 
} ,'register');

// Login
$router->map('POST|GET', APIPATH . '/users/login', function() { 
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    require_once ROOTPATH . '/routes/lib/BruteForceBlock.php';
    require_once ROOTPATH.'/api/v1/users/login.php'; 
} ,'login');

$router->map( 'POST|GET', APIPATH . '/users/account/[a:type]/[*:data]', function( $type, $data ) {
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    require_once ROOTPATH.'/api/v1/users/account.php'; 
}, 'account');

?>
