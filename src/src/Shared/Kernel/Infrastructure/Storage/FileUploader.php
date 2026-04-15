<?php

namespace StockFlow\Shared\Kernel\Infrastructure\Storage;

use StockFlow\Shared\Kernel\Domain\File\FileUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class FileUploader implements FileUploaderInterface
{
    public function __construct(
        private string $publicDir,
        private string $privateDir,
        private SluggerInterface $slugger
    ) {}

    public function upload(UploadedFile $file, string $subDirectory = '', bool $isPublic = true): string
    {
        $safeFilename = $this->slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $fileName = sprintf('%s-%s.%s', $safeFilename, uniqid(), $file->guessExtension());

        $basePath = $isPublic ? $this->publicDir : $this->privateDir;
        $prefix = $isPublic ? '/uploads/' : '/private/';

        $targetPath = rtrim($basePath, '/') . '/' . trim($subDirectory, '/');
        $file->move($targetPath, $fileName);

        return $prefix . ($subDirectory ? trim($subDirectory, '/') . '/' : '') . $fileName;
    }

    public function remove(string $filePath): void
    {
        $fullPath = null;

        if (str_starts_with($filePath, '/uploads/')) {
            $relativePath = str_replace('/uploads/', '', $filePath);
            $fullPath = rtrim($this->publicDir, '/') . '/' . ltrim($relativePath, '/');
        } elseif (str_starts_with($filePath, '/private/')) {
            $relativePath = str_replace('/private/', '', $filePath);
            $fullPath = rtrim($this->privateDir, '/') . '/' . ltrim($relativePath, '/');
        }

        if ($fullPath && file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
