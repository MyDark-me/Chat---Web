<?php
use ejfrancis\BruteForceBlock;
// Erstellen Sie eine Instanz der BruteForceBlock-Klasse
$BFBresponse = BruteForceBlock::getRegisterRequestStatus();
// Switch-Anweisung zur Abfrage des Login-Status
    switch ($BFBresponse['status']){
        // Falls es sicher ist
        case 'safe':
            // Abfrage ob username und password gesendet wurden
            if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
                // Falls ja, wird die Sql-Connection aufgebaut
                $db = app_db(); 
                // Zuerst mit SimpleDBClass werden die Eingaben geprüft
                $username = $db->CleanDBData($_POST['username']);
                $password = $db->CleanDBData($_POST['password']);
                $email = $db->CleanDBData($_POST['email']);

                // Abfrage ob die E-Mail existiert
                $feedback = json_decode(file_get_contents(AJAX_URL . '/users/account/email/' . $data));
                // Wenn der die E-Mail nicht existiert, wird die Benutzername Prüfung durchgeführt
                if(!$feedback->found) {
                    // Abfrage ob der Benutzer existiert
                    $feedback = json_decode(file_get_contents(AJAX_URL . '/users/account/username/' . $data));
                    // Wenn der Benutzer nicht existiert, kann der registrierungsfortschritt fortgesetzt werden
                    if(!$feedback->found) {
                        // Danach wird der user angelegt
                        $db->query("INSERT INTO `Nutzerdatenbank` (`Username`, `Password`, `Email`) VALUES ('$username', '$password', '$email');");
                            
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
                        $BFBresponse = BruteForceBlock::addRegisterRequestAttempt($username, GetRealUserIp());
                        die(json_encode($feedback, JSON_PRETTY_PRINT));
                    }
                } else {
                    // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
                    http_response_code(202);
                    $BFBresponse = BruteForceBlock::addRegisterRequestAttempt($username, GetRealUserIp());
                    die(json_encode($feedback, JSON_PRETTY_PRINT));
                }
            } else {
                // Falls nicht gebe eine Fehlermeldung zurück
                http_response_code(202);
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
        default:
    }
?>