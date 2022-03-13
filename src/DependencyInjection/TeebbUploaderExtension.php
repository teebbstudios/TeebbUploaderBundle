<?php


namespace Teebb\UploaderBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Teebb\UploaderBundle\Handler\UploadHandler;

class TeebbUploaderExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);


        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $fileNames = [
            'doctrine',
            'form',
            'namer',
            'storage',
        ];
        foreach ($fileNames as $configName) {
            $loader->load($configName . '.xml');
        }

        $this->registerHandlers($container, $config['handlers']);
    }

    private function registerHandlers(ContainerBuilder $containerBuilder, array $handlers)
    {
        $entityHandlers = [];
        foreach ($handlers as $handlerName => $values) {
            $id = sprintf('%s.%s', 'teebb.uploader.handler', $handlerName);

            $namerServiceId = $values['namer']['service'];
            $options = $values['namer']['options'];
            if (!empty($options)) {
                $namerDefinition = $containerBuilder->getDefinition($namerServiceId);
                $namerDefinition->addMethodCall('configure', [$options]);
            }

            $storageServiceType = $values['storage']['type'];

            if ($storageServiceType == 'file_system') {
                $storageServiceId = $values['storage']['service'];
            } elseif ($storageServiceType == 'fly_system') {
                $flySystemServiceId = $values['storage']['service'];
                if (false === strpos($flySystemServiceId, 'oneup_flysystem.')){
                    $flySystemServiceId = sprintf('%s.%s',
                        'oneup_flysystem',$flySystemServiceId.'_filesystem');
                }

                $storageServiceId = 'teebb.uploader.storage.fly_system_storage';
                $storageDefinition = $containerBuilder->getDefinition($storageServiceId);
                $storageDefinition->setArgument(0, new Reference($flySystemServiceId));
            }

            $handlerDefinition = new Definition(UploadHandler::class);

            $handlerDefinition->setArgument(0, $values['upload_dir']);
            $handlerDefinition->setArgument(1, new Reference($namerServiceId));
            $handlerDefinition->setArgument(2, new Reference($storageServiceId));
            $handlerDefinition->setPublic(true);

            $containerBuilder->setDefinition($id, $handlerDefinition);

            $entity = $values['entity'];
            $entityHandlers[$entity] = $id;
        }

        $containerBuilder->setParameter('teebb.uploader.handlers', $entityHandlers);
    }
}