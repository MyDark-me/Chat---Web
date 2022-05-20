<?php
/**
 * Name users.php
 * Das hier stellt die Datenbank user info abfragen bereit.
 * Verändert ein user Profil
 * 
 * @author KeksGauner
 * @version 2.0
 */
namespace Chat;

use ReallySimpleJWT\Token;
use ReallySimpleJWT\Parse;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Decode;
use ReallySimpleJWT\Build;
use ReallySimpleJWT\Helper\Validator;
use ReallySimpleJWT\Encoders\EncodeHS256;

class Users {

    /**
     * Gibt die zeit in Sekunden zurück wie lange ein Token gültig ist
     * @return int Zeit wann es expiret
     */
    public static function getExpiredSeconds() {
        // Gültigkeit des Tokens in Sekunden das 7 Tage sind
        return time() + (7 * 24 * 60 * 60);
    }

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
        return 0;
    }

    /**
     * Gibt den Persönlichen Token zurück
     *
     * @param int $userId Die ID des Benutzers
     * @return string Gibt den Token zurück
     */
    public static function createToken($userId, $bot) {
        // Token parameter
        $created = time();
        $bot = $bot ?? false;
        $expiration = self::getExpiredSeconds();
        $issuer = $_SERVER['HTTP_HOST'];

        // Token secret aus der Konfiguration auslesen
        require_once ROOTPATH . '/protected/config.php';
        $secret = TOKEN_SECRET;

        // Erstellt mit ReallySimpleJWT einen Token
        $build = new Build('JWT', new Validator(), new EncodeHS256($secret));

        // Setzt den inhalt des JWT Tokens
        $token = $build->setContentType('JWT')
            //->setHeaderClaim('info', 'foo')
            //->setIssuer($issuer)
            //->setSubject('admins')
            //->setAudience('https://google.com')
            ->setExpiration($expiration)
            //->setNotBefore(time() - 30)
            ->setIssuedAt(time())
            //->setJwtId('123ABC')
            ->setPayloadClaim('userid', $userId)
            ->build();

        // String abfrage des Tokens
        $string_token = $token->getToken() ?? null;

        // Speichern des Tokens in der Datenbank
        $db = app_db();
        $insert_arrays = array
        (
        'UserID' => "$userId",
        'Created'=> date(DATE_ATOM,$created),
        'Bot' => "$bot",
        'Token'=> "$string_token",
        'Expiration'=> date(DATE_ATOM,$expiration)
        );
        $db->Insert('Tokendatenbank',$insert_arrays);
        // Rückgabe des Tokens
        return $string_token;
    }

    /**
     * Prüft ob der Token gültig ist
     *
     * @param string $token Der zu prüfende Token
     * @param bool $bot Ob es nach einen Bot token gefragt ist
     * @return bool True wenn der Token gültig ist, sonst false
     */
    public static function verifyToken($token, $bot) {
        // Prüfen ob der Token nicht leer ist
        if(empty($token))
            return false;

        // Token secret aus der Konfiguration auslesen
        require_once ROOTPATH . '/protected/config.php';
        $secret = TOKEN_SECRET;

        // Prüfen ob der Token nicht gültig ist
        if(!Token::validate($token, $secret))
            return false;

        // Prüfen ob der Token in der Datenbank ist
        $db = app_db();
        $result = $db->query("SELECT * FROM `Tokendatenbank` WHERE `Token`= '$token';");
        $row = $result->fetch_assoc();
        // Prüfen ob der Token gültig ist
        if(empty($row['Token']))
            return false;
        
        // Prüfen ob der Token gültig ist
        if(($row['Bot'] == $bot) && ($row['Expiration'] > time()))
            // Token ist gültig
            return true;

        // Token nicht in der Datenbank
        return false;
    }

    /**
     * Prüft ob der Benutzername den richtlinen entspricht
     *
     * @param string $username Der zu prüfende Benutzername
     * @return bool True wenn der Benutzername gültig ist, sonst false
     */
    public static function verifyUsername($username) {
        if(preg_match('/^[a-zA-Z0-9]{3,20}$/', $username))
            return true;
        return false;
    }

    /**
     * Prüft ob die E-Mail Adresse den richtlinen entspricht
     *
     * @param string $email Der zu prüfende E-Mail Adresse
     * @return bool True wenn der Benutzername gültig ist, sonst false
     */
    public static function verifyEmail($email) {
        if(preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $email))
            return true;
        return false;
    }
    /**
     * 
     * Prüft ob die E-Mail Adresse den richtlinen entspricht
     *
     * @param string $email Der zu prüfende E-Mail Adresse
     * @return bool True wenn der Benutzername gültig ist, sonst false
     */
    public static function verifyPassword($password) {
        if(preg_match('/^[a-zA-Z0-9]{6,20}$/', $password))
            return true;
        return false;
    }
}
?>