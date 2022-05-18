<?php
/**
 * Name app-route.php
 * Das hier stellt die Standard-Routen für die App bereit.
 */

 use codewithmark\SimpleDB;

// MySQL Verbindung
function app_db ()
{
    require_once ROOTPATH . '/protected/config.php';

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

$router->map('GET',  '/', function() { require ROOTPATH . '/public/dashboard/index.html'; } ,'home');
$router->map('GET',  '/impressum', function() { require ROOTPATH . '/public/dashboard/impressum.html'; } ,'impressum');

// Hier werden die css/js/map resources freigeschaltet
/**
 * Das hier gibt dynamisch die css/js/map resources frei.
 * /resources/<ordnernamen>/<filenamen>
 */
$router->map( 'GET', '/resources/[a:where]/[*:datei]', function( $where, $datei ) {
    // Setze ggf. den richtigen header
    if(str_ends_with($datei, '.css')) 
        header("Content-Type: text/css");
    if(str_ends_with($datei, '.js')) 
        header("Content-Type: application/javascript");
    // Gebe den Inhalt der Datei aus
    echo file_get_contents(ROOTPATH . '/public/dashboard/resources/' . $where . '/' . $datei);
});

// ****************APIs****************

// Register
$router->map('POST|GET', AJAXPATH . '/users/register', function() { 
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    require_once ROOTPATH . AJAXPATH . '/users/register.php'; 
}, 'register');

// Login
$router->map('POST|GET', AJAXPATH . '/users/login', function() { 
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    require_once ROOTPATH . AJAXPATH . '/users/login.php'; 
}, 'login');

// Logout
$router->map('POST|GET', AJAXPATH . '/users/logout', function() { 
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    require_once ROOTPATH . AJAXPATH . '/users/logout.php'; 
}, 'logout');

$router->map('POST|GET', AJAXPATH . '/users/account/[a:type]/[*:data]', function( $type, $data ) {
    // Rückgabe erfolgt nur als json
    header('Content-type: application/json');
    require_once ROOTPATH . AJAXPATH . '/users/account.php'; 
});

?>
