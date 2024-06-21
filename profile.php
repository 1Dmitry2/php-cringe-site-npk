<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Получение данных пользователя
$sql_user = "SELECT * FROM users WHERE id = $user_id";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
} else {
    header("Location: login.php");
    exit();
}

// Получение объявлений пользователя
$ads = [];
$sql_ads = "SELECT * FROM ads WHERE user_id = $user_id";
$ads_result = $conn->query($sql_ads);

if ($ads_result) {
    if ($ads_result->num_rows > 0) {
        while ($row = $ads_result->fetch_assoc()) {
            $ads[] = $row;
        }
    }
} else {
    echo "Ошибка при получении объявлений: " . $conn->error;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Профиль пользователя</title>
    <style>
        /* Стили кнопок */
        .styled-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
        }

        .styled-button:hover {
            background-color: #0056b3;
        }

        .styled-button-red {
            display: inline-block;
            padding: 10px 20px;
            font-size: 18px;
            color: #fff;
            background-color: #d30d3c;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            cursor: pointer;
        }

        .styled-button-red:hover {
            background-color: red;
        }

        /* Стили модального окна */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 6% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        /* Дополнительные стили */
        .profile-info {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 20px;
        }

        .image-container {
            width: 250px;
            height: 300px;
            background-color: #ccc;
            text-align: center;
            line-height: 300px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .image-container img {
            width: 250px;
            height: 300px;
            object-fit: cover;
            vertical-align: middle;
        }

        .button-container {
            text-align: center;
            margin-top: 10px;
        }

        .button-container:first-child {
            margin-top: 0;
        }

        .button-container input[type=file] {
            display: none;
        }

        .ads-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .ad-card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .ad-card h3 {
            margin-top: 0;
        }

        .ad-card.archived {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .ad-card.deleted {
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
        }
    </style>
</head>

<body>
    <header>
        <h1>Профиль пользователя</h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="logout.php">Выход</a></li>
                <li><a href="profile.php">Профиль</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div style="display: flex;">
            <div style="float: left;" class="profile-info">
                <div class="image-container" onclick="document.getElementById('uploadAvatar').click();">
                    <?php if (!empty($user['avatar_path'])) : ?>
                        <img src="<?php echo $user['avatar_path']; ?>" alt="Аватар">
                    <?php else : ?>
                        <span style="color: #333;">ЗАГРУЗИТЬ ИЗОБРАЖЕНИЕ</span>
                    <?php endif; ?>
                </div>
                <form action="upload_avatar.php" method="post" enctype="multipart/form-data" style="display: none;">
                    <input type="file" name="avatar" id="uploadAvatar" onchange="this.form.submit();">
                </form>
                <div style="background-color: black; padding: 10px; border-radius: 6px; text-align: left; color: white; width:230px">
                    <p ><strong style="color: beige; width: 100px; overflow:hidden">ФИО:</strong> <?php echo htmlspecialchars($user['fio']); ?></p>
                    <p ><strong style="color: beige; width: 100px; overflow:hidden">Телефон:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                    <p><strong style="color: beige; width: 100px; overflow:hidden">Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <div class="button-container">
                        <a class="styled-button-red" onclick="openModal()">Редактировать данные</a>
                    </div>
                    <div class="button-container">
                        <a href="admin_panel.php" class="styled-button">Перейти на админ панель</a>
                    </div>
                </div>
            </div>
            <div style="margin: 0 auto">
                <h1>Ваши объявления</h1>
                <div class="ads-container">
                    <?php if (empty($ads)) : ?>
                        <p>У вас нет объявлений.</p>
                    <?php else : ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Номер автомобиля</th>
                                    <th>Описание</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ads as $ad) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($ad['car_number']); ?></td>
                                        <td><?php echo htmlspecialchars($ad['description']); ?></td>
                                        <td><?php echo htmlspecialchars($ad['status']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Модальное окно для редактирования данных -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Редактирование данных</h2>
                <form action="update_profile.php" method="post">
                    <label for="fio">ФИО:</label><br>
                    <input type="text" id="fio" name="fio" value="<?php echo htmlspecialchars($user['fio']); ?>"><br><br>
                    <label for="phone">Телефон:</label><br>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br><br>
                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"><br><br>
                    <input type="submit" value="Сохранить" class="styled-button">
                </form>
            </div>
        </div>
    </main>
    <script>
        function openModal() {
            document.getElementById('editModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('editModal').style.display = "none";
        }
    </script>
</body>

</html>
