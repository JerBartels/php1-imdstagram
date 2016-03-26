<?php

class Db
{
    private static $m_Db;

    //singleton pattern
    //enkel 1 instance kan gecreërd worden
    //if === null ==> instance aanmaken + return
    //anders ==> return instance
    public static function getInstance()
    {
        if(self::$m_Db === null )
        {
            self::$m_Db = new PDO("mysql:host=localhost; dbname=satchmo", "jer", "jerrej");
            self::$m_Db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return self::$m_Db;
        }
        else
        {
            return self::$m_Db;
        }
    }
}
