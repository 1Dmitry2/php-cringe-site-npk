    <?php
    include 'db.php';
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['user_id'];
        $car_number = $_POST['car_number'];
        $description = $_POST['description'];

        // Проверяем, был ли загружен файл без ошибок
        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_path = 'uploads/' . $file_name;

            // Перемещаем загруженный файл в директорию uploads
            if(move_uploaded_file($file_tmp, $file_path)) {
                // Файл успешно загружен, вставляем данные в базу данных
                $sql = "INSERT INTO claims (user_id, car_number, description, status, image_path) 
                        VALUES ('$user_id', '$car_number', '$description', 'новое', '$file_path')";

                if ($conn->query($sql) === TRUE) {
                    // Переадресация на claims.php после успешной вставки
                    header("Location: claims.php");
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
        <title>Новое заявление</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Новое заявление</h1>
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
            <form action="new_claim.php" method="post" enctype="multipart/form-data">
                <label for="car_number">Номер автомобиля</label>
                <input type="text" id="car_number" name="car_number" required>
                <label for="description">Описание нарушения</label>
                <textarea id="description" name="description" required></textarea>
                <label for="image">Загрузить изображение</label>
                <input type="file" id="image" name="image">
                <button type="submit">Отправить заявление</button>
            </form>
        </main>
        <footer>
            <p>&copy; 2024 Нарушениям.Нет</p>
        </footer>
    </body>
    </html>
