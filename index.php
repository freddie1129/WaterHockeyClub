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
    <style>
        .matchTeam{
            color: cornflowerblue;
            font-size: larger;
            text-align: center;
            margin-top: 5px;
        }
        .scoreLabel{
            text-align: center;
            font-weight: bold;
        }

        .statusLabel{
            text-align: center;
            font-weight: bold;
            margin-top: 5px;
        }
        .edit_match{
            float: left;
            margin-right: 10px;
        }

        .clear_flow{
            clear: both;
        }
    </style>
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
                <li><a href="search_news_result.php" target="_blank">News</a></li>
                <li><a href="team_manage.php" target="_blank">Team</a></li>
                <li><a href="match_manage.php" target="_blank">Match</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a hidden id="buttonLogin" href="#loginModal" data-toggle="modal" data-target="#loginModal"><span
                                class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Login</a></li>
                <li><a hidden id="buttonSignup" href="#signupModal" data-toggle="modal" data-target="#signupModal"><span
                                class="glyphicon glyphicon-log-in"></span>&nbsp;&nbsp;Sign up</a></li>
                <li><a hidden id="buttonUserManage" href="account_manage.php"><span
                                class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Account Manage</a></li>

                <li><a hidden id="containerUsername" href="#editUserModal"><span
                                class="glyphicon glyphicon-user"></span> <span id="txUsername">name</span></a></li>

                <li><a hidden id="buttonLogout" href="#loginModal"><span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid text-center">
    <div class="row content">

        <div class="col-sm-8 text-left">
            <h1><b>Welcome</b></h1>
            <p><?php
                $myfile = fopen("intro.txt", "r") or die("Unable to open file!");
                $s = fread($myfile, filesize("intro.txt"));
                echo $s;
                fclose($myfile);
                ?></p>
            <hr>
            <h3><b>News</b></h3>

            <div class="input-group" style="margin-bottom: 20px">
                <input id="id_search_keywords" type="text" class="form-control" placeholder="Input some keywords about news's title or content. ">

                <span class="input-group-btn">
                    <a id="id_link_search"></a>
                    <button id="id_search_news" class="btn btn-default" type="button">Search</button>
                </span>
            </div>


            <div id="newsList" style="margin-bottom: 20px"></div>
            <!--div class="container"-->
                <!--button id="createNewNews" type="button" class="btn btn-primary">New</button-->

                <button id="nextPage" type="button" class="btn btn-primary" style="float: right; margin-left: 10px">&nbsp;&nbsp;&nbsp;Next&nbsp;&nbsp;&nbsp;</button>
                <button id="previousPage" type="button" class="btn btn-primary" style="float: right" >Previous</button>
            <!--/div-->



        </div>
        <div class="col-sm-4 sidenav">
            <?php
            require_once('libcommon.php');
            $matchList = dbGetAllMatch();
            $max = 4;
            $showNum = count($matchList) < $max ? count($matchList) : $max;
            for ($index = 0; $index < $showNum; $index++)
                {
                    $match = $matchList[$index];
                    $teamA = dbGetTeamById($match->teamA);
                    $teamB = dbGetTeamById($match->teamB);
                    echo "<div class=\"well\">\n";
                    echo sprintf("<p>
                                <a href=\"team_detail.php?teamId=%u\" target=\"_blank\"><b class='matchTeam'>%s</b></a>
                                 <b>&nbsp;&nbsp;&nbsp;&nbsp;%u &ndash; %u&nbsp;&nbsp;&nbsp;&nbsp;</b>
                                   <a  href=\"team_detail.php?teamId=%u\" target=\"_blank\"><b class='matchTeam'>%s</b></a>
                                   </p>", $match->teamA, $teamA->name, $match->scoreA, $match->scoreB, $match->teamB, $teamB->name);
                    echo sprintf("<p>%s</p>\n", $match->time);
                    echo sprintf("<p><b>%s</b></p>\n", $match->location);
                    echo sprintf("<p>%s</p>\n", $match->status);
                    echo "</div>\n";
                }

                echo '<a  href="match_manage.php" target="_blank">See more</a>';
            ?>

        </div>
    </div>


    <div class="container">

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" role="dialog">
            <form id="login_form" name="httpLogin" class="js-ajax-php-json" action="action.php" method="post"
                  accept-charset="utf-8">
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
                                <input id="login_password" type="password" class="form-control" id="pwd"
                                       name="password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="submit_login_form" type="submit" class="btn btn-default" data-dismiss="modal">
                                Log in
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <!-- Sign up Modal -->
        <div class="modal fade" id="signupModal" role="dialog">
            <form id="signup_form" name="httpLogin" class="js-ajax-php-json" action="action.php" method="post"
                  accept-charset="utf-8">
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
                            <button id="submit_signup_form" type="submit" class="btn btn-default" data-dismiss="modal">
                                Sign Up
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Edit News User Modal -->
        <div class="modal fade" id="editNewsModal" role="dialog">

            <div class="modal-dialog">
                <input type="hidden" name="action" value="httpSignup">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit News</h4>
                    </div>
                    <div class="modal-body">
                        <input id="modal_newsId" type="hidden" class="form-control" value="newNews">
                        <p for="newsTitle">Title:</p>
                        <input id="modal_newsTitle" type="text" class="form-control" name="newsTitle">
                        <p for="newsContent">Content:</p>
                        <textarea id="modal_newsContent" type="text" class="form-control" name="newsContent"
                                  rows="20"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button id="update_editNews" type="submit" class="btn btn-default" data-dismiss="modal">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>

        </div>


        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" role="dialog">
            <form id="edit_user_form" name="httpLogin" class="js-ajax-php-json" action="action.php" method="post"
                  accept-charset="utf-8">
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
                        if (!isset($_COOKIE[$cookie_token])) {
                            echo '<div class="modal-body">';
                            echo "<p>Something is wrong with your account, please logout and try again.</p>";
                            echo '</div>';
                        } else {
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
                            echo sprintf($format, $user->username, $user->emailAddress, $user->password, $user->password, $user->userType);
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </form>
        </div>

    </div>


</div>


<footer class="container-fluid text-center" style="margin-top: 20px">
    <p>Power by Chen Zhu (u1098252) u1098252@umail.usq.edu.au </p>
</footer>

<script src="js/index.js" type="module"></script>


</body>
</html>