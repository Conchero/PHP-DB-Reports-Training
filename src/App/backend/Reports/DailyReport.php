<?php

$_SERVER["DOCUMENT_ROOT"] = "/var/www/html";

require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/UserController.php";

$userController = new UserController();
echo "nbUser" . $userController->GetAllUsers();
