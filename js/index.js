import * as lib from './lib.js';

var tag_cookie_accessToken = "accessToken";
var formURL = "action.php";
var POST = "POST"
var GET = "GET"
var constUserTypeGeneral = "client"
var constUserTypeEditor = "editor"
var constUserTypeAdmin = "admin"
var usrId,usrName,usrToken,usrType,usrEmail,usrPassword
var currentNewsPage = 1
var maxNewsPage




// validate password
function validatePassword(password)
{
    // Validate lowercase letters
    var lowerCaseLetters = /[a-z]/g;
    if(!password.match(lowerCaseLetters)) {
        return "Password must include at least one lowercase letter";
    }

    // Validate capital letters
    var upperCaseLetters = /[A-Z]/g;
    if(!password.match(upperCaseLetters)) {
        return "Password must include at least one capital letter";
    }

    // Validate numbers
    var numbers = /[0-9]/g;
    if(!password.match(numbers)) {
        return "Password must include at least one number";
    }

    // Validate length
    if(!password.length >= 8) {
        return "Password must be more than 8 characters";
    }

    return true;
}

// Login by token, apply for trying to auto login when connecting the homepage.
// Token is stored in the cookie at the last successful login.
// Token will be expired in 30 days, or as long as log out.
function loginByToken(userToken)
{
    var data = {"userToken" : userToken,
        "action" : "httpLoginByToken"};
    var postData = $.param(data);
    lib.http(formURL,POST,postData,function (data, textStatus, jqXHR) {
            if(data["status"] == "success")
            {
                usrId = data.user.id;
                usrName = data.user.username;
                usrToken = data.user.accessToken;
                usrType = data.user.userType;
                usrEmail = data.user.emailAddress;
                usrPassword = data.user.password;
                controlUserButtons(true,usrType);
                $('#txUsername').html(usrName);
            }
            else
            {
                controlUserButtons(false,usrType);
            }
    },
        function (jqXHR, status, error) {
            controlUserButtons(false,usrType);
            console.log(status + ": " + error);
        })
}


// Login by username and password, apply for trying to login from login form
// Token is stored in the cookie at the last successful login.
// Token will be expired in 30 days, or as long as log out.
function loginByName(username, password)
{
    var data = {"username" : username,
        "password": password,
        "action" : "httpLogin"};
    var postData = $.param(data);
    lib.http(formURL,POST,postData,function (data, textStatus, jqXHR) {
            if(data["status"] == "success")
            {
                usrId = data.user.id;
                usrName = data.user.username;
                usrToken = data.user.accessToken;
                usrType = data.user.userType;
                usrEmail = data.user.emailAddress;
                usrPassword = data.user.password;
                controlUserButtons(true,usrType);
                $('#txUsername').html(usrName);
                lib.setCookie(tag_cookie_accessToken,data.user.accessToken,30);
            }
            else
            {
                alert("Update failed.\nReason: " + data.msg);
                controlUserButtons(false,usrType);
            }
        },
        function (jqXHR, status, error) {
            controlUserButtons(false,usrType);
            console.log(status + ": " + error);
        })
}

// hide or show buttons: sign up, log in, admin, user, according to the login status and user type
function controlUserButtons(islogin,userType)
{
    if (islogin == false)
    {

        $('#buttonLogin').show();
        $('#buttonSignup').show();
        $('#buttonUserManage').hide();
        $('#containerUsername').hide();
        $('#buttonLogout').hide();
    }
    else
    {
        $('#buttonLogin').hide();
        $('#buttonSignup').hide();
        $('#buttonLogout').show();
        $('#containerUsername').show();
        if (userType == constUserTypeAdmin)
        {
            $('#buttonUserManage').show();
        }
    }
}


// update news list on the homepage
function updateNewsList(pageID)
{
    var data = {"pageId" : pageID,
        "action" : "httpGetNewsList"};
    var postData = $.param(data);
    lib.http(formURL,POST,postData,function(data, textStatus, jqXHR) {
        if(data["status"] == "success")
        {
            $("#newsList").empty();
            var i;
            for (i = 0; i < data["news"].length; i++)
            {
                var news = data["news"][i];
                maxNewsPage = data["maxPageNum"];
                $("#newsList").append(news);
            }
        }
        else
        {
            //controlUserButtons(true,usrType);
        }
        console.log(data);
    },function(jqXHR, status, error) {
        console.log(status + ": " + error);
    });

}


// must apply only after HTML has loaded */
$(document).ready(function () {

    console.log("This is for testing");
    // Try to Auto Login
    var userToken = lib.getCookie(tag_cookie_accessToken);
    var login = false;
    if (userToken != "")
    {
        loginByToken(userToken);
    }
    else
    {
        controlUserButtons(false,usrType)
    }


    // login
    $("#login_form").on("submit", function(e) {
        let username = $("#login_username").val();
        let password = $("#login_password").val();
        loginByName(username,password);
        e.preventDefault();
    });

    // sign up
    $("#signup_form").on("submit", function(e) {

        var valArray = $(this).serializeArray();
        console.log(valArray);
        var username = valArray[1]['value'];
        var emailAddress = valArray[2]['value'];
        var password1 = valArray[3]['value'];
        var password2 = valArray[4]['value'];
        console.log(username);
        console.log(emailAddress);
        console.log(password1);
        console.log(password2);
        console.log(formURL);

        if (username == "")
        {
            alert("Account Name must be filled out");
            return false;
        }

        if (emailAddress == "")
        {
            alert("Email Address must be filled out");
            return false;
        }
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(String(emailAddress).toLowerCase()))
        {
            alert("Email Address is invalid");
            return false;
        }

        if (password1 == "")
        {
            alert("Password must be filled out");
            return false;
        }

        var v = validatePassword(password1);
        if (v != true)
        {
            alert(v);
            return false;
        }

        if (password2 == "")
        {
            alert("Confirmed password must be filled out");
            return false;
        }

        var v = validatePassword(password2);
        if (v != true)
        {
            alert(v);
            return false;
        }

        if (password1 != password2 )
        {
            alert("Password and confirmed password are not same.");
            return false;
        }

        var postData = $(this).serialize();
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            dataType: "json",
            success: function(data, textStatus, jqXHR) {
                if(data["status"] == "success")
                {
                    usrId = data.user.id;
                    usrName = data.user.username;
                    usrToken = data.user.accessToken;
                    usrType = data.user.userType;
                    usrEmail = data.user.emailAddress;
                    usrPassword = data.user.password;
                    controlUserButtons(true,usrType);
                    $('#txUsername').html(usrName);
                    lib.setCookie(tag_cookie_accessToken,data.user.$accessToken,30);
                }
                else
                {
                    alert("Sign up failed.\nReason: " + data["msg"]);
                }
            },
            error: function(jqXHR, status, error) {
                $('#error').html(jqXHR.responseText);
                console.log(status + ": " + error);

            }
        });
        e.preventDefault();
    });

    // Update User profile
    $("#edit_user_form").on("submit", function(e) {

        var valArray = $(this).serializeArray();
        console.log(valArray);
        var username = valArray[1]['value'];
        var emailAddress = valArray[2]['value'];
        var password1 = valArray[3]['value'];
        var password2 = valArray[4]['value'];

        if (username == "")
        {
            alert("Account Name must be filled out");
            e.preventDefault();
            return false;
        }

        if (emailAddress == "")
        {
            alert("Email Address must be filled out");
            e.preventDefault();
            return false;
        }
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(String(emailAddress).toLowerCase()))
        {
            alert("Email Address is invalid");
            e.preventDefault();
            return false;
        }

        if (password1 == "")
        {
            alert("Password must be filled out");
            e.preventDefault();
            return false;
        }

        var v = validatePassword(password1);
        if (v != true)
        {
            alert(v);
            e.preventDefault();
            return false;
        }

        if (password2 == "")
        {
            alert("Confirmed password must be filled out");
            e.preventDefault();
            return false;
        }

        var v = validatePassword(password2);
        if (v != true)
        {
            alert(v);
            e.preventDefault();
            return false;
        }

        if (password1 != password2 )
        {
            alert("Password and confirmed password are not same.");
            e.preventDefault();
            return false;
        }

        var postData = $(this).serialize();
        $.ajax({
            url: formURL,
            type: "POST",
            data: postData,
            dataType: "json",
            success: function(data, textStatus, jqXHR) {
                if(data["status"] == "success")
                {
                    usrId = data.user.id;
                    usrName = data.user.username;
                    usrToken = data.user.accessToken;
                    usrType = data.user.userType;
                    usrEmail = data.user.emailAddress;
                    usrPassword = data.user.password;
                    controlUserButtons(true,usrType);
                    //lib.setCookie(tag_cookie_accessToken,data["accessToken"],30);
                }
                else
                {
                    alert("Update failed.\nReason: " + data["msg"]);
                }
            },
            error: function(jqXHR, status, error) {
                $('#error').html(jqXHR.responseText);
                console.log(status + ": " + error);

            }
        });
        e.preventDefault();
    });



    $("#buttonLogout").on('click',function () {
        var r = confirm("Are you sure you want to logout?");
        if (r == true) {
            lib.deleteCookie(tag_cookie_accessToken);
            $('#buttonLogin').show();
            $('#buttonSignup').show();
            $('#containerUsername').hide();
            $('#buttonLogout').hide();
        } else {
        }

    });

    // respond clicking login button
    $("#submit_login_form").on('click', function() {
        $("#login_form").submit();
    });

    // respond clicking sign up button
    $("#submit_signup_form").on('click', function() {
        $("#signup_form").submit();
    });

    // respond edit user button
    $("#submit_edit_user_form").on('click', function() {
        $("#edit_user_form").submit();
    });

    // on click user button
    $("#containerUsername").on('click', function() {
        var userToken = lib.getCookie(tag_cookie_accessToken);
        if (userToken != "") {
            var data = {"userToken" : userToken,
                "action" : "httpLoginByToken"};
            var postData = $.param(data);
            lib.http(formURL,POST,postData,function(data, textStatus, jqXHR) {
                if(data["status"] == "success")
                {
                    $('#edit_username').val(data.user.username);
                    $('#edit_email').val(data.user.emailAddress);
                    $('#edit_pwd').val(data.user.password);
                    $('#edit_pwd_confirm').val(data.user.password);
                    $("#editUserModal").modal('show');
                }
                else
                {
                    controlUserButtons(true,usrType);
                }
            },function(jqXHR, status, error) {
                console.log(status + ": " + error);
            });
        } else {
            controlUserButtons(false,usrType);
        }
    });

   $("#previousPage").on('click', function() {
       if (currentNewsPage > 1)
       {
           currentNewsPage -= 1;
           updateNewsList(currentNewsPage);
           console.log("previous currentNewsPage " + currentNewsPage);

       }

   });

   $("#nextPage").on('click', function() {
       if (currentNewsPage < maxNewsPage)
       {
           currentNewsPage += 1;
           updateNewsList(currentNewsPage);
           console.log("next currentNewsPage " + currentNewsPage);

       }

   });


    updateNewsList(currentNewsPage);
    console.log("currentNewsPage " + currentNewsPage);

});