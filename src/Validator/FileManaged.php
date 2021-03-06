<?php

namespace Teebb\UploaderBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FileManaged extends Constraint
{
    public function __construct(array $mimeTypes, $options = null, array $groups = null, $payload = null)
    {
        $this->mimeTypes = $mimeTypes;
        parent::__construct($options, $groups, $payload);
    }

    public $mimeTypes;
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'The current file "{{ name }}" mime is not allowed, only allowed "{{ mimes }}".';
}
