<?php


namespace Teebb\UploaderBundle\EventSubscriber\Doctrine;


use App\Entity\SimpleFile;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\EventDispatcher\EventDispatcherInterface;
use Teebb\UploaderBundle\Event\AfterFileObjectSetPropertyEvent;
use Teebb\UploaderBundle\Handler\UploadHandler;

class UploadFileSubscriber implements EventSubscriberInterface
{
    /**
     * @var UploadHandler
     */
    private $uploadHandler;

    private $eventDispatcher;

    public function __construct(UploadHandler $uploadHandler, EventDispatcherInterface $eventDispatcher)
    {
        $this->uploadHandler = $uploadHandler;
        $this->eventDispatcher = $eventDispatcher;
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
        if ($object instanceof SimpleFile)
        {
            $file = $object->getUploadedFile();
            if ($file){
                $fileName = $this->uploadHandler->getFileName($file);

                $object->setFileName($fileName);

                $afterEvent = new AfterFileObjectSetPropertyEvent($file, $object);
                $this->eventDispatcher->dispatch($afterEvent);
            }
        }
    }

    private function uploadFile(object $object)
    {
        if ($object instanceof SimpleFile)
        {
            $uploadedFile = $object->getUploadedFile();
            if ($uploadedFile){
                $fileName = $object->getFileName();
                $this->uploadHandler->upload($uploadedFile, $fileName);
            }
        }
    }
}