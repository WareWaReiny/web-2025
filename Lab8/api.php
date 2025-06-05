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

    $input = $_POST;
    $files = $_FILES;

    $errors = [];

    if (empty($input['user_id'])) {
        $errors[] = 'Не указан ID пользователя';
    } elseif (!is_numeric($input['user_id'])) {
        $errors[] = 'ID пользователя должен быть числом';
    }

    if (empty($input['content'])) {
        $errors[] = 'Нет описания поста.';
    } else {
        $contentValidation = validateStringLength($input['content'], 1, 200);
        if ($contentValidation !== true) {
            $errors[] = $contentValidation;
        }
    }

    $imagePath = null;
    if (!empty($files['image']) && $files['image']['error'] === UPLOAD_ERR_OK) {
        $image = $files['image'];

        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($image['type'], $allowedTypes)) {
            $errors[] = 'Недопустимый тип изображения. Разрешены только JPEG, PNG';
        }

        if (empty($errors)) {
            $imageDir = 'src/images';
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $imageName = uniqid() . '.' . $extension;
            $imagePath = $imageDir . '/' . $imageName;

            if (!move_uploaded_file($image['tmp_name'], $imagePath)) {
                $errors[] = 'Ошибка при сохранении изображения';
            }
        }
    } else {
        $errors[] = 'Нет изображения.';
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

        $query = sprintf(
            "INSERT INTO post (%s) VALUES (%s)",
            implode(', ', array_keys($postData)),
            ':' . implode(', :', array_keys($postData))
        );
        // ex: INSERT INTO post (title, content) VALUES (:title, :content)

        $stmt = $connection->prepare($query);
        $stmt->execute($postData);

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'post_id' => $connection->lastInsertId(),
            'message' => 'Пост успешно создан'
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Ошибка при сохранении поста: ' . $e->getMessage()
        ]);
    }
?>
