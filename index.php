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








<?php

if (array_key_exists('emailAddress', $_POST)) {
    echo $_POST["emailAddress"];
}

/*if ($_POST["emailAddress"])
{
    echo $_POST["emailAddress"];

}*/

/*if ($_POST["password"])
{

echo $_POST["emailAddress"];

}*/
?>






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
                <li><a hidden id="buttonLogin" href="#myModal" data-toggle="modal" data-target="#myModal" ><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Login</a></li>
                <li><a hidden id="buttonSignup" href="#myModal" data-toggle="modal" data-target="#myModal" ><span class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Sign up</a></li>
                <li><a hidden id="containerUsername" href="#"><span class="glyphicon glyphicon-user"></span> <span id="txUsername" >name</span></a></li>
                <li><a hidden id="buttonLogout" href="#myModal" ><span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid text-center">
    <div class="row content">

        <div class="col-sm-8 text-left">
            <h1>Welcome</h1>
            <p><?php
                $myfile = fopen("intro.txt", "r") or die("Unable to open file!");
                $s = fread($myfile,filesize("intro.txt"));
                echo $s;
                fclose($myfile);
                ?></p>
            <hr>
            <h3>News</h3>

            <?php
            $row = 1;
            if (($handle = fopen("news.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $id = $data[0];
                    $headline = $data[2];
                    $date = $data[1];
                    $txt = sprintf("<p style=\"text-align:left;\"><a href=\"news.php?id=%s\">%s</a>
                    <span style=\"float:right;\">%s</span></p>", $id,$headline,$date);
                    echo $txt;
                }
                fclose($handle);
            }
            ?>
        </div>
        <div class="col-sm-4 sidenav">
            <?php
            $row = 1;
            if (($handle = fopen("match.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $id = $data[0];
                    $team1 = $data[1];
                    $team2 = $data[2];
                    $time = $data[3];
                    $location = $data[4];
                    echo "<div class=\"well\">\n";
                    echo  sprintf("<p>%s <span class=\"vs\">VS</span> %s</p>\n",$team1,$team2);
                    echo  sprintf("<p>%s</p>\n",$time);
                    echo  sprintf( "<p>Location: %s</p>\n",$location);
                    echo "</div>\n";
                    $row++;
                    if ($row > 3)
                        break;
                }
                fclose($handle);
            }
            ?>
        </div>
    </div>


    <div id="error">
    </div>

    <button onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</button>



    <!--<div id="id01" class="modal">
    <div class="wrapper">
        <form class="form-signin">
            <h2 class="form-signin-heading">Please login</h2>
            <input type="text" class="form-control" name="username" placeholder="Email Address" required="" autofocus="" />
            <input type="password" class="form-control" name="password" placeholder="Password" required=""/>
            <label class="checkbox">
                <input type="checkbox" value="remember-me" id="rememberMe" name="rememberMe"> Remember me
            </label>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        </form>
    </div>
    </div>-->
    <!-- Modal -->
    <div class="container">
        <h2>Modal Example</h2>
        <!-- Trigger the modal with a button -->
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <form id="login_form" name="httpLogin" class="js-ajax-php-json" action="httpAction.php" method="post" accept-charset="utf-8"">
            <div class="modal-dialog">
                <input type="hidden" name="action" value="httpLogin">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">LogIn</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">Email address:</label>
                            <input type="email" class="form-control" id="email" name="username">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control" id="pwd" name="password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="submit_login_form" type="submit" class="btn btn-default" data-dismiss="modal">Log in</button>
                    </div>
                </div>

            </div>
            </form>
        </div>

    </div>



</div>




<?php
include 'component.php';
echo $html_footer;
?>




<!--<div id="id01" class="modal">

    <form class="modal-content animate" action="/action_page.php">
        <div class="container">
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="uname" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>

            <button type="submit">Login</button>
            <label>
                <input type="checkbox" checked="checked" name="remember"> Remember me
            </label>
        </div>

        <div class="container" style="background-color:#f1f1f1">
            <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
            <span class="psw">Forgot <a href="#">password?</a></span>
        </div>
    </form>
</div>-->



<li><a id="txUsername" href="#"><span class="glyphicon glyphicon-user"></span> name</a></li>
<li><a id="buttonLogout" href="#myModal" data-toggle="modal" data-target="#myModal" ><span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout</a></li>

    <script>


        var tag_cookie_accessToken = "accessToken";
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

        function deleteCookie(cname) {
            var d = new Date();
            d.setTime(d.getTime() + 100);
            var expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + "" + ";" + expires + ";path=/";

        }


    /* must apply only after HTML has loaded */


    $(document).ready(function () {



        var userToken = getCookie(tag_cookie_accessToken);

        var formURL = "httpAction.php";

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



        $("#login_form").on("submit", function(e) {
            var postData = $(this).serializeArray();
            var postData = $(this).serialize();
            var formURL = $(this).attr("action");

            var data = {
                "action": "test"
            };
            data = postData;
            $.ajax({
                url: formURL,
                type: "POST",
                data: data,
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
    });


</script>





</body>
</html>