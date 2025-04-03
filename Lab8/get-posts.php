<?php
include 'db.php';

function getAllPosts(PDO $connection): array {
    $query = "SELECT post.id AS post_id, post.content, post.likes, post.posted_at, 
                     user.id AS user_id, user.name, user.avatar, post.image
              FROM post
              JOIN user ON post.user_id = user.id
              ORDER BY post.posted_at DESC";

    $statement = $connection->query($query);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>