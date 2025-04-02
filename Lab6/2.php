<!DOCTYPE html>
<html>
    <head>
        <title>Переводчик цифр</title>
    </head>
    <body>
        <form method="post">
            <label for="digit">Ввести цифру:</label>
            <input name="digit" id="digit">
            <button type="submit">Перевод</button>
        </form>
        
        <?php
        function translateDigit($digit) {
            $translations = [
                "0" => "Ноль",
                "1" => "Один",
                "2" => "Два",
                "3" => "Три",
                "4" => "Четыре",
                "5" => "Пять",
                "6" => "Шесть",
                "7" => "Семь",
                "8" => "Восемь",
                "9" => "Девять"
            ];
            return $translations[$digit];
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $digit = $_POST["digit"];
            echo "<p>" . translateDigit($digit) . "</p>";
        }
        ?>
    </body>
</html>