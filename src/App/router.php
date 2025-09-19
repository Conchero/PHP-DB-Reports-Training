<?php


$path = $_SERVER['REQUEST_URI'];
$cleanPath = preg_replace('/\?.*/', '', $path);

// // Vérifier d'abord si c'est une route dynamique
// if (preg_match('#^/(\d+)/system-view/?$#', $cleanPath, $m)) {
//     $id = (int)$m[1];
//     include './templates/header.php';
//     include './templates/system_view.php';
//     exit;
// }

// $id = $m[1];

echo $cleanPath;


include './templates/header.php';

switch ($cleanPath) {
    case '/':
        include './templates/home.php';
        break;
    case '/fixture':
        include './templates/fixtures_management.php';
        break;
    default:
        echo 'Page introuvable - 505 ';
        break;
}
