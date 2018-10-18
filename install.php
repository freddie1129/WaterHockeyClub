<?php

include_once 'libcommon.php';
include_once 'User.php';
include_once 'News.php';
include_once 'Comment.php';

include_once 'constant.php';



global $glbDbName;
global $glbMatchStatusInProgress;
global $glbMatchStatusHaveDone;
global $glbMatchStatusInComing;

// Create a new database, if the file doesn't exist and open it for reading/writing.
$db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

// Create users' table.
$db->query('CREATE TABLE IF NOT EXISTS "user" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "username" VARCHAR,
    "password" VARCHAR,
    "accessToken" VARCHAR,
    "emailAddress" VARCHAR,
    "userType" VARCHAR,
    "time" DATETIME
)');
echo "<h3>Create User table</h3>";

// Create News' table.
$db->query('CREATE TABLE IF NOT EXISTS "news" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "title" VARCHAR,
    "time" DATETIME,
    "content" TEXT,
    "userId" INTEGER NOT NULL,
    "author" VARCHAR,
    FOREIGN KEY(userId) REFERENCES user(id)
)');
echo "<h3>Create Team news table</h3>";

// Create New Comment table.
$db->query('CREATE TABLE IF NOT EXISTS "comment" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "userId" INTEGER NOT NULL,
    "newsId" INTEGER NOT NULL,
    "time" DATE,
    "content" VARCHAR,
    FOREIGN KEY(userId) REFERENCES user(id),
    FOREIGN KEY(newsId) REFERENCES news(id)
)');
echo "<h3>Create Team comments table</h3>";

// Create Team table.
$db->query('CREATE TABLE IF NOT EXISTS "team" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR,
    "location" VARCHAR,
    "establishTime" VARCHAR,
    "captionName" VARCHAR,
    "intro" TEXT
)');
echo "<h3>Create Team table</h3>";

// Create Team member table.
$db->query('CREATE TABLE IF NOT EXISTS "member" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "firstName" VARCHAR,
    "lastName" VARCHAR,
    "nickName" VARCHAR,
    "gender" VARCHAR,
    "birthday" DATE,
    "teamId" INTEGER,
    "teamName" VARCHAR,
    FOREIGN KEY(teamId) REFERENCES team(id)
)');
echo "<h3>Create Team member table</h3>";


// Create Match table.
$db->query('CREATE TABLE IF NOT EXISTS "match" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "time" DATETIME,
    "location" VARCHAR,
    "teamA" INTEGER,
    "teamB" INTEGER,
    "status" VARCHAR,
    "scoreA" VARCHAR,
    "scoreB" VARCHAR,
    FOREIGN KEY(teamA) REFERENCES team(id),
    FOREIGN KEY(teamB) REFERENCES team(id)
)');



echo "<h3>Create match table</h3>";

$db->close();

// Insert some fake data for testing

// clear up user table
dbDeleteAllUser();

// Insert some admin users for testing
echo "<h3>Insert some admin users for testing</h3>";
$index = 0;
for ($index = 0; $index < 10; $index++) {
    $user = new User( 0, $username = "admin_" . strval($index),"secret","chen@gmail.com", dbGenerateAccessToken($username), $glbUserTypeAdmin, date('Y-m-d H:i:s'));
    dbInsertUser($user);
    echo $user;
}

// Insert some editor users for testing
echo "<h3>Insert some editor users for testing</h3>";
for ($index = 0; $index < 10; $index++) {
    $user = new User( 0, $username = "editor_" . strval($index),"secret","chen@gmail.com", dbGenerateAccessToken($username), $glbUserTypeEditor, date('Y-m-d H:i:s'));
    dbInsertUser($user);
    echo $user;
}

// Insert some general users for testing
echo "<h3>Insert some general users for testing</h3>";
for ($index = 0; $index < 10; $index++) {
    $user = new User( 0, $username = "client_" . strval($index),"secret","chen@gmail.com", dbGenerateAccessToken($username), $glbUserTypeClient, date('Y-m-d H:i:s'));
    dbInsertUser($user);
    echo $user;
}

echo "<h3>Insert some news for testing</h3>";
dbDeleteAllNews();
$userList = dbGetAllUsers();
$firstUser = $userList[0];
for ($index = 0; $index < 11; $index++) {
    $content = "Each side has 12 players, 10 of who can play in any one game. During the game 6 players are in the pool with 4 interchange players on the side who can sub at any time. The players wear large fins, a diving mask and snorkel and a thick glove made from latex to protect the hand from the pool bottom and the puck. The bats are made of wood and are about 25cm long, they usually have one straight edge for flicking the puck and the back edge is usually curved for hooking the puck. The top players can flick the puck well over 3m and it comes off the bottom enough to go over another player.";
    $news = new News(0, $title = "News of Water Hockey club (".strval($index).")", date('Y-m-d H:i:s'), $content, $firstUser->id, $firstUser->username);
    dbInsertNews($news, $firstUser);
    echo sprintf("<p>Insert news %u</p>",$index);
    echo $news;
}

echo "<h3>Insert some comments for testing</h3>";
dbDeleteAllComment();
$userList = dbGetAllUsers();
$newsList = dbGetAllNews();
$firstUser = $userList[0];
for ($index = 0; $index < count($newsList); $index++) {
    $news = $newsList[$index];
    for ($i = 0; $i < count($userList); $i++)
    {
        $u = $userList[$i];
        $comment = new Comment(0,$news->id,$u->id,date('Y-m-d H:i:s'),"This is a comments test (".strval($index).")");
        dbInsertComment($comment);
        echo sprintf("<p>Insert comment %u</p>",$index);
        echo $comment;
    }
}

echo "<h3>Print Comment</h3>";
for ($index = 0; $index < count($newsList); $index++) {
    $news = $newsList[$index];
    $commentList = dbGetCommentByNewsId($news->id);
    echo sprintf("<p>News %u</p>",$news->id);
    for ($i = 0; $i < count($commentList); $i++)
    {
        echo $commentList[$i];
    }
}


echo "<h3>Insert some Teams for testing</h3>";
dbDeleteAllTeam();
for ($index = 0; $index < 20; $index++) {
    $t = new Team(0,"Team_".strval($index), "Somewhere".strval($index),
        "establisTime".strval(1990 + $index),"Jack".strval($index),
        "Introduction   ".strval($index));
    dbInsertTeam($t);
}

echo "<h3>Insert some Member for testing</h3>";
$teamList = dbGetAllTeams();
for ($index = 0 ; $index < count($teamList); $index++)
{
    $team = $teamList[$index];
    for ($i = 0; $i < 10; $i++)
    {
        $member = new Member(0,"firstName".strval($i),"LastName".strval($i),
            "nickName".strval($i),"male",date('Y-m-d'),$team->id,$team->name);
        dbInsertMember($member);
    }
}


echo "<h3>Insert some Testing Matches</h3>";
dbDeleteAllMatch();
$teamList = dbGetAllTeams();
$teamCount = count($teamList);
$locations=array("Brisbane","Sydney","Perth","New York","Adelaide","Townsville","Toowoomba");
for ($index = 0 ; $index < 20; $index++)
{
    $teamA = $teamList[rand(0,$teamCount - 1)];
    $teamB = $teamList[rand(0,$teamCount - 1)];
    if ($index < 10)
    {
        $progress = $glbMatchStatusHaveDone;
        $date = date('Y-m-d H:i:s', strtotime(sprintf("-%u day" ,20-$index)));
        $scoreA = rand(0,20);
        $scoreB = rand(0,20);
        $location=$locations[rand(0,6)];

    }
    else if ($index < 15)
    {
        $progress = $glbMatchStatusInProgress;
        $date = date('Y-m-d H:i:s');
        $scoreA = rand(0,20);
        $scoreB = rand(0,20);
        $location=$locations[rand(0,6)];

    }
    else
    {
        $progress = $glbMatchStatusInComing;
        $date = date('Y-m-d H:i:s', strtotime(sprintf("+%u day" ,$index)));
        $scoreA = 0;
        $scoreB = 0;
        $location=$locations[rand(0,6)];

    }
    $match = new Match(0,$date,$location, $teamA->id,$teamB->id,$progress,$scoreA,$scoreB);
    dbInsertMatch($match);
}
$matchList = dbGetAllMatch();
foreach ($matchList as $match )
{
    echo $match;
}




?>
