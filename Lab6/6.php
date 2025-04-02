<!DOCTYPE html>
<html>
<head>
    <title>Вычисление факториала</title>
</head>
<body>
    <form method="post">
        <label for="number">Введите число:</label>
        <input name="number" id="number">
        <button type="submit">Вычислить</button>
    </form>
    
    <?php
    function factorial($n) {
        if ($n <= 1) {
            return 1;
        } else {
            return $n * factorial($n - 1);
        } 
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $number = (int)($_POST["number"]);
        echo "<p>Факториал $number: " . factorial($number) . "</p>";
    }

    ?>
</body>
</html>


