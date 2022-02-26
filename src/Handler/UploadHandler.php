<?php


namespace Teebb\UploaderBundle\Handler;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Teebb\UploaderBundle\Namer\NamerInterface;
use Teebb\UploaderBundle\Storage\StorageInterface;

class UploadHandler
{
    private $distDir;

    private $namer;

    private $storage;

    public function __construct(string $distDir, NamerInterface $namer, StorageInterface $storage)
    {
        $this->distDir = $distDir;
        $this->namer = $namer;
        $this->storage = $storage;
    }

    public function upload(UploadedFile $file, string $fileName)
    {

        $this->storage->upload($file, $this->distDir, $fileName);

        return $fileName;
    }

    public function getFileName(UploadedFile $file)
    {
        return $this->namer->rename($file);
    }
}