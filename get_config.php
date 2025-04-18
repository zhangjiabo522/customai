<?php
header('Content-Type: application/json');

// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => '未授权访问']);
    exit();
}

if (file_exists('config.json')) {
    $config = json_decode(file_get_contents('config.json'), true);
    echo json_encode($config);
} else {
    echo json_encode(['success' => false, 'message' => '没有找到配置文件']);
}
?>