<?php

// Rückgabe ob die E-Mail Adresse bereits vorhanden ist
if($type == 'email') {
    $db = app_db();
    $email = $db->CleanDBData($data);
    // Abfrage ob die E-Mail existiert
    $result = $db->query("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Email`= '$email';");
    $row = $result->fetch_assoc();
    // Wenn der die E-Mail existiert
    if($result != false && mysqli_num_rows($result) != 0) {
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
    $db = app_db();
    $username = $db->CleanDBData($data);
    // Abfrage ob die E-Mail existiert
    $result = $db->query("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Username`= '$username';");
    $row = $result->fetch_assoc();
    // Wenn der die E-Mail existiert
    if($result != false && mysqli_num_rows($result) != 0) {
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