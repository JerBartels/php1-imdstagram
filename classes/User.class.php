<?php

/* User class */

include_once("Db.class.php");

class User
{
    private $m_sUsername;
    private $m_sFirstname;
    private $m_sLastname;
    private $m_sEmail;
    private $m_sPass;

    //set-methode
    public function __set($p_sProperty, $p_vValue)
    {
        switch($p_sProperty){
            case 'Username':
                $this->m_sUsername = strtolower($p_vValue);
                break;
            case 'Firstname':
                $this->m_sFirstname = strtolower($p_vValue);
                break;
            case 'Lastname':
                $this->m_sLastname = strtolower($p_vValue);
                break;
            case 'Email':
                $this->m_sEmail = strtolower($p_vValue);
                break;
            case 'Pass':
                $this->m_sPass = $p_vValue;
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
            case 'Firstname':
                return $this->m_sFirstname;
            case 'Lastname':
                return $this->m_sLastname;
            case 'Email':
                return $this->m_sEmail;
            case 'Pass':
                return $this->m_sPass;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }

    //controleer of user met emailadres of username bestaat
    public function Exists($p_sProperty, $p_vDbColumn){
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM user WHERE $p_vDbColumn = :val");
        $p_sStmt->bindParam(':val', $p_sProperty);

        $p_sStmt->execute();

        if($p_sStmt->rowCount() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }

        $p_dDb = null;
    }

    //methode om user te bewaren in DB
    public function Save(){
        //nieuw object van klasse DB aanmaken
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("INSERT INTO user (username, firstname, lastname, email, pass) VALUES (:username, :firstname, :lastname, :email, :pass)");

        $p_sStmt->bindParam(':username', $this->m_sUsername);
        $p_sStmt->bindParam(':firstname', $this->m_sFirstname);
        $p_sStmt->bindValue(':lastname', $this->m_sLastname);
        $p_sStmt->bindParam(':email', $this->m_sEmail);
        $p_sStmt->bindParam(':pass', $this->m_sPass);

        $p_sStmt->execute();

        $p_dDb = null;
    }
}