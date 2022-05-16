<?php
/**
 * Name users.php
 * Das hier stellt die Datenbank user info abfragen bereit.
 * Verändert ein user Profil
 */
namespace Chat;

use ReallySimpleJWT\Token;
use ReallySimpleJWT\Parse;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Decode;

class Users {

    /**
     * Prüft ob die angegebene E-Mail Adresse existiert
     *
     * @param string $email Die zu prüfende E-Mail Adresse
     * @return bool True wenn die E-Mail Adresse existiert, sonst false
     */
    public static function existEmail($email) {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $email = $db->CleanDBData($email);
        // Abfrage der E-Mail anzahl
        $result = $db->select("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Email`= '$email';");
        // Rückgabe ob die E-Mail existiert
        if($result != false && $result != 0) 
            return true;
        else
            return false;
    }

    /**
     * Prüft ob der angegebene Benutzername existiert
     *
     * @param string $username Der zu prüfende Benutzername
     * @return bool True wenn die E-Mail Adresse existiert, sonst false
     */
    public static function existUsername($username) {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $username = $db->CleanDBData($username);
        // Abfrage der E-Mail anzahl
        $result = $db->select("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Username`= '$username';");
        // Rückgabe ob der Username existiert
        if($result != false && $result != 0) 
            return true;
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
    public static function addUser($username, $password, $email) {
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
     * Prüft das Passwort mit dem in der Datenbank gespeicherten Passwort
     *
     * @param string $username Der zu prüfende Benutzername oder die E-Mail Adresse
     * @param string $password Das zu prüfende Passwort
     * @return bool True wenn das Passwort korrekt ist, sonst false
     */
    public static function checkPassword($username, $password) {
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
     *
     * @param string $username Der zu prüfende Benutzername oder E-Mail Adresse
     * @return int Die ID des Benutzers falls vorhanden, sonst 0
     */
    public static function useridOf($username) {
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
     * Gibt den Persönlichen Token zurück
     *
     * @param int $userId Die ID des Benutzers
     * @return string Gibt den Token zurück
     */
    public static function createToken($userId) {
        // Dauer des Tokens in Sekunden das 7 Tage sind
        $expired_seconds = time() + 60 * 60 * 24 * 7;

        // Erstellt mit ReallySimpleJWT einen Token
        $payload = [
            'created' => time(),
            'userid' => $userId,
            'bot' => true,
            'expiration' => $expired_seconds,
            'issuer' => $_SERVER['HTTP_HOST']
        ];
        
        require_once ROOTPATH . '/protected/config.php';
        $secret = TOKEN_SECRET;
        return Token::customPayload($payload, $secret);
    }

    /**
     * Gibt einen Bot Token zurück
     *
     * @param int $userId Die ID des Benutzers
     * @return string Gibt den Token zurück
     */
    public static function createBotToken($userId) {
        // Dauer des Tokens in Sekunden das 7 Tage sind
        $expired_seconds = time() + 60 * 60 * 24 * 7;

        // Erstellt mit ReallySimpleJWT einen Token
        $payload = [
            'created' => time(),
            'userid' => $userId,
            'bot' => true,
            'expiration' => $expired_seconds,
            'issuer' => $_SERVER['HTTP_HOST']
        ];

        require_once ROOTPATH . '/protected/config.php';
        $secret = TOKEN_SECRET;
        return Token::customPayload($payload, $secret);
    }

    /**
     * Prüft ob der Token gültig ist
     *
     * @param string $token Der zu prüfende Token
     * @return bool True wenn der Token gültig ist, sonst false
     */
    public static function verifyToken($token) {
        require_once ROOTPATH . '/protected/config.php';
        $secret = TOKEN_SECRET;

        if(Token::validate($token, $secret))
            return true;
        else
            return false;
    }

    /**
     * Prüft ob der Token für bots gültig ist
     *
     * @param string $token Der zu prüfende Token
     * @return bool True wenn der Token gültig ist, sonst false
     */
    public static function verifyBotToken($token) {
        require_once ROOTPATH . '/protected/config.php';
        $secret = TOKEN_SECRET;

        if(Token::validate($token, $secret)) {
            // Parse Token
            $jwt = new Jwt($token);

            $parse = new Parse($jwt, new Decode());
            $parsed = $parse->parse();
            
            // Return the token payload claims as an associative array.
            $parsed->getPayload();

            // Prüfung ob der Token für einen Bot ist
            if($parsed->getPayload()['bot'])
                return true;
            else
                return false;
        }
        else
            return false;
    }
}
?>