<?php
$dir = __DIR__ . "/images";

$list = array_filter(scandir($dir), function($f) {
    return preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $f);
});

$list = array_values($list);

header("Content-Type: application/json");
echo json_encode($list);
