<?php


namespace Teebb\UploaderBundle\Storage;


use League\Flysystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FlySystemStorage implements StorageInterface
{
    private $fileSystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->fileSystem = $filesystem;
    }

    public function upload(UploadedFile $file, string $distDir, string $fileName): void
    {
        $stream = fopen($file->getPathname(), 'r');
        //这里以流的方式上传
        $result = $this->fileSystem->writeStream(
            $distDir . \DIRECTORY_SEPARATOR . $fileName,
            $stream
        );

        if ($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $fileName));
        }

        if (is_resource($stream)) {
            fclose($stream);
        }
    }
}