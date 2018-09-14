<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/09/18
 * Time: 10:40 PM
 */

// This file walks you through the most common features of PHP's SQLite3 API.
// The code is runnable in its entirety and results in an `analytics.sqlite` file.


// Create a new database, if the file doesn't exist and open it for reading/writing.
// The extension of the file is arbitrary.
$db = new SQLite3('waterhockey.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);


// Create users' table.
$db->query('CREATE TABLE IF NOT EXISTS "user" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "username" VARCHAR,
    "password" VARCHAR,
    "emailAddress" VARCHAR,
    "userType" VARCHAR,
    "time" DATETIME
)');

// Create News' table.
$db->query('CREATE TABLE IF NOT EXISTS "news" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "title" VARCHAR,
    "time" DATETIME,
    "content" TEXT,
    "userId" INTEGER NOT NULL,
    FOREIGN KEY(userId) REFERENCES user(id)
)');

// Create Club table.
$db->query('CREATE TABLE IF NOT EXISTS "club" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "name" VARCHAR,
    "location" VARCHAR
)');

// Create Team member table.
$db->query('CREATE TABLE IF NOT EXISTS "player" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "first_name" VARCHAR,
    "last_name" VARCHAR,
    "birthday" DATE,
    "clubId" INTEGER,
    FOREIGN KEY(clubId) REFERENCES club(id)
)');

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

// Create Thread table.
$db->query('CREATE TABLE IF NOT EXISTS "thread" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "" VARCHAR,
    "last_name" VARCHAR,
    "birthday" DATE,
    "clubId" INTEGER,
    FOREIGN KEY(clubId) REFERENCES club(id)
)');


// Create Match' table.
$db->query('CREATE TABLE IF NOT EXISTS "match" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "title" VARCHAR,
    "time" DATETIME,
    "content" TEXT,
    "userId" INTEGER NOT NULL,
    FOREIGN KEY(userId) REFERENCES user(userId)
)');



// Insert some sample data.
//
// It's advisable to wrap related queries in a transaction (BEGIN and COMMIT),
// even if you don't care about atomicity.
// If you don't do this, SQLite automatically wraps every single query
// in a transaction, which slows down everything immensely. If you're new to SQLite,
// you may be surprised why the INSERTs are so slow.

$db->exec('BEGIN');
//$db->query('INSERT INTO "user" ("username", "password", "emailAddress", "type","time")
//    VALUES ("freddie", "1254", "ad@gmail.com", "admin", "2017-01-14 10:11:23")');
$db->query('INSERT INTO "news" ("title", "time", "content","userId")
    VALUES ("title", "2017-01-14 10:11:44","this is today\'s new", (SELECT userId from user WHERE username="freddie" ) )');
$db->exec('COMMIT');





// Fetch today's visits of user #42.
// We'll use a prepared statement again, but with numbered parameters this time:
$statement = $db->prepare('SELECT * FROM "user" WHERE "username" = ?');
$statement->bindValue(1, "freddie");
$result = $statement->execute();
echo("Get the 1st row as an associative array:\n");
print_r($result->fetchArray(SQLITE3_ASSOC));
echo("\n");




$statement = $db->prepare('SELECT * FROM "user" WHERE "username" = ? AND "time" >= ?');
$statement->bindValue(1, 42);
$statement->bindValue(2, '2017-01-14');
$result = $statement->execute();

echo("Get the 1st row as an associative array:\n");
print_r($result->fetchArray(SQLITE3_ASSOC));
echo("\n");

echo("Get the next row as a numeric array:\n");
print_r($result->fetchArray(SQLITE3_NUM));
echo("\n");

// If there are no more rows, fetchArray() returns FALSE.

// free the memory, this in NOT done automatically, while your script is running
$result->finalize();


// A useful shorthand for fetching a single row as an associative array.
// The second parameter means we want all the selected columns.
//
// Watch out, this shorthand doesn't support parameter binding, but you can
// escape the strings instead.
// Always put the values in SINGLE quotes! Double quotes are used for table
// and column names (similar to backticks in MySQL).

$query = 'SELECT * FROM "visits" WHERE "url" = \'' .
    SQLite3::escapeString('/test') .
    '\' ORDER BY "id" DESC LIMIT 1';

$lastVisit = $db->querySingle($query, true);

echo("Last visit of '/test':\n");
print_r($lastVisit);
echo("\n");


// Another useful shorthand for retrieving just one value.

$userCount = $db->querySingle('SELECT COUNT(DISTINCT "user_id") FROM "visits"');

echo("User count: $userCount\n");
echo("\n");


// Finally, close the database.
// This is done automatically when the script finishes, though.

$db->close();
?>
