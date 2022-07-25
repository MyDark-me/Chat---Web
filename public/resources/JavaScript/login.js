"use strict";
$(function () {
    $('#form-login').on('submit', function (event) {
        event.preventDefault();
        const data = $("#form-login").serialize();
        $.ajax({
            type: 'POST',
            url: "api/v2/users/login?cookie",
            data: data,
            async: true,
            dataType: "json",
            success: function (response) {
                console.log(response);
            }
        });
    });
});
//# sourceMappingURL=login.js.map