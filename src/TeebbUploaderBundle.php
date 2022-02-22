<?php

namespace Teebb\UploaderBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;

class TeebbUploaderBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $mapping = [
            realpath(__DIR__ .'/Resources/config/doctrine-mapping') => 'Teebb\UploaderBundle\Entity'
        ];
        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mapping));
    }
}