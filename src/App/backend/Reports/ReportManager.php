<?php

isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] = "/var/www/html" :  "server root is set";

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/controllers/UserController.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/services/Mail.php";


function DailyReport()
{

    $dotenv = __DIR__ . "/../../.env";
    if (file_exists($dotenv)) {
        foreach (file($dotenv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }

    file_put_contents("/var/log/cronjobLogs/env.txt", print_r($_ENV, true));

    $userController = new UserController();
    $newDailyUserArray = $userController->GetDailyNewUsers(DateTimeImmutable::createFromFormat("Y-m-d", "2023-11-01"));
    $nbNewUsers = count($newDailyUserArray);


    echo "new Users {$nbNewUsers} \n";

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


    $userController = null;
}

function YearlyReport()
{
    $userController = new UserController();
    $tmp_date = DateTimeImmutable::createFromFormat("Y", "2023");

    $userController = new UserController();
    $newDailyUserArray = $userController->GetYearlyNewUsers($tmp_date);
    $nbNewUsers = count($newDailyUserArray);


    $userController = null;
}

function GlobalReport()
{

    echo "Debut de global report \n";

    try {
        $userController = new UserController();
        $userArray = $userController->GetUsersByCreatedAt();

        //current date meant to be changed in first for iteration 
        $currentDate =  DateTimeImmutable::createFromFormat("Y-m-d", "0000-00-00");

        //Used to create to the folder and subfolder 
        $reportYearFolderPath = null;
        $reportMonthSubFolderPath = null;

        $missingReportPathArray = array();
        $missingReportUsersInfoArray = array();

        for ($i = 0; $i < count($userArray); $i++) {
            //Get the user full date time created at
            $createdAt = DateTimeImmutable::createFromFormat("Y-m-d", $userArray[$i]["created_at"]);

            array_push($missingReportUsersInfoArray, [$userArray[$i]["first_name"], $userArray[$i]["last_name"], $userArray[$i]["email"], $userArray[$i]["created_at"]]);

            if ($createdAt->format("Y-m-d") !== $currentDate->format("Y-m-d")) {

                //if the month or year is changed then 
                if ($createdAt->format("Y-m") !== $currentDate->format("Y-m")) {

                    //if the year is different then create new year folder
                    if ($createdAt->format("Y") !== $currentDate->format("Y")) {

                        $reportYearFolderPath = "/var/reports/{$createdAt->format("Y")}/";

                        if (!is_dir($reportYearFolderPath)) {
                            mkdir($reportYearFolderPath);
                            echo "New folder for year {$createdAt->format("Y")} \n";
                        }
                    }

                    //change the current day to be new month of current year
                    $currentDate = $createdAt;
                    $reportMonthSubFolderPath =   $reportYearFolderPath . "{$currentDate->format("Y-m")}/";

                    if (!is_dir($reportMonthSubFolderPath)) {
                        mkdir($reportMonthSubFolderPath);
                        echo "New sub folder for month {$currentDate->format("m")} \n";
                    }
                }

                $reportPath = $reportMonthSubFolderPath . $createdAt->format("Y-m-d") . ".csv";
                $dailyNewUsers = $missingReportUsersInfoArray;
                is_null($reportYearFolderPath && $reportMonthSubFolderPath) ? $missingReportPathArray = null : array_push($missingReportPathArray, [$reportPath, $dailyNewUsers]);
                $missingReportUsersInfoArray = array();
            }
        }

        for ($i = 0; $i < count($missingReportPathArray); $i++) {
            echo $missingReportPathArray[$i][0] . "\n";
            CreateReport($missingReportPathArray[$i][0], $missingReportPathArray[$i][1]);
        }

        MailService::CreateAndSendMailWithAttachement("Global Report", "Test attahcemnt", $missingReportPathArray[0][0]);

    } catch (Exception $e) {
        echo "Erreur: " . $e->getMessage() . "\n";
    }


    $userController = null;
}


function CreateReport(string $reportPath, array $infoArray)
{
    if (file_exists($reportPath)) {
        echo "File For report already exist";
        return;
    }

    $pathNameArray = explode("/", $reportPath);
    $fileName = $pathNameArray[count($pathNameArray) - 1];

    $newReport = fopen($reportPath, "w");
    if (is_null($infoArray) || count($infoArray) === 0) {
        fwrite($newReport, "Error Report something went wrong");
        fclose($newReport);
        return;
    }

    $fileColumnArray = ["first_name", "last_name", "email", "created_at"];

    for ($i = 0; $i <= count($infoArray); $i++) {
        for ($j = 0; $j < count($fileColumnArray); $j++) {

            if ($i === 0) {
                fwrite($newReport, $fileColumnArray[$j] . ($j < count($fileColumnArray) - 1 ? ", " : ''));
            } else {
                if ($i - 1 < count($infoArray)) {
                    fwrite($newReport, $infoArray[$i - 1][$j] . ($j < count($fileColumnArray) - 1 ? ", " : ''));
                }
            }
        }
        fwrite($newReport, "\n");
    }
    fclose($newReport);
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


DailyReport();
