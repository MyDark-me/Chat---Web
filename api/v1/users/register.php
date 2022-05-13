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
                    // Falls ja, wird eine Sql-Abfrage ausgeführt
                    $db = app_db(); 
                    // Zuerst mit SimpleDBClass werden die Eingaben geprüft
                    $username = $db->CleanDBData($_POST['username']);
                    $password = $db->CleanDBData($_POST['password']);
                    $email = $db->CleanDBData($_POST['email']);

                    // Abfrage ob die E-Mail existiert
                    $result = $db->query("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Email`= '$email';");
                    $row = $result->fetch_assoc();
                    // Wenn der die E-Mail nicht existiert, wird die Benutzername Prüfung durchgeführt
                    if($result != false && mysqli_num_rows($result) == 0) {
                        // Abfrage ob der Benutzer existiert
                        $result = $db->query("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Username`= '$username';");
                        $row = $result->fetch_assoc();
                        // Wenn der Benutzer nicht existiert, kann der registrierungsfortschritt fortgesetzt werden
                        if($result != false && mysqli_num_rows($result) == 0) {
                            // Danach wird der user angelegt
                            $db->query("INSERT INTO `Nutzerdatenbank` (`Username`, `Password`, `Email`) VALUES ('$username', '$password', '$email');");
                            
                            // Erfolgreich registriert
                            $BFBresponse = BruteForceBlock::addRegisterRequestAttempt(GetRealUserIp());
                            die(json_encode(array
                            (
                                'status'=>'succes',		
                                'message' => 'Successfully registered',	
                                'code' => '0',
                            ), JSON_PRETTY_PRINT));

                        } else {
                            // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
                            $BFBresponse = BruteForceBlock::addRegisterRequestAttempt($username, GetRealUserIp());
                            die(json_encode(array(
                                'status'=>'failure',			
                                'message' => 'Username already exists',	
                                'code' => '0',
                            ), JSON_PRETTY_PRINT));
                        }
                    } else {
                        // Falls der Registrierungsversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
                        $BFBresponse = BruteForceBlock::addRegisterRequestAttempt($username, GetRealUserIp());
                        die(json_encode(array(
                            'status'=>'failure',			
                            'message' => 'Email already exists',	
                            'code' => '0',
                        ), JSON_PRETTY_PRINT));
                    }
                } else
                // Falls nicht gebe eine Fehlermeldung zurück
                $BFBresponse = BruteForceBlock::addRegisterRequestAttempt(BruteForceBlock::GetRealUserIp());
                die(json_encode(array(
			        'status'=>'failure',			
			        'message' => 'Incorrect inputs',	
			        'code' => '0',
		            ), JSON_PRETTY_PRINT));

                break;
            case 'error':
                //Fehler ist aufgetreten. Meldung zurückgeben
                $error_message = $BFBresponse['message'];
                // Allgemeiner Fehler
                die(json_encode(array(
                    'status'=>'failure',			
                    'message' => 'error',
                    'code' => $error_message,
                    ), JSON_PRETTY_PRINT));
            case 'delay':
                //Erforderliche Zeitspanne bis zur nächsten Registrierung
                $remaining_delay_in_seconds = $BFBresponse['message'];
                die(json_encode(array(
                    'status'=>'failure',			
                    'message' => 'delay',	
                    'code' => '0',
                    'delay' => $remaining_delay_in_seconds,
                    ), JSON_PRETTY_PRINT));
            case 'captcha':
                //Captcha required
                die(json_encode(array(
			        'status'=>'failure',			
			        'message' => 'lockedout',		
			        'code' => '0',
		            ), JSON_PRETTY_PRINT));
        }
?>