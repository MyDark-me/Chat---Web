function pwdcheck() {
    // Warum ist hier var? Das sollte eigentlich nicht verwendet werden.
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

