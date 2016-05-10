<?php

/* Likes class */

include_once("Db.class.php");

class Follow
{
    private $m_sFan;
    private $m_sTarget;
    private $m_bAccepted;

    //set-methode
    public function __set($p_sProperty, $p_vValue)
    {
        switch($p_sProperty){
            case 'Fan':
                $this->m_sFan = $p_vValue;
                break;
            case 'Target':
                $this->m_sTarget = $p_vValue;
                break;
            case 'Accepted':
                $this->m_bAccepted = $p_vValue;
                break;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }

    //get-methode
    public function __get($p_sProperty)
    {
        switch($p_sProperty){
            case 'Fan':
                return $this->m_sFan;
            case 'Target':
                return $this->m_sTarget;
            case 'Accepted':
                return $this->m_bAccepted;
            default:
                echo "Error: " . $p_sProperty . " does not exist.";
        }
    }

    public function AlreadyFan($p_sFan, $p_sTarget)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM follow WHERE fan = :fan AND target = :target");
        $p_sStmt->bindParam(':fan', $p_sFan);
        $p_sStmt->bindParam(':target', $p_sTarget);

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

    public function AlreadyAcceptedFan($p_sFan, $p_sTarget)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM follow WHERE fan = :fan AND target = :target AND accepted = TRUE ");
        $p_sStmt->bindParam(':fan', $p_sFan);
        $p_sStmt->bindParam(':target', $p_sTarget);

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

    public function AcceptFollow($p_sFan, $p_sTarget, $p_bAccepted)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("UPDATE follow SET accepted = :accepted WHERE fan = :fan AND target = :target");

        $p_sStmt->bindParam(':fan', $p_sFan);
        $p_sStmt->bindParam(':target', $p_sTarget);
        $p_sStmt->bindParam(':accepted', $p_bAccepted);

        $p_sStmt->execute();

        $p_dDb = null;
    }

    public function UpdateAccepted($p_sTarget, $p_bAccepted)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("UPDATE follow SET accepted = :accepted WHERE target = :target");

        $p_sStmt->bindParam(':target', $p_sTarget);
        $p_sStmt->bindParam(':accepted', $p_bAccepted);

        $p_sStmt->execute();

        $p_dDb = null;
    }

    public function updateUsernameFan($p_sOldUsername, $p_sNewUsername)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("UPDATE post SET fan = :new_username WHERE fan = :old_username");

        $p_sStmt->bindParam(':new_username', $p_sNewUsername);
        $p_sStmt->bindParam(':old_username', $p_sOldUsername);

        $p_sStmt->execute();

        $p_dDb = null;
    }

    public function updateUsernameTarget($p_sOldUsername, $p_sNewUsername)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("UPDATE post SET target = :new_username WHERE target = :old_username");

        $p_sStmt->bindParam(':new_username', $p_sNewUsername);
        $p_sStmt->bindParam(':old_username', $p_sOldUsername);

        $p_sStmt->execute();

        $p_dDb = null;
    }

    public function getAllFollows($p_sTarget)
    {
        $p_dDb = DB::getInstance();

        $p_sStmt = $p_dDb->prepare("SELECT * FROM follow WHERE target = :target AND accepted = FALSE ORDER BY id DESC");
        $p_sStmt->bindParam(':target', $p_sTarget);

        $p_sStmt->execute();

        $result = $p_sStmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function Save()
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("INSERT INTO follow (fan, target, accepted) VALUES (:fan, :target, :accepted)");

        $p_sStmt->bindParam(':fan', $this->m_sFan);
        $p_sStmt->bindParam(':target', $this->m_sTarget);
        $p_sStmt->bindParam(':accepted', $this->m_bAccepted);

        $p_sStmt->execute();

        $p_dDb = null;
    }

    //verwijder like uit db
    public function DeleteFollow($p_sFan, $p_sTarget)
    {
        $p_dDb = Db::getInstance();

        $p_sStmt = $p_dDb->prepare("DELETE FROM follow WHERE fan = :fan AND target = :target");

        $p_sStmt->bindParam(':fan', $p_sFan);
        $p_sStmt->bindParam(':target', $p_sTarget);

        $p_sStmt->execute();

        $p_dDb = null;
    }
}