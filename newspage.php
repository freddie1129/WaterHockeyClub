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
        p.comment {
            border-style: solid;
            border-color: green;
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
                <li><a href="index.php">Home</a></li>
                <li class="active"><a href="search_news_result.php" target="_blank">News</a></li>
                <li ><a href="team_manage.php" target="_blank">Team</a></li>
                <li><a href="match_manage.php" target="_blank">Match</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid">
    <div class="container">



            <?php
            require_once('libcommon.php');
            require_once('constant.php');
            $newId = $_GET["newId"];

            $showAction = false;
            if (isset($_COOKIE["userId"]))
            {
                $showAction = false;
                $userId = $_COOKIE["userId"];
                //echo sprintf('<h1>%u</h1>',$userId);
                $user = dbGetUserById($userId);
                if ($user != false) {
                        $showAction = true;
                }
            }
            else
            {
                $showAction = false;
            }


            $news = dbGetNewsById($newId);
            echo sprintf("<div id=\"news\">");
            echo sprintf("<h1 style=\"text-align:center;\">%s</h1>",$news->title);
            echo sprintf("<p style=\"text-align:center;\">%s</p>",$news->time);
            echo sprintf("<p>%s</p>",$news->content);
            echo sprintf("<p style=\"text-align:right;\">(Editor:%s)</p>",$news->author);
            echo sprintf("</div>");
            echo sprintf("<div id=\"comments\">");
            echo sprintf("<h3 style=\"text-align:left;\">Comment</h3>");

            if ($showAction) {
                echo sprintf('<input id="news_id" type="hidden" name="action" value="%u">', $newId);
                echo '<div style="width:100%;"><textarea id="edit_news_comment" type="text" style="width:100%;" rows="5"></textarea></div>';
                echo '<p align="right" style="font-size: 10px;">(Max character 1000)</p>';
                echo '<div align="right" style="margin-top: 5px; margin-bottom: 10px;">';
                echo '<button id="add_button_comment" align="right" type="button" class="btn btn-primary" data-toggle="modal" >Comment</button>';
            }
            echo '</div>';

            echo '<hr/>';

            $commentList = dbGetCommentByNewsId($newId);
            for ($index = 0; $index < count($commentList); $index++)
            {
                $comment = $commentList[$index];
                $user = dbGetUserById($comment->userId);
                $format = '<p style="text-align:left; font-weight: bold; margin-bottom: 1px;">%s<span style="float:right; font-weight: normal;">%s</span></p><p style="margin-bottom: 20px;">%s</p>';
                echo sprintf($format,$user->username,$comment->time,$comment->content);
            }
            echo sprintf("</div>");

            ?>





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
                            <h4 class="modal-title">Sign up</h4>
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