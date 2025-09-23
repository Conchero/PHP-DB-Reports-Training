<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/DatabaseController.php";


abstract class BaseController
{
    protected ?DatabaseController $dbController;
    protected ?PDO $dbh;

    function __construct(?DatabaseController $_dbContoller = null)
    {
        if ($_dbContoller != null) {
            $this->dbController = $_dbContoller;
        } else {
            $this->dbController = new DatabaseController();
        }

        if (!is_null($this->dbController->GetDBH())) {
            $this->dbh = $this->dbController->GetDBH();
        }
    }


    function __destruct()
    {
        $this->dbController = null;
        $this->dbh = null;
    }
}
