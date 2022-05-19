<?php
/**
 * Name account.php
 * Das hier stellt die Account info abfragen bereit.
 * 
 * @author KeksGauner
 * @version 2.0
 * 
 * Benutzen Sie diesen Code für das Login:
 * Sende ein AJAX Request zum Server
 * 
 * POST: mit token
 * In PHP (Javascript) - $url = "/api/{version}/users/account/{type}/{request}"
 * Ohne Webbrowser - $url = "{server}/api/{version}/users/account/{type}/{request}"
 * 
 * GET:
 * In PHP (Javascript) - $url = "/api/{version}/users/account/{type}/{request}?token={token}"
 * Ohne Webbrowser - $url = "{server}/api/{version}/users/account/{type}/{request}?token={token}"
 * 
 * Timestamp: 2020-05-18
 */

use Chat\Users;

// Bekomme Request methode
$request = null;
switch($_SERVER['REQUEST_METHOD'])
{
    case 'GET': $request = &$_GET; break;
    case 'POST': $request = &$_POST; break;

default:
}
/**
 * Prüft ob notoken oder token übergeben wurde sonst unautorisiert
 */
$token = $request['token'] ?? null;
if(isset($request['notoken']) && !isset($request['token']) || !isset($request['notoken']) && isset($request['token'])) {
    http_response_code(401);
    die(json_encode(array(
        'status'=>'failure',	
        'message' => 'Unauthorized',	
        'code' => '401',
    ), JSON_PRETTY_PRINT));
}
 
switch($type) {
    /**
    * Gibt zurück ob die E-Mail existiert
    */
    case 'email':
        // Abfrage ob die E-Mail Adresse vorhanden ist
        if(Users::existEmail($data)) {
            // Falls der die E-Mail existiert, wird eine existierende E-Mail zurückgegeben
            http_response_code(200);
            die(json_encode(array(
                'status'=> 'success',	
                'available'=> 'false',		
                'message' => 'Email already exists',	
                'code' => '10_005',
            ), JSON_PRETTY_PRINT));
        } else {
            // Falls der die E-Mail nicht existiert, wird eine vorhanden Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status'=> 'success',	
                'available'=> 'true',			
                'message' => 'Email available',	
                'code' => '200',
            ), JSON_PRETTY_PRINT));
        }
        break;
    /**
    * Gibt zurück ob der Benutzer existiert
    */
    case 'username':
        // Abfrage ob der Username vorhanden ist
        if(Users::existUsername($data)) {
            // Falls der die E-Mail existiert, wird eine existierende E-Mail zurückgegeben
            http_response_code(200);
            die(json_encode(array(
                'status'=> 'success',	
                'available'=> 'false',		
                'message' => 'Username already exists',	
                'code' => '10_005',
            ), JSON_PRETTY_PRINT));
        } else {
            // Falls der die E-Mail nicht existiert, wird eine vorhanden Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'success',	
                'available'=> 'true',			
                'message' => 'Username available',	
                'code' => '200'
            ), JSON_PRETTY_PRINT));
        }
        break;
    /**
    * Prüft ob der Benutzername benutzbar ist
    */
    case 'verifyUsername':
        // Abfrage ob der Username nutzbar ist
        if(Users::verifyUsername($data)) {
            // Falls der Username nutzbar ist, wird eine nutzbar Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'success',	
                'message' => 'Username is usable',	
                'code' => '200'
            ), JSON_PRETTY_PRINT));
        } else {
            // Falls der Username nicht nutzbar ist, wird eine nicht nutzbar Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'failure',	
                'message' => 'Username is not usable',	
                'code' => '406'
            ), JSON_PRETTY_PRINT));
        }
        break;
    /**
    * Prüft ob der Benutzername benutzbar ist
    */
    case 'verifyUsername':
        // Abfrage ob der Username nutzbar ist
        if(Users::verifyUsername($data)) {
            // Falls der Username nutzbar ist, wird eine nutzbar Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'success',	
                'message' => 'Username is usable',	
                'code' => '200'
            ), JSON_PRETTY_PRINT));
        } else {
            // Falls der Username nicht nutzbar ist, wird eine nicht nutzbar Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'failure',	
                'message' => 'Username is not usable',	
                'code' => '406'
            ), JSON_PRETTY_PRINT));
        }
        break;
    /**
    * Prüft ob die E-Mail Adresse benutzbar ist
    */
    case 'verifyEmail':
        // Abfrage ob die E-Mail Adresse nutzbar ist
        if(Users::verifyEmail($data)) {
            // Falls die E-Mail Adresse nutzbar ist, wird eine nutzbar Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'success',	
                'message' => 'Email is usable',	
                'code' => '200'
            ), JSON_PRETTY_PRINT));
        } else {
            // Falls die E-Mail Adresse nicht nutzbar ist, wird eine nicht nutzbar Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'failure',	
                'message' => 'Email is not usable',	
                'code' => '406'
            ), JSON_PRETTY_PRINT));
        }
        break;
    /**
    * Prüft ob das Passwort benutzbar ist
    */
    case 'verifyPassword':
        // Abfrage ob das Passwort nutzbar ist
        if(Users::verifyEmail($data)) {
            // Falls das Passwort nutzbar ist, wird eine nutzbar Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'success',	
                'message' => 'Password is usable',	
                'code' => '200'
            ), JSON_PRETTY_PRINT));
        } else {
            // Falls das Passwort nicht nutzbar ist, wird eine nicht nutzbar Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'failure',	
                'message' => 'Password is not usable',	
                'code' => '406'
            ), JSON_PRETTY_PRINT));
        }
        break;
    /**
    * Prüft ob der Token gültig ist
    */
    case 'token':
        // Prüft ob der Token gültig ist
        if(Users::verifyToken($data)) {
            // Falls der Token gültig ist, wird eine vorhanden Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'success',	
                'message' => 'Token is valid',	
                'code' => '200'
            ), JSON_PRETTY_PRINT));
        } else {
            // Falls der Token nicht gültig ist, wird eine vorhanden Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status' => 'failure',	
                'message' => 'Token is not valid',	
                'code' => '406'
            ), JSON_PRETTY_PRINT));
        }
        break;
    default:
        // Wenn ein falscher Typ angegeben wurde, wird eine Fehlermeldung zurückgegeben
        http_response_code(400);
        die(json_encode(array(
            'status'=> 'failure',	
            'message' => 'Bad Request',	
            'code' => '400',
        ), JSON_PRETTY_PRINT));
}

?>