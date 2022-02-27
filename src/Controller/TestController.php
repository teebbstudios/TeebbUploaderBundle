<?php


namespace Teebb\UploaderBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractController
{
    public function test():Response
    {
//        $this->render()
        return new Response('hello bundle');
    }
}