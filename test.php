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
global $glbMatchStatusInProgress;
global $glbMatchStatusHaveDone;
global $glbMatchStatusInComing;



echo "<h3>Insert some Testing Matches</h3>";
$match = new Match(0,"2018-10-18T10:30","beijing", 12,6,"d",3,6);
dbInsertMatch($match);



?>