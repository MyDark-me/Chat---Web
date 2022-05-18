<?php
/**
 * Name account.php
 * Das hier stellt die Account info abfragen bereit.
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
 * Validiert ob der Token gültig ist
 */
$token = $request['token'] ?? null;
if(!Users::verifyToken($token, true)) {
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
                'status'=>'success',	
                'found'=>'true',		
                'message' => 'Email already exists',	
                'code' => '10_005',
            ), JSON_PRETTY_PRINT));
        } else {
            // Falls der die E-Mail nicht existiert, wird eine vorhanden Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status'=>'success',	
                'found'=>'false',			
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
                'status'=>'success',	
                'found'=>'true',		
                'message' => 'Username already exists',	
                'code' => '10_005',
            ), JSON_PRETTY_PRINT));
        } else {
            // Falls der die E-Mail nicht existiert, wird eine vorhanden Meldung ausgegeben
            http_response_code(200);
            die(json_encode(array(
                'status'=>'success',	
                'found'=>'false',			
                'message' => 'Username available',	
                'code' => '200',
            ), JSON_PRETTY_PRINT));
        }
        break;
    default:
        // Wenn ein falscher Typ angegeben wurde, wird eine Fehlermeldung zurückgegeben
        http_response_code(400);
        die(json_encode(array(
            'status'=>'failure',	
            'message' => 'Bad Request',	
            'code' => '400',
        ), JSON_PRETTY_PRINT));
}

?>