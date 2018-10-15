<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/09/18
 * Time: 10:06 PM
 */

class Comment
{
    var $id;
    var $newsId;
    var $userId;
    var $time;
    var $content;

    /**
     * Comment constructor.
     * @param $id
     * @param $newsId
     * @param $userId
     * @param $content
     */
    public function __construct($id, $newsId, $userId, $time, $content)
    {
        $this->id = $id;
        $this->newsId = $newsId;
        $this->userId = $userId;
        $this->time = $time;
        $this->content = $content;
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
    public function getNewsId()
    {
        return $this->newsId;
    }

    /**
     * @param mixed $newsId
     */
    public function setNewsId($newsId)
    {
        $this->newsId = $newsId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
        $format = "<ul>
                <li>Id: %u</li>
                <li>newsId: %s</li>
                <li>userId: %s</li>
                <li>content: %s</li>
                </ul>";
        return sprintf($format,$this->id,$this->newsId,$this->userId,$this->content);
    }

}