<!DOCTYPE html>
<html lang="en">
<head>
    <title>Title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./css/index.css">

</head>
<body>


<div id="error"></div>
<!--navigation bar-->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!--
                        <a class="navbar-brand" href="#"> <img src="./img/logo.jpg" alt="HTML tutorial" style="width:42px;height:42px;border:0;"></a>
            -->
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Team</a></li>
                <li><a href="#">Match</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a hidden id="buttonLogin" href="#loginModal" data-toggle="modal" data-target="#loginModal" ><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Login</a></li>
                <li><a hidden id="buttonSignup" href="#signupModal" data-toggle="modal" data-target="#signupModal" ><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Sign up</a></li>
                <li><a hidden id="containerUsername" href="#"><span class="glyphicon glyphicon-user"></span> <span id="txUsername" >name</span></a></li>
                <li><a hidden id="buttonLogout" href="#loginModal" ><span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid text-center">
    <div class="container">
        <p>Type something in the input field to search the table for Username, EmailAddress or User Type last names or emails:</p>
        <input class="form-control" id="myInput" type="text" placeholder="Search..">
        <br>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>User Name</th>
                <th>Email Address</th>
                <th>User Type</th>
            </tr>
            </thead>
            <tbody id="myTable">
            <?php
            require_once('libcommon.php');
            include 'appConstant.php';
            global $glbUserTypeAdmin;
            global $glbUserTypeEditor;
            global $glbUserTypeClient;
            $userList = dbGetAllUsers();
//                                        <input type="radio" name="optradioType%u" checked>General

            $format = '<tr>
                <td>%s</td>
                <td>%s</td>
                <td><form class="changeUserType" >
                        <input type="hidden" name="action" value="%u">
                        <label class="radio-inline">
                            <input type="radio" name="optradioType%u" %s>General
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="optradioType%u" %s>Editor
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="optradioType%u" %s>Admin
                        </label>
                        <label class="radio-inline">
                            <button id="button_save_%u" class="btn btn-primary btn-xs save" data-dismiss="modal">Save</button>
                        </label>
                        <label class="radio-inline">
                            <button id="button_delete_%u" class="btn btn-primary btn-xs delete" data-dismiss="delete">delete</button>
                        </label>
                    </form></td>
            </tr>';


            for ($x = 0; $x < count($userList); $x++) {
                $user = $userList[$x];
                $userType = $user->userType;
                $tag = ["general"=>$userType == $glbUserTypeClient ? "checked" : "",
                    "editor"=>$userType == $glbUserTypeEditor ? "checked" : "",
                    "admin"=>$userType == $glbUserTypeAdmin ? "checked" : ""];
                echo sprintf($format,$user->username,$user->emailAddress,$user->id,$x,$tag['general'],
                    $x,$tag['editor'],
                    $x,$tag['admin'],
                    $x,
                    $x);
            }
            ?>



            </tbody>
        </table>


    </div>










</div>




<footer class="container-fluid text-center">
    <p>Power by Chen Zhu (u1098252)  u1098252@umail.usq.edu.au </p>
</footer>


<script>
    var tag_cookie_accessToken = "accessToken";
    var formURL = "httpAction.php";

    // Get a cookie
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    // Set a cookie
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    // Delete a cookie
    function deleteCookie(cname) {
        var d = new Date();
        d.setTime(d.getTime() + 100);
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + "" + ";" + expires + ";path=/";

    }

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


    /* must apply only after HTML has loaded */
    $(document).ready(function () {


            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });



        // Try to Auto Login
        var userToken = getCookie(tag_cookie_accessToken);
        //userToken = "chen_09e321dde9115b025face2764de73f40a5baf5cc62fda35690899dea15caf0fdc4aae37d0b5548cfe72a9454ff451f0c7061a2b61540c2ff3d7527c8329537404";
        if (userToken != "") {
            // validate the userToken in the cookie
            var data = {"userToken" : userToken,
                "action" : "httpLoginByToken"};
            //var postData = JSON.stringify(data);
            var postData = $.param(data);
            $.ajax({
                url: formURL,
                type: "POST",
                data: postData,
                dataType: "json",
                success: function(data, textStatus, jqXHR) {
                    if(data["status"] == "success")
                    {
                        $('#buttonLogin').hide();
                        $('#buttonSignup').hide();
                        $('#containerUsername').show();
                        $('#buttonLogout').show();
                        $('#txUsername').html(data["username"]);
                    }
                    else
                    {
                        $('#buttonLogin').show();
                        $('#buttonSignup').show();
                        $('#containerUsername').hide();
                        $('#buttonLogout').hide();
                    }
                },
                error: function(jqXHR, status, error) {
                    //  $('#error').html(jqXHR.responseText);
                    console.log(status + ": " + error);
                }
            });
        } else {
            $('#buttonLogin').show();
            $('#buttonSignup').show();
            $('#containerUsername').hide();
            $('#buttonLogout').hide();
        }


        // login
        $("#login_form").on("submit", function(e) {
            var postData = $(this).serialize();
            $.ajax({
                url: formURL,
                type: "POST",
                data: postData,
                dataType: "json",
                success: function(data, textStatus, jqXHR) {
                    if(data["status"] == "success")
                    {
                        $('#buttonLogin').hide();
                        $('#buttonSignup').hide();
                        $('#containerUsername').show();
                        $('#buttonLogout').show();
                        $('#txUsername').html(data["username"]);
                        setCookie(tag_cookie_accessToken,data["accessToken"],30);
                    }
                    else
                    {
                        alert("Login failed.\nReason: " + data["msg"]);
                    }
                },
                error: function(jqXHR, status, error) {
                    $('#error').html(jqXHR.responseText);
                    console.log(status + ": " + error);

                }
            });
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
                        $('#buttonLogin').hide();
                        $('#buttonSignup').hide();
                        $('#containerUsername').show();
                        $('#buttonLogout').show();
                        $('#txUsername').html(data["username"]);
                        setCookie(tag_cookie_accessToken,data["accessToken"],30);
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



        $("#buttonLogout").on('click',function () {
            var r = confirm("Are you sure you want to logout?");
            if (r == true) {
                deleteCookie(tag_cookie_accessToken);
                $('#buttonLogin').show();
                $('#buttonSignup').show();
                $('#containerUsername').hide();
                $('#buttonLogout').hide();
            } else {
            }

        });


        $("#submit_login_form").on('click', function() {
            $("#login_form").submit();
        });

        $("#submit_signup_form").on('click', function() {
            $("#signup_form").submit();
        });

        $(".save").on('click', function() {
            var useId = $(this).parent().parent().children().first().val();
            var txt;
            var r = confirm("Change user's type");
            if (r == true) {
                txt = "You pressed OK!";
            } else {
                txt = "You pressed Cancel!";
            }
        });

        $(".delete").on('click', function() {
            var useId = $(this).parent().parent().children().first().val();
            var txt;
            var r = true; // confirm("Delete this user");
            if (r == true) {
                var data = {"userId" : useId,
                    "action" : "httpDeleteUser"};
                var postData = $.param(data);
                $.ajax({
                    url: formURL,
                    type: "POST",
                    data: postData,
                    dataType: "json",
                    success: function(data, textStatus, jqXHR) {
                        if(data["status"] == "success")
                        {
                            //location.reload();
                        }
                        else
                        {

                        }
                    },
                    error: function(jqXHR, status, error) {
                        //  $('#error').html(jqXHR.responseText);
                        console.log(status + ": " + error);
                    }
                });


            } else {
                txt = "You pressed Cancel!";
            }
        });


    });


</script>





</body>
</html>