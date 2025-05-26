<!DOCTYPE html>
<html>
<head>
    <title>Счастливые билеты</title>
</head>
<body>
    <form method="post">
        <label for="start">Начальный номер билета:</label>
        <input name="start" id="start">
        <br>
        <label for="end">Конечный номер билета:</label>
        <input name="end" id="end">
        <br>
        <button type="submit">Найти счастливые билеты</button>
    </form>
    
    <?php
    function isLuckyTicket($ticket) {
        $digits = str_split($ticket);  //разбивает строку $ticket на массив отдельных символов
        return array_sum(array_slice($digits, 0, 3)) === array_sum(array_slice($digits, 3, 3)); //сумма первых трех элем массива = сумме трех последних элем массива
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $start = (int)($_POST["start"]);
        $end = (int)($_POST["end"]);
        
        if ($start > $end) {
            echo "<p>Начальный номер должен быть меньше или равен конечному</p>";
        } else {
            echo "<p>Счастливые билеты:</p><ul>";
            for ($i = $start; $i <= $end; $i++) {
                
                //$ticket = str_pad($i, 6, "0", STR_PAD_LEFT);
                // str_pad() - дополняет строку другой строкой до заданной длины. 
                // i:  текущее число, которое нужно преобразовать в номер билета 
                // 6: Это общая длина строки, которую нужно получить.  Билет должен состоять из 6 цифр.
                // "0": Это строка, которой нужно дополнить число (в данном случае, нули).
                // STR_PAD_LEFT: Это константа, которая указывает, что дополнение нулями должно выполняться слева (т.е., в начале строки).
            

                if (isLuckyTicket($ticket)) {
                    echo "<li>$ticket</li>";
                }
            }
            echo "</ul>";
        }
    }
    ?>
</body>
</html>
