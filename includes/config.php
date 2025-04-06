<?php
const FONT_UPLOAD_DIR = __DIR__ . '/../assets/fonts/uploaded/';
const FONTS_JSON = __DIR__ . '/../data/fonts.json';
const GROUPS_JSON = __DIR__ . '/../data/groups.json';

if (!file_exists(FONT_UPLOAD_DIR)) {
    mkdir(FONT_UPLOAD_DIR, 0755, true);
}
if (!file_exists(dirname(FONTS_JSON))) {
    mkdir(dirname(FONTS_JSON), 0755, true);
}

if (!file_exists(FONTS_JSON)) {
    file_put_contents(FONTS_JSON, '[]');
}
if (!file_exists(GROUPS_JSON)) {
    file_put_contents(GROUPS_JSON, '[]');
}
?>