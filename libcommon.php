<?php

require_once 'constant.php';
require_once 'User.php';
require_once 'News.php';

// Insert a user into user table
function dbInsertUser($user) {
    global $glbDbName;
    $user_try = dbGetUserByUsername($user->username);
    if ($user_try != false)
    {
        return false;
    }
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
    return true;
}

// select all users form the user table
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


// check user's existence
function dbSignUpNewUser($username,$emailAddress, $password)
{
    global $glbDbName;
    global $glbUserTypeClient;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "user" WHERE "username" = :username');
    $statement->bindValue(':username', $username);
    $result = $statement->execute();
    if ($row = $result->fetchArray())
    {
        $db->close();
        $ret = array("status" => "failed",
            "msg" => $username." is existed");
        return $ret;
    }
    else
    {
        $accessToken = dbGenerateAccessToken($username);
        $user = new User( 0, $username,$password,$emailAddress, $accessToken, $glbUserTypeClient, date('Y-m-d H:i:s'));
        dbInsertUser($user);
        $db->close();
        $ret = array("status" => "success",
            "user" => $user);
        return $ret;
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


// update a user
function dbUpdateUserByField($username,$emailAddress,$password,$userType)
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('UPDATE user SET
                                                password = :password,
                                                emailAddress = :emailAddress,
                                                userType = :userType,
                                                time = :time
                                                WHERE username = :username');
    $statement->bindValue(':username', $username);
    $statement->bindValue(':password', $password);
    $statement->bindValue(':emailAddress', $emailAddress);
    $statement->bindValue(':userType', $userType);
    $statement->bindValue(':time', date('Y-m-d H:i:s'));
    $statement->execute();
    $user = dbGetUserByUsername($username);
    $db->close();
    $ret = array("status" => "success",
        "user" => $user);
    return $ret;
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
    unset($db);
}

// Insert a news into news table
function dbInsertNews($news, $user) {
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('INSERT INTO "news" ("title", "time", "content","userId","author") VALUES (:title, :time, :content, :userId, :author)');
    $statement->bindValue(':title', $news->title);
    $statement->bindValue(':time', date('Y-m-d H:i:s'));
    $statement->bindValue(':content', $news->content);
    $statement->bindValue(':userId', $user->id);
    $statement->bindValue(':author', $user->username);
    $statement->execute();
    $db->close();
    return true;
}

// Insert a news into news table
function dbInsertContentNews($newstitle,$newsContent, $userId, $username) {
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('INSERT INTO "news" ("title", "time", "content","userId","author")
    VALUES (:title, :time, :content, :userId, :author)');
    $statement->bindValue(':title', $newstitle);
    $statement->bindValue(':time', date('Y-m-d H:i:s'));
    $statement->bindValue(':content', $newsContent);
    $statement->bindValue(':userId', $userId);
    $statement->bindValue(':author', $username);
    $statement->execute();
    $db->close();
    return true;
}


// Insert a news into news table
function dbUpdateNews($newsId,$newsTitle,$newsContent) {
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('UPDATE news SET
                                                
                                                title = :newsTitle,
                                                content = :newsContent
                                    WHERE id = :newsId');
    $statement->bindValue(':newsId', $newsId);
    $statement->bindValue(':newsTitle', $newsTitle);
    $statement->bindValue(':newsContent', $newsContent);
    $statement->execute();
    $db->close();
    return true;
}



// select all users for the user table
function dbGetAllNews()
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "news"');
    $result = $statement->execute();
    $newsList = array();
    while($row = $result->fetchArray()) {
        $news = new News($row["id"],$row["title"],$row["time"],$row["content"],$row["userId"],$row["author"]);
        array_push($newsList,$news);
    }
    $db->close();
    return $newsList;
}

// select all News from news table
function dbGetNews($number)
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "news" LIMIT :number');
    $statement->bindValue(':number', $number);
    $result = $statement->execute();
    $newsList = array();
    while($row = $result->fetchArray()) {
        $news = new News($row["id"],$row["title"],$row["time"],$row["content"],$row["userId"],$row["author"]);
        array_push($newsList,$news);
    }
    $db->close();
    return $newsList;
}


// select all News from news table
function dbGetNewsByPageID($pageID)
{
    global $glbDbName;
    global $glbNewsNumberInOnePage;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "news" ORDER BY "time"');
    $result = $statement->execute();
    $newsList = array();
    while($row = $result->fetchArray()) {
        $news = new News($row["id"],$row["title"],$row["time"],$row["content"],$row["userId"],$row["author"]);
        array_push($newsList,$news);
    }
    $db->close();
    $num = count($newsList);
    if ($pageID * $glbNewsNumberInOnePage > $num)
    {
        $queryNum = $pageID * $glbNewsNumberInOnePage - $num;
    }
    else
    {
        $queryNum = $glbNewsNumberInOnePage;
    }
    return array_slice($newsList,($pageID - 1) * $glbNewsNumberInOnePage, $queryNum);
}




// select all users for the user table
// query a user from user table by id
function dbGetNewsById($id)
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT * FROM "news" WHERE "id" = :id');
    $statement->bindValue(':id', $id);
    $result = $statement->execute();
    if ($row = $result->fetchArray())
    {
        $user = new News($row["id"],$row["title"],$row["time"],$row["content"],$row["userId"],$row["author"]);
        $db->close();
        return $user;
    }
    else
    {
        $db->close();
        return false;
    }
}

// select all users for the user table
// query a user from user table by id
function dbCountNews()
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('SELECT COUNT (*) as "total" FROM "news" ');
    $result = $statement->execute();
    if ($row = $result->fetchArray())
    {
        $db->close();
        return $row["total"];
    }
    else
    {
        $db->close();
        return 0;
    }
}

// delete news by ID from news table
function dbDeleteNewsById($newsID)
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('DELETE FROM news WHERE "id" = :id');
    $statement->bindValue(':id', $newsID);
    $statement->execute();
    $db->close();
    unset($db);
    return true;
}

// delete all news from news table
function dbDeleteAllNews()
{
    global $glbDbName;
    $db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare('DELETE FROM news');
    $statement->execute();
    $db->close();
    unset($db);
}


?>