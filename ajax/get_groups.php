<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/FontManager.php';

header('Content-Type: application/json');

try {
    $fontManager = new FontManager();
    $groups = $fontManager->getGroups();

    $response = array_map(function($group) {
        $fonts = $group['fonts'] ?? [];

        return [
            'id' => $group['id'] ?? '',
            'name' => $group['name'] ?? '',
            'fonts' => array_map(function($font) {
                return [
                    'id' => $font['id'] ?? '',
                    'name' => $font['name'] ?? $font['id'] ?? 'Unknown'
                ];
            }, $fonts)
        ];
    }, $groups);

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
?>