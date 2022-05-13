<?php
/**
 * Name app-route.php
 * Das hier stellt die Standard-Routen für die App bereit.
 */

// MySQL Verbindung
function app_db ()
{
    require_once ROOTPATH.'/protected/config.php';

    $db_conn = array(
        'host' => DB_HOST, 
        'user' => DB_USER,
        'pass' => DB_PASSWORD,
        'database' => DB_NAME, 
    );
    // Wir benutzen SimpleDBClass um die Datenbank zu benutzen
    $db = new SimpleDBClass($db_conn);

    return $db;     
}

// ****************Weiterleitung zur richtigen Seite****************

$router->map('GET','/', function() { require ROOTPATH .'/public/dashboard/index.html'; } ,'home');
$router->map('GET','/register', function() { require ROOTPATH .'/public/dashboard/register.html'; } ,'register');
$router->map('GET','/login', function() { require ROOTPATH .'/public/dashboard/login.html'; } ,'login');
$router->map('GET','/logout', function() { 
    session_destroy();
    session_unset();
    require ROOTPATH .'/public/dashboard/logout.html'; 
} ,'logout');

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
$router->map('POST', AJAXPATH . '/users/register', function() { 
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    require_once ROOTPATH . AJAXPATH . '/users/register.php'; 
} ,'register');

// Login
$router->map('POST', AJAXPATH . '/users/login', function() { 
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    $users->cookieAutoLogin();
    require_once ROOTPATH . AJAXPATH . '/users/login.php'; 
} ,'login');

$router->map('POST|GET', AJAXPATH . '/users/account/[a:type]/[*:data]', function( $type, $data ) {
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    require_once ROOTPATH . AJAXPATH . '/users/account.php'; 
}, 'account');

?>
