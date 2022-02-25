<?php


namespace Teebb\UploaderBundle\Storage;


use Symfony\Component\HttpFoundation\File\UploadedFile;

interface StorageInterface
{
    public function upload(UploadedFile $file, string $distDir, string $fileName): void;
}