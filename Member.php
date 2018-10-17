<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/09/18
 * Time: 10:06 PM
 */

class Member
{
    var $id;
    var $firstName;
    var $lastName;
    var $nickName;
    var $gender;
    var $birthday;
    var $teamId;
    var $teamName;

    /**
     * Member constructor.
     * @param $id
     * @param $firstName
     * @param $lastName
     * @param $nickName
     * @param $gender
     * @param $birthday
     * @param $teamId
     * @param $teamName
     */
    public function __construct($id, $firstName, $lastName, $nickName, $gender, $birthday, $teamId, $teamName)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->nickName = $nickName;
        $this->gender = $gender;
        $this->birthday = $birthday;
        $this->teamId = $teamId;
        $this->teamName = $teamName;
    }


    public function __toString(){
        // TODO: Implement __toString() method.
        $format = "<ul>
                <li>Id: %u</li>
                <li>First Name: %s</li>
                <li>Last Name: %s</li>
                <li>Gender: %s</li>
                <li>Birthday : %s</li>
                <li>Team : %u</li>

</ul>";
        return sprintf($format,$this->id,$this->firstNname,$this->lastName,$this->gender,$this->birthday,$this->teamId);
    }

}