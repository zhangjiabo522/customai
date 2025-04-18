<?php
header('Content-Type: application/json');

// Check if user is logged in
session_start();
if (!isset($_SESSION['logged_in'])) {
    echo json_encode(['success' => false, 'message' => '未授权访问']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => '无效数据']);
    exit();
}

// Save config
file_put_contents('config.json', json_encode($data, JSON_PRETTY_PRINT));

// Update call count if not exists
if (!file_exists('cs.json')) {
    file_put_contents('cs.json', json_encode(['cs' => 0]));
}

echo json_encode(['success' => true, 'message' => '配置保存成功']);
?>