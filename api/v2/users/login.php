<?php
/**
 * Name logiin.php
 * Stellt das Loginferfahren bereit.
 */

use ejfrancis\BruteForceBlock;
use Chat\Users;

// Erstellen Sie eine Instanz der BruteForceBlock-Klasse ob ein Loginversuch möglich ist
$BFBresponse = BruteForceBlock::getLoginStatus();
// Switch-Anweisung zur Abfrage des Login-Status
switch ($BFBresponse['status']){
    // Falls es sicher ist
    case 'safe':
        // Abfrage ob username und password gesendet wurden
        if (!(isset($_POST['username']) && isset($_POST['password']))) {
            // Falls nicht gebe eine Fehlermeldung zurück
            http_response_code(202);
            die(json_encode(array(
			    'status'=>'failure',				
			    'message' => 'Field is missing',
			    'code' => '1',
		    ), JSON_PRETTY_PRINT));
        }
        
        // Die Eingaben werden in variablen gespeichert
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $token = $_COOKIE['chat_token'] ?? null;
            
        // Abfrage ob der Benutzer existiert, wird die Passwortprüfung durchgeführt
        if(!(Users::existEmail($username) || Users::existUsername($username))) {
            // Falls nicht gebe eine Fehlermeldung zurück
            http_response_code(202);
            die(json_encode(array(
                'status'=>'failure',				
                'message' => 'Field is missing',
                'code' => '1',
            ), JSON_PRETTY_PRINT));
        }

        // Prüfen ob das Passwort falsch ist
        if(!Users::checkPassword($username, $password)) {
            // Falls der Einloggversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
            http_response_code(202);
            // Der liste hinzufügen, dass ein Falscher Loginversuch statt gefunden hat
            $BFBresponse = BruteForceBlock::addFailedLoginAttempt($users->useridOf($username), BruteForceBlock::GetRealUserIp());
            die(json_encode(array(
                'status'=>'failure',			
                'message' => ' Password is invalid',	
                'code' => '3',
            ), JSON_PRETTY_PRINT)); 
        }
        
        // Prüfen ob schon ein gültiger Token existiert
        if(Users::verifyToken($token, false)) { 
            // Already logged in
            http_response_code(200);
            die(json_encode(array
            (
                'status'=>'failure',		
                'message' => 'Already logged in',	
                'code' => '7',
            ), JSON_PRETTY_PRINT));
        }

        // Login erfolgreich, Cookie wird erstellt
        $token = Users::createToken(Users::useridOf($username), false);
        $expire = Users::getExpiredSeconds();
        setcookie('chat_token', $token, $expire, $_SERVER['HTTP_HOST']);
                            
        // Erfolgreich eingeloggt
        http_response_code(200);
        die(json_encode(array
            (
                'status'=>'succes',		
                'message' => 'Logged in succesfully',	
                'token' => "$token",		
                'expire' => "$expire",	
                'code' => '201',
            ), JSON_PRETTY_PRINT));
    case 'error':
        //Fehler ist aufgetreten. Meldung zurückgeben
        $error_message = $BFBresponse['message'];
        // Allgemeiner Fehler
        http_response_code(500);
        die(json_encode(array(
            'status'=>'failure',			
            'message' => $error_message,
            'code' => '500',
        ), JSON_PRETTY_PRINT));
    case 'delay':
        //Erforderliche Zeitspanne bis zur nächsten Anmeldung
        $remaining_delay_in_seconds = $BFBresponse['message'];
        http_response_code(203);
        die(json_encode(array(
            'status'=>'failure',			
            'message' => 'Request Blocked',	
            'code' => '203',
            'delay' => $remaining_delay_in_seconds,
        ), JSON_PRETTY_PRINT));
    case 'captcha':
        //Captcha required
        http_response_code(203);
        die(json_encode(array(
		    'status'=>'failure',			
		    'message' => 'Captcha required',		
		    'code' => '203',
		), JSON_PRETTY_PRINT));
    default:
}
?>