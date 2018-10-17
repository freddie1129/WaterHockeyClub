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


<div class="container-fluid">
    <div class="container">
        <br/>
        <h4 style="margin-bottom: 20px;"><b>Please set some search conditions to search your interesting Teams. </b></h4>
        <?php
            require_once('libcommon.php');
            require_once ('constant.php');
            global $glbUserTypeAdmin;
            global $glbUserTypeEditor;
            global $glbUserTypeClient;
            if (isset($_GET["keywords"]))
            {
                $keywords = $_GET["keywords"];
            }
            else
            {
                $keywords="";
            }

            $searchFormat =  '
        <div class="input-group" style="margin-bottom: 10px">
            <input id="id_search_team_keywords" type="text" class="form-control" value="%s" placeholder="Input some keywords about your interesting team.">
            <span class="input-group-btn">
                    <button id="id_search_team" class="btn btn-default" type="button">Search</button>
            </span>
        </div>';
            echo sprintf($searchFormat,$keywords);
            $rowformat = '<tr valign="middle" >
                <td class="col-md-1  text-center" >%u</td>
                <td class="col-md-4  text-center"><a id="team_name_%u"  href="team.php?teamId=%u">%s</a></td>
                <td class="col-md-3  text-center">%s</td>
                <td class="col-md-2  text-center">%s</td>
                <td class="col-md-2  text-center">
                    <button id="button_edit_team_%u" type="button" class="btn btn-primary edit_team" data-toggle="modal">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                    <button id="button_delete_team_%u" type="button" class="btn btn-danger delete_team">Delete</button>
                    <input id="inputTeamId_%u" type="hidden" value="%u">
                    <input id="inputTeamName_%u" type="hidden" value="%s">
                    <input id="inputTeamLocation_%u" type="hidden" value="%s">
                    <input id="inputTeamTime_%u" type="hidden" value="%s">
                    <input id="inputTeamCaptionName_%u" type="hidden" value="%s">
                    <input id="inputTeamIntro_%u" type="hidden" value="%s">
                </td>
            </tr>';

            $teamList = dbSearchTeam($keywords);
            if (count($teamList) == 0)
            {
                echo  "<h4 style='margin-top: 20px; margin-bottom: 50px'><b>Sorry, No teams matches your search condition. Please try other keywords.</b></h4>";
            }
            else {
                echo '<table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="text-align: center">Index</th>
                                <th style="text-align: center">Team Name</th>
                                <th style="text-align: center">Club Location</th>
                                <th style="text-align: center">Establish Time</th>
                                 <th style="text-align: center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">';
                for ($i = 0; $i < count($teamList); $i++) {
                    $item = $teamList[$i];
                    $txt = sprintf($rowformat,
                        $i + 1,     //index
                        $item->id, $item->id, $item->name,  //team name
                        $item->location,    //team location
                        $item->establishTime, //establishTime
                        $item->id,
                        $item->id,
                        $item->id, $item->id,
                        $item->id, $item->name,
                        $item->id, $item->location,
                        $item->id, $item->establishTime,
                        $item->id, $item->captionName,
                        $item->id, $item->intro);
                    echo $txt;
                }
               echo  '</tbody> </table>';
            }
            ?>
        <button id="createNewTeam" type="button" class="btn btn-success" data-toggle="modal" style="margin-bottom: 20px; padding-top: 15px; padding-bottom: 15px;float: right;">&nbsp;&nbsp;&nbsp;&nbsp;<b>Create New Team</b> &nbsp;&nbsp;&nbsp;&nbsp;</button>
    </div>

    <div class="container">
        <!-- Edit News interface -->
        <div class="modal fade" id="editTeamModal" role="dialog">
            <div class="modal-dialog">
                <input type="hidden" name="action" value="httpSignup">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 id="edit_dialog_title" class="modal-title" style="text-align: center"><b>Edit News</b></h4>
                    </div>
                    <div class="modal-body">
                        <input id="modal_teamId" type="hidden" class="form-control" value="newNews">
                        <p><b>Team Name:</b></p>
                        <input id="modal_teamName" type="text" class="form-control">
                        <p style="margin-top: 10px;"><b>Team Location:</b></p>
                        <input id="modal_teamLocation" type="text" class="form-control">
                        <p style="margin-top: 10px;"><b>Establish Time:</b></p>
                        <input id="modal_teamEstablishTime" type="text" class="form-control">
                        <p style="margin-top: 10px;"><b>Caption Name:</b></p>
                        <input id="modal_teamCaptionName" type="text" class="form-control">
                        <p style="margin-top: 10px;"><b>Team Introduction:</b></p>
                        <textarea id="modal_teamIntroduction" type="text" class="form-control"
                                  rows="20"></textarea>

                    </div>
                    <div class="modal-footer">
                        <button id="update_editTeam" type="submit" class="btn btn-primary" >
                            &nbsp;&nbsp; Confirm &nbsp;&nbsp;
                        </button>
                        <button id="create_editTeam" type="submit" class="btn btn-primary" >
                            &nbsp;&nbsp; Post &nbsp;&nbsp;
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





<script src="js/team_mamage.js" type="module"></script>





</body>
</html>