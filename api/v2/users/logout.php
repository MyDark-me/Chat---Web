<?php
/**
 * Name logout.php
 * Loggt den Benutzer aus indem der Token ungültig gesetzt wird.
 * 
 * @author KeksGauner
 * @version 2.0
 * 
 * Benutzen Sie diesen Code zum Ausloggen:
 * Sende ein AJAX Request zum Server
 * 
 * POST: mit token
 * In PHP (Javascript) - $url = "/api/{version}/users/logout"
 * Ohne Webbrowser - $url = "{server}/api/{version}/users/logout"
 * 
 * GET:

 * In PHP (Javascript) - $url = "/api/{version}/users/logout?token={token}"
 * Ohne Webbrowser - $url = "{server}/api/{version}/users/logout?token={token}"
 * 
 * Timestamp: 2020-05-18
 */

use Chat\Users;

// Bekomme Request methode
$request = null;
switch($_SERVER['REQUEST_METHOD'])
{
    case 'GET': $request = &$_GET; break;
    case 'POST': $request = &$_POST; break;

default:
}

$token = $_COOKIE['chat_token'] ?? $request['token'] ?? null;

// Token in der Datenbank ungültig machen
if(!empty($token)) {
    $db = app_db();
    
    // Abfrage ob der Token existiert
    $result = $db->select("SELECT `ID` FROM `Tokendatenbank` WHERE `Token`= '$token';");
    // Wenn er Existiert dann ungültig machen
    if($result != false && $result != 0)
        $db->query("UPDATE `Tokendatenbank` SET `Expiration`= '-1' WHERE `Token`= '$token';");
}

// Cookie löschen
if(isset($_COOKIE['chat_token'])) {
    
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
    $cookie_options = array (
        'expires' => -1,
        'path' => '/',
        'domain' => $domain . "", // führender Punkt für Kompatibilität oder Subdomain verwenden
        'secure' => true,     // or false
        'httponly' => true,    // or false
        'samesite' => 'Strict' // None || Lax  || Strict
        );

    setcookie('chat_token', null, $cookie_options); 
    unset($_COOKIE['chat_token']);
}

// Validieren ob der Cookie gelöscht wurde
if(isset($_COOKIE['chat_token'])) {
    // Feler beim Löschen des Cookies
    http_response_code(202);
    die(json_encode(array(
        'status'=>'failure',			
        'message' => 'Could not delete cookie',	
        'code' => '202',
    ), JSON_PRETTY_PRINT));
}
// Erfolgreich ausgeloggt
http_response_code(200);
die(json_encode(array
(
    'status'=>'success',		
    'message' => 'Logged out succesfully',	
    'code' => '200',
), JSON_PRETTY_PRINT));

?>