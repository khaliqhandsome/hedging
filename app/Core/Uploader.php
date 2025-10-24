<?php

namespace Core;

class Uploader
{
    public static function upload(string $field, string $destinationDir): ?string
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES[$field];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('File upload failed');
        }

        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('underlying_', true) . ($extension ? '.' . $extension : '');
        $target = rtrim($destinationDir, '/') . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new \RuntimeException('Failed to move uploaded file');
        }

        return $filename;
    }
}
