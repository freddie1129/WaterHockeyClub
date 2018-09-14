<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/09/18
 * Time: 11:44 PM
 */




class User
{
    var $id;
    var $username;
    var $password;
    var $emailAddress;
    var $userType;
    var $time;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $passwrod
     * @param $emailAddress
     * @param $userType
     * @param $time
     */
    public function __construct($id, $username, $password, $emailAddress, $userType, $time)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->emailAddress = $emailAddress;
        $this->userType = $userType;
        $this->time = $time;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPasswrod()
    {
        return $this->passwrod;
    }

    /**
     * @param mixed $passwrod
     */
    public function setPasswrod($passwrod)
    {
        $this->passwrod = $passwrod;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param mixed $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    public function __toString(){
 // TODO: Implement __toString() method.
    return sprintf("Id: %u, Username: %s, Password: %s, EmaillAddress: %s, UserType: %s, Time: %s\n",$this->id, $this->username, $this->password, $this->emailAddress, $this->userType, $this->time);
    }

}

?>