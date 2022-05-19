// Einmal die Events registrieren wenn das Document geladen wird
$(function() {
    // Password Check
    $('#form-register #fistpassword, #form-register #secondpassword').on('change', function(){
        // Passwort als Variable speichern
        const pwd = new String($("#fistpassword").val());
        const pwd2 = new String($("#secondpassword").val());

        // Passwort muss übereinstimmen
        if (pwd != pwd2) {
            $("#pwdcheck").html("Die angegebenen Passwörter stimmen nicht überein!");
            $("#pwdcheck").css("color", "red");
            $("#btn-register").attr('disabled', 'true');
        } else {
            // Passwort muss mindestens 8 Zeichen lang sein
            if (pwd.length < 8) {
                $("#pwdcheck").html("Das Passwort muss mindestens 8 Zeichen lang sein!");
                $("#pwdcheck").css("color", "red");
                $("#btn-register").attr('disabled', 'false');
            } else {
                $("#pwdcheck").html(" ");
                $("#btn-register").attr('disabled', 'false');
            }
        }
    });

    // Filter den Username in der HTML
    $('#form-register #username').on('change', function(){
        // Sende eine AJAX-Request zum Server (XML)
        $.ajax({
            type: 'GET',
            // URL zum Server
            url: "/api/v2/users/account/username/" + $("#username").val + "?notoken",
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
       // Sende eine AJAX-Request zum Server (XML)
        $.ajax({
            type: 'GET',
            // URL zum Server
            url: "/api/v2/users/account/email/" + $("#email").val + "?notoken",
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
    $('#form-register #btn-register').on('submit', function(){
        const data = $("#form-login").serialize();
        // Sende eine AJAX-Request zum Server (XML)
         $.ajax({
             type: 'POST',
             // URL zum Server
             url: "/api/v2/users/register?notoken",
             async: true,
             contentType: "javascript/json",
             dataType: "json",
             success: function (response) {
                 // Filtern nach der Antwort des Servers
                 /**
                  * 
                  * HIER SIND DIE DATEN VOM SERVER
                  * z.B. reponse['status']
                  * 
                  */
                console.log(response); // Für alle werte die zurückkommen
                 
            }
         });
     });
});