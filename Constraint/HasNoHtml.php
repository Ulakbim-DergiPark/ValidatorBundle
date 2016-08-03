<?php

namespace Ojs\ValidatorBundle\Constraint;

use Symfony\Component\Validator\Constraint;

class HasNoHtml extends Constraint
{
    public $message = 'The string "%string%" contains HTML tags.';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return get_class($this).'Validator';
    }
}
