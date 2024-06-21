<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <h1>Добро пожаловать на Площадку AutoМОБИЛЬ!</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Вы вошли в систему как пользователь.</p>
        <?php elseif (isset($_SESSION['admin_logged_in'])): ?>
            <p>Вы вошли в систему как администратор.</p>
        <?php else: ?>
            <p>Пожалуйста, войдите в систему или зарегистрируйтесь.</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; AutoМОБИЛЬ!</p>
    </footer>
</body>
</html>
