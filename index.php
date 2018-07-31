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
<?php
include 'component.php';
echo $html_nav;
?>

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
</div>

<?php
include 'component.php';
echo $html_footer;
?>

</body>
</html>