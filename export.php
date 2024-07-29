<?php
include 'db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=leads.csv');

$output = fopen('php://output', 'w');

$city = isset($_GET['city']) ? $_GET['city'] : '';

//загрузка csv по городу
if ($city) {
    $sql = $conn->prepare("SELECT name, email, phone, city FROM leads WHERE city = ?");
    $sql->bind_param("s", $city);
} else {
    $sql = $conn->prepare("SELECT name, email, phone, city FROM leads");
}

$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

fclose($output);
$sql->close();
$conn->close();
?>
