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

    public function upload(UploadedFile $file)
    {
        // todo: 1. 重命名文件 2.move
        $fileName = $this->namer->rename($file);

        $this->storage->upload($file, $this->distDir, $fileName);

        return $fileName;
    }
}