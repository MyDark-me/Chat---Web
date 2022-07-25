// Einmal die Events registrieren wenn das Document geladen wird
$(function() {
    // Password Check
    $('#form-register #password_1, #form-register #password_2').on('change', function(){
        // Passwort als Variable speichern
        const pwd = $("#form-register #password_1").val();
        const pwd2 = $("#form-register #password_2").val();
        // Passwort muss übereinstimmen
        if (pwd != pwd2) {
            $("#pwdcheck").html("Die angegebenen Passwörter stimmen nicht überein!");
            $("#pwdcheck").css("color", "red");
            $("#btn-register").attr('disabled');
        } else {
            // Passwort muss mindestens 8 Zeichen lang sein
            if (new String(pwd).length < 8) {
                $("#pwdcheck").html("Das Passwort muss mindestens 8 Zeichen lang sein!");
                $("#pwdcheck").css("color", "red");
                $("#btn-register").attr('disabled');
            } else {
                $("#pwdcheck").html(" ");
                $("#btn-register").removeAttr('disabled');
            }
        }
    });

    // Filter den Username in der HTML
    $('#form-register #username').on('change', function(){
        // Username als Variable speichern
        const username = $("#form-register #username").val();
        // Sende eine AJAX-Request zum Server (XML)
        $.ajax({
            type: 'GET',
            // URL zum Server
            url: "api/v2/users/account/username/" + username + "?notoken",
            // Die Daten die an den Server gesendet werden
            async: true,
            contentType: "javascript/json",
            dataType: "json",
            success: function (response) {
                // Filtern nach der Antwort des Servers
                if (response['available'] == "true") {
                    $("#form-register #username").css("color", "green");
                }
                if (response['available'] == "false") {
                    $("#form-register #username").css("color", "red");
                }
            }
        });
   });

    // Filter den E-Mail in der HTML
    $('#form-register #email').on('change', function(){
        // E-Mail als Variable speichern
        const email = $("#form-register #email").val();
       // Sende eine AJAX-Request zum Server (XML)
        $.ajax({
            type: 'GET',
            // URL zum Server
            url: "api/v2/users/account/email/" + email + "?notoken",
            async: true,
            contentType: "javascript/json",
            dataType: "json",
            success: function (response) {
                // Filtern nach der Antwort des Servers
                if (response['available'] == "true") {
                    $("#form-register #email").css("color", "green");
                }
                if (response['available'] == "false") {
                    $("#form-register #email").css("color", "red");
                }
           }
        });
    });

    // Wenn der Button gedrückt wird
    $('#form-register').on('submit', function(event){
        // Form daran hindern zu senden auf normalen weg
        event.preventDefault();
        // Die Daten aus dem Formular holen
        const data = $("#form-register").serialize();
        // Sende eine AJAX-Request zum Server (XML)
         $.ajax({
             type: 'POST',
             // URL zum Server. Automatisch in einen Cookie schreiben
             url: "api/v2/users/register?cookie",
             // Die Daten die an den Server gesendet werden
             data: data,
             async: true,
             //contentType: "javascript/json",
             dataType: "json",
             success: function (response) { //alert("I don't got any COOKIES :(");
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
                  *     message: Field is missing
                  *     code: 1
                  *     ---
                  *     status: failure
                  *     message: Username is invalid
                  *     code: 4
                  *     ---
                  *     status: failure
                  *     message: E-Mail is invalid
                  *     code: 5
                  *     ---
                  *     status: failure
                  *     message: Password is invalid
                  *     code: 6
                  *     ---
                  *     status: failure
                  *     message: Email already exists
                  *     code: 10_005
                  *     ---
                  *     status: failure
                  *     message: Username already exists
                  *     code: 10_005
                  *     ---
                  *     status: failure
                  *     message: Already logged in
                  *     code: 7
                  *     ---
                  *     status: succes
                  *     message: Successfully registered
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
                 
            }
         });
     });
});