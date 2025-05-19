<?php
function connectDatabase() {
    $host = "127.0.0.1";
    $dbname = "blog"; 
    $username = "root"; 
    $password = ""; 

    //$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    try {
        $connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
}

function getAllPosts($connection) {
    try {
        $query = "
            SELECT p.*, u.name, u.avatar 
            FROM post p
            JOIN user u ON p.user_id = u.id
            ORDER BY p.posted_at DESC
        ";
        $stmt = $connection->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Ошибка при получении постов: " . $e->getMessage());
    }
}

function getUserById($connection, $userId) {
    try {
        $stmt = $connection->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        $stmt = $connection->prepare("
            SELECT * FROM post 
            WHERE user_id = :user_id 
            ORDER BY posted_at DESC
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user['posts'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $user['posts_count'] = count($user['posts']);

        $user['gallery'] = array_column($user['posts'], 'image');

        return $user;
    } catch (PDOException $e) {
        die("Ошибка при получении данных пользователя: " . $e->getMessage());
    }
}
?>