<!DOCTYPE html>
    <html>
    <head>
        <title>Определение знака зодиака</title>
    </head>
    <body>
        <h1>Определение знака зодиака</h1>

        <form method="post">
            <label for="date">Введите дату в формате ДД.ММ.ГГГГ:</label>
            <input type="text" id="date" name="date" placeholder="ДД.ММ.ГГГГ" required>
            <button type="submit">Определить знак зодиака</button>
        </form>

        <?php
        function zodiakSignFunction($dateString) {
            $dateParts = explode('.', $dateString);  //разбивает строку разделителем, возвращает массив строк
            
            $day = (int)$dateParts[0];
            $month = (int)$dateParts[1];

            switch ($month) {
                case 1: // январь
                    return ($day <= 19) ? "Козерог" : "Водолей";
                case 2: // февраль
                    return ($day <= 18) ? "Водолей" : "Рыбы";
                case 3: // март
                    return ($day <= 20) ? "Рыбы" : "Овен";
                case 4: // апрель
                    return ($day <= 19) ? "Овен" : "Телец";
                case 5: // май
                    return ($day <= 20) ? "Телец" : "Близнецы";
                case 6: // июнь
                    return ($day <= 20) ? "Близнецы" : "Рак";
                case 7: // июль
                    return ($day <= 22) ? "Рак" : "Лев";
                case 8: // август
                    return ($day <= 22) ? "Лев" : "Дева";
                case 9: // сентябрь
                    return ($day <= 22) ? "Дева" : "Весы";
                case 10: // октябрь
                    return ($day <= 22) ? "Весы" : "Скорпион";
                case 11: // ноябрь
                    return ($day <= 21) ? "Скорпион" : "Стрелец";
                case 12: // декабрь
                    return ($day <= 21) ? "Стрелец" : "Козерог";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $date = $_POST["date"];
            $zodiacSign = zodiakSignFunction($date);
            echo "<p>Знак зодиака: " . ($zodiacSign) . "</p>"; 
        }
        ?>
    </body>
</html>

