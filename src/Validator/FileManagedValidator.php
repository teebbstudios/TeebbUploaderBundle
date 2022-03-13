<?php

namespace Teebb\UploaderBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Teebb\UploaderBundle\Entity\File;

class FileManagedValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint FileManaged */
        if (null === $value || '' === $value) {
            return;
        }

        if ($value instanceof File) {
            $mimeType = $value->getMimeType();

            foreach ($constraint->mimeTypes as $allowMimeType) {
                if ($allowMimeType === $mimeType) {
                    return;
                }

                if ($discrete = strstr($allowMimeType, '/*', true)) {
                    if (strstr($mimeType, '/', true) === $discrete) {
                        return;
                    }
                }
            }
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ name }}', $value->getUploadedFile()->getClientOriginalName())
            ->setParameter('{{ mimes }}', implode(',', $constraint->mimeTypes))
            ->addViolation();
    }
}
