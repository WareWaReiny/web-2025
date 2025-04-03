<?php
    if (!function_exists('connectDatabase')) {
        function connectDatabase(): PDO {
            $host = "127.0.0.1";
            $dbname = "blog"; 
            $username = "root"; 
            $password = ""; 
    
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    
            try {
                return new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("Ошибка подключения: " . $e->getMessage());
            }
        }
    }
?>