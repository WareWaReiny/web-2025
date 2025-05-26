<?php
function logErrorsToFile(array $errors, string $filePath = 'logs/errors.log') {
    $logContent = "[" . date('Y-m-d H:i:s') . "] Ошибки:\n";
    foreach ($errors as $error) {
        $logContent .= "- $error\n";
    }
    $logContent .= "\n";

    file_put_contents($filePath, $logContent, FILE_APPEND);
}
?>