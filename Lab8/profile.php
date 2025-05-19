<?php 
include 'validation.php';
include 'database.php';

$connection = connectDatabase();
$userId = isset($_GET['id']) ? $_GET['id'] : null;

if ($userId === null || !is_numeric($userId) || $userId <= 0) {
    header("Location: home.php");
    exit;
}

$user = getUserById($connection, $userId);

if ($user === null) {
    header("Location: home.php");
    exit;
}

$validationErrors = [];
$nameValidation = validateStringLength($user['name'], 2, 100);
if ($nameValidation !== true) {
    $validationErrors[] = "Имя: $nameValidation";
}

foreach ($user['posts'] as $post) {
    $timestampValidation = validateTimestamp(strtotime($post['posted_at']));
    if ($timestampValidation !== true) {
        $validationErrors[] = "Пост с ID {$post['id']}: $timestampValidation";
    }
}

$postsValidation = validateValueType($user['posts'], 'array');
if ($postsValidation !== true) {
    $validationErrors[] = "Посты: $postsValidation";
}

$postsValidation = validateValueType($user['name'], 'string');
if ($postsValidation !== true) {
    $validationErrors[] = "Имя: $postsValidation";
}

$postsValidation = validateValueType($user['id'], 'int');
if ($postsValidation !== true) {
    $validationErrors[] = "ID: $postsValidation";
}

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
    <title><?php echo htmlspecialchars($user['name']); ?></title>
    <link rel="stylesheet" href="src/css/profileStyle.css">
</head>
<body>
    <div class="profile-info">
        <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="profile's picture" height="150" width="150">
        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
        <p><?php echo htmlspecialchars($user['bio'] ?? ''); ?></p>
        <div class="posts-container">
            <span class="posts-count"> <?php echo $user['posts_count']; ?> постов</span>
        </div>
    </div>

    <div class="gallery">
        <?php foreach ($user['gallery'] as $image): ?>
            <img src="<?php echo htmlspecialchars($image); ?>" alt="posted picture" class="gallery-image">
        <?php endforeach; ?>
    </div>
</body>
</html>