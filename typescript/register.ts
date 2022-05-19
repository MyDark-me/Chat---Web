function usernameCheck() {
    $.ajax({
        type: 'POST',
        url: "/api/v2/users/account/username/" + $("#username").val + "?notoken",
        async: true,
        contentType: "javascript/json",
        dataType: "json",
        success: function (response) {
            if (response['available'] == "true") {
                $("#usercheck").html("Dieser Username ist frei!");
                $("#usercheck").css("color", "green");
                $("#username").css("color", "green");
            }
            if (response['available'] == "false") {
                $("#usercheck").html("Dieser Username ist bereits vergeben!");
                $("usercheck").css("color", "red");
                $("username").css("color", "red");
            }
        }
    });
}

function emailCheck() {
    $.ajax({
        type: 'POST',
        url: "/api/v2/users/account/email/" + $("#email").val + "?notoken",
        async: true,
        contentType: "javascript/json",
        dataType: "json",
        success: function (response) {
            if (response['available'] == "true") {
                $("#emailcheck").html("Diese E-Mail ist frei!");
                $("#emailcheck").css("color", "green");
                $("#email").css("color", "green");
            }
            if (response['available'] == "false") {
                $("#emailcheck").html("Diese E-Mail ist bereits vergeben!");
                $("#emailcheck").css("color", "red");
                $("#email").css("color", "red");
            }
        }
    });
}