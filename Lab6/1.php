<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Високосный год</title>
    </head>
    <body>
        <h1>Проверка года на високосность</h1>
        <form method="post">
            Год: <input name="year">
            <input type="submit" value="Проверить">
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $year = (int)$_POST["year"];

            if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) {
                echo "<p>Да, год високосный.</p>";
            } else {
                echo "<p>Нет, год не високосный.</p>";
            }
        }
        ?>
    </body>
</html>

