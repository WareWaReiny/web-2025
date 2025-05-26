<!DOCTYPE html>
<html>
<head>
    <title>Определение знака зодиака</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Определение знака зодиака</h1>
    <form method="post">
        <label for="date">Введите дату (форматы: ДД.ММ.ГГГГ, ДД-ММ-ГГГГ, ДД/ММ/ГГГГ, ДД ММ ГГГГ или ГГГГ-ММ-ДД):</label><br>
        <input type="text" id="date" name="date">
        <button type="submit">Определить знак зодиака</button>
    </form>

<?php
function parseDate($input) {
    $input = trim($input);
    $delimiters = ['.', '-', '/', ' '];

    foreach ($delimiters as $delimiter) {
        if (substr_count($input, $delimiter) === 2) {
            $parts = explode($delimiter, $input);

            // ГГГГ-ММ-ДД
            if (strlen($parts[0]) === 4) {
                $year = (int)$parts[0];
                $month = (int)$parts[1];
                $day = (int)$parts[2];
            } else {
                $day = (int)$parts[0];
                $month = (int)$parts[1];
                $year = (int)$parts[2];
            }

            return ['day' => $day, 'month' => $month];
        }
    }

    return ['day' => 0, 'month' => 0]; 
}

function isValidDate($day, $month) {
    if ($month < 1 || $month > 12 || $day < 1) return false;

    $daysInMonth = [
        1 => 31, 2 => 29, 3 => 31, 4 => 30, 5 => 31, 6 => 30,
        7 => 31, 8 => 31, 9 => 30, 10 => 31, 11 => 30, 12 => 31
    ];

    return $day <= $daysInMonth[$month];
}

function zodiakSignFunction($dateString) {
    $parsed = parseDate($dateString);
    $day = $parsed['day'];
    $month = $parsed['month'];

    if (!isValidDate($day, $month)) {
        return "Неверная дата!";
    }

    switch ($month) {
        case 1:  return ($day <= 19) ? "Козерог" : "Водолей";
        case 2:  return ($day <= 18) ? "Водолей" : "Рыбы";
        case 3:  return ($day <= 20) ? "Рыбы" : "Овен";
        case 4:  return ($day <= 19) ? "Овен" : "Телец";
        case 5:  return ($day <= 20) ? "Телец" : "Близнецы";
        case 6:  return ($day <= 20) ? "Близнецы" : "Рак";
        case 7:  return ($day <= 22) ? "Рак" : "Лев";
        case 8:  return ($day <= 22) ? "Лев" : "Дева";
        case 9:  return ($day <= 22) ? "Дева" : "Весы";
        case 10: return ($day <= 22) ? "Весы" : "Скорпион";
        case 11: return ($day <= 21) ? "Скорпион" : "Стрелец";
        case 12: return ($day <= 21) ? "Стрелец" : "Козерог";
        default: return "Неверная дата!";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["date"];
    $zodiacSign = zodiakSignFunction($date);
    echo "<p>Знак зодиака: <strong>$zodiacSign</strong></p>";
}
?>
</body>
</html>