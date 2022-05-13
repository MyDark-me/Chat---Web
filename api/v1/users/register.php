<?php
/**
 * Name register.php
 * Stellt das Registrierungsferfahren bereit.
 */

include_once ROOTPATH . AJAXPATH . '/users/users.php';

// Wir benutzen User Class um nutzerabfragen zu ermöglichen
$users = new Users();
use ejfrancis\BruteForceBlock;
// Erstellen Sie eine Instanz der BruteForceBlock-Klasse ob ein Registrierungsversuch möglich ist
$BFBresponse = BruteForceBlock::getRegisterRequestStatus();
// Switch-Anweisung zur Abfrage des Register Request Status
switch ($BFBresponse['status']){
    // Falls es sicher ist
    case 'safe':
        // Abfrage ob username und password gesendet wurden
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
            // Die Eingaben werden in variablen gespeichert
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];

            // Abfragen ob die E-Mail nicht existiert, wird die Benutzername Prüfung durchgeführt
            if(!$users->emailCount($email) != 0) {
                // Abfragen ob der Benutzer nicht existiert, kann der registrierungsfortschritt fortgesetzt werden
                if(!$users->usernameCount($data) != 0) {
                    // Danach wird der user angelegt
                    $users->addUser($username, $password, $email);
                            
                    // Erfolgreich registriert
                    http_response_code(200);
                    $BFBresponse = BruteForceBlock::addRegisterRequestAttempt(GetRealUserIp());
                    die(json_encode(array
                    (
                        'status'=>'succes',		
                        'message' => 'Successfully registered',	
                        'code' => '201',
                    ), JSON_PRETTY_PRINT));

                } else {
                    // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
                    http_response_code(202);
                    // Der liste hinzufügen, dass ein Registrierungsversuch statt gefunden hat
                    $BFBresponse = BruteForceBlock::addRegisterRequestAttempt($username, GetRealUserIp());
                    die(json_encode($feedback, JSON_PRETTY_PRINT));
                }
            } else {
                // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
                http_response_code(202);
                // Der liste hinzufügen, dass ein Registrierungsversuch statt gefunden hat
                $BFBresponse = BruteForceBlock::addRegisterRequestAttempt($username, GetRealUserIp());
                die(json_encode($feedback, JSON_PRETTY_PRINT));
            }
        } else {
            // Falls nicht gebe eine Fehlermeldung zurück
            http_response_code(202);
            // Der liste hinzufügen, dass ein Registrierungsversuch statt gefunden hat
            $BFBresponse = BruteForceBlock::addRegisterRequestAttempt(BruteForceBlock::GetRealUserIp());
            die(json_encode(array(
			    'status'=>'failure',			
			    'message' => 'Field is missing',	
			    'code' => '1',
		        ), JSON_PRETTY_PRINT));
        }
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
    }
?>