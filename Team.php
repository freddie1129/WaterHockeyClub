<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/09/18
 * Time: 10:06 PM
 */

class Team
{
    var $id;
    var $name;
    var $location;
    var $establishTime;
    var $captionName;
    var $intro;

    /**
     * Team constructor.
     * @param $id
     * @param $name
     * @param $location
     * @param $establishTime
     * @param $captionName
     * @param $intro
     */
    public function __construct($id, $name, $location, $establishTime, $captionName, $intro)
    {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->establishTime = $establishTime;
        $this->captionName = $captionName;
        $this->intro = $intro;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        $format = "<ul>
                <li>Id: %u</li>
                <li>Name: %s</li>
                <li>Location: %s</li>
                <li>Establish Time: %s</li>
                <li>Caption Name: %s</li>
                 <li>Introduction: %s</li>

</ul>";
        return sprintf($format, $this->id, $this->name, $this->location, $this->establishTime, $this->captionName, $this->intro);
    }

}