<?php
include 'db.php';

$city = isset($_GET['city']) ? $_GET['city'] : '';

$sql = "SELECT name, email, phone, city FROM leads";

// Проверка наличия фильтра по городу
if ($city) {
    $stmt = $conn->prepare($sql . " WHERE city = ?");
    $stmt->bind_param("s", $city);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['phone'], ENT_QUOTES, 'UTF-8') . "</td>
                <td>" . htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8') . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>Нет данных</td></tr>";
}

$stmt->close();
$conn->close();
?>
