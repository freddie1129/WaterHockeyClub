<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/09/18
 * Time: 11:44 PM
 */

class Match
{

    var $id;
    var $time;
    var $location;
    var $teamA;
    var $teamB;
    var $status;
    var $scoreA;
    var $scoreB;

    /**
     * Match constructor.
     * @param $id
     * @param $time
     * @param $location
     * @param $teamA
     * @param $teamB
     * @param $status
     * @param $scoreA
     * @param $scoreB
     */
    public function __construct($id, $time, $location, $teamA, $teamB, $status, $scoreA, $scoreB)
    {
        $this->id = $id;
        $this->time = $time;
        $this->location = $location;
        $this->teamA = $teamA;
        $this->teamB = $teamB;
        $this->status = $status;
        $this->scoreA = $scoreA;
        $this->scoreB = $scoreB;
    }


    public function __toString(){
 // TODO: Implement __toString() method.


        $format = "<ul>
                <li>Id: %u</li>
                <li>time: %s</li>
                <li>location: %s</li>
                <li>teamA: %s</li>
                <li>teamB: %s</li>
                <li>score: %s</li>
                

</ul>";
        return sprintf($format,$this->id,$this->time,$this->location,$this->teamA,$this->teamB,$this->score);
    }


}

?>