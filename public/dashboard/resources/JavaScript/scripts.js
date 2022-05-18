function pwdcheck() {
    var pwd = document.getElementById("fpwd").value;
    var pwd2 = document.getElementById("spwd").value;
    
    if (pwd != pwd2) {
        document.getElementById("pwdcheck").innerHTML = "Die angegebenen Passwörter stimmen nicht überein!";
        document.getElementById("pwdcheck").style.color = "red";
        document.getElementById("btn-register").disabled = true;
    } else {
        if (pwd.length < 8) {
            document.getElementById("pwdcheck").innerHTML = "Das Passwort muss mindestens 8 Zeichen lang sein!";
            document.getElementById("pwdcheck").style.color = "red";
            document.getElementById("btn-register").disabled = true;
        } else {
            document.getElementById("pwdcheck").innerHTML = " ";
            document.getElementById("btn-register").disabled = false;
        }
    }
}

function usernameCheck() {
    $.ajax({
        type: 'POST',
        url: "/api/v2/users/account/username/" + document.getElementById("username").value + "?devmode",
        success: function (response) {
            if (response['available'] == "true") {
                document.getElementById("usercheck").innerHTML = "Dieser Username ist frei!";
                document.getElementById("usercheck").style.color = "green";
                document.getElementById("username").style.color = "green";
            }
            if (response['available'] == "false") {
                pocument.getElementById("usercheck").innerHTML = "Dieser Username ist bereits vergeben!";
                document.getElementById("usercheck").style.color = "red";
                document.getElementById("username").style.color = "red";
            }
        }
    });
}

function emailCheck() {
    $.ajax({
        type: 'POST',
        url: "/api/v2/users/account/email/" + document.getElementById("email").value + "?devmode",
        success: function (response) {
            if (response['available'] == "true") {
                document.getElementById("emailcheck").innerHTML = "Diese E-Mail ist frei!";
                document.getElementById("emailcheck").style.color = "green";
                document.getElementById("email").style.color = "green";
            }
            if (response['available'] == "false") {
                document.getElementById("emailcheck").innerHTML = "Diese E-Mail ist bereits vergeben!";
                document.getElementById("emailcheck").style.color = "red";
                document.getElementById("email").style.color = "red";
            }
        }
    });

}