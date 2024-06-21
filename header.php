<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoМОБИЛЬ!</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>AutoМОБИЛЬ!</h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="login.php">Авторизация</a></li>
                    <li><a href="register.php">Регистрация</a></li>
                <?php else: ?>
                    <li><a href="logout.php">Выход</a></li>
                    <li><a href="profile.php">Профиль</a></li> <!-- Добавленная строка для профиля -->
                <?php endif; ?>
            </ul>
        </nav>
    </header>
