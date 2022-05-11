<?php

// Importieren Sie die erforderlichen Klassen
require_once(ROOTPATH . '/routes/lib/BruteForceBlock.php');
use ejfrancis\BruteForceBlock;

// Erstellen Sie eine Costom-Exception-Klasse für das Delay
class LoginDelayExeption extends Exception {
    public function getDelay() {
      // Rückgabe der Fehlermeldung "Delay"
      $errorMsg = $this->getMessage();
      return $errorMsg;
    }
}

try {
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

                    // TODO:
                    // Es wird geprüft ob der Benutzer existiert
                    //$result = $db->query("");
                    // nach der if abfrage
                    // Danach wird der user angelegt
                    //$result = $db->query("");
                    
                    // Erfolgreich registriert
                    $BFBresponse = BruteForceBlock::addRegisterRequestAttempt(GetRealUserIp());
                    die(json_encode(array
		            (
			            'status'=>'succes',		
			            'message' => 'Successfully registered',
		            )));
                } else
                // Falls nicht gebe eine Fehlermeldung zurück
                $BFBresponse = BruteForceBlock::addRegisterRequestAttempt(GetRealUserIp());
                die(json_encode(array(
			        'status'=>'failure',			
			        'message' => 'Incorrect inputs',
			        'code' => 'Wrong Inputs',
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