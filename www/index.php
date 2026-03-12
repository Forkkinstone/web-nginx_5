<?php 
date_default_timezone_set('Europe/Kaliningrad');
session_start(); 
require_once 'UserInfo.php';

require_once 'db.php';
require_once 'Volunteer.php';

$user_info = UserInfo::getInfo();
$api_data = $_SESSION['api_data'] ?? null;
$last_visit = $_COOKIE['last_submission'] ?? 'Это ваша первая отправка!';

$volunteer = new Volunteer($pdo);
$allVolunteers = $volunteer->getAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная - Лабораторная №5</title>
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
            padding: 20px 0;
        }
        /* Универсальный стиль для всех блоков (карточек) */
        .card-box {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 90%;
            text-align: left; /* Текст слева для удобного чтения */
        }
        .card-box h2, .card-box h3 {
            text-align: center;
            margin-top: 0;
            color: #333;
        }
        ul { list-style: none; padding: 0; }
        
        /* Стили для простых списков (API и сессии) */
        li.simple-item { 
            margin-bottom: 10px; 
            background: #eef; 
            padding: 10px; 
            border-radius: 5px; 
        }
        
        /* Стили для карточек волонтёров из MySQL */
        li.db-item {
            background: #f9f9f9;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #4CAF50;
            color: #333;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        a { color: #4CAF50; text-decoration: none; font-weight: bold; }
        a:hover { text-decoration: underline; }
        hr { border: 0; height: 1px; background: #ddd; margin: 20px 0; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

<div class="card-box">
    <h2>Лабораторная работа №5</h2>
    
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
                echo "<li class='simple-item'>📋 " . htmlspecialchars($name) . "</li>";
                $count++;
            endforeach; 
            ?>
        </ul>
    <?php else: ?>
        <p class="text-center">Отправьте форму, чтобы загрузить данные из API.</p>
    <?php endif; ?>
</div>

<div class="card-box">
    <h3>👥 Зарегистрированные волонтёры</h3>
    
    <?php if (!empty($allVolunteers)): ?>
        <ul>
            <?php foreach($allVolunteers as $row): ?>
                <li class="db-item">
                    <strong>👤 <?= htmlspecialchars($row['name']) ?></strong> (<?= htmlspecialchars($row['age']) ?> лет)<br>
                    <div style="margin-top: 8px; font-size: 0.9em; line-height: 1.5;">
                        🎯 <strong>Направление:</strong> <?= htmlspecialchars($row['direction']) ?><br>
                        🤝 <strong>Вид помощи:</strong> <?= htmlspecialchars($row['help_type']) ?><br>
                        ⭐️ <strong>Опыт:</strong> <?= $row['has_experience'] ? '<span style="color:green;">✅ Есть</span>' : '<span style="color:red;">❌ Нет</span>' ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-center" style="color: #777;">В базе данных пока нет ни одной заявки.</p>
    <?php endif; ?>
</div>

<div class="card-box text-center">
    <h3>Статус сессии</h3>

    <?php if(isset($_SESSION['errors'])): ?>
    <ul style="color:red; list-style: none; padding: 10px; border: 1px solid red; border-radius: 5px; background: #fff5f5; text-align: left;">
        <?php foreach($_SESSION['errors'] as $error): ?>
            <li style="margin-bottom: 5px;">⚠️ <?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php unset($_SESSION['errors']); ?><?php endif; ?>

    <?php if(isset($_SESSION['username'])): ?>
        <p style="color: green; font-weight: bold;">✅ Последняя заявка из сессии:</p>
        <ul style="text-align: left;">
            <li class="simple-item">👤 Имя: <?= htmlspecialchars($_SESSION['username']) ?></li>
            <li class="simple-item">📧 Email: <?= htmlspecialchars($_SESSION['email']) ?></li>
        </ul>
    <?php else: ?>
        <p style="color: #666;">ℹ️ Данных пока нет. Пожалуйста, заполните форму.</p>
    <?php endif; ?>

    <hr>

    <nav>
        <a href="form.html">📝 Заполнить форму</a> | 
        <a href="view.php">🗄 Посмотреть старые данные</a>
    </nav>
</div>

</body>
</html>
