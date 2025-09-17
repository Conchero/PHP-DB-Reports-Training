<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/backend/Controllers/BaseController.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/backend/Entity/User.php';

class UserController extends BaseController
{

    function CreateUser(string $_email, string $_password, DateTimeImmutable $_createdAt): ?User
    {
        $newUser  = new User($_email, $_password, $_createdAt);
        return $newUser->CheckIntegrity() ? $newUser : null;
    }

    function PushNewUsersInDatabase(array $_userArray)
    {

        try {
            $valuesForSQL = '';

            for ($i = 0; $i < count($_userArray); $i++) {
                if ($_userArray[$i]->CheckIntegrity()) {
                    $valuesForSQL .= "(DEFAULT, :email_{$i}, :password_{$i}, :created_at_{$i})";
                    if ($i < (count($_userArray) - 1))
                        $valuesForSQL .= ",";
                }

                if ($valuesForSQL != '') {
                    $sql = "INSERT INTO users VALUES{$valuesForSQL}";
                    $results = $this->dbController->GetDBH()->prepare($sql);
                    for ($i = 0; $i < count($_userArray); $i++) {
                        $results->bindParam(":email_{$i}", $_userArray[$i]->GetEmail(), PDO::PARAM_STR);
                        $results->bindParam(":password_{$i}", password_hash($_userArray[$i]->GetPassword(), PASSWORD_DEFAULT), PDO::PARAM_STR);
                        $results->bindParam(":created_at_{$i}", $_userArray[$i]->GetCreatedAt());
                    }
                    $results->execute();
                }
            }
        } catch (\PDOException $e) {
            echo $e;
        }
    }
}
