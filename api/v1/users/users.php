<?php
/**
 * Name users.php
 * Das hier stellt die Datenbank user info abfragen bereit.
 * Verändert ein user Profil
 */

use ReallySimpleJWT\Token;

class Users {

    public function __construct() {
    }

    /**
     * Zählt die Anzahl der Benutzer mit der angegebenen E-Mail Adresse
     *
     * @param string $email Die zu prüfende E-Mail Adresse
     */
    public function emailCount($email) {
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
    public function usernameCount($username) {
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
        // Passwort verslüsselungsrichtlinien
        $options = [ "cost" => 15 ];
        // User hinzufügen
        $insert_arrays = array
        (
        'Username' => "$username",
        'Password' => password_hash("$password", PASSWORD_BCRYPT, $options),
        'Email'=> "$email"
        );
        $db->Insert('Nutzerdatenbank',$insert_arrays);
    }

    /**
     * Zählt die Anzahl der Benutzer mit der angegebenen Benutzernamen oder E-Mail Adresse
     *
     * @param string $username Der zu prüfende Benutzername oder E-Mail Adresse
     */
    public function validUserCount($username) {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $username = $db->CleanDBData($username);
        // Abfrage der E-Mail anzahl
        $result = $db->select("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Username`= '$username' OR `Email`= '$username';");
        // Rückgabe wie viele user mit diesem username existieren
        if($result != false)
            return $result;
        else
            return false;
    }

    /**
     * Prüft das Passwort mit dem in der Datenbank gespeicherten Passwort
     *
     * @param string $username Der zu prüfende Benutzername
     * @param string $password Das zu prüfende Passwort
     */
    public function checkPassword($username, $password) {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $username = $db->CleanDBData($username);
        $password = $db->CleanDBData($password);

        // Aus der Datenbank das Passwort abfragen entweder mit dem Benutzername oder mit der E-Mail
        $result = $db->query("SELECT `Password` FROM `Nutzerdatenbank` WHERE `Username`= '$username' OR `Email`= '$username';");
        $row = $result->fetch_assoc();
        
        // Prüfung ob das Passwort korrekt ist
        if(password_verify($password, $row['Password']))
            return true;
        else
            return false;
    }

    /**
     * Gibt von dem Username oder E-Mail Adresse falls möglich die ID zurück
     * Sonst bekommt man eine 0
     *
     * @param string $username Der zu prüfende Benutzername oder E-Mail Adresse
     */
    public function useridOf($username) {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $username = $db->CleanDBData($username);

        // Aus der Datenbank das Passwort abfragen entweder mit dem Benutzername oder mit der E-Mail
        $result = $db->query("SELECT `ID` FROM `Nutzerdatenbank` WHERE `Username`= '$username' OR `Email`= '$username';");
        $row = $result->fetch_assoc();
        
        // Rückgabe der User ID
        if(!empty($row['ID']))
            return $row['ID'];
        else
            return 0;
    }

    /**
     * Speichert ein persönliches Thoken in den Cookies
     *
     * @param string $username Den Benutzernamen um den user zu identifizieren
     * @param string $remember Ob der Benutzer das Token merken möchte
     */
    public function setSession($userId, $remember) {
        // Erstellt mit ReallySimpleJWT einen Token
        $expired_seconds = time() + 60 * 60 * 24 * 7;
        
        $issuer = 'localhost';

        $token = Token::create($userId, 'sec!ReT423*&', $expired_seconds, $issuer);
        // Setze den Token in die Session
        $_SESSION['token'] = $token;
        $_SESSION['LAST_ACTIVITY'] = time();
        if ($remember == true) {
            // Token in den Cookies speichern
            setcookie('chat_token', $token, $expired_seconds);
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
     * Prüft ob der Benutzer länger als 30 Minuten nichts gemacht hat
     *
     */
    public function logoutUser() {
        // Session löschen
        if(isset($_SESSION['token'])) $_SESSION['token'] == "";
        session_destroy();
        session_unset();
        // Cookies löschen
        if(isset($_COOKIE['chat_token'])) $_COOKIE['chat_token'] == "";
        // Header aktualisieren zu login
        header('Location: /login');
    }

    /**
     * Prüft ob der Benutzer eingeloggt ist
     *
     */
    public function userLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['token']) || $_SESSION['token'] == "") {
            return false;
        }
        return true;
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
            $result = Token::validate($token, 'sec!ReT423*&');
            if ($result) {
                // Token ist gültig
                $username = $result['username'];
            }
            // Setze neuen Token
            if (!empty($username)) {
                $_SESSION['token'] = $token;
                $_SESSION['LAST_ACTIVITY'] = time();
                if ($remember == true) {
                    $expired_seconds = time() + 60 * 60 * 24 * 7;
                    setcookie('chat_token', $token, $expired_seconds);
                }
            }
        }
    }

    
}
?>