<?php

namespace Teebb\UploaderBundle\Form;


use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\UploaderBundle\Entity\File;
use Teebb\UploaderBundle\Event\AfterFileObjectSetPropertyEvent;

class FileManagedType extends AbstractType
{
//    /**
//     * @var ParameterBagInterface
//     */
//    private ParameterBagInterface $parameterBag;
//
//    public function __construct(ParameterBagInterface $parameterBag)
//    {
//        $this->parameterBag = $parameterBag;
//    }

    private $uploadDir;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param mixed $uploadDir
     */
    public function setUploadDir($uploadDir): void
    {
        $this->uploadDir = $uploadDir;
    }

//    public function __construct(string $uploadDir)
//    {
//        $this->uploadDir = $uploadDir;
//    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        dd($options);
//        $builder->addEventListener(FormEvents::SUBMIT,[$this, 'onFormSubmit']);
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            /**@var UploadedFile|null $data * */
            $file = $event->getData();

            $fileObject = $event->getForm()->getData();

//            dd($file, $fileObject);
            if ($file && !$fileObject) {

                $fileClass = $options['file_class'];
                $fileObject = new $fileClass();

                $this->fillFileObject($file, $fileObject);

            } elseif ($file && $fileObject) {

                $this->fillFileObject($file, $fileObject);

            }

            $afterEvent = new AfterFileObjectSetPropertyEvent($file, $fileObject);
            $this->eventDispatcher->dispatch($afterEvent);

            $event->setData($afterEvent->getFileObject());

        });
    }

    private function fillFileObject(UploadedFile $file, File $fileObject)
    {
        $originName = $file->getClientOriginalName();
        $fileName = pathinfo(htmlspecialchars($originName), PATHINFO_FILENAME) . '-' . $file->getFilename() . '.' . $file->getClientOriginalExtension();

        $mimeType = $file->getMimeType();
        $filesize = $file->getSize();

        $file->move($this->uploadDir, $fileName);

        $fileObject->setOriginName($originName);
        $fileObject->setFileName($fileName);
        $fileObject->setMimeType($mimeType);
        $fileObject->setFileSize($filesize);

    }

    public function onFormSubmit(FormEvent $event)
    {
        /**@var UploadedFile $data * */
        $file = $event->getData();
        $originName = $file->getClientOriginalName();
        $fileName = pathinfo(htmlspecialchars($originName), PATHINFO_FILENAME) . '-' . $file->getFilename() . '.' . $file->getClientOriginalExtension();
//        $uploadPath = $this->parameterBag->get('base_path');//$this->getParameter('base_path');
//        $uploadPath = $this->parameterBag->get('teebb.upload.upload_dir');
        $mimeType = $file->getMimeType();
        $filesize = $file->getSize();

        $file->move($this->uploadDir, $fileName);

        $simpleFile = new SimpleFile();
        $simpleFile->setOriginName($originName);
        $simpleFile->setFileName($fileName);
        $simpleFile->setMimeType($mimeType);
        $simpleFile->setFileSize($filesize);
//        $fileManaged = new FileManaged();
//        $fileManaged->setOriginName($originName);
//        $fileManaged->setFileName($fileName);
//        $fileManaged->setMimeType($mimeType);
//        $fileManaged->setPath($uploadPath . '/' . $fileName);
//        $fileManaged->setFileSize($filesize);
//
        $event->setData($simpleFile);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => null,
            'required' => false,
        ]);

        $resolver->setDefined('file_class');
        $resolver->setRequired('file_class');
    }

    public function getParent()
    {
        return FileType::class;
    }

    public function getBlockPrefix()
    {
        return 'teebb_file';
    }
}
