<?php
include 'db.php';

// Получаем id объявления из параметра GET
$id = $_GET['id'];

// Подготавливаем запрос
$sql = "SELECT * FROM ads WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $id); // 's' означает, что параметр $id является строкой (можно также использовать 'i' для целых чисел)
$stmt->execute();
$result = $stmt->get_result();

// Проверяем результат запроса и отправляем JSON
if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode([]);
}

// Закрываем соединение и освобождаем ресурсы
$stmt->close();
$conn->close();
?>
