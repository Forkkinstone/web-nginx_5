<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Все данные</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 50px;
            background-color: #f4f7f6;
            font-family: Arial, sans-serif;
        }
        .data-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 80%;
            max-width: 500px;
        }
        h2 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        ul { list-style: none; padding: 0; }
        li { padding: 8px 0; border-bottom: 1px solid #eee; }
        a { margin-top: 20px; text-decoration: none; color: #4CAF50; font-weight: bold; }
    </style>
</head>
<body>
    <div class="data-container">
        <h2>Все сохранённые данные:</h2>
        <ul>
            <?php
            if(file_exists("data.txt")){
                $lines = file("data.txt", FILE_IGNORE_NEW_LINES);
                foreach($lines as $line){
                    if (!empty($line)) {
                        list($name, $email) = explode(";", $line);
                        echo "<li>👤 <b>$name</b> — $email</li>";
                    }
                }
            } else {
                echo "<li>Данных нет</li>";
            }
            ?>
        </ul>
        <br>
        <a href="index.php">← На главную</a>
    </div>
</body>
</html>
