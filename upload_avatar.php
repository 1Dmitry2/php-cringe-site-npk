<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Обработка загрузки файла
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"])) {
    $file_name = $_FILES["avatar"]["name"];
    $file_tmp = $_FILES["avatar"]["tmp_name"];
    $file_type = $_FILES["avatar"]["type"];

    // Проверка, что загруженный файл является изображением
    $allowed_types = array("image/jpeg", "image/png", "image/gif");
    if (in_array($file_type, $allowed_types)) {
        $uploads_dir = "uploads/";
        $avatar_path = $uploads_dir . $file_name;

        if (move_uploaded_file($file_tmp, $avatar_path)) {
            // Обновление записи в базе данных с указанием пути к загруженному изображению
            $sql_update_avatar = "UPDATE users SET avatar_path = '$avatar_path' WHERE id = $user_id";

            if ($conn->query($sql_update_avatar) === TRUE) {
                header("Location: profile.php");
                exit();
            } else {
                echo "Ошибка при обновлении записи: " . $conn->error;
            }
        } else {
            echo "Ошибка при загрузке файла.";
        }
    } else {
        echo "Файл не является изображением - $file_type.";
    }
} else {
    echo "Некорректный запрос на загрузку файла.";
}

$conn->close();
?>
