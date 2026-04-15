<?php

namespace StockFlow\Shared\Kernel\Domain\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploaderInterface
{
    public function upload(UploadedFile $file, string $subDirectory = '', bool $isPublic = false): string;
    public function remove(string $filePath): void;
}
