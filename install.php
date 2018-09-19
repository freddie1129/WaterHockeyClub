<?php

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

$db->close();
?>
