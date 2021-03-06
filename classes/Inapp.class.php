<?php

/* Likes class */

include_once "Db.class.php";

class Inapp
{
    private $m_sUsername;
    private $m_sPicture;

    //set-methode
    public function __set($p_sProperty, $p_vValue)
    {
        switch($p_sProperty){
            case 'Username':
                $this->m_sUsername = $p_vValue;
                break;
            case 'Picture':
                $this->m_sPicture = $p_vValue;
                break;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }

    //get-methode
    public function __get($p_sProperty)
    {
        switch($p_sProperty){
            case 'Username':
                return $this->m_sUsername;
            case 'Picture':
                return $this->m_sPicture;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }

    //controleer of user foto geliked heeft
    public function AlreadyInapped($p_sUser, $p_sPhoto)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM inapp WHERE img_id = :img_id AND user_id = :user_id");
        $p_sStmt->bindParam(':img_id', $p_sPhoto);
        $p_sStmt->bindParam(':user_id', $p_sUser);

        $p_sStmt->execute();

        if($p_sStmt->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //methode om user te bewaren in DB
    public function Save()
    {
        //nieuw object van klasse DB aanmaken
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("INSERT INTO inapp (img_id, user_id) VALUES (:img_id, :user_id)");

        $p_sStmt->bindParam(':img_id', $this->m_sPicture);
        $p_sStmt->bindParam(':user_id', $this->m_sUsername);

        $p_sStmt->execute();

        $p_dDb = null;
    }

    //controleren of post inapp is
    public function IsThisInapp($p_sPhoto)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM inapp WHERE img_id = :img_id");
        $p_sStmt->bindParam(':img_id', $p_sPhoto);

        $p_sStmt->execute();

        if($p_sStmt->rowCount() > 2)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //verwijder inapp combi uit db
    public function DeleteInapp($p_sUser, $p_sPhoto)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("DELETE FROM inapp WHERE img_id = :img_id AND user_id = :user_id");

        $p_sStmt->bindParam(':img_id', $p_sPhoto);
        $p_sStmt->bindParam(':user_id', $p_sUser);

        $p_sStmt->execute();

        $p_dDb = null;
    }
}