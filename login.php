<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE email=?";

    // Проверяем подготовленное выражение и обрабатываем ошибку, если есть
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Ошибка подготовки запроса: ' . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: index.php");
            exit();
        } else {
            echo "Неправильный логин или пароль";
        }
    } else {
        echo "Неправильный логин или пароль";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <form action="login.php" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Войти</button>
        </form>
    </main>
    <footer>
        <p>&copy; AutoМОБИЛЬ!</p>
    </footer>
</body>
</html>
