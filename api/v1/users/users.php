<?php
/**
 * Name users.php
 * Das hier stellt die Datenbank user info abfragen bereit.
 * Verändert ein user Profil
 */
class Users {

    private static $salt = '1234_oder_so';

    public function __construct() {
    }

    /**
     * Zählt die Anzahl der Benutzer mit der angegebenen E-Mail Adresse
     *
     * @param string $email Die zu prüfende E-Mail Adresse
     */
    public function emailCount($email): int {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $email = $db->CleanDBData($email);
        // Abfrage der E-Mail anzahl
        $result = $db->select("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Email`= '$email';");
        // Rückgabe der Anzahl der E-Mail Adressen
        if($result != false)
            return $result;
        else
            return false;
    }

    /**
     * Zählt die Anzahl der Benutzer mit der angegebenen Benutzernamen
     *
     * @param string $username Der zu prüfende Benutzername
     */
    public function usernameCount($username): int {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $username = $db->CleanDBData($username);
        // Abfrage der E-Mail anzahl
        $result = $db->select("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Username`= '$username';");
        // Rückgabe wie viele user mit diesem username existieren
        if($result != false)
            return $result;
        else
            return false;
    }

    /**
     * Fügt ein neues Benutzerprofil hinzu
     *
     * @param string $username Wie der Benutzer heißt
     * @param string $email Wie die E-Mail heißt
     * @param string $password Welches Passwort genutzt wird
     */
    public function addUser($username, $password, $email) {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $username = $db->CleanDBData($username);
        $password = $db->CleanDBData($password);
        $email = $db->CleanDBData($email);
        // User hinzufügen
        $insert_arrays = array
        (
        'Username' => "$username",
        'Password' => "$password",
        'Email'=> "$email"
        );
        $db->Insert('Nutzerdatenbank',$insert_arrays);
    }

    /**
     * Prüft das Passwort mit dem in der Datenbank gespeicherten Passwort
     *
     * @param string $username Der zu prüfende Benutzername
     * @param string $password Das zu prüfende Passwort
     */
    public function checkPassword($username, $password): bool {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $username = $db->CleanDBData($username);
        $password = $db->CleanDBData($password);

        // Aus der Datenbank das Passwort abfragen entweder mit dem Benutzername oder mit der E-Mail
        $result = $db->query("SELECT `Password` FROM `Nutzerdatenbank` WHERE `Username`= '$username' OR `Email`= '$username';");
        $row = $result->fetch_assoc();
        
        // Prüfung ob das Passwort korrekt ist
        if(assword_verify($row['Password'], $password))
            return true;
        else
            return false;
    }

    /**
     * Speichert ein persönliches Thoken in den Cookies
     *
     * @param string $username Den Benutzernamen um den user zu identifizieren
     * @param string $remember Ob der Benutzer das Token merken möchte
     */
    public function setSession($username, $remember) {
        //  Irgendwie muss ein Token erstellt werden
        $token = bin2hex(random_bytes(32));
        // Muss in der Datenbank gespeichert werden
        $db = app_db();
        // SQL gegen injektion schützen
        $token = $db->CleanDBData($token);
        $username = $db->CleanDBData($username);
        // Token in der Datenbank speichern
        /*
        $insert_arrays = array
        (
        'Username' => "$username",
        'Token' => "$token"
        );
        $db->Insert('Nutzerdatenbank',$insert_arrays);
        */
        // Setze den Token in die Session
        $_SESSION['token'] = $token;
        $_SESSION['LAST_ACTIVITY'] = time();
        if ($remember == true) {
            // Token in den Cookies speichern
            $expired_seconds = time() + 60 * 60 * 24 * 7;
            setcookie('chat_token', md5($salt . $token), $expired_seconds);
        }
    }

    /**
     * Prüft ob der Benutzer länger als 30 Minuten nichts gemacht hat
     *
     */
    public function setUserLoginStatus() {
        // Bei keine Aktivität mehr als 30 minuten soll der nutzer automatisch ausgeloggt werden
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            // request was 30 minutes ago
            session_destroy();
            session_unset();
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // Aktualisiere die letzte Aktivität
        if (session_status() != 2 || !isset($_SESSION['token']) || $_SESSION['token'] == "") {
            // there is no session or the session is not set
            session_destroy();
            session_unset();
            // Header aktualisieren zu login
            header('Location: /login');
        }
    }
    /**
     * Prüft ob der Benutzer eingeloggt ist
     *
     */
    public function userLoggedIn() {
        if (isset($_SESSION['token'])) {
            header("Location: /");
        }
    }

    /**
     * Prüft ob automatisch einloggen möglich ist.
     * Falls ja, wird der Nutzer automatisch eingeloggt
     * 
     */
    public function cookieAutoLogin() {
        $username = null;
        if (isset($_COOKIE['chat_token'])) {
            // Validierung des Tokens
            // Aus der Datenbank alle funktionierenden Tokens abfragen
            /*
            $result = $db->query("SELECT `Token` FROM `Nutzerdatenbank` WHERE `Username`= '$username' OR `Email`= '$username';");
            $row = $result->fetch_assoc();
            foreach ($data as $row) {
                if (md5($salt . $row['token']) == $_COOKIE['chat_token']) {
                    $username = $row['username'];
                }
            }
            */
            if (!empty($username)) {
                $_SESSION['token'] = $token;
                $_SESSION['LAST_ACTIVITY'] = time();
                if ($remember == true) {
                    $expired_seconds = time() + 60 * 60 * 24 * 7;
                    setcookie('chat_token', md5($salt . $token), $expired_seconds);
                }
            }
        }
        // Header aktualisieren
        header('Location: /');
    }

    
}
?>