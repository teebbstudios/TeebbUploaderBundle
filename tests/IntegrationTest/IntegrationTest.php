<?php


namespace Teebb\UploaderBundle\Tests\IntegrationTest;


use PHPUnit\Framework\TestCase;
use Teebb\UploaderBundle\Handler\UploadHandler;
use Teebb\UploaderBundle\Tests\TeebbUploaderKernel;

class IntegrationTest extends TestCase
{
    public function testIntegration()
    {
        $kernel = new TeebbUploaderKernel('test', true);
        $kernel->boot();

        $container = $kernel->getContainer();

        $handler = $container->get('teebb.uploader.handler.name2');
        $this->assertInstanceOf(UploadHandler::class, $handler);
    }
}