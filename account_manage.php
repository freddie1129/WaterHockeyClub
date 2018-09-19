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
                <li><a hidden id="buttonLogout" href="index.php" ><span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout</a></li>
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
                <th>Id</th>
                <th>User Name</th>
                <th>Email Address</th>
                <th>User Type</th>
            </tr>
            </thead>
            <tbody id="myTable">
            <?php
            require_once('libcommon.php');
            include 'constant.php';
            global $glbUserTypeAdmin;
            global $glbUserTypeEditor;
            global $glbUserTypeClient;
            $userList = dbGetAllUsers();
            $format = '<tr>
                <td>%u</td>
                <td>%s</td>
                <td>%s</td>
                <td>
                        <input type="hidden" name="action" value="%u">
                        <label class="radio-inline">
                            <input type="radio" name="optradioType%u" value="client" %s>General
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="optradioType%u" value="editor" %s>Editor
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="optradioType%u" value="admin" %s>Admin
                        </label>
                        <label class="radio-inline">
                            <button id="button_save_%u" class="btn btn-primary btn-xs save" data-dismiss="modal" %s>Save</button>
                        </label>
                        <label class="radio-inline">
                            <button id="button_delete_%u" class="btn btn-primary btn-xs delete" data-dismiss="delete" %s>delete</button>
                        </label>
                </td>
            </tr>';


            for ($x = 0; $x < count($userList); $x++) {
                $user = $userList[$x];
                $userType = $user->userType;

                $tag = ["general"=>$userType == $glbUserTypeClient ? "checked" : "",
                    "editor"=>$userType == $glbUserTypeEditor ? "checked" : "",
                    "admin"=>$userType == $glbUserTypeAdmin ? "checked" : ""];
                if ($userType == $glbUserTypeAdmin)
                {
                    $tag['general'] .= " disabled";
                    $tag['editor'] .= " disabled";

                }
                $tag['admin'] .= " disabled";
                echo sprintf($format,$user->id,$user->username,$user->emailAddress,$user->id,$user->id,$tag['general'],
                    $user->id,$tag['editor'],
                    $user->id,$tag['admin'],
                    $user->id,$userType == $glbUserTypeAdmin ? "disabled" : "enabled",
                    $user->id,$userType == $glbUserTypeAdmin ? "disabled" : "enabled");
            }
            ?>



            </tbody>
        </table>


    </div>










</div>




<footer class="container-fluid text-center">
    <p>Power by Chen Zhu (u1098252)  u1098252@umail.usq.edu.au </p>
</footer>


<script src="js/account_manage.js" type="module"></script>





</body>
</html>