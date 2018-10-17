<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/09/18
 * Time: 12:57 PM
 */
//require_once('libcommon.php');
//require_once('constant.php');

include_once 'libcommon.php';
include_once 'User.php';
include_once 'News.php';
include_once 'Comment.php';
include_once 'constant.php';


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
            httpGetNewsList($_POST['pageId'],$_POST['userId']);
            break;
        case 'httpDeleteNews':
            httpDeleteNews($_POST['newsId']);
            break;
        case 'httpUpdateNews':
            httpUpdateNews($_POST['newsId'],$_POST['newsTitle'],$_POST['newsContent']);
            break;
        case 'httpCreateNews':
            httpCreateNews($_POST['userId'], $_POST['newsTitle'],$_POST['newsContent']);
            break;
        case 'httpAddComment':
            httpAddComment($_POST['newsId'], $_POST['userId'],$_POST['content']);
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
function httpGetNewsList($pageId,$userId)
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
                    <p style=\"text-align:left;\"><a href=\"newspage.php?newId=%u&userId=%u\">%s</a>
                    <span style=\"float:right;\">%s</span></p>
                    <button id=\"button_edit_news_%u\" type=\"button\" class=\"editNews btn btn-primary\" data-toggle=\"modal\" >Edit</button>
                    <button id=\"button_edit_news_%u\" type=\"button\" class=\"deleteNews btn btn-danger\" >Delete</button>
                    <input id=\"inputNewsId_%u\" type=\"hidden\" value=\"%u\">
                    <input id=\"inputNewsTitle_%u\" type=\"hidden\" value=\"%s\">
                    <input id=\"inputNewsContent_%u\" type=\"hidden\" value=\"%s\">
                    </div>",  $item->id, $item->id, $userId, $item->title, $item->time, $item->id, $item->id,
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

// update news
function httpCreateNews($userId, $newsTitle,$newsContent)
{
    $user = dbGetUserById($userId);
    //$d = date('Y-m-d H:i:s');
    $news = new News(0, $newsTitle, date('Y-m-d H:i:s'), $newsContent, $user->id, $user->username);
    $ret = true;
    $ret =  dbInsertNews($news,$user);
    //$ret  = dbInsertContentNews($newsTitle, $newsContent,$user->id, $user->username);

    if ($ret == true)
    {
        $ret = array ( "status" => "success",
            "title" => $news->title,
            "content" => $news->time,
            "id" =>$news->content,
            "author" => $user->username


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
function httpAddComment($newsId, $userId, $content)
{
    //$user = dbGetUserById($userId);
    $comment = new Comment(0,$newsId,$userId,date('Y-m-d H:i:s'),$content);
    dbInsertComment($comment);
    $ret = true;
    if ($ret == true)
    {
        $ret = array ( "status" => "success",
            "title" => $comment->content,
            "content" => $comment->newsId,
            "id" =>$comment->userId,
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