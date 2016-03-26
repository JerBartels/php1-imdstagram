<?php

/* DB class */

class Db
{
    private $m_sHost = "locahost";
    private $m_sUser = "jer";
    private $m_sPass = "jerrej";
    private $m_sDbname = "satchmo";

    private $m_Conn;
    public $m_Error;

    public function __construct()
    {
        //database source name
        $p_sDsn = "mysql:" . $this->m_sHost . ';dbname=' . $this->m_sDbname;

        try
        {
            //constructor die nieuwe PDO instance aanmaakt
            $this->m_Conn = new PDO($p_sDsn, $this->m_sUser, $this->m_sPass);
            //mogelijkheid tot gooien van exceptions aanzetten
            $this->m_Conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $p_e)
        {
            $this->m_Error = $p_e->getMessage();
        }

    }
}
