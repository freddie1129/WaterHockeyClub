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



// Create Team member table.
$db = new SQLite3($glbDbName, SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

// Create Match table.
$db->query('CREATE TABLE IF NOT EXISTS "match" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    "time" DATE,
    "location" VARCHAR,
    "teamA" INTEGER,
    "teamB" INTEGER,
    "status" VARCHAR,
    "scoreA" VARCHAR,
    "scoreB" VARCHAR,
    FOREIGN KEY(teamA) REFERENCES team(id),
    FOREIGN KEY(teamB) REFERENCES team(id)
)');

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


$db->close();


?>