<!-- admin_panel.php -->

<?php
include 'db.php'; // Подключаем файл с настройками базы данных
session_start();

// Проверка сессии пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Обработка добавления новой записи
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_claim'])) {
    $user_id = $_SESSION['user_id'];
    $car_number = $_POST['car_number'];
    $description = $_POST['description'];
    $status = $_POST['status'];



    // Обрабатываем загрузку изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'uploads/';
        $filename = basename($_FILES['image']['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // Добавляем запись в базу данных с учетом загруженного файла
            $insert_sql = "INSERT INTO claims (user_id, car_number, description, status, image_path) 
                           VALUES ('$user_id', '$car_number', '$description', '$status', '$targetFile')";

            if ($conn->query($insert_sql) === TRUE) {
                header("Location: admin_panel.php");
                exit();
            } else {
                echo "Ошибка: " . $insert_sql . "<br>" . $conn->error;
            }
        } else {
            echo "Ошибка при загрузке файла.";
        }
    } else {
        echo "Произошла ошибка при загрузке файла.";
    }
}

// Обработка редактирования записи
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_claim'])) {
    $claim_id = $_POST['claim_id'];
    $car_number = $_POST['car_number'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Обновляем запись в базе данных
    $update_sql = "UPDATE claims SET car_number='$car_number', description='$description', status='$status' WHERE id='$claim_id'";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: admin_panel.php");
        exit();
    } else {
        echo "Ошибка при обновлении записи: " . $conn->error;
    }
}

// Обработка удаления записи
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_claim'])) {
    $claim_id = $_POST['claim_id'];

    // Удаляем запись из базы данных
    $delete_sql = "DELETE FROM claims WHERE id='$claim_id'";

    if ($conn->query($delete_sql) === TRUE) {
        header("Location: admin_panel.php");
        exit();
    } else {
        echo "Ошибка при удалении записи: " . $conn->error;
    }
}

// SQL-запрос для выборки всех заявок
$sql = "SELECT * FROM claims";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="modal.css"> <!-- Подключаем стили для модального окна -->
    <style>
        /* Дополнительные стили для админ панели */
        .table-wrapper {
            margin-bottom: 20px;
        }

        .add-button {
            margin-bottom: 10px;
            background-color: #4CAF50;
            /* Зеленый цвет для кнопки "Добавить" */
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .edit-button {
            background-color: #3498db;
            /* Синий цвет для кнопки "Редактировать" */
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .edit-button:hover {
            background-color: #2980b9;
            /* Темнее синий при наведении */
        }

        .delete-button {
            background-color: #f44336;
            /* Красный цвет для кнопки "Удалить" */
            color: white;
            padding: 8px 16px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .delete-button:hover {
            background-color: #d32f2f;
            /* Темнее красный при наведении */
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
        }

        /* Стили для модальных окон */
        .modal {
            display: none;
            /* По умолчанию модальные окна скрыты */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            /* Полупрозрачный черный фон */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 6% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            position: relative;
        }

        .modal-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-close:hover,
        .modal-close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-buttons {
            text-align: right;
            margin-top: 10px;
        }

        .modal-buttons button {
            margin-left: 10px;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-buttons .save-button {
            background-color: #4CAF50;
            /* Зеленая кнопка "Сохранить" */
            color: white;
            border: none;
        }

        .modal-buttons .cancel-button {
            background-color: #f44336;
            /* Красная кнопка "Отменить" */
            color: white;
            border: none;
        }

        /* Стили для вкладок */
        .tab {
            overflow: hidden;
            border-bottom: 1px solid #ccc;
            background-color: #f1f1f1;
            margin-bottom: 20px;
        }

        .tab button {
            background-color: inherit;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: background-color 0.3s;
            font-size: 17px;
        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #ccc;
        }

        .tab-content {
            display: none;
            padding: 20px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .tab-content.active {
            display: block;
        }

        .back-button {
            margin-bottom: 10px;
            background-color: #f44336;
            /* Красный цвет для кнопки "Вернуться" */
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            text-decoration: none;
            /* Убираем подчеркивание ссылки */
            display: inline-block;
            /* Делаем ссылку строчно-блочным элементом */
        }

        .back-button:hover {
            background-color: #d32f2f;
            /* Темнее красный при наведении */
        }
    </style>
</head>

<body>
    <header>
        <h1>Панель администратора</h1>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="logout.php">Выход</a></li>
                <li><a href="profile.php">Профиль</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Список Объявлений</h2>

        <!-- Вкладки -->
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'posted')">Размещенные</button>
            <button class="tablinks" onclick="openTab(event, 'archive')">Архив</button>
            <button class="tablinks" onclick="openTab(event, 'deleted')">Удаленные</button>
        </div>

        <!-- Содержимое вкладок -->
        <div id="posted" class="tab-content">
            <?php displayClaimsByStatus($conn, 'разместить'); ?>
        </div>
        <div id="archive" class="tab-content">
            <?php displayClaimsByStatus($conn, 'архив'); ?>
        </div>
        <div id="deleted" class="tab-content">
            <?php displayClaimsByStatus($conn, 'удалить'); ?>
        </div>

        <!-- Кнопка "Добавить" для вызова модального окна -->
        <a href="profile.php" class="back-button">Вернуться</a>
        <button class="add-button" onclick="openAddModal()">Добавить</button>

        <!-- Модальное окно для добавления новой записи -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="modal-close" onclick="closeModal('addModal')">&times;</span>
                <h2>Добавить новое объявление</h2>
                <form action="admin_panel.php" method="post" enctype="multipart/form-data">
                    <label for="car_number">Номер автомобиля:</label>
                    <input type="text" id="car_number" name="car_number" required><br><br>
                    <label for="description">Описание:</label><br>
                    <textarea id="description" name="description" rows="4" required></textarea><br><br>
                    <label for="status">Статус:</label>
                    <select id="status" name="status">
                        <option value="разместить">Разместить</option>
                        <option value="архив">Архив</option>
                        <option value="удалить">Удалить</option>
                    </select><br><br>
                    <label for="image">Загрузить изображение:</label>
                    <input type="file" id="image" name="image"><br><br>
                    <div class="modal-buttons">
                        <button type="submit" name="add_claim" class="save-button">Сохранить</button>
                        <button type="button" class="cancel-button" onclick="closeModal('addModal')">Отменить</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Модальное окно для редактирования записи -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="modal-close" onclick="closeModal('editModal')">&times;</span>
                <h2>Редактировать запись</h2>
                <form action="admin_panel.php" method="post">
                    <input type="hidden" id="edit_claim_id" name="claim_id">
                    <label for="edit_car_number">Номер автомобиля:</label>
                    <input type="text" id="edit_car_number" name="car_number" required><br><br>
                    <label for="edit_description">Описание:</label><br>
                    <textarea id="edit_description" name="description" rows="4" required></textarea><br><br>
                    <label for="edit_status">Статус:</label>
                    <select id="edit_status" name="status">
                        <option value="разместить">Разместить</option>
                        <option value="архив">Архив</option>
                        <option value="удалить">Удалить</option>
                    </select><br><br>
                    <div class="modal-buttons">
                        <button type="submit" name="edit_claim" class="save-button">Сохранить</button>
                        <button type="button" class="cancel-button" onclick="closeModal('editModal')">Отменить</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Модальное окно для подтверждения удаления записи -->
        <div id="confirmDeleteModal" class="modal">
            <div class="modal-content">
                <span class="modal-close" onclick="closeModal('confirmDeleteModal')">&times;</span>
                <h2>Подтвердите удаление записи</h2>
                <p>Вы уверены, что хотите удалить эту запись?</p>
                <form action="admin_panel.php" method="post">
                    <input type="hidden" id="delete_claim_id" name="claim_id">
                    <div class="modal-buttons">
                        <button type="submit" name="delete_claim" class="save-button">Удалить</button>
                        <button type="button" class="cancel-button" onclick="closeModal('confirmDeleteModal')">Отменить</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- JavaScript для модальных окон и вкладок -->
        <script>
            // Получаем модальные окна
            var addModal = document.getElementById("addModal");
            var editModal = document.getElementById("editModal");
            var confirmDeleteModal = document.getElementById("confirmDeleteModal");

            // Функция для открытия модального окна добавления
            function openAddModal() {
                addModal.style.display = "block";
            }

            // Функция для закрытия модального окна
            function closeModal(modalId) {
                var modal = document.getElementById(modalId);
                modal.style.display = "none";
            }

            // Функция для открытия модального окна редактирования
            function openEditModal(id, carNumber, description, status) {
                document.getElementById("edit_claim_id").value = id;
                document.getElementById("edit_car_number").value = carNumber;
                document.getElementById("edit_description").value = description;
                document.getElementById("edit_status").value = status;
                editModal.style.display = "block";
            }

            // Функция для открытия модального окна подтверждения удаления
            function openDeleteModal(id) {
                document.getElementById("delete_claim_id").value = id;
                confirmDeleteModal.style.display = "block";
            }

            // Закрыть модальное окно, если пользователь кликает за его пределами
            window.onclick = function(event) {
                if (event.target == addModal) {
                    closeModal('addModal');
                } else if (event.target == editModal) {
                    closeModal('editModal');
                } else if (event.target == confirmDeleteModal) {
                    closeModal('confirmDeleteModal');
                }
            }

            // Функция для открытия вкладки
            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;

                // Скрыть все вкладки
                tabcontent = document.getElementsByClassName("tab-content");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Удалить класс "active" у всех вкладок
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }

                // Показать текущую вкладку и добавить класс "active" к кнопке, которая открыла вкладку
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";
            }

            // Открываем первую вкладку по умолчанию
            document.getElementsByClassName("tablinks")[0].click();
        </script>
    </main>
    <footer>
        <p>&copy; 2024 Все права защищены</p>
    </footer>
</body>

</html>

<?php
// Функция для отображения объявлений по статусу
function displayClaimsByStatus($conn, $status)
{
    $sql = "SELECT * FROM claims WHERE status='$status'";
    $result = $conn->query($sql);

    echo '<div class="table-wrapper">';
    echo '<table>';
    echo '<tr>
            <th>ID</th>
            <th>Номер автомобиля</th>
            <th>Описание</th>
            <th>Статус</th>
            <th>Изображение</th>
            <th>Действия</th>
          </tr>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['car_number'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td><img src='" . $row['image_path'] . "' style='width:100px;height:auto;'></td>";
            echo "<td class='action-buttons'>";
            echo "<button class='edit-button' onclick='openEditModal(" . $row['id'] . ", \"" . $row['car_number'] . "\", \"" . $row['description'] . "\", \"" . $row['status'] . "\")'>Редактировать</button>";
            echo "<button class='delete-button' onclick='openDeleteModal(" . $row['id'] . ")'>Удалить</button>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Нет размещенных объявлений     </td></tr>";
    }

    echo '</table>';
    echo '</div>';
}
?>