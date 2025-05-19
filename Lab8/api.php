<?php
header('Content-Type: application/json');
require_once 'database.php';
require_once 'validation.php';

// Разрешаем только POST-запросы
$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

// Получаем данные из запроса
$input = json_decode(file_get_contents('php://input'), true);
$files = $_FILES ?? [];

// Валидация данных
$errors = [];

// Проверяем обязательные поля
if (empty($input['user_id'])) {
    $errors[] = 'Не указан ID пользователя';
} elseif (!is_numeric($input['user_id'])) {
    $errors[] = 'ID пользователя должен быть числом';
}

if (empty($input['content'])) {
    $errors[] = 'Не указан текст поста';
} else {
    $contentValidation = validateStringLength($input['content'], 1, 200);
    if ($contentValidation !== true) {
        $errors[] = $contentValidation;
    }
}

// Обработка изображения
$imagePath = null;
if (!empty($files['image'])) {
    $image = $files['image'];
    
    // Проверяем тип файла
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($image['type'], $allowedTypes)) {
        $errors[] = 'Недопустимый тип изображения. Разрешены только JPEG, PNG и GIF';
    }
    
    // Проверяем размер файла (максимум 5MB)
    if ($image['size'] > 5 * 1024 * 1024) {
        $errors[] = 'Размер изображения не должен превышать 5MB';
    }
    
    if (empty($errors)) {
        // Создаем папку для изображений, если ее нет
        if (!file_exists('images')) {
            mkdir('images', 0777, true);
        }
        
        // Генерируем уникальное имя файла
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . '.' . $extension;
        $imagePath = 'images/' . $imageName;
        
        // Сохраняем файл
        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            $errors[] = 'Ошибка при сохранении изображения';
        }
    }
}

// Если есть ошибки - возвращаем их
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['errors' => $errors]);
    exit;
}

// Подготавливаем данные для сохранения в БД
$postData = [
    'user_id' => $input['user_id'],
    'content' => $input['content'],
    'image' => $imagePath
];

// Добавляем необязательные поля, если они есть
if (!empty($input['likes'])) {
    $postData['likes'] = (int)$input['likes'];
}

// Сохраняем пост в БД
try {
    $connection = connectDatabase();
    
    $columns = implode(', ', array_keys($postData));
    $values = ':' . implode(', :', array_keys($postData));
    
    $query = "INSERT INTO post ($columns) VALUES ($values)";
    $stmt = $connection->prepare($query);
    
    foreach ($postData as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }
    
    $stmt->execute();
    
    // Получаем ID созданного поста
    $postId = $connection->lastInsertId();
    
    // Возвращаем успешный ответ
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'post_id' => $postId,
        'message' => 'Пост успешно создан'
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Ошибка при сохранении поста: ' . $e->getMessage()
    ]);
}
?>