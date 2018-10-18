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


<div class="container-fluid">
    <div class="container">
        <br/>
        <?php
            require_once('libcommon.php');
            require_once ('constant.php');
            global $glbUserTypeAdmin;
            global $glbUserTypeEditor;
            global $glbUserTypeClient;


            if (isset($_GET["teamId"]))
            {
                $teamId = $_GET["teamId"];
            }
            if (isset($_COOKIE["userId"]))
            {
                $showAction = false;
                $userId = $_COOKIE["userId"];
                $user = dbGetUserById($userId);
                if ($user != false) {
                    if ($user->userType == $glbUserTypeAdmin | $user->userType == $glbUserTypeEditor) {
                        $showAction = true;
                    }
                }
            }
            else
            {
                $showAction = false;
            }

        if ($showAction == true) {
            echo '<button id="createNewMatch" type="button" class="btn btn-success" data-toggle="modal" style="margin-bottom: 20px; padding-top: 15px; padding-bottom: 15px; float: right;">&nbsp;&nbsp;&nbsp;&nbsp;<b>Add a new match</b> &nbsp;&nbsp;&nbsp;&nbsp;</button>
';
        }
            $matchList = dbGetAllMatch();
            for ($i=0; $i < count($matchList); $i++)
            {
                $match = $matchList[$i];
                $teamA = dbGetTeamById($match->teamA);
                $teamB = dbGetTeamById($match->teamB);
                echo sprintf(' <input id="input_match_id_%u" type="hidden" value="%u">', $match->id,$match->id);
                echo sprintf(' <input id="input_match_time_%u" type="hidden" value="%s">', $match->id,$match->time);
                echo sprintf(' <input id="input_match_location_%u" type="hidden" value="%s">', $match->id,$match->location);
                echo sprintf(' <input id="input_match_teamA_%u" type="hidden" value="%u">', $match->id,$match->teamA);
                echo sprintf(' <input id="input_match_teamB_%u" type="hidden" value="%u">', $match->id,$match->teamB);
                echo sprintf(' <input id="input_match_status_%u" type="hidden" value="%s">', $match->id,$match->status);
                echo sprintf(' <input id="input_match_scoreA_%u" type="hidden" value="%u">', $match->id,$match->scoreA);
                echo sprintf(' <input id="input_match_scoreB_%u" type="hidden" value="%u">', $match->id,$match->scoreB);
                echo sprintf(' <input id="input_match_teamA_name_%u" type="hidden" value="%s">', $match->id,$teamA->name);
                echo sprintf(' <input id="input_match_teamB_name_%u" type="hidden" value="%s">', $match->id,$teamB->name);
                $actionButton = "";

                echo sprintf('<div class="row well clear_flow">
                    <div class="col-sm-4"><b>%s</b></div>
                    <div class="col-sm-4"><p class="scoreLabel">Score</p><p style="text-align: center">
                    <a  href="team_detail.php?teamId=%u"><b class="matchTeam">%s</b></a>
                    
                    &nbsp;&nbsp;&nbsp;&nbsp;<b>%u &ndash;  %u</b>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a  href="team_detail.php?teamId=%u"><b class="matchTeam">%s</b></a>
                    <p class="statusLabel">%s</p>
                    </div>
                    <div class="col-sm-4"><p style="text-align: right">%s</p></div>
                </div>',$match->time,
                    $teamA->id, $teamA->name,
                    $match->scoreA,$match->scoreB,
                    $teamB->id, $teamB->name,
                    $match->status,
                    $match->location);
                if ($showAction == true)
                {
                    echo '<div style="margin-bottom: 20px">';
                    echo sprintf('<button id="button_edit_match_%u" type="button" class="btn btn-primary edit_match" data-toggle="modal">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>',$match->id);
                    echo sprintf('<button id="button_delete_match_%u" type="button" class="btn btn-danger delete_match" data-toggle="modal">Delete</button>',$match->id);
                    echo '</div>';

                }

            }






            ?>
    </div>

    <div class="container">
        <!-- Edit News interface -->
        <div class="modal fade" id="editMemberModal" role="dialog">
            <div class="modal-dialog">
                <input type="hidden" name="action" value="httpSignup">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 id="edit_dialog_title" class="modal-title" style="text-align: center"><b>Edit News</b></h4>
                    </div>
                    <div class="modal-body">
                        <input id="modal_match_id" type="hidden" class="form-control" value="newNews">
                        <p><b>Match Time:</b></p>
                        <input id="modal_time" type="datetime-local" class="form-control">
                        <p style="margin-top: 10px;"><b>Location:</b></p>
                        <input id="modal_location" type="text" class="form-control">
                        <?php
                            require_once('libcommon.php');
                            $teams = dbGetAllTeams();
                            echo '<p style="margin-top: 10px;"><b>Home Team:</b></p>';
                            echo '<select class="form-control" id="sel_teamA">';
                            for ($i = 0; $i < count($teams); $i++)
                                {
                                    $t = $teams[$i];
                                    echo sprintf("<option>(%u). %s</option>",$t->id, $t->name);
                                }
                            echo '</select>';

                            echo '<p style="margin-top: 10px;"><b>Visiting Team:</b></p>';
                            echo '<select class="form-control" id="sel_teamB">';
                            for ($i = 0; $i < count($teams); $i++)
                            {
                                $t = $teams[$i];
                                echo sprintf("<option>(%u). %s</option>",$t->id, $t->name);
                            }
                            echo '</select>';
                        ?>
                        <p style="margin-top: 10px;"><b>Status:</b></p>
                        <input id="modal_status" type="text" class="form-control">

                        <p style="margin-top: 10px;"><b>Home Team Score:</b></p>
                        <input id="modal_scoreA" type="number" name="quantity" min="0" max="500">

                        <p style="margin-top: 10px;"><b>Visiting Team Score:</b></p>
                        <input id="modal_scoreB" type="number" name="quantity" min="0" max="500">
                    </div>
                    <div class="modal-footer">
                        <button id="modal_update" type="submit" class="btn btn-primary" >
                            &nbsp;&nbsp; Confirm &nbsp;&nbsp;
                        </button>
                        <button id="modal_create" type="submit" class="btn btn-primary" >
                            &nbsp;&nbsp; Create &nbsp;&nbsp;
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>




<footer class="container-fluid text-center">
    <p>Power by Chen Zhu (u1098252)  u1098252@umail.usq.edu.au </p>
</footer>





<script src="js/match_mamage.js" type="module"></script>





</body>
</html>