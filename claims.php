<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $car_number = $_POST['car_number'];
    $description = $_POST['description'];

    // Проверяем, был ли загружен файл без ошибок
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_path = 'uploads/' . $file_name;

        // Перемещаем загруженный файл в директорию uploads
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Файл успешно загружен, вставляем данные в базу данных
            $sql = "INSERT INTO ads (user_id, car_number, description, status, image_path) 
                    VALUES ('$user_id', '$car_number', '$description', 'новое', '$file_path')";

            if ($conn->query($sql) === TRUE) {
                // Переадресация на profile.php после успешной вставки
                header("Location: profile.php");
                exit(); // Убедимся, что скрипт прекращает выполнение после переадресации
            } else {
                echo "Ошибка: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Ошибка при загрузке файла.";
        }
    } else {
        echo "Файл не был загружен или произошла ошибка.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заявления</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Мои заявления</h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="login.php">Авторизация</a></li>
                <li><a href="register.php">Регистрация</a></li>
                <li><a href="admin_login.php">Админ панель</a></li>
                <hr>
                <li><a href="new_claim.php">Оставить новое заявление</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Номер автомобиля</th>
                        <th>Описание</th>
                        <th>Статус</th>
                        <th>Изображение</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($claims as $claim): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($claim['car_number']); ?></td>
                            <td><?php echo htmlspecialchars($claim['description']); ?></td>
                            <td><?php echo htmlspecialchars($claim['status']); ?></td>
                            <td>
                                <?php if (!empty($claim['image_path'])): ?>
                                    <img src="<?php echo $claim['image_path']; ?>" alt="Изображение">
                                <?php else: ?>
                                    Нет изображения
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Нарушениям.Нет</p>
    </footer>
</body>
</html>
