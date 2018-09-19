<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/09/18
 * Time: 10:06 PM
 */

class News
{
    var $id;
    var $title;
    var $time;
    var $content;
    var $userId;
    var $author;

    /**
     * News constructor.
     * @param $id
     * @param $title
     * @param $time
     * @param $content
     * @param $userId
     */
    public function __construct($id, $title, $time, $content, $userId, $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->time = $time;
        $this->content = $content;
        $this->userId = $userId;
        $this->author = $author;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }



    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function __toString(){
        // TODO: Implement __toString() method.
        $format = "<ul>
                <li>Id: %u</li>
                <li>Title: %s</li>
                <li>Time: %s</li>
                <li>Content: %s</li>
                <li>Editor : %s</li>

</ul>";
        return sprintf($format,$this->id,$this->title,$this->title,$this->content,$this->author);
    }

}