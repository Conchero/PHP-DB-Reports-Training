<?php

require_once('vendor/autoload.php');

class DatabaseController
{
    protected ?string $dsn;
    protected ?string $user;
    protected ?string $password;
    protected ?PDO $dbh;
    function __construct()
    {
       $dbName = getenv("POSTGRES_NAME",true) ?: getenv("POSTGRES_NAME");
       $dbHost = getenv("POSTGRES_HOST",true) ?: getenv("POSTGRES_HOST");
       $dbPort = getenv("POSTGRES_PORT",true) ?: getenv("POSTGRES_PORT");
       $dbUser = getenv("POSTGRES_USER",true) ?: getenv("POSTGRES_USER");
       $dbPassword = getenv("POSTGRES_PASSWORD",true) ?: getenv("POSTGRES_PASSWORD");


        $this->dsn = "pgsql:dbname={$dbName};host={$dbHost};port={$dbPort}";
        $this->user = $dbUser;
        $this->password = $dbPassword;
        $this->dbh = new PDO($this->dsn, $this->user, $this->password);
    }


    function GetDBH(): PDO
    {
        return $this->dbh;
    }

    function __destruct()
    {
        $this->dsn = null;
        $this->user = null;
        $this->password = null;
        $this->dbh = null;
    }

}
