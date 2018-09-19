<?php

include_once 'libcommon.php';
include_once 'User.php';
include_once 'News.php';

include_once 'constant.php';

// Create a new database, if the file doesn't exist and open it for reading/writing.
$db = new SQLite3('waterhockey.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

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
echo "<h3>Create Club news table</h3>";


// Create Club table.
$db->query('CREATE TABLE IF NOT EXISTS "club" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR,
    "location" VARCHAR
)');
echo "<h3>Create Club table</h3>";


// Create Team member table.
$db->query('CREATE TABLE IF NOT EXISTS "player" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "first_name" VARCHAR,
    "last_name" VARCHAR,
    "birthday" DATE,
    "clubId" INTEGER,
    FOREIGN KEY(clubId) REFERENCES club(id)
)');
echo "<h3>Create Team member table</h3>";


// Create Match table.
$db->query('CREATE TABLE IF NOT EXISTS "match" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "club_a_id" VARCHAR,
    "club_b_id" VARCHAR,
    "time" DATE,
    "location" VARCHAR,
    FOREIGN KEY(club_a_id) REFERENCES club(id),
    FOREIGN KEY(club_b_id) REFERENCES club(id)
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
for ($index = 0; $index < 20; $index++) {
    $content = "Each side has 12 players, 10 of who can play in any one game. During the game 6 players are in the pool with 4 interchange players on the side who can sub at any time. The players wear large fins, a diving mask and snorkel and a thick glove made from latex to protect the hand from the pool bottom and the puck. The bats are made of wood and are about 25cm long, they usually have one straight edge for flicking the puck and the back edge is usually curved for hooking the puck. The top players can flick the puck well over 3m and it comes off the bottom enough to go over another player.";
    $news = new News(0, $title = "News of Water Hockey club (".strval($index).")", date('Y-m-d H:i:s'), $content, $firstUser->id, $firstUser->username);
    dbInsertNews($news, $firstUser);
    echo sprintf("<p>Insert news %u</p>",$index);
    echo $news;
}
?>
