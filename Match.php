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
        $formatnew = "<p>
                <b>Id:&nbsp;&nbsp;</b>%u&nbsp;
                <b>Status:&nbsp;&nbsp;</b>%s&nbsp;
                <b>Time:&nbsp;&nbsp;</b>%s&nbsp;
                <b>Location:&nbsp;&nbsp;</b>%s&nbsp;
                <b>TeamA:&nbsp;&nbsp;</b>%s&nbsp;
                <b>TeamB:&nbsp;&nbsp;</b>%s&nbsp;
                <b>ScoreA:&nbsp;&nbsp;</b>%u&nbsp;
                <b>ScoreB:&nbsp;&nbsp;</b>%u&nbsp;
                </p>";

        return sprintf($formatnew,$this->id,$this->time,$this->status, $this->location,$this->teamA,$this->teamB,$this->scoreA,$this->scoreB);
    }


}

?>