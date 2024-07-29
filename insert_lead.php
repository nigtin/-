<?php
session_start();
include 'db.php';

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    exit;
}

$ip_address = $_SERVER['REMOTE_ADDR'];

// Проверка блокировки
$sql = $conn->prepare("SELECT block_time FROM ip_block WHERE ip_address = ? AND block_time > NOW() - INTERVAL 2 HOUR");
$sql->bind_param("s", $ip_address);
$sql->execute();
$result = $sql->get_result();
if ($result->num_rows > 0) {
    echo "Форма заблокирована на 2 часа. Пожалуйста, попробуйте позже.";
    $sql->close();
    $conn->close();
    exit;
}

// Проверка количества заявок за последний час
$sql = $conn->prepare("SELECT COUNT(*) as request_count FROM leads WHERE ip_address = ? AND request_time > NOW() - INTERVAL 1 HOUR");
$sql->bind_param("s", $ip_address);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();
$request_count = $row['request_count'];

// Блокировка пользователя если более 5 заявок
if ($request_count >= 5) {
    $sql = $conn->prepare("INSERT INTO ip_block (ip_address, block_time) VALUES (?, NOW())");
    $sql->bind_param("s", $ip_address);
    $sql->execute();
    echo "Форма заблокирована на 2 часа. Пожалуйста, попробуйте позже.";
    $sql->close();
    $conn->close();
    exit;
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$city = $_POST['city'];

$sql = $conn->prepare("INSERT INTO leads (name, email, phone, city, ip_address, request_time) VALUES (?, ?, ?, ?, ?, NOW())");
$sql->bind_param("sssss", $name, $email, $phone, $city, $ip_address);

if ($sql->execute()) {
    echo "Новая запись успешно добавлена";
} else {
    echo "Error: " . $sql->error;
}

$sql->close();
$conn->close();
?>
