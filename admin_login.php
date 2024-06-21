<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в панель администратора</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Вход в панель администратора</h1>
        <nav>
            <ul>
            <li><a href="index.php">Главная</a></li>
            <li><a href="login.php">Авторизация</a></li>
                <li><a href="register.php">Регистрация</a></li>
                <li><a href="admin_login.php">Админ панель</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form action="admin_panel.php" method="post">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" required>
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Войти</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Нарушениям.Нет</p>
    </footer>
</body>
</html>
