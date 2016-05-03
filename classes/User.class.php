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
    private $m_sProfilePic;
    private $m_bPrivate;

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
            case 'ProfilePic':
                $this->m_sProfilePic = $p_vValue;
                break;
            case 'Private':
                $this->m_bPrivate = $p_vValue;
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
            case 'ProfilePic':
                return $this->m_sProfilePic;
            case 'Private':
                return $this->m_bPrivate;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }

    //controleer of user met emailadres of username bestaat
    public function Exists($p_sProperty, $p_vDbColumn)
    {
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
    }

    public function IsPrivate($p_sUsername)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM user WHERE username = :username");
        $p_sStmt->bindParam(':username', $p_sUsername);

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

    //methode om user op te zoeken op unieke username
    public function getUserById($p_iId)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM user WHERE id = :id");
        $p_sStmt->bindParam(':id', $p_iId);
        $p_sStmt->execute();

        $result = $p_sStmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //methode om user op te zoeken op unieke username
    public function getUserByUsername($p_sUsername)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM user WHERE username = :val");
        $p_sStmt->bindParam(':val', $p_sUsername);
        $p_sStmt->execute();

        $result = $p_sStmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //methode om in te loggen (controle op hash/password)
    public function LogIn($p_sUsername, $p_sPassword)
    {
        $result = $this->getUserByUsername($p_sUsername);

        if(password_verify($p_sPassword, $result['pass']))
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

        $p_sStmt = $p_dDb->prepare("INSERT INTO user (username, firstname, lastname, email, pass, profilepic, private) VALUES (:username, :firstname, :lastname, :email, :pass, :profilepic, :private)");

        $p_sStmt->bindParam(':username', $this->m_sUsername);
        $p_sStmt->bindParam(':firstname', $this->m_sFirstname);
        $p_sStmt->bindParam(':lastname', $this->m_sLastname);
        $p_sStmt->bindParam(':email', $this->m_sEmail);
        $p_sStmt->bindParam(':pass', $this->m_sPass);
        $p_sStmt->bindParam(':profilepic', $this->m_sProfilePic);
        $p_sStmt->bindParam(':private', $this->m_bPrivate);

        $p_sStmt->execute();

        $p_dDb = null;
    }

    public function Update($p_sUsername)
    {
        //nieuw object van klasse DB aanmaken
        $p_dDb = Db::getInstance();

        //updatequery
        $p_sStmt = $p_dDb->prepare("UPDATE user SET username = :username, firstname = :firstname, lastname = :lastname, email = :email, pass = :pass WHERE username = :user_name");

        $p_sStmt->bindParam(':username', $this->m_sUsername);
        $p_sStmt->bindParam(':firstname', $this->m_sFirstname);
        $p_sStmt->bindParam(':lastname', $this->m_sLastname);
        $p_sStmt->bindParam(':email', $this->m_sEmail);
        $p_sStmt->bindParam(':pass', $this->m_sPass);
        $p_sStmt->bindParam(':user_name', $p_sUsername);

        $p_sStmt->execute();

        $p_dDb = null;

    }

    public function SaveProfilePicture($p_sUsername, $p_sProfilePic)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("UPDATE user SET profilepic = :profilepic WHERE username = :user_name");

        $p_sStmt->bindParam(':user_name', $p_sUsername);
        $p_sStmt->bindParam(':profilepic', $p_sProfilePic);

        $p_sStmt->execute();

        $p_dDb = null;
    }

    public function SetProfilePrivate($p_sUsername, $p_bPrivate)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("UPDATE user SET private = :private WHERE username = :user_name");

        $p_sStmt->bindParam(':user_name', $p_sUsername);
        $p_sStmt->bindParam(':private', $p_bPrivate);

        $p_sStmt->execute();

        $p_dDb = null;
    }
}