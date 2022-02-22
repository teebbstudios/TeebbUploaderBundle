<?php


namespace Teebb\UploaderBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TeebbUploaderExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);


        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('form.xml');

        $uploadDir = $config['upload_dir'];
        $container->setParameter('teebb.upload.upload_dir', $uploadDir);

        $fileManagedTypeDefinition = $container->getDefinition('teebb.uploader.form.file_managed_type');
//        $fileManagedTypeDefinition->setArgument(0, $uploadDir);
        $fileManagedTypeDefinition->addMethodCall('setUploadDir', [$uploadDir]);
    }
}