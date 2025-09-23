<?php

isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] = "/var/www/html" : "server root is set";

require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Entities/User.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/DatabaseController.php";
require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/UserController.php";

class UserFixtures
{
    private ?string $pathToFixtureFile ;
    private ?UserController $userController;

    function __construct()
    {
        $this->pathToFixtureFile = __DIR__ . "/fixtures_files/fake_users_50.csv";
        $this->userController = new UserController();
    }
    
    function __destruct()
    {
        $this->pathToFixtureFile = null;
        $this->userController = null;
    }


    public function FillDBFromCSV()
    {
        $_path = $this->pathToFixtureFile;
        $parameters = array();
        $row = 0;
        $user = array();
        if (($file = fopen($_path, 'r')) !== FALSE) {
            while (($data = fgetcsv($file, 0, ',', '"', '\\')) !== FALSE) {

                $userParameter = array();
                $num = count($data);
                for ($c = 1; $c < $num; $c++) {
                    if ($row !== 0) {
                        if ($c - 1 < count($parameters)) {
                            $userParameter[$parameters[$c - 1]] = $data[$c];
                        }
                    } else {
                        array_push($parameters, $data[$c]);
                    }
                }
                if ($row !== 0) {
                    $format = 'Y-m-d H:i:s';
                    array_push($user, new User($userParameter["email"], $userParameter["password"], DateTimeImmutable::createFromFormat($format, $userParameter["created_at"]), $userParameter["first_name"], $userParameter["last_name"]));
                }
                $row++;
            }
            fclose($file);

            if (count($user) > 0) {
                $this->userController->PushNewUsersInDatabase($user);
            }
        }
    }


    public function DeleteFixturesFromDB() {
        $dbController = new DatabaseController();
    }
}
