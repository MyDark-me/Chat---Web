// Einmal die Events registrieren wenn das Document geladen wird
$(function() {
    // Wenn der Button gedrückt wird
    $('#form-login #btn-login').on('submit', function(){
        // Die Daten aus dem Formular holen
        const data = $("#form-login").serialize();
        // Sende eine AJAX-Request zum Server (XML)
         $.ajax({
             type: 'POST',
             // URL zum Server. Automatisch in einen Cookie schreiben
             url: "/api/v2/users/login?cookie",
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
                console.log(response); // Für alle werte die zurückkommen
            }
         });
     });
});