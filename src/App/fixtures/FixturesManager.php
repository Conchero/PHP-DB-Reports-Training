<?php


if ($argc > 1) {
    for ($i = 1; $i < $argc; $i++) {
        $explodedLowerArg = explode("_", strtolower(trim($argv[$i])));
        switch ($explodedLowerArg[0]) {
            case "user":
                require_once __DIR__ . "/UserFixtures.php";
                $userFixtures = new UserFixtures();

                switch ($explodedLowerArg[1]) {
                    case 'create':
                        $userFixtures->FillDBFromCSV();
                        break;
                    case 'delete':
                        $userFixtures->DeleteFixturesFromDB();
                        break;

                    default:
                        echo "Invalid User Fixture parameter";
                        break;
                }
                break;
            default:
                echo "Invalid Fixture parameter";
                break;
        }
    }
}
