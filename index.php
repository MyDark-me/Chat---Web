<?php

//********* APP URL *********

// Fragt ab ob der Server auf https oder http läuft
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $ssl = 'https';
}
else {
  $ssl = 'http';
}

// Holt sich den pfart des Servers
$app_url = ($ssl  )
          . "://".$_SERVER['HTTP_HOST']
          . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
          . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");

//********* GLOBAL DEFINES *********

// Macht app_url global sichtbar
define("APPURL", $app_url);
// Speichert die AJAX_URL global
define("AJAX_URL", $app_url.'/api');
// Absoluter Pfad zum Stammverzeichnis der Anwendung
define("ROOTPATH", str_replace("\\", "/",  dirname(__FILE__) ));

//********* GLOBAL INCLUDES *********

// libs
include_once ROOTPATH. '/routes/lib/AltoRouter.php'; 
include_once ROOTPATH. '/routes/lib/SimpleDBClass.php'; 

//********* Alto Router *********

//Inizialisiert die Alto Router library
$router = new AltoRouter();
$base_path = trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");
$router->setBasePath($base_path ? "/".$base_path : "");

//********* REQUEST MANAGEMENT *********
//Einbindung der Request Management Class
include_once ROOTPATH. '/routes/app-route.php';

//Holt sich die aktuelle abgefragte URL
$match = $router->match();


//Ruft näher oder wirft ein 404-Status
if( $match && is_callable( $match['target'] ) ) 
{    
    call_user_func_array( $match['target'], array_values($match['params'] )); 
} 
else 
{
  //Es wurde keine Route gefunden
  $app_url_asset = APPURL;
  http_response_code(404);
  header("HTTP/1.1 404 Not Found", TRUE);
  die('
  <!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
  <html>
    <head>
        <title>404 Not Found</title>
    </head>
    <body>
        <h1>Not Found</h1>
        <p>The requested URL '. htmlspecialchars($_SERVER['REQUEST_URI']) .' was not found on this server.</p>
    </body>
  </html>
  ');
}
?>