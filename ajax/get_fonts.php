<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/FontManager.php';

header('Content-Type: application/json');

$fontManager = new FontManager();
echo json_encode($fontManager->getFonts());
?>