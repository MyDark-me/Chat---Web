"use strict";
$(function () {
    $('#form-register #fistpassword, #form-register #secondpassword').on('change', function () {
        const pwd = new String($("#fistpassword").val());
        const pwd2 = $("#secondpassword").val();
        if (pwd != pwd2) {
            $("#pwdcheck").html("Die angegebenen Passwörter stimmen nicht überein!");
            $("#pwdcheck").css("color", "red");
            $("#btn-register").attr('disabled', 'true');
        }
        else {
            if (pwd.length < 8) {
                $("#pwdcheck").html("Das Passwort muss mindestens 8 Zeichen lang sein!");
                $("#pwdcheck").css("color", "red");
                $("#btn-register").attr('disabled', 'false');
            }
            else {
                $("#pwdcheck").html(" ");
                $("#btn-register").attr('disabled', 'false');
            }
        }
    });
    $('#form-register #username').on('change', function () {
        $.ajax({
            type: 'GET',
            url: "/api/v2/users/account/username/" + $("#username").val + "?notoken",
            async: true,
            contentType: "javascript/json",
            dataType: "json",
            success: function (response) {
                if (response['available'] == "true") {
                    $("#form-register #username").css("color", "green");
                }
                if (response['available'] == "false") {
                    $("#form-register #username").css("color", "red");
                }
            }
        });
    });
    $('#form-register #email').on('change', function () {
        $.ajax({
            type: 'GET',
            url: "/api/v2/users/account/email/" + $("#email").val + "?notoken",
            async: true,
            contentType: "javascript/json",
            dataType: "json",
            success: function (response) {
                if (response['available'] == "true") {
                    $("#form-register #email").css("color", "green");
                }
                if (response['available'] == "false") {
                    $("#form-register #email").css("color", "red");
                }
            }
        });
    });
    $('#form-register #btn-register').on('submit', function () {
        const data = $("#form-login").serialize();
        $.ajax({
            type: 'POST',
            url: "/api/v2/users/register?notoken",
            async: true,
            contentType: "javascript/json",
            dataType: "json",
            success: function (response) {
            }
        });
    });
});
//# sourceMappingURL=register.js.map