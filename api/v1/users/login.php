<?php

use ejfrancis\BruteForceBlock;

// Erstellen Sie eine Instanz der BruteForceBlock-Klasse ob ein Loginversuch möglich ist
$BFBresponse = BruteForceBlock::getLoginStatus();
// Switch-Anweisung zur Abfrage des Login-Status
switch ($BFBresponse['status']){
            // Falls es sicher ist
            case 'safe':
                // Abfrage ob username und password gesendet wurden
                if (isset($_POST['username']) && isset($_POST['password'])) {
                    // Falls ja, wird eine Sql-Abfrage ausgeführt
                    $db = app_db(); 
                    // Zuerst mit SimpleDBClass werden die Eingaben geprüft
                    $username = $db->CleanDBData($_POST['username']);
                    $password = $db->CleanDBData($_POST['password']);
                    // Abfrage ob der Benutzer existiert
                    $result = $db->query("SELECT `ID`, `Username`, `Password`, `UserGroup` FROM `Nutzerdatenbank` WHERE `Username`= '$username';");
                    $row = $result->fetch_assoc();
                    // Wenn der Benutzer existiert, wird die Passwortprüfung durchgeführt
                    if($result != false && mysqli_num_rows($result) != 0 && $password == $row['Password']) {
                        if (session_status() === PHP_SESSION_NONE) session_start();
                        // Session-Variablen werden gesetzt
                        /*
                        BITTE ÄNDERN!! DIESE DATEN SOLLEN ALS PHP CLASS MIT BLOWFISH ÜBERGEBEN WERDEN
                        */
                        $_SESSION['token'] = 'token';
                        // Erfolgreich eingeloggt
                        die(json_encode(array
		                (
			                'status'=>'succes',		
			                'message' => 'Logged in succesfully',	
                            'code' => '0',
		                ), JSON_PRETTY_PRINT));
                    } else {
                        // Falls der Einloggversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
                        $BFBresponse = BruteForceBlock::addFailedLoginAttempt($username, GetRealUserIp());
                        die(json_encode(array(
			                'status'=>'failure',			
			                'message' => 'Incorrect password',	
                            'code' => '0',
		                ), JSON_PRETTY_PRINT));
                    }
                } else
                // Falls nicht gebe eine Fehlermeldung zurück
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
                //Erforderliche Zeitspanne bis zur nächsten Anmeldung
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
		            )));
        }
