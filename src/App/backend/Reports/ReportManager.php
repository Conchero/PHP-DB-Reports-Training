<?php

isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] = "/var/www/html" :  "server root is set";


require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/UserController.php";


function DailyReport()
{
    $tmp_date = DateTimeImmutable::createFromFormat("Y-m-d", "2023-11-01");

    $userController = new UserController();
    $newDailyUserArray = $userController->GetDailyNewUsers(DateTimeImmutable::createFromFormat("Y-m-d", "2023-11-01"));
    $nbNewUsers = count($newDailyUserArray);

    echo "________________________________ \n";
    echo "On the day of {$tmp_date->format("Y-m-d")} \n";
    echo "You had {$nbNewUsers} new user \n";

    for ($i = 0; $i < count($newDailyUserArray); $i++) {
        echo "{$newDailyUserArray[$i]["first_name"]} {$newDailyUserArray[$i]["last_name"]} \n";
        echo "account created : {$newDailyUserArray[$i]["created_at"]} \n";
        echo "email : {$newDailyUserArray[$i]["email"]} \n";
        echo "------------------------------------- \n";
    }
    $userController = null;
}

function WeeklyReport() {}

function MonthlyReport()
{
    $userController = new UserController();
    $tmp_date = DateTimeImmutable::createFromFormat("Y-m", "2023-11");

    $userController = new UserController();
    $newDailyUserArray = $userController->GetMonthlyNewUsers($tmp_date);
    $nbNewUsers = count($newDailyUserArray);

    echo "________________________________ \n";
    echo "On the Month of {$tmp_date->format("Y-m")} \n";
    echo "You had {$nbNewUsers} new user \n";

    for ($i = 0; $i < count($newDailyUserArray); $i++) {
        echo "{$newDailyUserArray[$i]["first_name"]} {$newDailyUserArray[$i]["last_name"]} \n";
        echo "account created : {$newDailyUserArray[$i]["created_at"]} \n";
        echo "email : {$newDailyUserArray[$i]["email"]} \n";
        echo "------------------------------------- \n";
    }
    $userController = null;
}

function YearlyReport()
{
    $userController = new UserController();
    $tmp_date = DateTimeImmutable::createFromFormat("Y", "2023");

    $userController = new UserController();
    $newDailyUserArray = $userController->GetYearlyNewUsers($tmp_date);
    $nbNewUsers = count($newDailyUserArray);
    
    echo "________________________________ \n";

    echo "On the Year {$tmp_date->format("Y")} \n";
    echo "You had {$nbNewUsers} new user \n";

    for ($i = 0; $i < count($newDailyUserArray); $i++) {
        echo "{$newDailyUserArray[$i]["first_name"]} {$newDailyUserArray[$i]["last_name"]} \n";
        echo "account created : {$newDailyUserArray[$i]["created_at"]} \n";
        echo "email : {$newDailyUserArray[$i]["email"]} \n";
        echo "------------------------------------- \n";
    }
    $userController = null;
}


DailyReport();
MonthlyReport();
YearlyReport();




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
