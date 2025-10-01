<?php

isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] = "/var/www/html" :  "server root is set";


require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/UserController.php";


function DailyReport()
{
    $userController = new UserController();
    $newDailyUserArray = $userController->GetDailyNewUsers(DateTimeImmutable::createFromFormat("Y-m-d","2023-11-01"));
    $nbNewUsers = count($newDailyUserArray);
    echo "You had {$nbNewUsers} new user \n";

    for ($i = 0; $i < count($newDailyUserArray); $i++) {
        echo "{$newDailyUserArray[$i]["first_name"]} {$newDailyUserArray[$i]["last_name"]} \n";
        echo "account created : {$newDailyUserArray[$i]["created_at"]} \n";
        echo "email : {$newDailyUserArray[$i]["email"]} \n";
        echo "------------------------------------- \n";
    }
}

function WeeklyReport() {}

function MonthlyReport()
{
    $userController = new UserController();
}

function YearlyReport()
{
    $userController = new UserController();
}


DailyReport();




// $currentDateInfo = array();
// $lastDateInfo = null;

// for ($i = 0; $i < count($userPerMonth); $i++) {

//     $currentDateInfo = explode("-", $userPerMonth[$i]["created_at"]);
//     if (!is_null($lastDateInfo)) {
//         if ($lastDateInfo[0] !== $currentDateInfo[0]) {
//             echo "________________________________ \n";
//             echo "Year : " . $currentDateInfo[0] . "\n";
//         }
//     } else {
//         echo "~~~~ Report Updates ~~~~ \n";
//         echo "________________________________ \n";
//         echo "Year : " . $currentDateInfo[0] . "\n";
//     }


//     echo "{$userPerMonth[$i]["first_name"]} {$userPerMonth[$i]["last_name"]} \n";
//     echo "account created : {$userPerMonth[$i]["created_at"]} \n";
//     echo "email : {$userPerMonth[$i]["email"]} \n";
//     echo "------------------------------------- \n";

//     $lastDateInfo = $currentDateInfo;
// }
