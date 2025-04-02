<?php include 'users.php'; ?>
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
                foreach ($users as $user) {  // цикл по каждому пользователю
                    foreach ($user['posts'] as $post) {  
                        $username = $user['name'];  
                        include 'post-template.php'; 
                    }
                }
                
            ?>
        </div>
    </body>
</html>
