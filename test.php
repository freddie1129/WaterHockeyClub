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



$db = new SQLite3('waterhockey.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

// Test User table's interfaces

// Delete All users

dbDeleteAllUser();

// Add some users
$index = 0;
dbInsertUser(new User( 0, $username = "chen_" . strval($index++),"secret","chen@gmail.com", dbGenerateAccessToken($username), $glbUserTypeAdmin, date('Y-m-d H:i:s')));
dbInsertUser(new User( 0, $username = "chen_" . strval($index++),"secret","chen@gmail.com", dbGenerateAccessToken($username), $glbUserTypeEditor, date('Y-m-d H:i:s')));
dbInsertUser(new User( 0, $username = "chen_" . strval($index++),"secret","chen@gmail.com", dbGenerateAccessToken($username), $glbUserTypeClient, date('Y-m-d H:i:s')));
dbInsertUser(new User( 0, $username = "chen_" . strval($index++),"secret","chen@gmail.com", dbGenerateAccessToken($username), $glbUserTypeAdmin, date('Y-m-d H:i:s')));
dbInsertUser(new User( 0, $username = "chen_" . strval($index++),"secret","chen@gmail.com", dbGenerateAccessToken($username), $glbUserTypeAdmin, date('Y-m-d H:i:s')));



// List All users
echo "<h3>List all users</h3>";
$userList = dbGetAllUsers();
for($x = 0; $x < count($userList); $x++) {
    $user = $userList[$x];
    echo "<p>$user<p>";
    $lastIndex = $user->id;
}

// Get User by Id
echo "<h3>Get User by id, id = $lastIndex</h3>";
$user  = dbGetUserById($lastIndex);
if ($user != false)
{
    echo "<p>$user<p>";
}

echo "<h3>Update user, id = $lastIndex</h3>";
$user->setUsername("freee");
$user->setUserType($glbUserTypeEditor);
dbUpdateUser($user);
$user  = dBGetUserById($lastIndex);
if ($user != false)
{
    echo "<p>$user<p>";
}

// Delete user by Id
echo "<h3>Delete user, id = $lastIndex</h3>";
dbDeleteUser($lastIndex);

echo "<h3>List all users</h3>";
$userList = dbGetAllUsers();
for($x = 0; $x < count($userList); $x++) {
    $user = $userList[$x];
    echo "<p>$user<p>";

}

echo("\n");

