<?php


if ($_POST) {
    require_once $_SERVER["DOCUMENT_ROOT"] . "/fixtures/UserFixtures.php";
    require_once $_SERVER["DOCUMENT_ROOT"] . "/backend/Controllers/UserController.php";


    $userFixture = new UserFixtures();
    $userController = new UserController();


    $userController->PushNewUsersInDatabase($userFixture->ReadCSV());

    echo "pushed sucessfully";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Fixtures</title>
</head>

<body>
    <h1>Make Fixtures</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h2>
            Make user fixtures
        </h2>
        <input type="submit" name="fixtures-submit" value="Make fixtures">
    </form>
</body>

</html>