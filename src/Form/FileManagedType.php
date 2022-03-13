<?php

namespace Teebb\UploaderBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\UploaderBundle\Entity\File;

class FileManagedType extends AbstractType
{
    private $uploadDir;

    /**
     * @param mixed $uploadDir
     */
    public function setUploadDir($uploadDir): void
    {
        $this->uploadDir = $uploadDir;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            /**@var UploadedFile|null $data * */
            $file = $event->getData();

            $fileObject = $event->getForm()->getData();

            if ($file && !$fileObject) {

                $fileClass = $options['file_class'];
                $fileObject = new $fileClass();

                $this->fillFileObject($file, $fileObject);

            } elseif ($file && $fileObject) {

                $this->fillFileObject($file, $fileObject);

            }

            $event->setData($fileObject);

        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['package_name'] = $options['package_name'];
        $view->vars['show_image'] = $options['show_image'];
        $view->vars['image_attr'] = $options['image_attr'];
    }

    private function fillFileObject(UploadedFile $file, File $fileObject)
    {
        $originName = $file->getClientOriginalName();

        $mimeType = $file->getMimeType();
        $filesize = $file->getSize();

        $fileObject->setUploadedFile($file);
        $fileObject->setOriginName($originName);
        $fileObject->setMimeType($mimeType);
        $fileObject->setFileSize($filesize);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'required' => false,
            'show_image' => false,
            'image_attr' => [ 'width' => '100px', 'class' => 'my-3']
        ]);

        $resolver->setDefined('package_name');
        $resolver->setRequired('package_name');
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
