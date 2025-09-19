<?php

    $_SERVER["DOCUMENT_ROOT"] = "/var/www/html";

    require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/UserController.php";
    echo "salut \n";

    $userController = new UserController();
    $userController->GetAllUsers();