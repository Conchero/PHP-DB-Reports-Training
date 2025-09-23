<?php


if ($argc > 1)
{
    for ($i = 1; $i < $argc; $i++)
    {
        switch (strtolower(trim($argv[$i]))) {
            case "user_create":
                require_once __DIR__ . "/UserFixtures.php";
                $userFixtures = new UserFixtures();
                $userFixtures->FillDBFromCSV();
                break;
            default:
            echo "Invalid parameter";
            break;
        }
    }
}



