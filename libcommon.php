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




// Insert a user into user table
function dbInsertUser($user) {
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('INSERT INTO "user" ("username", "password", "emailAddress","accessToken", "userType","time")
    VALUES (:username, :password, :emailAddress, :accessToken, :userType, :time)');
    $statement->bindValue(':username', $user->username);
    $statement->bindValue(':password', $user->password);
    $statement->bindValue(':accessToken', $user->accessToken);
    $statement->bindValue(':emailAddress', $user->emailAddress);
    $statement->bindValue(':userType', $user->userType);
    $statement->bindValue(':time', date('Y-m-d H:i:s'));
    $statement->execute();
    $db->close();
}

// select all users for the user table
function dbGetAllUsers()
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "user"');
    $result = $statement->execute();
    $userList = array();
    while($row = $result->fetchArray()) {
        $user = new User($row["id"],$row["username"],$row["password"],$row["emailAddress"],$row["accessToken"],$row["userType"],$row["time"]);
        array_push($userList,$user);
    }
    $db->close();
    return $userList;
}

// check if it is a valid user
function dbIsValidUser($username, $password)
{
    $user = dbGetUserByUsername($username);
    if ($user != false)
    {
        if ($user->password == $password)
        {
            return $user;
        }
        else
        {
            return "Username or password is not correct.";
        }
    }
    else
    {
        return "This user is not existed.";
    }
}


// check if it is a valid user
function dbIsValidUserByToken($userToken)
{
    $user = dbGetUserByToken($userToken);
    if ($user != false)
    {
        return true;
    }
    else
    {
        return false;
    }
}


function dbGenerateAccessToken($username)
{
    $length = 64;
    return $username.bin2hex(random_bytes($length));
}

// generate an access token for a user, and update the token in the db
function dbUpdateAccessToken($username)
{
    $length = 64;
    $user =  dbGetUserByUsername($username);
    if (!$user)
    {
        $user->accessToken = $username + bin2hex(random_bytes($length));
        dbUpdateUser($user);
        return true;
    }
    else
    {
        return false;
    }
}

// query a user from user table by id
function dbGetUserById($id)
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "user" WHERE "id" = :id');
    $statement->bindValue(':id', $id);
    $result = $statement->execute();
    if ($row = $result->fetchArray())
    {
        $user = new User($row["id"],$row["username"],$row["password"],$row["emailAddress"],$row["accessToken"],$row["userType"],$row["time"]);
        $db->close();
        return $user;
    }
    else
    {
        $db->close();
        return false;
    }
}


// query a user from user table by id
function dbGetUserByUsername($username)
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "user" WHERE "username" = :username');
    $statement->bindValue(':username', $username);
    $result = $statement->execute();
    if ($row = $result->fetchArray())
    {
        $user = new User($row["id"],$row["username"],$row["password"],$row["emailAddress"],$row["accessToken"],$row["userType"],$row["time"]);
        $db->close();
        return $user;
    }
    else
    {
        $db->close();
        return false;
    }
}

// query a user from user table by id
function dbGetUserByToken($userToken)
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "user" WHERE "accessToken" = :accessToken');
    $statement->bindValue(':accessToken', $userToken);
    $result = $statement->execute();
    if ($row = $result->fetchArray())
    {
        $user = new User($row["id"],$row["username"],$row["password"],$row["emailAddress"],$row["accessToken"],$row["userType"],$row["time"]);
        $db->close();
        return $user;
    }
    else
    {
        $db->close();
        return false;
    }
}




// update a user
function dbUpdateUser($user)
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('UPDATE user SET username = :username,
                                                password = :password,
                                                emailAddress = :emailAddress,
                                                accessToken = :accessToken,
                                                userType = :userType,
                                                time = :time
                                                WHERE id = :id');
    $statement->bindValue(':username', $user->username);
    $statement->bindValue(':password', $user->password);
    $statement->bindValue(':emailAddress', $user->emailAddress);
    $statement->bindValue(':accessToken', $user->accessToken);
    $statement->bindValue(':userType', $user->userType);
    $statement->bindValue(':time', date('Y-m-d H:i:s'));
    $statement->bindValue(':id', $user->id);
    $statement->execute();
    $db->close();
}

// delete a user by id
function dbDeleteUser($id)
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('DELETE FROM user WHERE id = :id');
    $statement->bindValue(':id', $id);
    $statement->execute();
    $db->close();
}

// delete all user from user table
function dbDeleteAllUser()
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('DELETE FROM user');
    $statement->execute();
    $db->close();
}




?>