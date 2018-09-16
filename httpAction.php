<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/09/18
 * Time: 12:57 PM
 */
require_once('libcommon.php');

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
                    "username" => $user->getUsername(),
                    "userType" => $user->getUserType(),
                    "accessToken" => $user->accessToken);
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


// Handling Auto Login by access token
function httpLoginByToken($userToken)
{
    $ret = dbIsValidUserByToken($userToken);
    if ($ret == true)
    {
        $user = dbGetUserByToken($userToken);
        $ret = array ( "status" => "success",
            "username" => $user->getUsername(),
            "userType" => $user->getUserType(),
            "accessToken" => $user->accessToken);
        echo json_encode($ret);
    }
    else{
        $ret = array ( "status" => "failed",
            "msg" => "Token is invalid");
        echo json_encode($ret);
    }
}

?>