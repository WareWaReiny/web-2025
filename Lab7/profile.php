<?php 
include 'users.php';
include 'gallery.php';
include 'validation.php';
$validationErrors = [];
$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
$usersById = [];
foreach ($users as $user) {
    $usersById[$user['id']] = $user;
}
if (!isset($usersById[$userId])) {  
    header('Location: home.php');
    exit;
}
$user = $usersById[$userId];
$userGallery = array_filter($gallery, function($item) use ($userId) {
    return $item['user_id'] == $userId;
});
$idValidation = validateValueType($userId, 'int');
if ($idValidation !== true) {
    $validationErrors[] = "ID пользователя: $idValidation";
}
if (count($validationErrors) > 0) {
    echo "<ul>";
    foreach ($validationErrors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
    exit;
}  
if (!empty($user['posts']) && is_array($user['posts'])) {
    foreach ($user['posts'] as $post) {
        if (!isset($post['id'], $post['timestamp'])) {
            $validationErrors[] = "Пост не содержит обязательных полей (ID или timestamp)";
            continue;
        }
        $timestampValidation = validateTimestamp($post['timestamp']);
        if ($timestampValidation !== true) {
            $validationErrors[] = "Пост ID {$post['id']}: $timestampValidation";
        }
        if (isset($post['content'])) {
            $contentValidation = validateStringLength($post['content'], 0, 1000);
            if ($contentValidation !== true) {
                $validationErrors[] = "Содержание поста ID {$post['id']}: $contentValidation";
            }
        }
    }
} elseif (isset($user['posts']) && !is_array($user['posts'])) {
    $validationErrors[] = "Посты пользователя должны быть массивом";
}
$userGallery = [];
if (empty($validationErrors)) {
    $validGallery = [];
    foreach ($gallery as $item) {
        if (!isset($item['user_id'], $item['image'], $item['timestamp'])) {
            continue;
        }
        if (validateValueType($item['user_id'], 'int') === true &&
            validateStringLength($item['image'], 10, 255) === true &&
            validateTimestamp($item['timestamp']) === true) {
            $validGallery[] = $item;
        }
    }
    $userGallery = array_filter($validGallery, function($item) use ($userId) {
        return $item['user_id'] == $userId;
    });
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo $user['name']; ?></title>
    <link rel="stylesheet" href="src/css/profileStyle.css">
</head>
<body>
    <div class="profile-info">
        <img src="<?php echo $user['avatar']; ?>" alt="profile's picture" height="150" width="150">
        <h3><a href="profile.php?user_id=<?php echo $user['id']; ?>"><?php echo $user['name']; ?></a></h3>
        <p><?php echo $user['bio']; ?></p>
        <div class="posts-container">
            <img class="posts" src="src/images/Frame39.png" alt="posts count">
        </div>
    </div>
    <div class="gallery">
        <?php if (!empty($userGallery)): ?>
            <?php foreach ($userGallery as $image): ?>
                <img src="<?php echo $image['image']; ?>" alt="posted picture" class="gallery-image">
            <?php endforeach; ?>
        <?php else: ?>
            <p>No posts yet</p>
        <?php endif; ?>
    </div>
</body>
</html>