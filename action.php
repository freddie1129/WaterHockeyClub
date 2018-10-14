<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/09/18
 * Time: 12:57 PM
 */
require_once('libcommon.php');
require_once('constant.php');


header("Content-type: application/json");


if (isset($_POST['action']))
{
    switch ($_POST['action']){
        case 'httpLogin':
            httpLogin($_POST['username'],$_POST['password']);
            break;
        case 'httpLoginByToken':
            httpLoginByToken($_POST['userToken']);
            break;
        case 'httpSignup':
            httpSignup($_POST['username'],$_POST['emailAddress'],$_POST['password']);
            break;
        case 'httpUpdateUserProfile':
            httpUpdateUserProfile($_POST['username'],$_POST['emailAddress'],$_POST['password'],$_POST['userType']);
            break;
        case 'httpDeleteUser':
            httpDeleteUser($_POST['userId']);
            break;
        case 'httpChangeUserType':
            httpDeleteUser($_POST['userId'],$_POST['userType']);
        case 'httpGetNewsList':
            httpGetNewsList($_POST['pageId']);
            break;
    }
}

// Handling login form
function httpLogin($username,$password)
{
    $msg = dbIsValidUser($username,$password);
    if (!is_string($msg))
    {
        $user = dbGetUserByUsername($username);
        $ret = array ( "status" => "success",
            "user" => $user);
        echo json_encode($ret);
    }
    else{
        $ret = array ( "status" => "failed",
            "msg" => $msg);
        echo json_encode($ret);
    }
}

// Handling Signup form
function httpSignup($username,$emailAddress,$password)
{
    $ret = dbSignUpNewUser($username,$emailAddress,$password);
    echo json_encode($ret);
}

// Handling update user
function httpUpdateUserProfile($username,$emailAddress,$password,$userType)
{
    $ret = dbUpdateUserByField($username,$emailAddress,$password, $userType);
    echo json_encode($ret);
}

// Handling update user
function httpDeleteUser($userId)
{
    dbDeleteUser($userId);
    $ret = array ( "status" => "success",
        "msg" => "jkj");
    echo json_encode($ret);
}


// Handling update user
function httpChangeUserType($userId,$userType)
{
    $user = dbGetUserById($userId);
    $user->userType = $userType;
    dbUpdateUser($user);
    $ret = array ( "status" => "success",
        "user" => $user);
    echo json_encode($ret);
}



// Handling Auto Login by access token
function httpLoginByToken($userToken)
{
    $ret = dbIsValidUserByToken($userToken);
    if ($ret == true)
    {
        $user = dbGetUserByToken($userToken);
        $ret = array ( "status" => "success",
            "user" => $user);
        echo json_encode($ret);
    }
    else{
        $ret = array ( "status" => "failed",
            "msg" => "Token is invalid");
        echo json_encode($ret);
    }
}

// Handling Auto Login by access token
function httpGetNewsList($pageId)
{
    global $glbNewsNumberInOnePage;
    $newCount = dbCountNews();
    if ($glbNewsNumberInOnePage * $pageId - $newCount >= $glbNewsNumberInOnePage)
    {
        $ret = array ( "status" => "failed",
            "msg" => "It is already the last page.",
            "count" => $newCount);
        echo json_encode($ret);
    }
    else
    {
        $news = dbGetNewsByPageID($pageId);
        $htmlArray = array();
        for ($index = 0; $index < count($news); $index++)
        {
            $item = $news[$index];
            $txt = sprintf("<p style=\"text-align:left;\"><a href=\"newspage.php?newId=%u\">%s</a>
                    <span style=\"float:right;\">%s</span></p>", $item->id,$item->title, $item->time);
            array_push($htmlArray,$txt);
        }

        $ret = array ( "status" => "success",
            "news" => $htmlArray,
            "maxPageNum" => round(ceil($newCount / $glbNewsNumberInOnePage)),
            "count" => $newCount);

        echo json_encode($ret);
    }
}

?>