"use strict";
class Login {
    loginService(data) {
        $.ajax({
            method: 'POST',
            url: "/api/v2/users/account/username/" + "" + "?notoken",
            data: data,
            async: true,
            contentType: "javascript/json",
            dataType: "json",
            success: function (response) {
                console.log(JSON.stringify(response));
            },
            error: function (response, status, error) {
                console.log(JSON.stringify(response));
            }
        });
    }
}
Login.USERNAME = "username";
Login.PASSWORD = "password";
//# sourceMappingURL=login.js.map