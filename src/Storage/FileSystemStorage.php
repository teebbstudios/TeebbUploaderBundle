<?php


namespace Teebb\UploaderBundle\Storage;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSystemStorage implements StorageInterface
{

    public function upload(UploadedFile $file, string $distDir, string $fileName): void
    {
        $file->move($distDir, $fileName);
    }
}