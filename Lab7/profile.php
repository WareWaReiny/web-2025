<?php 
include 'users.php';
include 'posts.php';
include 'validation.php';
include 'error-log.php';

$validationErrors = [];
$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// Подготовка ассоциативного массива пользователей
$usersById = [];
foreach ($users as $user) {
    $usersById[$user['id']] = $user;
}

// Проверка существования пользователя
if (!isset($usersById[$userId])) {  
    header('Location: home.php');
    exit;
}

$user = $usersById[$userId];

// Валидация ID
$idValidation = validateValueType($userId, 'int');
if ($idValidation !== true) {
    $validationErrors[] = "ID пользователя: $idValidation";
}

// Получаем посты пользователя
$userPosts = array_filter($posts, function($post) use ($userId) {
    return $post['user_id'] == $userId;
});

// Фильтрация корректных постов
$validGallery = [];
foreach ($userPosts as $post) {
    if (!isset($post['id'], $post['image'], $post['timestamp'])) {
        $validationErrors[] = "Пост ID {$post['id']} не содержит обязательных полей.";
        continue;
    }

    // Проверка валидности
    if (
        validateValueType($post['id'], 'int') === true &&
        validateStringLength($post['image'], 5, 255) === true &&
        validateTimestamp($post['timestamp'], true) === true // допускаем timestamp = 0
    ) {
        $validGallery[] = $post;
    } else {
        $validationErrors[] = "Пост ID {$post['id']} содержит некорректные данные.";
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
    <title><?php echo htmlspecialchars($user['name']); ?></title>
    <link rel="stylesheet" href="src/css/profileStyle.css">
</head>
<body>
    <div class="profile-info">
        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="profile's picture" height="150" width="150">
        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
        <p><?php echo htmlspecialchars($user['bio']); ?></p>
        <div class="posts-container">
            <img class="posts" src="src/images/Frame39.png" alt="posts count">
        </div>
    </div>
    <div class="gallery">
        <?php if (!empty($validGallery)): ?>
            <?php foreach ($validGallery as $post): ?>
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="posted picture" class="gallery-image">
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет постов</p>
        <?php endif; ?>
    </div>
</body>
</html>
