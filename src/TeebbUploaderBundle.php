<?php

namespace Teebb\UploaderBundle;

use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Teebb\UploaderBundle\DependencyInjection\Compiler\TwigFormThemesCompilerPass;

class TeebbUploaderBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $mapping = [
            realpath(__DIR__ . '/Resources/config/doctrine-mapping') => 'Teebb\UploaderBundle\Entity'
        ];

        $namespaces = ['Teebb\UploaderBundle\Entity'];
        $dirs = [realpath(__DIR__ . '/Entity')];
        if (class_exists(DoctrineOrmMappingsPass::class)) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mapping));

//            $container->addCompilerPass(DoctrineOrmMappingsPass::createAnnotationMappingDriver($namespaces, $dirs));
        }

        if (class_exists(TwigRendererEngine::class))
        {
            $container->addCompilerPass(new TwigFormThemesCompilerPass());
        }
    }
}