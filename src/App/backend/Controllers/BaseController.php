<?php

require_once('vendor/autoload.php');
require_once($_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/DatabaseController.php");


abstract class BaseController
{
    protected ?DatabaseController $dbController;

    function __construct(?DatabaseController $_dbContoller = null)
    {
        if ($_dbContoller != null){
            $this->dbController = $_dbContoller;
        }
        else{
            $this->dbController = new DatabaseController();
        }
    }


    function __destruct()
    {
        $this->dbController = null;
    }
}
