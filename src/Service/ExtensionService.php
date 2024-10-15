<?php

namespace App\Service;

class ExtensionService
{
    public function isValidVideoFile(?string $extension): bool
    {
        $allowedExtensions = ['mp4', 'avi', 'mov'];
        return in_array(strtolower($extension), $allowedExtensions);
    }

    public function isValidImageFile(?string $extension): bool
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        return in_array(strtolower($extension), $allowedExtensions);
    }
}