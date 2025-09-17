<?php



class UserFixtures
{


    public function ReadCSV(string $_path)
    {
        $row = 1;
        if (($file = fopen($_path, 'r')) !== FALSE) {
            while (($data = fgetcsv($file, 0, ',', '"', '\\')) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                for ($c = 0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($file);
        }
    }
}


$pathToFixtureFile = __DIR__ . "/fixtures_files/fake_users_50.csv";


$fixture = new UserFixtures();
$fixture->ReadCSV($pathToFixtureFile);

exit();
