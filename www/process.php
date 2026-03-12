<?php
date_default_timezone_set('Europe/Kaliningrad');
session_start();

//подключение файлов для работы с бд и получение данных из формы
require_once 'db.php';
require_once 'Volunteer.php';

$username = htmlspecialchars($_POST['userName'] ?? ''); 
$email = htmlspecialchars($_POST['userEmail'] ?? ''); 
$age = intval($_POST['userAge'] ?? 0);
$direction = htmlspecialchars($_POST['direction'] ?? '');
$has_experience = isset($_POST['hasExperience']) ? 1 : 0; 
$help_type = htmlspecialchars($_POST['helpType'] ?? '');

$errors = [];

if(empty($username)){
    $errors[] = "Имя не может быть пустым";
}
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors[] = "Некорректный email";
}
if($age <= 0){
    $errors[] = "Укажите корректный возраст";
}

if(!empty($errors)){
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

$_SESSION['username'] = $username;
$_SESSION['email'] = $email;

$volunteer = new Volunteer($pdo);
$volunteer->add($username, $age, $direction, $has_experience, $help_type);

setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

require_once 'ApiClient.php';
$api = new ApiClient();
$url = 'https://data.gov.ru/opendata/7705846236-blagoorg/data-20200214T0000.json';
$apiData = $api->request($url);
$_SESSION['api_data'] = $apiData;

header("Location: index.php");
exit();
