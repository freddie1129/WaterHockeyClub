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
        case 'httpDeleteNews':
            httpDeleteNews($_POST['newsId']);
            break;
        case 'httpUpdateNews':
            httpUpdateNews($_POST['newsId'],$_POST['newsTitle'],$_POST['newsContent']);
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
            $txt = sprintf("<div id=\"news_%u\">
                    <p style=\"text-align:left;\"><a href=\"newspage.php?newId=%u\">%s</a>
                    <span style=\"float:right;\">%s</span></p>
                    <button id=\"button_edit_news_%u\" type=\"button\" class=\"editNews btn btn-primary\" data-toggle=\"modal\" >Edit</button>
                    <button id=\"button_edit_news_%u\" type=\"button\" class=\"deleteNews btn btn-danger\" >Delete</button>
                    <input id=\"inputNewsId_%u\" type=\"hidden\" value=\"%u\">
                    <input id=\"inputNewsTitle_%u\" type=\"hidden\" value=\"%s\">
                    <input id=\"inputNewsContent_%u\" type=\"hidden\" value=\"%s\">
                    </div>",  $item->id, $item->id,$item->title, $item->time, $item->id, $item->id,
                    $item->id,$item->id,
                    $item->id, $item->title,
                    $item->id, $item->content);
            array_push($htmlArray,$txt);
        }

        $ret = array ( "status" => "success",
            "news" => $htmlArray,
            "maxPageNum" => round(ceil($newCount / $glbNewsNumberInOnePage)),
            "count" => $newCount);

        echo json_encode($ret);
    }
}

//delete news
function httpDeleteNews($newsID)
{
    $ret  = dbDeleteNewsById($newsID);
    if ($ret == true)
    {
        global $glbNewsNumberInOnePage;
        $newCount = dbCountNews();
       $ret = array ( "status" => "success",
           "maxPageNum" => round(ceil($newCount / $glbNewsNumberInOnePage)),
            );
        echo json_encode($ret);
    }
    else
    {
        $ret = array ( "status" => "failed",
            );
        echo json_encode($ret);
    }
}

// update news
function httpUpdateNews($newsId, $newsTitle,$newsContent)
{
    $ret  = dbUpdateNews($newsId, $newsTitle,$newsContent);
    if ($ret == true)
    {
        global $glbNewsNumberInOnePage;
        $newCount = dbCountNews();
        $ret = array ( "status" => "success",
        );
        echo json_encode($ret);
    }
    else
    {
        $ret = array ( "status" => "failed",
        );
        echo json_encode($ret);
    }
}







?>