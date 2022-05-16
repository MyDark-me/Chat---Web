<?php
/**
 * Name logout.php
 * Loggt den Benutzer aus
 */
// Cookie löschen
$_COOKIE['chat_token'] = "";
if(isset($_COOKIE['chat_token'])) {
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
} else {
    // Erfolgreich ausgeloggt
    http_response_code(200);
    die(json_encode(array
    (
        'status'=>'success',		
        'message' => 'Logged out succesfully',	
        'code' => '200',
    ), JSON_PRETTY_PRINT));
}

?>