<?php
namespace App\Core;

class FileUpload {
    private const UPLOAD_DIR = __DIR__ . '/../../public/uploads/';
    
    public static function upload(array $file, string $prefix = ''): ?string
    {
        if (empty($file['tmp_name'])) {
            return null;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . uniqid() . '.' . $extension;
        $destination = self::UPLOAD_DIR . $filename;

        if (!is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return 'uploads/' . $filename;
        }

        return null;
    }
}
