<?php include 'appConstant.php';?>
<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/09/18
 * Time: 11:49 PM
 */

include 'appConstant.php';
require_once('User.php');


// Insert a user
function insertUser($username,$password,$emailAddress) {
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('INSERT INTO "visits" ("username", "password", "emailAddress","type",""time")
    VALUES (:username, :password, :emailAddress, :time)');
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':emailAddress', $emailAddress);
    $statement->bindValue(':time', date('Y-m-d H:i:s'));
    $statement->execute(); // you can reuse the statement with different values
    $db->close();
}


// Insert a user
function insertUser1($user) {
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('INSERT INTO "user" ("username", "password", "emailAddress","type","time")
    VALUES (:username, :password, :emailAddress,:userType, :time)');
    $statement->bindValue(':username', $user->username);
    $statement->bindValue(':password', $user->password);
    $statement->bindValue(':emailAddress', $user->emailAddress);
    $statement->bindValue(':userType', $user->userType);
    $statement->bindValue(':time', date('Y-m-d H:i:s'));
    $statement->execute(); // you can reuse the statement with different values
    $db->close();
}

// get users
function getAllUsers()
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "user"');
    $result = $statement->execute();
    $userList = array();
    while($row = $result->fetchArray()) {
        $user = new User($row["id"],$row["username"],$row["password"],$row["emailAddress"],$row["type"],$row["time"]);
        array_push($userList,$user);
    }
    $db->close();
    return $userList;
}




?>