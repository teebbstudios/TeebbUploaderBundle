<?php


namespace Teebb\UploaderBundle\Event;


use Symfony\Component\HttpFoundation\File\UploadedFile;
use Teebb\UploaderBundle\Entity\File;

class AfterFileObjectSetPropertyEvent
{
    /**
     * @var UploadedFile|null
     */
    private $uploadedFile;
    /**
     * @var File
     */
    private $fileObject;

    public function __construct(?UploadedFile $uploadedFile, File $fileObject)
    {
        $this->uploadedFile = $uploadedFile;
        $this->fileObject = $fileObject;
    }

    /**
     * @return UploadedFile|null
     */
    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * @return File
     */
    public function getFileObject(): File
    {
        return $this->fileObject;
    }
}