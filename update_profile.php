<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Проверяем, была ли отправлена форма для обновления профиля
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Защита от SQL-инъекций (хорошая практика)
    $fio = mysqli_real_escape_string($conn, $_POST['fio']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Запрос на обновление данных пользователя
    $update_sql = "UPDATE users SET fio = '$fio', phone = '$phone', email = '$email' WHERE id = $user_id";
    
    if ($conn->query($update_sql) === TRUE) {
        // Обновляем данные в текущей сессии пользователя для отображения обновленной информации
        $_SESSION['fio'] = $fio;
        $_SESSION['phone'] = $phone;
        $_SESSION['email'] = $email;
        
        // Редирект обратно на страницу профиля после успешного обновления
        header("Location: profile.php");
        exit();
    } else {
        echo "Ошибка при обновлении записи: " . $conn->error;
    }
}

$conn->close();
?>
