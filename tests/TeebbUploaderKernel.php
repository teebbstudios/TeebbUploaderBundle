<?php

namespace Teebb\UploaderBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Kernel;

class TeebbUploaderKernel extends Kernel
{

    public function registerBundles()
    {
        return [
            new DoctrineBundle(),
            new FrameworkBundle(),
            new \Teebb\UploaderBundle\TeebbUploaderBundle(),
        ];
    }

    public function registerContainerConfiguration(\Symfony\Component\Config\Loader\LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/doctrine.yaml');
        $loader->load(__DIR__ . '/config/teebb_uploader.yaml');
    }
}