<?php

// Rückgabe ob die E-Mail Adresse bereits vorhanden ist
if($type == 'email') {
    $db = app_db();
    $email = $db->CleanDBData($data);
    // Abfrage ob die E-Mail existiert
    $result = $db->query("SELECT `Username` FROM `Nutzerdatenbank` WHERE `Email`= '$email';");
    $row = $result->fetch_assoc();
    // Wenn der die E-Mail nicht existiert, wird die Benutzername Prüfung durchgeführt
    if($result != false && mysqli_num_rows($result) == 0) {
        // Falls der die E-Mail nicht existiert, wird eine vorhanden Meldung ausgegeben
        die(json_encode(array(
            'status'=>'failure',			
            'message' => 'Email not exists',	
            'code' => '0',
        ), JSON_PRETTY_PRINT));
    } else {
        // Falls der die E-Mail existiert, wird eine existierende E-Mail zurückgegeben
        die(json_encode(array(
            'status'=>'success',			
            'message' => 'Email already exists',	
            'code' => '0',
        ), JSON_PRETTY_PRINT));
    }
}
if($type == 'test') {
    $feedback = json_decode(file_get_contents(AJAX_URL . '/users/account/email/' . $data));
    echo $feedback->code; 
}

// Wenn ein falscher Typ angegeben wurde, wird eine Fehlermeldung zurückgegeben
die(json_encode(array(
    'status'=>'failure',			
    'message' => 'Type not known',	
    'code' => '404',
), JSON_PRETTY_PRINT));

?>