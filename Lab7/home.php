<?php 
include 'users.php';
include 'posts.php';
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
        $usersById = [];
        foreach ($users as $user) {
            $usersById[$user['id']] = $user;
        }
        foreach ($posts as $post) {
            if (isset($usersById[$post['user_id']])) {
                $user = $usersById[$post['user_id']];
                $username = $user['name'];
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