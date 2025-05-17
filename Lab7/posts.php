<?php
    $jsonPostsData = file_get_contents('posts.json');
    $posts = json_decode($jsonPostsData, true);
    if (!$posts) {
        exit("Ошибка загрузки данных постов!");
    }
?>