<?php
/**
 * Name logout.php
 * Loggt den Benutzer aus
 */

// Bekomme Request methode
$request = null;
switch($_SERVER['REQUEST_METHOD'])
{
    case 'GET': $request = &$_GET; break;
    case 'POST': $request = &$_POST; break;

default:
}

$token = $_COOKIE['chat_token'] ?? $request['token'] ?? null;

// Abfrage ob der Token gültig ist
if(!Users::verifyToken($token, true)) {
    http_response_code(401);
    die(json_encode(array(
        'status'=>'failure',	
        'message' => 'Token Invalid',	
        'code' => '401',
    ), JSON_PRETTY_PRINT));
}

// Token in der Datenbank ungültig machen
if(!emty($token)) {
    $db = app_db();
    $db->query("UPDATE `Tokendatenbank` SET `Expiration`= '-1' WHERE `Token`= '$token';");
}

// Cookie löschen
if(isset($_COOKIE['chat_token'])) {
    
    setcookie('chat_token', null, -1, $_SERVER['HTTP_HOST']); 
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