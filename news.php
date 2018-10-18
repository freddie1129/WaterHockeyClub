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
                <li><a href="index.php">Home</a></li>
                <li class="active"><a href="search_news_result.php" target="_blank">News</a></li>
                <li><a href="team_manage.php" target="_blank">Team</a></li>
                <li><a href="match_manage.php" target="_blank">Match</a></li>
            </ul>

        </div>
    </div>
</nav>

<div class="container-fluid text-center">
    <div class="container">


            <?php
            $current_id = $_GET["id"];
            $id = "";
            $title = "";
            $date = "";
            $content = "";


            if (($handle = fopen("news.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $id = $data[0];
                    $date = $data[1];
                    $title = $data[2];
                    $content = $data[3];
                    if ($id == $current_id)
                    {
                        break;
                    }
                }
                echo sprintf("<h2 class=\"text-center\">%s</h2>", $title);
                echo sprintf("<p class=\"text-center\">%s</p>", $date);
                echo sprintf("<p>%s</p>", $content);
                fclose($handle);
            }
            ?>


        </div>

    </div>
</div>

<!--footer-->
<?php
include 'component.php';
echo $html_footer;
?>

</body>
</html>