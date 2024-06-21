<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fio = $_POST['fio'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (fio, phone, email, login, password) VALUES ('$fio', '$phone', '$email', '$login', '$password')";

    if ($conn->query($sql) === TRUE) {
        session_start();
        $_SESSION['user_id'] = $conn->insert_id;
        header("Location: profile.php");
        exit();
    } else {
        echo "Ошибка: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Регистрация</h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="login.php">Авторизация</a></li>
                <li><a href="register.php">Регистрация</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form action="register.php" method="post">
            <label for="fio">ФИО</label>
            <input type="text" id="fio" name="fio" required>
            <label for="phone">Телефон</label>
            <input type="tel" id="phone" name="phone" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" required>
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </main>
    <footer>
        <p>&copy; AutoМОБИЛЬ!</p>
    </footer>
</body>
</html>
