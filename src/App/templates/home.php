<?php 


// require_once $_SERVER["DOCUMENT_ROOT"] . "/fixtures/UserFixtures.php";
 require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/UserController.php";


// $userFixture = new UserFixtures();
 $userController = new UserController();


 $userController->GetAllUsers();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Welcome Home</h1>
</body>

</html>
