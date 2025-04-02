<?php
    function validateStringLength($str, $minLength, $maxLength) {
        if (strlen($str) < $minLength) {
            return "Строка слишком короткая. Минимальная длина: $minLength символов.";
        } elseif (strlen($str) > $maxLength) {
            return "Строка слишком длинная. Максимальная длина: $maxLength символов.";
        }
        return true;
    }

    function validateValueType($value, $expectedType) {
        switch ($expectedType) {
            case 'string':
                if (!is_string($value)) {
                    return "Ожидается строка.";
                }
                break;
            case 'int':
                if (!is_int($value)) {
                    return "Ожидается целое число.";
                }
                break;
            case 'array':
                if (!is_array($value)) {
                    return "Ожидается массив.";
                }
                break;
            default:
                return "Неизвестный тип значения для валидации.";
        }
        return true;
    }

    function validateTimestamp($timestamp) {
        // числовое значение?
        if (!is_numeric($timestamp)) {
            return "Ожидается числовое значение.";
        }
    
        // >= 0 (1970 год)
        if ($timestamp < 0) {
            return "Время не может быть отрицательным.";
        }
    
        // ограничение года до 2038 
        $currentTimestamp = time();
        if ($timestamp > $currentTimestamp + 100 * 365 * 24 * 60 * 60) { // примерно 100 лет в секундах
            return "Метка времени слишком велика.";
        }

        return true;
    }
?>
