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
        <h4 style="margin-bottom: 20px;"><b>Please set some search conditions to search your interesting News. </b></h4>
        <?php
            require_once('libcommon.php');
            require_once ('constant.php');
            global $glbUserTypeAdmin;
            global $glbUserTypeEditor;
            global $glbUserTypeClient;
            $keywords = $_GET["keywords"];
            $userId = $_GET["userId"];
            $startTime = $_GET["startTime"];
            $endTime = $_GET["endTime"];
            $searchFormat =  '<div style="margin-bottom: 10px">
            <b>Start Time:&nbsp; </b> <input id="search_news_start_date" type="date" value="%s"> &nbsp; &nbsp;
            <b>End   Time:&nbsp; </b> <input id="search_news_end_date" type="date" value="%s"><br>
        </div>

        <div class="input-group" style="margin-bottom: 10px">
            <input id="id_search_news_keywords" type="text" class="form-control" value="%s" placeholder="Input some keywords about news\'s title or content.">
            <span class="input-group-btn">
                    <button id="id_search_news" class="btn btn-default" type="button">Search</button>
            </span>
        </div>';
            echo sprintf($searchFormat,$startTime,$endTime, $keywords);
            $rowformat = '<tr valign="middle" >
                <td class="col-md-1  text-center" ><p>%u</p></td>
                <td class="col-md-1  text-center">%s</td>
                <td class="col-md-6  text-center"><a id="news_title_%u"  href="newspage.php?newId=%u&userId=%u">%s</a></td>
                <td class="col-md-2  text-center">%s</td>
                <td class="col-md-2  text-center">
                    <button id="button_edit_news_%u" type="button" class="btn btn-primary edit_news" data-toggle="modal">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                    <button id="button_delete_news_%u" type="button" class="btn btn-danger delete_news">Delete</button>
                    <input id="inputNewsId_%u" type="hidden" value="%u">
                    <input id="inputNewsTitle_%u" type="hidden" value="%s">
                    <input id="inputNewsContent_%u" type="hidden" value="%s">
                </td>
            </tr>';

            $newsList = dbSearchNews($keywords,$startTime,$endTime);
            if (count($newsList) == 0)
            {
                echo  "<h4 style='margin-top: 20px; margin-bottom: 50px'><b>Sorry, No news matches your search condition. Please try other conditions.</b></h4>";
            }
            else {
                echo '<table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="text-align: center">Index</th>
                                <th style="text-align: center">Author</th>
                                <th style="text-align: center">Title</th>
                                <th style="text-align: center">Time</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">';
                for ($i = 0; $i < count($newsList); $i++) {
                    $item = $newsList[$i];
                    $txt = sprintf($rowformat, $i, $item->author, $item->id, $item->id, $userId, $item->title, $item->time, $item->id, $item->id,
                        $item->id, $item->id,
                        $item->id, $item->title,
                        $item->id, $item->content);
                    echo $txt;
                }
               echo  '</tbody> </table>';
            }
            ?>
        <button id="createNewNews" type="button" class="btn btn-success" data-toggle="modal" style="margin-bottom: 20px; padding-top: 15px; padding-bottom: 15px;float: right;">&nbsp;&nbsp;&nbsp;&nbsp;<b>Post a News</b> &nbsp;&nbsp;&nbsp;&nbsp;</button>


    </div>

    <div class="container">
        <!-- Edit News interface -->
        <div class="modal fade" id="editNewsModal" role="dialog">
            <div class="modal-dialog">
                <input type="hidden" name="action" value="httpSignup">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 id="edit_dialog_title" class="modal-title" style="text-align: center"><b>Edit News</b></h4>
                    </div>
                    <div class="modal-body">
                        <input id="modal_newsId" type="hidden" class="form-control" value="newNews">
                        <p for="newsTitle"><b>Title:</b></p>
                        <input id="modal_newsTitle" type="text" class="form-control" name="newsTitle">
                        <p for="newsContent" style="margin-top: 10px;"><b>Content:</b></p>
                        <textarea id="modal_newsContent" type="text" class="form-control" name="newsContent"
                                  rows="20"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button id="update_editNews" type="submit" class="btn btn-primary" data-dismiss="modal">
                            &nbsp;&nbsp; Confirm &nbsp;&nbsp;
                        </button>
                        <button id="create_editNews" type="submit" class="btn btn-primary" data-dismiss="modal">
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





<script src="js/search_news_result.js" type="module"></script>





</body>
</html>