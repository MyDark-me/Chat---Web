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
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Die Eingaben werden in variablen gespeichert
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;
            $token = $_COOKIE['chat_token'] ?? null;
            
            // Abfrage ob der Benutzer existiert, wird die Passwortprüfung durchgeführt
            if(Users::existEmail($username) || Users::existUsername($username)) {
                if(Users::checkPassword($username, $password)) {
                    if(!empty($token) && Users::verifyToken($token)) { 
                        // Already logged in
                        http_response_code(200);
                        die(json_encode(array
                        (
                            'status'=>'failure',		
                            'message' => 'Already logged in',	
                            'code' => '7',
                        ), JSON_PRETTY_PRINT));
                    }

                    // Dauer des Tokens in Sekunden das 7 Tage sind
                    $expired_seconds = time() + 60 * 60 * 24 * 7;
                    // Login erfolgreich, Cookie wird erstellt
                    setcookie('chat_token', Users::createToken($username), $expired_seconds);
                            
                    // Erfolgreich eingeloggt
                    http_response_code(200);
                    die(json_encode(array
                    (
                        'status'=>'succes',		
                        'message' => 'Logged in succesfully',	
                        'code' => '201',
                    ), JSON_PRETTY_PRINT));
                } else {
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
            } else {
                // Falls der Einloggversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
                http_response_code(202);
                // Der liste hinzufügen, dass ein Falscher Loginversuch statt gefunden hat
                $BFBresponse = BruteForceBlock::addFailedLoginAttempt($users->useridOf($username), BruteForceBlock::GetRealUserIp());
                die(json_encode(array(
			        'status'=>'failure',			
			        'message' => ' Username or Email address is invalid',	
                    'code' => '2',
		        ), JSON_PRETTY_PRINT));
            }
        } else {
            // Falls nicht gebe eine Fehlermeldung zurück
            http_response_code(202);
            die(json_encode(array(
			    'status'=>'failure',				
			    'message' => 'Field is missing',
			    'code' => '1',
		    ), JSON_PRETTY_PRINT));
        }
        break;
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