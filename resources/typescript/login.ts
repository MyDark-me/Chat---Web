// Einmal die Events registrieren wenn das Document geladen wird
$(function() {
    // Wenn der Button gedrückt wird
    $('#form-login').on('submit', function(event){
        // Form daran hindern zu senden auf normalen weg
        event.preventDefault();
        // Die Daten aus dem Formular holen
        const data = $("#form-login").serialize();
        // Sende eine AJAX-Request zum Server (XML)
         $.ajax({
             type: 'POST',
             // URL zum Server. Automatisch in einen Cookie schreiben
             url: "api/v2/users/login?cookie",
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
                  *     message: GET is not Allowed
                  *     code: 406
                  *     ---
                  *     status: failure
                  *     message: No Account
                  *     code: 2
                  *     ---
                  *     status: failure
                  *     message: Password is invalid
                  *     code: 3
                  *     ---
                  *     status: failure
                  *     message: Already logged in
                  *     code: 7
                  *     ---
                  *     status: succes
                  *     message: Logged in succesfully
                  *     token: token? // Der generiete Token
                  *     expire: ? // Die verfallszeit
                  *     code: 201
                  *     ---
                  *     status: failure
                  *     message: ? // Fehler nachricht
                  *     code: 500
                  *     ---
                  *     status: failure
                  *     message: Request Blocked
                  *     code: 203
                  *     delay: ? // verbleibende Verzögerung in Sekunden
                  *     ---
                  *     status: failure
                  *     message: Captcha required
                  *     code: 203
                  */
                console.log(response); // Für alle werte die zurückkommen
                if (response['code'] == 201) {
                    $("#form-login #btn-login").attr('disabled');
                    // Hier sollte code für Feedback stehen

                    window.setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                }

                if (response['code'] == 7) {
                    window.location.reload();
                }
                
            }
         });
     });
});