// Einmal die Events registrieren wenn das Document geladen wird
$(function() {
    // Wenn der Button gedrückt wird
    $('#form-login #btn-login').on('submit', function(){
        const data = $("#form-login").serialize();
        // Sende eine AJAX-Request zum Server (XML)
         $.ajax({
             type: 'POST',
             // URL zum Server
             url: "/api/v2/users/login?notoken",
             // Die Daten die an den Server gesendet werden
             data: data,
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
            }
         });
     });
});