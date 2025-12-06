<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const MAX_SIZE_KB = 2048; // 2MB
    private const THUMBNAIL_WIDTH = 300;
    private const THUMBNAIL_HEIGHT = 300;
    private const MAIN_IMAGE_MAX_WIDTH = 1200;
    private const MAIN_IMAGE_MAX_HEIGHT = 1200;

    public function uploadProductImage(UploadedFile $file, ?string $oldImage = null): array
    {
        $this->validateImage($file);

        // Delete old image if exists
        if ($oldImage) {
            $this->deleteProductImage($oldImage);
        }

        // Generate unique filename
        $filename = $this->generateFilename($file);

        // Process and save main image
        $mainPath = $this->saveMainImage($file, $filename);

        // Generate and save thumbnail
        $thumbnailPath = $this->saveThumbnail($file, $filename);

        return [
            'image' => $mainPath,
            'thumbnail' => $thumbnailPath,
        ];
    }

    public function deleteProductImage(string $imagePath): bool
    {
        $deleted = false;

        // Delete main image
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
            $deleted = true;
        }

        // Delete thumbnail
        $thumbnailPath = $this->getThumbnailPath($imagePath);
        if (Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }

        return $deleted;
    }

    private function validateImage(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            throw new \InvalidArgumentException(
                'Formato de imagen no válido. Formatos permitidos: ' . implode(', ', self::ALLOWED_EXTENSIONS)
            );
        }

        $sizeKb = $file->getSize() / 1024;
        if ($sizeKb > self::MAX_SIZE_KB) {
            throw new \InvalidArgumentException(
                'La imagen excede el tamaño máximo permitido de ' . (self::MAX_SIZE_KB / 1024) . 'MB'
            );
        }
    }

    private function generateFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        return Str::uuid() . '.' . $extension;
    }

    private function saveMainImage(UploadedFile $file, string $filename): string
    {
        $image = Image::read($file->getRealPath());

        // Resize if too large, maintaining aspect ratio
        $image->scaleDown(self::MAIN_IMAGE_MAX_WIDTH, self::MAIN_IMAGE_MAX_HEIGHT);

        $path = 'products/' . $filename;

        // Encode based on extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $encoded = $this->encodeImage($image, $extension);

        Storage::disk('public')->put($path, $encoded);

        return $path;
    }

    private function saveThumbnail(UploadedFile $file, string $filename): string
    {
        $image = Image::read($file->getRealPath());

        // Cover resize for thumbnail (crop to fit)
        $image->cover(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT);

        $path = 'products/thumbnails/' . $filename;

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $encoded = $this->encodeImage($image, $extension);

        Storage::disk('public')->put($path, $encoded);

        return $path;
    }

    private function encodeImage($image, string $extension): string
    {
        return match ($extension) {
            'png' => $image->toPng()->toString(),
            'webp' => $image->toWebp(quality: 85)->toString(),
            default => $image->toJpeg(quality: 85)->toString(),
        };
    }

    private function getThumbnailPath(string $imagePath): string
    {
        $directory = dirname($imagePath);
        $filename = basename($imagePath);
        return $directory . '/thumbnails/' . $filename;
    }

    public function getImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        return Storage::disk('public')->url($path);
    }

    public function getThumbnailUrl(?string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }
        $thumbnailPath = $this->getThumbnailPath($imagePath);
        return Storage::disk('public')->url($thumbnailPath);
    }
}
