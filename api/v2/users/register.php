<?php
/**
 * Name register.php
 * Stellt das Registrierungsferfahren bereit.
 * 
 * @author KeksGauner
 * @version 2.0
 * 
 * Benutzen Sie diesen Code für das Registrierung:
 * Sende ein AJAX Request zum Server
 * 
 * POST: mit username password email
 * In PHP (Javascript) - $url = "/api/{version}/users/register"
 * Ohne Webbrowser - $url = "{server}/api/{version}/users/register"
 * 
 * Timestamp: 2020-05-18
 */

use ejfrancis\BruteForceBlock;
use Chat\Users;

// Request methode get blockieren
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Gebe eine Fehlermeldung, dass GET nicht erlaubt ist
    http_response_code(406);
    die(json_encode(array(
        'status'=>'failure',				
        'message' => 'GET is not Allowed',
        'code' => '406',
    ), JSON_PRETTY_PRINT));

}

// Erstellen Sie eine Instanz der BruteForceBlock-Klasse ob ein Registrierungsversuch möglich ist
$BFBresponse = BruteForceBlock::getRegisterRequestStatus();
// Switch-Anweisung zur Abfrage des Register Request Status
switch ($BFBresponse['status']){
    // Falls es sicher ist
    case 'safe':
        // Der liste hinzufügen, dass ein Registrierungsversuch statt gefunden hat
        $BFBresponse = BruteForceBlock::addRegisterRequestAttempt(BruteForceBlock::GetRealUserIp());
        // Abfrage ob username und password gesendet wurden
        if (!(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']))) {
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
        $email = $_POST['email'] ?? null;

        // Prüfen ob der Username den richrlinien entspricht
        if(!Users::verifyUsername($username)) {
            // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
            http_response_code(202);
            die(json_encode(array(
                'status'=>'failure',			
                'message' => 'Username is invalid',	
                'code' => '4',
            ), JSON_PRETTY_PRINT));
        }

        // Prüfen ob die E-Mail Adresse den richrlinien entspricht
        if(!Users::verifyEmail($email)) {
            // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
            http_response_code(202);
            die(json_encode(array(
                'status'=>'failure',			
                'message' => 'E-Mail is invalid',	
                'code' => '5',
            ), JSON_PRETTY_PRINT));
        }

        // Prüfen ob das Passwort den richrlinien entspricht
        if(!verifyPassword($password)) {
            // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
            http_response_code(202);
            die(json_encode(array(
                'status'=>'failure',			
                'message' => 'Password is invalid',	
                'code' => '6',
            ), JSON_PRETTY_PRINT));
        }

        // Abfragen ob die E-Mail nicht existiert, wird die Benutzername Prüfung durchgeführt
        if(Users::existEmail($username)) {
            // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
            http_response_code(202);
            die(json_encode(array(
                'status'=>'failure',	
                'message' => 'Email already exists',	
                'code' => '10_005',
            ), JSON_PRETTY_PRINT));
        }

        // Abfragen ob der Benutzer nicht existiert, kann der registrierungsfortschritt fortgesetzt werden
        if(Users::existUsername($username)) {
            // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
            http_response_code(202);
            die(json_encode(array(
                'status'=>'failure',	
                'message' => 'Username already exists',	
                'code' => '10_005',
            ), JSON_PRETTY_PRINT));
        }
        
        // Danach wird der user angelegt
        $users->addUser($username, $password, $email);
                            
        // Erfolgreich registriert
        http_response_code(200);
        die(json_encode(array
            (
                'status'=>'succes',		
                'message' => 'Successfully registered',	
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
        //Erforderliche Zeitspanne bis zur nächsten Registrierung
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