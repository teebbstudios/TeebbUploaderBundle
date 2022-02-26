<?php


namespace Teebb\UploaderBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NamerCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('teebb.namer') as $serviceId => $tags) {
            $definition = $container->getDefinition($serviceId);
//            $definition->addMethodCall('xxxxxxx');
        };
    }
}