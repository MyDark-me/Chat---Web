<?php
/**
 * Name users.php
 * Das hier stellt die Datenbank user info abfragen bereit.
 * Verändert ein user Profil
 */
class Users {

    public function __construct() {
    }

    public function emailCount($data) {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $email = $db->CleanDBData($data);
        // Abfrage der E-Mail anzahl
        $result = $db->select("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Email`= '$email';");
        // Rückgabe der Anzahl der E-Mail Adressen
        if($result != false)
            return $result;
        else
            return false;
    }

    public function usernameCount($data) {
        // Datenbankverbindung herstellen
        $db = app_db();   
        // SQL gegen injektion schützen
        $username = $db->CleanDBData($data);
        // Abfrage der E-Mail anzahl
        $result = $db->select("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Username`= '$username';");
        // Rückgabe wie viele user mit diesem username existieren
        if($result != false)
            return $result;
        else
            return false;
    }

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

    
}
?>