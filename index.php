<?php

/**
 * Name index.php
 * Das hier stellt den Start der App bereit.
 */

//********* APP URL *********

// Überprüfen nach HTTPS / HTTP
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $ssl = 'https';
}
else {
  $ssl = 'http';
}

// Pfad abfragen und speichern
$app_url = ($ssl)
          . "://".$_SERVER['HTTP_HOST']
          . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
          . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");

//********* GLOBAL DEFINES *********

// app_url Global sichtbar stellen
define("APPURL", $app_url);
// Speichert die AJAX_URL Global
define("AJAX_URL", $app_url.'/api/v2');
// Rellativer Pfad zu der API
define("AJAXPATH", '/api/v2');
// Absoluter Pfad zum Stammverzeichnis der Anwendung
define("ROOTPATH", str_replace("\\", "/",  dirname(__FILE__) ));

//********* GLOBAL INCLUDES *********

// Manual Libs
require_once ROOTPATH . '/routes/lib/SimpleDBClass.php';
require_once ROOTPATH . '/routes/lib/BruteForceBlock.php';

require_once ROOTPATH . AJAXPATH . '/users/users.php';

// Import ReallySimpleJWT und AltoRouter
require 'vendor/autoload.php';

//********* Alto Router *********

//Initialisiert die Alto Router Library
// Wir benutzen AltoRouter um die URL zu parsen
$router = new AltoRouter();
$base_path = trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");
$router->setBasePath($base_path ? "/".$base_path : "");

//********* REQUEST MANAGEMENT *********
//Einbindung der Routes Management Class
require_once ROOTPATH . '/routes/app-route.php';

//Holt sich die aktuell abgefragte URL
$match = $router->match();

//Aufrufbaren Pfad verwenden oder 404-Status ausgeben
if( $match && is_callable( $match['target'] ) ) 
{    
    call_user_func_array( $match['target'], array_values($match['params'] )); 
} 
else 
{
  //Keine Route gefunden darum 404 Seite senden
  http_response_code(404);
  header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
  die(require ROOTPATH . '/public/error/404.php');
}
?>
