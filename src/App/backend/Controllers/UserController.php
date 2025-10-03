<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/backend/controllers/BaseController.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/backend/entities/User.php';

class UserController extends BaseController
{

    //basic new user management
    function CreateUser(string $_email, string $_password, DateTimeImmutable $_createdAt, string $_firstName, string $_lastName): ?User
    {
        $newUser  = new User($_email, $_password, $_createdAt,  $_firstName, $_lastName);
        return $newUser->CheckIntegrity() ? $newUser : null;
    }


    function CheckUserAlreadyExist(string $_email): bool
    {
        if (!is_null($_email)) {
            $sql = "SELECT email FROM users WHERE email = :email ";
            $result = $this->dbh->prepare($sql);
            $result->bindParam(":email", $_email, PDO::PARAM_STR);
            $result->execute();

            return $result->rowCount() >= 1;
        }
        return false;
    }

    function PushNewUsersInDatabase(array $_userArray)
    {
        try {
            $valuesForSQL = '';

            for ($i = 0; $i < count($_userArray); $i++) {
                if ($_userArray[$i]->CheckIntegrity() && !$this->CheckUserAlreadyExist($_userArray[$i]->GetEmail())) {
                    $valuesForSQL .= "(DEFAULT, :email_{$i}, :password_{$i}, :created_at_{$i}, :first_name_{$i},:last_name_{$i})";
                    if ($i < (count($_userArray) - 1))
                        $valuesForSQL .= ",";
                } else {
                    echo $_userArray[$i]->CheckIntegrity() ? "{$_userArray[$i]->GetEmail()} already have an account \n" : "User informations are wrong \n";
                }
            }


            //For multiple insertion INSERT INTO users VALUES(a,b,c),(d,e,f)
            if ($valuesForSQL != '') {
                $sql = "INSERT INTO users VALUES{$valuesForSQL}";
                $results = $this->dbh->prepare($sql);
                for ($i = 0; $i < count($_userArray); $i++) {
                    $results->bindParam(":first_name_{$i}", $_userArray[$i]->GetFirstName(), PDO::PARAM_STR);
                    $results->bindParam(":last_name_{$i}", $_userArray[$i]->GetLastName(), PDO::PARAM_STR);


                    $results->bindParam(":email_{$i}", $_userArray[$i]->GetEmail(), PDO::PARAM_STR);
                    $hashedPassword = password_hash($_userArray[$i]->GetPassword(), PASSWORD_DEFAULT);


                    $results->bindParam(":password_{$i}", $hashedPassword, PDO::PARAM_STR);
                    $results->bindParam(":created_at_{$i}", $_userArray[$i]->GetCreatedAt()->format('Y-m-d H:i:s'));


                    echo "adding {$_userArray[$i]->GetEmail()} \n";
                }
                $results->execute();
            }
        } catch (\PDOException $e) {
            echo $e;
        }
    }

    //Used for global report
    function GetUsersByCreatedAt(): array
    {
        $sql = "SELECT first_name, last_name, email, created_at FROM users ORDER BY created_at DESC, first_name ASC";
        $results = $this->dbh->prepare($sql);
        $results->execute();

        return $results->fetchAll();
    }

    //Used for daily report
    function GetDailyNewUsers(?DateTimeImmutable $targetedDate = null): ?array
    {
        $dateTimeFormat = "Y-m-d";

        if (!is_null($targetedDate) && count(date_parse($targetedDate->format($dateTimeFormat))["warnings"]) > 0) {
            echo "Targeted Date not valid \n";
            return null;
        }
        $dateTimeZone = new DateTimeZone('Europe/Paris');

        $dateTime = is_null($targetedDate) ? 'now' : $targetedDate->format($dateTimeFormat);


        $date = new DateTime($dateTime, $dateTimeZone);
        $currentDate = $date->format($dateTimeFormat);

        $sql = "SELECT first_name, last_name, email, created_at FROM users WHERE to_char(created_at, 'YYYY-MM-DD') = '{$currentDate}' ORDER BY created_at DESC, first_name ASC";
        $results = $this->dbh->prepare($sql);
        $results->execute();

        return $results->fetchAll();
    }

    //Used for monthly report
    function GetMonthlyNewUsers(?DateTimeImmutable $targetedDate = null): ?array
    {
        $dateTimeFormat = "Y-m";

        if (!is_null($targetedDate) && count(date_parse($targetedDate->format($dateTimeFormat))["warnings"]) > 0) {
            echo "Targeted Date not valid \n";
            return null;
        }
        $dateTimeZone = new DateTimeZone('Europe/Paris');

        $dateTime = is_null($targetedDate) ? 'now' : $targetedDate->format($dateTimeFormat);


        $date = new DateTime($dateTime, $dateTimeZone);
        $currentDate = $date->format($dateTimeFormat);

        $sql = "SELECT first_name, last_name, email, created_at FROM users WHERE to_char(created_at, 'YYYY-MM') = '{$currentDate}' ORDER BY created_at DESC, first_name ASC";
        $results = $this->dbh->prepare($sql);
        $results->execute();

        return $results->fetchAll();
    }


    //Used for yearly report
    function GetYearlyNewUsers(?DateTimeImmutable $targetedDate = null): ?array
    {
        $dateTimeFormat = "Y";

        if (!is_null($targetedDate) && count(date_parse($targetedDate->format($dateTimeFormat))["warnings"]) > 0) {
            echo "Targeted Date not valid \n";
            return null;
        }
        $dateTimeZone = new DateTimeZone('Europe/Paris');

        $dateTime = is_null($targetedDate) ? 'now' : $targetedDate->format($dateTimeFormat);


        $date = new DateTime($dateTime, $dateTimeZone);
        $currentDate = $date->format($dateTimeFormat);

        $sql = "SELECT first_name, last_name, email, created_at FROM users WHERE to_char(created_at, 'YYYY') = '{$currentDate}' ORDER BY created_at DESC, first_name ASC";
        $results = $this->dbh->prepare($sql);
        $results->execute();

        return $results->fetchAll();
    }


    function DeleteMultipleUsers(array $_userEmail)
    {
        $whereForSQL = '';

        for ($i = 0; $i < count($_userEmail); $i++) {
            if ($this->CheckUserAlreadyExist($_userEmail[$i])) {
                $whereForSQL .= " email = :email_{$i} ";
                if ($i < count($_userEmail) - 1) {
                    $whereForSQL .= " OR ";
                }
            } else {
                echo "Can't delete user {$_userEmail[$i]} it does not exist \n";
            }
        }

        //For multiple deletion DELETE FROM users WHERE (cond = value), (cond = value)
        if ($whereForSQL != '') {
            $sql = "DELETE FROM users WHERE {$whereForSQL}";
            $results = $this->dbh->prepare($sql);

            for ($i = 0; $i < count($_userEmail); $i++) {
                $results->bindParam(":email_{$i}", $_userEmail[$i], PDO::PARAM_STR);
                echo "deleting {$_userEmail[$i]} \n";
            }

            $results->execute();
        }
    }
}
