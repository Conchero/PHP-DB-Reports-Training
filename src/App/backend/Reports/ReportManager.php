<?php

isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] = "/var/www/html" :  "server root is set";

error_reporting(E_ALL);
ini_set("display_errors", 1);

echo "Avant require UserController\n";
require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/controllers/UserController.php";
echo "Après require UserController\n";
require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/services/Mail.php";


function DailyReport()
{
    $tmp_date = DateTimeImmutable::createFromFormat("Y-m-d", "2023-11-01");

    $userController = new UserController();
    $newDailyUserArray = $userController->GetDailyNewUsers(DateTimeImmutable::createFromFormat("Y-m-d", "2023-11-01"));
    $nbNewUsers = count($newDailyUserArray);

    $mailBody = '';


    $mailBody .= "________________________________ \n";
    $mailBody .= "On the day of {$tmp_date->format("Y-m-d")} \n";
    $mailBody .= "You had {$nbNewUsers} new user \n";

    for ($i = 0; $i < count($newDailyUserArray); $i++) {
        $mailBody .= "{$newDailyUserArray[$i]["first_name"]} {$newDailyUserArray[$i]["last_name"]} \n";
        $mailBody .= "account created : {$newDailyUserArray[$i]["created_at"]} \n";
        $mailBody .= "email : {$newDailyUserArray[$i]["email"]} \n";
        $mailBody .= "------------------------------------- \n";
    }

    MailService::CreateAndSendMail("Daily Report", $mailBody);
    echo $mailBody;

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
    $mailBody = '';

    $mailBody .= "________________________________ \n";
    $mailBody .= "On the Month of {$tmp_date->format("Y-m")} \n";
    $mailBody .= "You had {$nbNewUsers} new user \n";

    for ($i = 0; $i < count($newDailyUserArray); $i++) {
        $mailBody .= "{$newDailyUserArray[$i]["first_name"]} {$newDailyUserArray[$i]["last_name"]} \n";
        $mailBody .= "account created : {$newDailyUserArray[$i]["created_at"]} \n";
        $mailBody .= "email : {$newDailyUserArray[$i]["email"]} \n";
        $mailBody .= "------------------------------------- \n";
    }

    MailService::CreateAndSendMail("Monthly Report", $mailBody);
    echo $mailBody;

    $userController = null;
}

function YearlyReport()
{
    $userController = new UserController();
    $tmp_date = DateTimeImmutable::createFromFormat("Y", "2023");

    $userController = new UserController();
    $newDailyUserArray = $userController->GetYearlyNewUsers($tmp_date);
    $nbNewUsers = count($newDailyUserArray);

    $mailBody = '';

    $mailBody .= "________________________________ \n";

    $mailBody .= "On the Year {$tmp_date->format("Y")} \n";
    $mailBody .= "You had {$nbNewUsers} new user \n";

    for ($i = 0; $i < count($newDailyUserArray); $i++) {
        $mailBody .= "{$newDailyUserArray[$i]["first_name"]} {$newDailyUserArray[$i]["last_name"]} \n";
        $mailBody .= "account created : {$newDailyUserArray[$i]["created_at"]} \n";
        $mailBody .= "email : {$newDailyUserArray[$i]["email"]} \n";
        $mailBody .= "------------------------------------- \n";
    }

    MailService::CreateAndSendMail("Yearly Report", $mailBody);
    echo $mailBody;
    $userController = null;
}

function GlobalReport()
{

    echo "Debut de global report \n";

    try {
        echo "Avant new UserController\n";
        $userController = new UserController();
        $userArray = $userController->GetUsersByCreatedAt();
        $nbUser = count($userArray);

        echo "Après new UserController\n";
        echo "users got {$userArray[0]["created_at"]} \n";
    } catch (Exception $e) {
        echo "Erreur: " . $e->getMessage() . "\n";
    }


    // $baseDate_YM = DateTimeImmutable::createFromFormat("Y-m",, new DateTimeZone("Europe/Paris") );

    // for ($i = 0; $i < count($usersArray); $i++){

    // }


    $userController = null;
}

if (count($argv) > 1) {

    switch ($argv[1]) {
        case "DailyReport":
            DailyReport();
            break;
        case "MonthlyReport":
            MonthlyReport();
            break;
        case "YearlyReport":
            YearlyReport();
            break;
        case "GlobalReport":
            echo "Starting Global Report \n";
            GlobalReport();
            break;
        default:
            echo "Incorrect Report Parameter \n";
            break;
    }
}
