<?php

namespace Ojs\ValidatorBundle\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HasNoHtmlValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return mixed
     */
    public function validate($value, Constraint $constraint)
    {
        if (!is_null($value) && $value !== strip_tags($value)) {
            $this->context->addViolation(
                $constraint->message,
                ['%string%' => $value]
            );
        }
    }
}
