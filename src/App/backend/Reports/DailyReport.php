<?php

isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] = "/var/www/html" : "server root is set"; 


require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/UserController.php";

$userController = new UserController();

$userPerMonth  = $userController->GetUsersCreatedByMonth();

for ($i = 0 ; $i < count($userPerMonth); $i++){
    
    echo "{$userPerMonth[$i]["first_name"]} {$userPerMonth[$i]["last_name"]} \n";
    echo "account created : {$userPerMonth[$i]["created_at"]} \n";
    echo "email : {$userPerMonth[$i]["email"]} \n";
    echo "________________________________ \n";
}
