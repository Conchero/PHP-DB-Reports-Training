<?php

isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] = "/var/www/html" :  "server root is set";


require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/UserController.php";

$userController = new UserController();

$userPerMonth  = $userController->GetUsersByCreatedAt();

$currentDateInfo = array();
$lastDateInfo = null;

for ($i = 0; $i < count($userPerMonth); $i++) {

    $currentDateInfo = explode("-", $userPerMonth[$i]["created_at"]);
    if (!is_null($lastDateInfo)) {
        if ($lastDateInfo[0] !== $currentDateInfo[0]) {
            echo "________________________________ \n";
            echo "Year : " . $currentDateInfo[0] . "\n";
        }
    } else {
        echo "~~~~ Report Updates ~~~~ \n";
        echo "________________________________ \n";
        echo "Year : " . $currentDateInfo[0] . "\n";
    }


    echo "{$userPerMonth[$i]["first_name"]} {$userPerMonth[$i]["last_name"]} \n";
    echo "account created : {$userPerMonth[$i]["created_at"]} \n";
    echo "email : {$userPerMonth[$i]["email"]} \n";
    echo "------------------------------------- \n";

    $lastDateInfo = $currentDateInfo;
}


include $_SERVER["DOCUMENT_ROOT"] . "/backend/Services/mail.php";
