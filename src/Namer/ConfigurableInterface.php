<?php


namespace Teebb\UploaderBundle\Namer;


interface ConfigurableInterface
{
    public function configure(array $options): void;
}