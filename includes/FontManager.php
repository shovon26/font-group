<?php
class FontManager {
    private $fonts;
    private $groups;

    public function __construct() {
        $this->loadData();
    }

    private function loadData() {
        $this->fonts = json_decode(file_get_contents(FONTS_JSON), true) ?: [];
        $this->groups = json_decode(file_get_contents(GROUPS_JSON), true) ?: [];
    }

    private function saveData() {
        file_put_contents(FONTS_JSON, json_encode($this->fonts));
        file_put_contents(GROUPS_JSON, json_encode($this->groups));
    }

    public function uploadFont($file, $fontName) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'ttf') {
            throw new Exception('Only TTF files are allowed');
        }

        $filename = uniqid() . '.ttf';
        $destination = FONT_UPLOAD_DIR . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Failed to upload font');
        }

        $font = [
            'id' => uniqid(),
            'name' => $fontName,
            'filename' => $filename,
            'uploaded_at' => date('c')
        ];

        $this->fonts[] = $font;
        $this->saveData();

        return $font;
    }

    public function findFont($fontId) {
        foreach ($this->fonts as $font) {
            if ($font['id'] === $fontId) {
                return $font;
            }
        }
        return null;
    }

    public function deleteFont($fontId) {
        $font = $this->findFont($fontId);
        if ($font) {
            $this->fonts = array_filter($this->fonts, function($f) use ($fontId) {
                return $f['id'] !== $fontId;
            });
            $this->saveData();
            return true;
        }
        return false;
    }

    public function createGroup($name, $fontIds) {
        if (count($fontIds) < 2) {
            throw new Exception('At least 2 fonts required');
        }

        $group = [
            'id' => uniqid(),
            'name' => $name,
            'fonts' => $fontIds,
            'created_at' => date('c')
        ];

        $this->groups[] = $group;
        $this->saveData();

        return $group;
    }

    public function deleteGroup($groupId) {
        $this->groups = array_filter($this->groups, function($g) use ($groupId) {
            return $g['id'] !== $groupId;
        });
        $this->saveData();
    }

    public function getFonts() {
        return $this->fonts;
    }

    public function getGroups() {
        $groups = json_decode(file_get_contents(GROUPS_JSON), true) ?: [];
        $fonts = $this->getFonts();

        return array_map(function($group) use ($fonts) {
            $group['fonts'] = array_map(function($fontData) {
                return [
                    'id' => $fontData['id'],
                    'name' => $fontData['name']
                ];
            }, $group['fonts']);
            return $group;
        }, $groups);
    }
}
?>