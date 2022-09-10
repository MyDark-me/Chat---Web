"use strict";
$(function () {
    $('#form-logout').on('submit', function (event) {
        event.preventDefault();
        const data = $("#form-logout").serialize();
        $.ajax({
            type: 'POST',
            url: "api/v2/users/logout",
            data: data,
            async: true,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response['code'] == 200) {
                    $("#form-logout #btn-logout").attr('disabled');
                    window.setTimeout(function () {
                        window.location.reload();
                    }, 2000);
                }
            }
        });
    });
});
//# sourceMappingURL=logout.js.map