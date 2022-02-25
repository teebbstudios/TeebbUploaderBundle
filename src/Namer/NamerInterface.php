<?php


namespace Teebb\UploaderBundle\Namer;


use Symfony\Component\HttpFoundation\File\UploadedFile;

interface NamerInterface
{
    public function rename(UploadedFile $file):string;
}