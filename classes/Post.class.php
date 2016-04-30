<?php

/* Post class */

include_once("Db.class.php");

class Post
{
    private $m_sPhoto;
    private $m_sComment;
    private $m_sUsername;
    private $m_iLikes;
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
            case 'Likes':
                $this->m_iLikes = $p_vValue;
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
            case 'Likes':
                return $this->m_iLikes;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }


    public function getPostByPhoto($p_sPhoto)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM post WHERE photo = :val");

        $p_sStmt->bindParam(':val', $p_sPhoto);
        $p_sStmt->execute();

        $result = $p_sStmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getPostById($p_iId)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM post WHERE id = :val");

        $p_sStmt->bindParam(':val', $p_iId);
        $p_sStmt->execute();

        $result = $p_sStmt->fetch(PDO::FETCH_ASSOC);

        return $result;
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

        $p_sStmt = $p_dDb->prepare("SELECT * FROM post ORDER BY id DESC LIMIT $p_iValue1,$p_iValue2");
        $p_sStmt->execute();

        $result = $p_sStmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAllPosts()
    {
        $p_dDb = DB::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM post");
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

    public function saveLikes($p_iNumber, $p_pPhoto)
    {
        //nieuw object van klasse DB aanmaken
        $p_dDb = Db::getInstance();

        //updatequery
        $p_sStmt = $p_dDb->prepare("UPDATE post SET likes = :likes WHERE photo = :photo");

        $p_sStmt->bindParam(':likes', $p_iNumber);
        $p_sStmt->bindParam(':photo', $p_pPhoto);

        $p_sStmt->execute();

        $p_dDb = null;
    }

    //methode om te bewaren
    public function Save()
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("INSERT INTO post (photo, comment, username, likes, date) VALUES(:photo, :comment, :username, :likes, :date)");

        $p_sStmt->bindParam(':photo', $this->Photo);
        $p_sStmt->bindParam(':comment', $this->Comment);
        $p_sStmt->bindParam(':username', $this->Username);
        $p_sStmt->bindParam(':likes', $this->Likes);
        $p_sStmt->bindParam(':date', $this->Date);

        $p_sStmt->execute();

        $p_dDb = null;
    }
}