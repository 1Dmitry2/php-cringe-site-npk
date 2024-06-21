    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db";

    // Подключение к базе данных MySQL
    $conn = new mysqli($servername, $username, $password);

    // Проверка соединения
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Создание базы данных, если её нет
    $sql_create_db = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql_create_db) !== TRUE) {
        die("Error creating database: " . $conn->error);
    }

    // Подключение к базе данных db
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка соединения с базой данных
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Создание таблицы users, если она не существует
    $sql_create_table_users = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        fio VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        email VARCHAR(100) NOT NULL,
        login VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL,
        avatar_path VARCHAR(255) DEFAULT NULL,  -- Добавлен столбец для хранения пути к аватару
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql_create_table_users) !== TRUE) {
        die("Error creating table 'users': " . $conn->error);
    }

    // Создание таблицы ads, если она не существует
    $sql_create_table_ads = "CREATE TABLE IF NOT EXISTS ads (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";

    if ($conn->query($sql_create_table_ads) !== TRUE) {
        die("Error creating table 'ads': " . $conn->error);
    }

    // Создание таблицы claims, если она не существует
    $sql_create_table_claims = "CREATE TABLE IF NOT EXISTS claims (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        car_number VARCHAR(20) NOT NULL,
        description TEXT,
        status VARCHAR(50) NOT NULL,
        image_path VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";

    if ($conn->query($sql_create_table_claims) !== TRUE) {
        die("Error creating table 'claims': " . $conn->error);
    }
    
    ?>
