<?php


namespace Teebb\UploaderBundle\EventSubscriber\Doctrine;


use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Teebb\UploaderBundle\Event\AfterFileObjectSetPropertyEvent;

class UploadFileSubscriber implements EventSubscriberInterface
{

    private $eventDispatcher;

    /**
     * @var array
     */
    private $handlers;

    private $container;

    public function __construct(ParameterBagInterface $parameterBag, ContainerInterface $container,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->handlers = $parameterBag->get('teebb.uploader.handlers');

        $this->eventDispatcher = $eventDispatcher;
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
            Events::postPersist,
            Events::postUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();
        $this->setFileOjbectName($object);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();
        $this->setFileOjbectName($object);
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();
        $this->uploadFile($object);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();
        $this->uploadFile($object);
    }

    private function setFileOjbectName(object $object)
    {
        $className = get_class($object);
        if (key_exists($className, $this->handlers))
        {
            $file = $object->getUploadedFile();
            if ($file){
                $handler = $this->container->get($this->handlers[$className]);
                $fileName = $handler->getFileName($file);

                $object->setFileName($fileName);

                $afterEvent = new AfterFileObjectSetPropertyEvent($file, $object);
                $this->eventDispatcher->dispatch($afterEvent);
            }
        }
    }

    private function uploadFile(object $object)
    {
        $className = get_class($object);
        if (key_exists($className, $this->handlers))
        {
            $uploadedFile = $object->getUploadedFile();
            if ($uploadedFile){
                $fileName = $object->getFileName();
                $handler = $this->container->get($this->handlers[$className]);

                $handler->upload($uploadedFile, $fileName);
            }
        }
    }
}