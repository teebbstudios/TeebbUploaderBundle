<?php


namespace Teebb\UploaderBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('teebb_uploader');
        if (\method_exists($builder, 'getRootNode')) {
            $rootNode = $builder->getRootNode();
        } else {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $builder->root('teebb_uploader');
        }

        $this->addHandlersSection($rootNode);

        return $builder;
    }

    private function addHandlersSection(ArrayNodeDefinition $definition)
    {
        $definition
            ->children()
                ->arrayNode('handlers')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('entity')->isRequired()->end()
                            ->scalarNode('upload_dir')->isRequired()->end()
                            ->scalarNode('package_name')->info('Used for twig asset() method second argument.')->isRequired()->end()
                            ->arrayNode('namer')
                                ->addDefaultsIfNotSet()
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function($value){
                                        return ['service'=>$value, 'options'=>[]];
                                    })
                                ->end()
                                ->children()
                                    ->scalarNode('service')->defaultValue('teebb.uploader.namer.php_namer')->end()
                                    ->variableNode('options')->defaultValue([])->end()
                                ->end()
                            ->end()
                            ->arrayNode('storage')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->enumNode('type')->values(['file_system', 'fly_system'])->defaultValue('file_system')->end()
                                    ->scalarNode('service')->defaultValue('teebb.uploader.storage.file_system_storage')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}