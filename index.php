<?php
session_start();

// Генерация CSRF-токена
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма сбора лидов</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Форма сбора лидов</h1>
    <form id="leadForm">
        <label>ФИО:</label>
        <input type="text" name="name" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Телефон:</label>
        <input type="tel" name="phone" pattern="\+?[0-9\s\-\(\)]+" required><br>
        <label>Город:</label>
        <select name="city" required>
            <option value="Москва">Москва</option>
            <option value="Санкт-Петербург">Санкт-Петербург</option>
            <option value="Тула">Тула</option>
        </select><br>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <button type="submit">Отправить</button>
        <a href="leads.php"><button type="button">Список лидов</button></a>
    </form>
    <div id="blockMessage" style="color: red; display: none;"></div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#leadForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: 'insert_lead.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.includes("Форма заблокирована")) {
                            $('#blockMessage').text(response).show();
                        } else {
                            alert(response);
                            $('#leadForm')[0].reset();
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Произошла ошибка при добавлении лида: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
