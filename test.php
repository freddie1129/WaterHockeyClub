<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/09/18
 * Time: 12:56 AM
 */
include_once 'libcommon.php';
include_once 'User.php';
include 'constant.php';

global $glbDbName;

$db = new SQLite3('$glbDbName', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

$now = date('Y-m-d H:i:s');
echo $now;
echo "<br>";

$newDate = date("Y-m-d", strtotime($now));

echo $newDate;

$news = dbSearchNews("","2018-09-01", $newDate);


echo "<p>Print News</p>";
for ($i = 0;$i < count($news); $i++)
{
    echo $news[$i];
}

?>