<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/FontManager.php';

header('Content-Type: application/json');

try {
    if (empty($_FILES['font'])) {
        throw new Exception('No file uploaded');
    }

    $fontManager = new FontManager();
    $fontName = $_POST['name'] ?? pathinfo($_FILES['font']['name'], PATHINFO_FILENAME);

    // Basic cleaning
    $fontName = preg_replace('/[^a-zA-Z0-9\s]/', '', $fontName);
    $fontName = ucwords(strtolower($fontName));

    $font = $fontManager->uploadFont($_FILES['font'], $fontName);
    echo json_encode(['success' => true, 'font' => $font]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>