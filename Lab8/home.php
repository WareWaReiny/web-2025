<?php 
include 'database.php';
$connection = connectDatabase();
$posts = getAllPosts($connection);
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
        <?php foreach ($posts as $post): ?>
            <?php include 'post-template.php'; ?>
        <?php endforeach; ?>
    </div>
</body>
</html>