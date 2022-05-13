<?php
/**
 * Name users.php
 * Das hier stellt die Datenbank user info abfragen bereit.
 * Verändert ein user Profil
 */
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
        // User hinzufügen
        $db->query("INSERT INTO `Nutzerdatenbank` (`Username`, `Password`, `Email`) VALUES ('$username', '$password', '$email');");
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
        if($row['Password'] == $password)
            return true;
        else
            return false;
    }

    /**
     * Speichert ein persönliches Thoken in den Cookies
     *
     * @param string $username Wie der Benutzer heißt
     */
    public function setSession($username) {
        // Muss noch bearbeitet werden
    }

    
}
?>