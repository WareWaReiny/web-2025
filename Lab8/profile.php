<?php 
include 'users.php'; 
include 'validation.php'; 

    $userId = isset($_GET['id']) ? $_GET['id'] : null;

    //параметр не найден, или передан некорректно, или юзера не существует
    if ($userId === null || !is_numeric($userId) || $userId <= 0) {
        header("Location: home.php");
        exit;
    }

    // Ищем пользователя с нужным ID
    $user = null;
    foreach ($users as $u) {
        if ($u['id'] == $userId) {
            $user = $u;
            break;
        }
    }

    //редирект
    if ($user === null) {
        header("Location: home.php");
        exit;
    }

    // валидация 
    $validationErrors = [];

    // длина имени 
    $nameValidation = validateStringLength($user['name'], 2, 100);
    if ($nameValidation !== true) {
        $validationErrors[] = "Имя: $nameValidation";
    }

    // валидация timestamp 
    foreach ($user['posts'] as $post) {
        $timestampValidation = validateTimestamp($post['timestamp']);
        if ($timestampValidation !== true) {
            $validationErrors[] = "Пост с ID {$post['id']}: $timestampValidation";
        }
    }

    // проверка типа значения постов
    $postsValidation = validateValueType($user['posts'], 'array');
    if ($postsValidation !== true) {
        $validationErrors[] = "Посты: $postsValidation";
    }

    // проверка того, что в имя передано строковое значение
    $postsValidation = validateValueType($user['name'], 'string');
    if ($postsValidation !== true) {
        $validationErrors[] = "Имя: $postsValidation";
    }

    // проверка того, что в id передано число
    $postsValidation = validateValueType($user['id'], 'int');
    if ($postsValidation !== true) {
        $validationErrors[] = "ID: $postsValidation";
    }

    // отображение ошибок
    if (count($validationErrors) > 0) {
        echo "<ul>";
        foreach ($validationErrors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
        exit;
    }
    
?>

<!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title> <?php echo $user['name']; ?></title>
        <link rel="stylesheet" href="src/css/profileStyle.css">
    </head>
    <body>
        <div class="profile-info">
            <img src="<?php echo $user['avatar']; ?>" alt="profile's picture" height="150" width="150">
            <h3><?php echo $user['name']; ?></h3>
            <p><?php echo $user['bio']; ?></p>
            <div class="posts-container">
                <img class="posts" src="src/images/Frame39.png" alt="posts count">
            </div>
        </div>

        <div class="gallery">
            <?php foreach ($user['gallery'] as $image): ?>
                <img src="<?php echo $image; ?>" alt="posted picture" class="gallery-image">
            <?php endforeach; ?>
        </div>
    </body>
</html>
