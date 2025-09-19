<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/backend/Controllers/BaseController.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/backend/Entities/User.php';

class UserController extends BaseController
{

    function CreateUser(string $_email, string $_password, DateTimeImmutable $_createdAt, string $_firstName, string $_lastName): ?User
    {
        $newUser  = new User($_email, $_password, $_createdAt,  $_firstName, $_lastName);
        return $newUser->CheckIntegrity() ? $newUser : null;
    }

    function PushNewUsersInDatabase(array $_userArray)
    {

        try {
            $valuesForSQL = '';

            for ($i = 0; $i < count($_userArray); $i++) {
                if ($_userArray[$i]->CheckIntegrity()) {
                    $valuesForSQL .= "(DEFAULT, :email_{$i}, :password_{$i}, :created_at_{$i}, :first_name_{$i},:last_name_{$i})";
                    if ($i < (count($_userArray) - 1))
                        $valuesForSQL .= ",";
                }
            }

            if ($valuesForSQL != '') {
                $sql = "INSERT INTO users VALUES{$valuesForSQL}";
                $results = $this->dbController->GetDBH()->prepare($sql);
                for ($i = 0; $i < count($_userArray); $i++) {
                    $results->bindParam(":first_name_{$i}", $_userArray[$i]->GetFirstName(), PDO::PARAM_STR);
                    $results->bindParam(":last_name_{$i}", $_userArray[$i]->GetLastName(), PDO::PARAM_STR);
                    $results->bindParam(":email_{$i}", $_userArray[$i]->GetEmail(), PDO::PARAM_STR);
                    $results->bindParam(":password_{$i}", password_hash($_userArray[$i]->GetPassword(), PASSWORD_DEFAULT), PDO::PARAM_STR);
                    $results->bindParam(":created_at_{$i}", $_userArray[$i]->GetCreatedAt()->format('Y-m-d H:i:s'));
                }
                $results->execute();
            }
        } catch (\PDOException $e) {
            echo $e;
        }
    }


    function GetAllUsers() 
    {
         $sql = "SELECT COUNT(id) as nb_users FROM users";
        $results = $this->dbController->GetDBH()->prepare($sql);
        $results->execute();
        
        echo "nb users " . $results->fetchAll()[0]["nb_users"];
    }
}
