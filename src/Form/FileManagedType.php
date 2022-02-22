<?php

namespace Teebb\UploaderBundle\Form;

use App\Entity\FileManaged;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $builder->addEventListener(FormEvents::SUBMIT,[$this, 'onFormSubmit']);
//        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event){
//            /**@var UploadedFile $data**/
//            $file = $event->getData();
//            $originName = $file->getClientOriginalName();
//            $fileName = pathinfo(htmlspecialchars($originName), PATHINFO_FILENAME) . '-' . $file->getFilename() . '.' . $file->getClientOriginalExtension();
//            $uploadPath = $this->parameterBag->get('base_path');//$this->getParameter('base_path');
//            $mimeType = $file->getMimeType();
//            $filesize = $file->getSize();
//
//            $file->move($uploadPath, $fileName);
//
//            $fileManaged = new FileManaged();
//            $fileManaged->setOriginName($originName);
//            $fileManaged->setFileName($fileName);
//            $fileManaged->setMimeType($mimeType);
//            $fileManaged->setPath($uploadPath . '/' . $fileName);
//            $fileManaged->setFileSize($filesize);
//
//            $event->setData($fileManaged);
//        });
    }

    public function onFormSubmit(FormEvent $event){
        /**@var UploadedFile $data**/
        $file = $event->getData();
        $originName = $file->getClientOriginalName();
        $fileName = pathinfo(htmlspecialchars($originName), PATHINFO_FILENAME) . '-' . $file->getFilename() . '.' . $file->getClientOriginalExtension();
//        $uploadPath = $this->parameterBag->get('base_path');//$this->getParameter('base_path');
//        $uploadPath = $this->parameterBag->get('teebb.upload.upload_dir');
        $mimeType = $file->getMimeType();
        $filesize = $file->getSize();

        $file->move($this->uploadDir, $fileName);

//        $fileManaged = new FileManaged();
//        $fileManaged->setOriginName($originName);
//        $fileManaged->setFileName($fileName);
//        $fileManaged->setMimeType($mimeType);
//        $fileManaged->setPath($uploadPath . '/' . $fileName);
//        $fileManaged->setFileSize($filesize);
//
//        $event->setData($fileManaged);
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//            // Configure your form options here
//        ]);
//    }

    public function getParent()
    {
        return FileType::class;
    }
}
