<?php

/* Post class */

include_once("Db.class.php");

class Post
{
    private $m_sPhoto;
    private $m_sComment;
    private $m_sUsername;
    private $m_sDate;

    //set methode
    public function __set($p_sProperty, $p_vValue)
    {
        switch($p_sProperty)
        {
            case 'Photo':
                $this->m_sPhoto = $p_vValue;
                break;
            case 'Comment':
                $this->m_sComment = $p_vValue;
                break;
            case 'Username':
                $this->m_sUsername = $p_vValue;
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
            case 'Photo':
                return $this->m_sPhoto;
            case 'Comment':
                return $this->m_sComment;
            case 'Username':
                return $this->m_sUsername;
            case 'Date':
                return $this->m_sDate;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }

    //posts per user opvragen
    public function getPostByUsername($p_sUSername)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM post WHERE username = :val");

        $p_sStmt->bindParam(':val', $p_sUSername);
        $p_sStmt->execute();

        $result = $p_sStmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getPosts($p_iValue1, $p_iValue2)
    {
        $p_dDb = DB::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT photo, comment, username, date FROM post ORDER BY id DESC LIMIT $p_iValue1, $p_iValue2");
        $p_sStmt->execute();

        $result = $p_sStmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllPosts()
    {
        $p_dDb = DB::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT photo, comment, username, date FROM post");
        $p_sStmt->execute();

        $result = $p_sStmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function searchPosts($p_sTerm)
    {
        $p_dDb = DB::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM post WHERE photo LIKE '%{$p_sTerm}%' OR comment LIKE '%{$p_sTerm}%' OR username LIKE '%{$p_sTerm}%' OR date LIKE '%{$p_sTerm}%'");
        $p_sStmt->execute();

        $result = $p_sStmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    //methode om te bewaren
    public function Save()
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("INSERT INTO post (photo, comment, username, date) VALUES(:photo, :comment, :username, :date)");

        $p_sStmt->bindParam(':photo', $this->Photo);
        $p_sStmt->bindParam(':comment', $this->Comment);
        $p_sStmt->bindParam(':username', $this->Username);
        $p_sStmt->bindParam(':date', $this->Date);

        $p_sStmt->execute();

        $p_dDb = null;
    }
}