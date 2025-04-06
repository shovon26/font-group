<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/FontManager.php';

header('Content-Type: application/json');

$fontId = $_POST['id'];

try {
    $fontManager = new FontManager();
    $fonts = $fontManager->getFonts();

    $fontToDelete = $fontManager->findFont($fontId);
    if (!$fontToDelete) {
        throw new Exception('Font not found');
    }

    $filePath = FONT_UPLOAD_DIR . $fontToDelete['filename'];
    if (file_exists($filePath)) {
        if (!unlink($filePath)) {
            throw new Exception('Failed to delete font file');
        }
    }
    $fontManager->deleteFont($fontId);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>