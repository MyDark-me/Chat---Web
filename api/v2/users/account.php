<?php
/**
 * Name account.php
 * Das hier stellt die Account info abfragen bereit.
 */

// Wir initialisieren die User Class um User Einstellungen zu ermöglichen
use Chat\Users;

// Rückgabe ob die E-Mail Adresse bereits vorhanden ist
if($type == 'email') {
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
            'status'=>'failure',	
            'found'=>'false',			
            'message' => 'Email available',	
            'code' => '200',
        ), JSON_PRETTY_PRINT));
    }
}

if($type == 'username') {
    // Abfrage ob der Username existiert
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
            'status'=>'failure',	
            'found'=>'false',			
            'message' => 'Username available',	
            'code' => '200',
        ), JSON_PRETTY_PRINT));
    }
}

// Wenn ein falscher Typ angegeben wurde, wird eine Fehlermeldung zurückgegeben
http_response_code(404);
die(json_encode(array(
    'status'=>'failure',			
    'message' => 'Not Found',	
    'code' => '404',
), JSON_PRETTY_PRINT));

?>