// Einmal die Events registrieren wenn das Document geladen wird
$(function() {
    // Wenn der Button gedrückt wird
    $('#form-logout').on('submit', function(event){
        // Form daran hindern zu senden auf normalen weg
        event.preventDefault();
        // Die Daten aus dem Formular holen
        const data = $("#form-logout").serialize();
        // Sende eine AJAX-Request zum Server (XML)
         $.ajax({
             type: 'POST',
             // URL zum Server. Automatisch in einen Cookie schreiben
             url: "api/v2/users/logout",
             // Die Daten die an den Server gesendet werden
             data: data,
             async: true,
             //contentType: "javascript/json",
             dataType: "json",
             success: function (response) { //alert("THX for a Token COOKIE :)");
                 // Filtern nach der Antwort des Servers
                 /**
                  * 
                  * HIER SIND DIE DATEN VOM SERVER
                  * z.B. response['status']
                  * 
                  * Codes:
                  *     status: failure
                  *     message: Could not delete cookie
                  *     code: 202
                  *     ---
                  *     status: success
                  *     message: Logged out succesfully
                  *     code: 200
                  */
                console.log(response); // Für alle werte die zurückkommen
                if (response['code'] == 200) { // Logged out succesfully
                    $("#form-logout #btn-logout").attr('disabled');
                    // Hier sollte code für Feedback stehen

                    window.setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                }
            }
         });
     });
});