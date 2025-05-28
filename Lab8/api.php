<?php
header('Content-Type: application/json');
require_once 'database.php';
require_once 'validation.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$files = $_FILES ?? [];

$errors = [];

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

$imagePath = null;
if (!empty($files['image'])) {
    $image = $files['image'];

    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($image['type'], $allowedTypes)) {
        $errors[] = 'Недопустимый тип изображения. Разрешены только JPEG, PNG';
    }

    if ($image['size'] > 5 * 1024 * 1024) {
        $errors[] = 'Размер изображения не должен превышать 5MB';
    }
    
    if (empty($errors)) {
        $imageDir = 'src/images';

        if (!file_exists($imageDir)) {
            mkdir($imageDir, 0777, true);
        }
        
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $imageName = uniqid() . '.' . $extension;
        $imagePath = $imageDir . '/' . $imageName;

        if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
            $errors[] = 'Ошибка при сохранении изображения';
        }
    }
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['errors' => $errors]);
    exit;
}

$postData = [
    'user_id' => $input['user_id'],
    'content' => $input['content'],
    'image' => $imagePath
];

if (!empty($input['likes'])) {
    $postData['likes'] = (int)$input['likes'];
}

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

    $postId = $connection->lastInsertId();
    
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