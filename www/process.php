<?php
date_default_timezone_set('Europe/Kaliningrad');
session_start();

$username = htmlspecialchars($_POST['userName'] ?? ''); 
$email = htmlspecialchars($_POST['userEmail'] ?? ''); 

$errors = [];

if(empty($username)){
$errors[] = "Имя не может быть пустым";
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors[] = "Некорректный email";
}

if(!empty($errors)){
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

$_SESSION['username'] = $username;
$_SESSION['email'] = $email;

$line = $username . ";" . $email . "\n";

file_put_contents("data.txt", $line, FILE_APPEND);

setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

require_once 'ApiClient.php';
$api = new ApiClient();

$url = 'https://data.gov.ru/opendata/7705846236-blagoorg/data-20200214T0000.json';
$apiData = $api->request($url);

$_SESSION['api_data'] = $apiData;


header("Location: index.php");
exit();
?>
