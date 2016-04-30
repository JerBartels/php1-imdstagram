<?php

/* Comment class*/

include_once("Db.class.php");

class Comment
{
    private $m_sUsername;
    private $m_iPost;
    private $m_sComment;
    private $m_sDate;

    //set methode
    public function __set($p_sProperty, $p_vValue)
    {
        switch($p_sProperty)
        {
            case 'Username':
                $this->m_sUsername = $p_vValue;
                break;
            case 'Post':
                $this->m_iPost = $p_vValue;
                break;
            case 'Comment':
                $this->m_sComment = $p_vValue;
                break;
            case 'Date':
                $this->m_sDate = $p_vValue;
                break;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }

    //get methode
    public function __get($p_sProperty)
    {
        switch($p_sProperty)
        {
            case 'Username':
                return $this->m_sUsername;
            case 'Post':
                return $this->m_iPost;
            case 'Comment':
                return $this->m_sComment;
            case 'Date':
                return $this->m_sDate;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }

    //comments per post opvragen
    public function getComments($p_iValue1)
    {
        $p_dDb = DB::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM comments ORDER BY id DESC WHERE post LIKE($p_iValue1)");
        $p_sStmt->execute();

        $result = $p_sStmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllComments()
    {
        $p_dDb = DB::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM comments");
        $p_sStmt->execute();

        $result = $p_sStmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //methode om te bewaren
    public function Save()
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("INSERT INTO comments (username, post, comment, date) VALUES(:username, :post, :comment, :date)");

        $p_sStmt->bindParam(':username', $this->Username);
        $p_sStmt->bindParam(':post', $this->Post);
        $p_sStmt->bindParam(':comment', $this->Comment);
        $p_sStmt->bindParam(':date', $this->Date);

        $p_sStmt->execute();

        $p_dDb = null;
    }
}