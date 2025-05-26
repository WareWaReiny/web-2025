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
        if (!is_numeric($timestamp)) {
            return "Ожидается числовое значение.";
        }
        if ($timestamp < 0) {
            return "Время не может быть отрицательным.";
        }
        $currentTimestamp = time();
        if ($timestamp > $currentTimestamp + 100 * 365 * 24 * 60 * 60) { 
            return "Метка времени слишком велика.";
        }
        return true;
    }
?> 
