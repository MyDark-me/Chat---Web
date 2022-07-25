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
                if (response['code'] == 201) {
                    $("#form-login #btn-login").attr('disabled');
                    window.setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                }
                if (response['code'] == 7) {
                    window.location.reload();
                }
            }
        });
    });
});
//# sourceMappingURL=login.js.map