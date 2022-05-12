<?php

// Importieren Sie die erforderlichen Klassen
require_once ROOTPATH . '/routes/lib/BruteForceBlock.php';
use ejfrancis\BruteForceBlock;

// Erstellen Sie eine Custom-Exception-Klasse für das Delay
class LoginDelayExeption extends Exception {
    public function getDelay() {
      // Rückgabe der Fehlermeldung "Delay"
      $errorMsg = $this->getMessage();
      return $errorMsg;
    }
}

try {
    // Erstellen Sie eine Instanz der BruteForceBlock-Klasse
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
		                )));
                    } else {
                        // Falls der Einloggversuch fehlschlägt, wird eine Fellernmeldung ausgegeben
                        $BFBresponse = BruteForceBlock::addFailedLoginAttempt($username, GetRealUserIp());
                        die(json_encode(array(
			                'status'=>'failure',			
			                'message' => 'Incorrect password',
		                )));
                    }
                } else
                // Falls nicht gebe eine Fehlermeldung zurück
                die(json_encode(array(
			        'status'=>'failure',			
			        'message' => 'Incorrect inputs',
		            )));

                break;
            case 'error':
                //Fehler ist aufgetreten. Meldung zurückgeben
                $error_message = $BFBresponse['message'];
                throw new Exeption($error_message);
            case 'delay':
                //Erforderliche Zeitspanne bis zur nächsten Anmeldung
                $remaining_delay_in_seconds = $BFBresponse['message'];
                throw new LoginDelayExeption($remaining_delay_in_seconds);
            case 'captcha':
                //Captcha required
                die(json_encode(array(
			        'status'=>'failure',			
			        'message' => 'lockedout',
		            )));
        }
} catch (LoginDelayExeption $e) {
    die(json_encode(array(
		'status'=>'failure',			
		'message' => 'delay',
		'delay' => $e->getDelay(),
		)));
} catch (Exeption $e) {
    // Allgemeiner Fehler
    die(json_encode(array(
		'status'=>'failure',			
		'message' => 'error',
		'code' => $e->getTraceAsString(),
		)));
}

/**
 * Get real user ip
 *
 * Usage sample:
 * GetRealUserIp();
 * GetRealUserIp('ERROR',FILTER_FLAG_NO_RES_RANGE);
 *
 * @param string|null $default default return value if no valid ip found
 * @param int $filter_options filter options. default is FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
 *
 * @return string real user ip
 */
function GetRealUserIp()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

?>