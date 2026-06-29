<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class ImageProcessor
{
    private ImageManager $manager;
    private string $disk = 'public';

    // Fixed max widths - height auto based on aspect ratio
    private array $sizes = [
        'small' => ['maxWidth' => 768],
        'medium' => ['maxWidth' => 1200],
        'large' => ['maxWidth' => 1600],
    ];

    // WebP quality (87 = optimal sharpness + small size)
    private int $webpQuality = 87;

    // Max file size in bytes (8MB)
    private int $maxFileSize = 8 * 1024 * 1024;

    // Allowed MIME types
    private array $allowedMimes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process and store uploaded image with WebP variants
     * - NO crop, preserves original aspect ratio
     * - Resize only if exceeds max width (no upscale)
     * - Auto orient + remove EXIF metadata
     */
    public function processAndStore(UploadedFile $file, string $folder = 'posts'): array
    {
        $this->validateFile($file);

        // Generate unique filename
        $filename = $this->generateFilename($file);

        // Read and prepare image (Intervention Image v4)
        $image = $this->manager->decodePath($file->getPathname());

        // Get original dimensions
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        // Generate all size variants (resize only if needed)
        $variants = [];
        $aspectRatio = $originalHeight / $originalWidth;

        foreach ($this->sizes as $sizeName => $config) {
            $variants[$sizeName] = $this->generateVariant(
                $image,
                $config['maxWidth'],
                $aspectRatio,
                $filename,
                $sizeName,
                $folder
            );
        }

        return [
            'filename' => $filename,
            'original_width' => $originalWidth,
            'original_height' => $originalHeight,
            'aspect_ratio' => $aspectRatio,
            'variants' => $variants,
            'webp_path' => $variants['large']['webp'],
        ];
    }

    /**
     * Delete image variants from storage
     */
    public function delete(string $filename, string $folder = 'posts'): void
    {
        foreach (array_keys($this->sizes) as $sizeName) {
            Storage::disk($this->disk)->delete("{$folder}/{$sizeName}-{$filename}.webp");
        }
    }

    /**
     * Get responsive srcset for an image
     */
    public function getSrcset(string $filename, string $folder = 'posts'): array
    {
        $srcset = [];

        foreach ($this->sizes as $sizeName => $config) {
            $webpPath = "{$folder}/{$sizeName}-{$filename}.webp";

            if (Storage::disk($this->disk)->exists($webpPath)) {
                $srcset['webp'][$sizeName] = [
                    'url' => $this->getUrl($webpPath),
                    'maxWidth' => $config['maxWidth'],
                ];
            }
        }

        return $srcset;
    }

    /**
     * Get URL for storage path
     */
    private function getUrl(string $path): string
    {
        return asset('storage/' . $path);
    }

    /**
     * Validate uploaded file
     */
    private function validateFile(UploadedFile $file): void
    {
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedMimes)) {
            throw new \InvalidArgumentException(
                "File type not allowed. Allowed: " . implode(', ', $this->allowedMimes)
            );
        }

        if ($file->getSize() > $this->maxFileSize) {
            throw new \InvalidArgumentException(
                "File too large. Max: " . ($this->maxFileSize / 1024 / 1024) . "MB"
            );
        }

        // Security: verify it's actually an image
        $imageInfo = @getimagesize($file->getPathname());
        if ($imageInfo === false || !in_array($imageInfo[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP])) {
            throw new \InvalidArgumentException("Invalid image file");
        }
    }

    /**
     * Generate unique filename
     */
    private function generateFilename(UploadedFile $file): string
    {
        return hash('xxh3', $file->getPathname() . microtime(true));
    }

    /**
     * Generate WebP variants
     * - NO cropping
     * - Preserve aspect ratio
     * - Resize only if exceeds max width (no upscale)
     */
    private function generateVariant($image, int $maxWidth, float $aspectRatio, string $filename, string $sizeName, string $folder): array
    {
        $originalWidth = $image->width();

        // Calculate target dimensions (preserve aspect ratio, no upscale)
        if ($originalWidth <= $maxWidth) {
            // Image is smaller than max - don't resize/upscale
            $targetWidth = $originalWidth;
            $targetHeight = (int) round($originalWidth * $aspectRatio);
            $resized = clone $image;
        } else {
            // Resize to max width, height auto (aspect ratio preserved)
            $targetWidth = $maxWidth;
            $targetHeight = (int) round($maxWidth * $aspectRatio);
            $resized = clone $image;
            $resized->resize($targetWidth, $targetHeight);
        }

        // Generate WebP (EXIF metadata stripped by Intervention Image)
        Storage::disk($this->disk)->makeDirectory($folder);
        $webpPath = "{$folder}/{$sizeName}-{$filename}.webp";
        $resized->encode(new WebpEncoder($this->webpQuality))->save(
            Storage::disk($this->disk)->path($webpPath)
        );

        return [
            'webp' => $webpPath,
            'width' => $targetWidth,
            'height' => $targetHeight,
        ];
    }

    /**
     * Get image info from storage
     */
    public function getImageInfo(string $filename, string $folder = 'posts'): ?array
    {
        $webpPath = "{$folder}/large-{$filename}.webp";

        if (!Storage::disk($this->disk)->exists($webpPath)) {
            return null;
        }

        return [
            'filename' => $filename,
            'folder' => $folder,
            'url' => $this->getUrl($webpPath),
        ];
    }

    /**
     * Get the largest variant URL (fallback)
     * Also checks for old format images
     */
    public function getLargestVariant(string $filename, string $folder = 'posts'): ?string
    {
        // Check new format (with size prefix)
        foreach (['large', 'medium', 'small'] as $sizeName) {
            $webpPath = "{$folder}/{$sizeName}-{$filename}.webp";
            if (Storage::disk($this->disk)->exists($webpPath)) {
                return $this->getUrl($webpPath);
            }
        }

        // Check old format (direct file)
        $oldPath = "{$folder}/{$filename}";
        if (Storage::disk($this->disk)->exists($oldPath)) {
            return $this->getUrl($oldPath);
        }

        return null;
    }

    /**
     * Get sizes configuration
     */
    public function getSizes(): array
    {
        return $this->sizes;
    }
}
