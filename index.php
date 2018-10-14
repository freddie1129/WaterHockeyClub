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
                <li><a hidden id="buttonUserManage" href="account_manage.php" ><span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Account Manage</a></li>

                <li><a hidden id="containerUsername" href="#editUserModal"><span class="glyphicon glyphicon-user"></span> <span id="txUsername" >name</span></a></li>

                <li><a hidden id="buttonLogout" href="#loginModal" ><span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout</a></li>
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
            <div id="newsList"></div>
            <div class="container">
                <button id="previousPage" type="button" class="btn btn-default">Previous</button>
                <button id="nextPage" type="button" class="btn btn-default">Next</button>
            </div>



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








    <div class="container">

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" role="dialog">
            <form id="login_form" name="httpLogin" class="js-ajax-php-json" action="action.php" method="post" accept-charset="utf-8">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">LogIn</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">Email address:</label>
                            <input id="login_username" type="email" class="form-control" id="email" name="username">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input id="login_password" type="password" class="form-control" id="pwd" name="password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="submit_login_form" type="submit" class="btn btn-default" data-dismiss="modal">Log in</button>
                    </div>
                </div>

            </div>
            </form>
        </div>

        <!-- Sign up Modal -->
        <div class="modal fade" id="signupModal" role="dialog">
            <form id="signup_form" name="httpLogin" class="js-ajax-php-json" action="action.php" method="post" accept-charset="utf-8">
            <div class="modal-dialog">
                <input type="hidden" name="action" value="httpSignup">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Sign up</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="sign_in_username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address:</label>
                            <input type="email" class="form-control" id="sign_in_email" name="emailAddress">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control" id="sign_in_pwd" name="password1">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Confirm Password:</label>
                            <input type="password" class="form-control" id="sign_in_pwd_confirm" name="password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="submit_signup_form" type="submit" class="btn btn-default" data-dismiss="modal">Sign Up</button>
                    </div>
                </div>
            </div>
            </form>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" role="dialog">
            <form id="edit_user_form" name="httpLogin" class="js-ajax-php-json" action="action.php" method="post" accept-charset="utf-8">
            <div class="modal-dialog">
                <input type="hidden" name="action" value="httpUpdateUserProfile">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Update user profile</h4>
                    </div>
                    <?php
                        require_once('libcommon.php');
                        $cookie_token = "accessToken";
                        if(!isset($_COOKIE[$cookie_token])) {
                            echo '<div class="modal-body">';
                            echo "<p>Something is wrong with your account, please logout and try again.</p>";
                            echo '</div>';
                        }
                        else
                        {
                            echo '<div class="modal-body">';
                            $user = dbGetUserByToken($_COOKIE[$cookie_token]);
                            $format = '<div class="modal-body">
                            <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="edit_username" name="username" value="%s" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email address:</label>
                            <input type="email" class="form-control" id="edit_email" name="emailAddress" value="%s">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Password:</label>
                            <input type="password" class="form-control" id="edit_pwd" name="password1" value="%s">
                        </div>
                        <div class="form-group">
                            <label for="pwd">Confirm Password:</label>
                            <input type="password" class="form-control" id="edit_pwd_confirm" name="password" value="%s">
                        </div>
                        <input type="hidden" name="userType" value="%s">

                        </div>
                        <div class="modal-footer">
                        <button id="submit_edit_user_form" type="submit" class="btn btn-default" data-dismiss="modal">Save</button>
                    </div>';
                            echo sprintf($format, $user->username, $user->emailAddress, $user->password,$user->password,$user->userType);
                            echo '</div>';
                        }
                    ?>
                </div>
            </div>
            </form>
        </div>

    </div>



</div>




<footer class="container-fluid text-center">
    <p>Power by Chen Zhu (u1098252)  u1098252@umail.usq.edu.au </p>
</footer>

<script src="js/index.js" type="module"></script>




</body>
</html>