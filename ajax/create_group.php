<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/FontManager.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $fontManager = new FontManager();
    $group = $fontManager->createGroup($data['name'], $data['fonts']);
    echo json_encode(['success' => true, 'group' => $group]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>