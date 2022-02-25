<?php


namespace Teebb\UploaderBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormThemesCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $resources = $container->getParameter('twig.form.resources');
        $form_themes = array_merge($resources, ['@TeebbUploader/form/_uploader_file.html.twig']);

        $engineDefinition = $container->getDefinition('twig.form.engine');
        $engineDefinition->setArgument(0, $form_themes);
    }
}