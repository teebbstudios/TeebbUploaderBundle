<?php

namespace Teebb\UploaderBundle\Form;

use App\Entity\FileManaged;
use App\Entity\SimpleFile;
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
//        dd($options);
//        $builder->addEventListener(FormEvents::SUBMIT,[$this, 'onFormSubmit']);
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options){
            /**@var UploadedFile $data**/
            $file = $event->getData();
            $originName = $file->getClientOriginalName();
            $fileName = pathinfo(htmlspecialchars($originName), PATHINFO_FILENAME) . '-' . $file->getFilename() . '.' . $file->getClientOriginalExtension();
//        $uploadPath = $this->parameterBag->get('base_path');//$this->getParameter('base_path');
//        $uploadPath = $this->parameterBag->get('teebb.upload.upload_dir');
            $mimeType = $file->getMimeType();
            $filesize = $file->getSize();

            $file->move($this->uploadDir, $fileName);

            $fileClass = $options['file_class'];
            $simpleFile = new $fileClass();
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
        });
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
//        $resolver->setDefaults([
//            // Configure your form options here
//            'file_className' => 'abc'
//        ]);

        $resolver->setDefined('file_class');
        $resolver->setRequired('file_class');
    }

    public function getParent()
    {
        return FileType::class;
    }
}
