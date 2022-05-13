<?php
/**
 * Name account.php
 * Das hier stellt die Account info abfragen bereit.
 */

// Rückgabe ob die E-Mail Adresse bereits vorhanden ist
if($type == 'email') {
    if($users->emailCount($data) != 0) {
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
    if($users->usernameCount($data) != 0) {
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
/*if($type == 'test') {
    $feedback = json_decode(file_get_contents(AJAX_URL . '/users/account/email/' . $data));
    echo $feedback->code; 
}*/

// Wenn ein falscher Typ angegeben wurde, wird eine Fehlermeldung zurückgegeben
http_response_code(404);
die(json_encode(array(
    'status'=>'failure',			
    'message' => 'Not Found',	
    'code' => '404',
), JSON_PRETTY_PRINT));

?>