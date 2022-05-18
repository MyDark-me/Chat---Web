<?php
/**
 * Name logout.php
 * Loggt den Benutzer aus
 */
// Cookie löschen
if(isset($_COOKIE['chat_token'])) {
    $token = $_COOKIE['chat_token'] ?? null;
    // Token in der Datenbank ungültig machen
    if(!emty($token)) {
        $db = app_db();
        $db->query("UPDATE `Tokendatenbank` SET `Expiration`= '-1' WHERE `Token`= '$token';");
    }
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