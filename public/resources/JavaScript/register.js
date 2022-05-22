"use strict";
$(function () {
    $('#form-register #password_1, #form-register #password_2').on('change', function () {
        const pwd = $("#form-register #password_1").val();
        const pwd2 = $("#form-register #password_2").val();
        if (pwd != pwd2) {
            $("#pwdcheck").html("Die angegebenen Passwörter stimmen nicht überein!");
            $("#pwdcheck").css("color", "red");
            $("#btn-register").attr('disabled');
        }
        else {
            if (new String(pwd).length < 8) {
                $("#pwdcheck").html("Das Passwort muss mindestens 8 Zeichen lang sein!");
                $("#pwdcheck").css("color", "red");
                $("#btn-register").attr('disabled');
            }
            else {
                $("#pwdcheck").html(" ");
                $("#btn-register").removeAttr('disabled');
            }
        }
    });
    $('#form-register #username').on('change', function () {
        const username = $("#form-register #username").val();
        $.ajax({
            type: 'GET',
            url: "/api/v2/users/account/username/" + username + "?notoken",
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
        const email = $("#form-register #email").val();
        $.ajax({
            type: 'GET',
            url: "/api/v2/users/account/email/" + email + "?notoken",
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
    $('#form-register').on('submit', function (event) {
        event.preventDefault();
        const data = $("#form-register").serialize();
        $.ajax({
            type: 'POST',
            url: "/api/v2/users/register?cookie",
            data: data,
            async: true,
            dataType: "json",
            success: function (response) {
                console.log(response);
            }
        });
    });
});
//# sourceMappingURL=register.js.map