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
            }
        });
    });
});
//# sourceMappingURL=logout.js.map