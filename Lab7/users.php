<?php
    $jsonData = file_get_contents('users.json');
    $users = json_decode($jsonData, true);
    if (!$users) {
        exit("Ошибка загрузки данных!");
    }
?>
