<?php
include 'users.php';
include 'posts.php';
include 'validation.php';
include 'error-log.php';

$validationErrors = [];

$validUsers = [];
foreach ($users as $user) {
    if (!isset($user['id'], $user['name'], $user['avatar'])) {
        $validationErrors[] = "Некорректные данные пользователя.";
        continue;
    }
    $idValidation = validateValueType($user['id'], 'int');
    $nameValidation = validateStringLength($user['name'], 1, 100);
    $avatarValidation = validateStringLength($user['avatar'], 5, 255);

    if ($idValidation === true && $nameValidation === true && $avatarValidation === true) {
        $validUsers[$user['id']] = $user;
    } else {
        $validationErrors[] = "Пользователь ID {$user['id']} имеет некорректные данные.";
    }
}

$validPosts = [];
foreach ($posts as $post) {
    if (!isset($post['user_id'], $post['image'], $post['text'], $post['timestamp'], $post['likes'])) {
        $validationErrors[] = "Пост содержит неполные данные.";
        continue;
    }
    if (
        validateValueType($post['user_id'], 'int') === true &&
        validateStringLength($post['image'], 5, 255) === true &&
        validateStringLength($post['text'], 0, 1000) === true &&
        validateTimestamp($post['timestamp']) === true &&
        validateValueType($post['likes'], 'int') === true
    ) {
        $validPosts[] = $post;
    } else {
        $validationErrors[] = "Пост пользователя ID {$post['user_id']} содержит некорректные данные.";
    }
}

if (!empty($validationErrors)) {
    logErrorsToFile($validationErrors); 
    header('Location: home.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="src/css/homeStyle.css">
</head>
<body>
    <div class="content">
        <?php
        foreach ($validPosts as $post) {
            if (isset($validUsers[$post['user_id']])) {
                $user = $validUsers[$post['user_id']];
                include 'post-template.php';
            } else {
                $username = "Неизвестный пользователь";
                include 'post-template.php';
            }
        }
        ?>
    </div>
</body>
</html>
