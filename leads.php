<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список лидов</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Список лидов</h1>
    <form id="filterForm">
        <label>Город:</label>
        <select name="city" id="cityFilter">
            <option value="">Все</option>
            <option value="Москва">Москва</option>
            <option value="Санкт-Петербург">Санкт-Петербург</option>
            <option value="Тула">Тула</option>
        </select>
    </form>
    <table>
        <thead>
            <tr>
                <th>ФИО</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Город</th>
            </tr>
        </thead>
        <tbody id="leadsTable">
            
        </tbody>
    </table>
    <button id="exportBtn">Экспорт в CSV</button>
    <a href="index.php"><button type="button">Добавить лид</button></a>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadLeads(city = '') {
                $.ajax({
                    url: 'load_leads.php',
                    type: 'GET',
                    data: { city: city },
                    success: function(data) {
                        $('#leadsTable').html(data);
                    }
                });
            }

            loadLeads();

            $('#cityFilter').on('change', function() {
                var city = $(this).val();
                loadLeads(city);
            });

            $('#exportBtn').on('click', function() {
                var city = $('#cityFilter').val();
                var exportUrl = 'export.php';
                if (city) {
                    exportUrl += '?city=' + encodeURIComponent(city);
                }
                window.location.href = exportUrl;
            });
        });
    </script>
</body>
</html>
