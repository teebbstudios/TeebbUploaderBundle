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
use Teebb\UploaderBundle\Namer\PhpNamer;

class TeebbUploaderExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);


        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $fileNames = [
            'form',
            'namer',
            'storage'
        ];
        foreach ($fileNames as $configName)
        {
            $loader->load($configName.'.xml');
        }

        $uploadDir = $config['upload_dir'];
        $container->setParameter('teebb.upload.upload_dir', $uploadDir);

        $fileManagedTypeDefinition = $container->getDefinition('teebb.uploader.form.file_managed_type');
//        $fileManagedTypeDefinition->setArgument(0, $uploadDir);
        $fileManagedTypeDefinition->addMethodCall('setUploadDir', [$uploadDir]);

//        $this->registerNamers($container);
        $handlerDefinition = new Definition(UploadHandler::class);

        $handlerDefinition->setArgument(0, $uploadDir);
        $handlerDefinition->setArgument(1, new Reference('teebb.uploader.namer.hash_namer'));
        $handlerDefinition->setArgument(2, new Reference('teebb.uploader.storage.file_system_storage'));

        $container->setDefinition(UploadHandler::class, $handlerDefinition);
    }

//    private function registerNamers(ContainerBuilder $containerBuilder)
//    {
////        $namerDefinition = new Definition(PhpNamer::class);
////        $containerBuilder->setDefinition(PhpNamer::class, $namerDefinition);
//        $containerBuilder->register(PhpNamer::class, PhpNamer::class);
//    }
}