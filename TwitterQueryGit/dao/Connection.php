<?php
    //dao => data access object
include "validate/Message.php";
class Connection
{
    private $dns = "mysql:host=readacted; dbname=readacted; charset=utf8";
    private $username = "readacted";
    private $password = "readacted";
    private $pdo;
    private $stmt;

    public function __construct()
    {
        try
        {
            $this->pdo = new PDO($this->dns, $this->username, $this->password);
        }
        catch (Exception $e)
        {
            Message::showMessage($e->getMessage());
        }
    }

    public function closeCon()
    {
        $this->pdo=null;
    }

    public function selectData($query)
    {
        try
        {
            $this->stmt = $this->pdo->prepare($query);
            $this->stmt->execute();
            return $this->stmt;
        }
        catch (Exception $e)
        {
            Message::showMessage($e->getMessage());
        }
    }

    public function selectDataParam($query,$param)
    {
        try
        {
            $this->stmt = $this->pdo->prepare($query);
            $this->stmt->execute($param);
            return $this->stmt;
        }
        catch (Exception $e)
        {
            Message::showMessage($e->getMessage());
        }
    }
}
//$a = new Connection();


