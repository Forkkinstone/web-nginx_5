<?php 
date_default_timezone_set('Europe/Kaliningrad');
session_start(); 
require_once 'UserInfo.php';
$user_info = UserInfo::getInfo();
$api_data = $_SESSION['api_data'] ?? null;
$last_visit = $_COOKIE['last_submission'] ?? 'Это ваша первая отправка!';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f7f6;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 10px; background: #eef; padding: 5px; border-radius: 5px; }
        a { color: #4CAF50; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div style="border: 1px solid #ccc; padding: 15px; margin: 20px; border-radius: 8px; max-width: 600px;">
    
    <p><strong>Ваш IP:</strong> <?= htmlspecialchars($user_info['ip']) ?></p>
    <p><strong>Браузер:</strong> <?= htmlspecialchars($user_info['user_agent']) ?></p>
    <p><strong>Последняя отправка:</strong> <?= htmlspecialchars($last_visit) ?></p>

    <hr>

    <h3>Благотворительные организации (API):</h3>
    <?php if ($api_data): ?>
        <ul>
            <?php 
            $count = 0;
            foreach ($api_data as $item): 
                if ($count >= 5) break;
                $name = $item['name'] ?? $item['value'] ?? $item['FullName'] ?? 'Данные не найдены';
                echo "<li>" . htmlspecialchars($name) . "</li>";
                $count++;
            endforeach; 
            ?>
        </ul>
    <?php else: ?>
        <p>Отправьте форму, чтобы загрузить данные из API.</p>
    <?php endif; ?>
</div>

<div class="container">
    <h2>Главная страница</h2>

    <?php if(isset($_SESSION['errors'])): ?>
    <ul style="color:red; list-style: none; padding: 10px; border: 1px solid red; border-radius: 5px; background: #fff5f5;">
        <?php foreach($_SESSION['errors'] as $error): ?>
            <li>⚠️ <?= $error ?></li>
        <?php endforeach; ?>
    </ul>
    <?php unset($_SESSION['errors']); // Удаляем ошибки из сессии после показа ?><?php endif; ?>

    <?php if(isset($_SESSION['username'])): ?>
        <p>✅ Данные из сессии успешно получены:</p>
        <ul>
            <li>👤 Имя: <?= $_SESSION['username'] ?></li>
            <li>📧 Email: <?= $_SESSION['email'] ?></li>
        </ul>
    <?php else: ?>
        <p>ℹ️ Данных пока нет. Пожалуйста, заполните форму.</p>
    <?php endif; ?>

    <hr>

    <nav>
        <a href="form.html">Заполнить форму</a> | 
        <a href="view.php">Посмотреть все данные</a>
    </nav>
</div>

</body>
</html>
