
class Login {
    static readonly USERNAME = "username";
    static readonly PASSWORD = "password";


    private loginService(data: any) {
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