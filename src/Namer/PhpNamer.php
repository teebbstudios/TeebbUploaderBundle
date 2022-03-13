<?php


namespace Teebb\UploaderBundle\Namer;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhpNamer implements NamerInterface
{
    public function rename(UploadedFile $file): string
    {
        return pathinfo(htmlspecialchars($file->getClientOriginalName()), PATHINFO_FILENAME) . '-'
            . $file->getFilename() . '.' . $file->getClientOriginalExtension();
    }
}