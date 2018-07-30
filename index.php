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
    <style>
        /* Remove the navbar's default margin-bottom and rounded borders */
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
        }

        /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 450px}

        /* Set gray background color and 100% height */
        .sidenav {
            padding-top: 20px;
            background-color: #f1f1f1;
            height: 100%;
        }

        /* Set black background color, white text and some padding */
        footer {
            background-color: #555;
            color: white;
            padding: 15px;
        }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
            .sidenav {
                height: auto;
                padding: 15px;
            }
            .row.content {height:auto;}
        }
        .vs {
            font-size: 40px;
        }
    </style>

</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Logo</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Team</a></li>
                <li><a href="#">Match</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid text-center">
    <div class="row content">

        <div class="col-sm-8 text-left">
            <h1>Welcome</h1>
            <p><?php
                $myfile = fopen("intro", "r") or die("Unable to open file!");
                $s = fread($myfile,filesize("intro"));
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
</div>

<footer class="container-fluid text-center">
    <p>Footer Text</p>
</footer>

</body>
</html>