<?php
    $jsonGalleryData = file_get_contents('gallery.json');
    $gallery = json_decode($jsonGalleryData, true);
    if (!$gallery) {
        exit("Ошибка загрузки данных галереи!");
    }
?>