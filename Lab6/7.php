<!DOCTYPE html>
<html>
    <head>
        <title>Обратная польская запись</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1>Калькулятор (обратная польская запись)</h1>
        <form method="post">
            <input type="text" name="expression" placeholder="Введите выражение (например: 2 3 + 4 *)" required>
            <button type="submit">Вычислить</button>
        </form>

    <?php
    function evaluateRPN(string $expression): int
    {
        $stack = [];
        $tokens = explode(' ', trim($expression));

        foreach ($tokens as $token) {
            if (is_numeric($token)) {
                array_push($stack, (int)$token);
                continue;
            }

            $b = array_pop($stack);
            $a = array_pop($stack);

            if ($a === null || $b === null) {
                throw new Exception("Недостаточно операндов для оператора '$token'");
            }

            switch ($token) {
                case '+':
                    array_push($stack, $a + $b);
                    break;
                case '-':
                    array_push($stack, $a - $b);
                    break;
                case '*':
                    array_push($stack, $a * $b);
                    break;
                default:
                    throw new Exception("Неизвестный оператор: $token");
            }
        }

        $result = array_pop($stack);
        if (!empty($stack)) {
            throw new Exception("Некорректное выражение: слишком много чисел");
        }

        return $result;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $expression = $_POST['expression'] ?? '';

        try {
            $result = evaluateRPN($expression);
            echo "<p><strong>Результат:</strong> $result</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'><strong>Ошибка:</strong> " . $e->getMessage() . "</p>";
        }
    }
    ?>
    </body>
</html>

<!-- 
2 3 +
5 1 2 + 4 * + 
7 3 - 2 *
-->